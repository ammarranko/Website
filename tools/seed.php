<?php
/**
 * Amorçage du site local du BADR.
 *
 * À exécuter avec WP-CLI depuis la racine du projet :
 *
 *     npx wp-env run cli wp eval-file wp-content/badr-project/tools/seed.php
 *
 * Ce script est idempotent : relancé, il met à jour les pages existantes
 * plutôt que d'en créer des doublons.
 *
 * Il ne touche QUE le site local. Il n'effectue aucun appel réseau et ne
 * contient aucun identifiant.
 *
 * @package BADR
 */

// Pas de declare(strict_types) ici : WP-CLI exécute ce fichier via eval(),
// ce qui interdit toute déclaration en première instruction.

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( "Ce script doit être exécuté par WP-CLI.\n" );
}

/**
 * Racine du projet, telle que montée dans le conteneur wp-env.
 *
 * wp-env ne monte que ce que .wp-env.json déclare : le dépôt complet est
 * exposé via la clé « mappings » sous wp-content/badr-project. On tente
 * d'abord ce chemin, puis quelques replis pour un usage hors conteneur.
 */
$badr_candidates = array(
	WP_CONTENT_DIR . '/badr-project',
	dirname( get_template_directory(), 3 ),
	dirname( get_template_directory(), 2 ),
);

$badr_project_root = '';

foreach ( $badr_candidates as $badr_candidate ) {
	if ( is_dir( $badr_candidate . '/content/pages' ) ) {
		$badr_project_root = $badr_candidate;
		break;
	}
}

if ( '' === $badr_project_root ) {
	WP_CLI::error(
		"Racine du projet introuvable.\n"
		. "Vérifiez que .wp-env.json contient bien :\n"
		. '  "mappings": { "wp-content/badr-project": "." }' . "\n"
		. 'puis relancez « npx wp-env start ».'
	);
}

$badr_pages_dir = $badr_project_root . '/content/pages';

// images/web = jeu curé et optimisé produit par tools/optimize-images.ps1.
// images/source = archive brute de l'ancien site, conservée pour référence mais
// jamais importée : elle contient des photos génériques hors sujet et des PNG
// de plusieurs mégaoctets.
$badr_images_dir = $badr_project_root . '/images/web';

if ( ! is_dir( $badr_images_dir ) ) {
	$badr_images_dir = $badr_project_root . '/images/source';
	WP_CLI::warning( 'images/web absent — repli sur images/source. Lancez tools/optimize-images.ps1.' );
}

if ( ! is_dir( $badr_pages_dir ) ) {
	WP_CLI::error( "Dossier de contenu introuvable : {$badr_pages_dir}" );
}

WP_CLI::log( "Racine du projet : {$badr_project_root}" );

/* -------------------------------------------------------------------------
 * 1. Réglages du site
 * ---------------------------------------------------------------------- */

update_option( 'blogname', 'B.A.D.R' );
update_option( 'blogdescription', "Ici, chacun trouve sa place" );
update_option( 'timezone_string', 'America/Toronto' );
update_option( 'start_of_week', 1 );
update_option( 'permalink_structure', '/%postname%/' );

// Le site local ne doit jamais être indexé.
update_option( 'blog_public', 0 );

WP_CLI::success( 'Réglages du site appliqués (indexation désactivée).' );

/* -------------------------------------------------------------------------
 * 2. Import des médias
 * ---------------------------------------------------------------------- */

/**
 * Importe une image dans la médiathèque une seule fois.
 *
 * @param string $path Chemin absolu du fichier source.
 * @return int ID de la pièce jointe, ou 0 en cas d'échec.
 */
function badr_import_image( string $path ): int {
	$filename = basename( $path );

	// Déjà importée ? On réutilise.
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_badr_source_filename',
					'value'   => $filename,
					'compare' => '=',
				),
			),
		)
	);

	if ( ! empty( $existing ) ) {
		return (int) $existing[0];
	}

	if ( ! is_readable( $path ) ) {
		WP_CLI::warning( "Image illisible, ignorée : {$filename}" );
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// Copie dans un fichier temporaire : media_handle_sideload déplace le fichier.
	$tmp = wp_tempnam( $filename );
	if ( ! $tmp || ! copy( $path, $tmp ) ) {
		WP_CLI::warning( "Copie temporaire impossible : {$filename}" );
		return 0;
	}

	$attachment_id = media_handle_sideload(
		array(
			'name'     => $filename,
			'tmp_name' => $tmp,
		),
		0
	);

	if ( is_wp_error( $attachment_id ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		WP_CLI::warning( "Import échoué ({$filename}) : " . $attachment_id->get_error_message() );
		return 0;
	}

	update_post_meta( $attachment_id, '_badr_source_filename', $filename );

	return (int) $attachment_id;
}

$badr_media = array();

// GLOB_BRACE n'existe pas sur toutes les compilations de PHP ; on parcourt le
// dossier et on filtre les extensions à la main, ce qui reste portable.
$badr_allowed_ext = array( 'jpg', 'jpeg', 'png', 'webp' );

if ( is_dir( $badr_images_dir ) ) {
	$badr_files = array_filter(
		(array) scandir( $badr_images_dir ),
		static function ( $entry ) use ( $badr_images_dir, $badr_allowed_ext ): bool {
			if ( '.' === $entry || '..' === $entry || ! is_file( $badr_images_dir . '/' . $entry ) ) {
				return false;
			}
			$ext = strtolower( (string) pathinfo( (string) $entry, PATHINFO_EXTENSION ) );
			return in_array( $ext, $badr_allowed_ext, true );
		}
	);

	foreach ( $badr_files as $badr_entry ) {
		$badr_file = $badr_images_dir . '/' . $badr_entry;
		$badr_id   = badr_import_image( (string) $badr_file );
		if ( $badr_id ) {
			$badr_media[ basename( (string) $badr_file ) ] = array(
				'id'  => $badr_id,
				'url' => (string) wp_get_attachment_url( $badr_id ),
			);
		}
	}
	WP_CLI::success( count( $badr_media ) . ' média(s) disponibles dans la médiathèque.' );
} else {
	WP_CLI::warning( "Dossier d'images introuvable : {$badr_images_dir}" );
}

// Le logo officiel du site, jamais recadré ni recoloré.
$badr_logo = $badr_project_root . '/images/logo.jpeg';
if ( is_readable( $badr_logo ) ) {
	$badr_logo_id = badr_import_image( $badr_logo );
	if ( $badr_logo_id ) {
		set_theme_mod( 'custom_logo', $badr_logo_id );
		update_post_meta( $badr_logo_id, '_wp_attachment_image_alt', 'B.A.D.R — Bureau Associatif pour la Diversité et la Réinsertion' );
		WP_CLI::success( 'Logo officiel installé.' );
	}
}

/* -------------------------------------------------------------------------
 * 3. Pages
 *
 * L'ordre du tableau fixe l'ordre du menu principal. Les slugs reprennent
 * les chemins publics souhaités ; les anciennes URL de badr.ca sont
 * redirigées séparément (voir la carte de redirection).
 * ---------------------------------------------------------------------- */

/*
 * Quatrième valeur = libellé de menu, plus court que le titre éditorial de la
 * page. Séparer les deux est une pratique normale d'architecture de
 * l'information : le titre complet reste affiché sur la page.
 */
$badr_page_map = array(
	'accueil'                   => array( 'Accueil', 'accueil.html', false, 'Accueil' ),
	'a-propos'                  => array( 'À propos', 'a-propos.html', true, 'À propos' ),
	'milieu-de-vie'             => array( "Milieu de vie et d'entraide", 'milieu-de-vie.html', true, 'Milieu de vie' ),
	'nos-services-et-activites' => array( 'Nos services et activités', 'nos-services-et-activites.html', true, 'Services et activités' ),
	'banque-alimentaire'        => array( 'Banque alimentaire', 'banque-alimentaire.html', true, 'Banque alimentaire' ),
	'camp-de-jour'              => array( 'Camp de jour', 'camp-de-jour.html', true, 'Camp de jour' ),
	'projets-et-initiatives'    => array( 'Projets et Initiatives', 'projets-et-initiatives.html', true, 'Projets et initiatives' ),
	'temoignages'               => array( "Témoignages et histoires d'impact", 'temoignages.html', true, 'Témoignages' ),
	'implication'               => array( 'Implication / Nous joindre', 'implication.html', true, 'Nous joindre' ),
);

/**
 * Remplace les jetons d'image par les identifiants réels de la médiathèque.
 *
 * @param string                                  $content Contenu de la page.
 * @param array<string,array{id:int,url:string}>  $media   Médias importés.
 * @return string
 */
function badr_resolve_tokens( string $content, array $media ): string {
	return (string) preg_replace_callback(
		'/\{\{IMG_(ID|URL):([^}]+)\}\}/',
		static function ( array $m ) use ( $media ): string {
			$kind = $m[1];
			$file = trim( $m[2] );

			if ( ! isset( $media[ $file ] ) ) {
				WP_CLI::warning( "Jeton d'image non résolu : {$file}" );
				return 'ID' === $kind ? '0' : '';
			}

			return 'ID' === $kind
				? (string) $media[ $file ]['id']
				: esc_url_raw( $media[ $file ]['url'] );
		},
		$content
	);
}

$badr_created = array();

foreach ( $badr_page_map as $badr_slug => $badr_spec ) {
	list( $badr_title, $badr_file, $badr_in_menu, $badr_menu_label ) = $badr_spec;

	$badr_path = $badr_pages_dir . '/' . $badr_file;

	if ( ! is_readable( $badr_path ) ) {
		WP_CLI::warning( "Contenu manquant, page ignorée : {$badr_file}" );
		continue;
	}

	$badr_raw = (string) file_get_contents( $badr_path );

	// Un BOM UTF-8 en tête de fichier devient un nœud texte dans le contenu, ce
	// qui crée une ligne vide parasite avant le premier bloc. On le retire, ainsi
	// que les blancs de tête, quel que soit l'éditeur qui a écrit le fichier.
	$badr_raw = preg_replace( '/^\xEF\xBB\xBF/', '', $badr_raw );
	$badr_raw = ltrim( (string) $badr_raw );

	$badr_content = badr_resolve_tokens( $badr_raw, $badr_media );

	$badr_existing = get_page_by_path( $badr_slug, OBJECT, 'page' );

	$badr_args = array(
		'post_title'   => $badr_title,
		'post_name'    => $badr_slug,
		'post_content' => $badr_content,
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);

	if ( $badr_existing instanceof WP_Post ) {
		$badr_args['ID'] = $badr_existing->ID;
		$badr_id         = wp_update_post( $badr_args, true );
		$badr_verb       = 'mise à jour';
	} else {
		$badr_id   = wp_insert_post( $badr_args, true );
		$badr_verb = 'créée';
	}

	if ( is_wp_error( $badr_id ) ) {
		WP_CLI::warning( "Page « {$badr_title} » : " . $badr_id->get_error_message() );
		continue;
	}

	$badr_created[ $badr_slug ] = array(
		'id'      => (int) $badr_id,
		'title'   => $badr_title,
		'menu'    => $badr_menu_label,
		'in_menu' => $badr_in_menu,
	);

	WP_CLI::log( "  Page {$badr_verb} : {$badr_title} (/{$badr_slug}/)" );
}

WP_CLI::success( count( $badr_created ) . ' page(s) traitée(s).' );

/* -------------------------------------------------------------------------
 * 3 bis. Répertoire des programmes et activités
 *
 * Les familles, les espaces et les activités sont créés une fois puis
 * modifiables dans wp-admin : ce script ne réécrit jamais un contenu déjà
 * publié, il ne fait que créer ce qui manque.
 *
 * RÈGLE : seuls les champs pratiques présents dans le contenu vérifié du BADR
 * sont remplis. Horaires, adresses, prix et âges non confirmés restent VIDES —
 * le site affiche alors « À confirmer » plutôt qu'une valeur inventée.
 * ---------------------------------------------------------------------- */

/**
 * Convertit un texte en paragraphes de blocs WordPress.
 *
 * Déclarée AVANT son premier appel : WP-CLI exécute ce fichier via eval(),
 * qui ne pré-lie pas les déclarations de fonctions comme le ferait une
 * inclusion normale.
 *
 * @param string $text Texte brut, paragraphes séparés par une ligne vide.
 * @return string
 */
function badr_paragraphs( string $text ): string {
	$out = '';

	foreach ( preg_split( '/\n\s*\n/', trim( $text ) ) as $para ) {
		$para = trim( (string) $para );

		if ( '' === $para ) {
			continue;
		}

		$out .= "<!-- wp:paragraph -->\n<p>" . esc_html( $para ) . "</p>\n<!-- /wp:paragraph -->\n\n";
	}

	return $out;
}

if ( post_type_exists( 'badr_program' ) ) {

	$badr_familles = array(
		'soutien-communautaire'   => array( 'Soutien communautaire', 'Répondre aux besoins essentiels et renforcer la résilience.' ),
		'education-developpement' => array( 'Éducation et développement', 'Promouvoir l’épanouissement personnel et la confiance en soi.' ),
		'sports-loisirs'          => array( 'Sports et loisirs', 'Encourager une bonne santé physique et mentale.' ),
		'evenements-fetes'        => array( 'Événements et fêtes communautaires', 'Briser l’isolement et célébrer la diversité.' ),
	);

	$badr_espaces_tax = array(
		'parents'       => array( 'Espace Parents', 'Soutenir les parents dans leur rôle familial, en renforçant leurs compétences et en offrant un réseau de soutien.' ),
		'papas'         => array( 'Espace Papas', 'Valoriser le rôle des pères dans la famille et les encourager à s’impliquer activement dans la vie de leurs enfants.' ),
		'femmes-mamans' => array( 'Espace Femmes et Mamans', 'Soutenir les femmes dans leur développement personnel et professionnel, en renforçant leur confiance en elles.' ),
		'familles'      => array( 'Espace Familles', 'Renforcer les liens familiaux et offrir des moments de partage dans un cadre convivial.' ),
		'filles'        => array( 'Espace Filles', 'Encourager les jeunes filles à explorer leur potentiel et à développer leurs compétences.' ),
		'aines'         => array( 'Espace Aînés', 'Offrir un lieu de rencontre et d’entraide pour les aînés, afin de briser l’isolement et de favoriser leur bien-être.' ),
	);

	/**
	 * Crée un terme s'il n'existe pas, sans écraser une modification du personnel.
	 *
	 * @param string $taxonomy Taxonomie.
	 * @param string $slug     Identifiant.
	 * @param string $name     Nom affiché.
	 * @param string $desc     Description.
	 * @param int    $order    Position.
	 * @return void
	 */
	function badr_ensure_term( string $taxonomy, string $slug, string $name, string $desc, int $order ): void {
		$existing = get_term_by( 'slug', $slug, $taxonomy );

		if ( $existing instanceof WP_Term ) {
			return;
		}

		$created = wp_insert_term(
			$name,
			$taxonomy,
			array(
				'slug'        => $slug,
				'description' => $desc,
			)
		);

		if ( ! is_wp_error( $created ) ) {
			update_term_meta( (int) $created['term_id'], 'badr_order', $order );
		}
	}

	$badr_n = 0;
	foreach ( $badr_familles as $badr_slug => $badr_term ) {
		badr_ensure_term( 'badr_famille', $badr_slug, $badr_term[0], $badr_term[1], ++$badr_n );
	}

	$badr_n = 0;
	foreach ( $badr_espaces_tax as $badr_slug => $badr_term ) {
		badr_ensure_term( 'badr_espace', $badr_slug, $badr_term[0], $badr_term[1], ++$badr_n );
	}

	WP_CLI::success( 'Familles de services et espaces communautaires en place.' );

	/*
	 * Les activités.
	 *
	 * « meta » ne contient que ce qui est vérifié. Un champ absent du tableau
	 * reste vide en base et s'affichera « À confirmer » sur le site.
	 *
	 * Note : « Soccer » a été demandé explicitement par le client. Le contenu
	 * fourni mentionne « Activités sportives » sans détailler les disciplines,
	 * donc l'entrée existe mais tous ses champs pratiques restent à confirmer.
	 */
	$badr_programmes_seed = array(
		array(
			'slug'    => 'banque-alimentaire-distribution',
			'title'   => 'Banque alimentaire',
			'famille' => 'soutien-communautaire',
			'espaces' => array( 'familles', 'parents' ),
			'image'   => 'banque-alimentaire.webp',
			'order'   => 1,
			'meta'    => array(
				'summary'      => 'Distribution hebdomadaire de denrées aux familles et aux personnes à faibles revenus, sans rendez-vous.',
				'audience'     => 'Familles et individus à faibles revenus',
				'schedule'     => 'Tous les lundis, de 15 h 00 à 17 h 00',
				'location'     => 'Centre BADR, 6432, rue Jean-Talon Est (coin Langelier), Saint-Léonard',
				'registration' => 'aucune',
				'contact'      => 'panier@badr.ca · 514 324-5341',
			),
			'content' => 'Chaque semaine, nous distribuons des denrées alimentaires aux familles en situation de précarité. Notre objectif est de garantir un accès à une alimentation équilibrée et d’accompagner les familles vers une meilleure autonomie.

La banque alimentaire est aussi un lieu de socialisation, d’échange et d’intégration : on y vient chercher des provisions, on y repart souvent avec un contact, une information sur un atelier, ou l’envie de revenir comme bénévole. L’accueil se fait sans rendez-vous pendant les heures de distribution.',
		),
		array(
			'slug'    => 'don-meubles-vetements',
			'title'   => 'Don de meubles et vêtements',
			'famille' => 'soutien-communautaire',
			'espaces' => array( 'familles' ),
			'order'   => 2,
			'meta'    => array(
				'summary'      => 'Redistribution de meubles et de vêtements aux personnes et aux familles qui en ont besoin.',
				'registration' => 'info',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'S’installer dans un nouveau logement, remplacer un manteau d’hiver, équiper une chambre d’enfant : ces dépenses arrivent souvent au pire moment. Le BADR recueille des meubles et des vêtements en bon état et les redistribue aux personnes et aux familles de la communauté.

Pour donner comme pour recevoir, écrivez-nous : l’équipe vous indiquera ce qui est actuellement recherché et comment procéder.',
		),
		array(
			'slug'    => 'transport-solidaire',
			'title'   => 'Transport solidaire',
			'famille' => 'soutien-communautaire',
			'espaces' => array( 'aines', 'familles' ),
			'order'   => 3,
			'meta'    => array(
				'summary'      => 'Accompagnement au transport pour les déplacements essentiels.',
				'registration' => 'info',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Un rendez-vous médical à l’autre bout de la ville, des sacs trop lourds à rapporter, un service qui n’est pas desservi par une ligne directe : le manque de transport isole autant que le manque de moyens.

Le transport solidaire du BADR accompagne les personnes de la communauté dans leurs déplacements essentiels. Communiquez avec l’équipe pour connaître les conditions et la disponibilité.',
		),
		array(
			'slug'    => 'trousseau-scolaire',
			'title'   => 'Trousseau scolaire',
			'famille' => 'soutien-communautaire',
			'espaces' => array( 'familles', 'parents' ),
			'order'   => 4,
			'meta'    => array(
				'summary'      => 'Fournitures scolaires remises aux enfants des familles accompagnées.',
				'registration' => 'info',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'La rentrée coûte cher. Sacs, cahiers, crayons, calculatrices : la liste s’allonge chaque année et pèse lourd sur le budget des familles.

Le trousseau scolaire du BADR remet des fournitures aux enfants des familles accompagnées, pour que la rentrée commence sur un pied d’égalité avec les autres élèves de la classe. Écrivez-nous pour savoir quand la prochaine distribution est organisée.',
		),
		array(
			'slug'    => 'soutien-scolaire',
			'title'   => 'Soutien scolaire',
			'famille' => 'education-developpement',
			'espaces' => array( 'familles', 'filles' ),
			'image'   => 'espace-enfants.jpg',
			'order'   => 5,
			'meta'    => array(
				'summary'      => 'Accompagnement et tutorat pour les élèves qui ont besoin d’un coup de main.',
				'registration' => 'info',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Certains élèves décrochent non par manque de capacités, mais par manque d’un endroit calme et de quelqu’un qui prend le temps d’expliquer autrement.

Le soutien scolaire du BADR offre un accompagnement personnalisé et du tutorat, adaptés au rythme de chaque jeune. L’objectif n’est pas seulement de remonter une note : c’est de redonner confiance à un élève qui a cessé de croire qu’il pouvait y arriver.',
		),
		array(
			'slug'    => 'camp-de-jour',
			'title'   => 'Camp de jour répit parents',
			'famille' => 'education-developpement',
			'espaces' => array( 'familles', 'parents' ),
			'image'   => 'espace-jeunes.jpg',
			'order'   => 6,
			'meta'    => array(
				'summary'      => 'Un camp d’été qui occupe les enfants et donne du répit aux parents.',
				'registration' => 'bientot',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'L’été est long quand on n’a ni camp ni gardienne. Le camp de jour répit parents accueille les enfants pendant la saison estivale avec des activités éducatives, sportives et culturelles.

Pour les parents, c’est du temps pour travailler, souffler ou régler ce qui doit l’être. Pour les enfants, c’est un été qui ressemble enfin à un été. Les dates, les groupes d’âge et les modalités d’inscription sont annoncés avant chaque saison.',
		),
		array(
			'slug'    => 'programmes-educatifs',
			'title'   => 'Programmes éducatifs',
			'famille' => 'education-developpement',
			'espaces' => array( 'filles', 'familles' ),
			'image'   => 'espace-filles.jpg',
			'order'   => 7,
			'meta'    => array(
				'summary'      => 'Ateliers de leadership, de créativité et de développement personnel.',
				'registration' => 'info',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Nos programmes éducatifs travaillent ce qui ne s’enseigne pas dans un bulletin : prendre la parole devant un groupe, mener un projet à terme, se tromper sans abandonner.

Les ateliers abordent le leadership, la créativité et le développement personnel, avec des sessions de mentorat animées par des personnes qui ont un parcours à raconter. Ils s’adressent en priorité aux jeunes de la communauté.',
		),
		array(
			'slug'    => 'ateliers-formation',
			'title'   => 'Ateliers de formation',
			'famille' => 'education-developpement',
			'espaces' => array( 'femmes-mamans', 'parents', 'papas' ),
			'image'   => 'espace-femmes.jpg',
			'order'   => 8,
			'meta'    => array(
				'summary'      => 'Formations en compétences professionnelles, en parentalité et en autonomie.',
				'registration' => 'info',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Les ateliers de formation du BADR couvrent les compétences qui ouvrent des portes : parentalité positive, développement professionnel, autonomie au quotidien.

Ils se déroulent en petits groupes, dans un cadre où personne n’est jugé sur son point de départ. Plusieurs participantes et participants arrivent d’abord pour une seule séance, puis reviennent parce qu’ils y ont trouvé un réseau autant qu’un contenu.',
		),
		array(
			'slug'    => 'arts-martiaux',
			'title'   => 'Arts martiaux',
			'famille' => 'sports-loisirs',
			'espaces' => array( 'filles', 'familles' ),
			'image'   => 'espace-jeunes.jpg',
			'order'   => 9,
			'meta'    => array(
				'summary'      => 'Discipline, confiance en soi et maîtrise des émotions par la pratique martiale.',
				'registration' => 'info',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Les arts martiaux enseignent d’abord à se tenir droit — au sens propre comme au figuré. La pratique demande de la régularité, de la patience et un respect strict du partenaire.

Pour beaucoup de jeunes, c’est le premier endroit où l’effort produit un résultat visible et où la progression se mesure. Le volet intervention et prévention accompagne la pratique sportive : on y travaille la gestion des émotions autant que la technique.',
		),
		array(
			'slug'    => 'soccer',
			'title'   => 'Soccer',
			'famille' => 'sports-loisirs',
			'espaces' => array( 'familles', 'filles' ),
			'order'   => 10,
			'meta'    => array(
				'summary'      => 'Séances de soccer récréatif dans un cadre encadré et accessible.',
				'registration' => 'info',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Le soccer réunit sans avoir besoin de traduction : il suffit d’un ballon et de deux équipes pour que des jeunes qui ne se connaissaient pas jouent ensemble.

Les séances mettent l’accent sur le jeu collectif, le plaisir et l’esprit sportif plutôt que sur la compétition. Les modalités pratiques — horaire, terrain, groupes d’âge et inscription — sont confirmées avant chaque session.',
		),
		array(
			'slug'    => 'activites-sportives',
			'title'   => 'Activités sportives',
			'famille' => 'sports-loisirs',
			'espaces' => array( 'familles', 'filles', 'aines' ),
			'order'   => 11,
			'meta'    => array(
				'summary'      => 'Activités sportives et récréatives socio-éducatives pour tous les âges.',
				'registration' => 'info',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Bouger régulièrement est un des leviers les plus directs sur la santé physique et mentale — et un des premiers à disparaître quand le budget serre.

Le BADR propose des activités sportives et récréatives socio-éducatives, avec un volet intervention et prévention. Elles s’adressent à différents groupes d’âge et créent, au passage, des espaces de connexion et d’appartenance pour des personnes qui en manquent.',
		),
		array(
			'slug'    => 'sorties-communautaires',
			'title'   => 'Sorties communautaires et culturelles',
			'famille' => 'sports-loisirs',
			'espaces' => array( 'aines', 'familles', 'papas' ),
			'image'   => 'espace-aines.jpg',
			'order'   => 12,
			'meta'    => array(
				'summary'      => 'Sorties de groupe pour découvrir la ville et rompre l’isolement.',
				'registration' => 'info',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Une ville se visite mal seul, et encore moins quand on hésite sur la langue, le trajet ou le coût de l’entrée.

Les sorties communautaires et culturelles du BADR se font en groupe : musées, parcs, activités saisonnières, sorties familiales ou pères-enfants. L’objectif est double — découvrir ce que la ville offre, et rentrer avec quelques visages de plus qu’on connaît dans le quartier.',
		),
		array(
			'slug'    => 'fetes-diversite',
			'title'   => 'Fêtes de la diversité',
			'famille' => 'evenements-fetes',
			'espaces' => array( 'familles' ),
			'image'   => 'communaute-mains.jpg',
			'order'   => 13,
			'meta'    => array(
				'summary'      => 'Célébrations ouvertes à tous, où chaque culture du quartier a sa place.',
				'registration' => 'aucune',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Les Fêtes de la diversité rassemblent le quartier autour de ce qui le compose : musiques, cuisines, langues et traditions qui cohabitent à Saint-Léonard sans toujours se rencontrer.

L’événement est ouvert à toutes et à tous, sans inscription. C’est souvent le premier contact d’une famille avec le BADR — et l’occasion, pour l’organisme, de faire connaître ses services à des personnes qui ignoraient y avoir droit.',
		),
		array(
			'slug'    => 'fete-nationale',
			'title'   => 'Fête nationale du Québec',
			'famille' => 'evenements-fetes',
			'espaces' => array( 'familles' ),
			'order'   => 14,
			'meta'    => array(
				'summary'      => 'Une célébration de quartier ouverte à toute la communauté.',
				'registration' => 'aucune',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Le BADR souligne la Fête nationale du Québec avec la communauté de Saint-Léonard : un rendez-vous festif, gratuit et ouvert à tous, pensé pour que les familles arrivées récemment se sentent invitées au même titre que les autres.

La date et le programme de l’édition à venir sont annoncés dans l’agenda des événements.',
		),
		array(
			'slug'    => 'canada-day',
			'title'   => 'Canada Day',
			'famille' => 'evenements-fetes',
			'espaces' => array( 'familles' ),
			'order'   => 15,
			'meta'    => array(
				'summary'      => 'Rassemblement communautaire ouvert à toute la famille.',
				'registration' => 'aucune',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Le BADR organise un rassemblement communautaire à l’occasion de la fête du Canada : activités pour les enfants, moments de partage et rencontres entre voisins.

L’événement est gratuit et ouvert à toute la famille. La date et le programme de l’édition à venir sont annoncés dans l’agenda des événements.',
		),
		array(
			'slug'    => 'journees-thematiques',
			'title'   => 'Journées thématiques',
			'famille' => 'evenements-fetes',
			'espaces' => array( 'familles', 'femmes-mamans' ),
			'order'   => 16,
			'meta'    => array(
				'summary'      => 'Journées consacrées à un enjeu précis de la vie du quartier.',
				'registration' => 'aucune',
				'contact'      => 'info@badr.ca',
			),
			'content' => 'Certaines questions méritent qu’on y consacre une journée entière : la santé mentale, la place des femmes, la réussite scolaire, le vieillissement à domicile.

Les journées thématiques du BADR réunissent intervenants, partenaires et membres de la communauté autour d’un seul sujet, avec des ateliers, des discussions et de l’information pratique. Le thème de la prochaine journée est annoncé dans l’agenda des événements.',
		),
	);

	$badr_prog_created = 0;
	$badr_prog_skipped = 0;

	foreach ( $badr_programmes_seed as $badr_prog ) {
		$badr_found = get_posts(
			array(
				'post_type'      => 'badr_program',
				'post_status'    => 'any',
				'name'           => $badr_prog['slug'],
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		// Déjà présent : on n'écrase pas le travail éditorial du personnel.
		if ( ! empty( $badr_found ) ) {
			++$badr_prog_skipped;
			continue;
		}

		$badr_prog_id = wp_insert_post(
			array(
				'post_type'    => 'badr_program',
				'post_status'  => 'publish',
				'post_title'   => $badr_prog['title'],
				'post_name'    => $badr_prog['slug'],
				'menu_order'   => (int) $badr_prog['order'],
				'post_content' => badr_paragraphs( (string) $badr_prog['content'] ),
			),
			true
		);

		if ( is_wp_error( $badr_prog_id ) ) {
			WP_CLI::warning( "Activité « {$badr_prog['title']} » : " . $badr_prog_id->get_error_message() );
			continue;
		}

		wp_set_object_terms( (int) $badr_prog_id, $badr_prog['famille'], 'badr_famille' );
		wp_set_object_terms( (int) $badr_prog_id, $badr_prog['espaces'], 'badr_espace' );

		foreach ( $badr_prog['meta'] as $badr_mk => $badr_mv ) {
			update_post_meta( (int) $badr_prog_id, '_badr_program_' . $badr_mk, $badr_mv );
		}

		if ( ! empty( $badr_prog['image'] ) && isset( $badr_media[ $badr_prog['image'] ] ) ) {
			set_post_thumbnail( (int) $badr_prog_id, (int) $badr_media[ $badr_prog['image'] ]['id'] );
		}

		++$badr_prog_created;
	}

	WP_CLI::success( "Activités : {$badr_prog_created} créée(s), {$badr_prog_skipped} déjà présente(s) et laissée(s) intactes." );

	flush_rewrite_rules( false );
} else {
	WP_CLI::warning( 'Le plugin badr-site-core est inactif : répertoire des programmes non amorcé.' );
}

/* -------------------------------------------------------------------------
 * 4. Page d'accueil statique
 * ---------------------------------------------------------------------- */

if ( isset( $badr_created['accueil'] ) ) {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $badr_created['accueil']['id'] );
	WP_CLI::success( 'Page d\'accueil statique définie.' );
}

/* -------------------------------------------------------------------------
 * 5. Menu de navigation
 *
 * Le bloc core/navigation lit un objet wp_navigation. On le (re)génère afin
 * que le menu reflète les pages réellement créées.
 * ---------------------------------------------------------------------- */

/**
 * Cinq entrées de premier niveau, avec sous-menus.
 *
 * Les huit anciennes entrées ne tenaient pas sur une ligne et n'offraient
 * aucune hiérarchie. Les libellés de menu sont volontairement plus courts que
 * les titres de page — pratique courante d'architecture de l'information : le
 * titre éditorial complet reste intact sur la page elle-même.
 *
 * @param string $slug Slug de la page.
 * @return string Balisage du lien, ou chaîne vide si la page n'existe pas.
 */
$badr_link = static function ( string $slug ) use ( $badr_created ): string {
	if ( ! isset( $badr_created[ $slug ] ) ) {
		return '';
	}

	return sprintf(
		'<!-- wp:navigation-link {"label":"%1$s","type":"page","id":%2$d,"url":"%3$s","kind":"post-type"} /-->' . "\n",
		esc_attr( $badr_created[ $slug ]['menu'] ),
		$badr_created[ $slug ]['id'],
		esc_url( (string) get_permalink( $badr_created[ $slug ]['id'] ) )
	);
};

$badr_nav_items  = $badr_link( 'accueil' );
$badr_nav_items .= $badr_link( 'a-propos' );

// « Banque alimentaire » est une entrée de premier niveau : c'est le service le
// plus recherché, il ne doit jamais être caché dans un sous-menu.
$badr_nav_items .= $badr_link( 'banque-alimentaire' );

// Programmes et services : trois pages secondaires dans un sous-menu.
$badr_nav_items .= '<!-- wp:navigation-submenu {"label":"Programmes et services","type":"page","id":' . (int) $badr_created['nos-services-et-activites']['id'] . ',"url":"' . esc_url( (string) get_permalink( $badr_created['nos-services-et-activites']['id'] ) ) . '","kind":"post-type"} -->' . "\n";
$badr_nav_items .= $badr_link( 'nos-services-et-activites' );
$badr_nav_items .= $badr_link( 'milieu-de-vie' );
$badr_nav_items .= $badr_link( 'camp-de-jour' );
$badr_nav_items .= '<!-- /wp:navigation-submenu -->' . "\n";

// Projets et impact : projets + témoignages.
$badr_nav_items .= '<!-- wp:navigation-submenu {"label":"Projets et impact","type":"page","id":' . (int) $badr_created['projets-et-initiatives']['id'] . ',"url":"' . esc_url( (string) get_permalink( $badr_created['projets-et-initiatives']['id'] ) ) . '","kind":"post-type"} -->' . "\n";
$badr_nav_items .= $badr_link( 'projets-et-initiatives' );
$badr_nav_items .= $badr_link( 'temoignages' );
$badr_nav_items .= '<!-- /wp:navigation-submenu -->' . "\n";

// L'entrée Événements pointe vers l'archive du plugin d'événements.
$badr_nav_items .= '<!-- wp:navigation-link {"label":"Événements","type":"custom","url":"/evenements/","kind":"custom"} /-->' . "\n";

$badr_nav = get_posts(
	array(
		'post_type'      => 'wp_navigation',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
	)
);

$badr_nav_args = array(
	'post_title'   => 'Navigation principale',
	'post_content' => $badr_nav_items,
	'post_status'  => 'publish',
	'post_type'    => 'wp_navigation',
);

if ( ! empty( $badr_nav ) ) {
	$badr_nav_args['ID'] = $badr_nav[0]->ID;
	wp_update_post( $badr_nav_args );
} else {
	wp_insert_post( $badr_nav_args );
}

WP_CLI::success( 'Menu principal généré.' );

WP_CLI::log( '' );
WP_CLI::success( 'Amorçage terminé. Site : ' . home_url( '/' ) );
WP_CLI::log( 'Administration : ' . admin_url() );
WP_CLI::log( '' );
WP_CLI::warning( 'Rappel : ce site local n\'est pas indexable et ne doit jamais être déployé tel quel.' );

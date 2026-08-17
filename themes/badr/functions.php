<?php
/**
 * Amorçage du thème BADR.
 *
 * Ce thème reste volontairement visuel. Toute fonctionnalité persistante
 * (rôles, capacités, génération d'événements récurrents) vit dans le plugin
 * compagnon badr-site-core, afin qu'un changement de thème ne casse rien.
 *
 * @package BADR
 */

declare( strict_types = 1 );

namespace BADR\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const VERSION = '0.2.0';

// Chiffres d'impact : données et icônes, éditables sans toucher aux gabarits.
require_once __DIR__ . '/inc/impact.php';

// Lecture du répertoire des programmes (modèle de contenu défini par le plugin).
require_once __DIR__ . '/inc/programmes.php';

/**
 * Déclare les fonctionnalités prises en charge par le thème.
 */
function setup(): void {
	// Le fr-CA vient de WordPress ; les chaînes du thème sont traduisibles.
	load_theme_textdomain( 'badr', get_template_directory() . '/languages' );

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'search-form', 'script', 'style', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	// Recadrages pensés pour préserver le sujet des photos communautaires.
	add_image_size( 'badr-card', 720, 480, true );
	add_image_size( 'badr-event', 960, 540, true );
	add_image_size( 'badr-hero', 1920, 900, true );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );

/**
 * Charge la feuille de style et les scripts progressifs du thème.
 */
function enqueue_assets(): void {
	$dir = get_template_directory();

	wp_enqueue_style(
		'badr-style',
		get_template_directory_uri() . '/style.css',
		array(),
		(string) ( file_exists( "{$dir}/style.css" ) ? filemtime( "{$dir}/style.css" ) : VERSION )
	);

	// La navigation mobile s'appuie sur le bloc core/navigation : WordPress
	// fournit déjà le piégeage du focus, la touche Échap et aria-expanded.
	// Le thème n'ajoute que de la mise en forme, pas de script concurrent.

	// Révélation au défilement : purement décorative, donc chargée après tout.
	wp_enqueue_script(
		'badr-reveal',
		get_template_directory_uri() . '/assets/js/reveal.js',
		array(),
		(string) ( file_exists( "{$dir}/assets/js/reveal.js" ) ? filemtime( "{$dir}/assets/js/reveal.js" ) : VERSION ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );

/**
 * Ajoute la classe « js » très tôt.
 *
 * Les styles de révélation ne masquent le contenu que sous « .js », afin
 * qu'aucun texte ne devienne invisible si le JavaScript échoue ou est bloqué.
 */
function html_js_class(): void {
	echo "<script>document.documentElement.classList.add('js');</script>\n";
}
add_action( 'wp_head', __NAMESPACE__ . '\\html_js_class', 0 );

/**
 * Les neuf espaces du BADR, source unique de vérité.
 *
 * Définis ici plutôt que dupliqués dans chaque composition, afin qu'une
 * correction de libellé ou de tranche d'âge se fasse en un seul endroit.
 * Les libellés et publics visés proviennent du contenu fourni par l'organisme ;
 * aucune tranche d'âge n'est inventée.
 *
 * @return array<int,array{slug:string,mono:string,name:string,audience:string,desc:string}>
 */
function espaces(): array {
	return array(
		array(
			'slug'     => 'parents',
			'mono'     => 'PA',
			'name'     => 'Espace Parents',
			'audience' => 'Parents',
			'desc'     => 'Soutenir les parents dans leur rôle familial, en renforçant leurs compétences et en offrant un réseau de soutien.',
		),
		array(
			'slug'     => 'papas',
			'mono'     => 'PP',
			'name'     => 'Espace Papas',
			'audience' => 'Pères',
			'desc'     => "Valoriser le rôle des pères dans la famille et les encourager à s'impliquer activement dans la vie de leurs enfants.",
		),
		array(
			'slug'     => 'femmes',
			'mono'     => 'FM',
			'name'     => 'Espace Femmes / mamans',
			'audience' => 'Femmes et mamans',
			'desc'     => 'Soutenir les femmes dans leur développement personnel et professionnel, en renforçant leur confiance en elles et en leur offrant des outils pour leur autonomie.',
		),
		array(
			'slug'     => 'familles',
			'mono'     => 'FA',
			'name'     => 'Espace Familles',
			'audience' => 'Toute la famille',
			'desc'     => 'Renforcer les liens familiaux et offrir des moments de partage dans un cadre convivial.',
		),
		array(
			'slug'     => 'filles',
			'mono'     => 'FI',
			'name'     => 'Espace Filles',
			'audience' => 'Jeunes filles',
			'desc'     => 'Encourager les jeunes filles à explorer leur potentiel, renforcer leur confiance en elles et développer leurs compétences.',
		),
		array(
			'slug'     => 'aines',
			'mono'     => 'AÎ',
			'name'     => 'Espace Aînés',
			'audience' => 'Plus de 60 ans',
			'desc'     => "Offrir un lieu de rencontre et d'entraide pour les aînés, afin de briser l'isolement et de favoriser leur bien-être.",
		),
		array(
			'slug'     => 'petite-enfance',
			'mono'     => '0-5',
			'name'     => 'Espace Petite Enfance',
			'audience' => '0 à 5 ans',
			'desc'     => 'Accompagner le développement des tout-petits dans un environnement stimulant et sécurisant.',
		),
		array(
			'slug'     => 'enfants',
			'mono'     => '6-11',
			'name'     => 'Espace Enfants',
			'audience' => '6 à 11 ans',
			'desc'     => "Favoriser l'épanouissement des enfants et les aider à développer des habiletés sociales et personnelles.",
		),
		array(
			'slug'     => 'jeunes',
			'mono'     => '12-25',
			'name'     => 'Espace Jeunes',
			'audience' => '12 à 25 ans',
			'desc'     => "Offrir aux jeunes un environnement sûr pour s'exprimer, se développer et acquérir des compétences utiles pour leur avenir.",
		),
	);
}

/**
 * Rend la grille des espaces.
 *
 * @param int $heading_level Niveau de titre des cartes, pour respecter la
 *                           hiérarchie de la page hôte.
 * @return string Balisage échappé.
 */
function render_espaces_grid( int $heading_level = 3 ): string {
	$level = max( 2, min( 4, $heading_level ) );
	$out   = '<ul class="badr-espaces badr-reveal">';

	foreach ( espaces() as $espace ) {
		$out .= sprintf(
			'<li class="badr-espace badr-espace--%1$s">'
				. '<div class="badr-espace__body">'
				. '<span class="badr-espace__mono" aria-hidden="true">%2$s</span>'
				. '<div>'
				. '<h%5$d class="badr-espace__name">%3$s</h%5$d>'
				. '<span class="badr-espace__audience">%4$s</span>'
				. '<p class="badr-espace__desc">%6$s</p>'
				. '</div></div></li>',
			esc_attr( $espace['slug'] ),
			esc_html( $espace['mono'] ),
			esc_html( $espace['name'] ),
			esc_html( $espace['audience'] ),
			$level,
			esc_html( $espace['desc'] )
		);
	}

	return $out . '</ul>';
}

/**
 * Retrouve une image de la médiathèque par son nom de fichier source.
 *
 * Le nom de fichier est enregistré en méta à l'import par tools/seed.php, ce qui
 * permet aux compositions de désigner une image de façon stable, sans dépendre
 * d'un identifiant numérique qui changerait à chaque réimport.
 *
 * Définie ici et non dans une composition : un fichier de composition peut être
 * inclus plusieurs fois par requête, ce qui provoquerait une redéclaration
 * fatale de la fonction.
 *
 * @param string $filename Nom du fichier source, par ex. « espace-jeunes.jpg ».
 * @return array{url:string,srcset:string}|null Null si l'image est absente.
 */
function media_by_filename( string $filename ): ?array {
	$found = get_posts(
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

	if ( empty( $found ) ) {
		return null;
	}

	$id = (int) $found[0];

	return array(
		'url'    => (string) wp_get_attachment_image_url( $id, 'large' ),
		'srcset' => (string) wp_get_attachment_image_srcset( $id, 'large' ),
	);
}

/**
 * Le « fil » : la ligne dessinée qui relie les sections d'une page.
 *
 * Elle entre et sort toujours au même x (50 sur 100), si bien que les segments
 * de deux sections voisines se raccordent : visuellement, c'est un seul fil
 * continu qui traverse toute la page. C'est le motif signature du site.
 *
 * preserveAspectRatio="none" est volontaire : le tracé doit s'étirer sur la
 * hauteur réelle de la section, quelle qu'elle soit.
 *
 * @param string $variant Forme du segment : « ondule », « penche » ou « droit ».
 * @return string Balisage SVG décoratif.
 */
function fil( string $variant = 'ondule' ): string {
	$paths = array(
		'ondule' => 'M50 0 C 8 180, 92 340, 50 500 C 8 660, 92 820, 50 1000',
		'penche' => 'M50 0 C 90 200, 10 380, 50 560 C 84 720, 24 860, 50 1000',
		'droit'  => 'M50 0 C 62 240, 38 480, 50 700 C 58 840, 44 920, 50 1000',
	);

	$d = $paths[ $variant ] ?? $paths['ondule'];

	return '<div class="badr-fil" aria-hidden="true" data-reveal="fade">'
		. '<svg viewBox="0 0 100 1000" preserveAspectRatio="none" focusable="false">'
		. '<path d="' . esc_attr( $d ) . '" data-draw style="--b-len:1120"/>'
		. '<circle cx="50" cy="500" r="4.5"/>'
		. '</svg></div>';
}

/**
 * Les prochains événements publiés.
 *
 * Lit le type d'objet du plugin The Events Calendar lorsqu'il est actif. Si le
 * plugin est désactivé — ou si aucun événement n'a encore été saisi — la
 * fonction retourne un tableau vide et la composition affiche son état vide
 * dessiné. Aucun événement n'est jamais inventé pour remplir la page.
 *
 * @param int $limit Nombre maximal d'événements.
 * @return array<int,array{id:int,title:string,url:string,start:int,all_day:bool,venue:string,excerpt:string,thumb:int}>
 */
function upcoming_events( int $limit = 3 ): array {
	if ( ! post_type_exists( 'tribe_events' ) ) {
		return array();
	}

	$posts = get_posts(
		array(
			'post_type'      => 'tribe_events',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, $limit ),
			'meta_key'       => '_EventStartDate', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_EventStartDate',
					'value'   => current_time( 'Y-m-d H:i:s' ),
					'compare' => '>=',
					'type'    => 'DATETIME',
				),
			),
		)
	);

	$events = array();

	foreach ( $posts as $post ) {
		$start = (string) get_post_meta( $post->ID, '_EventStartDate', true );

		// Un événement sans date exploitable est ignoré plutôt qu'affiché faux.
		$timestamp = '' !== $start ? strtotime( $start ) : false;

		if ( false === $timestamp ) {
			continue;
		}

		$venue_id = (int) get_post_meta( $post->ID, '_EventVenueID', true );
		$venue    = $venue_id > 0 ? (string) get_the_title( $venue_id ) : '';

		$events[] = array(
			'id'      => $post->ID,
			'title'   => (string) get_the_title( $post ),
			'url'     => (string) get_permalink( $post ),
			'start'   => (int) $timestamp,
			'all_day' => 'yes' === get_post_meta( $post->ID, '_EventAllDay', true ),
			'venue'   => $venue,
			'excerpt' => wp_strip_all_tags( (string) get_the_excerpt( $post ) ),
			'thumb'   => (int) get_post_thumbnail_id( $post->ID ),
		);
	}

	return $events;
}

/**
 * Enregistre les catégories de compositions propres au BADR.
 */
function register_pattern_categories(): void {
	$categories = array(
		'badr-pages'         => __( 'BADR — Sections de page', 'badr' ),
		'badr-espaces'       => __( 'BADR — Espaces et milieux de vie', 'badr' ),
		'badr-evenements'    => __( 'BADR — Événements', 'badr' ),
		'badr-temoignages'   => __( 'BADR — Témoignages', 'badr' ),
		'badr-appels-action' => __( 'BADR — Appels à l\'action', 'badr' ),
	);

	foreach ( $categories as $slug => $label ) {
		register_block_pattern_category( $slug, array( 'label' => $label ) );
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_pattern_categories' );

/**
 * Retire les styles de blocs par défaut que le thème ne veut pas offrir,
 * pour éviter que le personnel casse la cohérence visuelle sans le vouloir.
 */
function curate_block_styles(): void {
	// Note : les styles de blocs du cœur (« plain » pour la citation,
	// « outline » pour le bouton) sont enregistrés en JavaScript, pas en PHP.
	// unregister_block_style() ne peut donc pas les retirer ici — cela ne
	// produirait qu'un avertissement _doing_it_wrong. S'il faut les masquer,
	// cela se fera par un script d'éditeur, pas depuis ce point d'ancrage.

	register_block_style(
		'core/button',
		array(
			'name'  => 'badr-secondary',
			'label' => __( 'Bouton secondaire BADR', 'badr' ),
		)
	);

	register_block_style(
		'core/group',
		array(
			'name'  => 'badr-band-dark',
			'label' => __( 'Bande bleu nuit', 'badr' ),
		)
	);

	register_block_style(
		'core/group',
		array(
			'name'  => 'badr-card',
			'label' => __( 'Carte BADR', 'badr' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\curate_block_styles' );

/**
 * Force le format de date canadien-français pour les dates affichées.
 *
 * Les gabarits d'événements utilisent wp_date() ; ce filtre garantit un rendu
 * cohérent (« lundi 8 septembre 2026 ») même si un réglage global change.
 */
function french_date_formats(): void {
	if ( 'fr_CA' !== get_locale() && 'fr_FR' !== get_locale() ) {
		return;
	}

	add_filter( 'option_date_format', static fn(): string => 'j F Y' );
	add_filter( 'option_time_format', static fn(): string => "G\\hi" );
}
add_action( 'init', __NAMESPACE__ . '\\french_date_formats' );

/**
 * Élimine les balises génératrices inutiles sans casser les fonctionnalités.
 */
function tidy_head(): void {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'init', __NAMESPACE__ . '\\tidy_head' );

/**
 * Ajoute rel="noopener" et une mention lisible aux liens externes du contenu.
 *
 * Sécurité : tous les liens sortants ouverts dans un nouvel onglet doivent être
 * isolés du contexte de navigation d'origine.
 *
 * @param string $content Contenu de l'article ou de la page.
 * @return string
 */
function secure_external_links( string $content ): string {
	if ( is_admin() || '' === trim( $content ) ) {
		return $content;
	}

	// Ne cible que les liens qui n'ont pas déjà un attribut rel, afin de ne
	// jamais produire deux attributs rel sur la même balise.
	return (string) preg_replace_callback(
		'/<a\s([^>]*target=["\']_blank["\'][^>]*)>/i',
		static function ( array $m ): string {
			if ( false !== stripos( $m[1], 'rel=' ) ) {
				return $m[0];
			}
			return '<a ' . $m[1] . ' rel="noopener noreferrer">';
		},
		$content
	);
}
add_filter( 'the_content', __NAMESPACE__ . '\\secure_external_links', 20 );

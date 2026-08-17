<?php
/**
 * Title: Nos espaces — parcours éditorial
 * Slug: badr/espaces-parcours
 * Categories: badr-espaces
 * Description: Les six espaces communautaires présentés comme un parcours large et vivant : six chapitres alternés avec grand visuel, numéro en filigrane, description longue, trois thèmes, activités rattachées et lien vers la page de l'espace. Remplace le sélecteur numéroté et la grille de boîtes.
 * Keywords: espaces, parents, papas, femmes, familles, filles, aînés, milieu de vie
 * Viewport Width: 1440
 *
 * Les objectifs et les activités de chaque espace proviennent du contenu
 * vérifié du BADR (« Milieu de vie et d'entraide »). Les descriptions longues
 * développent ce contenu sans annoncer d'horaire, d'adresse, de tarif ni de
 * partenariat qui n'aurait pas été fourni.
 *
 * Les activités affichées sous chaque espace viennent du répertoire éditable
 * dans wp-admin : rattacher une activité à un espace la fait apparaître ici.
 *
 * @package BADR
 */

$badr_espaces = array(
	array(
		'slug'   => 'parents',
		'name'   => 'Espace Parents',
		'badge'  => 'Parents',
		'accent' => 'var(--b-azure)',
		'image'  => 'espace-parents.jpg',
		'alt'    => 'Deux adultes et un enfant lisent un livre d’images ensemble, allongés sous une couverture',
		'intro'  => 'Être parent ne vient pas avec un mode d’emploi, et encore moins quand on élève ses enfants loin de sa famille élargie. L’Espace Parents existe pour que personne n’ait à s’en sortir seul.',
		'body'   => 'Cet espace soutient les parents dans leur rôle familial, en renforçant leurs compétences et en offrant un réseau de soutien. On y vient pour poser une question qu’on n’ose pas poser ailleurs, pour comprendre ce qui se joue chez un enfant qui change, ou simplement pour souffler une heure entre adultes qui traversent la même chose. Les ateliers abordent la parentalité positive de façon concrète : ce qu’on dit, ce qu’on fait, ce qu’on laisse passer. Les cafés causerie, eux, n’ont pas de programme — c’est leur intérêt. Beaucoup de parents arrivent pour une séance et restent parce qu’ils y ont trouvé des gens à qui parler.',
		'themes' => array(
			array( 'Accompagnement', 'Ateliers parentaux et formation en parentalité positive.' ),
			array( 'Échange', 'Café causerie et groupes de discussion entre parents.' ),
			array( 'Répit', 'Séances de relaxation pour relâcher la pression du quotidien.' ),
		),
	),
	array(
		'slug'   => 'papas',
		'name'   => 'Espace Papas',
		'badge'  => 'Pères',
		'accent' => '#1084C2',
		'image'  => 'espace-papas.jpg',
		'alt'    => 'Un père et son jeune fils rient ensemble dans un parc',
		'intro'  => 'On parle beaucoup des parents, rarement des pères en particulier. Cet espace corrige ce déséquilibre.',
		'body'   => 'L’Espace Papas valorise le rôle des pères dans la famille et les encourage à s’impliquer activement dans la vie de leurs enfants. C’est un lieu où la paternité se partage entre pères, sans jugement et sans le réflexe de faire bonne figure. Les rencontres régulières abordent les réalités du quotidien : la fatigue, l’autorité, la place qu’on prend ou qu’on n’ose pas prendre, la relation avec l’autre parent. Les ateliers interactifs prolongent la discussion, et les sorties pères-enfants offrent ce qui manque souvent le plus — du temps ensemble, hors de la maison, sans écran ni horaire à tenir.',
		'themes' => array(
			array( 'Entre pères', 'Rencontres régulières pour partager les réalités du quotidien.' ),
			array( 'Participation', 'Ateliers interactifs sur la place du père dans la famille.' ),
			array( 'Complicité', 'Sorties pères-enfants pour renforcer les liens familiaux.' ),
		),
	),
	array(
		'slug'   => 'femmes-mamans',
		'name'   => 'Espace Femmes et Mamans',
		'badge'  => 'Femmes et mamans',
		'accent' => 'var(--b-flame)',
		'image'  => 'espace-femmes.jpg',
		'alt'    => 'Plusieurs femmes travaillent ensemble autour d’une grande table, crayons et papier à la main',
		'intro'  => 'Beaucoup de femmes qui poussent la porte du BADR ont mis leurs propres projets en pause depuis des années. Cet espace sert à les reprendre.',
		'body'   => 'L’Espace Femmes et Mamans soutient les femmes dans leur développement personnel et professionnel, en renforçant leur confiance en elles et en leur offrant des outils pour leur autonomie. On y avance ensemble, à son rythme : certaines viennent chercher une formation, d’autres un réseau, d’autres encore une raison de sortir de chez elles une fois par semaine. Les groupes de soutien réunissent des femmes de tous parcours — arrivées récemment ou nées ici, jeunes mères ou grands-mères. Les formations en compétences professionnelles ouvrent des portes concrètes, et les activités de bien-être rappellent que prendre soin de soi n’est pas un luxe qu’on s’accorde une fois que tout le reste est réglé.',
		'themes' => array(
			array( 'Entraide', 'Groupes de soutien entre femmes de tous parcours.' ),
			array( 'Autonomie', 'Formation en compétences professionnelles et développement personnel.' ),
			array( 'Bien-être', 'Activités de bien-être et événements de réseautage.' ),
		),
	),
	array(
		'slug'   => 'familles',
		'name'   => 'Espace Familles',
		'badge'  => 'Toute la famille',
		'accent' => 'var(--b-amber)',
		'image'  => 'espace-petite-enfance.jpg',
		'alt'    => 'De jeunes enfants jouent avec du matériel éducatif dans un local communautaire',
		'intro'  => 'Passer du temps ensemble coûte cher. Cet espace enlève cet obstacle.',
		'body'   => 'L’Espace Familles renforce les liens familiaux et offre des moments de partage dans un cadre convivial. Les familles y viennent pour être ensemble autrement qu’à la maison, sans que la sortie devienne une dépense à calculer. Les soirées de jeux et les sorties familiales créent des souvenirs que les enfants racontent longtemps. Les ateliers de cuisine réunissent les générations autour d’une table et font circuler des recettes venues de partout dans le quartier. Les événements festifs, enfin, rassemblent tout le monde à la même occasion — c’est souvent là que des familles voisines depuis des années se parlent pour la première fois.',
		'themes' => array(
			array( 'Ensemble', 'Sorties familiales et soirées de jeux.' ),
			array( 'Autour de la table', 'Ateliers de cuisine ouverts à toute la famille.' ),
			array( 'Appartenance', 'Événements festifs qui rassemblent le quartier.' ),
		),
	),
	array(
		'slug'   => 'filles',
		'name'   => 'Espace Filles',
		'badge'  => 'Jeunes filles',
		'accent' => 'var(--b-leaf)',
		'image'  => 'espace-filles.jpg',
		'alt'    => 'Deux jeunes filles travaillent ensemble devant un ordinateur portable, cahier ouvert à côté',
		'intro'  => 'Un espace à elles, pour essayer, se tromper et recommencer sans que quelqu’un commente.',
		'body'   => 'L’Espace Filles encourage les jeunes filles à explorer leur potentiel, à renforcer leur confiance en elles et à développer leurs compétences. L’adolescence est l’âge où beaucoup de filles apprennent à se faire discrètes ; ici, on travaille exactement l’inverse. Les groupes de discussion sont conçus pour que chacune prenne sa place, y compris celles qui ne parlent jamais en classe. Les ateliers sur le leadership et la créativité donnent des occasions concrètes de mener un projet du début à la fin. Et les sessions de mentorat mettent les participantes en contact avec des femmes dont le parcours ressemble au leur — la preuve la plus efficace qu’un chemin est possible.',
		'themes' => array(
			array( 'Parole', 'Groupes de discussion où chacune prend sa place.' ),
			array( 'Leadership', 'Ateliers sur le leadership et la créativité.' ),
			array( 'Mentorat', 'Sessions de mentorat avec des modèles inspirants.' ),
		),
	),
	array(
		'slug'   => 'aines',
		'name'   => 'Espace Aînés',
		'badge'  => 'Plus de 60 ans',
		'accent' => '#8A6400',
		'image'  => 'espace-aines.jpg',
		'alt'    => 'Portrait de deux femmes aînées souriantes, côte à côte',
		'intro'  => 'L’isolement des aînés ne fait pas de bruit. C’est pour ça qu’il faut aller le chercher.',
		'body'   => 'L’Espace Aînés offre un lieu de rencontre et d’entraide pour les personnes de plus de 60 ans, afin de briser l’isolement et de favoriser leur bien-être. Les cafés-rencontres réguliers créent un rendez-vous fixe dans la semaine — pour beaucoup, c’est la seule sortie prévue, et elle change tout. Les sorties culturelles et les ateliers de bien-être maintiennent l’envie de découvrir, à un âge où l’on se fait souvent dire qu’il n’y a plus rien à découvrir. Les programmes intergénérationnels, eux, fonctionnent dans les deux sens : les aînés transmettent une mémoire du quartier que personne d’autre ne détient, et repartent avec des nouvelles d’un monde qui va vite.',
		'themes' => array(
			array( 'Lien social', 'Cafés-rencontres réguliers pour rompre l’isolement.' ),
			array( 'Découverte', 'Sorties culturelles et ateliers de bien-être.' ),
			array( 'Transmission', 'Programmes intergénérationnels avec les plus jeunes.' ),
		),
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->

<section class="badr-parcours-espaces" aria-labelledby="badr-espaces-titre">

	<div class="badr-section badr-section--tight">
		<div class="badr-shell">
			<div class="badr-head badr-head--split" data-reveal="mask">
				<div>
					<p class="badr-eyebrow">Nos espaces</p>
					<h2 class="badr-section__title" id="badr-espaces-titre">Six manières de se sentir <span class="badr-em">attendu</span></h2>
				</div>
				<div>
					<p class="badr-section__intro">Nos membres ont conçu ces espaces eux-mêmes, pour répondre aux besoins de chaque groupe de la communauté. Les activités y sont gratuites ou à faible coût, et ouvertes à toutes et à tous.</p>
				</div>
			</div>
		</div>
	</div>

	<?php foreach ( $badr_espaces as $badr_i => $badr_e ) : ?>
		<?php
		$badr_img      = \BADR\Theme\media_by_filename( $badr_e['image'] );
		$badr_programs = \BADR\Theme\programs(
			array(
				'space' => $badr_e['slug'],
				'limit' => 4,
			)
		);
		$badr_term_url = taxonomy_exists( \BADR\Theme\TAX_SPACE )
			? get_term_link( $badr_e['slug'], \BADR\Theme\TAX_SPACE )
			: '';
		$badr_term_url = is_string( $badr_term_url ) ? $badr_term_url : '';
		?>
		<article class="badr-espace-chapitre<?php echo 1 === $badr_i % 2 ? ' badr-espace-chapitre--flip' : ''; ?>"
			id="espace-<?php echo esc_attr( $badr_e['slug'] ); ?>"
			style="--b-accent: <?php echo esc_attr( $badr_e['accent'] ); ?>; --b-accent-d: <?php echo esc_attr( $badr_e['accent'] ); ?>">

			<?php /* Le fil courbe qui relie un espace au suivant. */ ?>
			<div class="badr-espace-chapitre__fil" aria-hidden="true">
				<svg viewBox="0 0 100 1000" preserveAspectRatio="none" focusable="false">
					<path d="M50 0 C 12 220, 88 420, 50 620 C 16 800, 84 900, 50 1000" data-draw style="--b-len:1120"/>
				</svg>
			</div>

			<span class="badr-espace-chapitre__folio" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $badr_i + 1 ) ); ?></span>

			<div class="badr-shell">
				<div class="badr-espace-chapitre__grid">

					<figure class="badr-espace-chapitre__media" data-reveal="clip">
						<?php if ( $badr_img ) : ?>
							<img src="<?php echo esc_url( $badr_img['url'] ); ?>"
								<?php if ( '' !== $badr_img['srcset'] ) : ?>srcset="<?php echo esc_attr( $badr_img['srcset'] ); ?>"<?php endif; ?>
								sizes="(max-width: 60rem) 92vw, 46vw"
								alt="<?php echo esc_attr( $badr_e['alt'] ); ?>" loading="lazy" decoding="async">
						<?php endif; ?>
						<figcaption class="badr-espace-chapitre__badge"><?php echo esc_html( $badr_e['badge'] ); ?></figcaption>
					</figure>

					<div class="badr-espace-chapitre__body">
						<h3 class="badr-espace-chapitre__title" data-reveal="mask"><?php echo esc_html( $badr_e['name'] ); ?></h3>
						<p class="badr-espace-chapitre__intro" data-reveal="up"><?php echo esc_html( $badr_e['intro'] ); ?></p>
						<p class="badr-espace-chapitre__text" data-reveal="up"><?php echo esc_html( $badr_e['body'] ); ?></p>

						<ul class="badr-espace-chapitre__themes" data-reveal-group>
							<?php foreach ( $badr_e['themes'] as $badr_t ) : ?>
								<li data-reveal="up">
									<strong><?php echo esc_html( $badr_t[0] ); ?></strong>
									<span><?php echo esc_html( $badr_t[1] ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>

						<?php if ( $badr_programs ) : ?>
							<div class="badr-espace-chapitre__services" data-reveal="up">
								<p class="badr-activities__label">Activités rattachées</p>
								<ul class="badr-chips">
									<?php foreach ( $badr_programs as $badr_pr ) : ?>
										<li><a href="<?php echo esc_url( $badr_pr['url'] ); ?>"><?php echo esc_html( $badr_pr['title'] ); ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>

						<?php if ( '' !== $badr_term_url ) : ?>
							<div class="badr-actions">
								<a class="badr-btn badr-btn--solid" href="<?php echo esc_url( $badr_term_url ); ?>">
									<span>Découvrir cet espace</span>
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
								</a>
							</div>
						<?php endif; ?>
					</div>

				</div>
			</div>
		</article>

		<?php if ( 2 === $badr_i ) : ?>
			<?php /* Respiration colorée au milieu du parcours. */ ?>
			<div class="badr-espace-intermede">
				<div class="badr-shell">
					<p class="badr-statement" data-reveal="mask">Six portes d’entrée, <span class="badr-em">un seul endroit.</span></p>
					<p class="badr-lead" data-reveal="up">On arrive par l’une d’elles — la banque alimentaire, un atelier, une sortie — et on découvre les cinq autres en chemin.</p>
				</div>
			</div>
		<?php endif; ?>

	<?php endforeach; ?>

</section>

<!-- /wp:html -->
</div>
<!-- /wp:group -->

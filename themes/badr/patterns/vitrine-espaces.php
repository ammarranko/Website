<?php
/**
 * Title: Nos espaces — vitrine interactive
 * Slug: badr/vitrine-espaces
 * Categories: badr-espaces
 * Description: Vitrine éditoriale des six espaces communautaires : liste de noms en grand serif à gauche, panneau généreux à droite avec photo, description et trois points thématiques. Remplace la grille de boîtes.
 * Keywords: espaces, parents, papas, femmes, familles, filles, aînés
 * Viewport Width: 1440
 *
 * Contenu : les objectifs et les activités proviennent mot pour mot du contenu
 * vérifié du BADR (« Milieu de vie et d'entraide »). Les trois points
 * thématiques reformulent ces activités réelles — ils n'annoncent aucun service
 * nouveau, aucun horaire et aucune date.
 *
 * Sans JavaScript, la classe « is-interactive » n'est jamais posée : les six
 * panneaux restent empilés et lisibles. Rien n'est caché derrière un script.
 *
 * @package BADR
 */

$badr_espaces = array(
	array(
		'slug'   => 'parents',
		'accent' => 'var(--wp--custom--espace--parents)',
		'name'   => 'Espace Parents',
		'badge'  => 'Parents',
		'lead'   => "Soutenir les parents dans leur rôle familial, en renforçant leurs compétences et en offrant un réseau de soutien. On y vient pour échanger avec d'autres parents, souffler un peu, et repartir avec des repères concrets.",
		'points' => array(
			array( 'Accompagnement', "Ateliers parentaux et formation en parentalité positive." ),
			array( 'Échange', "Café causerie et groupes de discussion entre parents." ),
			array( 'Répit', "Séances de relaxation pour relâcher la pression du quotidien." ),
		),
		'image'  => 'espace-parents.jpg',
		'alt'    => "Deux adultes et un enfant lisent un livre d'images ensemble, allongés sous une couverture",
	),
	array(
		'slug'   => 'papas',
		'accent' => 'var(--wp--custom--espace--papas)',
		'name'   => 'Espace Papas',
		'badge'  => 'Pères',
		'lead'   => "Valoriser le rôle des pères dans la famille et les encourager à s'impliquer activement dans la vie de leurs enfants. Un espace où la paternité se partage entre pères, sans jugement.",
		'points' => array(
			array( 'Entre pères', 'Rencontres régulières pour partager les réalités du quotidien.' ),
			array( 'Participation', 'Ateliers interactifs sur la place du père dans la famille.' ),
			array( 'Complicité', 'Sorties pères-enfants pour renforcer les liens familiaux.' ),
		),
		'image'  => 'espace-papas.jpg',
		'alt'    => "Un père et son jeune fils rient ensemble dans un parc",
	),
	array(
		'slug'   => 'femmes',
		'accent' => 'var(--wp--custom--espace--femmes)',
		'name'   => 'Espace Femmes et Mamans',
		'badge'  => 'Femmes et mamans',
		'lead'   => "Soutenir les femmes dans leur développement personnel et professionnel, en renforçant leur confiance en elles et en leur offrant des outils pour leur autonomie. On y avance ensemble, à son rythme.",
		'points' => array(
			array( 'Entraide', 'Groupes de soutien entre femmes de tous parcours.' ),
			array( 'Autonomie', 'Formation en compétences professionnelles et développement personnel.' ),
			array( 'Bien-être', 'Activités de bien-être et événements de réseautage.' ),
		),
		'image'  => 'espace-femmes.jpg',
		'alt'    => "Plusieurs femmes travaillent ensemble autour d'une grande table, crayons et papier à la main",
	),
	array(
		'slug'   => 'familles',
		'accent' => 'var(--wp--custom--espace--familles)',
		'name'   => 'Espace Familles',
		'badge'  => 'Toute la famille',
		'lead'   => "Renforcer les liens familiaux et offrir des moments de partage dans un cadre convivial. Les familles viennent y passer du temps ensemble, autrement qu'à la maison.",
		'points' => array(
			array( 'Ensemble', 'Sorties familiales et soirées de jeux.' ),
			array( 'Autour de la table', 'Ateliers de cuisine ouverts à toute la famille.' ),
			array( 'Appartenance', 'Événements festifs qui rassemblent le quartier.' ),
		),
		// Aucune photo retenue ne convenait sans réutiliser le modèle de l'Espace
		// Papas : ce panneau prend le visuel de marque abstrait.
		'image'  => '',
		'alt'    => '',
	),
	array(
		'slug'   => 'filles',
		'accent' => 'var(--wp--custom--espace--filles)',
		'name'   => 'Espace Filles',
		'badge'  => 'Jeunes filles',
		'lead'   => "Encourager les jeunes filles à explorer leur potentiel, renforcer leur confiance en elles et développer leurs compétences. Un espace à elles, pour essayer, se tromper et recommencer.",
		'points' => array(
			array( 'Parole', 'Groupes de discussion où chacune prend sa place.' ),
			array( 'Leadership', 'Ateliers sur le leadership et la créativité.' ),
			array( 'Mentorat', 'Sessions de mentorat avec des modèles inspirants.' ),
		),
		'image'  => 'espace-filles.jpg',
		'alt'    => "Deux jeunes filles travaillent ensemble devant un ordinateur portable, cahier ouvert à côté",
	),
	array(
		'slug'   => 'aines',
		'accent' => 'var(--wp--custom--espace--aines)',
		'name'   => 'Espace Aînés',
		'badge'  => 'Plus de 60 ans',
		'lead'   => "Offrir un lieu de rencontre et d'entraide pour les aînés, afin de briser l'isolement et de favoriser leur bien-être. La transmission va dans les deux sens : on y apprend autant qu'on y raconte.",
		'points' => array(
			array( 'Lien social', 'Cafés-rencontres réguliers pour rompre l\'isolement.' ),
			array( 'Découverte', 'Sorties culturelles et ateliers de bien-être.' ),
			array( 'Transmission', 'Programmes intergénérationnels avec les plus jeunes.' ),
		),
		'image'  => 'espace-aines.jpg',
		'alt'    => "Portrait en noir et blanc de deux femmes aînées souriantes, côte à côte",
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<section class="badr-vitrine" data-badr-vitrine aria-labelledby="badr-vitrine-titre">
	<div class="badr-vitrine__inner">

		<div class="badr-vitrine__head" data-reveal="mask">
			<p class="badr-eyebrow">Nos espaces</p>
			<h2 class="badr-section__title" id="badr-vitrine-titre">Six manières de se sentir <span class="badr-em">attendu</span></h2>
			<p class="badr-section__intro">Nos membres ont conçu ces espaces eux-mêmes, pour répondre aux besoins de chaque groupe de la communauté. Les activités y sont gratuites ou à faible coût, et ouvertes à toutes et à tous.</p>
		</div>

		<div class="badr-vitrine__grid">

			<div class="badr-vitrine__nav" role="tablist" aria-label="Les six espaces du BADR">
				<?php foreach ( $badr_espaces as $badr_i => $badr_e ) : ?>
					<button type="button"
						class="badr-vitrine__tab"
						style="--badr-accent: <?php echo esc_attr( $badr_e['accent'] ); ?>"
						role="tab"
						id="badr-tab-<?php echo esc_attr( $badr_e['slug'] ); ?>"
						aria-controls="badr-panel-<?php echo esc_attr( $badr_e['slug'] ); ?>"
						aria-selected="<?php echo 0 === $badr_i ? 'true' : 'false'; ?>"
						tabindex="<?php echo 0 === $badr_i ? '0' : '-1'; ?>">
						<span class="badr-vitrine__tab-num"><?php echo esc_html( sprintf( '%02d', $badr_i + 1 ) ); ?></span>
						<span><?php echo esc_html( $badr_e['name'] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="badr-vitrine__panels">
				<?php foreach ( $badr_espaces as $badr_i => $badr_e ) : ?>
					<?php $badr_media = '' !== $badr_e['image'] ? \BADR\Theme\media_by_filename( $badr_e['image'] ) : null; ?>
					<div class="badr-vitrine__panel"
						style="--badr-accent: <?php echo esc_attr( $badr_e['accent'] ); ?>"
						role="tabpanel"
						id="badr-panel-<?php echo esc_attr( $badr_e['slug'] ); ?>"
						aria-labelledby="badr-tab-<?php echo esc_attr( $badr_e['slug'] ); ?>"
						tabindex="0"
						<?php echo 0 === $badr_i ? '' : 'hidden'; ?>>

						<div class="badr-vitrine__media">
							<?php if ( $badr_media ) : ?>
								<img src="<?php echo esc_url( $badr_media['url'] ); ?>"
									<?php if ( '' !== $badr_media['srcset'] ) : ?>srcset="<?php echo esc_attr( $badr_media['srcset'] ); ?>"<?php endif; ?>
									sizes="(max-width: 62rem) 92vw, 52vw"
									alt="<?php echo esc_attr( $badr_e['alt'] ); ?>" loading="lazy" decoding="async">
							<?php else : ?>
								<span class="badr-vitrine__abstract" aria-hidden="true"></span>
							<?php endif; ?>
							<span class="badr-vitrine__badge"><?php echo esc_html( $badr_e['badge'] ); ?></span>
						</div>

						<h3 class="badr-vitrine__title"><?php echo esc_html( $badr_e['name'] ); ?></h3>
						<p class="badr-vitrine__lead"><?php echo esc_html( $badr_e['lead'] ); ?></p>

						<ul class="badr-vitrine__points">
							<?php foreach ( $badr_e['points'] as $badr_pt ) : ?>
								<li>
									<strong><?php echo esc_html( $badr_pt[0] ); ?></strong>
									<span><?php echo esc_html( $badr_pt[1] ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>

					</div>
				<?php endforeach; ?>
			</div>

		</div>
	</div>
</section>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

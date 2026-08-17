<?php
/**
 * Title: Espace communautaire — page d'accueil du public
 * Slug: badr/espace-hub
 * Categories: badr-espaces
 * Description: Gabarit d'un espace communautaire : accueil chaleureux avec visuel, description, thèmes de l'espace, programmes rattachés, événements à venir, contact et liens vers les autres espaces.
 * Keywords: espace, parents, papas, femmes, familles, filles, aînés
 * Viewport Width: 1440
 *
 * Alimenté par la taxonomie « badr_espace » du plugin : le personnel modifie la
 * description du terme dans wp-admin et rattache les activités à l'espace ; la
 * page se remplit toute seule.
 *
 * @package BADR
 */

$badr_term = get_queried_object();

if ( ! $badr_term instanceof WP_Term ) {
	return;
}

$badr_accent   = \BADR\Theme\term_accent( $badr_term->slug );
$badr_programs = \BADR\Theme\programs( array( 'space' => $badr_term->slug ) );
$badr_events   = \BADR\Theme\upcoming_events( 2 );
$badr_autres   = array_values(
	array_filter(
		\BADR\Theme\taxonomy_terms( \BADR\Theme\TAX_SPACE ),
		static fn( array $t ): bool => $t['slug'] !== $badr_term->slug
	)
);

// Visuel et thèmes propres à chaque espace, dérivés du contenu vérifié.
$badr_espace_meta = array(
	'parents'       => array(
		'image'  => 'espace-parents.jpg',
		'alt'    => 'Deux adultes et un enfant lisent un livre d’images ensemble, allongés sous une couverture',
		'themes' => array(
			array( 'Accompagnement', 'Ateliers parentaux et formation en parentalité positive.' ),
			array( 'Échange', 'Café causerie et groupes de discussion entre parents.' ),
			array( 'Répit', 'Séances de relaxation pour relâcher la pression du quotidien.' ),
		),
	),
	'papas'         => array(
		'image'  => 'espace-papas.jpg',
		'alt'    => 'Un père et son jeune fils rient ensemble dans un parc',
		'themes' => array(
			array( 'Entre pères', 'Rencontres régulières pour partager les réalités du quotidien.' ),
			array( 'Participation', 'Ateliers interactifs sur la place du père dans la famille.' ),
			array( 'Complicité', 'Sorties pères-enfants pour renforcer les liens familiaux.' ),
		),
	),
	'femmes-mamans' => array(
		'image'  => 'espace-femmes.jpg',
		'alt'    => 'Plusieurs femmes travaillent ensemble autour d’une grande table, crayons et papier à la main',
		'themes' => array(
			array( 'Entraide', 'Groupes de soutien entre femmes de tous parcours.' ),
			array( 'Autonomie', 'Formation en compétences professionnelles et développement personnel.' ),
			array( 'Bien-être', 'Activités de bien-être et événements de réseautage.' ),
		),
	),
	'familles'      => array(
		'image'  => 'espace-petite-enfance.jpg',
		'alt'    => 'De jeunes enfants jouent avec du matériel éducatif dans un local communautaire',
		'themes' => array(
			array( 'Ensemble', 'Sorties familiales et soirées de jeux.' ),
			array( 'Autour de la table', 'Ateliers de cuisine ouverts à toute la famille.' ),
			array( 'Appartenance', 'Événements festifs qui rassemblent le quartier.' ),
		),
	),
	'filles'        => array(
		'image'  => 'espace-filles.jpg',
		'alt'    => 'Deux jeunes filles travaillent ensemble devant un ordinateur portable, cahier ouvert à côté',
		'themes' => array(
			array( 'Parole', 'Groupes de discussion où chacune prend sa place.' ),
			array( 'Leadership', 'Ateliers sur le leadership et la créativité.' ),
			array( 'Mentorat', 'Sessions de mentorat avec des modèles inspirants.' ),
		),
	),
	'aines'         => array(
		'image'  => 'espace-aines.jpg',
		'alt'    => 'Portrait de deux femmes aînées souriantes, côte à côte',
		'themes' => array(
			array( 'Lien social', 'Cafés-rencontres réguliers pour rompre l’isolement.' ),
			array( 'Découverte', 'Sorties culturelles et ateliers de bien-être.' ),
			array( 'Transmission', 'Programmes intergénérationnels avec les plus jeunes.' ),
		),
	),
);

$badr_meta  = $badr_espace_meta[ $badr_term->slug ] ?? array( 'image' => '', 'alt' => '', 'themes' => array() );
$badr_image = '' !== $badr_meta['image'] ? \BADR\Theme\media_by_filename( $badr_meta['image'] ) : null;
?>
<!-- wp:html -->

<div class="badr-espace-hub" style="--b-accent: <?php echo esc_attr( $badr_accent ); ?>; --b-accent-d: <?php echo esc_attr( $badr_accent ); ?>">

	<header class="badr-espace-hub__hero">
		<div class="badr-shell">
			<nav class="badr-breadcrumb" aria-label="Fil d’Ariane">
				<a href="/">Accueil</a>
				<span aria-hidden="true">/</span>
				<a href="/milieu-de-vie/">Nos espaces</a>
				<span aria-hidden="true">/</span>
				<span aria-current="page"><?php echo esc_html( $badr_term->name ); ?></span>
			</nav>

			<div class="badr-espace-hub__grid">
				<div data-reveal="mask">
					<p class="badr-eyebrow">Espace communautaire</p>
					<h1 class="badr-espace-hub__title"><?php echo esc_html( $badr_term->name ); ?></h1>
					<?php if ( '' !== $badr_term->description ) : ?>
						<p class="badr-espace-hub__lead"><?php echo esc_html( $badr_term->description ); ?></p>
					<?php endif; ?>
					<div class="badr-actions">
						<a class="badr-btn badr-btn--solid" href="#badr-espace-programmes"><span>Voir les activités</span></a>
						<a class="badr-btn badr-btn--outline" href="/implication/"><span>Nous joindre</span></a>
					</div>
				</div>

				<?php if ( $badr_image ) : ?>
					<figure class="badr-espace-hub__media" data-reveal="clip">
						<img src="<?php echo esc_url( $badr_image['url'] ); ?>"
							<?php if ( '' !== $badr_image['srcset'] ) : ?>srcset="<?php echo esc_attr( $badr_image['srcset'] ); ?>"<?php endif; ?>
							sizes="(max-width: 54rem) 92vw, 42vw"
							alt="<?php echo esc_attr( $badr_meta['alt'] ); ?>" decoding="async">
					</figure>
				<?php endif; ?>
			</div>
		</div>
	</header>

	<?php if ( $badr_meta['themes'] ) : ?>
		<section class="badr-section badr-section--tight" aria-labelledby="badr-espace-themes">
			<div class="badr-shell">
				<h2 class="badr-sr-only" id="badr-espace-themes">Ce qu’on y trouve</h2>
				<ul class="badr-themes" data-reveal-group>
					<?php foreach ( $badr_meta['themes'] as $badr_i => $badr_t ) : ?>
						<li data-reveal="up" style="--b-i: <?php echo (int) $badr_i; ?>">
							<span class="badr-themes__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $badr_i + 1 ) ); ?></span>
							<strong><?php echo esc_html( $badr_t[0] ); ?></strong>
							<span><?php echo esc_html( $badr_t[1] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<section class="badr-section badr-section--wash badr-activites" id="badr-espace-programmes" aria-labelledby="badr-espace-prog-titre">
		<div class="badr-shell">
			<div class="badr-head badr-head--split" data-reveal="mask">
				<div>
					<p class="badr-eyebrow">Activités rattachées</p>
					<h2 class="badr-section__title" id="badr-espace-prog-titre">Ce qui se passe dans cet <span class="badr-em">espace</span></h2>
				</div>
				<div>
					<p class="badr-section__intro">Les activités du BADR auxquelles cet espace donne accès. Les informations pratiques non confirmées sont indiquées comme telles.</p>
					<div class="badr-head__aside" style="margin-top:1.25rem">
						<a class="badr-link" href="/nos-services-et-activites/"><span>Tout le répertoire des services</span>
							<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
						</a>
					</div>
				</div>
			</div>

			<?php if ( $badr_programs ) : ?>
				<div class="badr-activites__list" data-reveal-group>
					<?php foreach ( $badr_programs as $badr_a ) : ?>
						<?php $badr_action = \BADR\Theme\registration_action( $badr_a ); ?>
						<article class="badr-activite" data-reveal="up"
							style="--b-accent: <?php echo esc_attr( $badr_a['accent'] ); ?>; --b-accent-d: <?php echo esc_attr( $badr_a['accent'] ); ?>">
							<div class="badr-activite__media">
								<?php if ( $badr_a['thumb'] ) : ?>
									<?php echo wp_get_attachment_image( $badr_a['thumb'], 'badr-card', false, array( 'alt' => '', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php else : ?>
									<span class="badr-activite__mark" aria-hidden="true"></span>
								<?php endif; ?>
							</div>
							<div class="badr-activite__body">
								<p class="badr-activite__family"><?php echo esc_html( $badr_a['family'] ); ?></p>
								<h3 class="badr-activite__title"><a href="<?php echo esc_url( $badr_a['url'] ); ?>"><?php echo esc_html( $badr_a['title'] ); ?></a></h3>
								<?php if ( '' !== $badr_a['summary'] ) : ?>
									<p class="badr-activite__summary"><?php echo esc_html( $badr_a['summary'] ); ?></p>
								<?php endif; ?>
								<ul class="badr-activite__meta">
									<li><span<?php echo '' === $badr_a['schedule'] ? ' class="is-todo"' : ''; ?>><?php echo esc_html( (string) \BADR\Theme\practical( $badr_a['schedule'], true ) ); ?></span></li>
								</ul>
							</div>
							<div class="badr-activite__aside">
								<span class="badr-tag badr-tag--<?php echo esc_attr( $badr_a['registration'] ); ?>"><?php echo esc_html( \BADR\Theme\registration_label( $badr_a['registration'] ) ); ?></span>
								<a class="badr-link" href="<?php echo esc_url( $badr_a['url'] ); ?>"><span>Voir le programme</span>
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
								</a>
								<?php if ( $badr_action && 'ouverte' === $badr_a['registration'] ) : ?>
									<a class="badr-btn badr-btn--solid badr-btn--sm" href="<?php echo esc_url( $badr_action['url'] ); ?>"><span>S’inscrire</span></a>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="badr-empty">
					<p>Aucune activité n’est encore rattachée à cet espace. Elles s’ajoutent depuis l’administration du site.</p>
					<a class="badr-btn badr-btn--outline" href="/nos-services-et-activites/"><span>Voir toutes les activités</span></a>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $badr_events ) : ?>
		<section class="badr-section badr-section--tight badr-section--sand" aria-labelledby="badr-espace-events">
			<div class="badr-shell">
				<div class="badr-head" data-reveal="mask">
					<h2 class="badr-section__title" id="badr-espace-events">Prochains <span class="badr-em">rendez-vous</span></h2>
					<p class="badr-section__intro">Les prochaines dates inscrites à l’agenda du BADR.</p>
				</div>
				<div class="badr-events__side" data-reveal-group>
					<?php foreach ( $badr_events as $badr_e ) : ?>
						<a class="badr-event-mini" href="<?php echo esc_url( $badr_e['url'] ); ?>" data-reveal="up">
							<span class="badr-event-mini__media">
								<?php if ( $badr_e['thumb'] ) : ?>
									<?php echo wp_get_attachment_image( $badr_e['thumb'], 'badr-card', false, array( 'alt' => '', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
							</span>
							<span>
								<time class="badr-event-mini__date"><?php echo esc_html( wp_date( 'j F', $badr_e['start'] ) ); ?></time>
								<h3 class="badr-event-mini__title"><?php echo esc_html( $badr_e['title'] ); ?></h3>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="badr-section badr-section--tight" aria-labelledby="badr-espace-autres">
		<div class="badr-shell">
			<div class="badr-head" data-reveal="mask">
				<h2 class="badr-section__title" id="badr-espace-autres">Les autres <span class="badr-em">espaces</span></h2>
			</div>
			<div class="badr-espaces-liens" data-reveal-group>
				<?php foreach ( $badr_autres as $badr_o ) : ?>
					<a class="badr-espace-lien" href="<?php echo esc_url( $badr_o['url'] ); ?>" data-reveal="up"
						style="--b-accent: <?php echo esc_attr( $badr_o['accent'] ); ?>">
						<span class="badr-espace-lien__name"><?php echo esc_html( $badr_o['name'] ); ?></span>
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

</div>

<!-- /wp:html -->

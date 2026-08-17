<?php
/**
 * Title: Programme — page complète
 * Slug: badr/programme-page
 * Categories: badr-pages
 * Description: Gabarit d'une activité : fil d'Ariane, famille de services, titre, visuel, résumé, description complète, informations pratiques, inscription, contact, événements à venir et activités liées.
 * Keywords: programme, activité, inscription, détail
 * Viewport Width: 1440
 *
 * Lit l'activité de la requête principale. Toute information pratique absente
 * s'affiche « À confirmer » plutôt que d'être devinée, et le bouton
 * d'inscription n'apparaît que s'il mène quelque part.
 *
 * @package BADR
 */

$badr_post = get_queried_object();

if ( ! $badr_post instanceof WP_Post ) {
	return;
}

$badr_p      = \BADR\Theme\program_data( $badr_post );
$badr_action = \BADR\Theme\registration_action( $badr_p );
$badr_events = \BADR\Theme\upcoming_events( 2 );
$badr_liees  = $badr_p['family_slug']
	? \BADR\Theme\programs(
		array(
			'family'  => $badr_p['family_slug'],
			'exclude' => array( $badr_p['id'] ),
			'limit'   => 3,
		)
	)
	: array();

$badr_ico = static function ( string $name ): string {
	$paths = array(
		'public'  => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"/><circle cx="17" cy="9.5" r="2.4"/><path d="M15 20c0-2.6 1.8-4.6 4-4.6s2.9 1 2.9 2.6"/>',
		'horaire' => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'lieu'    => '<path d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11Z"/><circle cx="12" cy="10" r="2.6"/>',
		'age'     => '<path d="M4 18V9l8-4 8 4v9"/><path d="M4 18h16M9 18v-5h6v5"/>',
		'cout'    => '<circle cx="12" cy="12" r="8.5"/><path d="M15 9H11a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4H9M12 7v10"/>',
		'contact' => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
		'fleche'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
	);

	return '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"'
		. ' stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"'
		. ' focusable="false">' . ( $paths[ $name ] ?? '' ) . '</svg>';
};

// Les lignes pratiques : « À confirmer » est un état assumé, pas un oubli.
$badr_infos = array(
	array( 'public', 'À qui ça s’adresse', $badr_p['audience'] ),
	array( 'age', 'Groupe d’âge', $badr_p['ages'] ),
	array( 'horaire', 'Horaire', $badr_p['schedule'] ),
	array( 'lieu', 'Lieu', $badr_p['location'] ),
	array( 'cout', 'Coût', $badr_p['price'] ),
	array( 'contact', 'Contact', $badr_p['contact'] ),
);
?>
<!-- wp:html -->

<article class="badr-programme" style="--b-accent: <?php echo esc_attr( $badr_p['accent'] ); ?>; --b-accent-d: <?php echo esc_attr( $badr_p['accent'] ); ?>">

	<header class="badr-programme__hero">
		<div class="badr-shell">

			<nav class="badr-breadcrumb" aria-label="Fil d’Ariane">
				<a href="/">Accueil</a>
				<span aria-hidden="true">/</span>
				<a href="/nos-services-et-activites/">Services et activités</a>
				<?php if ( '' !== $badr_p['family'] ) : ?>
					<span aria-hidden="true">/</span>
					<a href="/nos-services-et-activites/#badr-activites"><?php echo esc_html( $badr_p['family'] ); ?></a>
				<?php endif; ?>
				<span aria-hidden="true">/</span>
				<span aria-current="page"><?php echo esc_html( $badr_p['title'] ); ?></span>
			</nav>

			<div class="badr-programme__hero-grid">
				<div data-reveal="mask">
					<?php if ( '' !== $badr_p['family'] ) : ?>
						<p class="badr-eyebrow"><?php echo esc_html( $badr_p['family'] ); ?></p>
					<?php endif; ?>

					<h1 class="badr-programme__title"><?php echo esc_html( $badr_p['title'] ); ?></h1>

					<?php if ( '' !== $badr_p['summary'] ) : ?>
						<p class="badr-programme__summary"><?php echo esc_html( $badr_p['summary'] ); ?></p>
					<?php endif; ?>

					<div class="badr-programme__badges">
						<span class="badr-tag badr-tag--<?php echo esc_attr( $badr_p['registration'] ); ?>">
							<?php echo esc_html( \BADR\Theme\registration_label( $badr_p['registration'] ) ); ?>
						</span>
						<?php foreach ( $badr_p['spaces'] as $badr_sp ) : ?>
							<a class="badr-tag badr-tag--espace" href="<?php echo esc_url( (string) get_term_link( $badr_sp['slug'], \BADR\Theme\TAX_SPACE ) ); ?>">
								<?php echo esc_html( $badr_sp['name'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>

					<?php if ( $badr_action ) : ?>
						<div class="badr-actions">
							<a class="badr-btn badr-btn--solid" href="<?php echo esc_url( $badr_action['url'] ); ?>"<?php echo 0 === strpos( $badr_action['url'], 'http' ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
								<span><?php echo esc_html( $badr_action['label'] ); ?></span>
								<?php echo $badr_ico( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( $badr_p['thumb'] ) : ?>
					<figure class="badr-programme__media" data-reveal="clip">
						<?php
						echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							$badr_p['thumb'],
							'badr-event',
							false,
							array(
								'loading'  => 'eager',
								'decoding' => 'async',
								'sizes'    => '(max-width: 54rem) 92vw, 40vw',
								'alt'      => '',
							)
						);
						?>
					</figure>
				<?php else : ?>
					<div class="badr-programme__mark" aria-hidden="true" data-reveal="rise"></div>
				<?php endif; ?>
			</div>

		</div>
	</header>

	<div class="badr-section badr-section--tight">
		<div class="badr-shell">
			<div class="badr-programme__body">

				<div class="badr-programme__prose" data-reveal="up">
					<?php
					// Le contenu vient de l'éditeur de blocs : le personnel le
					// modifie dans wp-admin comme n'importe quelle page.
					echo apply_filters( 'the_content', $badr_post->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>

				<aside class="badr-programme__aside" aria-label="Informations pratiques">
					<div class="badr-infobox" data-reveal="rise">
						<h2 class="badr-infobox__title">Informations pratiques</h2>

						<dl class="badr-infobox__list">
							<?php foreach ( $badr_infos as $badr_row ) : ?>
								<?php
								$badr_value = \BADR\Theme\practical( (string) $badr_row[2], true );
								$badr_todo  = '' === trim( (string) $badr_row[2] );
								?>
								<div>
									<dt><?php echo $badr_ico( $badr_row[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $badr_row[1] ); ?></span></dt>
									<dd<?php echo $badr_todo ? ' class="is-todo"' : ''; ?>><?php echo esc_html( (string) $badr_value ); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>

						<?php if ( $badr_action ) : ?>
							<a class="badr-btn badr-btn--solid badr-infobox__cta" href="<?php echo esc_url( $badr_action['url'] ); ?>"<?php echo 0 === strpos( $badr_action['url'], 'http' ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
								<span><?php echo esc_html( $badr_action['label'] ); ?></span>
							</a>
						<?php endif; ?>

						<a class="badr-link" href="/implication/">
							<span>Poser une question à l’équipe</span>
							<?php echo $badr_ico( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</div>
				</aside>

			</div>
		</div>
	</div>

	<?php if ( $badr_events ) : ?>
		<section class="badr-section badr-section--tight badr-section--sand" aria-labelledby="badr-prog-events">
			<div class="badr-shell">
				<div class="badr-head" data-reveal="mask">
					<h2 class="badr-section__title" id="badr-prog-events">Prochains <span class="badr-em">rendez-vous</span></h2>
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

	<?php if ( $badr_liees ) : ?>
		<section class="badr-section badr-section--tight" aria-labelledby="badr-prog-liees">
			<div class="badr-shell">
				<div class="badr-head" data-reveal="mask">
					<h2 class="badr-section__title" id="badr-prog-liees">Dans la même <span class="badr-em">famille</span></h2>
				</div>
				<div class="badr-activites__list" data-reveal-group>
					<?php foreach ( $badr_liees as $badr_l ) : ?>
						<article class="badr-activite" data-reveal="up"
							style="--b-accent: <?php echo esc_attr( $badr_l['accent'] ); ?>; --b-accent-d: <?php echo esc_attr( $badr_l['accent'] ); ?>">
							<div class="badr-activite__media">
								<?php if ( $badr_l['thumb'] ) : ?>
									<?php echo wp_get_attachment_image( $badr_l['thumb'], 'badr-card', false, array( 'alt' => '', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php else : ?>
									<span class="badr-activite__mark" aria-hidden="true"></span>
								<?php endif; ?>
							</div>
							<div class="badr-activite__body">
								<p class="badr-activite__family"><?php echo esc_html( $badr_l['family'] ); ?></p>
								<h3 class="badr-activite__title"><a href="<?php echo esc_url( $badr_l['url'] ); ?>"><?php echo esc_html( $badr_l['title'] ); ?></a></h3>
								<?php if ( '' !== $badr_l['summary'] ) : ?>
									<p class="badr-activite__summary"><?php echo esc_html( $badr_l['summary'] ); ?></p>
								<?php endif; ?>
							</div>
							<div class="badr-activite__aside">
								<span class="badr-tag badr-tag--<?php echo esc_attr( $badr_l['registration'] ); ?>"><?php echo esc_html( \BADR\Theme\registration_label( $badr_l['registration'] ) ); ?></span>
								<a class="badr-link" href="<?php echo esc_url( $badr_l['url'] ); ?>"><span>Voir le programme</span><?php echo $badr_ico( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

</article>

<!-- /wp:html -->

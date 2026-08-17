<?php
/**
 * Title: Événements à venir
 * Slug: badr/evenements
 * Categories: badr-evenements
 * Description: Section dynamique des prochains événements : un événement vedette en grand format avec image, date en très grande typographie et lieu, accompagné de deux aperçus. Si aucun événement n'est saisi, un état vide entièrement composé prend sa place.
 * Keywords: événements, agenda, activités, calendrier, à venir
 * Viewport Width: 1440
 *
 * Source des données : le type d'objet « tribe_events » du plugin The Events
 * Calendar, géré par le personnel dans wp-admin. Rien n'est codé en dur.
 *
 * Quand le plugin est désactivé ou qu'aucun événement futur n'existe, la
 * composition affiche un état vide dessiné plutôt qu'un faux agenda : aucune
 * date, aucun titre et aucun lieu ne sont inventés pour remplir la page.
 *
 * @package BADR
 */

$badr_events = \BADR\Theme\upcoming_events( 3 );
$badr_lead   = $badr_events[0] ?? null;
$badr_rest   = array_slice( $badr_events, 1, 2 );

/**
 * Petite icône en trait fin, cohérente avec le reste du système.
 *
 * @param string $name Identifiant.
 * @return string
 */
$badr_icon = static function ( string $name ): string {
	$paths = array(
		'lieu'   => '<path d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11Z"/><circle cx="12" cy="10" r="2.6"/>',
		'heure'  => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'fleche' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor"'
		. ' stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"'
		. ' focusable="false">' . $paths[ $name ] . '</svg>';
};
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<section class="badr-section badr-section--wash badr-events" aria-labelledby="badr-evenements-titre">
	<?php echo \BADR\Theme\fil( 'ondule' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- balisage interne. ?>

	<div class="badr-shell">

		<div class="badr-head badr-head--split" data-reveal="mask">
			<div>
				<p class="badr-eyebrow">Agenda du BADR</p>
				<h2 class="badr-section__title" id="badr-evenements-titre">Événements <span class="badr-em">à venir</span></h2>
			</div>
			<div>
				<p class="badr-section__intro">Découvrez les prochaines activités, rencontres et célébrations du B.A.D.R.</p>
				<div class="badr-head__aside" style="margin-top:1.25rem">
					<a class="badr-link" href="/evenements/">
						<span>Voir tous les événements</span>
						<?php echo $badr_icon( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				</div>
			</div>
		</div>

		<?php if ( $badr_lead ) : ?>

			<div class="badr-events__grid">

				<?php
				$badr_ts    = $badr_lead['start'];
				$badr_thumb = $badr_lead['thumb'];
				?>
				<a class="badr-event-lead" href="<?php echo esc_url( $badr_lead['url'] ); ?>" data-reveal="rise">
					<span class="badr-event-lead__media">
						<?php if ( $badr_thumb ) : ?>
							<?php
							echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sortie déjà échappée par le cœur.
								$badr_thumb,
								'badr-hero',
								false,
								array(
									'loading'  => 'lazy',
									'decoding' => 'async',
									'sizes'    => '(max-width: 62rem) 92vw, 58vw',
									'alt'      => '',
								)
							);
							?>
						<?php endif; ?>
					</span>

					<span class="badr-event-lead__body">
						<time class="badr-date" datetime="<?php echo esc_attr( wp_date( 'c', $badr_ts ) ); ?>">
							<span class="badr-date__day"><?php echo esc_html( wp_date( 'j', $badr_ts ) ); ?></span>
							<span class="badr-date__rest">
								<span class="badr-date__month"><?php echo esc_html( wp_date( 'F Y', $badr_ts ) ); ?></span>
								<span><?php echo esc_html( wp_date( 'l', $badr_ts ) ); ?></span>
							</span>
						</time>

						<h3 class="badr-event-lead__title"><?php echo esc_html( $badr_lead['title'] ); ?></h3>

						<?php if ( '' !== $badr_lead['excerpt'] ) : ?>
							<p class="badr-event-lead__excerpt"><?php echo esc_html( wp_trim_words( $badr_lead['excerpt'], 26 ) ); ?></p>
						<?php endif; ?>

						<ul class="badr-event-lead__meta">
							<?php if ( ! $badr_lead['all_day'] ) : ?>
								<li><?php echo $badr_icon( 'heure' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( wp_date( 'G\hi', $badr_ts ) ); ?></span></li>
							<?php endif; ?>
							<?php if ( '' !== $badr_lead['venue'] ) : ?>
								<li><?php echo $badr_icon( 'lieu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $badr_lead['venue'] ); ?></span></li>
							<?php endif; ?>
						</ul>

						<span class="badr-btn badr-btn--ghost" style="margin-top:.5rem;align-self:start">
							<span>Voir les détails</span>
							<?php echo $badr_icon( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					</span>
				</a>

				<div class="badr-events__side" data-reveal-group>
					<?php if ( ! empty( $badr_rest ) ) : ?>
						<?php foreach ( $badr_rest as $badr_e ) : ?>
							<a class="badr-event-mini" href="<?php echo esc_url( $badr_e['url'] ); ?>" data-reveal="up">
								<span class="badr-event-mini__media">
									<?php if ( $badr_e['thumb'] ) : ?>
										<?php
										echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											$badr_e['thumb'],
											'badr-card',
											false,
											array(
												'loading'  => 'lazy',
												'decoding' => 'async',
												'sizes'    => '8rem',
												'alt'      => '',
											)
										);
										?>
									<?php endif; ?>
								</span>
								<span>
									<time class="badr-event-mini__date" datetime="<?php echo esc_attr( wp_date( 'c', $badr_e['start'] ) ); ?>">
										<?php echo esc_html( wp_date( 'j F', $badr_e['start'] ) ); ?>
									</time>
									<h3 class="badr-event-mini__title"><?php echo esc_html( $badr_e['title'] ); ?></h3>
									<?php if ( '' !== $badr_e['venue'] ) : ?>
										<p class="badr-event-mini__place"><?php echo esc_html( $badr_e['venue'] ); ?></p>
									<?php endif; ?>
								</span>
							</a>
						<?php endforeach; ?>
					<?php endif; ?>

					<div class="badr-events__empty-side" data-reveal="up">
						<h3>Vous organisez avec nous&nbsp;?</h3>
						<p>Les bénévoles préparent chaque rencontre, du montage des salles à l’accueil des familles.</p>
						<a class="badr-link" href="/implication/">
							<span>Rejoindre l’équipe</span>
							<?php echo $badr_icon( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</div>
				</div>

			</div>

		<?php else : ?>

			<div class="badr-events__grid">

				<div class="badr-events__empty" data-reveal="rise">
					<?php
					// Ornement dessiné : un calendrier stylisé d'où pousse une tige
					// fleurie. Il rend l'attente accueillante plutôt que vide.
					?>
					<svg class="badr-events__empty-mark" viewBox="0 0 120 120" fill="none" stroke="currentColor"
						stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<rect x="18" y="30" width="84" height="72" rx="10"/>
						<path d="M18 50h84M40 22v16M80 22v16"/>
						<path d="M60 92c0-14-9-21-20-23 0 13 9 20 20 23Z" fill="currentColor" fill-opacity="0.12"/>
						<path d="M60 92c0-14 9-21 20-23 0 13-9 20-20 23Z" fill="currentColor" fill-opacity="0.12"/>
						<path d="M60 92V66"/>
						<circle cx="60" cy="62" r="5" fill="currentColor" fill-opacity="0.2"/>
					</svg>

					<h3 class="badr-events__empty-title">Les prochains événements seront affichés ici.</h3>
					<p class="badr-events__empty-note">Fêtes de la diversité, sorties communautaires, ateliers&nbsp;: dès qu’une date est confirmée par l’équipe, elle apparaît sur cette page.</p>
					<a class="badr-btn badr-btn--outline" href="/nos-services-et-activites/">
						<span>Voir nos activités régulières</span>
						<?php echo $badr_icon( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				</div>

				<div class="badr-events__side" data-reveal-group>
					<div class="badr-events__empty-side" data-reveal="up">
						<h3>Chaque lundi, sans rendez-vous</h3>
						<p>La banque alimentaire accueille les familles de 15&#8239;h&#8239;00 à 17&#8239;h&#8239;00 au Centre BADR.</p>
						<a class="badr-link" href="/banque-alimentaire/">
							<span>Comment ça fonctionne</span>
							<?php echo $badr_icon( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</div>

					<div class="badr-events__empty-side" data-reveal="up">
						<h3>Être prévenu des prochaines dates</h3>
						<p>Écrivez-nous&nbsp;: nous vous dirons quand la prochaine activité ouvre ses inscriptions.</p>
						<a class="badr-link" href="/implication/">
							<span>Nous joindre</span>
							<?php echo $badr_icon( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</div>
				</div>

			</div>

		<?php endif; ?>

	</div>
</section>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

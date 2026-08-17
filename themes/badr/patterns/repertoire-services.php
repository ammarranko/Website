<?php
/**
 * Title: Répertoire des services et activités
 * Slug: badr/repertoire-services
 * Categories: badr-pages
 * Description: L'expérience principale du site : un héros utile, un chercheur d'activités avec recherche et filtres, les quatre familles de services en grands sélecteurs colorés, puis la liste complète des activités avec public, horaire, lieu et état d'inscription.
 * Keywords: services, activités, programmes, répertoire, inscription, recherche
 * Viewport Width: 1440
 *
 * Les activités proviennent du type d'objet « badr_program » du plugin
 * badr-site-core, modifiable dans wp-admin. Rien n'est codé en dur ici.
 *
 * Les champs pratiques vides ne sont pas remplis par une valeur plausible :
 * ils affichent « À confirmer », ce qui indique au personnel ce qu'il reste à
 * saisir et n'induit personne en erreur.
 *
 * Le filtrage est fait côté client sur des cartes déjà rendues : les résultats
 * restent accessibles sans JavaScript, et le filtrage ne recharge pas la page.
 *
 * @package BADR
 */

$badr_familles = \BADR\Theme\taxonomy_terms( \BADR\Theme\TAX_FAMILY );
$badr_espaces  = \BADR\Theme\taxonomy_terms( \BADR\Theme\TAX_SPACE );
$badr_activites = \BADR\Theme\programs();

/**
 * Icône de famille, en trait fin.
 *
 * @param string $slug Identifiant de la famille.
 * @return string
 */
$badr_fam_icon = static function ( string $slug ): string {
	$paths = array(
		'soutien-communautaire'   => '<path d="M8 26c0 8 6 14 14 14h4c8 0 14-6 14-14Z"/><path d="M4 26h40"/><path d="M18 18c0-4 3-6 6-6s6 2 6 6"/><path d="M24 12V6"/>',
		'education-developpement' => '<path d="M6 36c8-4 14-4 18 0 4-4 10-4 18 0V16c-8-4-14-4-18 0-4-4-10-4-18 0Z"/><path d="M24 16v20M24 16V6"/><path d="M24 10c0-3-3-5-6-6 0 4 3 6 6 6Z"/>',
		'sports-loisirs'          => '<circle cx="24" cy="24" r="17"/><path d="m24 12 8 6-3 10h-10l-3-10Z"/><path d="M24 7v5M9 20l5 3M39 20l-5 3M15 41l4-9M33 41l-4-9"/>',
		'evenements-fetes'        => '<path d="M8 40 20 14l14 14Z"/><path d="M28 8c3 0 4 2 4 4s2 4 5 4"/><circle cx="38" cy="24" r="2"/><circle cx="30" cy="6" r="2"/><circle cx="42" cy="12" r="2"/>',
	);

	return '<svg class="badr-famille__icon" viewBox="0 0 48 48" aria-hidden="true" focusable="false">'
		. ( $paths[ $slug ] ?? '' ) . '</svg>';
};

/**
 * Petite icône d'information pratique.
 *
 * @param string $name Identifiant.
 * @return string
 */
$badr_ico = static function ( string $name ): string {
	$paths = array(
		'public'  => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"/><circle cx="17" cy="9.5" r="2.4"/><path d="M15 20c0-2.6 1.8-4.6 4-4.6s2.9 1 2.9 2.6"/>',
		'horaire' => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'lieu'    => '<path d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11Z"/><circle cx="12" cy="10" r="2.6"/>',
		'age'     => '<path d="M4 18V9l8-4 8 4v9"/><path d="M4 18h16M9 18v-5h6v5"/>',
		'cout'    => '<circle cx="12" cy="12" r="8.5"/><path d="M15 9H11a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4H9M12 7v10"/>',
		'fleche'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'loupe'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4.4-4.4"/>',
	);

	return '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor"'
		. ' stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"'
		. ' focusable="false">' . ( $paths[ $name ] ?? '' ) . '</svg>';
};
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->

<?php /* ---------- A. Héros utile, volontairement compact ---------- */ ?>
<header class="badr-svc-hero">
	<div class="badr-shell">
		<div class="badr-svc-hero__inner">
			<div>
				<p class="badr-eyebrow">Services et activités</p>
				<h1 class="badr-svc-hero__title">Trouvez l’activité qui vous <span class="badr-em">correspond.</span></h1>
				<p class="badr-svc-hero__lead">Explorez les services, programmes et activités du Bureau Associatif pour la Diversité et la Réinsertion selon votre âge, vos besoins et vos intérêts.</p>
				<div class="badr-actions">
					<a class="badr-btn badr-btn--solid" href="#badr-activites">
						<span>Voir toutes les activités</span>
						<?php echo $badr_ico( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
					<a class="badr-btn badr-btn--outline" href="/banque-alimentaire/"><span>Banque alimentaire</span></a>
				</div>
			</div>

			<?php if ( $badr_activites ) : ?>
				<dl class="badr-svc-hero__stats" data-reveal-group>
					<div data-reveal="up">
						<dt>Activités</dt>
						<dd><?php echo esc_html( (string) count( $badr_activites ) ); ?></dd>
					</div>
					<div data-reveal="up">
						<dt>Familles de services</dt>
						<dd><?php echo esc_html( (string) count( $badr_familles ) ); ?></dd>
					</div>
					<div data-reveal="up">
						<dt>Espaces</dt>
						<dd><?php echo esc_html( (string) count( $badr_espaces ) ); ?></dd>
					</div>
				</dl>
			<?php endif; ?>
		</div>
	</div>
</header>

<?php if ( ! $badr_activites ) : ?>

	<section class="badr-section badr-section--wash">
		<div class="badr-shell">
			<div class="badr-empty">
				<h2 class="badr-events__empty-title">Le répertoire des activités arrive bientôt.</h2>
				<p class="badr-events__empty-note">Les activités s’ajoutent depuis l’administration du site, dans « Programmes ». Dès qu’une première activité est publiée, elle apparaît ici.</p>
				<a class="badr-btn badr-btn--outline" href="/implication/"><span>Nous joindre</span></a>
			</div>
		</div>
	</section>

<?php else : ?>

<?php /* ---------- B + C. Chercheur d'activités et familles ---------- */ ?>
<section class="badr-finder" data-badr-finder aria-labelledby="badr-finder-titre">
	<div class="badr-shell">

		<div class="badr-finder__head" data-reveal="mask">
			<h2 class="badr-section__title" id="badr-finder-titre">Par où voulez-vous <span class="badr-em">commencer&nbsp;?</span></h2>
			<p class="badr-section__intro">Choisissez une famille de services, ou filtrez directement par public et par état d’inscription.</p>
		</div>

		<?php /* Les quatre familles : navigation principale, pas quatre colonnes de texte. */ ?>
		<div class="badr-familles" role="group" aria-label="Familles de services">
			<button type="button" class="badr-famille is-active" data-finder-family-btn="" style="--b-accent: var(--b-ink)">
				<span class="badr-famille__icon-wrap">
					<svg class="badr-famille__icon" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><circle cx="14" cy="14" r="7"/><circle cx="34" cy="14" r="7"/><circle cx="14" cy="34" r="7"/><circle cx="34" cy="34" r="7"/></svg>
				</span>
				<span class="badr-famille__name">Toutes les activités</span>
				<span class="badr-famille__desc">L’ensemble de l’offre du BADR.</span>
				<span class="badr-famille__count"><?php echo esc_html( (string) count( $badr_activites ) ); ?></span>
			</button>

			<?php foreach ( $badr_familles as $badr_fam ) : ?>
				<?php if ( 0 === $badr_fam['count'] ) : continue; endif; ?>
				<button type="button"
					class="badr-famille"
					data-finder-family-btn="<?php echo esc_attr( $badr_fam['slug'] ); ?>"
					style="--b-accent: <?php echo esc_attr( $badr_fam['accent'] ); ?>">
					<span class="badr-famille__icon-wrap">
						<?php echo $badr_fam_icon( $badr_fam['slug'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<span class="badr-famille__name"><?php echo esc_html( $badr_fam['name'] ); ?></span>
					<span class="badr-famille__desc"><?php echo esc_html( $badr_fam['desc'] ); ?></span>
					<span class="badr-famille__count"><?php echo esc_html( (string) $badr_fam['count'] ); ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<?php /* Les filtres fins. */ ?>
		<div class="badr-finder__bar">
			<div class="badr-finder__search">
				<label class="badr-sr-only" for="badr-finder-q">Rechercher une activité par son nom</label>
				<?php echo $badr_ico( 'loupe' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<input type="search" id="badr-finder-q" data-finder-search placeholder="Rechercher une activité — soccer, ateliers, banque…" autocomplete="off">
			</div>

			<div class="badr-finder__field">
				<label for="badr-finder-espace">Public</label>
				<select id="badr-finder-espace" data-finder-space>
					<option value="">Tous les publics</option>
					<?php foreach ( $badr_espaces as $badr_esp ) : ?>
						<?php if ( 0 === $badr_esp['count'] ) : continue; endif; ?>
						<option value="<?php echo esc_attr( $badr_esp['slug'] ); ?>"><?php echo esc_html( $badr_esp['name'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="badr-finder__field">
				<label for="badr-finder-reg">Inscription</label>
				<select id="badr-finder-reg" data-finder-reg>
					<option value="">Tous les états</option>
					<option value="ouverte">Inscription ouverte</option>
					<option value="bientot">Inscription à venir</option>
					<option value="aucune">Accès libre</option>
					<option value="info">Renseignements sur demande</option>
				</select>
			</div>

			<button type="button" class="badr-finder__reset" data-finder-reset>Réinitialiser</button>
		</div>

		<p class="badr-finder__count" data-finder-count aria-live="polite">
			<?php echo esc_html( sprintf( '%d activités', count( $badr_activites ) ) ); ?>
		</p>

	</div>
</section>

<?php /* ---------- D. Les activités ---------- */ ?>
<section class="badr-section badr-section--wash badr-activites" id="badr-activites" style="--b-accent:var(--b-azure);--b-accent-d:var(--b-azure-d)" aria-label="Toutes les activités">
	<div class="badr-shell">

		<div class="badr-activites__list" data-finder-results data-reveal-group>
			<?php foreach ( $badr_activites as $badr_a ) : ?>
				<?php
				$badr_spaces_slugs = implode( ' ', array_column( $badr_a['spaces'], 'slug' ) );
				$badr_action       = \BADR\Theme\registration_action( $badr_a );
				?>
				<article class="badr-activite"
					data-reveal="up"
					data-title="<?php echo esc_attr( mb_strtolower( $badr_a['title'] . ' ' . $badr_a['summary'] . ' ' . $badr_a['family'] ) ); ?>"
					data-family="<?php echo esc_attr( $badr_a['family_slug'] ); ?>"
					data-spaces="<?php echo esc_attr( $badr_spaces_slugs ); ?>"
					data-reg="<?php echo esc_attr( $badr_a['registration'] ); ?>"
					style="--b-accent: <?php echo esc_attr( $badr_a['accent'] ); ?>; --b-accent-d: <?php echo esc_attr( $badr_a['accent'] ); ?>">

					<div class="badr-activite__media">
						<?php if ( $badr_a['thumb'] ) : ?>
							<?php
							echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$badr_a['thumb'],
								'badr-card',
								false,
								array(
									'loading'  => 'lazy',
									'decoding' => 'async',
									'sizes'    => '(max-width: 48rem) 90vw, 16rem',
									'alt'      => '',
								)
							);
							?>
						<?php else : ?>
							<span class="badr-activite__mark" aria-hidden="true">
								<?php echo $badr_fam_icon( $badr_a['family_slug'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						<?php endif; ?>
					</div>

					<div class="badr-activite__body">
						<p class="badr-activite__family"><?php echo esc_html( $badr_a['family'] ); ?></p>
						<h3 class="badr-activite__title">
							<a href="<?php echo esc_url( $badr_a['url'] ); ?>"><?php echo esc_html( $badr_a['title'] ); ?></a>
						</h3>
						<?php if ( '' !== $badr_a['summary'] ) : ?>
							<p class="badr-activite__summary"><?php echo esc_html( $badr_a['summary'] ); ?></p>
						<?php endif; ?>

						<ul class="badr-activite__meta">
							<li><?php echo $badr_ico( 'public' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span<?php echo '' === $badr_a['audience'] ? ' class="is-todo"' : ''; ?>><?php echo esc_html( (string) \BADR\Theme\practical( $badr_a['audience'], true ) ); ?></span></li>
							<li><?php echo $badr_ico( 'horaire' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span<?php echo '' === $badr_a['schedule'] ? ' class="is-todo"' : ''; ?>><?php echo esc_html( (string) \BADR\Theme\practical( $badr_a['schedule'], true ) ); ?></span></li>
							<?php if ( '' !== $badr_a['location'] ) : ?>
								<li><?php echo $badr_ico( 'lieu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $badr_a['location'] ); ?></span></li>
							<?php endif; ?>
						</ul>
					</div>

					<div class="badr-activite__aside">
						<span class="badr-tag badr-tag--<?php echo esc_attr( $badr_a['registration'] ); ?>">
							<?php echo esc_html( \BADR\Theme\registration_label( $badr_a['registration'] ) ); ?>
						</span>

						<a class="badr-link" href="<?php echo esc_url( $badr_a['url'] ); ?>">
							<span>Voir le programme</span>
							<?php echo $badr_ico( 'fleche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>

						<?php if ( $badr_action && 'ouverte' === $badr_a['registration'] ) : ?>
							<a class="badr-btn badr-btn--solid badr-btn--sm" href="<?php echo esc_url( $badr_action['url'] ); ?>">
								<span>S’inscrire</span>
							</a>
						<?php endif; ?>
					</div>

				</article>
			<?php endforeach; ?>
		</div>

		<p class="badr-finder__none" data-finder-none hidden>
			Aucune activité ne correspond à cette recherche. <button type="button" class="badr-link" data-finder-reset>Réinitialiser les filtres</button>
		</p>

	</div>
</section>

<?php endif; ?>

<!-- /wp:html -->
</div>
<!-- /wp:group -->

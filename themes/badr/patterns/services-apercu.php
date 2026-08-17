<?php
/**
 * Title: Services — accès rapide depuis l'accueil
 * Slug: badr/services-apercu
 * Categories: badr-pages
 * Description: Raccourci vers le répertoire depuis la page d'accueil : les quatre familles de services en grands sélecteurs colorés avec leur nombre réel d'activités, plus quelques activités mises en avant. Mène directement au répertoire complet.
 * Keywords: services, activités, programmes, familles, accès
 * Viewport Width: 1440
 *
 * Les familles et les activités viennent du répertoire éditable dans wp-admin.
 * Si aucune activité n'est publiée, la section ne s'affiche pas du tout plutôt
 * que de laisser une zone vide sur la page d'accueil.
 *
 * @package BADR
 */

$badr_familles = \BADR\Theme\taxonomy_terms( \BADR\Theme\TAX_FAMILY );
$badr_vedettes = \BADR\Theme\programs( array( 'limit' => 3 ) );

if ( ! $badr_familles || ! $badr_vedettes ) {
	return;
}

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
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<section class="badr-section badr-section--wash" style="--b-accent:var(--b-azure);--b-accent-d:var(--b-azure-d)" aria-labelledby="badr-apercu-titre">
	<?php echo \BADR\Theme\fil( 'ondule' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- balisage interne. ?>

	<div class="badr-shell">

		<div class="badr-head badr-head--split" data-reveal="mask">
			<div>
				<p class="badr-eyebrow">Services et activités</p>
				<h2 class="badr-section__title" id="badr-apercu-titre">Trouvez l’activité qui vous <span class="badr-em">correspond.</span></h2>
			</div>
			<div>
				<p class="badr-section__intro">Explorez les services, programmes et activités du Bureau Associatif pour la Diversité et la Réinsertion selon votre âge, vos besoins et vos intérêts.</p>
				<div class="badr-head__aside" style="margin-top:1.25rem">
					<a class="badr-btn badr-btn--solid" href="/nos-services-et-activites/">
						<span>Voir toutes les activités</span>
						<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
					</a>
				</div>
			</div>
		</div>

		<div class="badr-familles" data-reveal-group>
			<?php foreach ( $badr_familles as $badr_fam ) : ?>
				<?php if ( 0 === $badr_fam['count'] ) : continue; endif; ?>
				<a class="badr-famille" href="<?php echo esc_url( $badr_fam['url'] ); ?>" data-reveal="up"
					style="--b-accent: <?php echo esc_attr( $badr_fam['accent'] ); ?>">
					<span class="badr-famille__icon-wrap">
						<?php echo $badr_fam_icon( $badr_fam['slug'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<span class="badr-famille__name"><?php echo esc_html( $badr_fam['name'] ); ?></span>
					<span class="badr-famille__desc"><?php echo esc_html( $badr_fam['desc'] ); ?></span>
					<span class="badr-famille__count"><?php echo esc_html( (string) $badr_fam['count'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

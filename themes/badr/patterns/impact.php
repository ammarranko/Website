<?php
/**
 * Title: Impact — compteurs animés
 * Slug: badr/impact
 * Categories: badr-pages
 * Description: Bande d'impact sur encre bleu-vert : quatre chiffres en très grande typographie serif qui comptent depuis zéro à l'entrée dans le viewport, reliés par des filets plutôt qu'enfermés dans des boîtes, avec icônes en trait fin et champs de couleur mouvants.
 * Keywords: impact, chiffres, statistiques, compteurs
 * Viewport Width: 1440
 *
 * Les valeurs viennent de inc/impact.php et sont modifiables sans toucher à
 * cette composition (option WordPress « badr_impact_stats » ou filtre
 * « badr_impact_stats »). Voir l'en-tête de ce fichier pour la provenance de
 * chaque chiffre : deux sont fournis par l'organisme et restent à confirmer,
 * deux sont vérifiables dans le contenu du site.
 *
 * Le chiffre final est écrit dans le HTML. Le comptage ne fait que l'animer :
 * si le JavaScript échoue, la bonne valeur reste affichée.
 *
 * @package BADR
 */

$badr_stats = \BADR\Theme\impact_stats();
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<section class="badr-section badr-impact" aria-labelledby="badr-impact-titre">
	<div class="badr-shell">

		<div class="badr-head badr-head--split" data-reveal="mask">
			<div>
				<p class="badr-eyebrow">Notre impact</p>
				<h2 class="badr-section__title" id="badr-impact-titre">Ce que la communauté <span class="badr-em">accomplit ensemble</span></h2>
			</div>
			<div>
				<p class="badr-section__intro">Derrière chaque chiffre, des familles accueillies chaque semaine, des bénévoles fidèles et des espaces qui restent ouverts.</p>
			</div>
		</div>

		<div class="badr-impact__grid">
			<?php foreach ( $badr_stats as $badr_i => $badr_stat ) : ?>
				<div class="badr-stat" style="--b-accent: <?php echo esc_attr( $badr_stat['accent'] ); ?>; --b-i: <?php echo (int) $badr_i; ?>">

					<?php echo \BADR\Theme\impact_icon( $badr_stat['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG interne, sans donnée utilisateur. ?>

					<span class="badr-stat__num">
						<span data-count="<?php echo (int) $badr_stat['value']; ?>" data-count-index="<?php echo (int) $badr_i; ?>"><?php echo esc_html( \BADR\Theme\format_number_fr( (int) $badr_stat['value'] ) ); ?></span><?php if ( '' !== $badr_stat['suffix'] ) : ?><span class="badr-stat__suffix"><?php echo esc_html( $badr_stat['suffix'] ); ?></span><?php endif; ?>
					</span>

					<p class="badr-stat__label"><?php echo esc_html( $badr_stat['label'] ); ?></p>
					<p class="badr-stat__note"><?php echo esc_html( $badr_stat['note'] ); ?></p>

				</div>
			<?php endforeach; ?>
		</div>

		<div class="badr-impact__foot" data-reveal="up">
			<p>Saint-Léonard · Montréal</p>
			<a class="badr-link" href="/temoignages/">
				<span>Lire les histoires derrière les chiffres</span>
				<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
			</a>
		</div>

	</div>
</section>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

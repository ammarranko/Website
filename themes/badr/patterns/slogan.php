<?php
/**
 * Title: Ici, chacun trouve sa place
 * Slug: badr/slogan
 * Categories: badr-pages
 * Description: Déclaration inclusive suivie des six portes d'entrée du BADR, en liens directs vers chaque espace communautaire. Remplace la mosaïque de trois photos qui ne menait nulle part.
 * Keywords: slogan, inclusion, place, communauté, espaces
 * Viewport Width: 1440
 *
 * La mosaïque décorative précédente occupait un demi-écran sans rien apporter :
 * trois photos sans titre, sans explication et sans lien. Elle est remplacée
 * par la même déclaration accompagnée d'une navigation réelle vers les six
 * espaces — la section garde son rôle émotionnel et devient utile.
 *
 * Le slogan vérifié du BADR (« Un milieu de vie, une communauté de soutien, un
 * espace d'entraide et de rencontres ») reste affiché dans l'ouverture animée.
 *
 * @package BADR
 */

$badr_espaces_liens = \BADR\Theme\taxonomy_terms( \BADR\Theme\TAX_SPACE );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<section class="badr-place" aria-labelledby="badr-place-titre">
	<div class="badr-place__inner">

		<div data-reveal="mask">
			<p class="badr-place__eyebrow">Une place pour chacun</p>
			<h2 class="badr-place__title" id="badr-place-titre">Ici, chacun trouve sa place.</h2>
			<p class="badr-place__lead">Des espaces pensés pour se rencontrer, s&rsquo;entraider et grandir ensemble — à chaque âge et à chaque étape de la vie.</p>
		</div>

		<?php if ( $badr_espaces_liens ) : ?>
			<nav class="badr-place__portes" aria-label="Les six espaces communautaires" data-reveal-group>
				<?php foreach ( $badr_espaces_liens as $badr_i => $badr_esp ) : ?>
					<a class="badr-porte" href="<?php echo esc_url( $badr_esp['url'] ); ?>" data-reveal="up"
						style="--b-accent: <?php echo esc_attr( $badr_esp['accent'] ); ?>">
						<span class="badr-porte__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $badr_i + 1 ) ); ?></span>
						<span class="badr-porte__name"><?php echo esc_html( $badr_esp['name'] ); ?></span>
						<?php if ( $badr_esp['count'] > 0 ) : ?>
							<span class="badr-porte__count"><?php echo esc_html( sprintf( 1 === $badr_esp['count'] ? '%d activité' : '%d activités', $badr_esp['count'] ) ); ?></span>
						<?php endif; ?>
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
					</a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>

	</div>
</section>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

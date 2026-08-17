<?php
/**
 * Title: Répertoire des espaces (section complète)
 * Slug: badr/espaces-repertoire
 * Categories: badr-espaces
 * Description: Section complète — titre, introduction et grille des neuf espaces du BADR. Chaque espace porte un monogramme et une couleur fixes ; le libellé écrit accompagne toujours la couleur.
 * Keywords: espaces, milieu de vie, répertoire, orientation
 * Viewport Width: 1280
 *
 * Les données viennent de BADR\Theme\espaces().
 *
 * @package BADR
 */

?>
<!-- wp:group {"align":"full","className":"badr-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull badr-section">

	<!-- wp:html -->
	<p class="badr-eyebrow"><?php esc_html_e( 'Répertoire des espaces', 'badr' ); ?></p>
	<h2 class="badr-section__title"><?php esc_html_e( 'Neuf espaces, un même lieu', 'badr' ); ?></h2>
	<p class="badr-section__intro"><?php esc_html_e( "Nos membres ont conçu plusieurs espaces pour répondre aux besoins spécifiques de chaque groupe de notre communauté. Chaque espace propose des activités gratuites ou à faible coût, accessibles à toutes et à tous.", 'badr' ); ?></p>
	<?php echo \BADR\Theme\render_espaces_grid( 3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- balisage déjà échappé dans render_espaces_grid(). ?>
	<!-- /wp:html -->

</div>
<!-- /wp:group -->

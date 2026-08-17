<?php
/**
 * Title: Tête de page
 * Slug: badr/tete-de-page
 * Categories: badr-pages
 * Description: Bande crème d'ouverture pour une page de contenu : surtitre, titre principal (h1) et chapeau. À insérer en premier sur toute nouvelle page, car le gabarit n'affiche pas de titre automatique.
 * Keywords: titre, en-tête, tête de page, chapeau
 * Viewport Width: 1280
 *
 * @package BADR
 */

?>
<!-- wp:group {"align":"full","className":"badr-pagehead","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull badr-pagehead">
	<!-- wp:paragraph {"className":"badr-eyebrow"} -->
	<p class="badr-eyebrow"><?php esc_html_e( 'Surtitre de section', 'badr' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":1,"className":"badr-pagehead__title"} -->
	<h1 class="wp-block-heading badr-pagehead__title"><?php esc_html_e( 'Titre de la page', 'badr' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"className":"badr-pagehead__lead"} -->
	<p class="badr-pagehead__lead"><?php esc_html_e( 'Chapeau d\'introduction : une ou deux phrases qui résument ce que le visiteur trouvera sur cette page.', 'badr' ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

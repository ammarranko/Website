<?php
/**
 * Title: Grille des espaces (sans titre)
 * Slug: badr/espaces-grille
 * Categories: badr-espaces
 * Description: La seule grille des neuf espaces, sans titre ni introduction, à insérer dans une section qui fournit déjà son propre titre.
 * Keywords: espaces, grille, répertoire
 * Viewport Width: 1280
 *
 * Les données viennent de BADR\Theme\espaces() : une seule source de vérité
 * partagée avec la composition « Répertoire des espaces ».
 *
 * @package BADR
 */

?>
<!-- wp:html -->
<?php echo \BADR\Theme\render_espaces_grid( 3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- balisage déjà échappé dans render_espaces_grid(). ?>
<!-- /wp:html -->

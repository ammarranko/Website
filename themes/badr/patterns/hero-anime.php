<?php
/**
 * Title: Ouverture animée B.A.D.R
 * Slug: badr/hero-anime
 * Categories: badr-pages
 * Description: Ouverture cinématographique de la page d'accueil, en HTML et CSS. Chaque initiale colorée du logo se déploie en mot complet, puis se replie pour composer B. A. D. R., suivi du nom complet et du slogan. Aucune image, aucune vidéo, aucune dépendance.
 * Keywords: héros, ouverture, accueil, animation, acronyme
 * Viewport Width: 1440
 *
 * Couleurs des initiales : échantillonnées pixel par pixel dans images/logo.jpeg
 * (tokens --wp--custom--letter--* de theme.json). Elles ne sont pas devinées.
 *
 * Typographie : Instrument Serif. L'initiale est en romain, son suffixe dans le
 * MÊME serif en italique et plus petit — on lit un seul mot, la hiérarchie est
 * immédiate. Tout reste du texte réel : vectoriel, sélectionnable, net à toute
 * densité d'écran.
 *
 * Accessibilité : la séquence est décorative (aria-hidden) puisqu'elle décompose
 * puis recompose la même information ; le titre porte une version textuelle pour
 * les lecteurs d'écran, et le nom comme le slogan sont du texte sémantique
 * visible, donc indexable. Le bouton « Passer » force l'état final.
 *
 * @package BADR
 */

?>
<!-- Enveloppe alignfull : sans elle, la mise en page « constrained » du contenu
     plafonnerait l'ouverture à la largeur du texte au lieu de la pleine page. -->
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<section class="badr-hero-anime" aria-labelledby="badr-hero-title" data-badr-hero>

	<?php
	// Couche botanique et céleste : tiges, fleurs et étoiles en trait fin SVG.
	// Elle vit derrière la typographie et n'entre jamais dans la bande centrale.
	require dirname( __DIR__ ) . '/inc/flore.php';
	?>

	<button type="button" class="badr-hero-anime__skip" data-badr-skip>
		<span>Passer l&rsquo;animation</span>
	</button>

	<div class="badr-hero-anime__stage">

		<h1 class="badr-hero-anime__acronyme" id="badr-hero-title">
			<span class="badr-sr-only">B.A.D.R. — Bureau Associatif pour la Diversité et la Réinsertion</span>

			<span class="badr-mot badr-mot--b" aria-hidden="true"><span class="badr-mot__initiale">B</span><span class="badr-mot__suffixe"><span class="badr-mot__suffixe-txt">ureau</span></span><span class="badr-mot__point">.</span></span>

			<span class="badr-mot badr-mot--a" aria-hidden="true"><span class="badr-mot__initiale">A</span><span class="badr-mot__suffixe"><span class="badr-mot__suffixe-txt">ssociatif</span></span><span class="badr-mot__point">.</span></span>

			<span class="badr-mot badr-mot--d" aria-hidden="true"><span class="badr-mot__initiale">D</span><span class="badr-mot__suffixe"><span class="badr-mot__suffixe-txt">iversité</span></span><span class="badr-mot__point">.</span></span>

			<span class="badr-mot badr-mot--r" aria-hidden="true"><span class="badr-mot__initiale">R</span><span class="badr-mot__suffixe"><span class="badr-mot__suffixe-txt">éinsertion</span></span><span class="badr-mot__point">.</span></span>
		</h1>

		<hr class="badr-hero-anime__filet" aria-hidden="true">

		<p class="badr-hero-anime__nom">Bureau Associatif pour la Diversité et la Réinsertion</p>

		<p class="badr-hero-anime__slogan">Un milieu de vie, une communauté de soutien,<br>un espace d&rsquo;entraide et de rencontres.</p>

		<div class="badr-hero-anime__actions">
			<a class="badr-btn badr-btn--solid" href="/implication/">
				<span>Devenir bénévole</span>
			</a>
			<a class="badr-btn badr-btn--outline" href="/nos-services-et-activites/">
				<span>Découvrir nos programmes</span>
			</a>
		</div>

	</div>

	<p class="badr-hero-anime__cue" aria-hidden="true">
		Découvrir
		<span></span>
	</p>

</section>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

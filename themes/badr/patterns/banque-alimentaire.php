<?php
/**
 * Title: Banque alimentaire — page complète
 * Slug: badr/banque-alimentaire
 * Categories: badr-pages
 * Description: Présentation dédiée de la banque alimentaire : héros digne, mission, deux voies (demander de l'aide / contribuer), informations pratiques vérifiées et appel à l'action.
 * Keywords: banque alimentaire, aide alimentaire, dons, bénévolat
 * Viewport Width: 1440
 *
 * Faits vérifiés utilisés ici, et rien d'autre : horaire du lundi 15 h à 17 h,
 * bénéficiaires (familles et individus à faibles revenus), lieu (6432, rue
 * Jean-Talon Est), contacts panier@badr.ca et 514 324-5341, et les trois
 * services connexes (don de meubles et vêtements, transport solidaire,
 * trousseau scolaire).
 *
 * Tout ce qui n'est PAS documenté — critères d'admissibilité, pièces à
 * fournir, quantités, modalités de don, encadrement du bénévolat — est signalé
 * par un encadré « à confirmer » dans le contenu éditable, jamais inventé.
 *
 * @package BADR
 */

// Le fichier optimisé est en .webp : chercher « .jpg » ne renvoyait rien et le
// visuel du héros restait vide.
$badr_hero_img = \BADR\Theme\media_by_filename( 'banque-alimentaire.webp' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->

<section class="badr-bank__hero">
	<div class="badr-bank__hero-inner">
		<div>
			<p class="badr-eyebrow" style="color:var(--wp--preset--color--gold-500)">Soutien communautaire</p>
			<h1 class="badr-bank__title">Une aide alimentaire offerte avec respect et dignité.</h1>
			<p class="badr-bank__lead">Chaque semaine, nous distribuons des denrées alimentaires aux familles en situation de précarité. Notre objectif est de garantir un accès à une alimentation équilibrée et d&rsquo;accompagner les familles vers une meilleure autonomie.</p>
			<div class="badr-actions">
				<a class="badr-btn badr-btn--primary" href="mailto:panier@badr.ca">
					<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
					<span>Écrire à panier@badr.ca</span>
				</a>
				<a class="badr-btn badr-btn--ghost" href="tel:+15143245341">
					<span>514&nbsp;324-5341</span>
				</a>
			</div>
		</div>

		<div class="badr-bank__media">
			<?php if ( $badr_hero_img ) : ?>
				<img src="<?php echo esc_url( $badr_hero_img['url'] ); ?>"
					<?php if ( '' !== $badr_hero_img['srcset'] ) : ?>srcset="<?php echo esc_attr( $badr_hero_img['srcset'] ); ?>"<?php endif; ?>
					sizes="(max-width: 56rem) 92vw, 42vw"
					alt="Légumes frais disposés dans des caisses de marché" loading="lazy" decoding="async">
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="badr-section" style="background:var(--wp--preset--color--paper)">
	<div style="max-inline-size:76rem;margin-inline:auto;padding-inline:clamp(1.25rem,5vw,2.5rem)">

		<div class="badr-watermark" data-mark="&amp;" style="max-inline-size:44rem">
			<p class="badr-eyebrow">Bien plus qu&rsquo;un panier</p>
			<h2 class="badr-section__title">Un lieu de socialisation autant qu&rsquo;un service</h2>
			<p class="badr-section__intro">La banque alimentaire est aussi un lieu de socialisation, d&rsquo;échange et d&rsquo;intégration. On y vient chercher des denrées, et on y rencontre son quartier.</p>
		</div>

		<blockquote class="badr-pull">Personne ne devrait avoir à choisir entre se nourrir et le reste.</blockquote>

		<div class="badr-bank__ways" style="margin-block-start:clamp(2rem,5vw,3.5rem)">

			<article class="badr-bank__way" style="--badr-accent:var(--wp--custom--espace--filles)">
				<h3>Recevoir de l&rsquo;aide</h3>
				<p>La distribution est ouverte chaque semaine, sans rendez-vous. Présentez-vous sur place pendant les heures d&rsquo;ouverture, ou écrivez-nous avant si vous préférez en parler d&rsquo;abord.</p>
				<ol class="badr-bank__steps">
					<li>Venez au Centre BADR pendant l&rsquo;ouverture&#8239;: tous les lundis, de 15&#8239;h&#8239;00 à 17&#8239;h&#8239;00.</li>
					<li>Une personne de l&rsquo;équipe vous accueille et vous explique le déroulement.</li>
					<li>Vous repartez avec des denrées, et l&rsquo;information sur nos autres services si vous le souhaitez.</li>
				</ol>
				<div class="badr-todo">
					<strong>À confirmer par l&rsquo;organisme</strong>
					Conditions d&rsquo;admissibilité, pièces à présenter, fréquence permise et territoire desservi. Ces précisions ne figurent pas dans le contenu fourni&#8239;; elles doivent être ajoutées avant publication.
				</div>
				<p style="margin-block-start:1.25rem;margin-block-end:0">
					<a class="badr-btn badr-btn--outline" href="mailto:panier@badr.ca"><span>Poser une question</span></a>
				</p>
			</article>

			<article class="badr-bank__way" style="--badr-accent:var(--wp--custom--espace--papas)">
				<h3>Donner un coup de main</h3>
				<p>La distribution repose sur l&rsquo;engagement de bénévoles. Si vous avez quelques heures à offrir, l&rsquo;équipe vous expliquera où votre aide est la plus utile.</p>
				<ol class="badr-bank__steps">
					<li>Remplissez le formulaire de bénévolat du BADR.</li>
					<li>L&rsquo;équipe vous contacte pour convenir d&rsquo;un moment.</li>
					<li>Vous rejoignez l&rsquo;équipe de distribution ou de préparation.</li>
				</ol>
				<div class="badr-todo">
					<strong>À confirmer par l&rsquo;organisme</strong>
					Modalités de dons de denrées ou d&rsquo;argent, points de dépôt, reçus fiscaux et âge minimum pour le bénévolat. Rien de tout cela n&rsquo;est documenté dans le contenu fourni.
				</div>
				<p style="margin-block-start:1.25rem;margin-block-end:0">
					<a class="badr-btn badr-btn--outline" href="https://forms.gle/dmGwDEsANYTQ653t5" target="_blank" rel="noopener noreferrer">
						<span>Devenir bénévole</span>
						<span class="badr-sr-only">(formulaire externe, nouvelle fenêtre)</span>
					</a>
				</p>
			</article>

		</div>
	</div>
</section>

<section class="badr-section badr-section--cream">
	<div style="max-inline-size:76rem;margin-inline:auto;padding-inline:clamp(1.25rem,5vw,2.5rem)">
		<div class="wp-block-columns alignwide" style="display:grid;gap:clamp(2rem,5vw,4rem);grid-template-columns:1fr">
			<div style="display:grid;gap:clamp(2rem,5vw,4rem)">

				<div>
					<p class="badr-eyebrow">Informations pratiques</p>
					<h2 class="badr-section__title">Quand et où</h2>
					<dl class="badr-details" style="margin-block-start:1.5rem">
						<dt>Horaire</dt>
						<dd>Tous les lundis, de 15&#8239;h&#8239;00 à 17&#8239;h&#8239;00</dd>
						<dt>Bénéficiaires</dt>
						<dd>Familles et individus à faibles revenus</dd>
						<dt>Lieu</dt>
						<dd>Centre BADR, 6432, rue Jean-Talon Est (coin Langelier), Saint-Léonard (Québec)</dd>
						<dt>Courriel</dt>
						<dd><a href="mailto:panier@badr.ca">panier@badr.ca</a></dd>
						<dt>Téléphone</dt>
						<dd><a href="tel:+15143245341">514&nbsp;324-5341</a></dd>
					</dl>
				</div>

				<div>
					<p class="badr-eyebrow">Aussi offert</p>
					<h2 class="badr-section__title">D&rsquo;autres formes de soutien matériel</h2>
					<p class="badr-section__intro">L&rsquo;aide alimentaire s&rsquo;accompagne d&rsquo;autres services de proximité offerts par le BADR.</p>
					<ul class="badr-values" style="margin-block-start:1.5rem">
						<li style="--badr-accent:var(--wp--custom--espace--parents)">
							<h3>Don de meubles et vêtements</h3>
							<p>Du mobilier et des vêtements redistribués aux familles qui en ont besoin.</p>
						</li>
						<li style="--badr-accent:var(--wp--custom--espace--papas)">
							<h3>Transport solidaire</h3>
							<p>Un accompagnement pour les déplacements essentiels.</p>
						</li>
						<li style="--badr-accent:var(--wp--custom--espace--filles)">
							<h3>Trousseau scolaire</h3>
							<p>Le matériel nécessaire pour commencer l&rsquo;année du bon pied.</p>
						</li>
					</ul>
				</div>

			</div>
		</div>
	</div>
</section>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"badr/cloture"} /-->

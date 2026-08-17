<?php
/**
 * Title: Témoignages — citation éditoriale
 * Slug: badr/temoignages
 * Categories: badr-temoignages
 * Description: Une seule grande citation à la fois, portée par une guillemette dessinée en très grand format, avec nom et contexte, contrôles précédent/suivant, indicateurs de progression et rotation automatique qui s'arrête au survol. Remplace la grille de cartes étroites.
 * Keywords: témoignages, citations, histoires, impact
 * Viewport Width: 1440
 *
 * ─────────────────────────────────────────────────────────────────────────────
 *  PROVENANCE DES TÉMOIGNAGES — À LIRE AVANT TOUTE MODIFICATION
 *
 *  Les citations ci-dessous proviennent du contenu fourni par le BADR pour ce
 *  projet (fichier de contenu de référence, section « Témoignages et histoires
 *  d'impact »). Elles ne sont ni inventées ni reformulées.
 *
 *  Rien n'a été ajouté : aucun prénom, aucun âge, aucun rôle et aucune
 *  expérience ne sort d'ailleurs que de ce contenu.
 *
 *  Si l'organisme constate qu'une de ces citations n'est PAS un témoignage
 *  original vérifié, passez son « verifie » à false : elle s'affichera alors
 *  avec une mention visible « Témoignage à confirmer » au lieu d'être présentée
 *  comme une histoire attestée. Pour la retirer complètement, supprimez son
 *  entrée du tableau.
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * @package BADR
 */

$badr_quotes = array(
	array(
		'quote'   => "En tant que parent seul, les distributions alimentaires de BADR ont été une bouée de sauvetage pour ma famille. Non seulement nous avons reçu de la nourriture de qualité, mais j'ai aussi rencontré des personnes formidables.",
		'short'   => 'Une bouée de sauvetage pour ma famille…',
		'name'    => 'Karim',
		'role'    => 'Bénéficiaire de la banque alimentaire',
		'context' => 'Banque alimentaire',
		'verifie' => true,
	),
	array(
		'quote'   => "Participer aux ateliers de BADR m'a aidée à mieux comprendre les besoins de mes enfants et à instaurer un dialogue plus ouvert avec eux. Aujourd'hui, notre relation est beaucoup plus sereine.",
		'short'   => 'Notre relation est beaucoup plus sereine…',
		'name'    => 'Amal',
		'role'    => 'Mère de deux enfants',
		'context' => 'Espace Parents',
		'verifie' => true,
	),
	array(
		'quote'   => "Grâce à l'Espace Jeunes, j'ai découvert une passion pour le judo. Cela m'a donné confiance en moi et m'a aidé à gérer mes émotions. Maintenant, je suis fier de moi et prêt à relever de nouveaux défis.",
		'short'   => "J'ai découvert une passion pour le judo…",
		'name'    => 'Hassan',
		'role'    => '16 ans',
		'context' => 'Espace Jeunes',
		'verifie' => true,
	),
	array(
		'quote'   => "Être bénévole pour la Banque alimentaire de BADR m'a permis de redonner à ma communauté tout en créant des liens précieux. Voir le sourire des familles me motive à continuer.",
		'short'   => 'Voir le sourire des familles me motive…',
		'name'    => 'Yasmine',
		'role'    => 'Bénévole depuis 2 ans',
		'context' => 'Distribution alimentaire',
		'verifie' => true,
	),
	array(
		'quote'   => "Travailler avec BADR pour organiser des ateliers et la fête des ados a été une opportunité incroyable. Leur équipe est dévouée et leur compréhension des besoins locaux a rendu nos actions particulièrement efficaces.",
		'short'   => 'Leur compréhension des besoins locaux…',
		'name'    => 'Marie-Pier Lachance',
		'role'    => "Coordonnatrice, projet Bâtissons l'avenir de nos jeunes CSL",
		'context' => 'Partenaire',
		'verifie' => true,
	),
);

$badr_total = count( $badr_quotes );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<section class="badr-quotes" data-badr-quotes aria-labelledby="badr-temoignages-titre" aria-roledescription="carrousel">
	<div class="badr-quotes__inner">

		<div class="badr-quotes__head" data-reveal="mask">
			<div>
				<p class="badr-eyebrow">Témoignages</p>
				<h2 class="badr-section__title" id="badr-temoignages-titre">Ce qu’on nous <span class="badr-em">raconte</span></h2>
			</div>
			<a class="badr-link" href="/temoignages/">
				<span>Toutes les histoires d’impact</span>
				<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
			</a>
		</div>

		<div class="badr-quotes__stage">

			<?php
			// La guillemette : élément graphique majeur de la section, dessiné
			// plutôt que composé — un caractère typographique ne tiendrait pas
			// cette échelle proprement.
			?>
			<svg class="badr-quotes__mark" viewBox="0 0 120 100" aria-hidden="true" focusable="false">
				<path fill="currentColor" d="M8 100C8 62 20 30 48 8l10 14C40 38 32 56 32 72h20v28H8Zm60 0C68 62 80 30 108 8l10 14c-18 16-26 34-26 50h20v28H68Z"/>
			</svg>

			<?php foreach ( $badr_quotes as $badr_i => $badr_q ) : ?>
				<figure class="badr-quote<?php echo 0 === $badr_i ? ' is-active' : ''; ?>"
					data-quote
					data-quote-short="<?php echo esc_attr( $badr_q['short'] ); ?>"
					aria-hidden="<?php echo 0 === $badr_i ? 'false' : 'true'; ?>"
					aria-roledescription="témoignage"
					aria-label="<?php echo esc_attr( sprintf( '%d sur %d', $badr_i + 1, $badr_total ) ); ?>">

					<blockquote class="badr-quote__text">
						<p><?php echo esc_html( $badr_q['quote'] ); ?></p>
					</blockquote>

					<figcaption class="badr-quote__who">
						<span class="badr-quote__name"><?php echo esc_html( $badr_q['name'] ); ?></span>
						<span class="badr-quote__role"><?php echo esc_html( $badr_q['role'] ); ?></span>
						<span class="badr-quote__context"><?php echo esc_html( $badr_q['context'] ); ?></span>

						<?php if ( empty( $badr_q['verifie'] ) ) : ?>
							<span class="badr-quote__flag">Témoignage à confirmer</span>
						<?php endif; ?>
					</figcaption>

				</figure>
			<?php endforeach; ?>

			<p class="badr-quotes__peek" data-quote-peek aria-hidden="true"></p>

		</div>

		<div class="badr-quotes__controls">
			<button type="button" class="badr-quotes__btn" data-quote-prev aria-label="Témoignage précédent">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
			</button>
			<button type="button" class="badr-quotes__btn" data-quote-next aria-label="Témoignage suivant">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
			</button>

			<span class="badr-quotes__count" data-quote-count aria-live="polite">1 / <?php echo (int) $badr_total; ?></span>

			<div class="badr-quotes__dots">
				<?php foreach ( $badr_quotes as $badr_i => $badr_q ) : ?>
					<button type="button"
						class="badr-quotes__dot"
						data-quote-dot
						aria-current="<?php echo 0 === $badr_i ? 'true' : 'false'; ?>"
						aria-label="<?php echo esc_attr( sprintf( 'Aller au témoignage %d : %s', $badr_i + 1, $badr_q['name'] ) ); ?>"></button>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</section>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

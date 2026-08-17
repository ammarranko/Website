<?php
/**
 * Title: Nos valeurs en action
 * Slug: badr/valeurs
 * Categories: badr-pages
 * Description: Expérience plein écran pilotée par le défilement : une valeur à la fois occupe l'écran, le fond passe d'une couleur du logo à la suivante, un symbole dessiné se trace, et la valeur suivante est annoncée. Remplace la grille de quatre cellules.
 * Keywords: valeurs, inclusion, solidarité, épanouissement, engagement
 * Viewport Width: 1440
 *
 * Les quatre valeurs et leur définition courte proviennent du contenu vérifié
 * du BADR (« À propos — Nos valeurs fondamentales »). Le texte long développe
 * ces définitions sans engager l'organisme sur des services précis.
 *
 * Sans JavaScript, la classe « is-scrollable » n'est jamais posée : les quatre
 * valeurs s'empilent simplement et restent toutes lisibles.
 *
 * @package BADR
 */

$badr_valeurs = array(
	array(
		'name'   => 'Inclusion',
		'accent' => 'var(--b-azure)',
		'short'  => 'Créer un environnement où chacun trouve sa place, quelle que soit son origine ou son histoire.',
		'long'   => 'Personne n’a à justifier sa présence au BADR. On entre sans dossier à monter, sans parcours à raconter, sans niveau de français à atteindre. C’est la condition pour que les personnes les plus isolées franchissent la porte une première fois.',
		// Une porte ouverte d'où sort un chemin.
		'mark'   => '<path d="M34 100V28l34-12v96Z"/><circle cx="60" cy="64" r="2.4" fill="currentColor"/>'
			. '<path d="M34 100h56"/><path d="M90 100V44"/>'
			. '<path d="M14 100c0-16 8-24 20-28"/>',
	),
	array(
		'name'   => 'Solidarité',
		'accent' => 'var(--b-flame)',
		'short'  => 'Soutenir activement ceux qui en ont besoin grâce à des programmes de soutien direct, des services de proximité et des activités communautaires.',
		'long'   => 'La solidarité du BADR est concrète avant d’être un principe : un panier le lundi, un transport pour un rendez-vous, des fournitures à la rentrée. Elle circule dans les deux sens — beaucoup de bénévoles d’aujourd’hui sont arrivés comme bénéficiaires.',
		// Deux mains qui se rejoignent.
		'mark'   => '<path d="M20 78c0-12 8-20 20-22l16-2"/><path d="M100 78c0-12-8-20-20-22l-16-2"/>'
			. '<path d="M44 54c4-6 12-8 16-8s12 2 16 8"/>'
			. '<path d="M20 78c0 12 10 22 22 22h36c12 0 22-10 22-22"/>'
			. '<path d="M60 34V16M44 40 34 26M76 40l10-14"/>',
	),
	array(
		'name'   => 'Épanouissement',
		'accent' => 'var(--b-leaf)',
		'short'  => 'Encourager le développement personnel et collectif pour que chaque personne puisse s’épanouir pleinement.',
		'long'   => 'Répondre aux besoins essentiels ne suffit pas. Apprendre, créer, bouger, prendre la parole : ce sont ces choses-là qui font qu’une personne se remet à faire des projets plutôt qu’à tenir le mois.',
		// Une fleur qui s'ouvre sur une tige.
		'mark'   => '<path d="M60 104V56"/><path d="M60 74c-14 0-22-8-24-20 14-2 22 6 24 20Z"/>'
			. '<path d="M60 66c14-2 22-10 24-24-14 0-22 8-24 24Z"/>'
			. '<circle cx="60" cy="36" r="12"/><path d="M60 24V12M48 30 38 22M72 30l10-8"/>',
	),
	array(
		'name'   => 'Engagement',
		'accent' => 'var(--b-amber)',
		'short'  => 'Impliquer les membres de la communauté pour qu’ils deviennent des acteurs de changement.',
		'long'   => 'Un organisme communautaire qui décide seul de ce dont le quartier a besoin se trompe. Les espaces du BADR ont été conçus par ses membres, et c’est encore eux qui décident de ce qu’on y fait.',
		// Un nœud tressé, jamais dénoué.
		'mark'   => '<circle cx="60" cy="60" r="34"/>'
			. '<path d="M60 26c18 12 18 56 0 68-18-12-18-56 0-68Z"/>'
			. '<path d="M26 60c12-18 56-18 68 0-12 18-56 18-68 0Z"/>'
			. '<circle cx="60" cy="60" r="5" fill="currentColor"/>',
	),
);

$badr_total = count( $badr_valeurs );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->

<section class="badr-valeurs" data-badr-valeurs aria-labelledby="badr-valeurs-titre"
	style="--b-accent: <?php echo esc_attr( $badr_valeurs[0]['accent'] ); ?>">

	<div class="badr-valeurs__track" data-valeurs-track>
		<div class="badr-valeurs__stage">

			<div class="badr-valeurs__bg" aria-hidden="true"></div>

			<div class="badr-shell badr-valeurs__inner">

				<p class="badr-eyebrow" id="badr-valeurs-titre">Nos valeurs en action</p>

				<ol class="badr-valeurs__list">
					<?php foreach ( $badr_valeurs as $badr_i => $badr_v ) : ?>
						<li class="badr-valeur<?php echo 0 === $badr_i ? ' is-active' : ''; ?>"
							data-valeur
							style="--b-accent: <?php echo esc_attr( $badr_v['accent'] ); ?>">

							<span class="badr-valeur__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $badr_i + 1 ) ); ?></span>

							<svg class="badr-valeur__mark" viewBox="0 0 120 120" aria-hidden="true" focusable="false">
								<?php echo $badr_v['mark']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- tracés internes. ?>
							</svg>

							<div class="badr-valeur__text">
								<h3 class="badr-valeur__name"><?php echo esc_html( $badr_v['name'] ); ?></h3>
								<p class="badr-valeur__short"><?php echo esc_html( $badr_v['short'] ); ?></p>
								<p class="badr-valeur__long"><?php echo esc_html( $badr_v['long'] ); ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ol>

				<div class="badr-valeurs__foot">
					<p class="badr-valeurs__next" data-valeurs-next aria-hidden="true">
						Ensuite&nbsp;· <span><?php echo esc_html( $badr_valeurs[1]['name'] ); ?></span>
					</p>
					<ol class="badr-valeurs__dots" aria-hidden="true">
						<?php foreach ( $badr_valeurs as $badr_i => $badr_v ) : ?>
							<li data-valeurs-dot class="<?php echo 0 === $badr_i ? 'is-active' : ''; ?>"
								style="--b-accent: <?php echo esc_attr( $badr_v['accent'] ); ?>"></li>
						<?php endforeach; ?>
					</ol>
					<p class="badr-valeurs__count" data-valeurs-count aria-live="polite">1 / <?php echo (int) $badr_total; ?></p>
				</div>

			</div>
		</div>
	</div>
</section>

<!-- /wp:html -->
</div>
<!-- /wp:group -->

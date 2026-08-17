<?php
/**
 * Title: Nos objectifs — parcours animé
 * Slug: badr/parcours-objectifs
 * Categories: badr-pages
 * Description: Les quatre engagements du BADR présentés comme un parcours : une ligne se dessine à travers la section, chaque objectif apparaît à un point du tracé, accompagné d'une illustration en trait fin qui se trace elle aussi. Remplace la grille de colonnes de texte.
 * Keywords: objectifs, engagements, mission, parcours, valeurs
 * Viewport Width: 1440
 *
 * Contenu : les quatre objectifs sont repris mot pour mot du contenu vérifié du
 * BADR (« À propos — Nos objectifs »). Rien n'est reformulé ni ajouté.
 *
 * Les illustrations sont dessinées ici en SVG plutôt que reprises d'un jeu
 * d'icônes : elles partagent l'épaisseur de trait du reste du système, et aucun
 * emoji système n'apparaît sur le site.
 *
 * @package BADR
 */

$badr_objectifs = array(
	array(
		'accent' => 'var(--b-amber)',
		'title'  => 'Lutter contre la pauvreté et l’exclusion',
		'lead'   => 'Réduire la pauvreté en luttant contre l’exclusion sociale, tout en offrant un lieu de rencontre et d’entraide pour tous, afin d’améliorer les conditions de vie des membres de la communauté.',
		// Deux mains ouvertes qui portent un bol : l'entraide concrète.
		'scene'  => '<path d="M20 62c0 18 14 30 32 30h16c18 0 32-12 32-30Z"/>'
			. '<path d="M14 62h92"/>'
			. '<path d="M46 44c0-8 6-12 14-12s14 4 14 12"/>'
			. '<path d="M60 32V20"/>'
			. '<path d="M22 96c-6-4-9-10-9-18v-16M98 96c6-4 9-10 9-18v-16"/>',
	),
	array(
		'accent' => 'var(--b-leaf)',
		'title'  => 'Accompagner les jeunes et les enfants',
		'lead'   => 'Assurer un accompagnement et un soutien spécifique pour les jeunes et les enfants vulnérables issus de milieux défavorisés, à travers des activités socio-éducatives, sportives et culturelles.',
		// Un livre ouvert d'où pousse une jeune tige : apprendre et grandir.
		'scene'  => '<path d="M16 84c14-8 28-8 44 0 16-8 30-8 44 0V44c-14-8-28-8-44 0-16-8-30-8-44 0Z"/>'
			. '<path d="M60 44v40"/>'
			. '<path d="M60 44V22"/>'
			. '<path d="M60 30c0-6-5-10-12-11 0 7 5 11 12 11Z"/>'
			. '<path d="M60 34c0-6 5-10 12-11 0 7-5 11-12 11Z"/>',
	),
	array(
		'accent' => 'var(--b-azure)',
		'title'  => 'Renforcer les liens communautaires',
		'lead'   => 'Favoriser l’intégration sociale et culturelle pour renforcer les liens au sein de la communauté, en valorisant la diversité et la solidarité.',
		// Cinq points reliés : un tissage, pas un organigramme.
		'scene'  => '<circle cx="60" cy="26" r="8"/><circle cx="24" cy="52" r="8"/>'
			. '<circle cx="96" cy="52" r="8"/><circle cx="38" cy="94" r="8"/>'
			. '<circle cx="82" cy="94" r="8"/>'
			. '<path d="M53 31 31 47M67 31l22 16M28 60l7 26M92 60l-7 26M46 94h28"/>'
			. '<path d="M32 48c12 10 44 10 56 0"/>',
	),
	array(
		'accent' => 'var(--b-flame)',
		'title'  => 'Promouvoir l’égalité des chances',
		'lead'   => 'Travailler pour l’égalité des chances afin que chaque individu ait la possibilité de réussir, quel que soit son parcours ou son milieu.',
		// Trois socles de hauteurs différentes rendus égaux, sous un même arc.
		'scene'  => '<path d="M14 100h92"/>'
			. '<rect x="18" y="72" width="24" height="28"/>'
			. '<rect x="48" y="60" width="24" height="40"/>'
			. '<rect x="78" y="80" width="24" height="20"/>'
			. '<path d="M30 72V56M60 60V44M90 80V64"/>'
			. '<circle cx="30" cy="50" r="6"/><circle cx="60" cy="38" r="6"/><circle cx="90" cy="58" r="6"/>'
			. '<path d="M20 32c12-14 68-14 80 0"/>',
	),
);
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<section class="badr-parcours" aria-labelledby="badr-objectifs-titre">
	<div class="badr-parcours__inner">

		<div class="badr-head badr-head--split" data-reveal="mask">
			<div>
				<p class="badr-eyebrow">Nos objectifs</p>
				<h2 class="badr-section__title" id="badr-objectifs-titre">Quatre engagements qui guident <span class="badr-em">chaque décision</span></h2>
			</div>
			<div>
				<p class="badr-section__intro">Ils ne sont pas une déclaration d’intention : ils décident de ce que nous ouvrons, de qui nous accueillons et de ce que nous refusons de faire payer.</p>
			</div>
		</div>

		<div class="badr-parcours__track">

			<div class="badr-parcours__line" aria-hidden="true">
				<svg viewBox="0 0 20 1000" preserveAspectRatio="none" focusable="false">
					<path d="M10 0 C 2 180, 18 340, 10 500 C 2 660, 18 840, 10 1000" data-draw style="--b-len:1060"/>
				</svg>
			</div>

			<?php foreach ( $badr_objectifs as $badr_i => $badr_o ) : ?>
				<article class="badr-etape" style="--b-accent: <?php echo esc_attr( $badr_o['accent'] ); ?>; --b-accent-d: <?php echo esc_attr( $badr_o['accent'] ); ?>; --b-i: <?php echo (int) $badr_i; ?>">

					<div class="badr-etape__body">
						<h3 class="badr-etape__title"><?php echo esc_html( $badr_o['title'] ); ?></h3>
						<p class="badr-etape__lead"><?php echo esc_html( $badr_o['lead'] ); ?></p>
					</div>

					<span class="badr-etape__marker" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $badr_i + 1 ) ); ?></span>

					<div class="badr-etape__scene" aria-hidden="true">
						<svg viewBox="0 0 120 120" focusable="false">
							<?php echo $badr_o['scene']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- tracés SVG internes, sans donnée utilisateur. ?>
						</svg>
					</div>

				</article>
			<?php endforeach; ?>

		</div>
	</div>
</section>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

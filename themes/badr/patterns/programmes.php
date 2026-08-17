<?php
/**
 * Title: Programmes — panneaux éditoriaux
 * Slug: badr/programmes
 * Categories: badr-pages
 * Description: Les trois grands volets de programmes du BADR en panneaux pleine largeur alternés : folio chiffré, titre en serif, description courte, liste de services filetée et visuel. Remplace les cartes blanches.
 * Keywords: programmes, services, sports, éducation, événements
 * Viewport Width: 1440
 *
 * Contenu : intégralement dérivé du fichier de contenu vérifié du BADR
 * (« Nos services et activités »). Aucun programme, aucune date, aucun tarif et
 * aucune statistique n'est inventé. Les descriptions courtes reformulent le
 * texte fourni sans en changer le sens.
 *
 * @package BADR
 */

$badr_programmes = array(
	array(
		'folio'   => '01',
		'accent'  => 'var(--wp--custom--espace--papas)',
		'eyebrow' => 'Sports et loisirs',
		'title'   => 'Bouger, se dépenser, appartenir',
		'lead'    => "Des activités sportives et récréatives socio-éducatives, avec un volet intervention et prévention, pour encourager une bonne santé physique et mentale tout en créant des espaces de connexion et d'appartenance.",
		'items'   => array( 'Arts martiaux', 'Activités sportives', 'Sorties communautaires et culturelles' ),
		'image'   => 'espace-jeunes.jpg',
		'alt'     => 'Un groupe de jeunes adultes marche ensemble dans un parc en discutant',
		'tag'     => 'Espace Jeunes · 12 à 25 ans',
		'cta'     => array( 'Voir toutes les activités', '/nos-services-et-activites/' ),
	),
	array(
		'folio'   => '02',
		'accent'  => 'var(--wp--custom--espace--filles)',
		'eyebrow' => 'Éducation et développement',
		'title'   => 'Apprendre, progresser, prendre confiance',
		'lead'    => "Des programmes éducatifs et formatifs qui soutiennent l'épanouissement personnel et renforcent la confiance en soi — deux conditions essentielles du bien-être et de la santé mentale.",
		'items'   => array( 'Soutien scolaire', 'Camp de jour répit parents', 'Programmes éducatifs', 'Ateliers de formation' ),
		'image'   => 'espace-enfants.jpg',
		'alt'     => 'Des enfants participent à une activité de groupe autour d\'une table dans un local communautaire',
		'tag'     => 'Espace Enfants · 6 à 11 ans',
		'cta'     => array( 'Découvrir le camp de jour', '/camp-de-jour/' ),
	),
	array(
		'folio'   => '03',
		'accent'  => 'var(--wp--custom--espace--familles)',
		'eyebrow' => 'Événements et fêtes communautaires',
		'title'   => 'Se rassembler et célébrer la diversité',
		'lead'    => "Des moments de rassemblement pour briser l'isolement et promouvoir la diversité, l'inclusion et la santé mentale collective, à travers des échanges culturels et des célébrations ouvertes à tous.",
		'items'   => array( 'Fêtes de la diversité', 'Fête nationale du Québec', 'Canada Day', 'Journées thématiques' ),
		'image'   => 'communaute-mains.jpg',
		'alt'     => 'De nombreuses mains d\'enfants et d\'adultes tendues vers le centre d\'un cercle, vues d\'en dessous contre un ciel bleu',
		'tag'     => 'Toute la communauté',
		'cta'     => array( 'Voir les prochains événements', '/evenements/' ),
	),
);

// Chaque volet devient une ancre : l'index collant s'en sert pour suivre la
// lecture, et un lien profond reste possible depuis une autre page.
foreach ( $badr_programmes as $badr_i => $badr_p ) {
	$badr_programmes[ $badr_i ]['id'] = 'programme-' . sanitize_title( $badr_p['eyebrow'] );
}
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
<!-- wp:html -->
<section class="badr-section badr-section--wash badr-progs" style="--b-accent: var(--b-leaf); --b-accent-d: var(--b-leaf-d)" aria-labelledby="badr-programmes-titre">
	<?php echo \BADR\Theme\fil( 'penche' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- balisage interne. ?>

	<div class="badr-shell">

		<div class="badr-head badr-head--split" data-reveal="mask">
			<div>
				<p class="badr-eyebrow">Programmes et services</p>
				<h2 class="badr-section__title" id="badr-programmes-titre">Trois façons de faire <span class="badr-em">partie du BADR</span></h2>
			</div>
			<div>
				<p class="badr-section__intro">Que vous soyez jeune, parent, aîné ou une famille en quête de soutien, chaque volet propose des activités gratuites ou à faible coût, ouvertes à toutes et à tous.</p>
			</div>
		</div>

		<div class="badr-progs__layout">

			<nav class="badr-progs__index" data-badr-prog-index aria-label="Les trois volets de programmes">
				<?php foreach ( $badr_programmes as $badr_p ) : ?>
					<a class="badr-progs__index-item" href="#<?php echo esc_attr( $badr_p['id'] ); ?>"
						style="--b-accent: <?php echo esc_attr( $badr_p['accent'] ); ?>; --b-accent-d: <?php echo esc_attr( $badr_p['accent'] ); ?>">
						<span><?php echo esc_html( $badr_p['folio'] ); ?></span>
						<span><?php echo esc_html( $badr_p['eyebrow'] ); ?></span>
					</a>
				<?php endforeach; ?>
			</nav>

			<div class="badr-progs__chapters">
				<?php foreach ( $badr_programmes as $badr_p ) : ?>
					<?php $badr_media = \BADR\Theme\media_by_filename( $badr_p['image'] ); ?>
					<article class="badr-prog" id="<?php echo esc_attr( $badr_p['id'] ); ?>"
						style="--b-accent: <?php echo esc_attr( $badr_p['accent'] ); ?>; --b-accent-d: <?php echo esc_attr( $badr_p['accent'] ); ?>">
						<div class="badr-prog__inner">

							<div class="badr-prog__body" data-reveal="up">
								<span class="badr-prog__folio" aria-hidden="true"><?php echo esc_html( $badr_p['folio'] ); ?></span>
								<p class="badr-prog__eyebrow"><?php echo esc_html( $badr_p['eyebrow'] ); ?></p>
								<h3 class="badr-prog__title"><?php echo esc_html( $badr_p['title'] ); ?></h3>
								<p class="badr-prog__lead"><?php echo esc_html( $badr_p['lead'] ); ?></p>

								<ul class="badr-prog__list" data-reveal-group>
									<?php foreach ( $badr_p['items'] as $badr_item ) : ?>
										<li data-reveal="up"><?php echo esc_html( $badr_item ); ?></li>
									<?php endforeach; ?>
								</ul>

								<a class="badr-btn badr-btn--outline" href="<?php echo esc_url( $badr_p['cta'][1] ); ?>">
									<span><?php echo esc_html( $badr_p['cta'][0] ); ?></span>
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
								</a>
							</div>

							<div class="badr-prog__media" data-reveal="clip">
								<?php if ( $badr_media ) : ?>
									<img src="<?php echo esc_url( $badr_media['url'] ); ?>"
										<?php if ( '' !== $badr_media['srcset'] ) : ?>srcset="<?php echo esc_attr( $badr_media['srcset'] ); ?>"<?php endif; ?>
										sizes="(max-width: 54rem) 92vw, 46vw"
										alt="<?php echo esc_attr( $badr_p['alt'] ); ?>" loading="lazy" decoding="async">
								<?php else : ?>
									<span class="badr-prog__abstract" aria-hidden="true"></span>
								<?php endif; ?>
								<span class="badr-prog__tag"><?php echo esc_html( $badr_p['tag'] ); ?></span>
							</div>

						</div>
					</article>
				<?php endforeach; ?>
			</div>

		</div>
	</div>
</section>
<!-- /wp:html -->
</div>
<!-- /wp:group -->

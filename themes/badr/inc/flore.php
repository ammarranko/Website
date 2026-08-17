<?php
/**
 * Couche botanique et céleste de l'ouverture animée.
 *
 * Entièrement vectorielle : tiges qui se tracent, feuilles qui se déplient,
 * fleurs qui s'ouvrent, étoiles qui scintillent, rayons et anneaux, points de
 * lumière, pétales qui traversent, puis une guirlande finale qui referme le
 * cadre autour de B.A.D.R.
 *
 * Aucune image, aucun canvas, aucun emoji : uniquement des tracés SVG, afin que
 * l'ensemble reste net à toute densité d'écran et cohérent avec le reste du
 * système visuel.
 *
 * Zone protégée : la bande centrale (x 300→1140, y 300→620) reste libre. Les
 * lettres y vivent seules ; rien ne doit venir concurrencer leur lecture.
 *
 * Couleurs : les quatre teintes exactes du logo, en trait fin et à faible
 * opacité. Aucun halo aléatoire, aucune particule générée au hasard.
 *
 * @package BADR
 */

$badr_amber = '#FA8603'; // B
$badr_azure = '#013954'; // A
$badr_leaf  = '#429C2C'; // D
$badr_flame = '#E33313'; // R
$badr_dot   = '#FC8D03';

/**
 * Une fleur à cinq pétales, dessinée une fois puis pivotée.
 *
 * @param string $color  Couleur.
 * @param float  $x      Position x.
 * @param float  $y      Position y.
 * @param float  $scale  Échelle.
 * @param float  $delay  Délai en secondes.
 * @return string
 */
$badr_bloom = static function ( string $color, float $x, float $y, float $scale, float $delay ): string {
	$petals = '';

	for ( $i = 0; $i < 5; $i++ ) {
		$petals .= sprintf(
			'<ellipse cx="0" cy="-11" rx="4.6" ry="11" transform="rotate(%d)" fill="%s" opacity="0.5"/>',
			$i * 72,
			esc_attr( $color )
		);
	}

	return sprintf(
		'<g class="bloom" transform="translate(%.1f %.1f) scale(%.2f)" style="--d:%.2fs">%s'
		. '<circle class="core" r="3.2" fill="%s" opacity="0.8"/></g>',
		$x,
		$y,
		$scale,
		$delay,
		$petals,
		esc_attr( $color )
	);
};

/**
 * Une tige courbe, ses feuilles et sa fleur terminale.
 *
 * @param array{d:string,len:int,delay:float,color:string,leaves:array<int,array<string,float>>,bloom:array<string,float>|null} $s Spécification.
 * @return string
 */
$badr_stem = static function ( array $s ) use ( $badr_bloom ): string {
	$out = sprintf(
		'<path class="stem" d="%s" stroke="%s" style="--len:%d;--d:%.2fs"/>',
		esc_attr( $s['d'] ),
		esc_attr( $s['color'] ),
		(int) $s['len'],
		(float) $s['delay']
	);

	foreach ( $s['leaves'] as $leaf ) {
		$out .= sprintf(
			'<path class="leaf" d="M0 0 C 7 -6, 17 -5, 22 0 C 17 5, 7 6, 0 0 Z" fill="%s"'
			. ' transform="translate(%.1f %.1f) rotate(%.1f) scale(%.2f)" style="--d:%.2fs"/>',
			esc_attr( $s['color'] ),
			(float) $leaf['x'],
			(float) $leaf['y'],
			(float) $leaf['r'],
			isset( $leaf['s'] ) ? (float) $leaf['s'] : 1.0,
			(float) $leaf['d']
		);
	}

	if ( ! empty( $s['bloom'] ) ) {
		$b    = $s['bloom'];
		$out .= $badr_bloom( $s['color'], (float) $b['x'], (float) $b['y'], (float) $b['s'], (float) $b['d'] );
	}

	return $out;
};

/**
 * Une étoile à quatre branches, fine et douce.
 *
 * @param float  $x     Position x.
 * @param float  $y     Position y.
 * @param float  $s     Échelle.
 * @param float  $d     Délai en secondes.
 * @param string $color Couleur.
 * @return string
 */
$badr_star = static function ( float $x, float $y, float $s, float $d, string $color ): string {
	return sprintf(
		'<path class="star" d="M0 -10 Q 1.6 -1.6, 10 0 Q 1.6 1.6, 0 10 Q -1.6 1.6, -10 0 Q -1.6 -1.6, 0 -10 Z"'
		. ' fill="%s" transform="translate(%.1f %.1f) scale(%.2f)" style="--d:%.2fs"/>',
		esc_attr( $color ),
		$x,
		$y,
		$s,
		$d
	);
};

/* -------------------------------------------------------------------------
 * 1. Fond céleste : anneaux et rayons, très en retrait
 * ---------------------------------------------------------------------- */

$badr_sky = sprintf(
	'<ellipse class="orbit" cx="720" cy="450" rx="620" ry="330" stroke="%s" style="--d:0.2s"/>',
	esc_attr( $badr_azure )
);
$badr_sky .= sprintf(
	'<ellipse class="orbit" cx="720" cy="450" rx="470" ry="252" stroke="%s" style="--d:0.5s"/>',
	esc_attr( $badr_amber )
);
$badr_sky .= sprintf(
	'<ellipse class="orbit" cx="720" cy="450" rx="760" ry="408" stroke="%s" style="--d:0.8s"/>',
	esc_attr( $badr_leaf )
);

// Rayons obliques : quatre traits longs qui arrivent des coins.
foreach (
	array(
		array( 'M 60 120 C 240 220, 300 260, 372 316', 420, 1.2, $badr_amber ),
		array( 'M 1380 120 C 1200 220, 1140 260, 1068 316', 420, 1.4, $badr_azure ),
		array( 'M 60 790 C 250 690, 306 650, 372 596', 420, 1.6, $badr_leaf ),
		array( 'M 1380 790 C 1190 690, 1134 650, 1068 596', 420, 1.8, $badr_flame ),
	) as $badr_r
) {
	$badr_sky .= sprintf(
		'<path class="ray" d="%s" stroke="%s" style="--len:%d;--d:%.1fs"/>',
		esc_attr( $badr_r[0] ),
		esc_attr( $badr_r[3] ),
		(int) $badr_r[1],
		(float) $badr_r[2]
	);
}

/* -------------------------------------------------------------------------
 * 2. Colonne gauche : quatre tiges qui montent
 * ---------------------------------------------------------------------- */

$badr_left = $badr_stem(
	array(
		'd'      => 'M110 880 C 44 720, 138 596, 104 440 C 76 316, 158 244, 138 140',
		'len'    => 790,
		'delay'  => 0.6,
		'color'  => $badr_leaf,
		'leaves' => array(
			array( 'x' => 104, 'y' => 690, 'r' => 200, 'd' => 2.2, 's' => 1.15 ),
			array( 'x' => 120, 'y' => 546, 'r' => -20, 'd' => 2.6, 's' => 1.0 ),
			array( 'x' => 94, 'y' => 386, 'r' => 195, 'd' => 3.0, 's' => 1.05 ),
			array( 'x' => 146, 'y' => 248, 'r' => -15, 'd' => 3.4, 's' => 0.88 ),
		),
		'bloom'  => array( 'x' => 138, 'y' => 140, 's' => 1.2, 'd' => 3.9 ),
	)
) . $badr_stem(
	array(
		'd'      => 'M226 900 C 202 764, 268 682, 246 578 C 230 500, 286 456, 274 386',
		'len'    => 540,
		'delay'  => 1.4,
		'color'  => $badr_amber,
		'leaves' => array(
			array( 'x' => 244, 'y' => 736, 'r' => -25, 'd' => 3.0, 's' => 0.85 ),
			array( 'x' => 240, 'y' => 592, 'r' => 200, 'd' => 3.4, 's' => 0.75 ),
		),
		'bloom'  => array( 'x' => 274, 'y' => 386, 's' => 0.85, 'd' => 4.2 ),
	)
) . $badr_stem(
	array(
		'd'      => 'M40 900 C 26 800, 70 742, 56 660 C 46 600, 84 566, 76 512',
		'len'    => 400,
		'delay'  => 2.0,
		'color'  => $badr_flame,
		'leaves' => array(
			array( 'x' => 56, 'y' => 782, 'r' => 205, 'd' => 3.4, 's' => 0.66 ),
			array( 'x' => 62, 'y' => 668, 'r' => -22, 'd' => 3.7, 's' => 0.6 ),
		),
		'bloom'  => array( 'x' => 76, 'y' => 512, 's' => 0.66, 'd' => 4.5 ),
	)
) . $badr_stem(
	array(
		'd'      => 'M300 900 C 288 812, 330 764, 316 700',
		'len'    => 230,
		'delay'  => 2.5,
		'color'  => $badr_azure,
		'leaves' => array(
			array( 'x' => 314, 'y' => 806, 'r' => -28, 'd' => 3.8, 's' => 0.58 ),
		),
		'bloom'  => array( 'x' => 316, 'y' => 700, 's' => 0.56, 'd' => 4.7 ),
	)
);

/* -------------------------------------------------------------------------
 * 3. Colonne droite : symétrie volontairement imparfaite
 * ---------------------------------------------------------------------- */

$badr_right = $badr_stem(
	array(
		'd'      => 'M1330 890 C 1396 730, 1302 606, 1336 450 C 1364 326, 1282 254, 1302 150',
		'len'    => 790,
		'delay'  => 0.9,
		'color'  => $badr_azure,
		'leaves' => array(
			array( 'x' => 1336, 'y' => 700, 'r' => -20, 'd' => 2.5, 's' => 1.15 ),
			array( 'x' => 1320, 'y' => 556, 'r' => 200, 'd' => 2.9, 's' => 1.0 ),
			array( 'x' => 1346, 'y' => 396, 'r' => -15, 'd' => 3.3, 's' => 1.05 ),
			array( 'x' => 1294, 'y' => 258, 'r' => 195, 'd' => 3.7, 's' => 0.88 ),
		),
		'bloom'  => array( 'x' => 1302, 'y' => 150, 's' => 1.15, 'd' => 4.1 ),
	)
) . $badr_stem(
	array(
		'd'      => 'M1214 900 C 1238 764, 1172 682, 1194 578 C 1210 500, 1154 456, 1166 386',
		'len'    => 540,
		'delay'  => 1.7,
		'color'  => $badr_flame,
		'leaves' => array(
			array( 'x' => 1196, 'y' => 736, 'r' => 205, 'd' => 3.2, 's' => 0.85 ),
			array( 'x' => 1200, 'y' => 592, 'r' => -22, 'd' => 3.6, 's' => 0.75 ),
		),
		'bloom'  => array( 'x' => 1166, 'y' => 386, 's' => 0.85, 'd' => 4.4 ),
	)
) . $badr_stem(
	array(
		'd'      => 'M1400 900 C 1414 800, 1370 742, 1384 660 C 1394 600, 1356 566, 1364 512',
		'len'    => 400,
		'delay'  => 2.2,
		'color'  => $badr_leaf,
		'leaves' => array(
			array( 'x' => 1384, 'y' => 782, 'r' => -25, 'd' => 3.5, 's' => 0.66 ),
			array( 'x' => 1378, 'y' => 668, 'r' => 202, 'd' => 3.8, 's' => 0.6 ),
		),
		'bloom'  => array( 'x' => 1364, 'y' => 512, 's' => 0.66, 'd' => 4.6 ),
	)
) . $badr_stem(
	array(
		'd'      => 'M1140 900 C 1152 812, 1110 764, 1124 700',
		'len'    => 230,
		'delay'  => 2.7,
		'color'  => $badr_amber,
		'leaves' => array(
			array( 'x' => 1126, 'y' => 806, 'r' => 208, 'd' => 3.9, 's' => 0.58 ),
		),
		'bloom'  => array( 'x' => 1124, 'y' => 700, 's' => 0.56, 'd' => 4.8 ),
	)
);

/* -------------------------------------------------------------------------
 * 4. Guirlande finale : le cadre floral qui se referme autour de B.A.D.R.
 *
 * Elle arrive après les lettres et le nom complet — c'est la dernière chose
 * que l'ouverture dessine, comme une signature.
 * ---------------------------------------------------------------------- */

$badr_frame = sprintf(
	'<path class="garland" d="M 396 268 C 540 196, 900 196, 1044 268" stroke="%s" style="--len:700;--d:4.6s"/>',
	esc_attr( $badr_amber )
);
$badr_frame .= sprintf(
	'<path class="garland" d="M 396 664 C 540 736, 900 736, 1044 664" stroke="%s" style="--len:700;--d:4.9s"/>',
	esc_attr( $badr_leaf )
);

// Petites feuilles posées le long des deux arcs.
foreach (
	array(
		array( 470, 232, -28, 5.4, $badr_amber ),
		array( 620, 208, -12, 5.5, $badr_amber ),
		array( 820, 208, 192, 5.6, $badr_leaf ),
		array( 970, 232, 208, 5.7, $badr_leaf ),
		array( 470, 700, 28, 5.7, $badr_leaf ),
		array( 620, 724, 12, 5.8, $badr_leaf ),
		array( 820, 724, 168, 5.9, $badr_amber ),
		array( 970, 700, 152, 6.0, $badr_amber ),
	) as $badr_gl
) {
	$badr_frame .= sprintf(
		'<path class="leaf" d="M0 0 C 7 -6, 17 -5, 22 0 C 17 5, 7 6, 0 0 Z" fill="%s"'
		. ' transform="translate(%.1f %.1f) rotate(%.1f) scale(0.62)" style="--d:%.2fs"/>',
		esc_attr( $badr_gl[4] ),
		(float) $badr_gl[0],
		(float) $badr_gl[1],
		(float) $badr_gl[2],
		(float) $badr_gl[3]
	);
}

// Fleurs aux quatre extrémités de la guirlande : une par couleur du logo.
$badr_frame .= $badr_bloom( $badr_amber, 396, 268, 0.72, 5.2 );
$badr_frame .= $badr_bloom( $badr_azure, 1044, 268, 0.72, 5.3 );
$badr_frame .= $badr_bloom( $badr_leaf, 396, 664, 0.72, 5.4 );
$badr_frame .= $badr_bloom( $badr_flame, 1044, 664, 0.72, 5.5 );

/* -------------------------------------------------------------------------
 * 5. Étoiles, points de lumière et pétales
 * ---------------------------------------------------------------------- */

$badr_stars = ''
	. $badr_star( 330, 176, 0.95, 4.4, $badr_dot )
	. $badr_star( 452, 108, 0.62, 4.7, $badr_azure )
	. $badr_star( 214, 288, 0.55, 5.0, $badr_amber )
	. $badr_star( 566, 84, 0.42, 5.2, $badr_leaf )
	. $badr_star( 1112, 158, 0.9, 4.5, $badr_dot )
	. $badr_star( 998, 96, 0.58, 4.8, $badr_azure )
	. $badr_star( 1252, 276, 0.55, 5.1, $badr_flame )
	. $badr_star( 880, 78, 0.42, 5.3, $badr_amber )
	. $badr_star( 386, 782, 0.62, 5.5, $badr_leaf )
	. $badr_star( 1064, 800, 0.68, 5.6, $badr_leaf )
	. $badr_star( 690, 842, 0.48, 5.8, $badr_dot )
	. $badr_star( 786, 60, 0.46, 6.0, $badr_azure )
	. $badr_star( 160, 520, 0.5, 6.1, $badr_flame )
	. $badr_star( 1290, 540, 0.5, 6.2, $badr_amber );

$badr_motes = '';
foreach (
	array(
		array( 348, 560, 5.0 ),
		array( 1108, 500, 6.0 ),
		array( 252, 430, 7.0 ),
		array( 1234, 600, 8.0 ),
		array( 430, 700, 9.0 ),
		array( 1010, 720, 10.0 ),
	) as $badr_m
) {
	$badr_motes .= sprintf(
		'<circle class="mote" cx="%d" cy="%d" r="2.2" fill="%s" style="--d:%.1fs"/>',
		(int) $badr_m[0],
		(int) $badr_m[1],
		esc_attr( $badr_dot ),
		(float) $badr_m[2]
	);
}

// Quelques pétales qui traversent lentement la composition, en périphérie.
$badr_petals = '';
foreach (
	array(
		array( 210, 200, 0.9, 6.0, $badr_amber ),
		array( 1230, 180, 0.8, 8.5, $badr_flame ),
		array( 140, 620, 0.7, 11.0, $badr_leaf ),
		array( 1300, 660, 0.85, 13.5, $badr_azure ),
	) as $badr_pt
) {
	$badr_petals .= sprintf(
		'<ellipse class="petal" cx="%d" cy="%d" rx="%.1f" ry="%.1f" fill="%s" opacity="0.5" style="--d:%.1fs"/>',
		(int) $badr_pt[0],
		(int) $badr_pt[1],
		4.4 * (float) $badr_pt[2],
		10.5 * (float) $badr_pt[2],
		esc_attr( $badr_pt[4] ),
		(float) $badr_pt[3]
	);
}
?>
<div class="badr-flore" aria-hidden="true">
	<svg viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" focusable="false">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- toutes les valeurs sont échappées dans les fabriques ci-dessus.
		echo $badr_sky . $badr_left . $badr_right . $badr_frame . $badr_stars . $badr_motes . $badr_petals;
		?>
	</svg>
</div>

<?php
/**
 * Diagnostic local jetable : état des compositions et des médias.
 *
 * npx wp-env run cli wp eval-file wp-content/badr-project/tools/diagnose.php
 *
 * @package BADR
 */

$all = WP_Block_Patterns_Registry::get_instance()->get_all_registered();
WP_CLI::log( 'Compositions enregistrées au total : ' . count( $all ) );

$theme_patterns = 0;
foreach ( $all as $p ) {
	if ( 0 === strpos( $p['name'], 'badr/' ) ) {
		++$theme_patterns;
		$len = isset( $p['content'] ) ? strlen( (string) $p['content'] ) : -1;
		WP_CLI::log( sprintf( '  %-28s content=%d filePath=%s', $p['name'], $len, isset( $p['filePath'] ) ? basename( (string) $p['filePath'] ) : 'aucun' ) );
	}
}
WP_CLI::log( 'Compositions BADR : ' . $theme_patterns );

$dir = get_template_directory() . '/patterns';
WP_CLI::log( 'Dossier patterns présent : ' . ( is_dir( $dir ) ? 'oui' : 'NON' ) );
if ( is_dir( $dir ) ) {
	foreach ( (array) scandir( $dir ) as $f ) {
		if ( '.' === $f || '..' === $f ) {
			continue;
		}
		$data = get_file_data( $dir . '/' . $f, array( 'title' => 'Title', 'slug' => 'Slug' ) );
		WP_CLI::log( sprintf( '  %-30s Title=%-40s Slug=%s', $f, $data['title'], $data['slug'] ) );
	}
}

WP_CLI::log( '' );
WP_CLI::log( 'Fichiers téléversés « little-kids » :' );
foreach ( (array) glob( WP_CONTENT_DIR . '/uploads/*/*/little-kids*' ) as $f ) {
	WP_CLI::log( sprintf( '  %-60s %d octets', basename( (string) $f ), (int) filesize( (string) $f ) ) );
}

WP_CLI::log( '' );
WP_CLI::log( 'URL des pièces jointes :' );
$atts = get_posts(
	array(
		'post_type'      => 'attachment',
		'posts_per_page' => -1,
		'post_status'    => 'inherit',
	)
);
foreach ( $atts as $a ) {
	$file = get_attached_file( $a->ID );
	WP_CLI::log(
		sprintf(
			'  #%-3d %-58s existe=%s',
			$a->ID,
			basename( (string) wp_get_attachment_url( $a->ID ) ),
			( $file && file_exists( $file ) ) ? 'oui' : 'NON'
		)
	);
}

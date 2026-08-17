<?php
/**
 * Vide la médiathèque du site LOCAL, puis laisse tools/seed.php réimporter
 * uniquement le jeu curé de images/web.
 *
 * npx wp-env run cli wp eval-file wp-content/badr-project/tools/reset-media.php
 *
 * Destructif par conception, mais sans risque : ce script refuse de s'exécuter
 * ailleurs que sur un environnement local, et tout le contenu supprimé est
 * reconstruit par le seed depuis les fichiers du dépôt.
 *
 * @package BADR
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( "Ce script doit être exécuté par WP-CLI.\n" );
}

// Garde-fou : jamais sur autre chose qu'un site local.
$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );

if ( 'local' !== wp_get_environment_type() && 'localhost' !== $host && '127.0.0.1' !== $host ) {
	WP_CLI::error( "Refus d'exécution : ce site n'est pas local (hôte « {$host} »)." );
}

$ids = get_posts(
	array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	)
);

WP_CLI::log( count( $ids ) . ' pièce(s) jointe(s) à supprimer.' );

$deleted = 0;
foreach ( $ids as $id ) {
	if ( wp_delete_attachment( (int) $id, true ) ) {
		++$deleted;
	}
}

remove_theme_mod( 'custom_logo' );

WP_CLI::success( $deleted . ' pièce(s) jointe(s) supprimée(s). Relancez tools/seed.php.' );

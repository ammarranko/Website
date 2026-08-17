<?php
/**
 * Plugin Name:       BADR — Noyau du site
 * Plugin URI:        https://badr.ca/
 * Description:       Fonctionnalités persistantes du site du BADR : rôle « Gestionnaire d'événements » à privilèges minimaux, génération d'événements récurrents, et données structurées. Volontairement séparé du thème afin qu'un changement de thème ne supprime ni les rôles ni les événements.
 * Version:           0.1.0
 * Requires at least: 6.6
 * Requires PHP:      8.0
 * Author:            Bureau Associatif pour la Diversité et la Réinsertion
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       badr-site-core
 * Domain Path:       /languages
 *
 * @package BADR_Site_Core
 */

declare( strict_types = 1 );

namespace BADR\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const VERSION  = '0.1.0';
const ROLE     = 'badr_event_manager';
const PLUGIN_FILE = __FILE__;

/**
 * Capacités accordées au rôle « Gestionnaire d'événements ».
 *
 * Principe du moindre privilège : ce rôle peut gérer entièrement les événements
 * et les inscriptions, mais ne peut jamais toucher aux thèmes, aux plugins, aux
 * utilisateurs, aux réglages, ni aux pages de contenu du site.
 *
 * Les capacités propres aux plugins d'événements ne sont ajoutées que si le
 * plugin correspondant est actif, afin de ne rien accorder à l'aveugle.
 *
 * @return array<string,bool>
 */
function role_capabilities(): array {
	$caps = array(
		// Accès minimal à l'administration.
		'read'                   => true,
		'upload_files'           => true,

		// Gestion complète des événements (type d'objet du plugin).
		'edit_tribe_events'      => true,
		'edit_others_tribe_events' => true,
		'publish_tribe_events'   => true,
		'delete_tribe_events'    => true,
		'edit_published_tribe_events'   => true,
		'delete_published_tribe_events' => true,
		'read_private_tribe_events'     => true,

		// Lieux et organisateurs, nécessaires pour créer un événement complet.
		'edit_tribe_venues'      => true,
		'publish_tribe_venues'   => true,
		'edit_tribe_organizers'  => true,
		'publish_tribe_organizers' => true,
	);

	/**
	 * Filtre les capacités du rôle Gestionnaire d'événements.
	 *
	 * @param array<string,bool> $caps Capacités.
	 */
	return (array) apply_filters( 'badr_event_manager_capabilities', $caps );
}

/**
 * Crée ou met à jour le rôle à l'activation du plugin.
 */
function activate(): void {
	remove_role( ROLE );
	add_role( ROLE, __( 'Gestionnaire d\'événements', 'badr-site-core' ), role_capabilities() );

	// Marque la version afin de pouvoir migrer les capacités plus tard.
	update_option( 'badr_core_version', VERSION );

	flush_rewrite_rules();
}
register_activation_hook( PLUGIN_FILE, __NAMESPACE__ . '\\activate' );

/**
 * Retire le rôle à la désactivation, sans toucher aux comptes existants.
 *
 * Les utilisateurs qui portaient ce rôle retombent sur leur rôle par défaut ;
 * aucun compte n'est supprimé.
 */
function deactivate(): void {
	remove_role( ROLE );
	flush_rewrite_rules();
}
register_deactivation_hook( PLUGIN_FILE, __NAMESPACE__ . '\\deactivate' );

/**
 * Réaligne les capacités si la version du plugin a changé.
 *
 * Évite qu'une mise à jour laisse un rôle avec d'anciennes capacités.
 */
function maybe_upgrade_role(): void {
	if ( get_option( 'badr_core_version' ) === VERSION ) {
		return;
	}

	$role = get_role( ROLE );

	if ( $role instanceof \WP_Role ) {
		foreach ( role_capabilities() as $cap => $grant ) {
			$role->add_cap( $cap, $grant );
		}
	} else {
		add_role( ROLE, __( 'Gestionnaire d\'événements', 'badr-site-core' ), role_capabilities() );
	}

	update_option( 'badr_core_version', VERSION );
}
add_action( 'admin_init', __NAMESPACE__ . '\\maybe_upgrade_role' );

/**
 * Empêche le gestionnaire d'événements d'accéder à l'éditeur de site.
 *
 * Défense en profondeur : les capacités ci-dessus ne l'autorisent déjà pas,
 * mais on retire aussi les entrées de menu correspondantes.
 */
function restrict_admin_menu(): void {
	$user = wp_get_current_user();

	// N'agit que sur les comptes portant exactement ce rôle. Un administrateur
	// qui porterait aussi ce rôle garde son menu complet.
	if ( ! $user->exists() || ! in_array( ROLE, (array) $user->roles, true ) ) {
		return;
	}

	if ( user_can( $user, 'manage_options' ) ) {
		return;
	}

	remove_menu_page( 'themes.php' );
	remove_menu_page( 'plugins.php' );
	remove_menu_page( 'users.php' );
	remove_menu_page( 'tools.php' );
	remove_menu_page( 'options-general.php' );
	remove_menu_page( 'edit.php' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\restrict_admin_menu', 999 );

/**
 * Charge les modules du plugin.
 *
 * Chaque module reste indépendant afin de pouvoir être désactivé sans casser
 * le reste. Les fichiers absents sont ignorés silencieusement pendant le
 * développement local.
 */
function bootstrap(): void {
	$modules = array(
		'includes/class-programs.php',
		'includes/class-recurrence.php',
		'includes/class-structured-data.php',
	);

	foreach ( $modules as $module ) {
		$path = plugin_dir_path( PLUGIN_FILE ) . $module;

		if ( is_readable( $path ) ) {
			require_once $path;
		}
	}
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );

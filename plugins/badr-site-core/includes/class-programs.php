<?php
/**
 * Modèle de contenu des programmes et activités du BADR.
 *
 * Ce module vit dans le plugin et non dans le thème : le répertoire des
 * services doit survivre à un changement de thème, comme les événements.
 *
 * Il crée :
 *   · le type d'objet « badr_program » — une activité = une entrée éditable ;
 *   · la taxonomie « badr_famille » — les quatre familles de services ;
 *   · la taxonomie « badr_espace »  — les espaces communautaires visés ;
 *   · les métadonnées pratiques (public, âges, horaire, lieu, prix, inscription).
 *
 * Rien n'est codé en dur dans un gabarit : un membre du personnel ajoute une
 * activité, l'assigne à une famille et à un ou plusieurs espaces, remplit les
 * champs pratiques et publie — sans toucher au code.
 *
 * Aucun second système d'événements n'est introduit : les séances à venir
 * restent gérées par le plugin d'événements existant, et sont reliées ici par
 * une simple correspondance de famille et d'espace.
 *
 * @package BADR_Site_Core
 */

declare( strict_types = 1 );

namespace BADR\Core\Programs;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CPT      = 'badr_program';
const TAX_FAM  = 'badr_famille';
const TAX_ESP  = 'badr_espace';
const META_KEY = '_badr_program';

/**
 * Champs pratiques d'une activité.
 *
 * « verifiable » à true signifie : ce champ décrit une réalité opérationnelle.
 * Tant qu'il est vide, le gabarit n'affiche rien plutôt que d'inventer une
 * valeur — ou affiche « À confirmer » si l'activité est publiée sans lui.
 *
 * @return array<string,array{label:string,type:string,help:string}>
 */
function fields(): array {
	return array(
		'summary'      => array(
			'label' => 'Résumé court',
			'type'  => 'textarea',
			'help'  => 'Une ou deux phrases, affichées dans le répertoire.',
		),
		'audience'     => array(
			'label' => 'À qui ça s’adresse',
			'type'  => 'text',
			'help'  => 'Ex. : Familles, Jeunes, Aînés. Laisser vide si non défini.',
		),
		'ages'         => array(
			'label' => 'Groupe d’âge',
			'type'  => 'text',
			'help'  => 'Ex. : 6 à 11 ans. Laisser vide si non confirmé — rien ne sera inventé.',
		),
		'schedule'     => array(
			'label' => 'Horaire',
			'type'  => 'text',
			'help'  => 'Ex. : Tous les lundis, de 15 h 00 à 17 h 00. Laisser vide si non confirmé.',
		),
		'location'     => array(
			'label' => 'Lieu',
			'type'  => 'text',
			'help'  => 'Adresse complète. Laisser vide si non confirmé.',
		),
		'price'        => array(
			'label' => 'Coût',
			'type'  => 'text',
			'help'  => 'Ex. : Gratuit. Laisser vide si non confirmé.',
		),
		'registration' => array(
			'label' => 'Inscription',
			'type'  => 'select',
			'help'  => 'Détermine le bouton affiché sur la page du programme.',
		),
		'reg_url'      => array(
			'label' => 'Lien d’inscription',
			'type'  => 'url',
			'help'  => 'Obligatoire si l’inscription est ouverte : sans lien, le bouton n’est pas affiché.',
		),
		'contact'      => array(
			'label' => 'Contact',
			'type'  => 'text',
			'help'  => 'Courriel ou téléphone pour les renseignements.',
		),
	);
}

/**
 * Les états d'inscription possibles.
 *
 * @return array<string,string>
 */
function registration_states(): array {
	return array(
		'info'    => 'Renseignements sur demande',
		'ouverte' => 'Inscription ouverte',
		'bientot' => 'Inscription à venir',
		'fermee'  => 'Inscription fermée',
		'aucune'  => 'Sans inscription — accès libre',
	);
}

/**
 * Déclare le type d'objet et ses taxonomies.
 */
function register(): void {
	register_post_type(
		CPT,
		array(
			'labels'        => array(
				'name'               => __( 'Programmes', 'badr-site-core' ),
				'singular_name'      => __( 'Programme', 'badr-site-core' ),
				'menu_name'          => __( 'Programmes', 'badr-site-core' ),
				'add_new'            => __( 'Ajouter une activité', 'badr-site-core' ),
				'add_new_item'       => __( 'Ajouter une activité', 'badr-site-core' ),
				'edit_item'          => __( 'Modifier l’activité', 'badr-site-core' ),
				'search_items'       => __( 'Rechercher une activité', 'badr-site-core' ),
				'not_found'          => __( 'Aucune activité pour l’instant.', 'badr-site-core' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
			'has_archive'   => false,
			'rewrite'       => array(
				'slug'       => 'activites',
				'with_front' => false,
			),
			'taxonomies'    => array( TAX_FAM, TAX_ESP ),
		)
	);

	register_taxonomy(
		TAX_FAM,
		array( CPT ),
		array(
			'labels'            => array(
				'name'          => __( 'Familles de services', 'badr-site-core' ),
				'singular_name' => __( 'Famille de services', 'badr-site-core' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'familles',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		TAX_ESP,
		array( CPT ),
		array(
			'labels'            => array(
				'name'          => __( 'Espaces communautaires', 'badr-site-core' ),
				'singular_name' => __( 'Espace communautaire', 'badr-site-core' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'espaces',
				'with_front' => false,
			),
		)
	);

	foreach ( array_keys( fields() ) as $key ) {
		register_post_meta(
			CPT,
			META_KEY . '_' . $key,
			array(
				'single'            => true,
				'type'              => 'string',
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
			)
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\\register' );

/**
 * Métadonnées d'une activité, avec valeurs vides plutôt qu'inventées.
 *
 * @param int $post_id Identifiant.
 * @return array<string,string>
 */
function meta( int $post_id ): array {
	$out = array();

	foreach ( array_keys( fields() ) as $key ) {
		$out[ $key ] = (string) get_post_meta( $post_id, META_KEY . '_' . $key, true );
	}

	return $out;
}

/* -------------------------------------------------------------------------
 * Écran d'administration
 * ---------------------------------------------------------------------- */

/**
 * Ajoute la boîte des informations pratiques.
 */
function add_meta_box(): void {
	\add_meta_box(
		'badr-program-details',
		__( 'Informations pratiques', 'badr-site-core' ),
		__NAMESPACE__ . '\\render_meta_box',
		CPT,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\\add_meta_box' );

/**
 * Affiche la boîte.
 *
 * @param \WP_Post $post Article courant.
 */
function render_meta_box( \WP_Post $post ): void {
	wp_nonce_field( 'badr_program_save', 'badr_program_nonce' );

	$values = meta( $post->ID );

	echo '<style>.badr-mb{display:grid;gap:1rem}.badr-mb label{display:block;font-weight:600;margin-bottom:.25rem}'
		. '.badr-mb input,.badr-mb textarea,.badr-mb select{width:100%}.badr-mb p{margin:.25rem 0 0;color:#666;font-size:12px}'
		. '.badr-mb__note{background:#fff8e5;border-left:4px solid #dba617;padding:.75rem 1rem;margin:0}</style>';

	echo '<div class="badr-mb">';
	echo '<p class="badr-mb__note">Laissez un champ <strong>vide</strong> si l’information n’est pas confirmée : '
		. 'le site n’affichera rien plutôt qu’une valeur inventée.</p>';

	foreach ( fields() as $key => $field ) {
		$id  = 'badr_program_' . $key;
		$val = $values[ $key ];

		echo '<div><label for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] ) . '</label>';

		if ( 'textarea' === $field['type'] ) {
			printf(
				'<textarea id="%1$s" name="%1$s" rows="3">%2$s</textarea>',
				esc_attr( $id ),
				esc_textarea( $val )
			);
		} elseif ( 'select' === $field['type'] ) {
			printf( '<select id="%1$s" name="%1$s">', esc_attr( $id ) );
			foreach ( registration_states() as $state => $label ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $state ),
					selected( $val, $state, false ),
					esc_html( $label )
				);
			}
			echo '</select>';
		} else {
			printf(
				'<input type="%1$s" id="%2$s" name="%2$s" value="%3$s">',
				'url' === $field['type'] ? 'url' : 'text',
				esc_attr( $id ),
				esc_attr( $val )
			);
		}

		echo '<p>' . esc_html( $field['help'] ) . '</p></div>';
	}

	echo '</div>';
}

/**
 * Enregistre les informations pratiques.
 *
 * @param int $post_id Identifiant.
 */
function save_meta( int $post_id ): void {
	if ( ! isset( $_POST['badr_program_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( (string) $_POST['badr_program_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'badr_program_save' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( fields() as $key => $field ) {
		$name = 'badr_program_' . $key;

		if ( ! isset( $_POST[ $name ] ) ) {
			continue;
		}

		$raw = wp_unslash( (string) $_POST[ $name ] );

		if ( 'url' === $field['type'] ) {
			$clean = esc_url_raw( $raw );
		} elseif ( 'textarea' === $field['type'] ) {
			$clean = sanitize_textarea_field( $raw );
		} elseif ( 'select' === $field['type'] ) {
			$clean = array_key_exists( $raw, registration_states() ) ? $raw : 'info';
		} else {
			$clean = sanitize_text_field( $raw );
		}

		update_post_meta( $post_id, META_KEY . '_' . $key, $clean );
	}
}
add_action( 'save_post_' . CPT, __NAMESPACE__ . '\\save_meta' );

/**
 * Autorise le gestionnaire d'événements à gérer aussi les programmes.
 *
 * Même principe de moindre privilège : il touche au contenu de son périmètre,
 * jamais aux réglages, aux thèmes ni aux utilisateurs.
 *
 * @param array<string,bool> $caps Capacités existantes.
 * @return array<string,bool>
 */
function role_caps( array $caps ): array {
	return $caps + array(
		'edit_posts'              => true,
		'edit_published_posts'    => true,
		'publish_posts'           => true,
		'delete_posts'            => true,
		'manage_categories'       => true,
	);
}
add_filter( 'badr_event_manager_capabilities', __NAMESPACE__ . '\\role_caps' );

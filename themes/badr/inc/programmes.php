<?php
/**
 * Lecture du répertoire des programmes, côté thème.
 *
 * Le modèle de contenu vit dans le plugin badr-site-core ; le thème ne fait
 * que le lire. Si le plugin est désactivé, toutes les fonctions ci-dessous
 * retournent des tableaux vides et les gabarits affichent leur état vide —
 * jamais de fausses activités.
 *
 * @package BADR
 */

namespace BADR\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const PROGRAM_CPT = 'badr_program';
const TAX_FAMILY  = 'badr_famille';
const TAX_SPACE   = 'badr_espace';

/**
 * Le modèle de contenu est-il disponible ?
 */
function programs_available(): bool {
	return post_type_exists( PROGRAM_CPT );
}

/**
 * Couleur d'accent d'une famille ou d'un espace, dérivée du logo.
 *
 * Définie ici plutôt qu'en base : c'est une décision de design, pas une donnée
 * éditoriale. Un terme inconnu retombe sur l'ambre du logo.
 *
 * @param string $slug Identifiant du terme.
 * @return string Valeur CSS.
 */
function term_accent( string $slug ): string {
	$map = array(
		// Familles de services.
		'soutien-communautaire'   => 'var(--b-flame)',
		'education-developpement' => 'var(--b-azure)',
		'sports-loisirs'          => 'var(--b-leaf)',
		'evenements-fetes'        => 'var(--b-amber)',
		// Espaces communautaires.
		'parents'                 => 'var(--b-azure)',
		'papas'                   => '#1084C2',
		'femmes-mamans'           => 'var(--b-flame)',
		'familles'                => 'var(--b-amber)',
		'filles'                  => 'var(--b-leaf)',
		'aines'                   => '#8A6400',
	);

	return $map[ $slug ] ?? 'var(--b-amber)';
}

/**
 * Les termes d'une taxonomie, avec leur nombre d'activités réel.
 *
 * Le compte n'est affiché que s'il est vrai : aucun chiffre décoratif.
 *
 * @param string $taxonomy Taxonomie.
 * @return array<int,array{slug:string,name:string,desc:string,count:int,url:string,accent:string}>
 */
function taxonomy_terms( string $taxonomy ): array {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return array();
	}

	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'orderby'    => 'term_order',
		)
	);

	if ( is_wp_error( $terms ) ) {
		return array();
	}

	$out = array();

	foreach ( $terms as $term ) {
		$out[] = array(
			'slug'   => $term->slug,
			'name'   => $term->name,
			'desc'   => $term->description,
			'count'  => (int) $term->count,
			'url'    => (string) get_term_link( $term ),
			'accent' => term_accent( $term->slug ),
		);
	}

	return $out;
}

/**
 * Les activités publiées, prêtes à être affichées.
 *
 * @param array<string,mixed> $args Filtres facultatifs (family, space, limit).
 * @return array<int,array<string,mixed>>
 */
function programs( array $args = array() ): array {
	if ( ! programs_available() ) {
		return array();
	}

	$query = array(
		'post_type'      => PROGRAM_CPT,
		'post_status'    => 'publish',
		'posts_per_page' => $args['limit'] ?? -1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
	);

	$tax = array();

	if ( ! empty( $args['family'] ) ) {
		$tax[] = array(
			'taxonomy' => TAX_FAMILY,
			'field'    => 'slug',
			'terms'    => (array) $args['family'],
		);
	}

	if ( ! empty( $args['space'] ) ) {
		$tax[] = array(
			'taxonomy' => TAX_SPACE,
			'field'    => 'slug',
			'terms'    => (array) $args['space'],
		);
	}

	if ( $tax ) {
		$query['tax_query'] = $tax; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	}

	if ( ! empty( $args['exclude'] ) ) {
		$query['post__not_in'] = (array) $args['exclude'];
	}

	$posts = get_posts( $query );
	$out   = array();

	foreach ( $posts as $post ) {
		$out[] = program_data( $post );
	}

	return $out;
}

/**
 * Normalise une activité pour l'affichage.
 *
 * @param \WP_Post $post Activité.
 * @return array<string,mixed>
 */
function program_data( \WP_Post $post ): array {
	$meta = function_exists( '\\BADR\\Core\\Programs\\meta' )
		? \BADR\Core\Programs\meta( $post->ID )
		: array();

	$families = wp_get_post_terms( $post->ID, TAX_FAMILY );
	$spaces   = wp_get_post_terms( $post->ID, TAX_SPACE );

	$family = ( ! is_wp_error( $families ) && $families ) ? $families[0] : null;

	return array(
		'id'           => $post->ID,
		'title'        => (string) get_the_title( $post ),
		'url'          => (string) get_permalink( $post ),
		'summary'      => (string) ( $meta['summary'] ?? '' ),
		'audience'     => (string) ( $meta['audience'] ?? '' ),
		'ages'         => (string) ( $meta['ages'] ?? '' ),
		'schedule'     => (string) ( $meta['schedule'] ?? '' ),
		'location'     => (string) ( $meta['location'] ?? '' ),
		'price'        => (string) ( $meta['price'] ?? '' ),
		'registration' => (string) ( $meta['registration'] ?? 'info' ),
		'reg_url'      => (string) ( $meta['reg_url'] ?? '' ),
		'contact'      => (string) ( $meta['contact'] ?? '' ),
		'thumb'        => (int) get_post_thumbnail_id( $post->ID ),
		'family'       => $family ? $family->name : '',
		'family_slug'  => $family ? $family->slug : '',
		'accent'       => $family ? term_accent( $family->slug ) : 'var(--b-amber)',
		'spaces'       => ( ! is_wp_error( $spaces ) )
			? array_map( static fn( $t ): array => array( 'slug' => $t->slug, 'name' => $t->name ), $spaces )
			: array(),
	);
}

/**
 * Libellé public d'un état d'inscription.
 *
 * @param string $state État.
 * @return string
 */
function registration_label( string $state ): string {
	$labels = array(
		'ouverte' => 'Inscription ouverte',
		'bientot' => 'Inscription à venir',
		'fermee'  => 'Inscription fermée',
		'aucune'  => 'Accès libre',
		'info'    => 'Renseignements sur demande',
	);

	return $labels[ $state ] ?? $labels['info'];
}

/**
 * Le bouton d'action d'une activité.
 *
 * Un bouton « S'inscrire » n'apparaît que si un lien d'inscription existe
 * vraiment : jamais de bouton actif qui ne mène nulle part.
 *
 * @param array<string,mixed> $program Activité.
 * @return array{label:string,url:string,primary:bool}|null
 */
function registration_action( array $program ): ?array {
	$state = (string) $program['registration'];
	$url   = (string) $program['reg_url'];

	if ( 'ouverte' === $state && '' !== $url ) {
		return array(
			'label'   => 'S’inscrire à cette activité',
			'url'     => $url,
			'primary' => true,
		);
	}

	if ( 'fermee' === $state ) {
		return null;
	}

	return array(
		'label'   => 'Demander des renseignements',
		'url'     => '/implication/',
		'primary' => true,
	);
}

/**
 * Valeur pratique, ou mention explicite à compléter.
 *
 * Retourne null quand le champ est vide et que la mention n'est pas voulue :
 * le gabarit masque alors la ligne au lieu d'inventer une donnée.
 *
 * @param string $value    Valeur enregistrée.
 * @param bool   $show_todo Afficher « À confirmer » plutôt que masquer.
 * @return string|null
 */
function practical( string $value, bool $show_todo = false ): ?string {
	$value = trim( $value );

	if ( '' !== $value ) {
		return $value;
	}

	return $show_todo ? 'À confirmer' : null;
}

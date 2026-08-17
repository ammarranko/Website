<?php
/**
 * Chiffres d'impact du BADR — source unique de vérité.
 *
 * ─────────────────────────────────────────────────────────────────────────────
 *  POUR MODIFIER UN CHIFFRE
 *  Deux possibilités, sans toucher au code :
 *
 *   1. Depuis WordPress (WP-CLI ou un futur écran de réglages) :
 *        wp option update badr_impact_stats '{"personnes":"12000"}' --format=json
 *      Toute clé absente reprend la valeur par défaut ci-dessous.
 *
 *   2. En modifiant directement la valeur « value » dans ce fichier.
 *
 *  Le suffixe « + » et le formatage français (espace fine insécable comme
 *  séparateur de milliers) sont appliqués à l'affichage : n'écrivez ici que le
 *  nombre brut.
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * PROVENANCE DES DONNÉES
 *  · personnes  — valeur fournie par l'utilisateur, À CONFIRMER par l'organisme.
 *  · paniers    — valeur fournie par l'utilisateur, À CONFIRMER par l'organisme.
 *  · espaces    — vérifié : six espaces communautaires listés dans le contenu.
 *  · axes       — vérifié : trois grands axes d'action (sports et loisirs,
 *                 éducation et développement, événements communautaires).
 *
 * Aucune autre statistique publique n'est inventée ici.
 *
 * @package BADR
 */

namespace BADR\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les quatre compteurs affichés sur la page d'accueil.
 *
 * @return array<int,array{key:string,value:int,suffix:string,label:string,note:string,accent:string,icon:string,provisional:bool}>
 */
function impact_stats(): array {
	$defaults = array(
		array(
			'key'         => 'personnes',
			'value'       => 10000,
			'suffix'      => '+',
			'label'       => 'Personnes aidées',
			'note'        => 'Depuis la création du BADR, à Saint-Léonard et dans les quartiers voisins.',
			'accent'      => 'var(--b-amber)',
			'icon'        => 'personnes',
			'provisional' => true,
		),
		array(
			'key'         => 'paniers',
			'value'       => 7000,
			'suffix'      => '+',
			'label'       => 'Paniers alimentaires distribués',
			'note'        => 'Chaque lundi, des denrées remises aux familles et aux personnes à faibles revenus.',
			'accent'      => 'var(--b-leaf)',
			'icon'        => 'panier',
			'provisional' => true,
		),
		array(
			'key'         => 'espaces',
			'value'       => 6,
			'suffix'      => '',
			'label'       => 'Espaces communautaires',
			'note'        => 'Parents, papas, femmes et mamans, familles, filles et aînés.',
			'accent'      => 'var(--b-azure)',
			'icon'        => 'espaces',
			'provisional' => false,
		),
		array(
			'key'         => 'axes',
			'value'       => 3,
			'suffix'      => '',
			'label'       => 'Grands axes d’action',
			'note'        => 'Sports et loisirs, éducation et développement, événements communautaires.',
			'accent'      => 'var(--b-flame)',
			'icon'        => 'axes',
			'provisional' => false,
		),
	);

	// Surcharge éventuelle enregistrée en base : { "clé": nombre }.
	$stored = get_option( 'badr_impact_stats', array() );

	if ( is_array( $stored ) ) {
		foreach ( $defaults as $i => $stat ) {
			if ( isset( $stored[ $stat['key'] ] ) && is_numeric( $stored[ $stat['key'] ] ) ) {
				$defaults[ $i ]['value'] = (int) $stored[ $stat['key'] ];
			}
		}
	}

	/**
	 * Filtre les chiffres d'impact avant affichage.
	 *
	 * @param array $defaults Les quatre compteurs.
	 */
	return (array) apply_filters( 'badr_impact_stats', $defaults );
}

/**
 * Formate un nombre à la française : espace fine insécable entre les milliers.
 *
 * 10000 devient « 10 000 » — jamais « 10,000 » ni « 10.000 ».
 *
 * @param int $value Nombre à formater.
 * @return string
 */
function format_number_fr( int $value ): string {
	return number_format_i18n( $value );
}

/**
 * Icônes en trait fin des compteurs.
 *
 * Dessinées à la main en SVG plutôt que reprises d'un jeu d'icônes générique :
 * elles partagent l'épaisseur de trait et les extrémités arrondies du reste du
 * système. Aucun emoji système n'est utilisé.
 *
 * @param string $name Identifiant de l'icône.
 * @return string Balisage SVG, ou chaîne vide si l'icône n'existe pas.
 */
function impact_icon( string $name ): string {
	$paths = array(
		// Trois silhouettes réunies : les personnes accompagnées.
		'personnes' => '<circle cx="12" cy="9" r="4"/><path d="M4 30c0-4.4 3.6-8 8-8s8 3.6 8 8"/>'
			. '<circle cx="27" cy="12" r="3.2"/><path d="M22 30c0-3.6 2.4-6.4 5.6-6.4S33 26.4 33 30"/>'
			. '<path d="M18 16.5c1.6-1 3.4-1.5 5.2-1.5"/>',
		// Un panier tressé : la banque alimentaire.
		'panier'    => '<path d="M5 13h28l-3 17a3 3 0 0 1-3 2.6H11A3 3 0 0 1 8 30Z"/>'
			. '<path d="M13 13 17 4M25 13 21 4"/><path d="M5 19h28M15 19v13M23 19v13"/>',
		// Six cellules reliées : les espaces communautaires.
		'espaces'   => '<circle cx="10" cy="9" r="4.2"/><circle cx="28" cy="9" r="4.2"/>'
			. '<circle cx="10" cy="29" r="4.2"/><circle cx="28" cy="29" r="4.2"/>'
			. '<circle cx="19" cy="19" r="4.2"/>'
			. '<path d="M13.2 11.6 15.8 15.8M25 11.6 22.2 15.8M13.2 26.4 15.8 22.2M25 26.4 22.2 22.2"/>',
		// Trois branches issues d'un même tronc : les axes d'action.
		'axes'      => '<path d="M19 34V17"/><path d="M19 17C19 11 14 8 8 7c0 6 5 9 11 10Z"/>'
			. '<path d="M19 17c0-6 5-9 11-10 0 6-5 9-11 10Z"/><path d="M12 34h14"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return '<svg class="badr-stat__icon" viewBox="0 0 38 38" aria-hidden="true" focusable="false">'
		. $paths[ $name ] . '</svg>';
}

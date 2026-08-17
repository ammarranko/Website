<?php
/**
 * Title: Pied de page premium
 * Slug: badr/pied
 * Categories: badr-pages
 * Description: Pied de page en quatre colonnes de largeurs intentionnelles : identité et logo, programmes, organisme, coordonnées et infolettre. Toutes les coordonnées proviennent du contenu vérifié du BADR.
 * Keywords: pied de page, contact, coordonnées, infolettre
 * Viewport Width: 1440
 *
 * En composition PHP plutôt qu'en simple partie de gabarit HTML, afin de
 * résoudre le logo officiel depuis la médiathèque : un chemin de fichier codé en
 * dur casserait au moindre réimport des médias.
 *
 * @package BADR
 */

$badr_logo_id  = (int) get_theme_mod( 'custom_logo' );
$badr_logo_url = $badr_logo_id ? wp_get_attachment_image_url( $badr_logo_id, 'medium' ) : '';
?>
<!-- wp:html --><div class="badr-footer__inner">

		<div class="badr-footer__grid">

			<div class="badr-footer__identity">
				<div class="badr-footer__logo">
					<?php if ( $badr_logo_url ) : ?><img src="<?php echo esc_url( $badr_logo_url ); ?>" width="76" height="76" alt="B.A.D.R — Bureau Associatif pour la Diversité et la Réinsertion" loading="lazy" decoding="async"><?php endif; ?>
				</div>
				<p class="badr-footer__name">Bureau Associatif pour la Diversité et la Réinsertion</p>
				<p class="badr-footer__desc">Un organisme d&rsquo;entraide de Saint-Léonard dédié à la lutte contre la pauvreté et l&rsquo;exclusion sociale, auprès des aînés, des jeunes, des enfants et des familles.</p>

				<ul class="badr-footer__social">
					<li>
						<a href="https://www.facebook.com/badr.stleonard.1" target="_blank" rel="noopener noreferrer">
							<svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
							<span class="badr-sr-only">Facebook du BADR (nouvelle fenêtre)</span>
						</a>
					</li>
					<li>
						<a href="https://www.instagram.com/badrstleonard?igsh=dm1pdDdoZGdiYWM2" target="_blank" rel="noopener noreferrer">
							<svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
							<span class="badr-sr-only">Instagram du BADR (nouvelle fenêtre)</span>
						</a>
					</li>
					<li>
						<a href="https://chat.whatsapp.com/JxQx96vcnN3BnV6uBe3zhj" target="_blank" rel="noopener noreferrer">
							<svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
							<span class="badr-sr-only">Groupe WhatsApp du BADR (nouvelle fenêtre)</span>
						</a>
					</li>
					<li>
						<a href="https://www.youtube.com/@CentreBADR" target="_blank" rel="noopener noreferrer">
							<svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/></svg>
							<span class="badr-sr-only">Chaîne YouTube du Centre BADR (nouvelle fenêtre)</span>
						</a>
					</li>
				</ul>
			</div>

			<div>
				<p class="badr-footer__heading">Programmes</p>
				<ul class="badr-footer__list">
					<li><a href="/milieu-de-vie/">Milieu de vie et d&rsquo;entraide</a></li>
					<li><a href="/nos-services-et-activites/">Services et activités</a></li>
					<li><a href="/camp-de-jour/">Camp de jour</a></li>
					<li><a href="/projets-et-initiatives/">Projets et initiatives</a></li>
				</ul>
			</div>

			<div>
				<p class="badr-footer__heading">L&rsquo;organisme</p>
				<ul class="badr-footer__list">
					<li><a href="/a-propos/">À propos</a></li>
					<li><a href="/temoignages/">Témoignages</a></li>
					<li><a href="/evenements/">Événements</a></li>
					<li><a href="/implication/">Nous joindre</a></li>
				</ul>
			</div>

			<div>
				<p class="badr-footer__heading">Nous joindre</p>
				<address class="badr-footer__contact">
					<span class="badr-footer__address">
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
						<span>6432, rue Jean-Talon Est (coin Langelier)<br>Saint-Léonard (Québec)</span>
					</span>
					<a href="tel:+15143245341">
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						<span>+1 (514) 324-5341</span>
					</a>
					<a href="tel:+15142947695">
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						<span>+1 (514) 294-7695</span>
					</a>
					<a href="mailto:info@badr.ca">
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
						<span>info@badr.ca</span>
					</a>
				</address>

				<div class="badr-footer__news">
					<p>Recevez nos activités et nos événements par courriel.</p>
					<a href="https://mailchi.mp/badr/application-badr" target="_blank" rel="noopener noreferrer">
						<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
						<span>S&rsquo;abonner à l&rsquo;infolettre</span>
						<span class="badr-sr-only">(nouvelle fenêtre)</span>
					</a>
				</div>
			</div>

		</div>

		<div class="badr-footer__legal">
			<p>&copy; 2026 Bureau Associatif pour la Diversité et la Réinsertion</p>
			<nav aria-label="Liens légaux">
				<a href="/implication/">Nous joindre</a>
				<a href="/a-propos/">À propos</a>
			</nav>
		</div>

	</div>
<!-- /wp:html -->
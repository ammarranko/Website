/**
 * Système de mouvement du thème BADR.
 *
 *  1. Révélations au défilement (entrées, masques, tracés SVG).
 *  2. En-tête collant.
 *  3. Bouton « Passer l'ouverture animée ».
 *  4. Vitrine des espaces — onglets accessibles.
 *  5. Compteurs d'impact — comptage à l'entrée dans le viewport.
 *  6. Témoignages — rotation éditoriale avec contrôles.
 *  7. Index des programmes — mise en évidence du chapitre courant.
 *  8. Parallaxe très légère sur les visuels marqués.
 *
 * Tout est progressif : sans JavaScript, rien n'est masqué, les six espaces
 * restent empilés, les compteurs affichent leur valeur finale et les
 * témoignages restent tous lisibles. prefers-reduced-motion est respecté.
 */
( function () {
	'use strict';

	var reduceQuery = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	var reduce = reduceQuery.matches;
	var hasIO = 'IntersectionObserver' in window;

	function each( list, fn ) {
		Array.prototype.forEach.call( list, fn );
	}

	/* ---------------------------------------------------------------------
	 * 1. Révélations
	 * ------------------------------------------------------------------ */

	function markVisible( el ) {
		el.classList.add( 'is-visible' );
	}

	function initReveal() {
		// L'index de cascade est posé ici : le CSS n'a pas à connaître le
		// nombre d'enfants, et le balisage reste propre.
		each( document.querySelectorAll( '[data-reveal-group]' ), function ( group ) {
			each( group.children, function ( child, i ) {
				child.style.setProperty( '--b-i', i );
			} );
		} );

		// Longueur réelle de chaque tracé : sans elle, le pointillé est faux
		// dès que la section n'a pas la hauteur supposée.
		each( document.querySelectorAll( '[data-draw]' ), function ( path ) {
			if ( typeof path.getTotalLength === 'function' ) {
				try {
					var len = Math.ceil( path.getTotalLength() );
					if ( len > 0 ) {
						path.style.setProperty( '--b-len', len );
					}
				} catch ( e ) {
					// Un tracé non rendu peut lever : la valeur par défaut suffit.
				}
			}
		} );

		var targets = document.querySelectorAll( '[data-reveal], [data-draw], .badr-reveal, .badr-stat, .badr-etape, .badr-values' );

		if ( ! targets.length ) {
			return;
		}

		if ( reduce || ! hasIO ) {
			each( targets, markVisible );
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						markVisible( entry.target );
						observer.unobserve( entry.target );
					}
				} );
			},
			{ rootMargin: '0px 0px -10% 0px', threshold: 0.01 }
		);

		each( targets, function ( el ) {
			observer.observe( el );
		} );

		// Filet de sécurité : du contenu masqué en attendant une révélation est
		// du contenu perdu si quoi que ce soit empêche l'observateur de se
		// déclencher (onglet en arrière-plan au chargement, extension, bogue
		// moteur). Passé ce délai, tout ce qui est déjà atteint par le
		// défilement est révélé, animation ou pas.
		window.setTimeout( function () {
			each( document.querySelectorAll( '[data-reveal]:not(.is-visible), .badr-reveal:not(.is-visible)' ), function ( el ) {
				if ( el.getBoundingClientRect().top < window.innerHeight * 1.5 ) {
					markVisible( el );
				}
			} );
		}, 2500 );

		// Si l'utilisateur active « mouvement réduit » en cours de route, on
		// révèle tout de suite plutôt que de laisser des blancs.
		onMediaChange( reduceQuery, function ( matches ) {
			if ( matches ) {
				reduce = true;
				observer.disconnect();
				each( document.querySelectorAll( '[data-reveal], [data-draw], .badr-reveal' ), markVisible );
			}
		} );
	}

	function onMediaChange( mql, fn ) {
		var handler = function ( event ) {
			fn( event.matches );
		};

		if ( typeof mql.addEventListener === 'function' ) {
			mql.addEventListener( 'change', handler );
		} else if ( typeof mql.addListener === 'function' ) {
			mql.addListener( handler );
		}
	}

	/* ---------------------------------------------------------------------
	 * 2. En-tête collant
	 * ------------------------------------------------------------------ */

	function initHeader() {
		var header = document.querySelector( '.badr-header' );

		if ( ! header ) {
			return;
		}

		var ticking = false;

		function apply() {
			header.classList.toggle( 'is-collee', window.scrollY > 24 );
			ticking = false;
		}

		function onScroll() {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( apply );
			}
		}

		apply();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	/* ---------------------------------------------------------------------
	 * 3. Passer l'ouverture animée
	 * ------------------------------------------------------------------ */

	function initHeroSkip() {
		var hero = document.querySelector( '[data-badr-hero]' );
		var skip = document.querySelector( '[data-badr-skip]' );

		if ( ! hero || ! skip ) {
			return;
		}

		if ( reduce ) {
			skip.hidden = true;
			return;
		}

		skip.addEventListener( 'click', function () {
			hero.classList.add( 'is-terminee' );
			skip.hidden = true;

			var title = hero.querySelector( '#badr-hero-title' );
			if ( title ) {
				title.setAttribute( 'tabindex', '-1' );
				title.focus( { preventScroll: true } );
			}
		} );
	}

	/* ---------------------------------------------------------------------
	 * 4. Vitrine des espaces
	 * ------------------------------------------------------------------ */

	function initVitrine() {
		var root = document.querySelector( '[data-badr-vitrine]' );

		if ( ! root ) {
			return;
		}

		var tabs = Array.prototype.slice.call( root.querySelectorAll( '[role="tab"]' ) );
		var panels = Array.prototype.slice.call( root.querySelectorAll( '[role="tabpanel"]' ) );

		if ( tabs.length < 2 || tabs.length !== panels.length ) {
			return;
		}

		root.classList.add( 'is-interactive' );

		function select( index, moveFocus ) {
			tabs.forEach( function ( tab, i ) {
				var on = i === index;
				tab.setAttribute( 'aria-selected', on ? 'true' : 'false' );
				tab.setAttribute( 'tabindex', on ? '0' : '-1' );
				panels[ i ].hidden = ! on;
			} );

			// La teinte de fond de la vitrine suit l'espace sélectionné : le
			// changement de couleur fait partie de la navigation.
			var accent = tabs[ index ].style.getPropertyValue( '--badr-accent' );
			if ( accent ) {
				root.style.setProperty( '--b-accent', accent.trim() );
			}

			if ( moveFocus ) {
				tabs[ index ].focus();
			}
		}

		tabs.forEach( function ( tab, i ) {
			tab.addEventListener( 'click', function () {
				select( i, false );
			} );

			tab.addEventListener( 'keydown', function ( event ) {
				var next = null;

				if ( event.key === 'ArrowDown' || event.key === 'ArrowRight' ) {
					next = ( i + 1 ) % tabs.length;
				} else if ( event.key === 'ArrowUp' || event.key === 'ArrowLeft' ) {
					next = ( i - 1 + tabs.length ) % tabs.length;
				} else if ( event.key === 'Home' ) {
					next = 0;
				} else if ( event.key === 'End' ) {
					next = tabs.length - 1;
				}

				if ( next !== null ) {
					event.preventDefault();
					select( next, true );
				}
			} );
		} );

		select( 0, false );
	}

	/* ---------------------------------------------------------------------
	 * 5. Compteurs d'impact
	 *
	 * La valeur finale est déjà dans le HTML : si le script échoue, le chiffre
	 * juste reste affiché. Le comptage ne fait que l'animer.
	 * ------------------------------------------------------------------ */

	function formatFr( value ) {
		// Espace fine insécable comme séparateur de milliers, comme en français.
		return String( value ).replace( /\B(?=(\d{3})+(?!\d))/g, ' ' );
	}

	function countUp( el, target, duration ) {
		var start = null;

		function frame( now ) {
			if ( start === null ) {
				start = now;
			}

			var p = Math.min( ( now - start ) / duration, 1 );
			// Sortie douce : le chiffre ralentit en arrivant, il ne s'arrête pas net.
			var eased = 1 - Math.pow( 1 - p, 3 );

			el.textContent = formatFr( Math.round( target * eased ) );

			if ( p < 1 ) {
				window.requestAnimationFrame( frame );
			} else {
				el.textContent = formatFr( target );
				// Impulsion de fin : le chiffre se pose visiblement.
				var num = el.closest( '.badr-stat__num' ) || el.parentNode;
				if ( num && num.classList ) {
					num.classList.add( 'is-counted' );
				}
			}
		}

		window.requestAnimationFrame( frame );
	}

	function initCounters() {
		var counters = document.querySelectorAll( '[data-count]' );

		if ( ! counters.length ) {
			return;
		}

		each( counters, function ( el, i ) {
			el.closest( '.badr-stat' ) && el.closest( '.badr-stat' ).style.setProperty( '--b-i', i );
		} );

		if ( reduce || ! hasIO ) {
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( ! entry.isIntersecting ) {
						return;
					}

					var el = entry.target;
					var target = parseInt( el.getAttribute( 'data-count' ), 10 );

					observer.unobserve( el );

					if ( isNaN( target ) ) {
						return;
					}

					var index = parseInt( el.getAttribute( 'data-count-index' ), 10 ) || 0;

					el.textContent = '0';

					window.setTimeout( function () {
						countUp( el, target, 1600 + index * 120 );
					}, index * 160 );
				} );
			},
			{ threshold: 0.35 }
		);

		each( counters, function ( el ) {
			observer.observe( el );
		} );
	}

	/* ---------------------------------------------------------------------
	 * 6. Témoignages
	 * ------------------------------------------------------------------ */

	function initQuotes() {
		var root = document.querySelector( '[data-badr-quotes]' );

		if ( ! root ) {
			return;
		}

		var slides = Array.prototype.slice.call( root.querySelectorAll( '[data-quote]' ) );

		if ( slides.length < 2 ) {
			return;
		}

		var prev = root.querySelector( '[data-quote-prev]' );
		var next = root.querySelector( '[data-quote-next]' );
		var dots = Array.prototype.slice.call( root.querySelectorAll( '[data-quote-dot]' ) );
		var live = root.querySelector( '[data-quote-count]' );
		var peek = root.querySelector( '[data-quote-peek]' );
		var index = 0;
		var timer = null;
		var delay = 8000;

		root.classList.add( 'is-interactive' );

		function render( to ) {
			index = ( to + slides.length ) % slides.length;

			slides.forEach( function ( slide, i ) {
				var on = i === index;
				slide.classList.toggle( 'is-active', on );
				// aria-hidden plutôt que hidden : la transition doit rester visible.
				slide.setAttribute( 'aria-hidden', on ? 'false' : 'true' );
				each( slide.querySelectorAll( 'a, button' ), function ( focusable ) {
					if ( on ) {
						focusable.removeAttribute( 'tabindex' );
					} else {
						focusable.setAttribute( 'tabindex', '-1' );
					}
				} );
			} );

			dots.forEach( function ( dot, i ) {
				dot.setAttribute( 'aria-current', i === index ? 'true' : 'false' );
			} );

			if ( live ) {
				live.textContent = ( index + 1 ) + ' / ' + slides.length;
			}

			if ( peek ) {
				var upcoming = slides[ ( index + 1 ) % slides.length ];
				var text = upcoming.getAttribute( 'data-quote-short' ) || '';
				peek.textContent = text;
			}
		}

		function stop() {
			if ( timer ) {
				window.clearInterval( timer );
				timer = null;
			}
			root.classList.add( 'is-paused' );
		}

		function play() {
			if ( reduce || timer ) {
				return;
			}
			root.classList.remove( 'is-paused' );
			timer = window.setInterval( function () {
				render( index + 1 );
			}, delay );
		}

		function goTo( to ) {
			// Une action manuelle interrompt la rotation : c'est le visiteur qui mène.
			stop();
			render( to );
		}

		if ( prev ) {
			prev.addEventListener( 'click', function () {
				goTo( index - 1 );
			} );
		}

		if ( next ) {
			next.addEventListener( 'click', function () {
				goTo( index + 1 );
			} );
		}

		dots.forEach( function ( dot, i ) {
			dot.addEventListener( 'click', function () {
				goTo( i );
			} );
		} );

		root.addEventListener( 'mouseenter', stop );
		root.addEventListener( 'focusin', stop );
		root.addEventListener( 'mouseleave', play );

		root.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'ArrowLeft' ) {
				event.preventDefault();
				goTo( index - 1 );
			} else if ( event.key === 'ArrowRight' ) {
				event.preventDefault();
				goTo( index + 1 );
			}
		} );

		root.style.setProperty( '--b-quote-dur', delay + 'ms' );
		render( 0 );

		// La rotation ne démarre que lorsque la section est réellement visible.
		if ( hasIO && ! reduce ) {
			var visibility = new IntersectionObserver(
				function ( entries ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							play();
						} else {
							stop();
						}
					} );
				},
				{ threshold: 0.3 }
			);
			visibility.observe( root );
		}
	}

	/* ---------------------------------------------------------------------
	 * 7. Index des programmes
	 * ------------------------------------------------------------------ */

	function initProgIndex() {
		var index = document.querySelector( '[data-badr-prog-index]' );

		if ( ! index || ! hasIO ) {
			return;
		}

		var links = Array.prototype.slice.call( index.querySelectorAll( 'a[href^="#"]' ) );
		var chapters = links
			.map( function ( link ) {
				return document.getElementById( link.getAttribute( 'href' ).slice( 1 ) );
			} )
			.filter( Boolean );

		if ( chapters.length !== links.length || ! chapters.length ) {
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( ! entry.isIntersecting ) {
						return;
					}

					var i = chapters.indexOf( entry.target );

					links.forEach( function ( link, n ) {
						link.classList.toggle( 'is-active', n === i );
					} );
				} );
			},
			{ rootMargin: '-45% 0px -45% 0px', threshold: 0 }
		);

		chapters.forEach( function ( chapter ) {
			observer.observe( chapter );
		} );
	}

	/* ---------------------------------------------------------------------
	 * 8. Parallaxe
	 *
	 * Quelques pixels seulement : assez pour donner de la profondeur, jamais
	 * assez pour donner le mal de mer.
	 * ------------------------------------------------------------------ */

	function initParallax() {
		var items = document.querySelectorAll( '[data-parallax]' );

		if ( ! items.length || reduce || ! hasIO ) {
			return;
		}

		var active = [];
		var ticking = false;

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				var i = active.indexOf( entry.target );

				if ( entry.isIntersecting && i === -1 ) {
					active.push( entry.target );
				} else if ( ! entry.isIntersecting && i !== -1 ) {
					active.splice( i, 1 );
					entry.target.style.transform = '';
				}
			} );

			if ( active.length ) {
				update();
			}
		} );

		each( items, function ( el ) {
			observer.observe( el );
		} );

		function update() {
			var viewport = window.innerHeight;

			active.forEach( function ( el ) {
				var rect = el.getBoundingClientRect();
				var progress = ( rect.top + rect.height / 2 - viewport / 2 ) / viewport;
				var amount = parseFloat( el.getAttribute( 'data-parallax' ) ) || 18;

				el.style.transform = 'translate3d(0,' + ( -progress * amount ).toFixed( 2 ) + 'px,0)';
			} );

			ticking = false;
		}

		window.addEventListener(
			'scroll',
			function () {
				if ( ! ticking && active.length ) {
					ticking = true;
					window.requestAnimationFrame( update );
				}
			},
			{ passive: true }
		);
	}

	/* ---------------------------------------------------------------------
	 * 9. Chercheur d'activités
	 *
	 * Le filtrage se fait sur des cartes déjà rendues : sans JavaScript, la
	 * liste complète reste lisible et navigable, et rien ne recharge la page.
	 * ------------------------------------------------------------------ */

	function initFinder() {
		var root = document.querySelector( '[data-badr-finder]' );
		var results = document.querySelector( '[data-finder-results]' );

		if ( ! root || ! results ) {
			return;
		}

		var cards = Array.prototype.slice.call( results.querySelectorAll( '[data-title]' ) );
		var search = root.querySelector( '[data-finder-search]' );
		var spaceSel = root.querySelector( '[data-finder-space]' );
		var regSel = root.querySelector( '[data-finder-reg]' );
		var famBtns = Array.prototype.slice.call( root.querySelectorAll( '[data-finder-family-btn]' ) );
		var countEl = root.querySelector( '[data-finder-count]' );
		var noneEl = document.querySelector( '[data-finder-none]' );
		var resets = Array.prototype.slice.call( document.querySelectorAll( '[data-finder-reset]' ) );

		var state = { q: '', family: '', space: '', reg: '' };

		function normalise( value ) {
			// Sans accents : « aines » doit trouver « aînés ».
			return String( value )
				.toLowerCase()
				.normalize( 'NFD' )
				.replace( /[̀-ͯ]/g, '' );
		}

		function apply() {
			var q = normalise( state.q ).trim();
			var shown = 0;

			cards.forEach( function ( card ) {
				var ok = true;

				if ( q && normalise( card.getAttribute( 'data-title' ) ).indexOf( q ) === -1 ) {
					ok = false;
				}

				if ( ok && state.family && card.getAttribute( 'data-family' ) !== state.family ) {
					ok = false;
				}

				if ( ok && state.space ) {
					var spaces = ( card.getAttribute( 'data-spaces' ) || '' ).split( /\s+/ );
					if ( spaces.indexOf( state.space ) === -1 ) {
						ok = false;
					}
				}

				if ( ok && state.reg && card.getAttribute( 'data-reg' ) !== state.reg ) {
					ok = false;
				}

				card.classList.toggle( 'is-filtered', ! ok );

				if ( ok ) {
					++shown;
					// L'entrée est rejouée : les résultats se réorganisent visiblement.
					card.classList.remove( 'is-visible' );
					window.requestAnimationFrame( function () {
						card.classList.add( 'is-visible' );
					} );
				}
			} );

			if ( countEl ) {
				countEl.textContent = shown === 1 ? '1 activité' : shown + ' activités';
			}

			if ( noneEl ) {
				noneEl.hidden = shown !== 0;
			}
		}

		if ( search ) {
			search.addEventListener( 'input', function () {
				state.q = search.value;
				apply();
			} );
		}

		if ( spaceSel ) {
			spaceSel.addEventListener( 'change', function () {
				state.space = spaceSel.value;
				apply();
			} );
		}

		if ( regSel ) {
			regSel.addEventListener( 'change', function () {
				state.reg = regSel.value;
				apply();
			} );
		}

		famBtns.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				state.family = btn.getAttribute( 'data-finder-family-btn' ) || '';

				famBtns.forEach( function ( other ) {
					other.classList.toggle( 'is-active', other === btn );
					other.setAttribute( 'aria-pressed', other === btn ? 'true' : 'false' );
				} );

				apply();
			} );

			btn.setAttribute( 'aria-pressed', btn.classList.contains( 'is-active' ) ? 'true' : 'false' );
		} );

		resets.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				state = { q: '', family: '', space: '', reg: '' };

				if ( search ) {
					search.value = '';
				}
				if ( spaceSel ) {
					spaceSel.value = '';
				}
				if ( regSel ) {
					regSel.value = '';
				}

				famBtns.forEach( function ( other, i ) {
					other.classList.toggle( 'is-active', i === 0 );
					other.setAttribute( 'aria-pressed', i === 0 ? 'true' : 'false' );
				} );

				apply();
			} );
		} );

		apply();
	}

	/* ---------------------------------------------------------------------
	 * 10. Nos valeurs en action
	 *
	 * Sans ce script, la classe « is-scrollable » n'est jamais posée et les
	 * quatre valeurs s'empilent simplement : rien n'est caché.
	 * ------------------------------------------------------------------ */

	function initValeurs() {
		var root = document.querySelector( '[data-badr-valeurs]' );

		if ( ! root || reduce ) {
			return;
		}

		var track = root.querySelector( '[data-valeurs-track]' );
		var items = Array.prototype.slice.call( root.querySelectorAll( '[data-valeur]' ) );
		var dots = Array.prototype.slice.call( root.querySelectorAll( '[data-valeurs-dot]' ) );
		var nextEl = root.querySelector( '[data-valeurs-next]' );
		var countEl = root.querySelector( '[data-valeurs-count]' );

		if ( ! track || items.length < 2 ) {
			return;
		}

		root.classList.add( 'is-scrollable' );

		var current = -1;
		var ticking = false;

		function names() {
			return items.map( function ( item ) {
				var el = item.querySelector( '.badr-valeur__name' );
				return el ? el.textContent.trim() : '';
			} );
		}

		var labels = names();

		function show( index ) {
			if ( index === current ) {
				return;
			}

			current = index;

			items.forEach( function ( item, i ) {
				item.classList.toggle( 'is-active', i === index );
				item.setAttribute( 'aria-hidden', i === index ? 'false' : 'true' );
			} );

			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === index );
			} );

			// Le fond prend la couleur de la valeur affichée.
			var accent = items[ index ].style.getPropertyValue( '--b-accent' );
			if ( accent ) {
				root.style.setProperty( '--b-accent', accent.trim() );
			}

			if ( nextEl ) {
				var span = nextEl.querySelector( 'span' );
				if ( span ) {
					span.textContent = labels[ ( index + 1 ) % labels.length ];
				}
			}

			if ( countEl ) {
				countEl.textContent = ( index + 1 ) + ' / ' + items.length;
			}
		}

		function update() {
			var rect = track.getBoundingClientRect();
			var scrollable = rect.height - window.innerHeight;

			if ( scrollable <= 0 ) {
				show( 0 );
				ticking = false;
				return;
			}

			var progress = Math.min( Math.max( -rect.top / scrollable, 0 ), 0.9999 );

			show( Math.floor( progress * items.length ) );
			ticking = false;
		}

		window.addEventListener(
			'scroll',
			function () {
				if ( ! ticking ) {
					ticking = true;
					window.requestAnimationFrame( update );
				}
			},
			{ passive: true }
		);

		window.addEventListener( 'resize', update, { passive: true } );
		update();
	}

	/* ------------------------------------------------------------------ */

	function init() {
		initReveal();
		initHeader();
		initHeroSkip();
		initVitrine();
		initCounters();
		initQuotes();
		initProgIndex();
		initParallax();
		initFinder();
		initValeurs();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );

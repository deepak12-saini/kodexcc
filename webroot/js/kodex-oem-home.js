/**
 * KodexCC OEM page interactions + sticky header
 */
(function () {
	'use strict';

	function onReady(fn) {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
	}

	onReady(function () {
		var header = document.getElementById('kx-header');
		var root = document.getElementById('kx-home') || document.getElementById('kx-page');

		if (header && root) {
			var onScroll = function () {
				if (window.scrollY > 24) {
					header.classList.add('is-scrolled');
				} else {
					header.classList.remove('is-scrolled');
				}
			};
			onScroll();
			window.addEventListener('scroll', onScroll, { passive: true });
		}

		if (!root) {
			return;
		}

		var nodes = root.querySelectorAll('.kx-reveal');
		if (!('IntersectionObserver' in window)) {
			nodes.forEach(function (el) {
				el.classList.add('is-in');
			});
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-in');
						observer.unobserve(entry.target);
					}
				});
			},
			{ rootMargin: '0px 0px -8% 0px', threshold: 0.12 }
		);

		nodes.forEach(function (el) {
			observer.observe(el);
		});

		root.querySelectorAll('.kx-hero .kx-reveal, .kx-page-hero .kx-reveal').forEach(function (el) {
			el.classList.add('is-in');
		});
	});
})();

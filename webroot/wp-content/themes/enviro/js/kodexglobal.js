var $site_header;

jQuery(function ($) {
	$site_header = $('.site-header');

	mobile_menu($);	
	display_viewport_width($);
	vc_yt_video($);
	hash_anchor_position_fix($);
	// Theme Specific
	site_menu($);
	tile_link($);
	document_gallery($);		
	faq($);
	accordions($);
	data_sheet_search($);	
	select_news_category($);	
	watch_home_video($);
	
	
	rgc_product_archive($);
});
function rgc_product_archive ($) {
	$('#rgc-product-archive').siblings('.pagination').find('a').each(function () {
		this.href = this.href + '#rgc-product-archive';
	});
}
function hash_anchor_position_fix ($) {
	// Fix FireFox and Safari do not scroll the page to the correct position of a target anchor point.
	if (window.location.hash.length > 0) {
		window.scrollTo(0, $(window.location.hash).offset().top - $site_header.height());
    }
}
var initMap = null;


function mobile_menu ($) {
	new Mmenu("#mob-menu", {
		extensions: ["position-right", "pagedim-black"]
	}, {
		offCanvas: {
			page: { selector:"#webpage" }
		}
	});
}
function page_scrolling ($) {
	$("a").click(function (event) {
		if (!this.hasAttribute('href')) return;
		var href = this.getAttribute('href').trim();
		var hash_pos = href.lastIndexOf('#');
		if (hash_pos === -1) return;
		var anchor = href.substr(hash_pos);
		if (anchor == '#') return;
		var $anchor = $(anchor);
		if ($anchor.length !== 1) return;
		event.preventDefault();
		var offset = 0;
		if ($site_header.length > 0 && $site_header.css('position') == 'fixed') {
			offset = $site_header.height() + 
				Number($site_header.css('padding-top').replace('px', '')) +
				Number($site_header.css('padding-bottom').replace('px', '')) +
				Number($site_header.css('border-top-width').replace('px', '')) +
				Number($site_header.css('border-bottom-width').replace('px', ''));
		}
		$([document.documentElement, document.body]).animate({
			scrollTop: $anchor.offset().top - offset
		}, 500);
	});
}
function display_viewport_width ($) {
	if (USER_NAME !== 'rgcdev') return;
	var vw = document.createElement('a');
	vw.href = EDIT_URL;
	vw.target = '_blank';
	vw.classList.add('vw-label');
	vw.innerHTML = window.innerWidth;
	document.body.appendChild(vw);
	$(window).resize(function () {
		vw.innerHTML = window.innerWidth;
	});
}
function fix_sec_on_vw_change ($) {
	if (USER_NAME !== 'rgcdev') return;
	var fix_sec = null;
	var offset = 150;
	var timeout;
	$('.page-body > section').each(function (index, element) {
		element.style.position = 'relative';
		var radio = document.createElement('input');
		radio.id = 'rad-' + index;
		radio.type = 'radio';
		radio.name = 'fixed';
		radio.className = 'fix-sec';
		this.appendChild(radio);
	});
	$('.fix-sec').change(function () {
		fix_sec = this;
	});
	

	$(window).resize(function () {
		clearTimeout(timeout);
		if (fix_sec == null) return;
		timeout = setTimeout(function () {
			$([document.documentElement, document.body]).animate({
				scrollTop: $(fix_sec).offset().top - offset
			}, 300);
		}, 300);
	});
}

///////////////////////////////////////////////////////////////////////////////
function site_menu ($) {
	$('.subcat-menu').each(function () {
		var $this = $(this);
		var $subcat_li = $this.find('.subcat-li');
		var $subcat_products = $(this).find('.subcat-products');
		$subcat_li.click(function () {
			var subcat_li = this;
			if (subcat_li.classList.contains('active')) return;
			$subcat_li.removeClass('active');
			$subcat_products.removeClass('active');
			subcat_li.classList.add('active');
			$subcat_products.each(function () {
				if (this.dataset.cat == subcat_li.dataset.cat) {
					this.classList.add('active');
				}
			});
		});
	});

	$('.header-menu .sitemenu-ul > .sitemenu-li.has-submenu').hover(function () {
		$site_header.parent().addClass('submenu-opened');
	}, function () {
		$site_header.parent().removeClass('submenu-opened');
	});
}
function tile_link ($) {
	$('.tile-links .tile-link').each(function () {
		var $this = $(this);
		this.$desc = $this.siblings('.tile-text').children('.link-desc');
		this.$root = $this.closest('.vc-col');
	}).hover(function () {
		this.$root.css('background-image', 'url(' + this.$root.data('gif') + ')');
		this.$desc.slideDown(300);
	}, function () {
		this.$root.css('background-image', 'url(' + this.$root.data('img') + ')');
		this.$desc.slideUp(300);
	});
}

function document_gallery ($) {
	$('.document-gallery .document-icon').css({ width:'' });
}

function faq ($) {
	var sliding = false;
	$('.faq-list .question, .faq-list .faq-cat').each(function () {
		this.next = $(this.nextElementSibling);
	}).click(function () {
		if (sliding) return;
		sliding = true;
		this.classList.toggle('opened');
		this.next.slideToggle({
			duration:400,
			start: function () {
				$(this).css({ display: "block" });
			},
			complete: function () {
				sliding = false;
			}
		});
	});

}
function accordions ($) {
	var sliding = false;
	$('.accordions .accordion-header').each(function () {
		this.next = $(this.nextElementSibling);
	}).click(function () {
		if (sliding) return;
		sliding = true;
		this.classList.toggle('opened');
		this.next.slideToggle({
			duration:400,
			start: function () {
				$(this).css({ display: "flex" });
			},
			complete: function () {
				sliding = false;
			}
		});
	});
}
function data_sheet_search ($) {
	var $dss = $('#data-sheet-search');
	if ($dss.length != 1) return;
	var timeout;
	var $docs = $('#data-sheet-search').find('.document-icon');
	var $search_category = $dss.find('#search-category');
	var $search_title = $dss.find('#search-title');
	var search_data_sheet = function () {
		$docs.addClass('hide');
		
		var cat_id = Number($search_category.val());
		if (cat_id == 0) {
			$docs.removeClass('hide');
		} else {
			$docs.each(function (idx, elm) {
				if (data_sheets['doc_' + elm.dataset.id].cats.indexOf(cat_id) >= 0) {
					elm.classList.remove('hide');
				}
			});
		}
		
		var words = $search_title.val().toLowerCase().trim();
		if (words == '') return;
		words = words.split(" ");
		var len = words.length;
		if (len == 0) return;
		
		$docs.filter(':not(.hide)').each(function (idx, elm) {
			var match = true;
			var titles = data_sheets['doc_' + elm.dataset.id].titles.join(' ').toLowerCase();
			for (var i = 0; i < len; i++) {
				if (titles.indexOf(words[i]) == -1) {
					match = false;
					break;
				}
			}
			if (match) {
				elm.classList.remove('hide');
			} else {
				elm.classList.add('hide');
			}
		});
	};
	$search_title.val('');
	$search_category.val(0);
	$docs.removeClass('hide');
	$search_category.change(search_data_sheet);
	
	$search_title.keyup(function () {
		clearTimeout(timeout);
		setTimeout(search_data_sheet, 500);
	});
}

function product_slier ($) {
	$('.product-slider').slick({
		autoplay:false,
		dots:false,
		arrows:true,
		infinite:false,
		fade:false,
		slidesToShow:4,
		slidesToScroll:4,
		prevArrow: '<button type="button" class="slick-arrow slick-prev"><i class="fas fa-chevron-left"></i></button>',
		nextArrow: '<button type="button" class="slick-arrow slick-next"><i class="fas fa-chevron-right"></i></button>',
		responsive: [
			{ breakpoint:1200, settings: { slidesToShow:3, slidesToScroll:3 } },
			{ breakpoint: 992, settings: { slidesToShow:3, slidesToScroll:3, arrows:false } },
			{ breakpoint: 768, settings: { slidesToShow:2, slidesToScroll:2, arrows:false } },
			{ breakpoint: 600, settings: { slidesToShow:1, slidesToScroll:1, arrows:true } }
		]
	})
}

function select_news_category ($) {
	/* On single news pages */
	$('#select-news-category').change(function () {
		window.location.href = this.value;
	});
}


function vc_yt_video ($) {
	$('.yt-video-ctn').click(function () {
		var $iframe = $(this).children('iframe');
		if ($iframe.length != 1) return;
		if (this.classList.contains('playing')) {
			$iframe.attr('src', '');
			this.classList.remove('playing');
		} else {
			var src = $iframe.data('src');
			if (!src) return;
			$iframe.attr('src', src);
			this.classList.add('playing');
			//this.src = this.dataset.src;
		}
	});
}
function watch_home_video ($) {
	var working = false;
	var home_video = $('#home-video');
	var wrapper = home_video.children('.home-video');
	wrapper.hide();
	var yt = home_video.find('.yt-video-ctn');
	$('#watch-home-video').click(function () {
		if (working) return;
		working = true;
		var btn = this;
		if (this.classList.contains('playing')) {
			this.classList.remove('playing');
			this.innerHTML = 'Watch Video';
			yt.trigger('click');
			wrapper.slideUp(500, function () {
				working = false;
			});
		} else {
			this.innerHTML = 'Close Video';
			//yt.removeClass('playing');
			
			$([document.documentElement, document.body]).animate({
				scrollTop: home_video.offset().top - 1
			}, 500, 'swing', function () {
				wrapper.slideDown(500, function () {
					if (!btn.classList.contains('playing')) {
						yt.trigger('click');
					}
					btn.classList.add('playing');
					working = false;
				});
			});
		}
	});
}
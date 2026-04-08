var $site_header;

jQuery(function ($) {
	$site_header = $('.site-header');

	mobile_menu($);
	
	display_viewport_width($);
	//fix_sec_on_vw_change($);
	vc_yt_video($);

	//page_scrolling($);
	hash_anchor_position_fix($);

	// Theme Specific
	site_menu($);
	tile_link($);
	document_gallery($);
	single_product_gallery($);
	project_slider($);
	project_gallery($);
	post_slider($);
	faq($);
	accordions($);
	data_sheet_search($);
	product_slier($);
	header_search($);
	select_news_category($);
	square_image_slider($);
	watch_home_video($);
	office_info_map($);
	where_to_buy($);
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
function where_to_buy ($) {
	//ajax_get_centre_loc($);
	var $map_div = $('.gmap');
	if ($map_div.length == 0) return;
	var map_animating = false;

	initMap = function () {
		$map_div.each(function () {
			var map_div = this;
			var office_map = null;
			var center_pos = { lat:Number(map_div.dataset.lat), lng:Number(map_div.dataset.lng) };
			var $lis = $(map_div).closest('.addr-map').find('.col-addr li');
			$lis.each(function () {
				this.pos = { lat:Number(this.dataset.lat), lng:Number(this.dataset.lng) };
			});
			map_div.office_map = new google.maps.Map(map_div, {
				zoom: Number(map_div.dataset.zoom),
				center: center_pos,
				mapTypeId: 'roadmap'
			});
			

			$lis.each(function () {
				var marker = new google.maps.Marker({
					position: this.pos,
					map: map_div.office_map,
					url:'https://www.google.com/maps/dir/?api=1&destination=' + this.pos.lat + '%2C' + this.pos.lng
				});
				this.marker = marker;
				google.maps.event.addListener(marker, 'click', function() {
					//window.location.href = marker.url;
					window.open(this.url, '_blank');
				});
				this.info = new google.maps.InfoWindow({
					content:this.innerHTML.replace('–</strong>', '</strong><br>').replace(', ', '<br>')
				});
			});
		});
	};
	
	$.getScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyAcBISIsj_QXWH2WB_2Bs664X9ZFgyhQO0&callback=initMap", function() {
		$map_div.each(function () {
			var map_div = this;
			var center_pos = { lat:Number(map_div.dataset.lat), lng:Number(map_div.dataset.lng) };
			var current_pos = center_pos;
			var $lis = $(map_div).closest('.addr-map').find('.col-addr li');
			var current_btn = null;
			// initMap();
	
			$lis.click(function () {
				if (current_btn) current_btn.info.close();
				current_btn = this;
				var new_pos = current_btn.pos;
				
				if (map_animating || (map_div.office_map == null)) return;
				map_animating = true;
				if (current_pos === center_pos) {
					map_div.office_map.setCenter(new_pos);
					map_div.office_map.setZoom(18);
					setTimeout(function () { current_pos = new_pos; map_animating = false; }, 800);
				} else {
					map_div.office_map.setCenter(center_pos);
					map_div.office_map.setZoom(11);
					setTimeout(function () {
						map_div.office_map.setCenter(new_pos);
						map_div.office_map.setZoom(18);
						setTimeout(function () { current_pos = new_pos; map_animating = false; }, 800);
					}, 800)
				}
				current_btn.info.open(map_div.office_map, current_btn.marker);
			});
		});
	});
}
function office_info_map ($) {
	$('.office-info-map').each(function () {
		$root = $(this);
		$btns = $root.find('.switch-btn');
		$maps = $root.find('.office-info');

		$btns.click(function (e) {
			if (this.classList.contains('active')) return;
			$maps.removeClass('active');
			$maps.each(function (index, map) {
				if (map.dataset.office == e.target.dataset.office) {
					map.classList.add('active');
				}
			}); // switch-btn btn btn2 hover1
			// switch-btn btn btn2 hover1 
			$btns.filter('.active').parent().removeClass('active');
			$btns.removeClass('active btn1').addClass('btn2');
			this.classList.remove('btn2');
			this.classList.add('active', 'btn1');
			this.parentElement.classList.add('active');
		});
	});
}
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
function single_product_gallery ($) {
	$('.single-product-gallery').slick({
		autoplay:false,
		dots:false,
		arrows:true,
		infinite:true,
		fade:false,
		variableWidth:true,
		centerMode:true,
		slidesToShow:1,
		slidesToScroll:1,
		prevArrow: '<button type="button" class="slick-arrow slick-prev"><i class="fas fa-chevron-left"></i></button>',
		nextArrow: '<button type="button" class="slick-arrow slick-next"><i class="fas fa-chevron-right"></i></button>',
	})
}
function project_slider ($) {
	$('.project-slider').slick({
		autoplay:false,
		dots:false,
		arrows:true,
		infinite:true,
		fade:true,
		slidesToShow:1,
		slidesToScroll:1,
		prevArrow: '<button type="button" class="slick-arrow slick-prev"><i class="fas fa-chevron-left"></i></button>',
		nextArrow: '<button type="button" class="slick-arrow slick-next"><i class="fas fa-chevron-right"></i></button>',
	});
}
function project_gallery ($) {
	$('.project-gallery').slick({
		autoplay:false,
		dots:false,
		arrows:true,
		infinite:true,
		fade:false,
		variableWidth:true,
		centerMode:false,
		//slidesToShow:1,
		//slidesToScroll:1,
		prevArrow: '<button type="button" class="slick-arrow slick-prev"><i class="fas fa-chevron-left"></i></button>',
		nextArrow: '<button type="button" class="slick-arrow slick-next"><i class="fas fa-chevron-right"></i></button>',
	});
}
function post_slider ($) {
	$('.post-slider').slick({
		autoplay:false,
		dots:false,
		arrows:true,
		infinite:true,
		fade:false,
		variableWidth:true,
		centerMode:true,
		slidesToShow:1,
		slidesToScroll:1,
		prevArrow: '<button type="button" class="slick-arrow slick-prev"><i class="fas fa-chevron-left"></i></button>',
		nextArrow: '<button type="button" class="slick-arrow slick-next"><i class="fas fa-chevron-right"></i></button>',
		responsive: [ { breakpoint:450, settings: { arrows:false } } ]
	});
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

	/*
	$('.faq-list .faq-cat').each(function () {
		this.faq = $(this.nextElementSibling);
	}).click(function () {
		if (sliding) return;
		sliding = true;
		this.classList.toggle('opened');
		this.faq.slideToggle(400, function () {
			sliding = false;
		});
	});
	*/
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

function header_search ($) {
	$('.enviro-product-search').each(function () {
		var $search_console = $(this);
		var working = false;
		var search_text = '';
		var search_cat = 0;
		var timeout;
		var $search_input = $search_console.find('.search-input').val('');
		var $select_cat = $search_console.find('.select-cat');
		var $result_msg = $search_console.find('.result-msg').html('');
		var $search_wrapper = $search_console.find('.search-wrapper');
		var $search_result_list = $search_console.find('.result-list');
		var $search_result_details = $search_console.find('.result-details');
		var $search_results = $.fn.add.call($search_result_list, $search_result_details);
		var $result_sec = $search_console.find('.result-sec').hide();
		var init_results = function () {
			var $tabs = $search_result_list.find('> li > a').removeClass('active');
			var $details = $search_result_details.children().removeClass('active');
			$tabs.each(function (index, tab) {
				tab.detail = $details[index];
			}).hover(function () {
				if (this.classList.contains('active')) return;
				$tabs.removeClass('active');
				$details.removeClass('active');
				this.classList.add('active');
				this.detail.classList.add('active');
			}, function () {});
		};
		init_results();
		var ajax_search = function () {
			var s = $search_input.val().trim().toLowerCase();
			var cat = Number($select_cat.val());
			if ((s == search_text) && (cat == search_cat)) return;
			$search_wrapper.removeClass('fa-sync').addClass('fa-search');
			clearTimeout(timeout);
			search_text = s;
			search_cat = cat;
			if (search_text == '') {
				$result_sec.hide();
				$search_results.empty();
				$result_msg.html('');
				$search_input.val('');
				return;
			}
			$search_wrapper.removeClass('fa-search').addClass('fa-sync');
			
			timeout = setTimeout(function () {
				$search_wrapper.removeClass('fa-search').addClass('fa-sync');
				$.ajax({
					type: "POST",
					url: SITE_URL + '/wp-admin/admin-ajax.php',
					data: {
						action: 'rgc_website_search',
						rgc_query: 'search_product',
						search_text: search_text,
						product_category: search_cat
					},
					success: function (results) {
						results = JSON.parse(results);
						$result_sec.hide();
						$search_results.empty();
						$result_msg.html(results.length ? '' : 'No products found.');
						results.forEach(function (item, index) {
							$search_result_list.append(`
								<li><a href="${ item.product_url }">${ item.product_name }</a></li>
							`);
							$search_result_details.append(`
								<li>
									<div class="cols">
										<div class="col col-l col-img">
											<a href="${ item.product_url }"><img src="${ item.product_image_url }" alt="${ item.product_name }"></a>
										</div>
										<div class="col col-r col-txt txt-block">
											<h3 class="hd product-name">${ item.product_name }</h3>
											<p class="product-cat">${ item.product_category }</p>
											<a class="btn btn2 p" href="${ item.product_url }">view product</a>
										</div>
									</div>
								</li>
							`);
						});
						init_results();
						$result_sec.show();
						$search_wrapper.removeClass('fa-sync').addClass('fa-search');
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
					}
				});
			}, 600);
		};
		$select_cat.change(ajax_search);
		
		if ($search_console.attr('id') == 'search-console') {
			$('.search-btn').click(function () {
				if (working) return;
				working = true;
				
				if ($search_console.css('display') == 'none') {
					$site_header.parent().addClass('submenu-opened');
				}
				$search_console.slideToggle(200, function () {
					if (this.style.display == 'none') {
						$site_header.parent().removeClass('submenu-opened');
					}
					$search_input.select();
					working = false;
				});
			});
		}
		$search_input.keyup(ajax_search);
	});
}

function select_news_category ($) {
	/* On single news pages */
	$('#select-news-category').change(function () {
		window.location.href = this.value;
	});
}

function square_image_slider ($) {
	$('.square-image-slider').slick({
		autoplay:true,
		dots:true,
		arrows:true,
		infinite:false,
		fade:false,
		slidesToShow:3,
		slidesToScroll:3,
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
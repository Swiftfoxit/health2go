jQuery(document).ready(function($) {
	"use strict";

	/* window */
	var window_width, window_height, scroll_top;

	/* admin bar */
	var adminbar = $('#wpadminbar');
	var adminbar_height = 0;

	/* header menu */
	var header = $('#cshero-header');
	var header_top = 0;

	/* scroll status */
	var scroll_status = '';

	/**
	 * window load event.
	 * 
	 * Bind an event handler to the "load" JavaScript event.
	 * @author CMSSuperHeroes Team
	 */
	$(window).on('load', function() {
		/** current scroll */
		scroll_top = $(window).scrollTop();

		/** current window width */
		window_width = $(window).width();

		/** current window height */
		window_height = $(window).height();

		/* get admin bar height */
		adminbar_height = adminbar.length > 0 ? adminbar.outerHeight(true) : 0 ;

		/* get top header menu */
		header_top = header.length > 0 ? header.offset().top - adminbar_height : 0 ;

		/* check sticky menu. */
		cms_stiky_menu();

		/* page loading */
		cms_page_loading();

		cms_enscroll();

		cms_col_sameheight();

		cms_cart_scroll();

		cms_section_offset();

		cms_modal_job();

		cms_auto_video_width();

		$(".jb-carousel-wrap").css('opacity','1');

	});

    $(window).unload(function() { cms_page_loading(1); });
	
	/**
	 * resize event.
	 * 
	 * Bind an event handler to the "resize" JavaScript event, or trigger that event on an element.
	 * @author CMSSuperHeroes Team
	 */
	$(window).on('resize', function(event, ui) {
		/** current window width */
		window_width = $(event.target).width();

		/** current window height */
		window_height = $(window).height();

		/** current scroll */
		scroll_top = $(window).scrollTop();

		/* check sticky menu. */
		cms_stiky_menu();

		cms_enscroll();

		cms_col_sameheight();

		cms_cart_scroll();

		cms_section_offset();

		cms_auto_video_width();
	});
	
	/**
	 * scroll event.
	 * 
	 * Bind an event handler to the "scroll" JavaScript event, or trigger that event on an element.
	 * @author CMSSuperHeroes Team
	 */
	$(window).on('scroll', function() {
		/** current scroll */
		scroll_top = $(window).scrollTop();

		/* check sticky menu. */
		cms_stiky_menu();

		/* check back to top */
		cms_back_to_top(); 

	});

	/**
	 * Stiky menu
	 *
	 * Show or hide sticky menu.
	 * @author CMSSuperHeroes Team
	 * @since Recruitment 1.0.9
	 */
	function cms_stiky_menu() {
		if (header.hasClass('sticky-desktop') && header_top < scroll_top && window_width > 991) {
			header.addClass('header-fixed');
			$('body').addClass('hd-fixed');
		} else {
			header.removeClass('header-fixed');
			$('body').removeClass('hd-fixed');
		}
	}

	/**
	 * Mobile menu
	 * 
	 * Show or hide mobile menu.
	 * @author CMSSuperHeroes Team
	 * @since Recruitment 1.0.9
	 */
	

	$("#cshero-menu-mobile .open-menu").on('click',function(){
		$('#cshero-header-navigation .main-navigation').toggleClass('navigation-open');
    })
    $("#cshero-menu-mobile .open-user").on('click',function(){
		$('.widget-profile .widget-content').toggleClass('jb-active');
    })
    $("#cshero-menu-mobile .open-cart-job").on('click',function(){
		$('.widget-basket .widget-content').toggleClass('jb-active');
    })
    $(".cshero-navigation-right .widget-profile").parents("#cshero-header").addClass("nav-profile");
    $(".cshero-navigation-right .widget-basket").parents("#cshero-header").addClass("nav-basket");

    $(".btn-buy-close").on('click',function(){
		$('#cms-buy-button-fixed').toggleClass('hide');
    })

    /* User Login */
    $(".cms-login-wrap .button-login span").on('click',function(){
		$('.cms-form-login').toggleClass('open');
    })

    $(".cms-login-wrap .button-register span").on('click',function(){
		$('.cms-form-register').toggleClass('open');
    })


    $(".cms-login-wrap .button-cart span").on('click',function(){
		$('.cms-login-wrap .widget_shopping_cart').toggleClass('open');
    })

    /* Remove active class header */
    $(".open-cart-job").on('click',function(){
		$('.widget-profile .widget-content').removeClass('jb-active');
		$('.main-navigation').removeClass('navigation-open');
    })
    $(".open-user").on('click',function(){
		$('.widget-basket .widget-content').removeClass('jb-active');
		$('.main-navigation').removeClass('navigation-open');
    })
     $(".open-menu").on('click',function(){
		$('.widget-basket .widget-content').removeClass('jb-active');
		$('.widget-profile .widget-content').removeClass('jb-active');
    })


	/**
	 * Back To Top
	 * 
	 * @author CMSSuperHeroes Team
	 * @since Recruitment 1.0.9
	 */
	$('body').on('click', '#back_to_top', function () {
        $("html, body").animate({
            scrollTop: 0
        }, 1500);
    });


    /* Show or Hide Buttom  */
	function cms_back_to_top(){
		/* Back To Top */
        if (scroll_top < window_height) {
        	$('#back_to_top').addClass('off').removeClass('on');
        } else {
        	$('#back_to_top').removeClass('off').addClass('on');
        }
	}
	/**
	 * Auto width video iframe
	 * 
	 * Youtube Vimeo.
	 * @author Fox
	 */
	function cms_auto_video_width() {
		$('.entry-video iframe').each(function(){
			var v_width = $(this).width();
			
			v_width = v_width / (16/9);
			$(this).attr('height',v_width + 35);
		})
	}	

	/* Video Light Box */
	$('.cms-button-video').magnificPopup({
		//disableOn: 700,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,

		fixedContentPos: false
	});

	$('#searchform').find("input[type='text']").each(function(ev) {
	    if(!$(this).val()) { 
	        $(this).attr("placeholder", "Enter Keywords");
	    }
	});

	$('.faq-search #searchform').find("input[type='text']").each(function(ev) {
	    if(!$(this).val()) { 
	        $(this).attr("placeholder", "Find your answer quickly...");
	    }
	});

	$('.tnp-field-email').find("input[type='email']").each(function(ev) {
	    if(!$(this).val()) { 
	        $(this).attr("placeholder", "Enter email address");
	    }
	});

	/* Scrolling Sidebar */
	function cms_scrolling_sidebar(){
		var offset = $("#sidebar").offset();
	    var topPadding = 60;
	    $(window).scroll(function() {
	        if ($(window).scrollTop() > offset.top) {
	            $("#sidebar").stop().animate({
	                marginTop: $(window).scrollTop() - offset.top + topPadding
	            });
	        } else {
	            $("#sidebar").stop().animate({
	                marginTop: 0
	            });
	        };
	    });
	}

	/**
	 * Enscroll
	 * 
	 * 
	 * @author CMSSuperHeroes Team
	 */
	function cms_enscroll() {
		$('#cshero-header-top .widget_shopping_cart_content ').enscroll();
		$('.cms-recent-post').enscroll();
		$('#cshero-header .widget-basket .jobboard-widget-content').enscroll();
		$('#applications .table-wrap').enscroll();

		if($(window).width() < 1336) {
			$('.jobboard-form.apply-form .form-content .form-fields').enscroll();
		}
	}

	function cms_col_sameheight() {
		$('.columns-same .col-same').matchHeight();
		$('.vc_row-o-equal-height .col-same').matchHeight();
		$('.cms-recent-jobs2 .item-inner').matchHeight();
		$('.multicolumn.sub-menu').each(function () {
			$(this).find('> li').matchHeight();
		})
	}

	/* Woo Add Class */
	$('.woo-social').on('click', function() {
    	$(this).toggleClass('opened');
    })
    $('.plus').on('click', function(){
    	$(this).parent().find('input[type="number"]').get(0).stepUp();
    });
    $('.minus').on('click', function(){
    	$(this).parent().find('input[type="number"]').get(0).stepDown();
    });

    /* CMS Image Popup */
    $('.cms-images-zoom').magnificPopup({
	  delegate: 'a.z-view', // child items selector, by clicking on it popup will open
	  type: 'image',
	  gallery: {
	     enabled: true
	  },
	  mainClass: 'mfp-fade',
	  // other options
	});
	$('.cms-image-zoom').magnificPopup({
	  delegate: 'a.z-view', // child items selector, by clicking on it popup will open
	  type: 'image',
	  gallery: {
	     enabled: false
	  },
	  mainClass: 'mfp-fade',
	  // other options
	});
	/* Add Class Input Contact Form */

    $(".wpcf7-form-control").focus(function(){
        $('.wpcf7-row-two').removeClass('input-filled-active');
        $(this).parents('.wpcf7-row-two').addClass('input-filled-active');
    })
    $(".wpcf7-form-control").focusout(function(){
        $(this).parents('.wpcf7-row-two').removeClass('input-filled-active');
    });
    var filled = $(".wpcf7-form-control").val();
    if(filled == '') {
        $('.wpcf7-form-control').parents('.wpcf7-row-two').removeClass('input-filled-hold');
    }
    $(".wpcf7-form-control").change(function(){ 
        $(this).parents('.wpcf7-row-two').addClass('input-filled-hold');
    });

    /* Disnable Link */
    $(".dislink a").click(function() { return false; });
    
	/* Section Offset */
	function cms_section_offset() {
		if($(window).width() > 768) {
			var w_desktop = $(window).width();
			var w_padding = (w_desktop - 1170)/2;
			$('.section-offset-left').css('padding-left',w_padding+'px');
			$('.section-offset-right').css('padding-right',w_padding+'px');
		}
	}

	/* Resize Height Cart */
	function cms_cart_scroll() {
		var h_basket = $('#cshero-header .widget-basket .basket-widget-content').height();
		if(h_basket > 400 && $(window).width() > 993) {
			$('#cshero-header .widget-basket .jobboard-widget-content').css('height',350+'px');
		}
		if(h_basket > 300 && $(window).width() < 992) {
			$('#cshero-header .widget-basket .jobboard-widget-content').css('height',208+'px');
		}
	}

	/* Section Overlay */
	$(".row-overlay").append( "<div class='row-overlay-inner'></div>" );

	/* Job */
    $('.sidebar-job-border .wg-title').each(function() {
         $(this).on('click', function() {
            $(this).parent().find('.widget-content').slideToggle();
            $(this).parent().toggleClass('sidebar-effect');
        });
    });

	$('.jobboard-archive-actions').on('change', '.jb-layout', function () {
		$('.jobboard-archive-actions').submit();
	});

	$('.jobboard-archive-actions .jb-sort').on('click', function() {
    	$(this).parent().toggleClass('active');
    });

    $('.event-modern').parents('#cms-content').addClass('rm-padding-top');

	/* tooltip */
	$('[data-toggle="tooltip"]').tooltip();

	/**
	 * Page Loading.
	 */
	function cms_page_loading($load) {
		switch ($load) {
		case 1:
			$('#cms-loadding').css('display','block')
			break;
		default:
			$('#cms-loadding').css('display','none')
			break;
		}
	}
	/**
	 * One page
	 *
	 * @author CMSSuperHeroes Team
	 */
	if(typeof(one_page_options) != "undefined"){
		one_page_options.speed = parseInt(one_page_options.speed);
		$('.main-navigation').singlePageNav(one_page_options);
	}

	function cms_modal_job() {
		if($(window).width() < 1400) {
			var h_modal = $('.jobboard-modal .md-content').outerHeight();
			if(h_modal > 310) {
				$('.jobboard-modal .md-content').css('height',480+'px');
				$('.jobboard-modal .md-content').enscroll();
			}
		}
	}

	var jb_N = $('.job-newsletter .job-newsletter-form .tnp-submit').width() + 67;
	$('.job-newsletter .job-newsletter-form .tnp-field-email').css('padding-right',jb_N+'px');

	$('.jr-holder').each(function() {
         $(this).on('click', function() {
            $(this).parent().find('.jr-action').slideToggle();
        });
    });


});
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2018 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

//custom scrollbar js
!function(t,e){"object"==typeof exports?module.exports=e(window,document):t.SimpleScrollbar=e(window,document)}(this,function(t,e){function s(t){Object.prototype.hasOwnProperty.call(t,"data-simple-scrollbar")||Object.defineProperty(t,"data-simple-scrollbar",{value:new o(t)})}function i(t,s){function i(t){var e=t.pageY-a;a=t.pageY,n(function(){s.el.scrollTop+=e/s.scrollRatio})}function r(){t.classList.remove("ss-grabbed"),e.body.classList.remove("ss-grabbed"),e.removeEventListener("mousemove",i),e.removeEventListener("mouseup",r)}var a;t.addEventListener("mousedown",function(s){return a=s.pageY,t.classList.add("ss-grabbed"),e.body.classList.add("ss-grabbed"),e.addEventListener("mousemove",i),e.addEventListener("mouseup",r),!1})}function r(t){for(this.target=t,this.direction=window.getComputedStyle(this.target).direction,this.bar='<div class="ss-scroll">',this.wrapper=e.createElement("div"),this.wrapper.setAttribute("class","ss-wrapper"),this.el=e.createElement("div"),this.el.setAttribute("class","ss-content"),"rtl"===this.direction&&this.el.classList.add("rtl"),this.wrapper.appendChild(this.el);this.target.firstChild;)this.el.appendChild(this.target.firstChild);this.target.appendChild(this.wrapper),this.target.insertAdjacentHTML("beforeend",this.bar),this.bar=this.target.lastChild,i(this.bar,this),this.moveBar(),this.el.addEventListener("scroll",this.moveBar.bind(this)),this.el.addEventListener("mouseenter",this.moveBar.bind(this)),this.target.classList.add("has-scrollbar");var s=window.getComputedStyle(t);"0px"===s.height&&"0px"!==s["max-height"]&&(t.style.height=s["max-height"])}function a(){for(var t=e.querySelectorAll("*[has-scrollbar]"),i=0;i<t.length;i++)s(t[i])}var n=t.requestAnimationFrame||t.setImmediate||function(t){return setTimeout(t,0)};r.prototype={moveBar:function(t){var e=this.el.scrollHeight,s=this.el.clientHeight,i=this;this.scrollRatio=s/e;var r="rtl"===i.direction,a=r?i.target.clientWidth-i.bar.clientWidth+18:-1*(i.target.clientWidth-i.bar.clientWidth);n(function(){i.scrollRatio>=1?i.bar.classList.add("ss-hidden"):(i.bar.classList.remove("ss-hidden"),i.bar.style.cssText="height:"+Math.max(100*i.scrollRatio,10)+"%; top:"+i.el.scrollTop/e*100+"%;right:"+a+"px;")})}},e.addEventListener("DOMContentLoaded",a),r.initEl=s,r.initAll=a;var o=r;return o});

jQuery(function ($) {
      function socialStikcy (){
          var socialDiv = $(".article-ratings-social-share").offset()?.top -70;
          var windowTop = $(window).scrollTop();
          if(windowTop> socialDiv){
            $(".article-ratings-social-share").css("position", "sticky");
          }
      }
      
   
     // Stikcy Header
    if ($('body').hasClass('sticky-header')) {
        var header = $('#sp-header');

        if($('#sp-header').length) {
            var headerHeight = header.outerHeight();
            var stickyHeaderTop = header.offset().top;
            header.before('<div class="nav-placeholder"></div>');
            var stickyHeader = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > stickyHeaderTop) {
                    header.addClass('header-sticky');
                    $('.nav-placeholder').height(headerHeight);
                } else {
                    if (header.hasClass('header-sticky')) {
                        header.removeClass('header-sticky');
                        $('.nav-placeholder').height('inherit');
                    }
                }
            };
            stickyHeader();
            $(window).scroll(function () {
                stickyHeader();
                if($(".com-content.view-article").length>0){
                    socialStikcy();
                }
            });
        }

        if ($('body').hasClass('layout-boxed')) {
            var windowWidth = header.parent().outerWidth();
            header.css({"max-width": windowWidth, "left": "auto"});
        }
    }

    // go to top
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.sp-scroll-up').fadeIn();
        } else {
            $('.sp-scroll-up').fadeOut(400);
        }
    });

    $('.sp-scroll-up').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    // Preloader
    $(window).on('load', function () {
        $('.sp-preloader').fadeOut(500, function() {
            $(this).remove();
        });
    });

    //mega menu
    $('.sp-megamenu-wrapper').parent().parent().css('position', 'static').parent().css('position', 'relative');
    $('.sp-menu-full').each(function () {
        $(this).parent().addClass('menu-justify');
    });

    // Offcanvs
    $('#offcanvas-toggler').on('click', function (event) {
        event.preventDefault();
        $('.offcanvas-init').addClass('offcanvas-active');
    });

    $('.close-offcanvas, .offcanvas-overlay').on('click', function (event) {
        event.preventDefault();
        $('.offcanvas-init').removeClass('offcanvas-active');
    });
    
    $(document).on('click', '.offcanvas-inner .menu-toggler', function(event){
        event.preventDefault();
        $(this).closest('.menu-parent').toggleClass('menu-parent-open').find('>.menu-child').slideToggle(400);
    });

    // Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], .hasTooltip'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            html: true
        });
    });

    // Popover
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Article Ajax voting
    $('.article-ratings .rating-star').on('click', function (event) {
        event.preventDefault();
        var $parent = $(this).closest('.article-ratings');

        var request = {
            'option': 'com_ajax',
            'template': template,
            'action': 'rating',
            'rating': $(this).data('number'),
            'article_id': $parent.data('id'),
            'format': 'json'
        };

        $.ajax({
            type: 'POST',
            data: request,
            beforeSend: function () {
                $parent.find('.fa-spinner').show();
            },
            success: function (response) {
                var data = $.parseJSON(response);
                $parent.find('.ratings-count').text(data.message);
                $parent.find('.fa-spinner').hide();

                if(data.status)
                {
                    $parent.find('.rating-symbol').html(data.ratings)
                }

                setTimeout(function(){
                    $parent.find('.ratings-count').text('(' + data.rating_count + ')')
                }, 3000);
            }
        });
    });

    //  Cookie consent
    $('.sp-cookie-allow').on('click', function(event) {
        event.preventDefault();
        
        var date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();               
        document.cookie = "spcookie_status=ok" + expires + "; path=/";

        $(this).closest('.sp-cookie-consent').fadeOut();
    });

    $(".btn-group label:not(.active)").click(function()
		{
			var label = $(this);
			var input = $('#' + label.attr('for'));
            
			if (!input.prop('checked')) {
				label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
				if (input.val() === '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
				input.trigger('change');
            }
            var parent = $(this).parents('#attrib-helix_ultimate_blog_options'); 
            if( parent ){ 
                showCategoryItems( parent, input.val() )
            }
		});
		$(".btn-group input[checked=checked]").each(function()
		{
			if ($(this).val() == '') {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn btn-primary');
			} else if ($(this).val() == 0) {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn btn-danger');
			} else {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn btn-success');
            }
            var parent = $(this).parents('#attrib-helix_ultimate_blog_options'); 
            if( parent ){
                parent.find('*[data-showon]').each( function() {
                    $(this).hide();
                })
            }
        });
        
        function showCategoryItems(parent, value){
            var controlGroup = parent.find('*[data-showon]'); 
            controlGroup.each( function() {
                var data = $(this).attr('data-showon')
                data = typeof data !== 'undefined' ? JSON.parse( data ) : []
                if( data.length > 0 ){
                    if(typeof data[0].values !== 'undefined' && data[0].values.includes( value )){
                        $(this).slideDown();
                    }else{
                        $(this).hide();
                    }
                }
            })
        }

        $(window).on('scroll', function(){
            var scrollBar = $(".sp-reading-progress-bar");
            if( scrollBar.length > 0 ){
                var s = $(window).scrollTop(),
                    d = $(document).height(),
                    c = $(window).height();
                var scrollPercent = (s / (d - c)) * 100;
                const postition = scrollBar.data('position')
                if( postition === 'top' ){
                    // var sticky = $('.header-sticky');
                    // if( sticky.length > 0 ){
                    //     sticky.css({ top: scrollBar.height() })
                    // }else{
                    //     sticky.css({ top: 0 })
                    // }
                }
                scrollBar.css({width: `${scrollPercent}%` })
            }
             
          })
          
          var $megaMenuDiv = $('<div class="menu-collapse-icon active"><span></span><span></span><span></span></div>'); 
          
        // Template Releated
        if ($(".main-megamenu").length > 0) {
            var $this = $(".main-megamenu");
            $this.find('ul.menu').wrapAll("<div class='sp-megamenu-main-wrap'></div>");
            $this.find(".sp-megamenu-main-wrap").prepend("<div class='menu-collapse-icon'><span></span><span></span><span></span></div>");
            $this.find("ul.menu").addClass("menu-area");
            $(".menu-collapse-icon").on('click', function () {
                $(".menu-area").toggleClass("active").prepend($megaMenuDiv);
            });

            $('.menu-collapse-icon').click(function () {
                $(this).toggleClass('active');
            });
        }
         //prepent icon click
        $(document).on("click", '.menu-area .menu-collapse-icon',function(){
            $(".menu-area").removeClass("active");
            $(".sp-megamenu-main-wrap >.menu-collapse-icon").removeClass("active");
        });

        
        $('#login .close').on('click', function(){
            $('#login').css('display', 'none');
            $('body').removeClass('login-open');
            $('#login').removeClass('active');
        })
        $('.open-login').on('click', function(){
            $('#login').css('display', 'block');
            $('#login').addClass('active');
            $('body').addClass('login-open');
        })

        if ($(".nano").length > 0) {
            $(".nano").each(function () {
                $(this).nanoScroller();
            })
        }

        if ($(".marquee").length > 0) {
            jQuery(".marquee").marquee({
                duration: 20 * jQuery(".marquee").width(),
                delayBeforeStart: 0,
                gap: 0,
                duplicated: true,
                pauseOnHover: true
            });
        }

    //Search
    var searchRow = $('.top-search-input-wrap').parent().closest('.row');
    $('.top-search-input-wrap').insertAfter(searchRow);

    $(".search-icon").on('click', function () {
        $(".top-search-input-wrap").slideDown(200);
        $(this).hide();
        $('.close-icon').show();
        $(".top-search-input-wrap").addClass('active');
    });

    $(".close-icon").on('click', function () {
        $(".top-search-input-wrap").slideUp(200);
        $(this).hide();
        $('.search-icon').show();
        $(".top-search-input-wrap").removeClass('active');
    });

    // press esc to hide search
    $(document).keyup(function (e) {
        if (e.keyCode == 27) { // esc keycode
            $(".top-search-input-wrap").fadeOut(200);
            $(".close-icon").fadeOut(200);
            $(".search-icon").delay(200).fadeIn(200);
            $('body.off-canvas-menu-init').css({ 'overflow-y': 'initial' });
        }
    });

    //Weather Toggle
	var forecastCurrent = $('.sp-weather-current');
	//var forecastIcon    = $('.show-forcasts');
	var forecastWrap    = forecastCurrent.parent();

	forecastCurrent.on('click', function(){
		$(this).next().slideToggle();
		$(this).next().toggleClass('active');
		//forecastIcon.toggleClass('icon-caret-up icon-caret-down');
	});

	$(document).on('click', function(e) {
    	// If the target of the click isn't the weather list wrap
		if(!forecastWrap.is(e.target) && forecastWrap.has(e.target).length === 0) {

			if(forecastCurrent.next().hasClass('active')) {
				// change icon
				//forecastIcon.removeClass('icon-caret-down').addClass('icon-caret-up');
				// slide up/toggle forecast
				forecastCurrent.next().slideToggle();
				forecastCurrent.next().toggleClass('active');
			}

		}
	});

	$('.sp-vertical-tabs').each(function(){
		var $tab = $(this),
		$handlers = $tab.find('.sp-tab-btns').children(),
		$tabs = $tab.find('.sp-tab-content').children();

		$handlers.each(function(index) {
			$(this).on('mouseenter', function(event){
				event.preventDefault();
				$handlers.removeClass('active');
				$tabs.removeClass('active');
				$(this).addClass('active');
				$($tabs[index]).addClass('active');
			});
		});
	});

    document.querySelector('body').addEventListener('click', function(e) {
        let targetDom = e.target.parentNode.parentNode;
        if(targetDom.classList.contains('mod-languages')) {
            if(targetDom.querySelector('.dropdown-toggle').classList.contains('show') || targetDom.querySelector('.lang-block').classList.contains('show')) {
                targetDom.querySelector('.dropdown-toggle').classList.remove('show');
                targetDom.querySelector('.lang-block').classList.remove('show');
            } else {
                targetDom.querySelector('.dropdown-toggle').classList.add('show');
                targetDom.querySelector('.lang-block').classList.add('show');
            }
        } else {
            targetDom.querySelector('.mod-languages .dropdown-toggle')?.classList.remove('show');
            targetDom.querySelector('.mod-languages .lang-block')?.classList.remove('show');
        }
    })
});


(function($) {
    'use strict';

    /**
    * kepler_theme.
     * @constructor
     * @property {string}  VERSION      - Build Version.
     * @property {string}  AUTHOR       - Author.
     * @property {string}  SUPPORT      - Support Email.
     * @property {string}  pageScrollElement  - Scroll Element in Page.
     * @property {object}  $body - Cache Body.
     */
    var kepler_theme = function() {
        this.VERSION = "1.0.0";
        this.AUTHOR = "Revox";
        this.SUPPORT = "support@revox.io";

        this.pageScrollElement = 'html, body';
        this.$body = $('body');

        this.setUserOS();
        this.setUserAgent();
        // this.adjustBuilderHeight();
    }

    /** @function setUserOS
    * @description SET User Operating System eg: mac,windows,etc
    * @returns {string} - Appends OSName to Pages.$body
    */
    kepler_theme.prototype.setUserOS = function() {
        var OSName = "";
        if (navigator.appVersion.indexOf("Win") != -1) OSName = "windows";
        if (navigator.appVersion.indexOf("Mac") != -1) OSName = "mac";
        if (navigator.appVersion.indexOf("X11") != -1) OSName = "unix";
        if (navigator.appVersion.indexOf("Linux") != -1) OSName = "linux";

        this.$body.addClass(OSName);
    }

    /** @function setUserAgent
    * @description SET User Device Name to mobile | desktop
    * @returns {string} - Appends Device to Pages.$body
    */
    kepler_theme.prototype.setUserAgent = function() {
        if (navigator.userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i)) {
            this.$body.addClass('mobile');
        } else {
            this.$body.addClass('desktop');
            var isIE = /*@cc_on!@*/false || !!document.documentMode;
            if (isIE) {
                this.$body.addClass('ie');
            }
        }
    }


    /** @function adjustBuilderHeight
    * @description execute function in builder to adjust iframe height when custom fonts are loaded
    */
    kepler_theme.prototype.adjustBuilderHeight = function() {
        // if not inside builder
        if(!window.parent.app){
            return; 
        }
    
        var customFonts = []

        window.parent.app.wp.WP_STYELKIT.VAR.fonts.forEach(function(font) {
            var fontFamily = Object.values(font)[0]['name']
            
            if(customFonts.indexOf(fontFamily) > -1) return;
            
            customFonts.push(fontFamily)
        })
        
          
        var observers = [];
        
        // Make one observer for each font,
        // by iterating over the data we already have
        customFonts.forEach(function(family) {
            var obs = new FontFaceObserver(family);
            observers.push(obs.load());
        });
        
        Promise.all(observers)
        .then(function(fonts) {
            setTimeout(function(){
                window.parent.app.relayout() 
            }, 1000)
        })
        .catch(function(err) {
            console.warn('Some critical font are not available:', err);
        });   
    }

    /** @function getUserAgent
    * @description Get Current User Agent.
    * @returns {string} - mobile | desktop
    */
    kepler_theme.prototype.getUserAgent = function() {
        return $('body').hasClass('mobile') ? "mobile" : "desktop";
    }
    kepler_theme.prototype.initStickySidebar = function(context) {
        $.fn.stickySidebar && $('[data-init-plugin="stickySidebar"]', context).each(function() {
        });
    }
    /** @function initClamp
    *   @description Crossbrowser Text clamp
    */
    kepler_theme.prototype.initClamp = function(){
        $('[data-init-plugin="clamp"]').each(function(){
            var module = $(this)[0];
            var attrLineHeight = $(this).attr("data-line-height");
            var lineHeight = 18;
            if(attrLineHeight){
                lineHeight = parseInt(attrLineHeight);
            }
            //title
            if($(this).hasClass("title")){
                var $titleText = $(this).find(".entry-title");
                var heightValue = ($titleText.height() - lineHeight );
                if(heightValue > 170){
                    $clamp(module, {clamp: heightValue +"px"});
                }
            }
            //content
            else if($(this).hasClass("content-inner")){
                var $content = $(this).parent();
                var heightValue = ($content.height() - lineHeight );
                $clamp(module, {clamp: heightValue +"px"});
            }
            else{
                var heightValue = ($(this).height() - lineHeight ) +"px";
                $clamp(module, {clamp: heightValue});
            }
            
        });
    },
    /** @function initFormSubmitToggle
    *   @description Comment form toggler
    */
    kepler_theme.prototype.initFormSubmitToggle = function(context) {
        $(document).click(function(event) { 
            var $target = $(event.target);
            if($target.closest('.comment-form').length) return; 

            if($('.comment-form').hasClass("active")) {
                $('.comment-form').stop().animate({"height": "60px"},"fast").removeClass('active')
            }     
        });

        $('[data-kepler-trigger="comments"]', context).on('click',function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleCommentBox($('.comment-form'));
        });
        
        $('.comment-form', context).on('click',function(e) {
            e.stopPropagation();
            toggleCommentBox(this);
        });

        $('.comment-reply-link').on('click', function(e) {
            setTimeout(function() {
                toggleCommentBox($('.comment-form'));
            },100);
        })

        function toggleCommentBox(el){
            if($(el).hasClass("active")) return; 

            var $wrapper = $(el).parent().parent();
            if(!$(el).hasClass()){
                if($wrapper.hasClass("no-login-comment-form")){
                    if($(!el).hasClass("active")){
                        setTimeout(function(){
                            $(".author").focus();
                        }, 500);
                    }
                }else{
                    if(!$(el).hasClass("active")){
                        setTimeout(function(){
                            $(".comment").focus();
                        }, 500);
                    }
                }
       
                var contentHeight = 0
                $(el).children().not('script').each(function(){
                    contentHeight += $(this).outerHeight(true)
                })
                contentHeight += 20;

                $(el).stop().animate({"height": contentHeight+"px"}, "fast").addClass("active");
            }
        }
    }
    /** @function initSocialMediaFormatting
     *   @description On page load fix social media urls
     */
    kepler_theme.prototype.initSocialMediaFormatting=function (context) {
        var allSocialLinks=$('footer').find('#menu-social-menu').find('li');
        for(var i=0;i<allSocialLinks.length;i++){
            var socialLinkURL=$(allSocialLinks[i]).find('a').text();
            $(allSocialLinks[i]).find('a').text(socialLinkURL.replace(/\.com.*/,''));
        }
    }
    /** @function initPageHeader
    *   @description On page header scroll fixed / static
    */
    kepler_theme.prototype.initPageHeader = function(context) {
        var body = $("body");
        var header = $("#header");
        var headerInner = $('[data-kepler="header"]'),
        content = $('#content').eq(0),
        fix = content.attr('style') || '',
        scrolledClass = 'header-scrolled',scrollBody;
        var timer;
        var offSetTop = headerInner.outerHeight();
        var bodyOffset = 0;
        var topOffset = 0;
        //Autoscale submenu items to fit
        header.find(".sub-menu li a").each(function(){
            var fs = $(this).css("font-size");
            fs = parseFloat(fs)
            if(fs > 14){
                $(this).css("font-size",fs - 2);
            }
        });

        if(!body.hasClass("page") && headerInner.hasClass("header-top")){
            $("#primary").css('padding-top',offSetTop);
        }

        if(body.hasClass("admin-bar")){
          bodyOffset = 0;
          topOffset = 32;
          if(window.innerWidth <= 768){
              bodyOffset = 47;
          }
        }
        
        if($("body").hasClass("page-designer")){
            scrollBody = $("body");
            $("body").scroll(function(event){
                pageScroll();
            });
        }else{
            scrollBody = $(window);
            $(window).scroll(function(event){
                pageScroll();
            });
        }

        function pageScroll(){
            var st = scrollBody.scrollTop();
            if(headerInner.hasClass("header-sticky")){
                if (st > bodyOffset) {
                    if(!body.hasClass("page-designer")){
                        headerInner.css('top',topOffset)
                    }
                    header.addClass(scrolledClass);
                    if(!headerInner.hasClass("header-top")){
                        if(body.hasClass("page")){
                            content.css('margin-top', offSetTop + topOffset);
                        }else{
                            content.css('margin-top', offSetTop);
                        }
                    }
                } else {
                    header.removeClass(scrolledClass);
                    content.attr('style', fix);
                    if(!body.hasClass("page-designer")){
                        headerInner.attr('style', fix);
                    }
                }
            }
        }

        function _closeMenu(){
            var $cloneHeader = $("#cloneHeader");
            var $cloneNav = $("#cloneHeader").children(".navbar-collapse");
            $cloneNav.removeClass("active");
            body.removeClass("menu-opened");
            timer = window.setTimeout(function(){
                $cloneNav.addClass("hidden");
                $cloneHeader.remove();
            },500);
        }
        //Mobile Menu dropdown
        $(document).on("click",".navbar-collapse .menu-item-has-children",function(){
            var sub = $(this).children(".sub-menu");
            $(this).toggleClass("open");
            sub.slideToggle();
        })
        //Mobile Menu trigger button
        $("[data-nav-toggle]").on("click",function(e){
            var el = $(e.target).parent();
            var el_id = el.parent().attr("id");
            var nav = el.next().clone();
            nav.removeClass("menu").find(".menu").removeClass("menu").addClass("navbar-nav")
            if(body.hasClass("menu-opened")){
               _closeMenu();
            }else{
                window.clearTimeout(timer);
                var cloneHeader = $('<div id="cloneHeader" class="header-clone '+el_id+'"></div>');
                $('body #page').append(cloneHeader);
                nav.appendTo("#cloneHeader");
                nav.removeClass("hidden")
                body.addClass("menu-opened");
                timer = window.setTimeout(function(){
                    nav.addClass("active")
                },150);
            }
        });
        //Close button for mobile menu
        $("body").on("click","[data-nav-close]",function(e){
            e.preventDefault();
            if(body.hasClass("menu-opened")){
                _closeMenu();
            }
        });
    }
    /** @function initHeader
    *   @description Blog Header toggler and dropdown navigation
    */
	kepler_theme.prototype.initHeader = function(context) {
        // Hide Header on on scroll down
        var didScroll;
        var lastScrollTop = 0;
        var delta = 5;
        var navbarHeight = $('.default-header').outerHeight();
        var headerEl = $('.default-header');
        var scrollBody = $(window)
        var body = $("body");
        $(window).scroll(function(event){
            didScroll = true;
        });

        setInterval(function() {
            if (didScroll && headerEl.length > 0) {
                hasScrolled();
                didScroll = false;
            }
        }, 250);

        function hasScrolled() {
            var st = scrollBody.scrollTop();
            
            // Make sure they scroll more than delta
            if(Math.abs(lastScrollTop - st) <= delta)
                return;
            
            if (st > lastScrollTop && st > navbarHeight){
                // Scroll Down
                if(!body.hasClass("page")){
                    $('#header').addClass('header-scrolled');
                }
            } else {
                // Scroll Up
                if(st + $(window).height() < $(document).height()) {
                    if(!body.hasClass("page")){
                        $('#header').removeClass('header-scrolled');
                    }
                }
            }
            //header border for pages
            if (st == 0){
                headerEl.removeClass('header-bordered');
            }else{
                headerEl.addClass('header-bordered');
            }

            lastScrollTop = st;
        }


        // mobile menu toggle
        $('.mobile-menu-toggle').click(function() {
            $('#mobile-nav').toggleClass('open');
            $("body").toggleClass("menu-open");
        });

        //Hover menu 
        $("#main-nav .page_item_has_children").on("mouseenter",function(){
            $("#main-nav .is-hovered").removeClass("is-hovered");
            $(this).addClass("is-hovered");
        });
        $("#main-nav .children").on("mouseleave",function(){
            $("#main-nav .is-hovered").removeClass("is-hovered");
        })

        //Input Search
        $("#header #searchform input.text").on("focus",function(){
            $(this).parent().parent().parent().addClass("focus")
        })
        $("#header #searchform input.text").on("focusout",function(){
            $(this).parent().parent().parent().removeClass("focus")
        })

        // Show search overlay
        $('#header #search-overlay-show').click(function(e){
            e.preventDefault();
            $('body').addClass('search-visible')
        })
        // Dismiss search overlay
        $('#header #searchform #search-overlay-close').click(function(e){
            e.preventDefault();
            $('body').removeClass('search-visible')
        })

        $('#header .search-overlay-inner').click(function(e){
            e.stopPropagation();
            
        })
        $('#header .search-overlay').click(function() {
            $('body').removeClass('search-visible')
        })

    }
    /** @function initGoogleMap
    *   @description Initialize google maps using data api
    */
    kepler_theme.prototype.initGoogleMap = function(context) {
        $('[data-init-plugin="googlemaps"]').each(function(){
            var mapEl = $(this).find(".map-element")[0];
            var props = $(this).data();
            var lat = parseFloat(props.cordinatesLat);
            var lng = parseFloat(props.cordinatesLng);
            var zoom = parseInt(props.zoom);
            var mapType = props.mapType;

            var config = props.json
            if (config) {
                config = config.replace(/'/g, '"');
                config = config.replace(/`/g, '"');
                config = config.replace(/%10/g, '[');
                config = config.replace(/%20/g, ']');
                config = JSON.parse(config)
            }

            var googleMap = new google.maps.Map(mapEl, {
                center: {
                    lat: lat,
                    lng: lng
                },
                mapTypeId: mapType,
                zoom: zoom,
                styles: config,
                disableDefaultUI: true
            });
            if (props.marker) {
                var marker = new google.maps.Marker({
                    position: {
                        lat: lat,
                        lng: lng
                    },
                    map: googleMap,
                });
            }            
        })
    }
    /** @function initSlider
    *   @description Initialize SwiperJS with Data API
    */
    kepler_theme.prototype.initSlider = function(context) {

        function getSlidePerViewport($slider) {
            var wWidth = $(window).width();
            if(wWidth > 1200) {
                return 1;
            } else if(wWidth > 993) {
                return $slider.data('slidesDesktop')
            } else if(wWidth > 320) {
                return $slider.data('slidesTablet')
            } else {
                return $slider.data('slidesMobile')
            }
        }

		window.Swiper && $('[data-init-plugin="swiper"]', context).each(function() {
            var data = $(this).data();
            var $self = $(this);
            var effect = data['contentTransition'] === 'fadeIn' ? 'fade' : 'slide'
            var animatedElements = 'h1,h2,h3,h4,h5,h6,p, .btn, .button, .card, .icon-wrapper, .image-wrapper, .map-wrapper, .shortcode, .video-wrapper'

            var config = {
                autoHeight: false,
                simulateTouch: false,
                effect: effect,
                on:{
                    init: function () {
                        if($self.data("contentTransition") === 'none' || $self.data("contentTransition") === 'fadeIn') return; 

                        var slideWidth = this.virtualSize / this.slides.length 
                        var visibleSlidesMaxRange = Math.ceil(this.width / slideWidth) 

                        var hiddenSlides = $self.find(".swiper-slide").slice(visibleSlidesMaxRange).addClass('hiddenEl');
                        var visibleElements = $self.find(".swiper-slide").slice(0, visibleSlidesMaxRange).find(animatedElements)

                        hiddenSlides.find(animatedElements).css({
                            opacity: 0
                        })

                        visibleElements
                        .delay(1000)
                        .velocity("transition."+$self.data("contentTransition"), { 
                            stagger: $self.data("contentTransitionSpeed")  || 0
                        })

                    },
                    slideChangeTransitionEnd:function(){
                        if($self.data("contentTransition") === 'none' || $self.data("contentTransition") === 'fadeIn') return; 

                        var slideWidth = this.virtualSize / this.slides.length 
                        var visibleSlidesMaxRange = Math.ceil(this.width / slideWidth) 
                        
                        var allSlides = $self.find(".swiper-slide")
                        var visibleSlides = allSlides.slice(this.realIndex, this.realIndex + visibleSlidesMaxRange)
                        var hiddenSlidesInVisibleRange = visibleSlides.filter('.hiddenEl')
                        var elementsToAnimate = hiddenSlidesInVisibleRange.find(animatedElements)

                        hiddenSlidesInVisibleRange.removeClass('hiddenEl');
                        
                        elementsToAnimate
                        .velocity("transition."+$self.data("contentTransition"), { 
                            stagger: $self.data("contentTransitionSpeed")  || 0
                        })   
                    }
                }
            }
            if(data["autoPlay"]){
                var autoPlay = {
                    delay : 3000,
                    stopOnLastSlide:false
                    // loop: true
                }
                if(data["autoPlayDuration"]) {
                    autoPlay["delay"] = data["autoPlayDuration"] * 1000
                }
                if(data["autoPlayOnce"]){
                    autoPlay["stopOnLastSlide"] = true
                }

                config["autoplay"] = autoPlay;
            }

            data["spaceBetween"] ? config["spaceBetween"] = data["spaceBetween"]: null;

            data["transitionSpeed"] ? config["speed"] = data["transitionSpeed"]: null;


            if(data['responsive']) {
                data["slidesPerView"] ? config["slidesPerView"] = data["slidesPerView"]: null;
                if(data["slidesDesktop"] || data["slidesMobile"] || data["slidesTablet"] ){
                    var breakpoints = {
                        320: {
                            slidesPerView: 1
                        },
                        993: {
                            slidesPerView: 1
                        },
                        1200: {
                            slidesPerView: 1   
                        }
                    }
                    data["slidesDesktop"] !== 'auto' ? breakpoints["1200"]["slidesPerView"] = data["slidesDesktop"]: 1;
                    data["slidesTablet"]  !== 'auto'? breakpoints["993"]["slidesPerView"] = data["slidesTablet"]: 1;
                    data["slidesMobile"]  !== 'auto'? breakpoints["320"]["slidesMobile"] = data["slidesMobile"]: 1;
                    config["breakpoints"] = breakpoints;

                    config["slidesPerView"] = data["slidesDesktop"] 

                }
            } else {
                config["slidesPerView"] = 'auto'
            }
            
            if(data["controlButtons"]){
                config.navigation = {
                    nextEl: $(this).find('.swiper-button-next'),
                    prevEl: $(this).find('.swiper-button-prev'),
                }
            }
            if(data["pagination"]){
                config.pagination = {
                    el: $(this).find('.swiper-pagination'),
                    clickable: true
                }
            }
            new Swiper($(this),config)
            
        });
    }
    /** @function scrollToElement
    *   @description Scroll to section on link link
    */
   kepler_theme.prototype.scrollToElement = function() {
    $('[data-scroll-to="true"]').on("click",function(event){
        event.preventDefault();
        var elId = $(this).attr("href");
        $('html, body').animate({
            scrollTop: $(elId).offset().top
        }, 250);
    });
   }
       /** @function initCopyText
    *   @description Copy text selection to clipboard
    */
   kepler_theme.prototype.initCopyText = function() {
    $('[data-init="copy"]').on("click",function(e){
        e.preventDefault();
        var input = $(this).prev();
        input[0].select();
        document.execCommand("copy");
        var tooltip = $(this).parent().find('.alt-space-tooltip');
        $(tooltip).addClass('active copied');
        $(tooltip).text('Link copied');
    });
    $('[data-init="copy"]').hover(
        function(e) {
            e.preventDefault();
            var tooltip = $(this).parent().find('.alt-space-tooltip');
            $(tooltip).addClass('active');
        }, function(e) {
            e.preventDefault();
            var tooltip = $(this).parent().find('.alt-space-tooltip');
            $(tooltip).removeClass('active copied');
            $(tooltip).text('Copy link');
        }
    );
   }
    /** @function scrollToComments
    *   @description scroll to the comment section
    */
   kepler_theme.prototype.scrollToComments = function() {
    $('[data-init="scoll-comments"]').on("click",function(e){
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $(".post-footer-author-wrapper").offset().top-50,
        }, 700);
    });
   }
    /** @function initColorTheif
    *   @description Get Dominant Color from image
    */
    kepler_theme.prototype.initColorTheif = function() {
        $('[data-init-plugin="color-theif"]').each(function(){
            var colorThief = new ColorThief();
            var img = $(this)[0];
            
            if (img.complete) {
              var c = colorThief.getColor(img);
            } else {
                img.addEventListener('load', function() {
                var c = colorThief.getColor(img);
                //If Load script before image
                if(c){
                    $("#perc").css("background-color","rgb("+c[0]+","+c[1]+","+c[2]+")")
                }
              });
            }
            //If Load image before script
            if(c){
                $("#perc").css("background-color","rgb("+c[0]+","+c[1]+","+c[2]+")")
            }
        });
    }
    /** @function initArticleUtils
    *   @description All related article plugins such hero loading, readtime
    *   & read progress bar.
    */
   kepler_theme.prototype.initArticleUtils = function() {
    var self = this;
    //Scroll Reveal
    $(window).scroll( function(){
        $('[data-scroll-reveal="true"]').each( function(i){
            if(isInViewPort($(this))){
                if(!$(this).hasClass("loading")){
                    $(this).addClass("reveal");
                }
            }
        });
    });

    //Grid Hero
    $('[data-init-plugin="article-hero-grid"] img').on('load', function() {
        var $el = $(this).next();
        $el.css("background-image","url("+$(this).attr("src")+")");
        $el.addClass("loaded");
        var $parent = $el.parent();
        //Auto reveal if loaded
        if($parent[0].hasAttribute("data-scroll-reveal")){
            if(isInViewPort($parent)){
                $parent.removeClass("loading");
                $parent.addClass("reveal");
            }
        }
      }).each(function() {
        if(this.complete) $(this).load();
    });

    //If article hero image is missing init plugins anyway
    if($('[data-init-plugin="article-hero"]').length == 0){
        $(".article-wrapper").removeClass("loading");
        this.initReadTime();
        this.initNavigationPost();
    }

    //Article Hero
    $('[data-init-plugin="article-hero"] img').on('load', function() {
        var $el = $(this).next();
        $el.css("background-image","url("+$(this).attr("src")+")");
        $el.addClass("loaded");
        $(".article-wrapper").removeClass("loading");
        var $parent = $el.parent();
        //Auto reveal if loaded
        $parent.removeClass("loading");
        setTimeout(function(){
            self.initReadTime();
            self.initNavigationPost();
        },2400)
      }).each(function() {
        if(this.complete) $(this).load();
    });

    $('[data-article-tooltip="true"]').each(function(){
        var $tooltip = $(this).children(".alt-space-tooltip")
        $(this).on("mouseenter",function(){
            $tooltip.addClass("active");
        });
        $(this).on("mousemove",function(event){
            $tooltip.css({
                "top":(event.clientY - 30) +"px",
                "left":event.clientX+"px"
            });
        });
        $(this).on("mouseleave",function(){
            $tooltip.removeClass("active");
        });

        $(this).on("click",function(){
            var $el = $(this).parent().find(".entry-title a");
            if($el.length > 0){
                $el[0].click();
            }else{
                var $el = $(this).parent().parent().find(".entry-title a");
                if($el.length > 0){
                    $el[0].click();
                }
            }
        })

        
    });
    function isInViewPort($el){
        var bottom_of_object = $el.offset().top + $el.outerHeight();
        var bottom_of_window = $(window).scrollTop() + $(window).height();
        /* If the object is completely visible in the window, fade it it */
        if( bottom_of_window > bottom_of_object ){
            return true;
        }
        else{
            return false;
        }
    }
   }
    /** @function initNavigationPost
    *   @description Post utility bar
    */
   kepler_theme.prototype.initNavigationPost = function() {
        var $heroImage = $(".post > .hero-container");
        var $footer = $("footer");
        var $navigation = $(".navigation-post");
        var $heroRect = false;
        var $footerRect = false;
        var windowH = $(window).height();
        if($heroImage.length == 0){
            $navigation.css("opacity","1");
        }
        if($heroImage.length != 0){
            $heroRect = $heroImage.offset();
            $heroRect.height = $heroImage.height();
        }
        if($footer.length != 0){
            $footerRect = $footer.offset();
        }

        $(window).on("scroll",function(){
             var scroll = $(window).scrollTop();
             if($heroImage.length != 0){
                if((scroll + (windowH / 2)) > $heroRect.height && (scroll + (windowH / 2)) < $footerRect.top){
                    $navigation.css("opacity","1");
                }else{
                    $navigation.css("opacity","0");
                }
             }else{
                if((scroll + (windowH / 2)) > $footerRect.top){
                    $navigation.css("opacity","0");
                }else{
                    $navigation.css("opacity","1");
                }
             }

        });
 

   }
    /** @function initReadTime
    *   @description Post utility bar
    */
   kepler_theme.prototype.initReadTime = function() {
    $('[data-init-plugin="current-article"]').each(function(){
        var $article = $(".entry-content");
        var $articleHeader = $(this);
        var $entryTitle = $(".entry-title-wrapper");
        var $perc = $articleHeader.find(".perc")
        var top = $entryTitle.offset().top + $entryTitle.height();
        var outerHeight = $article.height();
        var windowHeight = $(window).height();
        var self = this;
        //set width
        $('[data-init-plugin="current-article"]').css("width",($(".article-inner").width() + 32)+"px");
        //Remaining time calc
        var wordsPerMinute = 200; // Average case.
        var time;
        var textLength = $(".entry-content").text().split(' ').length; // Split by words
        if(textLength > 0){
          var value = Math.ceil(textLength / wordsPerMinute);
          time = value+' min read';
          $("[data-reading-time]").html(time);
        }

        $(window).on('scroll', function(){
            onScroll()
        });

        $(window).on('resize', function(){
            $(self).css("width",$(".article-inner").width()+"px");
        });

        onScroll();
        function onScroll(){
            var scrollTop = $(window).scrollTop();
            var per = (((scrollTop + (windowHeight / 1.4)) - top) / outerHeight) * 100;
            if(per > 0  && per < 100){
                $articleHeader.addClass("active")
            }else{
                $articleHeader.removeClass("active")
            }
            $perc.css('width', per + "%" );
        }
    });
    
   }

    /** @function initVideoPlayer
    *   @description Initializes youtube and vimeo players for video elements and background video
    */
   kepler_theme.prototype.initVideoPlayer = function() {
   
        function fitYoutubeVideo(elem, player) {
            var parent = $(elem).parent();
            var iframe = $(elem).find('iframe')

            var w = parent.width(),
            h = parent.height()+200;
            var hideTitleOffset = '-60px'
        
            if (w/h > 16/9){
                player.setSize(w, w/16*9);
                iframe.css({'transform': 'translateY('+hideTitleOffset+')'});
            } else {
                player.setSize(h/9*16, h);
                iframe.css({'transform': 'translate3d(-' + (iframe.outerWidth()-w)/2 + 'px, '+hideTitleOffset+', 0)'});
            }
        }

        function fitVimeoVideo(elem) {
            var parent = $(elem).parent();
            var iframe = $(elem).find('iframe')

            var w = parent.width(),
            h = parent.height()+200;
            var cropFactor = 1.5 // to hide seek bar

            var newW = w * cropFactor
            var newH = w/16*9 * cropFactor

            if(h > w) {
                newH = (h - 200) * cropFactor
                newW = h*16*9 * cropFactor
            }
            iframe.css({
                width: newW,
                height: newH,
                position: 'absolute',
                top: '50%',
                left: '50%',
                'transform': 'translate(-50%, -50%)',
            });
        }

        function initYouTube(elem) {
            var videoId = $(elem).data('videoid')
            var videoType = $(elem).data('videoType')
            var mute = $(elem).data('controlsMute');
            var loop = $(elem).data('controlsLoop')
            var info = $(elem).data('controlsInfo')
            var autoplay = $(elem).data('controlsAutoplay')

            var container = $(elem).find('div').get(0)

            var player = new YT.Player(container, {
                width: '100%',
                height: '100%',
                videoId: videoId,
                playerVars: {
                    'rel': 0,
                    'showinfo': info ? 1 : 0,
                    'autoplay': autoplay ? 1 : 0,
                    'playlist': videoId,
                    'loop': loop ? 1 : 0,
                    'autohide': 1,
                    'color': 'white',
                    'theme': 'light',
                    'controls': 0
                },
                events: {
                    'onReady': function(event) {
                        if(mute) {
                            event.target.mute();
                        }
                        var isBgVideo = $(elem).parent().hasClass('bgVideo')
                        if(isBgVideo) {
                            fitYoutubeVideo(elem, event.target)
                            $(window).resize(function () { fitYoutubeVideo(elem, event.target); });
                        }
                    },
                }
            });
            $(elem).data('player', player)

        }

        function initVimeo(elem) {
            var container = $(elem).find('div').get(0)
            var videoId = $(elem).data('videoid')
            var mute = $(elem).data('controlsMute');
            var loop = $(elem).data('controlsLoop')
            var info = $(elem).data('controlsInfo')
            var autoplay = $(elem).data('controlsAutoplay')

            var options = {
                id: videoId,
                loop: loop == '' ? false : loop,
                muted: mute == '' ? false : mute, 
                autoplay: autoplay == '' ? false : autoplay,
                title: info == '' ? false : info

            };
        
            var player = new Vimeo.Player(container, options);

            var callback = function() {
                var isBgVideo = $(elem).parent().hasClass('bgVideo')
                if(isBgVideo) {
                    fitVimeoVideo(elem)
                    $(window).resize(function () { fitVimeoVideo(elem); });
                }
            };

            player.on('loaded', callback);
        }

        window.onYouTubeIframeAPIReady = function() {
            $('[data-init-plugin="video"][data-video-type="youtube"]').each(function(){
                initYouTube(this)
            });
        }
    
        $('[data-init-plugin="video"][data-video-type="vimeo"]').each(function(){
            initVimeo(this)
        });

   }


    /** @function init
    *   @description Inintialize all core components.
    */
    kepler_theme.prototype.init = function() {
        // init core js functions
        this.initHeader();
        this.initPageHeader();
        this.initClamp();
        this.initArticleUtils();
        this.initColorTheif();
        this.initCopyText();
        this.scrollToComments();
        this.initFormSubmitToggle()
        this.initSocialMediaFormatting()
        this.initSlider();
        this.initGoogleMap();
        this.scrollToElement();
        this.initVideoPlayer();
    }
    

    $.kepler_theme = new kepler_theme();
    $.kepler_theme.Constructor = kepler_theme;

})(window.jQuery);
/*
* Parallax Plugin 
*/
(function($) {
    'use strict';
    // PARALLAX CLASS DEFINITION
    // ======================

    var Parallax = function(element, options) {
        this.$element = $(element);
        this.body = $("#clientframe").contents().find("body");
        this.windowHeight = this.body.height();
        this.options = $.extend(true, {}, $.fn.parallax.defaults, this.$element.data());
        this.options.depth = ((this.options.depth / -10) * -1) / 2;
        this.intialTransform = this.getTransform();
        this.intialRect = this.$element[0].getBoundingClientRect();
        this.animate();
    }
    Parallax.VERSION = "1.0.0";

    Parallax.prototype.getTransform = function(){
        var element = this.$element[0];
        var style = window.getComputedStyle(element);
        var transform = style.getPropertyValue("-webkit-transform") ||
        style.getPropertyValue("-moz-transform") ||
        style.getPropertyValue("-ms-transform") ||
        style.getPropertyValue("-o-transform") ||
        style.getPropertyValue("transform");
        return transform;
    },

    Parallax.prototype.matrixToArray = function(transform,scrollPos){
        var values = transform.match(/([-+]?[\d\.]+)/g);
        var obj = {};
        if(values){
            //Matrix to CSS
            if(values.length == 6){
                var ypos = parseInt(values[5]) + (scrollPos * this.options.depth);
                obj.rotate = (Math.round(
                    Math.atan2(parseFloat(values[1]),parseFloat(values[0])) * (180/Math.PI)) || 0).toString() + 'deg';
                obj.translate = ypos ? values[4] + 'px, ' + ypos + 'px' : (values[4] ? values[4] + 'px' : '');
                obj.scale = parseFloat(Math.sqrt(values[0]*values[0] + values[1]*values[1])).toFixed(3);
                return obj;
            }
        }
    },

    Parallax.prototype.animate = function() {
        var rect = this.$element[0].getBoundingClientRect();
        var offset = rect.top;
        if(this.intialRect.top >= this.windowHeight){
            var scrollPos = Math.round(((offset - (this.windowHeight / 2) + this.intialRect.height) - this.$element.scrollTop()) * this.options.depth);
        }else{
            var scrollPos = Math.round(((offset - (this.windowHeight / 2) + this.intialRect.height) -  this.body.scrollTop()) * this.options.depth);
        }
        
        var obj = this.matrixToArray(this.intialTransform,scrollPos) || {};
        if(this.matrixToArray(this.intialTransform)){
            this.$element.css({
                'transform': 'translate('+obj.translate+') scale('+obj.scale+') rotate('+obj.rotate+') ',
                'transition':'none'
            });
        }else{
            this.$element.css({
                'transform': 'translateY(' + scrollPos + 'px)',
                'transition':'none'
            });
        }
    

    }

    // PARALLAX PLUGIN DEFINITION
    // =======================
    function Plugin(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data('pg.parallax');
            var options = typeof option == 'object' && option;

            if (!data) $this.data('pg.parallax', (data = new Parallax(this, options)));
            if (typeof option == 'string') data[option]();
        })
    }

    var old = $.fn.parallax

    $.fn.parallax = Plugin
    $.fn.parallax.Constructor = Parallax


    $.fn.parallax.defaults = {
        depth:1,
        scrollElement: window
    }

    // PARALLAX NO CONFLICT
    // ====================

    $.fn.parallax.noConflict = function() {
        $.fn.parallax = old;
        return this;
    }

    // PARALLAX DATA API
    //===================

    $(document).on('ready', function() {
        $('[data-kepler-parallax="true"]').each(function() {
            var $parallax = $(this);
            $parallax.parallax($parallax.data())
        })
    });
    $(document).on('scroll', function() {
        clearTimeout($.data(this, 'scrollTimer'));
        $('[data-kepler-parallax="true"]').parallax('animate');
        $.data(this, 'scrollTimer', setTimeout(function() {
           $('[data-kepler-parallax="true"]').css("transition","");
        }, 250));
    });

})(window.jQuery);

/*
* Hero Parallax Plugin 
*/
(function($) {
    'use strict';
    //Hero Parrallax
    var heroParrallax = function(element, options) {
        this.$element = $(element);
        this.options = $.extend(true, {}, $.fn.heroParrallax.defaults, options);
        this.$content = this.$element.find('.inner');

        if(this.options.scrollElement !== window) {
            $(this.options.scrollElement).on('scroll', function() {
                $(element).heroParrallax('animate');
            });
        }

    }
    heroParrallax.VERSION = "1.0.0";

    heroParrallax.prototype.animate = function() {

        var scrollPos;
        var pagecoverWidth = this.$element.height();
        //opactiy to text starts at 50% scroll length
        var opacityKeyFrame = pagecoverWidth * 50 / 100;
        var direction = 'translateX';

        scrollPos = $(this.options.scrollElement).scrollTop();
        direction = 'translateY';

        this.$content.css({
            'transform': direction + '(' + scrollPos * this.options.speed.content + 'px)',
            'transition':"none"
        });

    }

    // PARALLAX PLUGIN DEFINITION
    // =======================
    function Plugin(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data('kp.heroParrallax');
            var options = $.extend(true, {}, typeof option == 'object' ? option : {}, $this.data());
            
            if (!data) $this.data('kp.heroParrallax', (data = new heroParrallax(this, options)));
            if (typeof option == 'string') data[option]();
        })
    }

    var old = $.fn.heroParrallax

    $.fn.heroParrallax = Plugin
    $.fn.heroParrallax.Constructor = heroParrallax


    $.fn.heroParrallax.defaults = {
        speed: {
            coverPhoto: 0.3,
            content: 0.17
        },
        scrollElement: window
    }

    // heroParrallax NO CONFLICT
    // ====================

    $.fn.heroParrallax.noConflict = function() {
        $.fn.heroParrallax = old;
        return this;
    }

    // heroParrallax DATA API
    //===================

    $(window).on('load', function() {

        $('[data-kepler="hero-parrallax"]').each(function() {
            var $heroParrallax = $(this)
            $heroParrallax.heroParrallax($heroParrallax.data())
        })
    });

    $(window).on('scroll', function() {
        $('[data-kepler="hero-parrallax"]').heroParrallax('animate');
    });
})(window.jQuery);

/*
* Initializes kepler_theme core plugin
*/
(function($) {
    'use strict';
    // Initialize layouts and plugins
   $.kepler_theme.init();
})(window.jQuery);
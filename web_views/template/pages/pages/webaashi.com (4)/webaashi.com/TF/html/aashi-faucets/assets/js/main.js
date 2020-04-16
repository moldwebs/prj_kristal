/*
 *****************************************************
 *	CUSTOM JS DOCUMENT                              *
 *	Single window load event                        *
 *   "use strict" mode on                            *
 *****************************************************
 */
$(window).load(function(){  
	
    // ============================================
    // PreLoader On window Load
    // =============================================

     $('.preloader').addClass('loaderout');

    //========================================
    // Accordion 
    //======================================== 	

    if ($('#faqs-accordion').length) {
        $('#faqs-accordion').accordion();
    }

    //========================================
    // LightBox / Fancybox
    //======================================== 	

    if ($('.fancybox').length) {
        $('.fancybox').fancybox();
    }

    //========================================
    // Sidebar List Toggle 
    //======================================== 	

    $('.links-lists li').click(function(e) {

        if ($(this).find('>ul').hasClass('active')) {

            $(this).children('ul').removeClass('active').children('li').slideUp();

            $('.links-lists li').parent('ul').children('li').removeClass('active');

            $(this).addClass('active');
            if ($(this).hasClass('collapse-link')) {
                $(this).children('a').children('i').removeClass('fa-angle-down');
                $(this).children('a').children('i').addClass('fa-angle-right');
                e.preventDefault();
            }

            e.stopPropagation();
        } else {
            $(this).children('ul').addClass('active').children('li').slideDown();

            $('.links-lists li').parent('ul').children('li').removeClass('active');
            $(this).addClass('active');
            if ($(this).hasClass('collapse-link')) {
                $(this).children('a').children('i').removeClass('fa-angle-right');
                $(this).children('a').children('i').addClass('fa-angle-down');
            }
            e.stopPropagation();
        }
    });

    //***************************************
    // Mini Cart Settings
    //****************************************
	var cartOpen = $('#cartIcon, #whishlistIcon');
    if (cartOpen.length) {

        $('.closeCart').click(function() {
            if ($(this).parent('.shopping-cart').hasClass('active')) {
                $(this).parent('.shopping-cart').removeClass('active');
            }
        });
        cartOpen.click(function() {
            if ($('.user-menu').hasClass('active')) {
                $('.user-menu').removeClass('active');
            }
            if ($(this).next('.shopping-cart').hasClass('active')) {
                $(this).next('.shopping-cart').removeClass('active');
            } else if ($('.shopping-cart').hasClass('active')) {
                $('.shopping-cart').removeClass('active');
                $(this).next('.shopping-cart').addClass('active');
            } else {
                $(this).next('.shopping-cart').addClass('active');
            }
        });
    }

    //***************************************
    // User Menu Settings
    //****************************************

    if ($('#userIcon').length) {

        $('.closeMenu').click(function() {
            if ($(this).parent('.user-menu').hasClass('active')) {
                $(this).parent('.user-menu').removeClass('active');
            }
        });

        $('.user-menu li a').click(function() {
            if ($(this).parent('li').parent('.user-menu-items').parent('.user-menu').hasClass('active')) {
                $(this).parent('li').parent('.user-menu-items').parent('.user-menu').removeClass('active');
            }
        });

        $('#userIcon').click(function() {
            if ($('.shopping-cart').hasClass('active')) {
                $('.shopping-cart').removeClass('active');
            }
            if ($(this).next('.user-menu').hasClass('active')) {
                $(this).next('.user-menu').removeClass('active');
            } else if ($('.user-menu').hasClass('active')) {
                $('.user-menu').removeClass('active');


                $(this).next('.user-menu').addClass('active');
            } else {
                $(this).next('.user-menu').addClass('active');
            }

        });

    }

    //***************************************
    // Newsletter Popup Settings
    //****************************************

    if ($('#newsLetterPopup').length) {
        setTimeout(function() {
            $('#newsLetterPopup').fadeIn();
            $('#newsLetterPopup').addClass('showpopup');
        }, 3000);

        $('.close-news-letter').click( function(e) {
            e.preventDefault();
            $('#newsLetterPopup').removeClass('showpopup');
        });
    }

    //***************************************
    // Quick View Popup Settings
    //****************************************

    if ($('.quick-view-popup').length) {

        $('.quickview-box-btn').click(function(e) {
            e.preventDefault();
            $('.quick-view-popup').addClass('showpopup');

        });

        $('.close-quick-view').click(function(e) {
            e.preventDefault();
            $('.quick-view-popup').removeClass('showpopup');
        });

    }

    //***************************************
    // Price Rannge Filter Settings
    //****************************************

    if ($("#slider-range").length) {
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 500,
            values: [75, 300],
            slide: function(event, ui) {
                $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
            }
        });

        $("#amount").val("$" + $("#slider-range").slider("values", 0) +
            " - $" + $("#slider-range").slider("values", 1));
    }

    //***************************************
    // Checkout Page Effect function Calling
    //****************************************

    checkoutPageEffect();

    //***************************************
    // Map initialization function Calling
    //****************************************

    initMap();


    //***************************************
    // All Owl Carousel function Calling
    //****************************************

    owlCarouselInit();

}); // End of the window load event


//***************************************
// Checkout Page Effect function
//****************************************

function checkoutPageEffect() {
   
    $('.showlogin').click( function(e) {
        e.preventDefault();
        $('.login').slideToggle("slow");
    });

    $('.showcoupon').click(function(e) {
        e.preventDefault();
        $('.checkout_coupon').slideToggle("slow");
    });

    $('#ship-to-different-address-checkbox').change(function() {
        if (this.checked) {
            $('.shipping-fields').slideToggle('slow');
        } else {
            $('.shipping-fields').slideToggle('slow');
        }
    });

    $('#createaccount').change(function() {
        if (this.checked) {
            $('.create-account').slideToggle('slow');
        } else {
            $('.create-account').slideToggle('slow');
        }
    });


}

//***************************************
// Contact Page Map
//****************************************  

function initMap() {
    

    if ($('#gmap_canvas').length) {
        var myOptions = {
            zoom: 10,
            scrollwheel: false,
            draggable: true,
            center: new google.maps.LatLng(-37.81614570000001, 144.95570680000003),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);
        var marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(-37.81614570000001, 144.95570680000003)
        });
        var infowindow = new google.maps.InfoWindow({
            content: '<strong>Envato</strong><br>Envato, King Street, Melbourne, Victoria<br>'
        });
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map, marker);
        });

        infowindow.open(map, marker);
    }

}

//***************************************
// All owl Carousels 
//****************************************   

function owlCarouselInit() {



    if ($('#home1-main-slider').length) {
        $('#home1-main-slider').owlCarousel({
            autoPlay: true,
            items: 1,
            singleItem: true,
            navigation: true,
            pagination: true,

        });
    }

    if ($('#best-seller').length) {
        $('#best-seller').owlCarousel({
            autoPlay: false,
            items: 4,
            navigation: true,
            pagination: false,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [979, 3]

        });
    }

    if ($("#home-blog-carousel").length) {
        $("#home-blog-carousel").owlCarousel({
            autoPlay: false,
            items: 3,
            navigation: true,
            pagination: false,
            itemsDesktop: [1199, 3],
            itemsDesktopSmall: [979, 3]

        });
    }

    if ($('.wa-partner-carousel').length) {
        $('.wa-partner-carousel').owlCarousel({
            autoPlay: true,
            items: 4,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [979, 3],
            margin: 5,
            navigation: false,
            pagination: false

        });
    }

    if ($('#wa-slide-image').length) {
        $('#wa-slide-image').owlCarousel({
            autoPlay: true,
            items: 4,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [979, 3],
            itemsMobile: [979, 3],
            margin: 5,
            navigation: true,
            pagination: false
        });
    }

}
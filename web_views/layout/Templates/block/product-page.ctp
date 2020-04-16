<section id="<?php e($code)?>" class="page-product-box blockproductscategory products_block block section <?php e($css)?>">
<h4 class="title_block"> <span> <?php e($title)?> </span></h4>
<div id="productscategory-itemslider" class="flexslider">
 <div class="nav_top_right"></div>
 <?php e($body)?>
</div>
</section>

<script>
DocReadyFunction = (function() {
    var cached_function = DocReadyFunction;
    
    return function() {
        console.log('DocReadyFunction');
        
         jQuery(function($) {
            $('#productscategory-itemslider .sliderwrap').flexslider({
                easing: "swing",
                useCSS: false,
                slideshow: 0,
                slideshowSpeed: 7000,
                animationSpeed: 400,
                pauseOnHover: 1,
                direction: "horizontal",
                animation: "slide",
                animationLoop: 0,
                controlNav: false,
                controlsContainer: "#productscategory-itemslider .nav_top_right",
                itemWidth: 260,
                minItems: getFlexSliderSize({
                    'lg': 5,
                    'md': 5,
                    'sm': 4,
                    'xs': 3,
                    'xxs': 2
                }),
                maxItems: getFlexSliderSize({
                    'lg': 5,
                    'md': 5,
                    'sm': 4,
                    'xs': 3,
                    'xxs': 2
                }),
                move: 0,
                prevText: '<i class="icon-left-open-3"></i>',
                nextText: '<i class="icon-right-open-3"></i>',
                productSlider: true,
                allowOneSlide: false
            });
            var productscategory_flexslider_rs;
            $(window).resize(function() {
                clearTimeout(productscategory_flexslider_rs);
                var rand_s = parseInt(Math.random() * 200 + 300);
                productscategory_flexslider_rs = setTimeout(function() {
                    var flexSliderSize = getFlexSliderSize({
                        'lg': 5,
                        'md': 5,
                        'sm': 4,
                        'xs': 3,
                        'xxs': 2
                    });
                    var flexslide_object = $('#productscategory-itemslider .sliderwrap').data('flexslider');
                    if (flexSliderSize && flexslide_object != null)
                        flexslide_object.setVars({
                            'minItems': flexSliderSize,
                            'maxItems': flexSliderSize
                        });
                }, rand_s);
            });
        });;
        
        cached_function.apply(this);
    };
    }());
</script>
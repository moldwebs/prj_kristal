<?php $uniq_id = uniqid()?>
<div class="container">
<div id="featured_products_sldier_block_center_container_<?php e($uniq_id)?>" class="featured_products_sldier_block_center_container block">
   <section id="featured_products_sldier_block_center_<?php e($uniq_id)?>" class="featured_products_sldier_block_center products_block section ">
      <h4 class="title_block mar_b1"><span><?php e($title)?></span></h4>
      <div id="featured-itemslider-<?php e($uniq_id)?>" class="featured-itemslider flexslider">
         <div class="nav_top_right"></div>
         <?php e($body)?>
      </div>
   </section>
</div>
</div>
<script>
DocReadyFunction = (function() {
    var cached_function = DocReadyFunction;
    
    return function() {
        console.log('DocReadyFunction');
        
        var featured_itemslider_options<?php e($uniq_id)?>;
        jQuery(function($) {
            featured_itemslider_options<?php e($uniq_id)?> = {
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
                controlsContainer: "#featured-itemslider-<?php e($uniq_id)?> .nav_top_right",
                itemWidth: 160,
                minItems: getFlexSliderSize({
                    'lg': 7,
                    'md': 6,
                    'sm': 3,
                    'xs': 3,
                    'xxs': 2
                }),
                maxItems: getFlexSliderSize({
                    'lg': 7,
                    'md': 6,
                    'sm': 3,
                    'xs': 3,
                    'xxs': 2
                }),
                move: 0,
                prevText: '<i class="icon-left-open-3"></i>',
                nextText: '<i class="icon-right-open-3"></i>',
                productSlider: true,
                allowOneSlide: false
            };
            $('#featured-itemslider-<?php e($uniq_id)?> .sliderwrap').flexslider(featured_itemslider_options<?php e($uniq_id)?>);
            var featured_flexslider_rs<?php e($uniq_id)?>;
            $(window).resize(function() {
                clearTimeout(featured_flexslider_rs<?php e($uniq_id)?>);
                var rand_s = parseInt(Math.random() * 200 + 300);
                featured_flexslider_rs<?php e($uniq_id)?> = setTimeout(function() {
                    var flexSliderSize = getFlexSliderSize({
                        'lg': 7,
                        'md': 6,
                        'sm': 3,
                        'xs': 3,
                        'xxs': 2
                    });
                    var flexslide_object = $('#featured-itemslider-<?php e($uniq_id)?> .sliderwrap').data('flexslider');
                    if (flexSliderSize && flexslide_object != null)
                        flexslide_object.setVars({
                            'minItems': flexSliderSize,
                            'maxItems': flexSliderSize
                        });
                }, rand_s);
            });
        });
        
        cached_function.apply(this);
    };
    }());
</script>
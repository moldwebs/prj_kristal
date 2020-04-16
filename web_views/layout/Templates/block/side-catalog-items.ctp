<?php $uniq_id = uniqid()?>
<div id="special-products_block_center_container_<?php e($uniq_id)?>" class="special-products_block_center_container block s_countdown_block">
   <section id="special-products_block_center_<?php e($uniq_id)?>_column" class="special-products_block_center_column products_block section ">
      <h4 class="title_block"><span><?php e($title)?></span></h4>
      <div id="special-itemslider-<?php e($uniq_id)?>_column" class="special-itemslider_column flexslider">
         <div class="nav_top_right"></div>
         <?php e($body)?>
      </div>
   </section>
</div>

<script>
DocReadyFunction = (function() {
    var cached_function = DocReadyFunction;
    
    return function() {
        console.log('DocReadyFunction');
        
    var special_itemslider_options<?php e($uniq_id)?>_column;;
    jQuery(function($) {
        special_itemslider_options<?php e($uniq_id)?>_column = {
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
            controlsContainer: "#special-itemslider-<?php e($uniq_id)?>_column .nav_top_right",
            itemWidth: 280,
            minItems: getFlexSliderSize({
                'lg': 1,
                'md': 1,
                'sm': 1,
                'xs': 1,
                'xxs': 1
            }),
            maxItems: getFlexSliderSize({
                'lg': 1,
                'md': 1,
                'sm': 1,
                'xs': 1,
                'xxs': 1
            }),
            move: 0,
            prevText: '<i class="icon-left-open-3"></i>',
            nextText: '<i class="icon-right-open-3"></i>',
            productSlider: true,
            allowOneSlide: false
        };
        $('#special-itemslider-<?php e($uniq_id)?>_column .sliderwrap').flexslider(special_itemslider_options<?php e($uniq_id)?>_column);
        var special_flexslider_rs<?php e($uniq_id)?>_column;
        $(window).resize(function() {
            clearTimeout(special_flexslider_rs<?php e($uniq_id)?>_column);
            var rand_s = parseInt(Math.random() * 200 + 300);
            special_flexslider_rs<?php e($uniq_id)?>_column = setTimeout(function() {
                var flexSliderSize = getFlexSliderSize({
                    'lg': 1,
                    'md': 1,
                    'sm': 1,
                    'xs': 1,
                    'xxs': 1
                });
                var flexslide_object = $('#special-itemslider-<?php e($uniq_id)?>_column .sliderwrap').data('flexslider');
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
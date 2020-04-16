<?php if(!empty($block['data']['items'])):?>

<div id="sp_extra_slider_11_14780101446046255301"
   class="sp-extraslider buttom-type1 preset01-5 preset02-3 preset03-2 preset04-2
   button-type1">
   <!-- Begin extraslider-inner -->
   <div class="extraslider-inner product_list grid product_list_bases">
      <!-- Begin item -->
      <?php foreach($block['data']['items'] as $key => $item):?>
      <div class="item ">
         <div class="product-container">
            <div class="left-block">
               <div class="product-image-container">
                  <div class="product-image" >
                     <a href="<?php e($item['ObjItemTree']['url'])?>?fltr_eqorrel__extra_1=1" >
                     <img class="img_1" src="/getimages/180x180x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>" />
                     </a>
                  </div>
               </div>
            </div>
            <div class="right-block">
              <div class="label-box">
                 <span class="sale-box"><?php ___e('SALE')?></span>
              </div>
               <!--  Show Product title  -->
               <h5 class="product-name">
                  <a href="<?php e($item['ObjItemTree']['url'])?>?fltr_eqorrel__extra_1=1"  >
                  <?php eth($item['ObjItemTree']['title'], 500)?>
                  </a>
               </h5>
            </div>
            <!-- End item-wrap-inner -->
         </div>
         <!-- End item-wrap -->
      </div>
      <?php endforeach;?>

   </div>
   <!--End extraslider-inner -->
</div>
<script type="text/javascript">
   //<![CDATA[
   jQuery(document).ready(function ($) {
   ;(function (element) {
   var $element = $(element),
   $extraslider = $(".extraslider-inner", $element),
   _delay = 500,
   _duration = 800,
   _effect = "none";
   
   $extraslider.on("initialized.owl.carousel", function () {
   var $item_active = $(".owl-item.active", $element);
   if ($item_active.length > 1 && _effect != "none") {
   _getAnimate($item_active);
   }
   else {
   var $item = $(".owl-item", $element);
   
   $item.css({"opacity": 1, "filter": "alpha(opacity = 100)"});
   
   }
   
   
   $(".owl-controls", $element).insertBefore($extraslider);
   $(".owl-dots", $element).insertAfter($(".owl-prev", $element));
   
   });
   
   $extraslider.owlCarousel({
   margin: 20,
   slideBy: 1,
   autoplay: false,
   autoplay_hover_pause: false,
   autoplay_timeout: 1000,
   autoplaySpeed: 1000,
   smartSpeed: 1000,
   startPosition: 0,
   mouseDrag: false,
   touchDrag:false,
   pullDrag:false,
   autoWidth: false,
   responsive: {
   0: {items:1 },
   480: {items:2},
   768: {items:2},
   992: {items:3},
   1200: {items: 5}
   },
   dotClass: "owl-dot",
   dotsClass: "owl-dots",
   dots: false,
   dotsSpeed:500,
   nav: true,
   loop: false,
   navSpeed: 500,
   navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
   navClass: ["owl-prev", "owl-next"]
   
   });
   
   $extraslider.on("translate.owl.carousel", function (e) {
   
   var $item_active = $(".owl-item.active", $element);
   _UngetAnimate($item_active);
   _getAnimate($item_active);
   });
   
   $extraslider.on("translated.owl.carousel", function (e) {
   
   
   var $item_active = $(".owl-item.active", $element);
   var $item = $(".owl-item", $element);
   
   _UngetAnimate($item);
   
   if ($item_active.length > 1 && _effect != "none") {
   _getAnimate($item_active);
   } else {
   
   $item.css({"opacity": 1, "filter": "alpha(opacity = 100)"});
   
   }
   });
   
   function _getAnimate($el) {
   if (_effect == "none") return;
   //if ($.browser.msie && parseInt($.browser.version, 10) <= 9) return;
   $extraslider.removeClass("extra-animate");
   $el.each(function (i) {
   var $_el = $(this);
   $(this).css({
   "-webkit-animation": _effect + " " + _duration + "ms ease both",
   "-moz-animation": _effect + " " + _duration + "ms ease both",
   "-o-animation": _effect + " " + _duration + "ms ease both",
   "animation": _effect + " " + _duration + "ms ease both",
   "-webkit-animation-delay": +i * _delay + "ms",
   "-moz-animation-delay": +i * _delay + "ms",
   "-o-animation-delay": +i * _delay + "ms",
   "animation-delay": +i * _delay + "ms",
   "opacity": 1
   }).animate({
   opacity: 1
   });
   
   if (i == $el.size() - 1) {
   $extraslider.addClass("extra-animate");
   }
   });
   }
   
   function _UngetAnimate($el) {
   $el.each(function (i) {
   $(this).css({
   "animation": "",
   "-webkit-animation": "",
   "-moz-animation": "",
   "-o-animation": "",
   "opacity": 0
   });
   });
   }
   
   })("#sp_extra_slider_11_14780101446046255301");
   });
   //]]>
</script>

<?php endif;?>
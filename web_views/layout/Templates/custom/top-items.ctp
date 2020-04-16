<div class="moduletable  content_extra custom_extraslider4">
        <div class="box-title">
           <h3 class="title_block">
              <span>best</span> seller<span>.</span>
           </h3>
        </div>
        <div id="sp_extra_slider_11_1478010144604625530"
           class="sp-extraslider buttom-type1 preset01-5 preset02-3 preset03-2 preset04-2
           button-type1">
           <!-- Begin extraslider-inner -->
           <div class="extraslider-inner product_list grid" data-effect="none">
              <!-- Begin item -->
              <?php for($i=1;$i<=7;$i++):?>
              <div class="item ">
                 <div class="product-container" itemscope itemtype="http://schema.org/Product">
                    <div class="left-block">
                       <div class="product-image-container">
                          <div class="product-image" >
                             <a href="/sp_agood/en/electronics/2-blouse.html" title="Aliqa  loban mopun loren zasen lote">
                             <img class="img_1" src="/sp_agood/1-home_default/blouse.jpg" alt="Aliquam lobortis"/>
                             </a>
                             <div class="product-cart">
                                <a class=" cart_button ajax_add_to_cart_button" href="/sp_agood/en/cart?add=1&amp;id_product=2&amp;token=01986de43b72224f078ea5ba5865a1ea" rel="nofollow"  title="Add to cart" data-id-product="2">
                                <i class="fa fa-shopping-cart"></i>
                                </a>
                             </div>
                             <div class="button-container">
                                <a class="add_to_compare" href="/sp_agood/en/electronics/2-blouse.html" data-tooltip-remove="Remove from compare"  title="Add to compare" data-id-product="2">
                                <i class="fa fa-list-ul"></i>
                                </a>
                                <a class="addToWishlist wishlistProd_2" title="Add to wishlist" href="#"  onclick="WishlistCart('wishlist_block_list', 'add', '2', false, 1); return false;">
                                <i class="fa fa-heart"></i>
                                </a>
                                <a class="quick-view" href="/sp_agood/en/electronics/2-blouse.html" title="Quick view" data-rel="/sp_agood/en/electronics/2-blouse.html">
                                <i class="fa fa-search"></i>
                                </a>
                             </div>
                          </div>
                          <div class="label-box">
                             <span class="new-box">New</span>
                          </div>
                       </div>
                    </div>
                    <div class="right-block">
                       <div class="product-top">
                          <!--Product Prices-->
                          <div itemprop="offers" itemscope
                             itemtype="http://schema.org/Offer"
                             class="price-box">
                             <span itemprop="price" class="price product-price">
                             $27.00														</span>
                             <meta itemprop="priceCurrency"
                                content="USD"/>
                          </div>
                          <!--  Show average rating stars  -->
                          <div class="comments_note" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                             <div class="star_content">
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <meta itemprop="worstRating" content = "0" />
                                <meta itemprop="ratingValue" content = "0" />
                                <meta itemprop="bestRating" content = "5" />
                             </div>
                             <span class="nb-comments"><span itemprop="reviewCount">0</span> Review(s)</span>
                          </div>
                       </div>
                       <!--  Show Product title  -->
                       <h5 itemprop="name" class="product-name">
                          <a href="/sp_agood/en/electronics/2-blouse.html" 
                             title="Aliqa  loban mopun loren zasen lote"  >
                          Aliqa  loban mopun loren zasen lote
                          </a>
                       </h5>
                    </div>
                    <!-- End item-wrap-inner -->
                 </div>
                 <!-- End item-wrap -->
              </div>
              <?php endfor;?>

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
           
           })("#sp_extra_slider_11_1478010144604625530");
           });
           //]]>
        </script>
     </div>
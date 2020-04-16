<?php if(!empty($block['data'])):?>
<div>&nbsp;</div>
<div class="moduletable  deals2 ">
   <div class="spcountdownproductslider-heading clearfix">
      <div class="spcountdownproductslider-top">

        <h3 class="title_block">
            <?php e($block['block']['title'])?>
        </h3>
        
        
         <div class="spcountdownproductslider-time">
            <div class="item-timer product_time_2"></div>
            <span class="time-label"></span>
            <script type="text/javascript">
               //<![CDATA[
               listcountdownproduct.push("product_time_2|<?php e(date("Y/m/d", strtotime("+1 day")))?> 00:00:00");
               //]]>
            </script>
         </div>
      </div>
   </div>
   <div id="sp_countdownproduct_2_14791444281552601534" class="sp-countdownproductslider sp-preload" >
      <div id="spcountdownproductslider-slider-2" class="spcountdownproductslider-slider product_list grid">
         <?php foreach($block['data'] as $item):?>
         <div class="product-container">
            <div class="left-block">
               <div class="product-image-container">
                  <div class="product-image">
                     <div class="item-img item-height">
                        <div class="item-img-info ">
                           <a class="product_img_link" href="<?php eurl($item['ObjItemList']['alias'])?>"  >
                            <img src="/getimages/180x180x0/thumb/<?php e($item['ObjOptAttachDef']['attach'])?>" alt="<?php eth($item['ObjItemList']['title'], 500)?>"/>
                           </a>
                        </div>
                     </div>
                  </div>
                  <?php //e($this->telement('quick-view', array('item' => $item)))?>
                    <div class="label-box">
                        <span class="sale-box">
                            - <?php e(round(($item['Price']['old']-$item['Price']['value'])/$item['Price']['old'] * 100))?> %
                        </span>
                    </div>
               </div>
            </div>
            <div style="min-height: 110px;" class="right-block">
               <div class="product-top">
                  <!--Product Prices-->
                      <div class="price-box">
                            <?php if($item['Price']['old'] > 0):?><span class="old-price product-price"><?php e($item['Price']['html_old'])?> <?php e($item['Price']['html_currency'])?></span><?php endif;?>
                            <span class="price product-price"><?php e($item['Price']['html_value'])?> <?php e($item['Price']['html_currency'])?></span>
                      </div>
                  <!--  Show average rating stars  -->
                  
               </div>
               <!--  Show Product title  -->
               <h5 class="product-name">
                  <a href="<?php eurl($item['ObjItemList']['alias'])?>" target="_blank">
                  <?php eth($item['ObjItemList']['title'], 500)?>
                  </a>
               </h5>
               <!--   Show category description   -->
            </div>
            <div style="clear: both;"></div>
         </div>
        <?php endforeach;?>
         
      </div>
   </div>
   <script type="text/javascript">
      jQuery(document).ready(function ($) {
      	;(function (element) {
      		var $element = $(element),
      			$countdownproductslider = $("#spcountdownproductslider-slider-2", $element),
      			_delay = '500',
      			_duration = '800',
      			_effect = 'flip';	
      
      			$countdownproductslider.on("initialized.owl.carousel", function () {
      				var $item_active = $(".spcountdownproductslider-item.active", $element);
      				if ($item_active.length > 1 && _effect != "none") {
      					_getAnimate($item_active);
      				}
      				else {
      					var $item = $(".spcountdownproductslider-item", $element);
      					$item.css("opacity", "1");
      					$item.css("filter", "alpha(opacity = 100)");
      				}
      			});
      
      			$countdownproductslider.owlCarousel({
      				autoplay: false,
      				autoplayTimeout: 2000,
      				autoplaySpeed: 500,
      				smartSpeed: 500,
      				autoplayHoverPause: false,
      				startPosition: 0,
      				mouseDrag: true,
      				touchDrag: true,
      				pullDrag: true,
      				dots: 1,
      				autoWidth: false,
      				dotClass: "spcountdownproductslider-dot",
      				dotsClass: "spcountdownproductslider-dots",
      				themeClass: 'spcountdownproductslider-theme',
      				baseClass: 'spcountdownproductslider-carousel',
      				itemClass: 'spcountdownproductslider-item',
      				nav: true,
      				loop: false,
      				navText: ["Next", "Prev"],
      				navClass: ["owl-prev", "owl-next"],
      				responsive:{
      					0:{
      					  items:1 // In this configuration 1 is enabled from 0px up to 479px screen size 
      					},
      					480:{
      					  items:2 // In this configuration 1 is enabled from 0px up to 767px screen size 
      					},	
      					768:{
      					  items:2 // In this configuration 1 is enabled from 0px up to 1199px screen size 
      					},	
      					1200:{
      					  items:1 // In this configuration 1 is enabled from 0px up to 1200px screen size 
      					},												
      				}
      			});
      
      			$countdownproductslider.on("translate.owl.carousel", function (e) {
      				var $item_active = $(".spcountdownproductslider-item.active", $element);
      				_UngetAnimate($item_active);
      				_getAnimate($item_active);
      			});
      
      			$countdownproductslider.on("translated.owl.carousel", function (e) {
      				var $item_active = $(".spcountdownproductslider-item.active", $element);
      				var $item = $(".spcountdownproductslider-item", $element);
      				
      				_UngetAnimate($item);
      
      				if ($item_active.length > 1 && _effect != "none") {
      					_getAnimate($item_active);
      				} else {
      					$item.css("opacity", "1");
      					$item.css("filter", "alpha(opacity = 100)");
      				}
      			});
      
      			function _getAnimate($el) {
      				if (_effect == "none") return;
      				//if ($.browser.msie && parseInt($.browser.version, 10) <= 9) return;
      				$countdownproductslider.removeClass("extra-animate");
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
      						$countdownproductslider.addClass("extra-animate");
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
      			
      		   var _timer = 0;
      			$(window).load(function () {
      				if (_timer) clearTimeout(_timer);
      				_timer = setTimeout(function () {
      					$(".sp-loading", $element).remove();
      					$element.removeClass("sp-preload");
      				}, 1000);
      			});
      
      			data = new Date(2013, 10, 26, 12, 00, 00);
      			function CountDown(date, id) {
      				dateNow = new Date();
      				amount = date.getTime() - dateNow.getTime();
      				if (amount < 0 && $("#" + id).length) {
      					$("." + id).html("Now!");
      				} else {
      					days = 0;
      					hours = 0;
      					mins = 0;
      					secs = 0;
      					out = "";
      					amount = Math.floor(amount / 1000);
      					days = Math.floor(amount / 86400);
      					amount = amount % 86400;
      					hours = Math.floor(amount / 3600);
      					amount = amount % 3600;
      					mins = Math.floor(amount / 60);
      					amount = amount % 60;
      					secs = Math.floor(amount);
      					if (days != 0) {
      						out += "<div class='time-item time-day'>" + "<div class='num-time'>" + days + "</div>" + "<div class='name-time'>" + ((days == 1) ? "" : "") + "</div>" + "</div> ";
      					}
      					if (hours != 0) {
      						out += "<div class='time-item time-hour'>" + "<div class='num-time'>" + hours + "</div>" + "<div class='name-time'>" + ((hours == 1) ? "" : "") + "</div>" + "</div>";
      					}
      					out += "<div class='time-item time-min'>" + "<div class='num-time'>" + mins + "</div>" + "<div class='name-time'>" + ((mins == 1) ? "" : "") + "</div>" + "</div>";
      					out += "<div class='time-item time-sec'>" + "<div class='num-time'>" + secs + "</div>" + "<div class='name-time'>" + ((secs == 1) ? "" : "") + "</div>" + "</div>";
      					out = out.substr(0, out.length - 2);
      					$("." + id).html(out);
      
      					setTimeout(function () {
      						CountDown(date, id);
      					}, 1000);
      				}
      			}
      
      			if (listcountdownproduct.length > 0) {
      				for (var i = 0; i < listcountdownproduct.length; i++) {
      					var arr = listcountdownproduct[i].split("|");
      					if (arr[1].length) {
      						var data = new Date(arr[1]);
      						CountDown(data, arr[0]);
      					}
      				}
      			}					
      
      		})("#sp_countdownproduct_2_14791444281552601534");
      	});					
                
   </script>
</div>
<?php endif;?>
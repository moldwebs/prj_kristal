<div class="moduletable  our_brands">
 <div id="sp_manu_slider_1_14780101441251039431" class="sp-manu-slider sp-preload"
    style="">
    <div class="sp-loading"></div>
    <div class="slider not-js cols-6 preset01-9 preset02-6 preset03-3 preset04-1">
       <div class="vpo-wrap">
          <div class="vp">
             <div class="vpi-wrap tt-effect-slide tt-effect-delay">
                <?php for($i=1;$i<=15;$i++):?>
                <div class="item">
                   <div class="item-wrap">
                      <div class="item-img item-height">
                         <div class="item-img-info">
                            <a href="/sp_agood/en/1_adidas" 
                               title="Adidas">
                            <img src="/sp_agood/img/m/1.jpg"
                               class="logo_manufacturer"
                               title="Adidas"
                               alt="Adidas"/>
                            </a>
                         </div>
                      </div>
                   </div>
                </div>
                <?php endfor;?>
             </div>
          </div>
       </div>
    </div>
    <div class="page-button middle style1">
       <ul class="control-button">
          <li class="preview">Prev</li>
          <li class="next">Next</li>
       </ul>
    </div>
 </div>
 <script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready(function ($) {
        ;
        (function (element) {
            var $el = $(element);
            var _timer = 0;
            $(window).load(function () {
                if (_timer) clearTimeout(_timer);
                _timer = setTimeout(function () {
                    runSlider();
                    $el.removeClass("sp-preload");
                    $(".sp-loading", $el).remove();
                }, 1000);
            });
    
            function runSlider() {
                $(".slider", $el).responsiver({
                    interval: 0,
                    speed: 800,
                    start: 0,
                    step: 1,
                    circular: true,
                    preload: true,
                    //fx: 'fade",
                    pause: "hover",
                    control: {
                        prev: "#sp_manu_slider_1_14780101441251039431 .control-button .preview",
                        next: "#sp_manu_slider_1_14780101441251039431 .control-button .next"
                    },
                    getColumns: function (_element) {
                        var match = $(_element).attr("class").match(/cols-(\d+)/);
                        if (match[1]) {
                            var column = parseInt(match[1]);
                        } else {
                            var column = 1;
                        }
                        if (!column) column = 1;
                        return column;
                    }
                });
    
            }
    
                                            $(".slider", $el).touchSwipeLeft(function () {
                            $(".slider", $el).responsiver("next");
                        }
                );
                $(".slider", $el).touchSwipeRight(function () {
                            $(".slider", $el).responsiver("prev");
                        }
                );
            
        })("#sp_manu_slider_1_14780101441251039431")
    
    });
    //]]>
 </script>
</div>
<?php if(!empty($block['data'])):?>
   <div class="wa-products-main padT20"> 
      <div class="container">
            <div class="row">
               <!--//==Section Heading Start==//-->
               <div class="col-md-12">
                  <div class="centered-title">
                        <h2><?php e($block['block']['title'])?> <span class="heading-border"></span></h2>
                        <div class="clear"></div>
                        <em><?php e($block['block']['desc'])?></em>
                  </div>
               </div>
               <!--//==Section Heading End==//-->
            </div>
            <div class="row">
               <div id="best-seller-<?php echo $block['block']['code']?>" class="owl-carousel owl-theme carousel-style-1">
                  <!--//==product Item==//-->
                  <?php foreach($block['data'] as $item):?>
                  <div class="col-md-12" >
                        <div class="wa-products">
                           <div class="wa-products-thumbnail wa-item">
                              <img src="/getimages/510x600x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>" alt="<?php eth($item['ObjItemList']['title'], 500)?>">
                              <?php e($this->telement('quick-view', array('item' => $item)))?>
                           </div>
                           <div class="wa-products-caption">
                              <h2><a href="<?php eurl($item['ObjItemList']['alias'])?>"><?php eth($item['ObjItemList']['title'], 100)?></a></h2>
                              <div class="clear"></div>
                              <?php e($this->telement('quick-stars', array('item' => $item)))?>
                              <span class="price">
                                 <?php if($item['Price']['old'] > 0):?><del><?php e($item['Price']['html_old'])?> <?php e($item['Price']['html_currency'])?></del><?php endif;?>
                                 <?php e($item['Price']['html_value'])?> <?php e($item['Price']['html_currency'])?>
                              </span>
                           </div>
                        </div>
                  </div>
                  <?php endforeach;?>
               </div>
               <div class="clear"></div>
            </div>
      </div>
   </div>
   <div class="hidden-lg">
   <div>&nbsp;</div>
   <div>&nbsp;</div>
   </div>

<script>
   document.addEventListener("DOMContentLoaded", function(event) { 
      $('#best-seller-<?php echo $block['block']['code']?>').owlCarousel({
         autoPlay: false,
         items: 4,
         navigation: true,
         pagination: false,
         itemsDesktop: [1199, 4],
         itemsDesktopSmall: [979, 3]

      });
   });
</script>
<?php endif;?>
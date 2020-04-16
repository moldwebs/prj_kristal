<div class="prod-info-section">
   <div class="clearfix">
         <!--Thumbnail Column-->
         <div class="carousel-column col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <?php if(!empty($_GET['modal'])):?>
               <div class="wa-product-main-image">
                  <img src="/getimages/510x600x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>" alt="<?php eth($item['ObjItemList']['title'], 500)?>">
               </div>
            <?php else:?>
               <div class="wa-product-main-image marB20">
                  <a href="/getimages/0x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>" class="fancybox" data-fancybox-group="group" title="<?php eth($item['ObjItemList']['title'])?>"> <img src="/getimages/510x600x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>" alt="<?php eth($item['ObjItemList']['title'])?>"> </a>
               </div>
               <?php if(!empty($item['ObjOptAttachs']['images'])):?>
               <div id="wa-slide-image" class="owl-carousel  wa-slide-image carousel-style-1">
                  <?php foreach($item['ObjOptAttachs']['images'] as $key => $attach):?>
                     <a href="/getimages/0x0/large/<?php e($attach['attach'])?>" class="fancybox" data-fancybox-group="group" title="<?php eth($item['ObjItemList']['title'])?>"> <img src="/getimages/129x125x0/thumb/<?php e($attach['attach'])?>" alt="<?php eth($item['ObjItemList']['title'])?>"> </a>  
                  <?php endforeach;?>
               </div>
               <?php endif;?>
            <?php endif;?>
         </div>
         <!--Content Column-->
         <div class="content-column col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="outer padT30">
               <div class="title-box">
                     <div class="inner marB30">
                        <a href="<?php eurl($item['ObjItemList']['alias'])?>"><h2 class="marB10"><?php e($item['ObjItemList']['title'])?></h2></a>

                        <p class="availability in-stock"><strong><?php ___e('Availability')?>:</strong> <span><?php ___e($item['ObjItemList']['qnt'] > 0 ? 'In stock' : 'Out stock')?></span></p>

                        <p class="availability in-stock"><strong><?php ___e('Code')?>:</strong> <span><?php e($item['ObjItemList']['code'])?></span></p>
                        <p class="availability in-stock"><strong><?php ___e('Brand')?>:</strong> <span><a href="<?php eurl($item['ObjItemTree']['alias'])?>?fltr_eq__extra_2[]=<?php e($item['ObjItemList']['extra_2'])?>"><?php e($manufacturers[$item['ObjItemList']['extra_2']])?></a></span></p>
                        <?php if(!empty($item['ObjItemList']['data']['wrnt'])):?>
                        <p class="availability in-stock"><strong><?php ___e('Warranty')?>:</strong> <span><?php e($item['ObjItemList']['data']['wrnt'])?> <?php ___e('month(s)')?></span></p>
                        <?php endif;?>

                        <?php e($this->telement('quick-stars', array('item' => $item)))?>
                        <span class="price marB10">								
                        <?php e($item['Price']['html_value'])?> <?php e($item['Price']['html_currency'])?>
                        <?php if($item['Price']['old'] > 0):?><del><?php e($item['Price']['html_old'])?> <?php e($item['Price']['html_currency'])?></del><?php endif;?>														
                        <span class="clear"></span>
                        </span>
                        <p><?php e($item['ObjItemList']['short_body'])?></p>
                     </div>
                     <div class="clear"></div>
               </div>
               <!--Add-->
               <div class="add-options">
                  <button onclick="window.location='/shop/basket/add/<?php e($item['ObjItemList']['id'])?>/0/1'" type="button" class="theme-button"><?php ___e('Order Now')?></button>
                  <!--<button onclick="mxwin('/shop/checkout/fast/<?php e($item['ObjItemList']['id'])?>'); return false;" type="button" class="theme-button"><?php ___e('Fast Order')?></button>-->
                  <button onclick="geteval('/users/collections/catalog_wish/<?php e($item['ObjItemList']['id'])?>'); return false;" type="button" class="theme-button"><span class="fa fa-heart"></span></button>
               </div>

               <div class="clear"></div>
               <div class="clear">&nbsp;</div>
               <div class="clear">&nbsp;</div>
               <div class="clear">&nbsp;</div>
               <?php echo $tpl->block('share-product');?>

            </div>
         </div>
   </div>
</div>
<?php if(!empty($block['data']['items'])):?>
<div class="bloc bloc_videoreviews bloc_borderBottom bloc_paddingSTop bloc_shadow_none" >
  <div class="container hidden-sm hidden-xs">
     <div class="row">
        <div class="col-xs-12 text-center">
           <h2><?php eth($block['data']['promo']['ObjItemTree']['title'], 500)?></h2>
        </div>
     </div>
     <div class="carousel slide" id="CarouselId8982" data-interval="false" data-pause="hover">
        <div class="carousel-inner">
           <?php foreach($block['data']['items'] as $key => $item):?>
           <div class="item <?php if($key < 1) e('active')?> item_<?php e($key)?>">
              <div class="row">
                 <div class="col-xs-12">
                    <div class="videoreviewBox">
                        <iframe width="800" height="450" src="https://www.youtube.com/embed/<?php e($item['ObjItemTree']['url'])?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                 </div>
              </div>
              <div class="row text-center">
                 <div class="col-xs-12">
                    <h3 class="videoreviewQuote"><?php eth($item['ObjItemTree']['title'], 500)?></h3>
                 </div>
              </div>
              <div class="row text-center">
                 <div class="col-xs-12">
                    <div class="videoreviewAuthor"><?php eth($item['ObjItemTree']['body'], 500)?></div>
                 </div>
              </div>
           </div>
           <?php endforeach;?>
        </div>
        <button class="left carousel-control" href="#CarouselId8982" data-slide="prev"><span class="videoreviewsIcon videoreviewsIcon_Prev"></span></button><button class="right carousel-control" href="#CarouselId8982" data-slide="next"><span class="videoreviewsIcon videoreviewsIcon_Next"></span></button>
     </div>

  </div>
</div>

<?php endif;?>
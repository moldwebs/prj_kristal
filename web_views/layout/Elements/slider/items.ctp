<?php if(!empty($block['data']['items'])):?>
   <div id="home1-main-slider" class="owl-carousel owl-theme">
      <?php foreach($block['data']['items'] as $key => $item):?>
         <div class="item">
            <figure>
               <img src="/getimages/1920x446x1/large/<?php e($item['ObjOptAttachDef']['attach'])?>" class="hidden-xs hidden-sm " alt=""/>
               <img src="/getimages/1920x1160x1/large/<?php e($item['ObjOptAttachDef']['attach'])?>" class="hidden-lg hidden-md" alt=""/>
               <figcaption>
                     <div class="container">
                     <?php e($item['ObjItemTree']['body'])?>
                     </div>
               </figcaption>
            </figure>
         </div>
      <?php endforeach;?>
   </div>
<?php endif;?>

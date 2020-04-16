<div>&nbsp;</div>
<div>&nbsp;</div>
<div id="easycontent_container_37" class="easycontent_container full_container block">
   <div class="container">
      <div class="row">
         <div class="col-xs-12">
            <aside id="easycontent_37" class="easycontent_37 easycontent section">
               <div class=" block_content">
                  <div class="row">
                     <?php foreach($block['data']['items'] as $item):?>
                     <div class="col-xs-12 col-sm-3 easycontent_s10 text-center">
                        <div class="easy_icon"><em class="<?php e($item['ObjItemTree']['code'])?> icon-2x"><span class="unvisible">&nbsp;</span></em></div>
                        <h4 class="color_444"><?php e($item['ObjItemTree']['title'])?></h4>
                        <div class="color_999 center_width_90"><?php e($item['ObjItemTree']['body'])?></div>
                     </div>
                     <?php endforeach;?>
                  </div>
               </div>
            </aside>
         </div>
      </div>
   </div>
</div>
<?php if(!empty($block['data']['items'])):?>
<div id="st_advanced_banner_66" class="st_advanced_banner_row st_advanced_banner_0 block hover_effect_2 hidden-xs hidden-sm">
   <div class="row">
      <a href="<?php e($block['data']['items'][0]['ObjItemTree']['url'])?>">
      <div id="advanced_banner_box_67" class="col-sm-6 advanced_banner_col " data-height="100" >
         <div id="st_advanced_banner_block_52" class="st_advanced_banner_block_52 st_advanced_banner_block" style="height:304px;">
            <div class="st_advanced_banner_image" style="background-image:url(/getimages/0x0/large/<?php e($block['data']['items'][0]['ObjOptAttachDef']['attach'])?>);"></div>
            <div class="advanced_banner_text text_table_wrap ">
               <div class="text_table">
                  <div class="text_td style_content text-center advanced_banner_text_center clearfix">
                     <h4 style="font-family:Roboto;"><?php e($block['data']['items'][0]['ObjItemTree']['body'])?></h4>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </a>
      <a href="<?php e($block['data']['items'][1]['ObjItemTree']['url'])?>">
      <div id="advanced_banner_box_68" class="col-sm-6 advanced_banner_col " data-height="100" >
         <div id="st_advanced_banner_block_53" class="st_advanced_banner_block_53 st_advanced_banner_block" style="height:304px;">
            <div class="st_advanced_banner_image" style="background-image:url(/getimages/0x0/large/<?php e($block['data']['items'][1]['ObjOptAttachDef']['attach'])?>);"></div>
            <div class="advanced_banner_text text_table_wrap ">
               <div class="text_table">
                  <div class="text_td style_content text-center advanced_banner_text_center clearfix">
                     <h4 style="font-family:Roboto;"><?php e($block['data']['items'][1]['ObjItemTree']['body'])?></h4>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </a>
   </div>
</div>
<?php endif;?>
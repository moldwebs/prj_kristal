<?php if(!empty($block['data']['items'])):?>
<div class="sp_customhtml_27_1478010144309578446 banner-layout-4 spcustom_html">
<div>
   <div class="custom-banner-04">
      <div class="custom-row">
         <?php foreach($block['data']['items'] as $key => $item):?>
         <div class="images style-0<?php e($key+1)?>"><a href="<?php eurl($item['ObjItemTree']['url'])?>"><img src="/getimages/0x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>" /></a></div>
         <?php endforeach;?>
      </div>
   </div>
</div>
</div>
<?php endif;?>
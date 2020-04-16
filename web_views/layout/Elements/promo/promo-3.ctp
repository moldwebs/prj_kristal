<?php if(!empty($block['data']['items'])):?>
<div class="bloc bloc_clientage hidden-xs bloc_paddingMBottom bloc_shadow_none" >
  <div class="container">
     <div class="row text-center">
        <h2><?php eth($block['data']['promo']['ObjItemTree']['title'], 500)?></h2>
     </div>
     <div class="row">
        <div class="col col-lg-12">
           <ul id="clientageId10" class="jcarousel-skin-clientage">
            <?php foreach($block['data']['items'] as $key => $item):?>
                <li class="clientagePhoto"><span class="clientagePhotoGreyscale" style="background-image:url(/getimages/0x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>);"><span class="clientagePhotoColor" style="background-image:url(/getimages/0x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>);"></span></span></li>
            <?php endforeach;?>
           </ul>
        </div>
     </div>
  </div>
</div>
<?php endif;?>
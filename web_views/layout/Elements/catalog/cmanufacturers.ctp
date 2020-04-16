<?php if(empty($block['data'])) $block['data'] = $tpl->get('/catalog/manufacturer/get_clist')?>
<?php if(!empty($block['data'])):?>
 <div class="sliderwrap products_slider">
    <ul class="slides">
       <?php foreach($block['data'] as $item):?>
       <li class="ajax_block_product first_item">
          <div class="pro_outer_box">
             <div class="pro_first_box ">
                <a href="<?php eurl('/catalog?fltr_eq__extra_2=' . $item['ObjItemList']['id'])?>" title="<?php eth($item['ObjItemList']['title'], 500)?>" class="product_image">
                    <img src="/getimages/140x50x0/large/<?php e($item['ObjOptAttachDef']['attach'])?>" width="140" height="50" alt="<?php eth($item['ObjItemList']['title'], 500)?>" title="<?php eth($item['ObjItemList']['title'], 500)?>" class="replace-2x img-responsive" />
                    <?php e($this->telement('quick-type', array('item' => $item)))?>
                </a>
             </div>
          </div>
       </li>
       <?php endforeach;?>
    </ul>
 </div>
<?php endif;?>
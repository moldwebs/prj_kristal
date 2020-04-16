<?php if(!empty($menu['links'])):?>
<ul class="mo_advanced_mu_level_0">
   <?php foreach($menu['links'] as $key => $val):?>
       <?php if(!empty($val['child'])):?>
       <li class="mo_advanced_ml_level_0 mo_advanced_ml_column">
          <a  href="<?php e($val['data']['url'])?>" class="mo_advanced_ma_level_0" title="<?php e($val['title'])?>"><?php e($val['title'])?></a> <span class="opener">&nbsp;</span>
          <ul class="mo_advanced_mu_level_1 mo_advanced_sub_ul">
             <?php foreach($val['child'] as $_key => $_val):?>
             <?php if(!empty($_val['child'])):?>
             <li class="mo_advanced_ml_level_1 mo_advanced_sub_li">
                <a href="<?php e($_val['data']['url'])?>" title="<?php e($_val['title'])?>" class="mo_advanced_ma_level_1 mo_advanced_sub_a"><?php e($_val['title'])?></a> <span class="opener">&nbsp;</span>
                <ul class="mo_advanced_sub_ul mo_advanced_mu_level_2 p_granditem_1">
                   <?php foreach($_val['child'] as $__key => $__val):?>
                   <li class="mo_advanced_sub_li mo_advanced_ml_level_2 granditem_0 p_granditem_1"> <a href="<?php e($__val['data']['url'])?>" title="<?php e($__val['title'])?>" class="mo_advanced_sub_a mo_advanced_ma_level_2 advanced_ma_item "><?php e($__val['title'])?></a></li>
                   <?php if(!empty($__val['child'])):?>
                   <?php foreach($__val['child'] as $___key => $___val):?>
                   <li class="mo_advanced_sub_li mo_advanced_ml_level_2 granditem_0 p_granditem_1"> <a href="<?php e($___val['data']['url'])?>" title="<?php e($___val['title'])?>" class="mo_advanced_sub_a mo_advanced_ma_level_2 advanced_ma_item "><?php e($___val['title'])?></a></li>
                   <?php endforeach;?>
                   <?php endif;?>
                   <?php endforeach;?>
                </ul>
             </li>
             <?php else:?>
             <li class="mo_advanced_ml_level_1 mo_advanced_sub_li">
                <a href="<?php e($_val['data']['url'])?>" title="<?php e($_val['title'])?>" class="mo_advanced_ma_level_1 mo_advanced_sub_a"><?php e($_val['title'])?></a> <span class="opener">&nbsp;</span>
             </li>
             <?php endif;?>
             <?php endforeach;?>
          </ul>
       </li>
        <?php else:?>
       <li class="mo_advanced_ml_level_0 mo_advanced_ml_column"> <a  href="<?php e($val['data']['url'])?>" class="mo_advanced_ma_level_0" title="<?php e($val['title'])?>"><?php e($val['title'])?></a></li>
        <?php endif;?>
   <?php endforeach;?>
</ul>
<?php endif;?>
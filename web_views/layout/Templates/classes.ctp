<?php 
$types = array();
if(!empty($item['ObjItemList']['extra_1'])) $types[] = $item['ObjItemList']['extra_1'];
if(!empty($item['Relation']['extra_1'])) $types = $types + $item['Relation']['extra_1'];
?>
<?php if(!empty($types)):?>
<div class="group-price">
    <?php foreach($types as $type):?>
        <span class="<?php e($fill_types[$type]['ObjOptType']['data']['class'])?>"><?php e($fill_types[$type]['ObjOptType']['title'])?></span>
    <?php endforeach;?>
</div>
<?php endif;?>
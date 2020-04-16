<?php 
$types = array();
foreach($fill_types as $fill_type_id => $fill_type){
    if((!empty($fill_type['ObjOptType']['data']['class']) || !empty($fill_type['ObjOptAttachDef']['attach'])) && (in_array($fill_type_id, $item['Relation']['extra_1']) || $item['ObjItemList']['extra_1'] == $fill_type_id)) $types[] = $fill_type_id;
}
$top = 5;
$left = 5;
?>
<?php if(!empty($types)):?>
    <?php foreach($types as $type):?>
        <?php if(!empty($fill_types[$type]['ObjOptType']['data']['class'])):?>
            <span class="<?php e($fill_types[$type]['ObjOptType']['data']['class'])?>"><i><?php e($fill_types[$type]['ObjOptType']['title'])?></i></span>
        <?php else:?>
            <img style="position: absolute; top: <?php e($top)?>px; left: <?php e($left)?>px;" src="/getimages/0x0/thumb/<?php e($fill_types[$type]['ObjOptAttachDef']['attach'])?>" />
            <?php //$top += 32;?>        
            <?php $left += 85;?>        
        <?php endif;?>
    <?php endforeach;?>
<?php endif;?>
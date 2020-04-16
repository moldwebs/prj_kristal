<?php 
$types = array();
foreach($fill_types as $fill_type_id => $fill_type){
    if((!empty($fill_type['ObjOptType']['data']['class']) || !empty($fill_type['ObjOptAttachDef']['attach']))&& (in_array($fill_type_id, $item['Relation']['extra_1']) || $item['ObjItemList']['extra_1'] == $fill_type_id)) $types[] = $fill_type_id;
}
$top = 0;
?>
<?php if(!empty($types)):?>
    <?php foreach($types as $type):?>
        <?php if(!empty($fill_types[$type]['ObjOptType']['data']['class'])):?>
            <span class="<?php e($fill_types[$type]['ObjOptType']['data']['class'])?>"><?php e($fill_types[$type]['ObjOptType']['title'])?></span>
        <?php else:?>
            <img style="position: absolute; right: 17px; top: <?php e($top)?>px;" src="/getimages/0x0/thumb/<?php e($fill_types[$type]['ObjOptAttachDef']['attach'])?>" />
            <?php $top += 32;?>        
        <?php endif;?>
    <?php endforeach;?>
<?php endif;?>

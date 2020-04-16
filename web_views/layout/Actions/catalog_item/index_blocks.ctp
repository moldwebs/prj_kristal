<div id="center_column" class="column col-sm-12 col-md-12 col-lg-12 col-xs-12">

<div><h1 class="page-heading"><?php e($title_for_action)?></h1></div>

 <?php if(!empty($description_for_action)):?>
    <div><?php e($description_for_action)?></div>
 <?php endif;?>

<div>
    <?php $data = $tpl->get("/catalog/item/get_list_groups/{$base_id}/mod_limit:6/mod_extra_2:{$_GET['fltr_eq__extra_2']}/mod_type:{$_GET['fltr_eqorrel__extra_1']}/");?>
    
    <?php foreach($data as $_data):?>
        <?php if(empty($_data['items'])) continue;?>
        <div class="moduletable  featured-products " id="base_<?php echo $_data['ObjItemTree']['id']?>">
            <div class="box-title">
            <h3 class="title_block"><a href="<?php eurl($_data['ObjItemTree']['alias'])?><?php if(!empty($_GET)) e('?'.http_build_query($_GET))?>"><?php echo $_data['ObjItemTree']['title']?></a></h3>
            </div>
            <?php echo $tpl->belement('catalog/items', $_data['items']);?>
        </div>
    <?php endforeach;?>
</div>

</div>
<?php $ws_model = 'ObjItemList'?>
<?php
    $_items = array();
    foreach($items as $item){
        if(!empty($item['Combinations'])){
            foreach($item['Combinations'] as $_item){
                $_items[] = $_item;
            }
        } else {
            $_items[] = $item;
        }
    }
?>
<table>
<?php foreach($_items as $item):?>
    <tr>
        <td style="width: 30px;"><?php echo $this->element('/admin/item_thumb', array('item' => $item, 'ws_model' => $ws_model))?></td>
        <td><a target="_blank" href="<?php eurl($item[$ws_model]['alias'])?>"><?php et($item[$ws_model]['title'], 100)?></a></td>
        <td style="width: 100px;"><?php et($item['ObjItemTree']['title'], 17)?></td>
        <td style="width: 100px; text-align: right;"><?php e($item[$ws_model]['price'])?></td>
        <td style="width: 30px;"><?php if(!empty($item[$ws_model]['price'])) e($currencies[$item[$ws_model]['currency']])?></td>
        <td style="width: 20px;">
            <?php if(empty($related)):?>
                <a onclick="if($('#rel_prods_present_similar').find('#Relation_product_id_similar_<?php e($item[$ws_model]['id'])?>').length == 0) {$(this).next().attr('name', 'data[Relation][product_id_similar][]');$(this).removeClass('ico_add').addClass('ico_remove').attr('onclick', '$(this).parent().parent().remove();');$(this).parent().parent().prependTo('#rel_prods_present_similar > table');}" class="ico ico_add"></a>
                <input type="hidden" name="" value="<?php e($item[$ws_model]['id'])?>" />
            <?php else:?>
                <a onclick="$(this).parent().parent().remove();" class="ico ico_remove"></a>
                <input type="hidden" id="Relation_product_id_similar_<?php e($item[$ws_model]['id'])?>" name="data[Relation][product_id_similar][]" value="<?php e($item[$ws_model]['id'])?>" />
            <?php endif;?>
        </td>
    </tr>
<?php endforeach;?>
</table>
<input type="hidden" name="data[RelationRemove][product_id_similar]" value="1" />
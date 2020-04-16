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
        <td style="width: 200px; text-align: center;">
            <span <?php if(empty($related)):?>style="display: none;"<?php endif;?>>
                <input <?php if(empty($related)):?>disabled="disabled"<?php endif;?> onclick="select()" style="width: 70px;" type="number" max="100" id="RelationValue_product_id_<?php e($item[$ws_model]['id'])?>" name="data[RelationValue][product_id][<?php e($item[$ws_model]['id'])?>]" value="<?php e(!empty($relation_values[$item[$ws_model]['id']]) ? $relation_values[$item[$ws_model]['id']] : '0')?>" placeholder="<?php ___e('discount')?>" /> %
            </span>
        </td>
        <td style="width: 20px;">
            <?php if(empty($related)):?>
                <a onclick="if($('#rel_prods_present').find('#RelationValue_product_id_<?php e($item[$ws_model]['id'])?>').length == 0) {$(this).parent().parent().find('#RelationValue_product_id_<?php e($item[$ws_model]['id'])?>').removeAttr('disabled').parent().show();$(this).removeClass('ico_add').addClass('ico_remove').attr('onclick', '$(this).parent().parent().remove();');$(this).parent().parent().prependTo('#rel_prods_present > table');}" class="ico ico_add"></a>
            <?php else:?>
                <a onclick="$(this).parent().parent().remove();" class="ico ico_remove"></a>
            <?php endif;?>
        </td>
    </tr>
<?php endforeach;?>
</table>
<input type="hidden" name="data[RelationRemove][product_id]" value="1" />
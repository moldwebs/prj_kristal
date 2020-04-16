<?php if(!empty($append)):?>
        <tr>
            <td>
                <a href="/catalog/item/view/<?php e($_item['item_id'])?>?tkey=<?php e(TMP_KEY)?>" target="_blank">
                    <?php e($_item['title'])?> [<?php e($_item['code'])?>]
                </a>
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.item_id', array('type' => 'hidden', 'value' => $_item['item_id']));?>
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.code', array('type' => 'hidden', 'value' => $_item['code']));?>
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.title', array('type' => 'hidden', 'value' => $_item['title']));?>
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.weight', array('type' => 'hidden', 'value' => $_item['weight']));?>
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.currency', array('type' => 'hidden', 'value' => $_item['currency']));?>
            </td>
            <td align="center">
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.data.vendor.vendor_id', array('label' => false, 'div' => false, 'empty' => '', 'options' => (!empty($vendor_prices[$_item['item_id']]) ? $vendor_prices[$_item['item_id']] : array()), 'value' => $_item['vendor_id']));?>
            </td>
            <td align="center">
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.data.vendor_data.price', array('label' => false, 'div' => false));?>
            </td>
            <td align="center">
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.data.vendor_data.reserved', array('label' => false, 'div' => false, 'value' => '1', 'type' => 'checkbox'));?>
            </td>
            <td align="right">
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.price', array('label' => false, 'div' => false, 'value' => $_item['price'], 'style' => 'width: 70px;', 'rel_currency' => $_item['currency']));?>
                <?php e($_item['currency'])?>
            </td>
            <td></td>
            <td align="center">
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.quantity', array('label' => false, 'div' => false, 'value' => $_item['quantity']));?>
            </td>
            <td align="right">
                <span class="total_row"><?php e($_item['price_total'])?></span>
                <?php e($_item['currency'])?>
            </td>
            <td style="text-align: center;">
                <a onclick="$(this).parent().parent().next().remove();$(this).parent().parent().remove();order_calc();" class="ico ico_del" href="javascript:void(0)"></a>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.data.comment', array('label' => false, 'div' => false, 'placeholder' => ___('Comment'), 'style' => "width: 99.6%"));?>
            </td>
            <td colspan="6"></td>
        </tr>
<?php elseif(!empty($search)):?>
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
            <td style="width: 150px; text-align: center;"><?php et($item['ObjItemTree']['title'], 30)?></td>
            <td style="width: 100px; text-align: right;"><?php e($item['Price']['value'])?></td>
            <td style="width: 30px;"><?php if(!empty($item['Price']['value'])) e($currencies[$item['Price']['currency']])?></td>
            <td style="width: 20px;">
                <a onclick="$.get('/admin/shop/order/add_item?id=<?php e($item[$ws_model]['id'])?>&currency=<?php e($_GET['currency'])?>', function(data){$('#order_item').append(data);order_calc();});" class="ico ico_add"></a>
            </td>
        </tr>
    <?php endforeach;?>
    </table>
<?php else:?>
    <div style="width: 800px;" class="nw-table">
        <div class="nw-table-content">
        <form class="no-enter">
        <div class="n3 cl">
            <?php echo $this->Form->input('prods_fltr1', array('label' => ___('Title')));?>
            <?php if(!empty($bases)) echo $this->Form->input('prods_fltr2', array('options' => $bases, 'label' => ___('Category'), 'empty' => ___(' --- None --- ')));?>
            <div style="padding-top: 24px;">
                <input type="button" class="button primary" id="FltrSearch" value="<?php ___e('Search')?>" />
            </div>
        </div>
        <div id="prods_results" class="input text" style="height: 400px; overflow-y: scroll; margin-top: 5px;"></div>
        <script>
            $('#FltrSearch').click(function(){
                $('#prods_results').load('/admin/shop/order/add_item?search=1&currency=<?php e($_GET['currency'])?>&fltr_eqorrel__base_id=' + $('[name="data[prods_fltr2]"]').val() + '&fltr_lk__title=' + encodeURIComponent($('[name="data[prods_fltr1]"]').val()));
                //$('#prods_results').load('/admin/catalog/item/pbl_related/?fltr_eqorrel__base_id=' + $('[name="data[prods_fltr2]"]').val() + '&fltr_lk__title=' + $('[name="data[prods_fltr1]"]').val());
            });
        </script>
        </form>
        </div>
    </div>
<?php endif;?>
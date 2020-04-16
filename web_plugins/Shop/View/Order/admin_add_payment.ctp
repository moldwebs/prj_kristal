<?php if(!empty($append)):?>
        <tr>
            <td>
                <?php e($payment_types[$_item['item_id']])?>
                <?php echo $this->Form->input('ModOrder.payment.' . $_item['id'] . '.item_id', array('type' => 'hidden', 'value' => $_item['item_id']));?>
                <?php echo $this->Form->input('ModOrder.payment.' . $_item['id'] . '.title', array('type' => 'hidden', 'value' => $_item['title']));?>
                <?php echo $this->Form->input('ModOrder.payment.' . $_item['id'] . '.currency', array('type' => 'hidden', 'value' => $_item['currency']));?>
            </td>
            <td>
                <?php e($_item['title'])?>
            </td>
            <td align="right">
                <?php echo $this->Form->input('ModOrder.payment.' . $_item['id'] . '.price', array('label' => false, 'div' => false, 'value' => $_item['price'], 'style' => 'width: 70px;', 'rel_currency' => $_item['currency']));?>
                <?php e($_item['currency'])?>
            </td>
            <td></td>
            <td align="center">
                <?php echo $this->Form->input('ModOrder.payment.' . $_item['id'] . '.quantity', array('label' => false, 'div' => false, 'value' => $_item['quantity']));?>
            </td>
            <td align="right">
                <span class="total_row"><?php e($_item['price_total'])?></span>
                <?php e($_item['currency'])?>
            </td>
            <td style="text-align: center;">
                <a onclick="$(this).parent().parent().remove();order_calc();" class="ico ico_del" href="javascript:void(0)"></a>
            </td>
        </tr>
<?php else:?>
    <div style="width: 400px;">
        <div class="nw-table-content">
        <form class="no-enter" id="add_payment">
        <div class="n1 cl">
            <?php if(!empty($payment_types)) echo $this->Form->input('payment_id', array('options' => $payment_types, 'label' => ___('Type'), 'empty' => false));?>
            <?php echo $this->Form->input('title', array('label' => ___('Description')));?>
            <?php echo $this->Form->input('value', array('label' => ___('Value'), 'required' => 'required'));?>
            <div style="clear: both;"></div>
            <div class="submit">
                <input type="button" class="button primary" id="BtnSubmit" value="<?php ___e('Submit')?>" />
            </div>
        </div>
        <script>
            $('#BtnSubmit').click(function(){
                if(parseFloat($('#add_payment [name="data[value]"]').val()) > 0){
                    $.post('/admin/shop/order/add_payment?currency=<?php e($_GET['currency'])?>', $('#add_payment').serialize(), function(data){$('#order_payment').append(data);ajx_win_close();order_calc();});
                }
            });
        </script>
        </form>
        </div>
    </div>
<?php endif;?>
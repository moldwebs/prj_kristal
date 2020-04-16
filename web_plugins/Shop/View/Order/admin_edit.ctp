<?php $ws_model = 'ModOrder'?>

<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ModOrder', array('type' => 'file', 'class' => 'ajx_validate order_form'));?>
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
            </div>
        </div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php ___e('Items')?></span></a></li>
                    <li><a href="#node-2"><span><?php ___e('Customer Data')?></span></a></li>
                    <li><a href="#node-3"><span><?php ___e('Status')?></span></a></li>
                    <li><a href="#node-4"><span><?php ___e('Extra')?></span></a></li>
                    <li><a href="#node-5"><span><?php ___e('Payment Data')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div style="padding: 10px;">
                        <table id="order" class="lst">
                            <thead><tr>
                                <th><?php ___e('Product');?></th>
                                <th width="250px" style="text-align: center;"><?php ___e('Vendor');?></th>
                                <th width="60px" style="text-align: center;"><?php ___e('Price');?></th>
                                <th width="20px" style="text-align: center;"><span title="<?php ___e('Reserved')?>">R</span></th>
                                <th width="150px" style="text-align: right;"><?php ___e('Price');?></th>
                                <th width="20px"></th>
                                <th width="50px" style="text-align: center;"><?php ___e('Qnt.');?></th>
                                <th width="100px" style="text-align: right;"><?php ___e('Total');?></th>
                                <th width="40px"></th>
                            </tr></thead>
                            <tbody id="order_item">
                                <?php foreach($item['ModOrderItem']['item'] as $_item):?>
                                    <tr>
                                        <td>
                                            <a href="/catalog/item/view/<?php e($_item['item_id'])?>?tkey=<?php e(TMP_KEY)?>" target="_blank">
                                                <?php e($_item['title'])?> [<?php e($_item['code'])?>]
                                            </a>
                                            <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.weight', array('type' => 'hidden', 'value' => $_item['weight']));?>
                                        </td>
                                        <td align="center">
                                            <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.data.vendor.vendor_id', array('label' => false, 'div' => false, 'empty' => '', 'options' => (!empty($vendor_prices[$_item['item_id']]) ? $vendor_prices[$_item['item_id']] : array()), 'value' => $_item['data']['vendor']['vendor_id']));?>
                                        </td>
                                        <td align="center">
                                            <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.data.vendor_data.vprice', array('label' => false, 'div' => false, 'value' => $_item['data']['vendor_data']['vprice']));?>
                                        </td>
                                        <td align="center">
                                            <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.data.vendor_data.reserved', array('label' => false, 'div' => false, 'value' => '1', 'type' => 'checkbox', 'checked' => $_item['data']['vendor_data']['reserved']));?>
                                        </td>
                                        <td align="right">
                                            <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.price', array('label' => false, 'div' => false, 'value' => $_item['price'], 'style' => 'width: 70px;', 'rel_currency' => $item[$ws_model]['currency']));?>
                                            <?php e($item[$ws_model]['currency'])?>
                                        </td>
                                        <td></td>
                                        <td align="center">
                                            <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.quantity', array('label' => false, 'div' => false, 'value' => $_item['quantity']));?>
                                        </td>
                                        <td align="right">
                                            <span class="total_row"><?php e($_item['price_total'])?></span>
                                            <?php e($item[$ws_model]['currency'])?>
                                        </td>
                                        <td style="text-align: center;">
                                            <a onclick="$(this).parent().parent().next().remove();$(this).parent().parent().remove();order_calc();" class="ico ico_del" href="javascript:void(0)"></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <?php echo $this->Form->input('ModOrder.item.' . $_item['id'] . '.data.comment', array('label' => false, 'div' => false, 'value' => $_item['data']['comment'], 'placeholder' => ___('Comment'), 'style' => "width: 99.6%; opacity: 0.6;"));?>
                                        </td>
                                        <td colspan="6"></td>
                                    </tr>
                                <?php endforeach;?>
                                
                            </tbody>
    
                            <tbody id="order_service">
                               <?php foreach($item['ModOrderItem']['extra'] as $_item):?>
                                <?php if($_item['price'] < 0) continue;?>
                                    <tr>
                                        <td colspan="2">
                                            <?php e($_item['title'])?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td align="right">
                                            <?php echo $this->Form->input('ModOrder.extra.' . $_item['id'] . '.price', array('label' => false, 'div' => false, 'value' => $_item['price'], 'style' => 'width: 70px;', 'rel_currency' => $item[$ws_model]['currency']));?>
                                            <?php e($item[$ws_model]['currency'])?>
                                        </td>
                                        <td></td>
                                        <td align="center">
                                            <?php echo $this->Form->input('ModOrder.extra.' . $_item['id'] . '.quantity', array('label' => false, 'div' => false, 'value' => $_item['quantity']));?>
                                        </td>
                                        <td align="right">
                                            <span class="total_row"><?php e($_item['price_total'])?></span>
                                            <?php e($item[$ws_model]['currency'])?>
                                        </td>
                                        <td style="text-align: center;">
                                            <a onclick="$(this).parent().parent().remove();order_calc();" class="ico ico_del" href="javascript:void(0)"></a>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
    
                            <tbody id="order_discount">
                               <?php foreach($item['ModOrderItem']['extra'] as $_item):?>
                                <?php if($_item['price'] > 0) continue;?>
                                    <tr>
                                        <td colspan="2">
                                            <?php e($_item['title'])?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td align="right">
                                            <?php echo $this->Form->input('ModOrder.extra.' . $_item['id'] . '.price', array('label' => false, 'div' => false, 'value' => $_item['price'], 'style' => 'width: 70px;', 'rel_currency' => $item[$ws_model]['currency']));?>
                                            <?php e($item[$ws_model]['currency'])?>
                                        </td>
                                        <td></td>
                                        <td align="center">
                                            <?php echo $this->Form->input('ModOrder.extra.' . $_item['id'] . '.quantity', array('label' => false, 'div' => false, 'value' => $_item['quantity']));?>
                                        </td>
                                        <td align="right">
                                            <span class="total_row"><?php e($_item['price_total'])?></span>
                                            <?php e($item[$ws_model]['currency'])?>
                                        </td>
                                        <td style="text-align: center;">
                                            <a onclick="$(this).parent().parent().remove();order_calc();" class="ico ico_del" href="javascript:void(0)"></a>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
    
                            <tbody id="order_extra">
                                
                                <?php foreach(array('shipping', 'lifting') as $extra_type):?>
                                <?php $_item = reset($item['ModOrderItem'][$extra_type])?>
                                    <tr>
                                        <td colspan="2">
                                            <?php echo $this->Form->input('ModOrder.sextra.' . $extra_type . '.item_id', array('label' => false, 'div' => false, 'empty' => ($extra_type == 'shipping' ? ___('Shipping') : ___('Lifting')), 'options' => ($extra_type == 'shipping' ? $shippings : $liftings), 'value' => $_item['item_id']));?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td align="right">
                                            <?php echo $this->Form->input('ModOrder.sextra.' . $extra_type . '.price', array('label' => false, 'div' => false, 'value' => $_item['price'], 'style' => 'width: 70px;', 'rel_currency' => $item[$ws_model]['currency']));?>
                                            <?php e($item[$ws_model]['currency'])?>
                                        </td>
                                        <td></td>
                                        <td align="center">
                                            <?php echo $this->Form->input('ModOrder.sextra.' . $extra_type . '.quantity', array('label' => false, 'div' => false, 'value' => $_item['quantity']));?>
                                        </td>
                                        <td align="right">
                                            <span class="total_row"><?php e($_item['price_total'])?></span>
                                            <?php e($item[$ws_model]['currency'])?>
                                        </td>
                                        <td style="text-align: center;">
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php echo $this->Form->input('ModOrder.sextra.' . $extra_type . '.data.comment', array('label' => false, 'div' => false, 'value' => $_item['data']['comment'], 'placeholder' => ___('Comment'), 'style' => "opacity: 0.6;"));?>
                                        </td>
                                        <td colspan="7"></td>
                                    </tr>
                                <?php endforeach;?>
    
                            </tbody>
                            <thead>
                                <tr>
                                    <td style="text-align: left; font-weight: bold;" colspan="1"><?php ___e('Total')?></td>
                                    <td colspan="7" style="text-align: right; font-weight: bold;">
                                        <span class="total"><?php e($item[$ws_model]['price'])?></span>
                                        <?php e($item[$ws_model]['currency'])?>
                                    </td>
                                    <td></td>
                                </tr>
                            </thead>
                        </table>
                        <div>&nbsp;</div>
                        <div>
                            <a href="/admin/shop/order/add_item?currency=<?php e($item[$ws_model]['currency'])?>" class="ajx_win button"><?php ___e('Add Product')?></a>
                            <a href="/admin/shop/order/add_service?currency=<?php e($item[$ws_model]['currency'])?>" class="ajx_win button"><?php ___e('Add Service')?></a>
                            <a href="/admin/shop/order/add_discount?currency=<?php e($item[$ws_model]['currency'])?>" class="ajx_win button"><?php ___e('Add Discount')?></a>
                            <a href="javascript:void(0)" onclick="delivery_calc(this);" class="button"><?php ___e('Calculate Delivery')?></a>
                        </div>

                        <div>&nbsp;</div>
                        <div>&nbsp;</div>

                        <table id="payment" class="lst">
                            <thead><tr>
                                <th><?php ___e('Payment');?></th>
                                <th width="300px" style="text-align: left;"><?php ___e('Description');?></th>
                                <th width="150px" style="text-align: right;"><?php ___e('Price');?></th>
                                <th width="20px"></th>
                                <th width="50px" style="text-align: center;"><?php ___e('Qnt.');?></th>
                                <th width="100px" style="text-align: right;"><?php ___e('Total');?></th>
                                <th width="40px"></th>
                            </tr></thead>
    
                            <tbody id="order_payment">
                                
                                <?php foreach($item['ModOrderItem']['payment'] as $_item):?>
                                    <tr>
                                        <td>
                                            <?php e($payments[$_item['item_id']])?>
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
                                <?php endforeach;?>
    
                            </tbody>
                            <thead>
                                <tr>
                                    <td style="text-align: left; font-weight: bold;" colspan="1"><?php ___e('To Pay')?></td>
                                    <td colspan="5" style="text-align: right; font-weight: bold;">
                                        <span class="total"><?php e($item[$ws_model]['price'])?></span>
                                        <?php e($item[$ws_model]['currency'])?>
                                    </td>
                                    <td></td>
                                </tr>
                            </thead>
                        </table>
                        <div>&nbsp;</div>
                        <div>
                            <a href="/admin/shop/order/add_payment?currency=<?php e($item[$ws_model]['currency'])?>" class="ajx_win button"><?php ___e('Add Payment')?></a>
                        </div>                        
                        
                    </div>
                </div>
                <div id="node-3">
                    <div class="n4 cl">
                        <?php echo $this->Form->input('ModOrder.onstatus', array('label' => ___('Order Status'), 'options' => $sys_order_statuses, 'value' => $item[$ws_model]['onstatus']));?>
                        <?php echo $this->Form->input('ModOrder.shipstatus', array('label' => ___('Shipping Status'), 'options' => $sys_order_ship_statuses, 'value' => $item[$ws_model]['shipstatus']));?>
                        <?php echo $this->Form->input('ModOrder.paystatus', array('disabled' => (!empty($item[$ws_model]['transaction_id']) ? true : false), 'label' => ___('Pay Status'), 'options' => $sys_order_pay_statuses, 'value' => $item[$ws_model]['paystatus']));?>
                    </div>

                    <div style="padding: 20px 10px;">
                        <div class="input text">
                            <label><?php ___e('Actions')?></label>
                        </div>
                        <table id="order_action" class="lst">
                            <tbody>
                                <?php foreach($item['ModOrderAction'] as $action):?>
                                    <tr>
                                        <td style="width: 70px;" align="center"><?php e(date_stl_1($action['created']))?></td>
                                        <td style="width: 110px;"><?php eth($action['Operator']['username'], 50)?></td>
                                        <td style="width: 300px;">
                                            <?php ___e($action['data']['action'])?>: <?php ___e($action['data']['value'])?>
                                        </td>
                                        <td title="<?php eth($action['message'], 500)?>">
                                            <?php eth($action['message'], 100)?>
                                        </td>
                                        <td style="text-align: center; width: 40px;">
                                            <?php if(strtotime($action['created']) > (time() - 300) && 1==2):?>
                                                <a onclick="$(this).parent().parent().remove();order_calc();" class="ico ico_del" href="javascript:void(0)"></a>
                                            <?php endif;?>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    <div>&nbsp;</div>
                    <div>
                        <a href="/admin/shop/order/add_action" class="ajx_win button"><?php ___e('Add Action')?></a>
                    </div> 
                    </div>

    
                    <?php if(!empty($item[$ws_model]['data']['data_shipping']['comment']) || !empty($item[$ws_model]['data']['data_checkout']['comment'])):?>
                        <div class="nw-table">
                            <div class="nw-table-title"><?php ___e('Comment')?></div>
                            <div class="nw-table-content pd">
                                <div><?php e(nl2br($item[$ws_model]['data']['data_shipping']['comment']))?></div>
                                <div><?php e(nl2br($item[$ws_model]['data']['data_checkout']['comment']))?></div>
                            </div>
                        </div>
                    <?php endif;?>   

                </div>
                
                <div id="node-4">
                    <div class="n2 cl">
                        <?php echo $this->Form->input('ModOrder.operator_id', array('label' => ___('Operator'), 'options' => $operators, 'value' => (!empty($item[$ws_model]['operator_id']) ? $item[$ws_model]['operator_id'] : $this->Session->read('Auth.User.id'))));?>
                    </div>
                    <?php echo $this->aelement('admin_edit_extra')?>
                    <?php echo $this->Form->input('ModOrder.data_extra.referer', array('label' => ___('Referer'), 'type' => 'text', 'value' => $item[$ws_model]['data']['data_extra']['referer']));?>
                </div>
                
                <div id="node-2">
                    <?php echo $this->Form->input('ModOrder.userid', array('type' => 'hidden', 'value' => $item[$ws_model]['userid']));?>
                    <?php echo $this->Form->input('ModOrder.data_checkout.lang', array('type' => 'hidden', 'value' => $item[$ws_model]['data']['data_checkout']['lang']));?>
                    
                    <div class="n5 cl">
                    <?php $base_fields = array('name' => ___('Name'), 'lname' => ___('Last Name'), 'phone' => ___('Phone'), 'phone_alt' => ___('Phone 2'), 'email' => ___('Email'))?>
                    <?php foreach($base_fields as $key => $val) if(!empty($item[$ws_model]['data']['data_checkout'][$key]) || empty($item['ModOrder']['id'])):?>
                        <?php echo $this->Form->input('ModOrder.data_checkout.' . $key, array('label' => $val, 'value' => $item[$ws_model]['data']['data_checkout'][$key], 'class' => ''));?>
                    <?php endif;?>
                    </div>
                    
                    <div class="n5 cl">
                    <?php if(empty($item['ModOrder']['id'])) echo $this->Form->input('ModOrder.data_payment.customer_type', array('label' => ___('Customer type'), 'options' => array('0' => ___('Physical Person'), '1' => ___('Juridical Person')), 'value' => $item[$ws_model]['data']['data_payment']['customer_type'], 'class' => ''));?>
                    <?php $base_fields = array('jur_company' => ___('Company name'), 'jur_adress' => ___('Juridical adress'), 'jur_tax' => ___('VAT code'), 'jur_iban' => ___('IBAN'), 'jur_bank' => ___('Bank'), 'jur_bank_code' => ___('Bank Code'))?>
                    <?php foreach($base_fields as $key => $val) if(!empty($item[$ws_model]['data']['data_payment'][$key]) || empty($item['ModOrder']['id'])):?>
                        <?php echo $this->Form->input('ModOrder.data_payment.' . $key, array('label' => $val, 'value' => $item[$ws_model]['data']['data_payment'][$key], 'class' => 'data_payment_tp1'));?>
                    <?php endif;?>
                    </div>

                    <?php if(empty($item['ModOrder']['id'])):?>
                    <div class="n4 cl">
                        <div class="input text">
                            <label>&nbsp;</label>
                            <a href="/admin/shop/order/add_customer" class="ajx_win button"><?php ___e('Add Customer')?></a>
                        </div>
                    </div>
                    <?php endif;?>        

                    <div class="n4 cl">
                    <?php echo $this->Form->input('ModOrder.data_shipping.' . 'zone_id', array('label' => ___('Zone'), 'options' => $zones, 'empty' => '', 'value' => $item[$ws_model]['data']['data_shipping']['zone_id'], 'class' => ''));?>
                    <?php echo $this->Form->input('ModOrder.data_shipping.' . 'street', array('label' => ___('Street'), 'value' => $item[$ws_model]['data']['data_shipping']['street'], 'class' => ''));?>
                    <?php echo $this->Form->input('ModOrder.data_shipping.' . 'appartment', array('label' => ___('Appartment'), 'value' => $item[$ws_model]['data']['data_shipping']['appartment'], 'class' => ''));?>
                    <?php echo $this->Form->input('ModOrder.data_shipping.' . 'floor', array('label' => ___('Floor'), 'value' => $item[$ws_model]['data']['data_shipping']['floor'], 'class' => ''));?>
                    </div>
                    
                    <div>
                        <div class="input text">
                            <label><?php ___e('Customer Orders')?></label>
                        </div>
                        <div class="n4 cl">
                            <?php echo $this->Form->input('oth.customer_order_name', array('label' => ___('Name'), 'value' => $item[$ws_model]['data']['data_checkout']['name']));?>
                            <?php echo $this->Form->input('oth.customer_order_phone', array('label' => ___('Phone'), 'value' => $item[$ws_model]['data']['data_checkout']['phone']));?>
                            <?php echo $this->Form->input('oth.customer_order_ip', array('label' => ___('Ip adress'), 'value' => $item[$ws_model]['data']['userip']));?>
                            <div class="input text">
                                <label>&nbsp;</label>
                                <a class="button" onclick="$.get('/admin/shop/order/customer/<?php e($user['User']['id'])?>', {'fltr_objdt__name': $('#othCustomerOrderName').val(), 'fltr_objdt__phone': $('#othCustomerOrderPhone').val(), 'fltr_objdt__userip': $('#othCustomerOrderIp').val()}, function(data){$('#customer_orders').html(data);ajx_init_js();});" href="javascript:void();"><?php ___e('Search')?></a>
                            </div>
                        </div>
                        <div style="max-height: 300px; overflow-y: auto;" id="customer_orders"></div>
                    </div>
                    
                </div>
                
                <div id="node-5">
                    <?php if(!empty($item[$ws_model]['data']['pay_data']['dates'])):?>
                    
                        <div class="n6 cl">
                        <?php foreach(ws_tree2list($item[$ws_model]['data']['pay_data']['dates']) as $key => $val):?>
                            <?php if(is_array($val)):?>
                                <div class="input text">
                                    <label><?php ___e($val['key'])?></label>
                                    <input type="text" name="data[pay_data][<?php e($header_val)?>][<?php e($val['key'])?>]" value="<?php e($val['val'])?>" />
                                </div>
                            <?php else:?>
                                <?php $header_val = $val?>
                                <div style="clear: both;"></div>
                                <div class="input text"><label style="font-style: oblique;"><?php ___e($val)?></label></div>
                                <div style="clear: both;"></div>
                            <?php endif;?>
                        <?php endforeach;?>
                        </div>
                        
                        <?php if(!empty($item[$ws_model]['long_data']['pay_data']['files'])):?>
                            <div class="input text"><label style="font-style: oblique;"><?php ___e('Attachments')?></label></div>
                            <div class="n4 cl">
                            <?php foreach($item[$ws_model]['long_data']['pay_data']['files'] as $key => $val):?>
                                <div class="input text">
                                    <label><?php ___e($key)?></label>
                                    <label style="font-weight: normal;"><a download="<?php e($val['name'])?>" href="data:<?php e($val['name'])?>;base64,<?php e($val['base64'])?>"><?php e($val['name'])?></a></label>
                                </div>
                            <?php endforeach;?>
                            </div>
                        <?php endif;?>
                    <?php endif;?>
                </div>
                
            </div>
        </div>
        <div class="nw-table-footer">
            <?php if($item['ModOrder']['id'] > 0) echo $this->Form->button(___('Copy & Edit'), array('name' => 'saction', 'value' => '10'))?>
            <?php echo $this->Layout->form_save('simple_modify');?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>
<div class="clear"></div>
<?php echo $this->Form->end();?>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        $('.order_form input').live('click', function(){
           $(this).select();
        });
        $('#order input,#order select,#payment input,#payment select').live('change', function(){
            order_calc();
        });
        
        $('[name="data[ModOrder][data_payment][customer_type]"]').live('change', function(){
            if($(this).val() != '1'){
                $('.data_payment_tp1').attr('disabled', 'disabled').parent().hide();
            } else {
                $('.data_payment_tp1').removeAttr('disabled').parent().show();
            }
        }).trigger('change');
        /*
        $('#order').on("DOMSubtreeModified",function(){
            alert('ok');
        });
        */
        /*
        $('.tabs-left > div').css('min-height', '0');
        $('.ui-tabs-nav').hide();
        $('.tabs-left').css('padding-left', '0');
        $('.ui-tabs-panel').show();
        $('.ui-tabs-panel').each(function(){
           $(this).append("<hr>"); 
        });
        */
    });
    function order_calc(){
       var or_total = 0;
       $('#order').find('[name$="[price]"]').each(function(el){
            var el_price = parseFloat($(this).val());
            var el_quantity = parseFloat($(this).parent().parent().find('[name$="[quantity]"]:first').val());
            if(!el_price) el_price = 0;
            if(!(el_quantity > 0)) el_quantity = 0;
            var el_total = el_price * el_quantity;
            or_total = or_total + el_total;
            $(this).parent().parent().find('.total_row:first').text(el_total);
       });
       $('#order').find('.total:first').text(or_total);
       
       var pay_total = 0;
       $('#payment').find('[name$="[price]"]').each(function(el){
            var el_price = parseFloat($(this).val());
            var el_quantity = parseFloat($(this).parent().parent().find('[name$="[quantity]"]:first').val());
            if(!el_price) el_price = 0;
            if(!(el_quantity > 0)) el_quantity = 0;
            var el_total = el_price * el_quantity;
            pay_total = pay_total + el_total;
            $(this).parent().parent().find('.total_row:first').text(el_total);
       });
       $('#payment').find('.total:first').text(or_total - pay_total);
    }
    order_calc();
    
    function delivery_calc(obj){
        $.post('/admin/shop/order/delivery/<?php e($item[$ws_model]['currency'])?>', $(obj).parents('form:first').serialize(), function(data){
            var response = jQuery.parseJSON(data);
            $('[name="data[ModOrder][sextra][shipping][price]"]').val(response.shipping);
            $('[name="data[ModOrder][sextra][lifting][price]"]').val(response.lifting);
            $('[name="data[ModOrder][sextra][shipping][quantity]"]').val('1');
            $('[name="data[ModOrder][sextra][lifting][quantity]"]').val('1');
            order_calc();
        });
    }
</script>
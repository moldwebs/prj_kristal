<div class="container_16" style="width: 1300px;">
    <?php $ws_model = 'ModOrder'?>
    
    <div class="grid_4">
        <div class="nw-table">
            <div class="nw-table-title"><?php ___e('Order')?></div>
            <div class="nw-table-content pd">
                <div class="nw-list">
                  
                    <div class="head"><?php ___e('Status')?></div>
                    <div><?php e($sys_order_statuses[$item[$ws_model]['onstatus']])?></div>
                    
                    <div class="head"><?php ___e('Shipping')?></div>
                    <div><?php e($sys_order_ship_statuses[$item[$ws_model]['shipstatus']])?></div>
                    
                    <div class="head"><?php ___e('Payment')?></div>
                    <div><?php eth((!empty($payments[$item['ModOrder']['data']['data_payment']['payment']]) ? $payments[$item['ModOrder']['data']['data_payment']['payment']] : $payments[$item['ModOrder']['payment']]))?></div>
                    <div><?php e($sys_order_pay_statuses[$item[$ws_model]['paystatus']])?></div>
                    
                </div>
            </div>
        </div>

        <?php if(!empty($item[$ws_model]['data']['data_shipping']['street'])):?>
        <div class="nw-table">
            <div class="nw-table-title"><?php ___e('Address')?></div>
            <div class="nw-table-content pd">
                <div class="nw-list">
                    <?php $base_fields = array('zone_id' => ___('Zone'), 'street' => ___('Street'), 'appartment' => ___('Appartment'), 'floor' => ___('Floor'))?>
                    <?php foreach($base_fields as $key => $val) if(!empty($item[$ws_model]['data']['data_shipping'][$key])):?>
                        <div class="head"><?php e($val)?></div>
                        <div><?php e($key == 'zone_id' ? $zones_parents[$zones_list[$item[$ws_model]['data']['data_shipping']['zone_id']]['ObjItemTree']['parent_id']] . ', ' . $zones_list[$item[$ws_model]['data']['data_shipping']['zone_id']]['ObjItemTree']['title'] : $item[$ws_model]['data']['data_shipping'][$key])?></div>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <?php endif;?>
        
        <div class="nw-table">
            <div class="nw-table-title"><?php ___e('Customer')?></div>
            <div class="nw-table-content pd">
                <div class="nw-list">
                    <?php $base_fields = array('name' => ___('Name'), 'lname' => ___('Last Name'), 'phone' => ___('Phone'), 'phone_alt' => ___('Phone 2'), 'email' => ___('Email'))?>
                    <?php foreach($base_fields as $key => $val) if(!empty($item[$ws_model]['data']['data_checkout'][$key])):?>
                        <div class="head"><?php e($val)?></div>
                        <div><?php e($item[$ws_model]['data']['data_checkout'][$key])?></div>
                    <?php endif;?>
                    
                    <?php $base_fields = array('jur_company' => ___('Company name'), 'jur_adress' => ___('Juridical adress'), 'jur_tax' => ___('VAT code'), 'jur_iban' => ___('IBAN'), 'jur_bank' => ___('Bank'), 'jur_bank_code' => ___('Bank Code'))?>
                    <?php foreach($base_fields as $key => $val) if(!empty($item[$ws_model]['data']['data_payment'][$key])):?>
                        <div class="head"><?php e($val)?></div>
                        <div><?php e($item[$ws_model]['data']['data_payment'][$key])?></div>
                    <?php endif;?>
  
                </div>
            </div>
        </div>
        
        <?php if(!empty($user)):?>
        <div class="nw-table">
            <div class="nw-table-title"><?php ___e('User')?></div>
            <div class="nw-table-content pd">
                <div class="nw-list">
                    <?php $base_fields = array('username' => ___('Name'), 'usermail' => ___('Email'), 'extra_1' => ___('Extra 1'), 'extra_2' => ___('Extra 2'), 'extra_3' => ___('Extra 3'))?>
                    <?php foreach($base_fields as $key => $val) if(!empty($user['User'][$key])):?>
                        <div class="head"><?php e($val)?></div>
                        <div><?php e($user['User'][$key])?></div>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <?php endif;?>
    </div>
    
    
    <div class="grid_12">
        <div class="nw-table">
            <div class="nw-table-title">
            <div class="fl"><?php ___e('ID')?>: #<?php e(str_pad($item[$ws_model]['id'], 5, "0", STR_PAD_LEFT))?></div>
            <div class="fr">
            </div>
            </div>
            <div class="nw-table-content pd">
                <table class="lst">
                    <thead><tr>
                        <th><?php ___e('Product');?></th>
                        <th width="100px" style="text-align: left;"><?php ___e('Code');?></th>
                        <th width="70px" style="text-align: right;"><?php ___e('Weight');?></th>
                        <th width="70px" style="text-align: center;"><?php ___e('Ext');?></th>
                        <th width="100px" style="text-align: right;"><?php ___e('Price');?></th>
                        <th width="50px" style="text-align: center;"><?php ___e('Qnt.');?></th>
                        <th width="100px" style="text-align: right;"><?php ___e('Total');?></th>
                    </tr></thead>
                    <tbody>
                        <?php foreach($item['ModOrderItem']['item'] as $_item):?>
                            <tr>
                                <td title="<?php e($_item['data']['comment'])?>">
                                    <a href="/catalog/item/view/<?php e($_item['item_id'])?>?tkey=<?php e(TMP_KEY)?>" target="_blank">
                                        <?php if(!empty($_item['rel_item_id'])) e('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')?><?php e($_item['title'])?>
                                    </a>
                                    <?php if(!empty($vendors[$_item['data']['vendor']['vendor_id']])):?>
                                    ~ <?php e($vendors[$_item['data']['vendor']['vendor_id']])?>
                                    <?php endif;?>
                                </td>
                                <td align="left">
                                    <?php e($_item['code'])?>
                                    <?php if(!empty($_item['data']['vendor']['vendor_code'])):?>
                                    ~ <span title="<?php e($_item['data']['vendor']['vendor_price'])?>"><?php e($_item['data']['vendor']['vendor_code'])?></span>
                                    <?php endif;?>
                                </td>
                                <td align="right"><?php e(($_item['weight'] > 0 ? $_item['weight'] . ' Kg' : null))?></td>
                                <td align="center">
                                    <?php e($_item['ext'])?>
                                    <?php foreach($_item['data']['extra'] as $key => $val):?>
                                        &nbsp;<span title="<?php ___e($key)?>: <?php e($val)?>">[<?php e($key[0])?>]</span>
                                    <?php endforeach;?>
                                </td>
                                <td align="right"><?php e($_item['price'])?> <?php e(!empty($_item['price']) ? $_item['currency'] : '')?></td>
                                <td align="center"><?php e($_item['quantity'])?></td>
                                <td align="right"><?php e($_item['price_total'])?> <?php e(!empty($_item['price_total']) ? $_item['currency'] : '')?></td>
                            </tr>
                        <?php endforeach;?>
                        <?php foreach($item['ModOrderItem'] as $_tp => $_items) if($_tp != 'item' && $_tp != 'payment') foreach($_items as $_item):?>
                            <?php if(empty($_item['title'])) continue;?>
                            <tr> 
                                <td title="<?php e($_item['data']['comment'])?>">
                                    <?php ___e($_item['title'])?>
                                </td>
                                <td align="left"></td>
                                <td align="right"></td>
                                <td align="center"><?php e($_item['ext'])?></td>
                                <td align="right"><?php e($_item['price'])?> <?php e(!empty($_item['price']) ? $_item['currency'] : '')?></td>
                                <td align="center"><?php e($_item['quantity'])?></td>
                                <td align="right"><?php e($_item['price_total'])?> <?php e(!empty($_item['price_total']) ? $_item['currency'] : '')?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                    <thead>
                        <tr>
                            <td style="text-align: left; font-weight: bold;" colspan="5"><?php ___e('Total')?></td>
                            <td colspan="2" style="text-align: right; font-weight: bold;"><?php e($item[$ws_model]['price'])?> <?php e($item[$ws_model]['currency'])?></td>
                        </tr>
                    </thead>
                </table>
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
       
        <?php if(!empty($item['ModOrderAction'])):?>
            <div class="nw-table">
                <div class="nw-table-title"><?php ___e('Actions')?></div>
                <div class="nw-table-content pd">
                <table class="lst">
                    <tbody>
                        <?php foreach($item['ModOrderAction'] as $action):?>
                            <tr>
                                <td style="width: 70px;" align="center"><?php e(date_stl_1($action['created']))?></td>
                                <td style="width: 110px;"><?php eth($action['Operator']['username'], 50)?></td>
                                <td style="width: 200px;">
                                    <?php ___e($action['data']['action'])?>: <?php ___e($action['data']['value'])?>
                                </td>
                                <td title="<?php eth($action['message'], 500)?>">
                                    <?php eth($action['message'], 100)?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                </div>
            </div>
        <?php endif;?>

    </div>
    
    <div class="clear"></div>
</div>
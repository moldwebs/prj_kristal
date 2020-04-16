<?php $ws_model = 'ModOrder'?>
<div class="nw-table-content pd">
    <?php if(!empty($items)):?>
    <table>
        <thead><tr>
        	<th style="text-align: left;"><?php ___e('Order');?></th>
            <?php if(Configure::read('CMS.settings.shop.orders_show') != '1'):?><th style="width: 300px; text-align: left;"><?php ___e('Shipping')?></th><?php endif;?>
            <th colspan="2" style="width: 100px;"><?php ___e('Total')?></th>
            <th style="width: 150px;"><?php ___e('Status')?></th>
            <th style="width: 100px;"><?php ___e('Created');?></th>
            <th style="width: 100px;"><?php ___e('Modified')?></th>
        </tr></thead>
        <tbody>
        <?php foreach ($items as $item):?>
        	<tr>
        		<td style="text-align: left;">
                    <div style="line-height: 20px;">#<?php e(str_pad($item[$ws_model]['id'], 5, "0", STR_PAD_LEFT))?>: <b style="font-weight: bold;"><?php et($item[$ws_model]['data']['data_checkout']['name'], 50)?></b>, <span style="font-style: italic; font-size: 95%;"><?php et($item[$ws_model]['data']['data_checkout']['phone'], 50)?></span></div>
                    <?php if(Configure::read('CMS.settings.shop.orders_show') != '1'):?>
                    <div>
                        <?php foreach($item['ModOrderItem']['item'] as $_item):?>
                            <div style="font-size: 90%; line-height: 15px;">
                                <a style="color: #666666;" href="/catalog/item/view/<?php e($_item['item_id'])?>" target="_blank"><?php e($_item['title'])?></a>
                                x <?php e($_item['quantity'])?>
                                <?php if(!empty($_item['data']['vendor']['vendor_id'])):?>
                                ~ <?php e($vendors[$_item['data']['vendor']['vendor_id']])?>
                                <?php endif;?>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <?php endif;?>
                </td>
                <?php if(Configure::read('CMS.settings.shop.orders_show') != '1'):?>
                <td>
                    <?php if(!empty($item[$ws_model]['data']['data_shipping']['street']) && !empty($zones_list[$item[$ws_model]['data']['data_shipping']['zone_id']])):?>
                        <div style="font-size: 90%; line-height: 17px;">
                            <?php if(!empty($zones_list[$item[$ws_model]['data']['data_shipping']['zone_id']])) e($zones_parents[$zones_list[$item[$ws_model]['data']['data_shipping']['zone_id']]['ObjItemTree']['parent_id']] . ', ' . $zones_list[$item[$ws_model]['data']['data_shipping']['zone_id']]['ObjItemTree']['title'])?>, <?php eth($item[$ws_model]['data']['data_shipping']['street'] . (!empty($item[$ws_model]['data']['data_shipping']['appartment']) ? ', ' . $item[$ws_model]['data']['data_shipping']['appartment'] : null), 200)?>
                        </div>
                    <?php endif;?>
                    <div style="font-size: 90%; line-height: 17px;">
                        <?php eth((!empty($shippings[$item['ModOrderItem']['shipping'][0]['item_id']]) ? $shippings[$item['ModOrderItem']['shipping'][0]['item_id']] : $item['ModOrderItem']['shipping'][0]['title']), 200)?>
                    </div>
                    <?php if(!empty($item[$ws_model]['data']['fast'])):?>
                        <div style="font-size: 90%; line-height: 17px;">
                        <b style="font-weight: bold;"><?php ___e('FAST')?></b>
                        </div>
                    <?php endif;?>
                </td>
                <?php endif;?>
                <td title="<?php eth((!empty($payments[$item['ModOrder']['data']['data_payment']['payment']]) ? $payments[$item['ModOrder']['data']['data_payment']['payment']] : $item['ModOrder']['payment']))?>" style="text-align: right;"><?php e($item[$ws_model]['price'])?></td>
                <td><?php e($item[$ws_model]['currency'])?></td>
                <td style="text-align: center;">
                    <div style="font-size: 90%; line-height: 17px;">
                        <b><?php e($sys_order_statuses[$item[$ws_model]['onstatus']])?></b>
                    </div>
                    <div style="font-size: 90%; line-height: 17px;">
                        <b><?php e($sys_order_ship_statuses[$item[$ws_model]['shipstatus']])?></b>
                    </div>
                </td>
                <td style="text-align: center;">
                    <div style="line-height: 17px;"><?php e(date_stl_1($item[$ws_model]['created']))?></div>
                    <div style="line-height: 17px; font-size: 90%;"><?php eth($item['Operator']['username'], 50)?></div>
                </td>
                <td style="text-align: center;">
                    <div style="line-height: 17px;"><?php e(date_stl_1($item['ModOrderAction'][0]['created']))?></div>
                    <div style="line-height: 17px; font-size: 90%;"><?php eth($item['ModOrderAction'][0]['Operator']['username'], 50)?></div>
                </td>
        	</tr>
            <?php if(!empty($item['ModOrderAction'])):?>
                <?php foreach($item['ModOrderAction'] as $action):?>
                    <tr>
                        <td style="font-size: 90%; height: 12px;">
                            <?php e(date_stl_1($action['created']))?> 
                            &nbsp;&nbsp;&nbsp; <i><?php eth($action['Operator']['username'], 50)?></i>
                            &nbsp;&nbsp;&nbsp; <?php ___e($action['data']['action'])?>: <?php ___e($action['data']['value'])?>
                        </td>
                        <td style="font-size: 90%; height: 12px;">
                            <?php eth($action['message'], 100)?>
                        </td>
                        <td colspan="5" style="font-size: 90%; height: 12px;">
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else:?>
        <?php echo $this->element('/admin/no_results')?>
    <?php endif;?>
</div>
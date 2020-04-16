<?php $ws_model = 'ModOrder'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Filters')?></div>
        <div class="nw-table-content pd">
            <?php echo $this->Form->input('fltr_eq__id', array('label' => ___('ID'), 'type' => 'text'));?>
            <?php echo $this->Form->input('fltr_eq__onstatus', array('label' => ___('Status'), 'options' => $sys_order_statuses, 'empty' => ___('All')));?>
            <?php echo $this->Form->input('fltr_eq__shipstatus', array('label' => ___('Shipping'), 'options' => $sys_order_ship_statuses, 'empty' => ___('All')));?>
            <?php echo $this->Form->input('fltr_eq__paystatus', array('label' => ___('Payment'), 'options' => $sys_order_pay_statuses, 'empty' => ___('All')));?>
            <?php echo $this->Form->input('fltr_objdt__name', array('label' => ___('Name')));?>
            <?php echo $this->Form->input('fltr_objdt__email', array('label' => ___('Email')));?>
            <?php echo $this->Form->input('fltr_objdt__phone', array('label' => ___('Phone')));?>
            <?php echo $this->Form->input('fltr_objdt__address', array('label' => ___('Address')));?>
            <?php echo $this->Form->input('fltr_eq__payment', array('label' => ___('Payment'), 'options' => $payments, 'empty' => ___('All')));?>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Search'), array('type' => 'submit', 'class' => 'button primary'));?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="grid_12">
    <div class="nw-table">
        <form action="<?php echo $this->Html->url(array('action' => 'table_actions'))?>" method="POST">
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
            </div>
        </div>
        <div class="nw-table-content pd">
            <?php if(!empty($items)):?>
            <table>
                <thead><tr>
                    <th style="width: 25px;"><input type="checkbox" class="nw-table-check" /></th>
                	<th style="text-align: left;"><?php echo $this->Paginator->sort('id', ___('Order'));?></th>
                    <?php if(Configure::read('CMS.settings.shop.orders_show') != '1'):?><th style="width: 200px; text-align: left;"><?php ___e('Shipping')?></th><?php endif;?>
                    <th colspan="2" style="width: 100px;"><?php ___e('Total')?></th>
                    <th colspan="3" style="width: 250px;"><?php ___e('Status')?></th>
                    <th style="width: 100px;"><?php echo $this->Paginator->sort('created', ___('Created'));?></th>
                    <th style="width: 100px;"><?php ___e('Modified')?></th>
                    <th style="width: 100px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></td>
                		<td style="text-align: left;">
                            <div style="line-height: 20px;">#<?php e(str_pad($item[$ws_model]['id'], 5, "0", STR_PAD_LEFT))?>: <b style="font-weight: bold;"><?php et($item[$ws_model]['data']['data_checkout']['name'] . ' ' . $item[$ws_model]['data']['data_checkout']['lname'], 50)?></b>, <span style="font-style: italic; font-size: 95%;"><?php et($item[$ws_model]['data']['data_checkout']['phone'], 50)?></span></div>
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
                                <div title="<?php eth($item[$ws_model]['data']['data_shipping']['street'] . (!empty($item[$ws_model]['data']['data_shipping']['appartment']) ? ', ' . $item[$ws_model]['data']['data_shipping']['appartment'] : null), 200)?>" style="font-size: 90%; line-height: 17px;">
                                    <?php if(!empty($zones_list[$item[$ws_model]['data']['data_shipping']['zone_id']])) e($zones_parents[$zones_list[$item[$ws_model]['data']['data_shipping']['zone_id']]['ObjItemTree']['parent_id']] . ', ' . $zones_list[$item[$ws_model]['data']['data_shipping']['zone_id']]['ObjItemTree']['title'])?>
                                </div>
                            <?php endif;?>
                            <div title="<?php eth(___($item['ModOrderItem']['lifting'][0]['title']) . (!empty($item['ModOrderItem']['lifting'][0]['ext']) ? ', ' . ___('Floor') . ': ' . $item['ModOrderItem']['lifting'][0]['ext'] : null), 200)?>" style="font-size: 90%; line-height: 17px;">
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
                        <td><?php echo $this->Form->input('ModOrder.paystatus', array('disabled' => (!empty($item[$ws_model]['transaction_id']) ? true : false), 'label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_paystatus', $item[$ws_model]['id'])), 'type' => 'select', 'options' => $sys_order_pay_statuses, 'value' => $item[$ws_model]['paystatus']));?></td>
                        <td><?php echo $this->Form->input('ModOrder.onstatus', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_onstatus', $item[$ws_model]['id'])), 'type' => 'select', 'options' => $sys_order_statuses, 'value' => $item[$ws_model]['onstatus']));?></td>
                        <td><?php echo $this->Form->input('ModOrder.shipstatus', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_shipstatus', $item[$ws_model]['id'])), 'type' => 'select', 'options' => $sys_order_ship_statuses, 'value' => $item[$ws_model]['shipstatus']));?></td>
                        <td style="text-align: center;">
                            <div style="line-height: 17px;"><?php e(date_stl_1($item[$ws_model]['created']))?></div>
                            <div style="line-height: 17px; font-size: 90%;"><?php eth($item['Operator']['username'], 50)?></div>
                        </td>
                        <td style="text-align: center;">
                            <div style="line-height: 17px;"><?php e(date_stl_1($item['ModOrderAction'][0]['created']))?></div>
                            <div style="line-height: 17px; font-size: 90%;"><?php eth($item['ModOrderAction'][0]['Operator']['username'], 50)?></div>
                        </td>
                		<td style="text-align: center;">
                            <?php echo $this->Html->link('', array('action' => 'print', $item[$ws_model]['id']), array('class' => 'ico ico_print', 'target' => '_blank', 'title' => ___('Print'))); ?>
                            &nbsp;
                            <?php echo $this->Html->link('', array('action' => 'view', $item[$ws_model]['id']), array('class' => 'ico ico_info ajx_win', 'title' => ___('Details'))); ?>
                            &nbsp;
                            <?php echo $this->Html->link('', array('action' => 'edit', $item[$ws_model]['id']), array('class' => 'ico ico_edit', 'title' => ___('Edit'))); ?>
                            &nbsp;
                            <?php echo $this->Html->link('', array('action' => 'delete', $item[$ws_model]['id']), array('class' => 'ico ico_del', 'title' => ___('Delete'), 'confirm' => ___('Confirm your action.'))); ?>
                        </td>
                	</tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else:?>
                <?php echo $this->element('/admin/no_results')?>
            <?php endif;?>
        </div>
        <div class="nw-table-footer">
            <div class="fl">
                <select class="button-select" name="data[table-action]">
                    <option><?php ___e('Choose an action')?>...</option>
                    <option value="remove"><?php ___e('Remove')?></option>
                </select>
                <input type="submit" value="<?php ___e('Apply to selected')?>" class="button">
            </div>
            <div class="fr">
                <?php echo $this->element('/admin/pages')?>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="clear"></div>

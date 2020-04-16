<?php $this->extend('layout');?>

 <table class="table table-bordered table-hover">
    <thead>
       <tr>
          <td class="text-left" colspan="2"><?php ___e('Order Details')?></td>
       </tr>
    </thead>
    <tbody>
       <tr>
          <td class="text-left" style="width: 50%;">              
             <b><?php ___e('Order ID')?>:</b> #<?php e(str_pad($order['ModOrder']['id'], 5, "0", STR_PAD_LEFT))?><br />
             <b><?php ___e('Date Added')?>:</b> <?php e(date("d.m.Y", strtotime($order['ModOrder']['created'])))?>
          </td>
          <td class="text-left">              
             <b><?php ___e('Payment Method')?>:</b> <?php eth((!empty($payments[$order['ModOrder']['data']['data_payment']['payment']]) ? $payments[$order['ModOrder']['data']['data_payment']['payment']] : $order['ModOrder']['payment']))?>
             <br />
             <b><?php ___e('Shipping Method')?>:</b> <?php eth((!empty($shippings[$order['ModOrder']['data']['data_shipping']['shipping']]) ? $shippings[$order['ModOrder']['data']['data_shipping']['shipping']] : $order['ModOrder']['shipping']))?>           
          </td>
       </tr>
    </tbody>
 </table>
 <table class="table table-bordered table-hover">
    <thead>
       <tr>
          <td class="text-left" style="width: 50%;"><?php ___e('Contacts')?></td>
          <td class="text-left"><?php ___e('Shipping')?></td>
       </tr>
    </thead>
    <tbody>
       <tr>
          <td class="text-left">
            <?php $base_fields = array('name' => ___('Name'), 'phone' => ___('Phone'), 'phone_alt' => ___('Phone 2'), 'email' => ___('Email'))?>
            <?php foreach($base_fields as $key => $val) if(!empty($order['ModOrder']['data']['data_checkout'][$key])):?>
                <?php e($val)?>: <?php e($order['ModOrder']['data']['data_checkout'][$key])?><br />
            <?php endif;?>
            <?php $base_fields = array('jur_company' => ___('Company name'), 'jur_tax' => ___('VAT code'), 'jur_iban' => ___('IBAN'))?>
            <?php foreach($base_fields as $key => $val) if(!empty($order['ModOrder']['data']['data_payment'][$key])):?>
                <?php e($val)?>: <?php e($order['ModOrder']['data']['data_payment'][$key])?><br />
            <?php endif;?>
          </td>
          <td class="text-left">
            <?php if(!empty($order['ModOrder']['data']['data_shipping']['street'])):?>
            <?php if(!empty($zones_list[$order['ModOrder']['data']['data_shipping']['zone_id']])) e($zones_parents[$zones_list[$order['ModOrder']['data']['data_shipping']['zone_id']]['ObjItemTree']['parent_id']] . ', ' . $zones_list[$order['ModOrder']['data']['data_shipping']['zone_id']]['ObjItemTree']['title'] . '<br>')?>
            <?php $base_fields = array('street' => ___('Street'), 'appartment' => ___('Appartment'), 'floor' => ___('Floor'))?>
            <?php foreach($base_fields as $key => $val) if(!empty($order['ModOrder']['data']['data_shipping'][$key])):?>
                <?php e($val)?>: <?php e($order['ModOrder']['data']['data_shipping'][$key])?><br />
            <?php endif;?>
            <?php endif;?>
          </td>
       </tr>
    </tbody>
 </table>
 <div class="table-responsive">
    <table class="table table-bordered table-hover">
       <thead>
          <tr>
             <td class="text-left"><?php ___e('Product Name')?></td>
             <td class="text-left"><?php ___e('Weight')?></td>
             <td class="text-right"><?php ___e('Quantity')?></td>
             <td class="text-right"><?php ___e('Price')?></td>
             <td class="text-right"><?php ___e('Total')?></td>
             <td style="width: 20px;"></td>
          </tr>
       </thead>
       <tbody>
          <?php foreach($order['ModOrderItem']['item'] as $_item):?>
          <tr>
             <td class="text-left">
                <?php e($_item['title'])?>
             </td>
             <td class="text-left"><?php e(($_item['weight'] > 0 ? $_item['weight'] . ' ' . 'Kg/buc' : null))?></td>
             <td class="text-right"><?php e($_item['quantity'])?></td>
             <td class="text-right"><?php e($_item['price'])?> <?php e(!empty($_item['price']) ? $_item['currency'] : '')?></td>
             <td class="text-right"><?php e($_item['price_total'])?> <?php e(!empty($_item['price_total']) ? $_item['currency'] : '')?></td>
             <td class="text-right" style="white-space: nowrap;">                
                <a href="/shop/basket/add/<?php e($_item['item_id'])?>/0/1" data-toggle="tooltip" title="<?php ___e('Reorder')?>" class="btn btn-primary"><?php ___e('Reorder')?></a>
             </td>
          </tr>
          <?php endforeach;?>
       </tbody>
       <tfoot>
          <?php foreach($order['ModOrderItem'] as $_tp => $_items) if($_tp != 'item') foreach($_items as $_item):?>
          <tr>
             <td colspan="1"></td>
             <td colspan="3" class="text-right"><b><?php ___e($_item['title'])?></b></td>
             <td class="text-right"><?php e($_item['price'])?> <?php e(!empty($_item['price']) ? $_item['currency'] : '')?></td>
             <td></td>
          </tr>
          <?php endforeach;?>
          <tr>
             <td colspan="1"></td>
             <td colspan="3" class="text-right"><b><?php ___e('Total')?></b></td>
             <td class="text-right"><?php e($order['ModOrder']['price'])?> <?php e($order['ModOrder']['currency'])?></td>
             <td></td>
          </tr>
       </tfoot>
    </table>
 </div>
 <h3><?php ___e('Order History')?></h3>
 <div class="table-responsive">
    <table class="table table-bordered table-hover">
       <thead>
          <tr>
             <td class="text-left"><?php ___e('Date Added')?></td>
             <td class="text-left"><?php ___e('Order Status')?></td>
             <td class="text-left"><?php ___e('Comment')?></td>
          </tr>
       </thead>
       <tbody>
          <?php foreach($order['ModOrderAction'] as $action):?>
          <?php if($action['type'] != 'onstatus') continue;?>
          <tr>
             <td class="text-left"><?php e(date("d.m.Y H:i", strtotime($action['created'])))?></td>
             <td class="text-left"><?php e($sys_order_statuses[$action['data']['id']])?></td>
             <td class="text-left"><?php eth($action['message'], 100)?></td>
          </tr>
          <?php endforeach;?>
       </tbody>
    </table>
 </div>

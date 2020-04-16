<?php if(!empty($cfg['shop']['mail_header'])):?>
    <?php e_mail($cfg['shop']['mail_header'])?>
    
    <div>&nbsp;</div>
    <table style="width: 800px;">
        <tr>
            <td style="width: 45%; vertical-align: top;">
                <?php if(!empty($order['ModOrder']['data']['data_shipping']['street'])):?>

                <b><?php ___e('Delivery Adress')?>:</b><br />

                <?php $base_fields = array('name' => ___('Name'), 'phone' => ___('Phone'))?>
                <?php foreach($base_fields as $key => $val) if(!empty($order['ModOrder']['data']['data_checkout'][$key])):?>
                    <?php e($order['ModOrder']['data']['data_checkout'][$key])?><br />
                <?php endif;?>

                <?php $base_fields = array('street' => ___('Street'), 'appartment' => ___('Appartment'))?>
                <?php foreach($base_fields as $key => $val) if(!empty($order['ModOrder']['data']['data_shipping'][$key])):?>
                    <?php e($val)?>: <?php e($order['ModOrder']['data']['data_shipping'][$key])?><br />
                <?php endif;?>

                <?php endif;?>
            </td>
            <td style="width: 45%; vertical-align: top;">
                 <b><?php ___e('Payment Method')?>:</b> <?php eth((!empty($payments[$order['ModOrder']['data']['data_payment']['payment']]) ? $payments[$order['ModOrder']['data']['data_payment']['payment']] : $order['ModOrder']['payment']))?>
                 <br />
                 <b><?php ___e('Shipping Method')?>:</b> <?php eth((!empty($shippings[$order['ModOrder']['data']['data_shipping']['shipping']]) ? $shippings[$order['ModOrder']['data']['data_shipping']['shipping']] : $order['ModOrder']['shipping']))?>           
            </td>
        </tr>
    </table>
    <div>&nbsp;</div>
    <table cellspacing="2" cellpadding="4" style="width: 800px; background: #FFFFFF;">
       <thead>
          <tr>
             <th style="background: #CCCCCC;"><?php ___e('Product Name')?></th>
             <th style="background: #CCCCCC; text-align: right; width: 100px;"><?php ___e('Weight')?></th>
             <th style="background: #CCCCCC; text-align: center; width: 100px;"><?php ___e('Quantity')?></th>
             <th style="background: #CCCCCC; text-align: right; width: 150px;"><?php ___e('Price')?></th>
             <th style="background: #CCCCCC; text-align: right; width: 150px;"><?php ___e('Total')?></th>
          </tr>
       </thead>
       <tbody>
          <?php foreach($order['ModOrderItem']['item'] as $_item):?>
          <tr>
             <td style="background: #EEEEEE;">
                <?php e($_item['title'])?>
             </td>
             <td style="background: #EEEEEE; text-align: right;"><?php e(($_item['weight'] > 0 ? $_item['weight'] . ' ' . 'Kg/buc' : null))?></td>
             <td style="background: #EEEEEE; text-align: center;"><?php e($_item['quantity'])?></td>
             <td style="background: #EEEEEE; text-align: right;"><?php e($_item['price'])?> <?php e(!empty($_item['price']) ? $_item['currency'] : '')?></td>
             <td style="background: #EEEEEE; text-align: right;"><?php e($_item['price_total'])?> <?php e(!empty($_item['price_total']) ? $_item['currency'] : '')?></td>
          </tr>
          <?php endforeach;?>
       </tbody>
       <tbody>
          <?php foreach($order['ModOrderItem'] as $_tp => $_items) if($_tp != 'item') foreach($_items as $_item):?>
          <tr>
             <td style="background: #EEEEEE; text-align: right;" colspan="4"><b><?php ___e($_item['title'])?></b></td>
             <td style="background: #EEEEEE; text-align: right;"><?php e($_item['price'])?> <?php e(!empty($_item['price']) ? $_item['currency'] : '')?></td>
          </tr>
          <?php endforeach;?>
          <tr>
             <td style="background: #EEEEEE; text-align: right;" colspan="4"><b><?php ___e('Total')?></b></td>
             <td style="background: #EEEEEE; text-align: right;"><?php e($order['ModOrder']['price'])?> <?php e($order['ModOrder']['currency'])?></td>
          </tr>
       </tbody>
    </table>
    
    <?php e_mail($cfg['shop']['mail_footer'])?>
<?php else:?>
    <?php ___e('You have new order with ID')?>: <?php e($order_id)?>
<?php endif;?>

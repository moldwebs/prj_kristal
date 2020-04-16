<?php $this->extend('layout');?>

<?php if($user_bonuses > 0):?>
<div>
    <?php ___e('You have %s bonuses.', $user_bonuses)?>
</div>
<div>&nbsp;</div>
<?php endif;?>

<?php if(!empty($items)):?>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
       <thead>
          <tr>
             <td class="text-right"><?php ___e('Order ID')?></td>
             <td class="text-left"><?php ___e('Order Status')?></td>
             <td class="text-left"><?php ___e('Date Added')?></td>
             <td class="text-right"><?php ___e('No. of Products')?></td>
             <td class="text-left"><?php ___e('Customer')?></td>
             <td class="text-right"><?php ___e('Total')?></td>
             <td></td>
          </tr>
       </thead>
       <tbody>
          <?php foreach($items as $key => $item):?>
          <tr>
             <td class="text-right">#<?php e(str_pad($item['ModOrder']['id'], 5, "0", STR_PAD_LEFT))?></td>
             <td class="text-left"><?php e($sys_order_statuses[$item['ModOrder']['onstatus']])?>, <?php e($sys_order_pay_statuses[$item['ModOrder']['paystatus']])?></td>
             <td class="text-left"><?php e(date("d.m.Y", strtotime($item['ModOrder']['created'])))?></td>
             <td class="text-right"><?php e($item['ModOrder']['quantity'])?></td>
             <td class="text-left"><?php e($item['ModOrder']['data']['data_checkout']['name'])?></td>
             <td class="text-right"><?php e($item['ModOrder']['price'])?> <?php e($item['ModOrder']['currency'])?></td>
             <td class="text-right"><a href="/shop/order/view/<?php e($item['ModOrder']['id'])?>" data-toggle="tooltip" title="<?php ___e('View')?>" class="btn btn-primary"><?php ___e('View')?></a></td>
          </tr>
          <?php endforeach;?>
       </tbody>
    </table>
 </div>
<?php echo $this->telement('pages')?>

<?php else:?>
    <div class="no_results"><?php ___e('No results')?></div>
<?php endif;?>

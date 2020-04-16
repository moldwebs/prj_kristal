<?php $ws_model = 'ModTransaction'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Filters')?></div>
        <div class="nw-table-content pd">
            <?php echo $this->Form->input('fltr_eq__extra_id', array('label' => ___('Item ID'), 'type' => 'text'));?>
            <?php echo $this->Form->input('fltr_eq__ext_id', array('label' => ___('ID'), 'type' => 'text'));?>
            <?php echo $this->Form->input('fltr_eq__pay_type', array('label' => ___('Payment'), 'options' => $payment_types, 'empty' => ___('All')));?>
            <?php echo $this->Form->input('fltr_eq__status', array('label' => ___('Success'), 'options' => ws_yn(), 'empty' => ___('All')));?>
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
                    <th style="width: 50px;"><?php echo ___('Item ID');?></th>
                    <th style="width: 200px;"><?php echo ___('ID');?></th>
                	<th style="width: 120px;"><?php echo $this->Paginator->sort('pay_type', ___('Payment'));?></th>
                    <th style="text-align: left;"><?php echo ___('Description');?></th>
                    <th colspan="2" style="width: 100px;"><?php ___e('Total')?></th>
                    <th colspan="2" style="width: 100px;"><?php ___e('Paid')?></th>
                    <th style="width: 150px;"><?php echo $this->Paginator->sort('created', ___('Date'));?></th>
                    <th style="width: 30px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                		<td style="text-align: center;"><?php e($item[$ws_model]['extra_id'])?></td>
                		<td title="<?php e($item[$ws_model]['ext_id'])?>" style="text-align: center;"><?php e($item[$ws_model]['code'])?></td>
                        <td style="text-align: center;"><?php et($item[$ws_model]['pay_type'], 60)?></td>
                		<td><?php et($item[$ws_model]['description'], 60)?></td>
                        <td style="text-align: right;"><?php e($item[$ws_model]['amount'])?></td>
                        <td><?php e($item[$ws_model]['currency'])?></td>
                        <td title="<?php e($item[$ws_model]['paid_ext_id'])?>" style="text-align: right;"><?php e($item[$ws_model]['paid_amount'] > 0 ? $item[$ws_model]['paid_amount'] : '0')?></td>
                        <td><?php e($item[$ws_model]['currency'])?></td>
                        <td style="text-align: center;">
                            <?php e(date_stl_1($item[$ws_model]['created']))?>
                        </td>
                        <td><?php e($item[$ws_model]['status'])?></td>
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
            </div>
            <div class="fr">
                <?php echo $this->element('/admin/pages')?>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="clear"></div>

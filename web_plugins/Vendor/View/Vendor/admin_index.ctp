<?php $ws_model = 'ObjItemList'?>

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
                	<th style="text-align: left;"><?php echo $this->Paginator->sort('title', ___('Title'));?></th>
                    <th style="width: 100px;"><?php ___e('Currency')?></th>
                    <th style="width: 60px;"><?php ___e('%')?></th>
                    <th style="width: 60px;"><?php ___e('Fix')?></th>
                    <th colspan="2" style="width: 120px;"><?php ___e('Convert')?></th>
                    <th style="width: 150px;"><?php echo $this->Paginator->sort('created', ___('Date'));?></th>
                    <th style="width: 50px;"></th>
                    <th style="width: 100px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></td>
                		<td><?php et($item[$ws_model]['title'], 60)?></td>
                		<td style="text-align: center;"><?php e($item[$ws_model]['data']['col_currency'])?></td>
                		<td style="text-align: center;">
                            <?php echo $this->Form->input('coeficient', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_data_field', $item[$ws_model]['id'], 'coeficient')), 'type' => 'text', 'value' => $item[$ws_model]['data']['coeficient']));?>
                        </td>
                		<td style="text-align: center;">
                            <?php echo $this->Form->input('coeficient_fix', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_data_field', $item[$ws_model]['id'], 'coeficient_fix')), 'type' => 'text', 'value' => $item[$ws_model]['data']['coeficient_fix']));?>
                        </td>
                		<td style="text-align: right;">
                            <?php e(!empty($item[$ws_model]['data']['conv_currency']) ? Configure::read('Obj.currency')['currency'] : '')?>
                        </td>
                		<td style="text-align: center;">
                            <?php if(!empty($item[$ws_model]['data']['conv_currency'])) echo $this->Form->input('conv_rate', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_data_field', $item[$ws_model]['id'], 'conv_rate')), 'type' => 'text', 'value' => $item[$ws_model]['data']['conv_rate']));?>
                        </td>
                        <td style="text-align: center;">
                            <?php e(date_stl_1($item[$ws_model]['created']))?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $this->Form->input('order_id', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'order', $item[$ws_model]['id'])), 'type' => 'text', 'value' => $item[$ws_model]['order_id']));?>
                        </td>
                		<td style="text-align: center;">
                            <a href="/admin/vendor/vendor/compare/<?php e($item[$ws_model]['id'])?>" title="<?php ___e('Compare Pricelists')?>" class="ico ico_copy ajx_win"></a>
                            &nbsp;
                            <a href="/admin/vendor/vendor/insert/<?php e($item[$ws_model]['id'])?>" title="<?php ___e('Insert Pricelists')?>" class="ico ico_add "></a>
                            &nbsp;
                            <?php echo $this->element('/admin/item_actions', array('item' => $item, 'ws_model' => $ws_model, 'type' => '1'))?>
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

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create('Import', array('type' => 'file', 'class' => '', 'url' => array('action' => 'import')));?>
        <div class="nw-table-title">
            <div class="fl"><?php ___e('Import')?></div>
            <div class="fr">
            </div>
        </div>
        <div class="nw-table-content pd">
            <?php echo $this->Form->input('vendor_id', array('label' => ___('Vendor'), 'empty' => ___('Choose...'), 'options' => Set::combine($vendors, '{n}.ObjItemList.id', '{n}.ObjItemList.title'), 'class' => 'req'));?>
            <?php echo $this->Form->input('pricelist', array('label' => ___('Pricelist'), 'type' => 'file', 'class' => 'req'));?>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Import'), array('name' => 'saction', 'value' => '2'))?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>

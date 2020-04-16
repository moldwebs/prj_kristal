<?php $ws_model = 'ObjItemList'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <input type="hidden" name="scopefield" value="<?php e($_GET['scopefield'])?>" />
        <div class="nw-table-title"><?php ___e('Filters')?></div>
        <div class="nw-table-content pd">
            <?php echo $this->Form->input('fltr_eq__id', array('label' => ___('ID'), 'type' => 'text'));?>
            <?php echo $this->Form->input('fltr_eq__code', array('label' => ___('Code'), 'type' => 'text'));?>
            <?php if(Configure::read('PLUGIN.Vendor') == '1' && !empty($vendors)) echo $this->Form->input('fltr_reltype__vendor_code', array('label' => ___('Vendor Code'), 'type' => 'text'));?>
            <?php echo $this->Form->input('fltr_lk__title', array('label' => ___('Title')));?>
            <?php echo $this->Form->input('fltr_eq__status', array('label' => ___('Status'), 'options' => ws_vh(), 'empty' => ___('All')));?>
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
                    <th style="width: 25px;"></th>
                	<th style="text-align: left;"><?php echo $this->Paginator->sort('title', ___('Title'));?></th>
                    <th style="width: 60px;"><?php ___e('Price')?></th>
                    <th style="width: 55px;">&nbsp;</th>
                	<?php if(Configure::read('PLUGIN.Vendor') == '1' && !empty($vendors)):?><th style="width: 110px;"><?php echo ___('Vendor');?></th><?php endif;?>
                    <th style="width: 80px;"><?php echo $this->Paginator->sort('created', ___('Date'));?></th>
                    <?php if(Configure::read('CMS.settings.catalog.obj_qnt') == '1'):?><th style="width: 30px;"><?php echo $this->Paginator->sort('qnt', ___('Q'));?></th><?php endif;?>
                    <th style="width: 50px;"></th>
                    <th style="width: 120px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></td>
                		<td style="text-align: center;">
                            <?php echo $this->element('/admin/item_thumb', array('item' => $item, 'ws_model' => $ws_model))?>
                        </td>
                		<td>
                            <div><?php et($item[$ws_model]['title'], 100)?></div>
                		</td>
                        <td>
                            <?php echo $this->Form->input('ObjItemList.price', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_price', $item[$ws_model]['id'])), 'type' => 'text', 'value' => $item[$ws_model]['price']));?>
                        </td>
                        <td>
                            <?php echo $this->Form->input('ObjItemList.currency', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_currency', $item[$ws_model]['id'])), 'type' => 'select', 'options' => $currencies, 'value' => $item[$ws_model]['currency']));?>
                        </td>
                        <?php if(Configure::read('PLUGIN.Vendor') == '1' && !empty($vendors)):?>
                        <td style="text-align: center;">
                            <div style="line-height: 17px;"><?php e($vendors[key($item['RelationValue']['vendor_price'])]['ObjItemList']['title'])?></div>
                            <div style="line-height: 17px; font-size: 90%;"><?php e(str_replace(':', ' ', $item['RelationValue']['vendor_price'][key($item['RelationValue']['vendor_price'])]))?></div>
                        </td>
                        <?php endif;?>
                        <td style="text-align: center;">
                            <?php e(date_stl_1($item[$ws_model]['created']))?>
                        </td>
                        <?php if(Configure::read('CMS.settings.catalog.obj_qnt') == '1'):?>
                        <td style="text-align: center;">
                            <?php echo $this->Form->input('qnt', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'quantity', $item[$ws_model]['id'])), 'type' => 'text', 'value' => $item[$ws_model]['qnt']));?>
                        </td>
                        <?php endif;?>
                        <td style="text-align: center;">
                            <?php echo $this->Form->input('order_id', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'order', $item[$ws_model]['id'])), 'type' => 'text', 'value' => $item[$ws_model]['order_id']));?>
                        </td>
                		<td style="text-align: center;">
                            <?php echo $this->element('/admin/item_actions', array('item' => $item, 'ws_model' => $ws_model))?>
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
                    <option value="show"><?php ___e('Show')?></option>
                    <option value="hide"><?php ___e('Hide')?></option>
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

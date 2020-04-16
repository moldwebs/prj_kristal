<?php $ws_model = 'ObjItemList'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Filters')?></div>
        <div class="nw-table-content pd">
            <?php if(!empty($bases)) echo $this->Form->input('fltr_eqorrel__base_id', array('label' => ___('Category'), 'options' => $bases, 'empty' => ___('All')));?>
            <?php if(!empty($manufacturers)) echo $this->Form->input('fltr_eq__extra_2', array('label' => ___('Manufacturer'), 'options' => $manufacturers, 'empty' => ___('All')));?>
            <?php if(!empty($deposits)) echo $this->Form->input('fltr_eq__extra_3', array('label' => ___('Deposit'), 'options' => $deposits, 'empty' => ___('All')));?>
            <?php if(!empty($vendors_list)) echo $this->Form->input('fltr_relexist__vendor_code', array('label' => ___('Vendor'), 'options' => $vendors_list, 'empty' => ___('All')));?>
            <?php if(Configure::read('PLUGIN.Vendor') == '1' && !empty($vendors)) echo $this->Form->input('fltr_reltype__vendor_code', array('label' => ___('Vendor Code'), 'type' => 'text'));?>
            <?php echo $this->Form->input('fltr_eq__id', array('label' => ___('ID'), 'type' => 'text'));?>
            <?php echo $this->Form->input('fltr_eq__code', array('label' => ___('Code'), 'type' => 'text'));?>
            <?php if(!empty($types)) echo $this->Form->input('fltr_eqorrel__extra_1', array('label' => ___('Type'), 'options' => $types, 'empty' => ___('All')));?>
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
                	<?php if(!empty($deposits)):?><th style="width: 70px;"><?php echo $this->Paginator->sort('extra_3', ___('Deposit'));?></th><?php endif;?>
                	<?php if(!empty($types)):?><th style="width: 90px;"><?php echo $this->Paginator->sort('extra_1', ___('Type'));?></th><?php endif;?>
                	<?php if(!empty($bases)):?><th style="width: 110px;"><?php echo $this->Paginator->sort('base_id', ___('Category'));?></th><?php endif;?>
                	<th style="width: 60px;">&nbsp;</th>
                	<th style="width: 80px;"><?php echo $this->Paginator->sort('created', ___('Date'));?></th>
                    <?php if(Configure::read('CMS.settings.catalog.obj_qnt') == '1'):?><th style="width: 30px;"><?php echo $this->Paginator->sort('qnt', ___('Q'));?></th><?php endif;?>
                    <th style="width: 120px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<?php $icols = 0?>
                    <tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></td>
                		<td style="text-align: center;">
                            <?php echo $this->element('/admin/item_thumb', array('item' => $item, 'ws_model' => $ws_model))?>
                        </td>
                		<td>
                            <div><a target="_blank" href="<?php eurl($item[$ws_model]['alias'])?>?tkey=<?php e(TMP_KEY)?>"><?php et($item[$ws_model]['title'], 100)?></a></div>
                		</td>
                        <td>
                            <?php echo $this->Form->input('ObjItemList.price', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_price', $item[$ws_model]['id'])), 'type' => 'text', 'value' => $item[$ws_model]['price']));?>
                        </td>
                        <td>
                            <?php echo $this->Form->input('ObjItemList.currency', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_currency', $item[$ws_model]['id'])), 'type' => 'select', 'options' => $currencies, 'value' => $item[$ws_model]['currency']));?>
                        </td>
                        
                        <?php if(!empty($deposits)):?>
                        <?php $icols++?>
                        <td style="text-align: center;">
                            <?php echo $this->Form->input('ObjItemList.extra_3', array('label' => false, 'div' => false, 'empty' => '', 'ajx_change' => $this->Html->url(array('action' => 'set_deposit', $item[$ws_model]['id'])), 'type' => 'select', 'options' => $deposits, 'value' => $item[$ws_model]['extra_3']));?>
                        </td>
                        <?php endif;?>

                        <?php if(!empty($types)):?>
                        <?php $icols++?>
                        <td>
                            <?php echo $this->element('/admin/item_type', array('item' => $item, 'ws_model' => $ws_model))?>
                        </td>
                        <?php endif;?>
                        
                        <?php if(!empty($bases) || !empty($manufacturers)):?>
                        <?php $icols++?>
                        <td style="text-align: center;">
                            <div style="line-height: 17px;"><?php et($item['ObjItemTree']['title'], 17)?></div>
                            <div style="line-height: 17px; font-size: 90%;"><?php et($manufacturers[$item[$ws_model]['extra_2']], 17)?></div>
                        </td>
                        <?php endif;?>
                        
                        <?php $icols++?>
                        <td style="text-align: center;">
                            <div style="line-height: 17px; font-size: 90%;"><?php if($item['ObjItemList']['data']['weight'] > 0) e($item['ObjItemList']['data']['weight'] . ' ' . ___('Kg'))?></div>
                            <div style="line-height: 17px; font-size: 90%;"><?php if($item['ObjItemList']['data']['wrnt'] > 0) e($item['ObjItemList']['data']['wrnt'] . ' ' . ___('month(s)'))?></div>
                        </td>
                        
                        <td style="text-align: center;">
                            <div style="line-height: 17px;"><?php e(date_stl_1($item[$ws_model]['created']))?></div>
                            <div style="line-height: 17px; font-size: 90%;"><?php e($item['User']['username'])?></div>
                        </td>
                        
                        <?php if(Configure::read('CMS.settings.catalog.obj_qnt') == '1'):?>
                        <?php $icols++?>
                        <td style="text-align: center;">
                            <?php echo $this->Form->input('qnt', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'quantity', $item[$ws_model]['id'])), 'type' => 'text', 'value' => $item[$ws_model]['qnt']));?>
                        </td>
                        <?php endif;?>
                		<td style="text-align: center;">
                            <?php if(Configure::read('PLUGIN.Vendor') == '1' && !empty($vendors) && 1==1):?>
                            <a onclick="if($('#vendor_<?php e($item[$ws_model]['id'])?>').html() != ''){$('#vendor_<?php e($item[$ws_model]['id'])?>').parent().toggle();} else {$('#vendor_<?php e($item[$ws_model]['id'])?>').load('/admin/vendor/vendor/item_price/<?php e($item[$ws_model]['id'])?>', function(){$(this).parent().toggle();});}" title="<?php ___e('Vendors')?>" class="ico ico_pricelist"></a>
                            &nbsp;
                            <?php endif;?>
                            <?php if(Configure::read('CMS.settings.catalog.obj_qnt') == '1') echo $this->Html->link('', array('action' => 'makeorder', $item[$ws_model]['id']), array('class' => 'ico ico_basket ajx_win', 'title' => ___('Make order'))) . '&nbsp;&nbsp;'; ?>
                            <?php if(Configure::read('CMS.settings.catalog.obj_combinations') == '1') echo $this->Html->link('', array('controller' => 'combination', 'action' => 'index', '?' => array('scopefield' => $item[$ws_model]['id'])), array('class' => 'ico ' . (!empty($item[$ws_model]['combinations']) ? 'ico_node-select-all' : 'ico_node-select'), 'title' => ___('Combinations'))) . '&nbsp;&nbsp;'; ?>
                            <?php echo $this->element('/admin/item_actions', array('item' => $item, 'ws_model' => $ws_model))?>
                        </td>
                	</tr>
                    <?php if(Configure::read('PLUGIN.Vendor') == '1' && !empty($vendors) && 1==1):?>
                    <tr class="not_nth" style="display: none;">
                        <td></td><td></td>
                        <td id="vendor_<?php e($item[$ws_model]['id'])?>" colspan="<?php e(5 + $icols)?>"></td>
                    </tr>
                    <?php endif;?>
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

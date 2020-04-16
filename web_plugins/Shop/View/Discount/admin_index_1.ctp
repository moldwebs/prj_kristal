<?php $ws_model = 'ModDiscount'?>

<div class="grid_12">
    <div class="nw-table">
        <form action="<?php echo $this->Html->url(array('action' => 'table_actions'))?>" method="POST">
        <div class="nw-table-title"><?php echo $page_title?></div>
        <div class="nw-table-content pd">
            <?php if(!empty($items)):?>
            <table>
                <thead><tr>
                    <th style="width: 25px;"><input type="checkbox" class="nw-table-check" /></th>
                	<th style="text-align: left;"><?php echo $this->Paginator->sort('title', ___('Title'));?></th>
                	<th style="width: 170px;"><?php echo $this->Paginator->sort('expire', ___('Valid to'));?></th>
                	<th style="width: 120px;"><?php echo $this->Paginator->sort('code', ___('Code'));?></th>
                	<th style="width: 170px;"><?php echo $this->Paginator->sort('use_type', ___('Qnt. of use'));?></th>
                	<th style="width: 70px;"><?php echo ___('Item ID');?></th>
                	<th style="width: 70px; text-align: right;"><?php ___e('Discount')?></th>
                    <th style="width: 40px;"></th>
                    <th style="width: 70px;"><?php echo ___('Used');?></th>
                    <th style="width: 60px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></td>
                        <td>
                            <?php e($item[$ws_model]['title'])?>
                		</td>
                        <td style="text-align: center;">
                            <?php e(date_stl_1($item[$ws_model]['expire']))?>
                		</td>
                        <td style="text-align: center;">
                            <?php e($item[$ws_model]['code'])?>
                		</td>
                        <td style="text-align: center;">
                            <?php e($item[$ws_model]['use_type'])?>
                		</td>
                        <td style="text-align: center;">
                            <?php e($item[$ws_model]['data']['item_id'])?>
                		</td>
                        <td style="text-align: right;">
                            <?php e($item[$ws_model]['discount'])?>
                		</td>
                        <td>
                            <?php e($discount_types[$item[$ws_model]['discount_type']])?>
                		</td>
                        <td style="text-align: center;">
                            <?php e($item[$ws_model]['used'] > 0 ? $item[$ws_model]['used'] : '0')?>
                        </td>
                		<td style="text-align: center;">
                            <?php echo $this->Html->link(___('', true), array('action' => 'edit_1', $item[$ws_model]['id']), array('class' => 'ico ico_edit', 'title' => ___('Edit', true))); ?>
                            <?php echo $this->Html->link(___('', true), array('action' => 'delete', $item[$ws_model]['id']), array('class' => 'ico ico_del', 'title' => ___('Delete', true), 'confirm' => ___('Confirm your action.'))); ?>            
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
        <?php echo $this->Form->create('ModDiscount', array('type' => 'file', 'class' => 'ajx_validate', 'url' => array('action' => 'edit_1')));?>
        <div class="nw-table-title">
            <div class="fl"><?php ___e('Create')?></div>
            <div class="fr">
                <?php echo $this->element('/admin/edit_langs')?>
            </div>
        </div>
        <div class="nw-table-content pd">
            <?php echo $this->Form->input('type', array('type' => 'hidden', 'value' => '1'));?>
            <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
            <?php echo $this->Form->input('code', array('label' => ___('Code'), 'class' => 'req'));?>
            <div class="input text">
                <?php echo $this->Form->input('discount', array('label' => ___('Discount'), 'class' => 'req', 'div' => false, 'style' => 'width: 70%'));?>
                <?php echo $this->Form->input('discount_type', array('options' => $discount_types, 'class' => 'req', 'empty' => false, 'label' => false, 'div' => false, 'style' => 'width: 25%'));?>
            </div>
            <?php //echo $this->Form->input('use_type', array('options' => $use_types, 'empty' => false, 'label' => ___('Type'), 'class' => 'req'));?>
            <?php echo $this->Form->input('use_type', array('empty' => false, 'label' => ___('Qnt. of use')));?>
            <?php echo $this->Form->input('expire', array('class' => 'ui_date', 'type' => 'text', 'label' => ___('Valid to')));?>
            <?php echo $this->Form->input('ModDiscount.data.item_id', array('type' => 'text', 'label' => ___('Item ID')));?>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Create'), array('name' => 'saction', 'value' => '2'))?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>
<?php $ws_model = 'ObjItemList'?>

<div class="grid_12">
    <div class="nw-table">
        <form action="<?php echo $this->Html->url(array('action' => 'value_table_actions'))?>" method="POST">
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
                    <th style="width: 30px;"></th>
                    <th style="width: 80px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></td>
                		<td>
                            <?php et($item[$ws_model]['title'], 60)?>
                        </td>
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

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'file', 'class' => 'ajx_validate', 'url' => array('action' => 'edit')));?>
        <div class="nw-table-title">
            <div class="fl"><?php ___e('Create')?></div>
            <div class="fr">
                <?php echo $this->element('/admin/edit_langs')?>
            </div>
        </div>
        <div class="nw-table-content pd">
            <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
            <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Create'), array('name' => 'saction', 'value' => '2'))?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>

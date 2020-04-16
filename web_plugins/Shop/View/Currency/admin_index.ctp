<?php $ws_model = 'ModCurrency'?>

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
                    <th style="text-align: left; width: 100px;"><?php echo $this->Paginator->sort('title', ___('Short title'));?></th>
                	<th style="text-align: left;"><?php ___e('Long title')?></th>
                    <th style="width: 100px;"><?php ___e('Currency')?></th>
                    <th style="width: 100px;"><?php ___e('Value')?></th>
                    <th style="width: 100px;"><?php ___e('Default')?></th>
                    <th style="width: 80px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></td>
                		<td><?php et($item[$ws_model]['title'], 60)?></td>
                		<td><?php et($item[$ws_model]['long_title'], 60)?></td>
                        <td style="text-align: center;"><?php et($item[$ws_model]['currency'], 60)?></td>
                        <td style="text-align: center;"><?php et($item[$ws_model]['value'], 60)?></td>
                        <td style="text-align: center;"><input name="data[default]" value="1" type="radio" <?php if($item[$ws_model]['is_default'] == '1') e('checked="checked"')?> ajx_change="<?php e($this->Html->url(array('action' => 'set_default', $item[$ws_model]['id'])))?>" /></td>
                		<td style="text-align: center;">
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

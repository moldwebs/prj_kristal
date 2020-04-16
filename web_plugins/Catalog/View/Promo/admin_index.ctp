<?php $ws_model = 'ObjItemList'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Filters')?></div>
        <div class="nw-table-content pd">
            <?php if(!empty($bases)) echo $this->Form->input('fltr_eq__base_id', array('label' => ___('Category'), 'options' => $bases, 'empty' => ___('All')));?>
            <?php if(!empty($manufacturers)) echo $this->Form->input('fltr_eq__extra_2', array('label' => ___('Manufacturer'), 'options' => $manufacturers, 'empty' => ___('All')));?>
            <?php if(!empty($types)) echo $this->Form->input('fltr_eq__extra_1', array('label' => ___('Type'), 'options' => $types, 'empty' => ___('All')));?>
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
                    <th style="width: 120px;"><?php echo $this->Paginator->sort('base_id', ___('Category'));?></th>
                    <th style="width: 120px;"><?php echo $this->Paginator->sort('extra_2', ___('Manufacturer'));?></th>
                    <th style="width: 120px;"><?php echo $this->Paginator->sort('extra_1', ___('Type'));?></th>
                    <th style="width: 120px;"><?php echo ___('Dates');?></th>
                    <th style="width: 120px;"><?php echo $this->Paginator->sort('created', ___('Date'));?></th>
                    <th style="width: 100px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></td>
                        <td style="text-align: center;">
                            <?php echo $this->element('/admin/item_thumb', array('item' => $item, 'ws_model' => $ws_model))?>
                        </td>
                		<td>
                            <div><a target="_blank" href="<?php eurl($item[$ws_model]['alias'])?>"><?php et($item[$ws_model]['title'], 100)?></a></div>
                        </td>
                        <td style="text-align: center;">
                            <?php et($item['ObjItemTree']['title'], 30)?>
                        </td>
                        <td style="text-align: center;">
                            <?php et($manufacturers[$item[$ws_model]['extra_2']], 30)?>
                        </td>
                        <td style="text-align: center;">
                            <?php et($types[$item[$ws_model]['extra_1']], 30)?>
                        </td>
                        <td style="text-align: center;">
                            <?php e(($item[$ws_model]['date']))?>
                        </td>
                        <td style="text-align: center;">
                            <?php e(date_stl_1($item[$ws_model]['created']))?>
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

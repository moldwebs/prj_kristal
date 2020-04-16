<?php $ws_model = 'ObjOptReview'?>
<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Filters')?></div>
        <div class="nw-table-content pd">
            <?php if(!empty($objects)) echo $this->Form->input('fltr_eq__item_id', array('label' => ___('Item'), 'options' => $objects, 'empty' => ___('All')));?>
            <?php echo $this->Form->input('fltr_lk__username', array('label' => ___('Name')));?>
            <?php echo $this->Form->input('fltr_lk__usermail', array('label' => ___('Email')));?>
            <?php echo $this->Form->input('fltr_lk__comment', array('label' => ___('Review')));?>
            <?php echo $this->Form->input('fltr_lk__userip', array('label' => ___('Ip')));?>
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
                    <th style="width: 50px;"></th>
                	<th style="text-align: left;"><?php echo ___('Review');?></th>
                	<th style="width: 150px; text-align: left;"><?php echo ___('Name');?></th>
                	<th style="width: 150px; text-align: left;"><?php echo ___('Email');?></th>
                    <th style="width: 100px;"><?php echo $this->Paginator->sort('created', ___('Date'));?></th>
                    <th style="width: 100px;"></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></td>
                		<?php if(strpos($item[$ws_model]['tid'], 'content')):?>
                            <td style="text-align: center;" title="<?php e($item['ObjItemTree']['title'])?>"><a target="_blank" href="<?php e($this->Html->url(aurl($item['ObjItemTree'], 'ObjItemTree')))?>"><?php e($item['ObjItemTree']['id'])?></a></td>
                        <?php else:?>
                            <td style="text-align: center;" title="<?php e($item['ObjItemList']['title'])?>"><a target="_blank" href="<?php e($this->Html->url(aurl($item['ObjItemList'], 'ObjItemList')))?>"><?php e($item['ObjItemList']['id'])?></a></td>
                        <?php endif;?>
                        <td title="<?php e($item[$ws_model]['comment'])?>"><?php et($item[$ws_model]['comment'], 60)?></td>
                		<td title="<?php e($item[$ws_model]['userip'])?>"><?php et(!empty($item[$ws_model]['username']) ? $item[$ws_model]['username'] : $item['User']['username'], 30)?></td>
                		<td title="<?php e(!empty($item[$ws_model]['usermail']) ? $item[$ws_model]['usermail'] : $item['User']['usermail'])?>"><?php et(!empty($item[$ws_model]['usermail']) ? $item[$ws_model]['usermail'] : $item['User']['usermail'], 30)?></td>
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

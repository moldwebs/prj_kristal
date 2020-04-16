<?php $ws_model = 'User'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Filters')?></div>
        <div class="nw-table-content pd">
            <?php echo $this->Form->input('fltr_lk__username', array('label' => ___('Name')));?>
            <?php echo $this->Form->input('fltr_lk__usermail', array('label' => ___('Email')));?>
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
                	<th style="text-align: left;"><?php echo $this->Paginator->sort('username', ___('Name'));?></th>
                	<th style="width: 250px; text-align: left;"><?php echo $this->Paginator->sort('usermail', ___('Email'));?></th>
                	<th style="width: 200px;"><?php echo ___('Group');?></th>
                	<th style="width: 150px;"><?php echo $this->Paginator->sort('created', ___('Date'));?></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<tr>
                        <td style="text-align: center;"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></td>
                		<td style="text-align: center;">
                            <?php echo $this->element('/admin/item_thumb', array('item' => $item, 'ws_model' => $ws_model))?>
                        </td>
                		<td><?php et($item[$ws_model]['username'], 60)?></td>
                		<td><?php et($item[$ws_model]['usermail'], 60)?></td>
                		<td style="text-align: center;">
                            <?php echo $this->Form->input('group', array('options' => $groups, 'empty' => '', 'div' => false, 'label' => false, 'selected' => reset($item['Relation']['customer']), 'onchange' => "$.get('/admin/shop/customer/customer_group/{$item[$ws_model]['id']}/' + this.value);"));?>
                        </td>
                        <td style="text-align: center;">
                            <?php e(date_stl_1($item[$ws_model]['created']))?>
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
            </div>
            <div class="fr">
                <?php echo $this->element('/admin/pages')?>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="clear"></div>

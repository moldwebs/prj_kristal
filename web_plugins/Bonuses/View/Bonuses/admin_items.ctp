<?php $ws_model = 'ObjItemList'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Filters')?></div>
        <div class="nw-table-content pd">
            <?php if(!empty($bases)) echo $this->Form->input('fltr_eqorrel__base_id', array('label' => ___('Category'), 'options' => $bases, 'empty' => ___('All')));?>
            <?php if(!empty($manufacturers)) echo $this->Form->input('fltr_eq__extra_2', array('label' => ___('Manufacturer'), 'options' => $manufacturers, 'empty' => ___('All')));?>
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
                    <th style="width: 25px;"></th>
                	<th style="text-align: left;"><?php echo $this->Paginator->sort('title', ___('Title'));?></th>
                    <th style="width: 60px; text-align: right;"><?php ___e('Price')?></th>
                    <th style="width: 55px;">&nbsp;</th>
                    <?php if(!empty($manufacturers)):?><th style="width: 110px;"><?php echo $this->Paginator->sort('extra_2', ___('Manufacturer'));?></th><?php endif;?>
                	<?php if(!empty($bases)):?><th style="width: 110px;"><?php echo $this->Paginator->sort('base_id', ___('Category'));?></th><?php endif;?>
                	<th style="width: 80px;"><?php echo $this->Paginator->sort('created', ___('Date'));?></th>
                    <th style="width: 80px;">%</th>
                    <th style="width: 80px;"><?php e(key(Configure::read('CMS.currency')))?></th>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                    <tr>
                		<td style="text-align: center;">
                            <?php echo $this->element('/admin/item_thumb', array('item' => $item, 'ws_model' => $ws_model))?>
                        </td>
                		<td>
                            <div><a target="_blank" href="<?php eurl($item[$ws_model]['alias'])?>"><?php et($item[$ws_model]['title'], 100)?></a></div>
                		</td>
                        <td style="text-align: right;">
                            <?php e($item[$ws_model]['price'])?>&nbsp;
                        </td>
                        <td>
                            <?php e($currencies[$item[$ws_model]['currency']])?>
                        </td>
                        <?php if(!empty($manufacturers)):?>
                        <td style="text-align: center;">
                            <?php et($manufacturers[$item[$ws_model]['extra_2']], 17)?>
                        </td>
                        <?php endif;?>
                        <?php if(!empty($bases)):?>
                        <td style="text-align: center;">
                            <?php et($item['ObjItemTree']['title'], 17)?>
                        </td>
                        <?php endif;?>
                        <td style="text-align: center;">
                            <?php e(date_stl_1($item[$ws_model]['created']))?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $this->Form->input('bonuses', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_bonuses_prc', $ws_model, $item[$ws_model]['id'])), 'type' => 'text', 'value' => $item['RelationValue']['bonuses_prc']));?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $this->Form->input('bonuses', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('action' => 'set_bonuses', $ws_model, $item[$ws_model]['id'])), 'type' => 'text', 'value' => $item['RelationValue']['bonuses']));?>
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

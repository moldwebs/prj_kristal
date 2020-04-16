<?php $ws_model = 'ObjItemTree'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Filters')?></div>
        <div class="nw-table-content pd">
            <?php if(!empty($bases)) echo $this->Form->input('fltr_eq__id', array('label' => ___('Category'), 'options' => $bases, 'empty' => ___('All')));?>
            <?php if(!empty($bases)) echo $this->Form->input('fltr_eq__parent_id', array('label' => ___('Parent'), 'options' => $bases, 'empty' => ''));?>
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

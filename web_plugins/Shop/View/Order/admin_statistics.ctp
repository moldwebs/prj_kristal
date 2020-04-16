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
            <?php echo $this->Form->input('fltr_lk__title', array('label' => ___('Title')));?>
            <?php echo $this->Form->input('othfltr_date_start', array('label' => ___('Date Start'), 'class' => 'ui_date', 'value' => $_GET['othfltr_date_start']));?>
            <?php echo $this->Form->input('othfltr_date_end', array('label' => ___('Date End'), 'class' => 'ui_date', 'value' => $_GET['othfltr_date_end']));?>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Search'), array('type' => 'submit', 'class' => 'button primary'));?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="grid_12">
    <div class="nw-table">
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
                    <th style="width: 100px;"><?php echo $this->Paginator->sort('stats', ___('Qnt'));?></th>
                    <th style="width: 100px; text-align: right;"><?php ___e('Price')?></th>
                    <th style="width: 55px;">&nbsp;</th>
                    <?php if(!empty($manufacturers)):?><th style="width: 200px;"><?php echo $this->Paginator->sort('extra_2', ___('Manufacturer'));?></th><?php endif;?>
                	<?php if(!empty($bases)):?><th style="width: 250px;"><?php echo $this->Paginator->sort('base_id', ___('Category'));?></th><?php endif;?>
                </tr></thead>
                <tbody>
                <?php foreach ($items as $item):?>
                	<?php $icols = 0?>
                    <tr>
                		<td style="text-align: center;">
                            <?php echo $this->element('/admin/item_thumb', array('item' => $item, 'ws_model' => $ws_model))?>
                        </td>
                		<td>
                            <div><a target="_blank" href="<?php eurl($item[$ws_model]['alias'])?>"><?php et($item[$ws_model]['title'], 100)?></a></div>
                		</td>
                        <td style="text-align: center;">
                            <?php e($item[$ws_model]['stats'])?>
                        </td>
                        <td style="text-align: right;">
                            <?php e($item[$ws_model]['price'])?>
                        </td>
                        <td>
                            <?php e(!empty($item[$ws_model]['price']) ? $currencies[$item[$ws_model]['currency']] : null)?>
                        </td>
                        <?php if(!empty($manufacturers)):?>
                        <td style="text-align: center;">
                            <?php et($manufacturers[$item[$ws_model]['extra_2']], 50)?>
                        </td>
                        <?php endif;?>
                        <?php if(!empty($bases)):?>
                        <td style="text-align: center;">
                            <?php et($item['ObjItemTree']['title'], 50)?>
                        </td>
                        <?php endif;?>
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
   </div>
</div>

<div class="clear"></div>

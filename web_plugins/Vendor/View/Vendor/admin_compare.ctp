<?php if(empty($items)):?>
        <?php echo $this->Form->create('Import', array('type' => 'file', 'class' => ''));?>
        <div>
            <?php echo $this->Form->input('pricelist_1', array('label' => ___('Previous Pricelist') . ' (' . ___('Not Required') . ')', 'type' => 'file', 'class' => ''));?>
            <?php echo $this->Form->input('pricelist_2', array('label' => ___('Actual Pricelist'), 'type' => 'file', 'class' => 'req'));?>
        </div>
        <div style="clear: both;"></div>
        <div style="margin: 10px; text-align: right;">
            <?php echo $this->Form->button(___('Compare'), array('name' => 'saction', 'value' => '2', 'class' => 'button'))?>
        </div>
        <?php echo $this->Form->end();?>
<?php else:?>
    <div class="grid_12">
        <div class="nw-table">
            <div class="nw-table-title">
                <div class="fl"><?php echo ___('Results')?> :: <?php e($vendor['title'])?></div>
                <div class="fr">
                </div>
            </div>
            <div class="nw-table-content pd">
                <?php if(is_array($items)):?>
                <table>
                    <thead><tr>
                        <th style="text-align: left;"><?php ___e('Title')?></th>
                        <th style="text-align: left; width: 120px;"><?php ___e('Code')?></th>
                        <th style="text-align: right; width: 90px;"><?php ___e('Price')?></th>
                        <th style="text-align: right; width: 90px;"><?php ___e('Price')?></th>
                        <th style="width: 50px;"></th>
                        <th style="width: 100px;"></th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($items as $item):?>
                    	<tr>
                    		<td style="white-space: normal;"><?php et($item['title'], 500)?></td>
                    		<td><?php et($item['code'], 200)?></td>
                    		<td style="text-align: right;"><?php et($item['price_old'], 200)?> <?php if(!empty($item['price_old'])) e($vendor['data']['col_currency'])?></td>
                    		<td style="text-align: right;"><?php et($item['price'], 200)?> <?php if(!empty($item['price'])) e($vendor['data']['col_currency'])?></td>
                    		<td></td>
                            <td style="text-align: center;">
                                <input style="color: <?php e($item['act'] == 'new' ? 'blue' : ($item['act'] == 'delete' ? 'red' : 'green'))?>;" class="button small" type="button" value="<?php e(strtoupper($item['act']))?>" />
                            </td>
                    	</tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else:?>
                    <?php echo $items?>
                <?php endif;?>
            </div>
        </div>
    </div>
<?php endif;?>
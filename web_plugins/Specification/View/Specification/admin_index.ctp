<?php $ws_model = 'Specification'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create($ws_model, array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Category')?></div>
        <div class="nw-table-content pd">
            <?php echo $this->Form->input('base_id', array('label' => false, 'options' => $bases, 'empty' => ___('All'), 'selected' => $this->request->query['scopefield'], 'onchange' => "window.location='{$this->here}?scopefield='+this.value+'&tb=1'"));?>
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
                <a href="javascript:void(0)" class="button ns-expand-all"><?php ___e('Expand All')?></a>
                <a href="javascript:void(0)" class="button ns-collapse-all"><?php ___e('Collapse All')?></a>
            </div>
        </div>
        <div class="nw-table-content pd">
        	<ol class="sortable" maxLevels="2">
                <?php foreach($oitems as $item):?>
                    <li id="tree_<?php e($item[$ws_model]['id'])?>" tree_parent_id="<?php e($item[$ws_model]['parent_id'])?>" class="mjs-nestedSortable-no-<?php e($item[$ws_model]['extra_1'] == '1' ? 'thisnesting' : 'nesting')?>">
                        <div class="cl <?php e($item[$ws_model]['extra_1'] == '1' ? 'cl_head' : null)?>">
                            <span class="disclose fl"></span>
                            <span class="<?php e($item[$ws_model]['extra_1'] == '1' ? 'dismenu' : 'dismove')?> fl"></span>
                            <span class="fl"><?php et($item[$ws_model]['mtitle'], 120)?></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => ($item[$ws_model]['extra_1'] == '1' ? 'edit_section' : ($item[$ws_model]['extra_1'] == '4' ? 'edit_test' : 'edit')), $item[$ws_model]['id']), array('class' => 'ico ico_edit', 'title' => ___('Edit'))); ?></span>
                        </div>
                    </li>
                <?php endforeach;?>
                <?php foreach($items as $item):?>
                    <li id="tree_<?php e($item[$ws_model]['id'])?>" tree_parent_id="<?php e($item[$ws_model]['parent_id'])?>" class="mjs-nestedSortable-no-<?php e($item[$ws_model]['extra_1'] == '1' ? 'thisnesting' : 'nesting')?>">
                        <div class="cl <?php e($item[$ws_model]['extra_1'] == '1' ? 'cl_head' : null)?>">
                            <span class="disclose fl"></span>
                            <span class="<?php e($item[$ws_model]['extra_1'] == '1' ? 'dismenu' : 'dismove')?> fl"></span>
                            <span class="fl"><?php et($item[$ws_model]['mtitle'], 120)?></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => 'delete', $item[$ws_model]['id']), array('class' => 'ico ico_del', 'title' => ___('Delete'), 'confirm' => ___('Confirm your action.'))); ?></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => ($item[$ws_model]['extra_1'] == '1' ? 'edit_section' : ($item[$ws_model]['extra_1'] == '4' ? 'edit_test' : 'edit')), $item[$ws_model]['id']), array('class' => 'ico ico_edit', 'title' => ___('Edit'))); ?></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => 'status', $item[$ws_model]['id']), array('class' => 'ajx_toggle ico ico_visible' . ($item[$ws_model]['status'] == '1' ? '' : ' off'), 'title' => ___('Show/Hide'))); ?></span>
                            <span class="dissep"></span>
                            <span class="disblock"><input type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></span>
                            <?php if($item[$ws_model]['extra_1'] != '1'):?>
                            <span class="dissep"></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => 'set_description', $item[$ws_model]['id']), array('class' => 'ajx_toggle ico ico_list' . ($item[$ws_model]['data']['in_desc'] == '1' ? '' : ' off'), 'title' => ___('Description'))); ?></span>
                            <span class="dissep"></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => 'set_option', $item[$ws_model]['id']), array('class' => 'ajx_toggle ico ico_menu' . ($item[$ws_model]['data']['in_option'] == '1' ? '' : ' off'), 'title' => ___('Option'))); ?></span>
                            <span class="dissep"></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => 'set_filter', $item[$ws_model]['id']), array('class' => 'ajx_toggle ico ico_filter' . ($item[$ws_model]['data']['in_filter'] == '1' ? '' : ' off'), 'title' => ___('Filter'))); ?></span>
                            <span class="dissep"></span>
                            <span class="disblock"><img title="<?php e($spec_types[$item[$ws_model]['extra_2']])?>" src="/img/specifs/<?php e($item[$ws_model]['extra_2'])?>.png" /></span>
                            <?php if(in_array($item[$ws_model]['extra_2'], array('6', '7', '9', '10'))):?>
                            <span class="dissep"></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => ($item[$ws_model]['extra_2'] == '9' ? 'value_img_index' : 'value_index'), $item[$ws_model]['id']), array('class' => 'ico ico_node-select', 'title' => ___('Values'))); ?></span>
                            <?php endif;?>
                            <?php endif;?>
                        </div>
                    </li>
                <?php endforeach;?>
        	</ol>
            <?php if(empty($items) && empty($oitems)) echo $this->element('/admin/no_results')?>
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
                <input onclick="$.post('<?php echo $this->Html->url(array('action' => 'table_structure'))?>', {'data[tree-structure]' : serialize($('.sortable').nestedSortable('toArray', {startDepthCount: 0}))});" type="button" value="<?php ___e('Save Structure')?>" class="button primary">
            </div>
        </div>
        </form>
    </div>
</div>

<div class="clear"></div>
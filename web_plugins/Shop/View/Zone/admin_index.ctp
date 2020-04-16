<?php $ws_model = 'ObjItemTree'?>
<div class="grid_12">
    <div class="nw-table">
        <form action="<?php echo $this->Html->url(array('action' => 'table_actions'))?>" method="POST">
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
                <a href="javascript:void(0)" class="button ns-expand-all"><?php ___e('Expand All')?></a>
                <a href="javascript:void(0)" class="button ns-collapse-all"><?php ___e('Collapse All')?></a>
                <a href="javascript:void(0)" class="button ns-check-all"><?php ___e('Check All')?></a>
            </div>
        </div>
        <div class="nw-table-content pd">
        	<ol class="sortable" maxLevel="2">
                <?php foreach($items as $item):?>
                    <li id="tree_<?php e($item[$ws_model]['id'])?>" tree_parent_id="<?php e($item[$ws_model]['parent_id'])?>" class="mjs-nestedSortable-no-<?php e($item[$ws_model]['extra_1'] == '1' ? 'thisnesting' : '')?>">
                        <div class="cl">
                            <span class="disclose fl"></span>
                            <span class="<?php e($item[$ws_model]['extra_1'] == '1' ? 'disregion' : 'dismove')?> fl"></span>
                            <span class="fl"><?php et($item[$ws_model]['title'], 120)?></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => 'delete', $item[$ws_model]['id']), array('class' => 'ico ico_del', 'title' => ___('Delete'), 'confirm' => ___('Confirm your action.'))); ?></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => ($item[$ws_model]['extra_1'] == '1' ? 'edit_panel' : 'edit'), $item[$ws_model]['id']), array('class' => 'ico ico_edit', 'title' => ___('Edit'))); ?></span>
                            <span class="disblock"><?php echo $this->Html->link('', array('action' => 'status', $item[$ws_model]['id']), array('class' => 'ajx_toggle ico ico_visible' . ($item[$ws_model]['status'] == '1' ? '' : ' off'), 'title' => ___('Show/Hide'))); ?></span>
                            <span class="dissep"></span>
                            <span class="disblock"><input class="ns-check-tree" type="checkbox" name="data[item][]" value="<?php e($item[$ws_model]['id'])?>" /></span>
                            <span class="dissep"></span>
                            <span class="disblock"><?php e($item[$ws_model]['data']['distance'])?></span>
                            <span class="dissep"></span>
                            <span class="disblock"><?php echo $this->element('/admin/item_thumb', array('item' => $item, 'ws_model' => $ws_model))?></span>
                        </div>
                    </li>
                <?php endforeach;?>
        	</ol>
            <?php if(empty($items)) echo $this->element('/admin/no_results')?>
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
            <?php echo $this->Form->input('parent_id', array('options' => $parents, 'label' => ___('Parent'), 'empty' => ___(' --- None --- ')));?>
            <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
            <?php echo $this->Form->input('ObjItemTree.data.distance', array('label' => ___('Distance (Km)'), 'type' => 'text'));?>
            <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Create'), array('name' => 'saction', 'value' => '2'))?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>

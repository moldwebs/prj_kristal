<?php //echo $this->Form->input('extra_1', array('label' => false, 'div' => false, 'style' => 'width: 65%;', 'ajx_change' => $this->Html->url(array('action' => 'set_field', 'extra_1', $item[$ws_model]['id'])), 'type' => 'select', 'empty' => '', 'options' => $types, 'value' => $item[$ws_model]['extra_1']));?>
&nbsp;
<a class="ico ico_menu pop_menu_ico"></a>
<div style="margin-left: 45px;" class="pop_menu">
    <?php foreach($types as $key => $val):?>
        <div style="line-height: 24px;">
            <div style="float: left; width: 80px; overflow: hidden; white-space: nowrap;">
                <input style="width: auto;" type="checkbox" value="<?php e($key)?>" onchange="if($(this).not(':checked')) $(this).parent().next().find('input:first').val('');" ajx_change="<?php e($this->Html->url(array('action' => 'set_relation', 'extra_1', $item[$ws_model]['id'], $key)))?>" <?php if(in_array($key, $item['Relation']['extra_1'])) echo 'checked="checked"'?> /> <?php e($val)?>
            </div>
            <div style="float: left; width: 90px; overflow: hidden; white-space: nowrap;">
                <input placeholder="<?php ___e('expire date')?>" class="ui_date" type="text" onchange="if($(this).val() != '') $(this).parent().prev().find('input:first').attr('checked', 'checked');" ajx_change="<?php e($this->Html->url(array('action' => 'set_relation', 'extra_1', $item[$ws_model]['id'], $key, '1')))?>" value="<?php e(strtotime($item['RelationExpire']['extra_1'][$key]) > 0 ? date("Y-m-d", strtotime($item['RelationExpire']['extra_1'][$key])) : null)?>" />
            </div>
            <div style="clear: both;"></div>
        </div>
    <?php endforeach;?>
</div>
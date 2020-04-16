<?php foreach($vendors as $vendor):?>
    <div style="float: left; width: 45%; padding: 5px; border-bottom: 1px dotted #CCCCCC;">
        <div style="float: left; width: 25%; overflow: hidden; line-height: 20px;">
            <?php e($vendor['ObjItemList']['title'])?>
        </div>
        <div style="float: left; width: 15%; overflow: hidden;">
        <?php echo $this->Form->input('vendor_price', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('plugin' => 'vendor', 'controller' => 'vendor', 'action' => 'set_data', 'extra_6', $item_id, $vendor['ObjItemList']['id'])), 'type' => 'text', 'placeholder' => ___('price'), 'value' => $vendor_data[$vendor['ObjItemList']['id']]['extra_6']));?>
        </div>
        <div style="float: left; width: 12%; overflow: hidden;">
        <?php echo $this->Form->input('vendor_currency', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('plugin' => 'vendor', 'controller' => 'vendor', 'action' => 'set_data', 'extra_7', $item_id, $vendor['ObjItemList']['id'])), 'type' => 'select', 'options' => $currencies, 'value' => $vendor_data[$vendor['ObjItemList']['id']]['extra_7']));?>
        </div>
        <div style="float: left; width: 7%; overflow: hidden;">
            <input type="checkbox" <?php e($vendor_data[$vendor['ObjItemList']['id']]['extra_4'] == '1' ? 'checked="checked"' : null)?> ajx_change="/admin/vendor/vendor/set_data/extra_4/<?php e($item_id)?>/<?php e($vendor['ObjItemList']['id'])?>" />
        </div>

        <div style="float: left; width: 40%; overflow: hidden; line-height: 20px;">
            <?php if(!empty($vendor_codes[$vendor['ObjItemList']['id']])):?>
            [ <?php e($vendor_codes[$vendor['ObjItemList']['id']])?> ]
            <?php endif;?>
            <?php if(!empty($vendor_data[$vendor['ObjItemList']['id']]['data']['price'])):?>
            [ <?php e($vendor_data[$vendor['ObjItemList']['id']]['data']['price'])?> <?php e($vendor_data[$vendor['ObjItemList']['id']]['data']['currency'])?> <?php e(!empty($vendor_data[$vendor['ObjItemList']['id']]['data']['extra']) ? '/ ' . $vendor_data[$vendor['ObjItemList']['id']]['data']['extra'] : '')?> ]
            <?php endif;?>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div style="float: left; width: 3%;">&nbsp;</div>
<?php endforeach;?>
<div style="clear: both;"></div>
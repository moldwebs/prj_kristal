<fieldset>
    <legend><?php ___e('Code')?></legend>
    <div class="n6 cl">
        <?php foreach($vendors as $vendor):?>
            <?php echo $this->Form->input("RelationValue.vendor_code.{$vendor['ObjItemList']['id']}", array('label' => $vendor['ObjItemList']['title'], 'type' => 'text'));?>
        <?php endforeach;?>
    </div>
</fieldset>
<fieldset>
    <legend><?php ___e('Force Coeficient')?></legend>
    <div class="n6 cl">
        <?php echo $this->Form->input("RelationValue.vendor_percent.1", array('label' => ___('All Vendors') . ' (%)', 'type' => 'text'));?>
        <?php echo $this->Form->input("RelationValue.vendor_fix.1", array('label' => ___('All Vendors') . ' (FIX)', 'type' => 'text'));?>
    </div>
</fieldset>
<input type="hidden" name="data[RelationRemove][vendor_code]" value="1" />
<?php if(!empty($vendor_data)):?>
<fieldset>
    <legend><?php ___e('Vendors Prices')?></legend>
    <div>
        <?php foreach($vendors as $vendor):?>
            <div style="float: left; width: 45%; padding: 5px; border-bottom: 1px dotted #CCCCCC;">
                <div style="float: left; width: 140px; overflow: hidden; line-height: 20px;">
                    <?php e($vendor['ObjItemList']['title'])?>
                </div>
                <div style="float: left; width: 50px; overflow: hidden;">
                <?php echo $this->Form->input('vendor_price', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('plugin' => 'vendor', 'controller' => 'vendor', 'action' => 'set_data', 'extra_6', $item_id, $vendor['ObjItemList']['id'])), 'type' => 'text', 'placeholder' => ___('v. price'), 'value' => $vendor_data[$vendor['ObjItemList']['id']]['extra_6']));?>
                </div>
                <div style="float: left; width: 50px; overflow: hidden;">
                <?php echo $this->Form->input('vendor_currency', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('plugin' => 'vendor', 'controller' => 'vendor', 'action' => 'set_data', 'extra_7', $item_id, $vendor['ObjItemList']['id'])), 'type' => 'select', 'options' => $currencies, 'value' => $vendor_data[$vendor['ObjItemList']['id']]['extra_7']));?>
                </div>
                <div style="float: left; width: 20px; overflow: hidden; text-align: center;">
                    <input type="checkbox" <?php e($vendor_data[$vendor['ObjItemList']['id']]['extra_4'] == '1' ? 'checked="checked"' : null)?> ajx_change="/admin/vendor/vendor/set_data/extra_4/<?php e($item_id)?>/<?php e($vendor['ObjItemList']['id'])?>" />
                </div>
                <div style="float: left; width: 50px; overflow: hidden;">
                    <?php echo $this->Form->input('vendor_price_calc', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('plugin' => 'vendor', 'controller' => 'vendor', 'action' => 'set_data', 'price_calc', $item_id, $vendor['ObjItemList']['id'])), 'type' => 'text', 'placeholder' => ___('c. price'), 'value' => $vendor_data[$vendor['ObjItemList']['id']]['data']['price_calc']));?>
                </div>
                <div style="float: left; width: 50px; overflow: hidden;">
                    <?php echo $this->Form->input('vendor_currency_calc', array('label' => false, 'div' => false, 'ajx_change' => $this->Html->url(array('plugin' => 'vendor', 'controller' => 'vendor', 'action' => 'set_data', 'currency_calc', $item_id, $vendor['ObjItemList']['id'])), 'type' => 'select', 'options' => $currencies, 'value' => $vendor_data[$vendor['ObjItemList']['id']]['data']['currency_calc']));?>
                </div>
                <div style="float: left; width: 5px; overflow: hidden;">
                &nbsp;
                </div>
                <div style="float: left; width: 150px; overflow: hidden; line-height: 20px;">
                    <?php if(!empty($vendor_data[$vendor['ObjItemList']['id']]['data']['price'])):?>
                    [ <?php e($vendor_data[$vendor['ObjItemList']['id']]['data']['price'])?> <?php e($vendor_data[$vendor['ObjItemList']['id']]['data']['currency'])?> <?php e(!empty($vendor_data[$vendor['ObjItemList']['id']]['data']['extra']) ? '/ ' . $vendor_data[$vendor['ObjItemList']['id']]['data']['extra'] : '')?> ]
                    <?php endif;?>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div style="float: left; width: 3%;">&nbsp;</div>
        <?php endforeach;?>
        <div style="clear: both;"></div>
    </div>
</fieldset>
<?php endif;?>
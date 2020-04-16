<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('CmsSetting', array('type' => 'file'));?>
        <div class="nw-table-title">
            <div class="fl"><?php ___e('Settings')?></div>
            <div class="fr">
                <?php echo $this->element('/admin/edit_langs')?>
            </div>
        </div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php echo ___('Settings')?></span></a></li>
                    <li><a href="#node-4"><span><?php echo ___('Elevator Price')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Stairs Price')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n4 cl">
                        <?php echo $this->Form->input('shipping_weight_calc', array('label' => ___('Shipping Weight Calculation'), 'options' => array('' => ___('By Item'), '1' => ___('By Basket'))));?>
                        <?php echo $this->Form->input('elevator_weight_calc', array('label' => ___('Elevator Weight Calculation'), 'options' => array('' => ___('By Item'), '1' => ___('By Basket'))));?>
                        <?php echo $this->Form->input('shipping_disc', array('label' => ___('Shipping Discount'), 'options' => ws_ny()));?>
                        <?php echo $this->Form->input('elevator_disc', array('label' => ___('Elevator Discount'), 'options' => ws_ny()));?>
                    </div>
                    <div class="n3 cl">
                        <?php echo $this->Form->input('max_floor', array('label' => ___('Max. Floor'), 'type' => 'text'));?>
                    </div>
                </div>
                <div id="node-4">
                    <?php if(!empty($this->data['CmsSetting']['data']['elevator_price'])):?>
                        <?php foreach($this->data['CmsSetting']['data']['elevator_price'] as $pkey => $price):?>
                            <div style="padding: 5px 0;">
                                <div class="n7 cl">
                                    <div class="input text">
                                        <label><?php ___e('Min. Floor')?></label>
                                        <input name="CmsSetting[data][elevator_price][<?php e($pkey)?>][min_floor]" value="<?php e($price['min_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Max. Floor')?></label>
                                        <input name="CmsSetting[data][elevator_price][<?php e($pkey)?>][max_floor]" value="<?php e($price['max_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Min. Weight (Kg)')?></label>
                                        <input name="CmsSetting[data][elevator_price][<?php e($pkey)?>][min_weight]" value="<?php e($price['min_weight'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Max. Weight (Kg)')?></label>
                                        <input name="CmsSetting[data][elevator_price][<?php e($pkey)?>][max_weight]" value="<?php e($price['max_weight'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price for Floor')?></label>
                                        <input name="CmsSetting[data][elevator_price][<?php e($pkey)?>][price_floor]" value="<?php e($price['price_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price for Kg')?></label>
                                        <input name="CmsSetting[data][elevator_price][<?php e($pkey)?>][price_kg]" value="<?php e($price['price_kg'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price fix')?></label>
                                        <input name="CmsSetting[data][elevator_price][<?php e($pkey)?>][price_fix]" value="<?php e($price['price_fix'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label>&nbsp;</label>
                                        <a class="button" onclick="$(this).parent().parent().parent().remove();" href="javascript:void();"><?php ___e('Delete')?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    <?php else:?>
                        <script>
                            //$(function() { box_tpl('2'); });
                        </script>
                    <?php endif;?>
                
                    <div id="box_2"></div>
                    <div><a class="button" onclick="box_tpl('2');" href="javascript:void();"><?php ___e('Create')?></a></div>
                    <div id="box_tpl_2" style="display: none;">
                            <div class="n7 cl">
                                <div class="input text">
                                    <label><?php ___e('Min. Floor')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][elevator_price][{v_id}][min_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Max. Floor')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][elevator_price][{v_id}][max_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Min. Weight (Kg)')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][elevator_price][{v_id}][min_weight]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Max. Weight (Kg)')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][elevator_price][{v_id}][max_weight]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price for Floor')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][elevator_price][{v_id}][price_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price for Kg')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][elevator_price][{v_id}][price_kg]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price fix')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][elevator_price][{v_id}][price_fix]" type="text" />
                                </div>
                                <div class="input text">
                                    <label>&nbsp;</label>
                                    <a class="button" onclick="$(this).parent().parent().parent().remove();" href="javascript:void();"><?php ___e('Delete')?></a>
                                </div>
                            </div>
                    </div>
                </div>
                <div id="node-5">
                    <?php if(!empty($this->data['CmsSetting']['data']['stairs_price'])):?>
                        <?php foreach($this->data['CmsSetting']['data']['stairs_price'] as $pkey => $price):?>
                            <div style="padding: 5px 0;">
                                <div class="n7 cl">
                                    <div class="input text">
                                        <label><?php ___e('Min. Floor')?></label>
                                        <input name="CmsSetting[data][stairs_price][<?php e($pkey)?>][min_floor]" value="<?php e($price['min_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Max. Floor')?></label>
                                        <input name="CmsSetting[data][stairs_price][<?php e($pkey)?>][max_floor]" value="<?php e($price['max_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Min. Weight (Kg)')?></label>
                                        <input name="CmsSetting[data][stairs_price][<?php e($pkey)?>][min_weight]" value="<?php e($price['min_weight'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Max. Weight (Kg)')?></label>
                                        <input name="CmsSetting[data][stairs_price][<?php e($pkey)?>][max_weight]" value="<?php e($price['max_weight'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price for Floor')?></label>
                                        <input name="CmsSetting[data][stairs_price][<?php e($pkey)?>][price_floor]" value="<?php e($price['price_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price for Kg')?></label>
                                        <input name="CmsSetting[data][stairs_price][<?php e($pkey)?>][price_kg]" value="<?php e($price['price_kg'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price fix')?></label>
                                        <input name="CmsSetting[data][stairs_price][<?php e($pkey)?>][price_fix]" value="<?php e($price['price_fix'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label>&nbsp;</label>
                                        <a class="button" onclick="$(this).parent().parent().parent().remove();" href="javascript:void();"><?php ___e('Delete')?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    <?php else:?>
                        <script>
                            //$(function() { box_tpl('3'); });
                        </script>
                    <?php endif;?>
                
                    <div id="box_3"></div>
                    <div><a class="button" onclick="box_tpl('3');" href="javascript:void();"><?php ___e('Create')?></a></div>
                    <div id="box_tpl_3" style="display: none;">
                            <div class="n7 cl">
                                <div class="input text">
                                    <label><?php ___e('Min. Floor')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][stairs_price][{v_id}][min_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Max. Floor')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][stairs_price][{v_id}][max_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Min. Weight (Kg)')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][stairs_price][{v_id}][min_weight]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Max. Weight (Kg)')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][stairs_price][{v_id}][max_weight]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price for Floor')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][stairs_price][{v_id}][price_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price for Kg')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][stairs_price][{v_id}][price_kg]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price fix')?></label>
                                    <input disabled="disabled" name="CmsSetting[data][stairs_price][{v_id}][price_fix]" type="text" />
                                </div>
                                <div class="input text">
                                    <label>&nbsp;</label>
                                    <a class="button" onclick="$(this).parent().parent().parent().remove();" href="javascript:void();"><?php ___e('Delete')?></a>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Save'))?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>

<script>
    function box_tpl(b_id){
        var new_id = (new Date()).getTime();
        var new_box = $('#box_tpl_' + b_id).html().replace(/{v_id}/g, new_id).replace(/disabled="disabled"/g, '');
        $('#box_' + b_id).append('<div style="padding: 5px 0;">' + new_box + '</div>');
    }
</script>
<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjItemList', array('type' => 'file', 'class' => 'ajx_validate'));?>
        <?php echo $this->Form->input('status', array('value' => '1', 'type' => 'hidden'));?>
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
                <?php echo $this->element('/admin/edit_langs')?>
            </div>
        </div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php echo ___('Content')?></span></a></li>
                    <?php if(!empty($zones)):?><li><a href="#node-2"><span><?php echo ___('Zone Price')?></span></a></li><?php endif;?>
                    <li><a href="#node-4"><span><?php echo ___('Elevator Price')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Stairs Price')?></span></a></li>
                    <li><a href="#node-3"><span><?php echo ___('Settings')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                        <?php echo $this->Form->input('ObjItemList.data.is_pickup', array('label' => ___('Agency Pickup'), 'options' => ws_ny()));?>
                    </div>
                    <div class="n3 cl">
                        <?php echo $this->Form->input('ObjItemList.price', array('label' => ___('Price'), 'type' => 'text'));?>
                        <?php echo $this->Form->input('ObjItemList.currency', array('label' => ___('Currency'), 'options' => $currencies));?>
                        <?php echo $this->Form->input('ObjItemList.data.free_price', array('label' => ___('Free price'), 'type' => 'text'));?>
                        <?php if(!empty($zones)) echo $this->Form->input('Relation.zone_id', array('options' => $zones, 'label' => ___('Zones'), 'multiple' => 'multiple'));?>
                        <?php //echo $this->Form->input('ObjItemList.data.auto_select', array('label' => ___('Auto select'), 'options' => ws_ny()));?>
                        <?php //echo $this->Form->input('ObjItemList.data.req_address', array('label' => ___('Address required'), 'options' => ws_ny()));?>
                    </div>
                    <?php echo $this->Layout->__input('body', array('label' => ___('Description'), 'type' => 'textarea'));?>                    
                </div>
                <?php if(!empty($zones)):?>
                <div id="node-2">
                    <?php if(!empty($this->data['ObjItemList']['data']['zone_price'])):?>
                        <?php foreach($this->data['ObjItemList']['data']['zone_price'] as $pkey => $price):?>
                            <div style="padding: 5px 0;">
                                <div class="n7 cl">
                                    <div class="input text">
                                        <label><?php ___e('Min. Distance (Km)')?></label>
                                        <input name="ObjItemList[data][zone_price][<?php e($pkey)?>][min_dist]" value="<?php e($price['min_dist'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Max. Distance (Km)')?></label>
                                        <input name="ObjItemList[data][zone_price][<?php e($pkey)?>][max_dist]" value="<?php e($price['max_dist'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Min. Weight (Kg)')?></label>
                                        <input name="ObjItemList[data][zone_price][<?php e($pkey)?>][min_weight]" value="<?php e($price['min_weight'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Max. Weight (Kg)')?></label>
                                        <input name="ObjItemList[data][zone_price][<?php e($pkey)?>][max_weight]" value="<?php e($price['max_weight'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price for Km')?></label>
                                        <input name="ObjItemList[data][zone_price][<?php e($pkey)?>][price_km]" value="<?php e($price['price_km'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price for Kg')?></label>
                                        <input name="ObjItemList[data][zone_price][<?php e($pkey)?>][price_kg]" value="<?php e($price['price_kg'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price fix')?></label>
                                        <input name="ObjItemList[data][zone_price][<?php e($pkey)?>][price_fix]" value="<?php e($price['price_fix'])?>" type="text" />
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
                            //$(function() { box_tpl('1'); });
                        </script>
                    <?php endif;?>
                
                    <div id="box_1"></div>
                    <div><a class="button" onclick="box_tpl('1');" href="javascript:void();"><?php ___e('Create')?></a></div>
                    <div id="box_tpl_1" style="display: none;">
                            <div class="n7 cl">
                                <div class="input text">
                                    <label><?php ___e('Min. Distance (Km)')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][zone_price][{v_id}][min_dist]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Max. Distance (Km)')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][zone_price][{v_id}][max_dist]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Min. Weight (Kg)')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][zone_price][{v_id}][min_weight]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Max. Weight (Kg)')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][zone_price][{v_id}][max_weight]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price for Km')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][zone_price][{v_id}][price_km]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price for Kg')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][zone_price][{v_id}][price_kg]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price fix')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][zone_price][{v_id}][price_fix]" type="text" />
                                </div>
                                <div class="input text">
                                    <label>&nbsp;</label>
                                    <a class="button" onclick="$(this).parent().parent().parent().remove();" href="javascript:void();"><?php ___e('Delete')?></a>
                                </div>
                            </div>
                    </div>
                </div>
                <?php endif;?>
                <div id="node-4">
                    <?php if(!empty($this->data['ObjItemList']['data']['elevator_price'])):?>
                        <?php foreach($this->data['ObjItemList']['data']['elevator_price'] as $pkey => $price):?>
                            <div style="padding: 5px 0;">
                                <div class="n7 cl">
                                    <div class="input text">
                                        <label><?php ___e('Min. Floor')?></label>
                                        <input name="ObjItemList[data][elevator_price][<?php e($pkey)?>][min_floor]" value="<?php e($price['min_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Max. Floor')?></label>
                                        <input name="ObjItemList[data][elevator_price][<?php e($pkey)?>][max_floor]" value="<?php e($price['max_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Min. Weight (Kg)')?></label>
                                        <input name="ObjItemList[data][elevator_price][<?php e($pkey)?>][min_weight]" value="<?php e($price['min_weight'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Max. Weight (Kg)')?></label>
                                        <input name="ObjItemList[data][elevator_price][<?php e($pkey)?>][max_weight]" value="<?php e($price['max_weight'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price for Floor')?></label>
                                        <input name="ObjItemList[data][elevator_price][<?php e($pkey)?>][price_floor]" value="<?php e($price['price_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price for Kg')?></label>
                                        <input name="ObjItemList[data][elevator_price][<?php e($pkey)?>][price_kg]" value="<?php e($price['price_kg'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price fix')?></label>
                                        <input name="ObjItemList[data][elevator_price][<?php e($pkey)?>][price_fix]" value="<?php e($price['price_fix'])?>" type="text" />
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
                                    <input disabled="disabled" name="ObjItemList[data][elevator_price][{v_id}][min_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Max. Floor')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][elevator_price][{v_id}][max_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Min. Weight (Kg)')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][elevator_price][{v_id}][min_weight]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Max. Weight (Kg)')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][elevator_price][{v_id}][max_weight]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price for Floor')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][elevator_price][{v_id}][price_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price for Kg')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][elevator_price][{v_id}][price_kg]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price fix')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][elevator_price][{v_id}][price_fix]" type="text" />
                                </div>
                                <div class="input text">
                                    <label>&nbsp;</label>
                                    <a class="button" onclick="$(this).parent().parent().parent().remove();" href="javascript:void();"><?php ___e('Delete')?></a>
                                </div>
                            </div>
                    </div>
                </div>
                <div id="node-5">
                    <?php if(!empty($this->data['ObjItemList']['data']['stairs_price'])):?>
                        <?php foreach($this->data['ObjItemList']['data']['stairs_price'] as $pkey => $price):?>
                            <div style="padding: 5px 0;">
                                <div class="n7 cl">
                                    <div class="input text">
                                        <label><?php ___e('Min. Floor')?></label>
                                        <input name="ObjItemList[data][stairs_price][<?php e($pkey)?>][min_floor]" value="<?php e($price['min_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Max. Floor')?></label>
                                        <input name="ObjItemList[data][stairs_price][<?php e($pkey)?>][max_floor]" value="<?php e($price['max_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Min. Weight (Kg)')?></label>
                                        <input name="ObjItemList[data][stairs_price][<?php e($pkey)?>][min_weight]" value="<?php e($price['min_weight'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Max. Weight (Kg)')?></label>
                                        <input name="ObjItemList[data][stairs_price][<?php e($pkey)?>][max_weight]" value="<?php e($price['max_weight'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price for Floor')?></label>
                                        <input name="ObjItemList[data][stairs_price][<?php e($pkey)?>][price_floor]" value="<?php e($price['price_floor'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price for Kg')?></label>
                                        <input name="ObjItemList[data][stairs_price][<?php e($pkey)?>][price_kg]" value="<?php e($price['price_kg'])?>" type="text" />
                                    </div>
                                    <div class="input text">
                                        <label><?php ___e('Price fix')?></label>
                                        <input name="ObjItemList[data][stairs_price][<?php e($pkey)?>][price_fix]" value="<?php e($price['price_fix'])?>" type="text" />
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
                                    <input disabled="disabled" name="ObjItemList[data][stairs_price][{v_id}][min_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Max. Floor')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][stairs_price][{v_id}][max_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Min. Weight (Kg)')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][stairs_price][{v_id}][min_weight]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Max. Weight (Kg)')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][stairs_price][{v_id}][max_weight]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price for Floor')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][stairs_price][{v_id}][price_floor]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price for Kg')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][stairs_price][{v_id}][price_kg]" type="text" />
                                </div>
                                <div class="input text">
                                    <label><?php ___e('Price fix')?></label>
                                    <input disabled="disabled" name="ObjItemList[data][stairs_price][{v_id}][price_fix]" type="text" />
                                </div>
                                <div class="input text">
                                    <label>&nbsp;</label>
                                    <a class="button" onclick="$(this).parent().parent().parent().remove();" href="javascript:void();"><?php ___e('Delete')?></a>
                                </div>
                            </div>
                    </div>
                </div>
                <div id="node-3">
                    <?php echo $this->Form->input('order_id', array('label' => ___('Order'), 'type' => 'text'));?>
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
                </div>
            </div>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Layout->form_save();?>
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

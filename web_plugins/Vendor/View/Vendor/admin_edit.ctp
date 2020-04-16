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
                    <li><a href="#node-2"><span><?php echo ___('Categories %')?></span></a></li>
                    <li><a href="#node-4"><span><?php echo ___('Categories ID')?></span></a></li>
                    <li><a href="#node-3"><span><?php echo ___('Settings')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                    </div>
                    <div class="n5 cl">
                    <?php echo $this->Form->input('ObjItemList.data.col_base', array('label' => ___('Category Column'), 'class' => ''));?>
                    <?php echo $this->Form->input('ObjItemList.data.col_brand', array('label' => ___('Manufacturer Column'), 'class' => ''));?>
                    <?php echo $this->Form->input('ObjItemList.data.col_prodcode', array('label' => ___('Product Code Column'), 'class' => ''));?>
                    <?php echo $this->Form->input('ObjItemList.data.col_wrnt', array('label' => ___('Warranty Column'), 'class' => ''));?>
                    </div>
                    <div class="n5 cl">
                    <?php echo $this->Form->input('ObjItemList.data.col_title', array('label' => ___('Title Column'), 'class' => 'req'));?>
                    <?php echo $this->Form->input('ObjItemList.data.col_code', array('label' => ___('Code Column'), 'class' => 'req'));?>
                    <?php echo $this->Form->input('ObjItemList.data.col_extra', array('label' => ___('Extra Column'), 'class' => ''));?>
                    </div>
                    <div class="n5 cl">
                    <?php echo $this->Form->input('ObjItemList.data.col_price', array('label' => ___('Price Column'), 'class' => 'req'));?>
                    <?php echo $this->Form->input('ObjItemList.data.col_currency', array('label' => ___('Currency'), 'options' => $currencies));?>
                    <?php echo $this->Form->input('ObjItemList.data.col_price_calc', array('label' => ___('Calc. Price Column'), 'class' => ''));?>
                    <?php echo $this->Form->input('ObjItemList.data.col_currency_calc', array('label' => ___('Currency'), 'options' => $currencies));?>
                    </div>
                    <div class="n4 cl">
                    <?php echo $this->Form->input('ObjItemList.data.conv_currency', array('label' => ___('Convert to') . ' ' . Configure::read('Obj.currency')['title'], 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('ObjItemList.data.conv_rate', array('label' => ___('Custom Convert Rate'), 'class' => ''));?>
                    </div>
                    <div class="n4 cl">
                    <?php echo $this->Form->input('ObjItemList.data.coeficient', array('label' => ___('Coeficient') . ' (%)', 'class' => ''));?>
                    <?php echo $this->Form->input('ObjItemList.data.coeficient_fix', array('label' => ___('Coeficient') . ' (FIX)', 'class' => ''));?>
                    </div>
                </div>
                <div id="node-2">
                    <?php if(!empty($this->data['ObjItemList']['data']['params'])):?>
                        <?php foreach($this->data['ObjItemList']['data']['params'] as $pkey => $param):?>
                            <div style="padding: 5px 0;">
                                <div class="n6 cl">
                                    <?php echo $this->Form->input("ObjItemList.data.params.{$pkey}.base_id", array('label' => ___('Category'), 'options' => $bases, 'empty' => ''));?>
                                    <?php echo $this->Form->input("ObjItemList.data.params.{$pkey}.range_min", array('label' => ___('Range Min')));?>
                                    <?php echo $this->Form->input("ObjItemList.data.params.{$pkey}.range_max", array('label' => ___('Range Max')));?>
                                    <?php echo $this->Form->input("ObjItemList.data.params.{$pkey}.coeficient", array('label' => ___('Coeficient') . ' (%)'));?>
                                    <?php echo $this->Form->input("ObjItemList.data.params.{$pkey}.coeficient_fix", array('label' => ___('Coeficient') . ' (FIX)'));?>
                                    <div class="input text">
                                        <label>&nbsp;</label>
                                        <a class="button" onclick="$(this).parent().parent().parent().remove();" href="javascript:void(0);"><?php ___e('Delete')?></a>
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
                    <div><a class="button" onclick="box_tpl('1');" href="javascript:void(0);"><?php ___e('Create')?></a></div>
                    <div class="box_tpl" id="box_tpl_1" style="display: none;">
                        <div class="n6 cl">
                            <?php echo $this->Form->input("ObjItemList.data.params.{v_id}.base_id", array('label' => ___('Category'), 'options' => $bases, 'empty' => '', 'id' => false));?>
                            <?php echo $this->Form->input("ObjItemList.data.params.{v_id}.range_min", array('label' => ___('Range Min'), 'id' => null));?>
                            <?php echo $this->Form->input("ObjItemList.data.params.{v_id}.range_max", array('label' => ___('Range Max')));?>
                            <?php echo $this->Form->input("ObjItemList.data.params.{v_id}.coeficient", array('label' => ___('Coeficient') . ' (%)'));?>
                            <?php echo $this->Form->input("ObjItemList.data.params.{v_id}.coeficient_fix", array('label' => ___('Coeficient') . ' (FIX)'));?>
                            <div class="input text">
                                <label>&nbsp;</label>
                                <a class="button" onclick="$(this).parent().parent().parent().remove();" href="javascript:void(0);"><?php ___e('Delete')?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="node-4">
                    <div>
                        <?php foreach($bases as $base_id => $base_title):?>
                            <div>
                            <div class="n3 cl">
                                <div class="input text"><?php e(str_replace('&_nbsp;', '&nbsp;', $base_title))?></div>
                                <?php echo $this->Form->input('ObjItemList.data.bases.' . $base_id, array('label' => false));?>
                            </div>
                            </div>
                        <?php endforeach;?>
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
    $('.box_tpl').find('input,select,textarea').attr('disabled', 'disabled');
    function box_tpl(b_id){
        var new_id = (new Date()).getTime();
        $('#box_' + b_id).append('<div style="padding: 5px 0;">' + $('#box_tpl_' + b_id).html().replace(/{v_id}/g, new_id) + '</div>');
        $('#box_' + b_id).find('input,select,textarea').removeAttr('disabled');
    }
</script>

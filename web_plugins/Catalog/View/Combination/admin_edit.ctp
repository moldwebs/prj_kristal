<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjItemList', array('type' => 'file', 'class' => 'ajx_validate'));?>
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
                    <li><a href="#node-5"><span><?php echo ___('Attachments')?></span></a></li>
                    <?php if(Configure::read('PLUGIN.Specification') == '1'):?>
                    <li><a href="#node-8"><span><?php echo ___('Options')?></span></a></li>
                    <?php endif;?>
                    <?php if(Configure::read('PLUGIN.Vendor') == '1' && !empty($vendors)):?>
                    <li><a href="#node-8-1"><span><?php echo ___('Vendors')?></span></a></li>
                    <?php endif;?>
                    <li><a href="#node-3"><span><?php echo ___('Settings')?></span></a></li>
                    <li><a href="#node-4"><span><?php echo ___('Meta')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => ''));?>
                        <?php echo $this->Layout->__input('alias', array('label' => ___('Alias')));?>
                    </div>
                    <div class="n5 cl">
                        <?php echo $this->Form->input('ObjItemList.price', array('label' => ___('Price'), 'type' => 'text'));?>
                        <?php echo $this->Form->input('ObjItemList.currency', array('label' => ___('Currency'), 'options' => $currencies));?>
                        <?php echo $this->Form->input('ObjItemList.data.old_price', array('label' => ___('Old price'), 'type' => 'text'));?>
                        <?php echo $this->Form->input('RelationValue.promo_price', array('label' => ___('Promo Price'), 'type' => 'text'));?>
                        <?php echo $this->Form->input('RelationExpire.promo_price', array('label' => ___('Promo Price Expire'), 'type' => 'text', 'class' => 'ui_date'));?>
                    </div>
                    <div class="n5 cl">
                        <?php echo $this->Form->input('ObjItemList.code', array('label' => ___('Code'), 'type' => 'text'));?>
                        <?php echo $this->Form->input('ObjItemList.qnt', array('label' => ___('Quantity'), 'type' => 'text'));?>
                        <?php if(!empty($deposits)) echo $this->Form->input('extra_3', array('options' => $deposits, 'label' => ___('Deposit'), 'empty' => array('0' => ___(' --- None --- '))));?>
                    </div>
                </div>
                <div id="node-5">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <?php if(Configure::read('PLUGIN.Specification') == '1'):?>
                <div id="node-8">
                </div>
                <script>
                    $(document).ready(function(){
                        $('#node-8').load('<?php e($this->Html->url(array('controller' => 'specification', 'action' => 'item', 'admin' => false)))?>/<?php e($parent['ObjItemList']['base_id'])?>/2?data=<?php e(urlencode(base64_encode(json_encode($this->data['RelationValue']['specification']))))?>');
                    });
                </script>
                <?php endif;?>
                <?php if(Configure::read('PLUGIN.Vendor') == '1' && !empty($vendors)):?>
                <div id="node-8-1">
                </div>
                <script>
                    $(document).ready(function(){
                        $('#node-8-1').load('/admin/vendor/vendor/item/<?php e($this->data['ObjItemList']['id'])?>?base_id=<?php e($parent['ObjItemList']['base_id'])?>&data=<?php e(urlencode(base64_encode(json_encode(array('vendor_code' => $this->data['RelationValue']['vendor_code'], 'vendor_percent' => $this->data['RelationValue']['vendor_percent'])))))?>');
                    });
                </script>
                <?php endif;?>
                <div id="node-3">
                    <?php echo $this->Form->input('order_id', array('label' => ___('Order'), 'type' => 'text'));?>
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
                    <?php echo $this->Form->input('created', array('label' => ___('Date')));?>
                </div>
                <div id="node-4">
                    <?php echo $this->Layout->__input('meta_title', array('label' => ___('Title'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
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


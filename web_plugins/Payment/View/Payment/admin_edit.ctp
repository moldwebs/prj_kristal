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
                    <li><a href="#node-1"><span><?php echo ___('Payment')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Attachments')?></span></a></li>
                    <li><a href="#node-3"><span><?php echo ___('Settings')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                        <?php echo $this->Form->input('extra_1', array('label' => ___('Customer Type'), 'options' => array('0' => ___('All'), '1' => ___('Physical Person'), '2' => ___('Juridical Person'))));?>
                    </div>
                    <?php echo $this->Layout->__input('short_body', array('label' => ___('Description'), 'type' => 'textarea'));?>
                    <?php echo $this->Form->input('code', array('label' => ___('Type', true), 'options' => $payment_types));?>
                    <div id="pay_type_custom">
                        <?php echo $this->Layout->__input('body', array('label' => ___('Content'), 'type' => 'textarea', 'class' => 'redactor'));?>
                    </div>
                    <?php foreach(Configure::read('CMS.payment_type') as $pay_type => $data):?>
                        <div class="n4 cl" id="pay_type_<?php e($pay_type)?>">
                            <?php foreach($data['keys'] as $key => $val):?>
                                <?php echo $this->Form->input('ObjItemList.data.'. $pay_type . '.' . $key, array('label' => $val, 'type' => 'text'));?>
                            <?php endforeach;?>
                        </div>
                    <?php endforeach;?>
                </div>
                <div id="node-5">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <div id="node-3">
                    <?php echo $this->Form->input('order_id', array('label' => ___('Order'), 'type' => 'text'));?>
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
                    <?php echo $this->Form->input('created', array('label' => ___('Date')));?>
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
    $('#ObjItemListCode').live('change', function(){
        $('[id^="pay_type_"]').hide();
        $('#pay_type_' + $(this).val()).show();
    }).trigger('change');
</script>
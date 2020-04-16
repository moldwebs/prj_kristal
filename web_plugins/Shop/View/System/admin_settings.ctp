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
                    <li><a href="#node-4"><span><?php echo ___('Settings')?></span></a></li>
                    <li><a href="#node-1"><span><?php echo ___('Email')?></span></a></li>
                </ul>
                <div id="node-4">
                    <div class="n3 cl">
                    <?php echo $this->Form->input('orders_show', array('label' => ___('Orders List Type'), 'options' => array('' => ___('Large'), '1' => ___('Compact'))));?>
                    </div>
                    <div class="n3 cl">
                    <?php echo $this->Form->input('req_register', array('label' => ___('Required registration for order'), 'options' => array('0' => ___('No'), '1' => ___('Yes'), '2' => ___('Only Request'))));?>
                    </div>
                    <div class="n3 cl">
                    <?php echo $this->Form->input('basket_add_new_item', array('label' => ___('Force New Item To Basket'), 'options' => ws_ny()));?>
                    </div>
                </div>
                <div id="node-1">
                    <?php echo $this->Layout->__input('mail_header', array('label' => ___('Header'), 'type' => 'textarea', 'class' => 'redactor'));?>
                    <?php echo $this->Layout->__input('mail_footer', array('label' => ___('Footer'), 'type' => 'textarea', 'class' => 'redactor'));?>
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
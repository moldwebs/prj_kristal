<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ModDiscount', array('type' => 'file', 'class' => 'ajx_validate'));?>
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
                <?php echo $this->element('/admin/edit_langs')?>
            </div>
        </div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php ___e('Discount')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php echo $this->Form->input('type', array('type' => 'hidden', 'value' => '3'));?>
                    <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                    <div class="n2 cl">
                        <div class="input text">
                            <?php echo $this->Form->input('total', array('label' => ___('Orders amount', true), 'class' => 'req', 'div' => false, 'style' => 'width: 70%'));?>
                            <?php echo $this->Form->input('total_type', array('options' => $currencies, 'empty' => false, 'label' => false, 'div' => false, 'style' => 'width: 25%'));?>
                        </div>
                        <div class="input text">
                            <?php echo $this->Form->input('discount', array('label' => ___('Discount', true), 'class' => 'req', 'div' => false, 'style' => 'width: 70%'));?>
                            <?php echo $this->Form->input('discount_type', array('options' => $discount_types, 'empty' => false, 'label' => false, 'div' => false, 'style' => 'width: 25%'));?>
                        </div>
                    </div>
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
<?php echo $this->Form->end();?>
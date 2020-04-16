<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ModCurrency', array('type' => 'file', 'class' => 'ajx_validate'));?>
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
                </ul>
                <div id="node-1">
                    <div class="n2 cl">
                        <?php echo $this->Form->input('currency', array('label' => ___('Currency'), 'options' => $currencies));?>
                        <?php echo $this->Layout->__input('title', array('label' => ___('Short title'), 'class' => 'req'));?>
                    </div>
                    <div class="n3 cl">
                        <?php echo $this->Layout->__input('long_title', array('label' => ___('Long Title'), 'class' => 'req'));?>
                        <?php echo $this->Form->input('value', array('label' => ___('Value')));?>
                        <?php echo $this->Form->input('position', array('label' => ___('Position'), 'options' => array('0' => ___('After price'), '1' => ___('Before price'))));?>
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

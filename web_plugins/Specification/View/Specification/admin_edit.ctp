<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('Specification', array('type' => 'file', 'class' => 'ajx_validate'));?>
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
                    <li><a href="#node-2"><span><?php echo ___('Description')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Additionally')?></span></a></li>
                    <li><a href="#node-6"><span><?php echo ___('Filters')?></span></a></li>
                    <li><a href="#node-3"><span><?php echo ___('Settings')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php echo $this->Form->input('parent_id', array('options' => $parents, 'label' => ___('Parent'), 'empty' => ___(' --- None --- ')));?>
                    <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                    
                    <?php echo $this->Form->input('extra_2', array('label' => ___('Type'), 'options' => $spec_types, 'empty' => false));?>
                    <?php echo $this->Layout->__input('measure', array('label' => ___('Measure')));?>

                    <?php echo $this->Form->input('Specification.data.in_filter', array('label' => ___('Filter'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('Specification.data.in_option', array('label' => ___('Option'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('Specification.data.in_desc', array('label' => ___('Description'), 'options' => ws_ny()));?>
                </div>
                <div id="node-2">
                    <?php echo $this->Layout->__input('body', array('label' => ___('Content'), 'type' => 'textarea', 'class' => 'redactor'));?>
                </div>
                <div id="node-5">
                    <?php echo $this->Form->input('extra_4', array('options' => $depends, 'label' => ___('Depend'), 'empty' => ___(' --- None --- ')));?>
                    <?php echo $this->Form->input('Specification.data.required', array('label' => ___('Required'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('Specification.data.is_title', array('label' => ___('Is title'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('Specification.data.attribute', array('label' => ___('Attribute')));?>
                    <?php echo $this->Form->input('Specification.data.order', array('label' => ___('Order')));?>
                </div>
                <div id="node-6">
                    <div class="n3 cl">
                        <?php echo $this->Form->input('Specification.data.fltr_range_min', array('label' => ___('Range Min')));?>
                        <?php echo $this->Form->input('Specification.data.fltr_range_step', array('label' => ___('Range Step')));?>
                        <?php echo $this->Form->input('Specification.data.fltr_range_max', array('label' => ___('Range Max')));?>
                    </div>
                    <?php echo $this->Form->input('Specification.data.fltr_values', array('label' => ___('Values (comma separated)'), 'type' => 'textarea'));?>
                </div>
                <div id="node-3">
                    <?php echo $this->Form->input('extra_3', array('options' => $bases, 'label' => ___('Parent Base'), 'empty' => ___(' --- None --- ')));?>
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

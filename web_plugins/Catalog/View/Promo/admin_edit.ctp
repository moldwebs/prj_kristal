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
                    <li><a href="#node-5"><span><?php echo ___('Attachments')?></span></a></li>
                    <li><a href="#node-3"><span><?php echo ___('Settings')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                        <?php echo $this->Layout->__input('alias', array('label' => ___('Alias')));?>
                        <?php echo $this->Layout->__input('date', array('label' => ___('Date'), 'class' => ''));?>
                        <?php if(!empty($parents)) echo $this->Form->input('rel_id', array('options' => $parents, 'label' => ___('Parent'), 'empty' => ___(' --- None --- ')));?>
                    </div>
                    <div class="n3 cl">
                        <?php if(!empty($bases)) echo $this->Form->input('base_id', array('options' => $bases, 'label' => ___('Category'), 'empty' => ___(' --- All --- ')));?>
                        <?php if(!empty($manufacturers)) echo $this->Form->input('extra_2', array('options' => $manufacturers, 'label' => ___('Manufacturer'), 'empty' => ___(' --- All --- ')));?>
                        <?php if(!empty($types)) echo $this->Form->input('extra_1', array('options' => $types, 'label' => ___('Type'), 'empty' => ___(' --- All --- ')));?>
                    </div>
                    <div>
                        <?php echo $this->Layout->__input('body', array('label' => ___('Description'), 'type' => 'textarea', 'class' => 'redactor'));?>
                    </div>
                </div>
                <div id="node-5">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <div id="node-3">
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
                    <?php echo $this->Form->input('ObjItemList.data.list', array('label' => ___('List'), 'options' => ws_ny()));?>
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

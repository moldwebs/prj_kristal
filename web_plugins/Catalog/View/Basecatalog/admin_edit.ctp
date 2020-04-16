<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjItemTree', array('type' => 'file', 'class' => 'ajx_validate'));?>
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?><?php echo $edit_obj?></div>
            <div class="fr">
                <?php echo $this->element('/admin/edit_langs')?>
            </div>
        </div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php echo ___('Content')?></span></a></li>
                    <li><a href="#node-2"><span><?php echo ___('Description')?></span></a></li>
                    <li><a href="#node-6"><span><?php echo ___('Attachments')?></span></a></li>
                    <li><a href="#node-3"><span><?php echo ___('Settings')?></span></a></li>
                    <li><a href="#node-4"><span><?php echo ___('Meta')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Template')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n2 cl">
                        <?php if(!empty($parents)) echo $this->Form->input('parent_id', array('options' => $parents, 'label' => ___('Parent'), 'empty' => ___(' --- None --- ')));?>
                        <?php //if(!empty($parents)) echo $this->Form->input('Relation.base_id', array('options' => $parents, 'label' => ___('Related Category'), 'multiple' => 'multiple', 'class' => 'multiselect'));?>
                        <?php if(!empty($parents)) echo $this->Form->input('rel_id', array('options' => $parents, 'label' => ___('Related Category'), 'empty' => ___(' --- None --- ')));?>
                    </div>
                    <div class="n3 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                        <?php echo $this->Layout->__input('alias', array('label' => ___('Alias')));?>
                        <?php echo $this->Layout->__input('title_prod', array('label' => ___('Title Product')));?>
                    </div>
                    <?php echo $this->Layout->__imagetypeone('icon', array('label' => ___('Icon')));?>
                    <?php echo $this->Layout->__input('body_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
                </div>
                <div id="node-2">
                    <?php echo $this->Layout->__input('body', array('label' => ___('Content'), 'type' => 'textarea', 'class' => 'redactor'));?>
                    <?php echo $this->Layout->__input('body_info', array('label' => ___('Information'), 'type' => 'textarea', 'class' => 'redactor'));?>
                </div>
                <div id="node-6">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <div id="node-3">
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
                    <?php echo $this->Form->input('created', array('label' => ___('Date')));?>
                    <?php echo $this->Form->input('ObjItemTree.data.is_sep', array('label' => ___('Is separator'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('ObjItemTree.data.fltr_price_max', array('label' => ___('Price filter maximum')));?>
                    <?php echo $this->Form->input('ObjItemTree.data.fltr_price_values', array('label' => ___('Price filter values (comma separated)'), 'type' => 'textarea'));?>
                </div>
                <div id="node-4">
                    <?php echo $this->Layout->__input('meta_title', array('label' => ___('Title'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_keyw', array('label' => ___('Keywords'), 'type' => 'textarea'));?>
                </div>
                <div id="node-5">
                    <?php echo $this->Form->input('ObjItemTree.data.template', array('label' => ___('Template'), 'options' => Configure::read('CMS.layouts')));?>
                    <?php echo $this->Form->input('ObjItemTree.data.template_type', array('label' => ___('Template Type')));?>
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

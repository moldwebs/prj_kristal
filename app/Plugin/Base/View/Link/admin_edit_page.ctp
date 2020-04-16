<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjItemTree', array('type' => 'file', 'class' => 'ajx_validate'));?>
        <?php echo $this->Form->input('extra_1', array('value' => '2', 'type' => 'hidden'));?>
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
                    <li><a href="#node-6"><span><?php echo ___('Attachments')?></span></a></li>
                    <li><a href="#node-3"><span><?php echo ___('Template')?></span></a></li>
                    <li><a href="#node-2"><span><?php echo ___('Settings')?></span></a></li>
                    <li><a href="#node-4"><span><?php echo ___('Meta')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php echo $this->Form->input('parent_id', array('options' => $parents, 'label' => ___('Parent'), 'empty' => ___(' --- None --- ')));?>
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                        <?php echo $this->Layout->__input('alias', array('label' => ___('Alias')));?>
                    </div>
                    <?php echo $this->Layout->__input('body', array('label' => ___('Content'), 'type' => 'textarea', 'class' => 'redactor'));?>
                    <?php echo $this->Layout->__imagetypeone('icon', array('label' => ___('Icon')));?>
                </div>
                <div id="node-6">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <div id="node-2">
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
                    <?php echo $this->Form->input('ObjItemTree.data.show_for', array('label' => ___('Visible For'), 'options' => am(array('' => ___('All users'), 'logged' => ___('Logged users'), 'guests' => ___('Not logged users')), array(___('Group') => Configure::read('CMS.user_types')))));?>
                    <?php echo $this->Form->input('ObjItemTree.data.cond_show', array('label' => ___('Visible Conditions') . " (Ex: data.var1=val1)", 'type' => 'textarea'));?>
                    <?php echo $this->Form->input('ObjItemTree.data.no_img', array('label' => ___('No Image'), 'options' => ws_ny()));?>
                </div>
                <div id="node-3">
                    <?php echo $this->Form->input('ObjItemTree.data.css_class', array('label' => ___('CSS Class')));?>
                    <?php echo $this->Form->input('ObjItemTree.data.css_ico', array('label' => ___('CSS Icon')));?>
                    <?php echo $this->Form->input('ObjItemTree.data.template', array('label' => ___('Template'), 'options' => Configure::read('CMS.layouts')));?>
                    <?php echo $this->Form->input('ObjItemTree.data.template_type', array('label' => ___('Template Type')));?>
                </div>
                <div id="node-4">
                    <?php echo $this->Layout->__input('meta_title', array('label' => ___('Title'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
                </div>
            </div>
        </div>
        <div class="nw-table-footer">
            <?php if(!empty($this->data['ObjItemTree']['id'])):?>
                <button onclick="window.location='/admin/base/link/transform/<?php e($this->data['ObjItemTree']['id'])?>/1';" type="button" class="button pill primary"><?php e(___('Transform') . ' :: ' . ___('Link'))?></button>
            <?php endif;?>
            <?php echo $this->Layout->form_save();?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>

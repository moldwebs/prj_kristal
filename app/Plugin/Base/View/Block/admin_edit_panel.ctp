<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjItemTree', array('type' => 'file', 'class' => 'ajx_validate'));?>
        <?php echo $this->Form->input('extra_1', array('value' => '1', 'type' => 'hidden'));?>
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
                    <li><a href="#node-3"><span><?php echo ___('Template')?></span></a></li>
                    <li><a href="#node-2"><span><?php echo ___('Settings')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                        <?php if($this->data['ObjItemTree']['tpl_obj'] != '1') echo $this->Form->input('code', array('label' => ___('Code')));?>
                    </div>
                </div>
                <div id="node-2">
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
                    <?php echo $this->Form->input('ObjItemTree.data.show_for', array('label' => ___('Visible For'), 'options' => am(array('' => ___('All users'), 'logged' => ___('Logged users'), 'guests' => ___('Not logged users')), array(___('Group') => Configure::read('CMS.user_types')))));?>
                    <div class="n2 cl">
                    <?php echo $this->Form->input('ObjItemTree.data.show_on', array('label' => ___('Visible on pages') . " (Ex: /pages/*)", 'type' => 'textarea'));?>
                    <?php echo $this->Form->input('ObjItemTree.data.no_show_on', array('label' => ___('Invisible on pages') . " (Ex: /pages/*)", 'type' => 'textarea'));?>
                    </div>
                    <div class="n2 cl">
                    <?php echo $this->Form->input('ObjItemTree.data.cond_show', array('label' => ___('Visible Conditions') . " (Ex: data.var1=val1)", 'type' => 'textarea'));?>
                    <?php echo $this->Form->input('ObjItemTree.data.variables', array('label' => ___('Variables') . " (Ex: var1=val1)", 'type' => 'textarea'));?>
                    </div>
                </div>
                <div id="node-3">
                    <?php echo $this->Form->input('ObjItemTree.data.custom_template', array('label' => ___('Custom Template'), 'empty' => ___('No'), 'options' => Configure::read('CMS.custom')));?>
                    <?php //echo $this->Form->input('ObjItemTree.data.custom_template', array('label' => ___('Custom Template'), 'options' => ws_ny()));?>
                    <?php //echo $this->Form->input('ObjItemTree.data.custom_template_code', array('label' => ___('Custom Template Code'), 'type' => 'textarea', 'class' => 'redactor_code'));?>
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
    /*
    $('#ObjItemTreeDataCustomTemplate').on('change', function(){
        if($(this).val() == '1'){
            $('#ObjItemTreeDataCustomTemplateCode').parent().show();
        } else {
            $('#ObjItemTreeDataCustomTemplateCode').parent().hide();
        }
    }).trigger('change');
    */
</script>
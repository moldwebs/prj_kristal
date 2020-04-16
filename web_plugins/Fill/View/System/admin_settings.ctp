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
                    <li><a href="#node-1"><span><?php echo ___('Content')?></span></a></li>
                    <li><a href="#node-2"><span><?php echo ___('Meta')?></span></a></li>
                    <li><a href="#node-4"><span><?php echo ___('Settings')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Template')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => ''));?>
                        <?php echo $this->Layout->__input('alias', array('label' => ___('Alias')));?>
                    </div>
                        <?php echo $this->Layout->__input('stitle', array('label' => ___('Subtitle'), 'class' => ''));?>
                    <?php echo $this->Layout->__input('body', array('label' => ___('Description'), 'type' => 'textarea', 'class' => 'redactor'));?>
                </div>
                <div id="node-2">
                    <?php echo $this->Layout->__input('meta_title', array('label' => ___('Title')));?>
                    <?php echo $this->Layout->__input('meta_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_keyw', array('label' => ___('Keywords'), 'type' => 'textarea'));?>
                </div>
                <div id="node-4">
                    <?php echo $this->Form->input('obj_limit', array('label' => ___('Items per page')));?>
                    <?php echo $this->Form->input('obj_order', array('label' => ___('Items order'), 'options' => Configure::read('CMS.base.mod_order_types')));?>
                    <?php echo $this->Form->input('obj_sn_translate', array('label' => ___('Hide not translated items'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('obj_no_views_count', array('label' => ___('No increase views'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('comment_approve', array('label' => ___('Moderators must approve all comments'), 'options' => ws_ny()));?>
                </div>
                <div id="node-5">
                    <?php echo $this->Form->input('template', array('label' => ___('Template'), 'options' => Configure::read('CMS.layouts')));?>
                    <?php echo $this->Form->input('template_type', array('label' => ___('Template Type')));?>
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
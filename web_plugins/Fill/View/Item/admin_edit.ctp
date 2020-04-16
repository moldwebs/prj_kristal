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
                    <li><a href="#node-1"><span><?php echo ___('Content')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Attachments')?></span></a></li>
                    <li><a href="#node-3"><span><?php echo ___('Settings')?></span></a></li>
                    <li><a href="#node-4"><span><?php echo ___('Meta')?></span></a></li>
                    <li><a href="#node-6"><span><?php echo ___('Template')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php if(!empty($bases) || !empty($types)):?>
                    <div class="n4 cl">
                        <?php if(!empty($bases)) echo $this->Form->input('base_id', array('options' => $bases, 'label' => ___('Category'), 'empty' => ___(' --- None --- ')));?>
                        <?php if(!empty($types)) echo $this->Form->input('extra_1', array('options' => $types, 'label' => ___('Type'), 'empty' => array('0' => ___(' --- None --- '))));?>
                        <?php if(!empty($bases)) echo $this->Form->input('Relation.base_id', array('options' => $bases, 'label' => ___('Related Category'), 'multiple' => 'multiple', 'class' => 'multiselect'));?>
                        <?php if(!empty($types)) echo $this->Form->input('Relation.extra_1', array('options' => $types, 'label' => ___('Related Type'), 'multiple' => 'multiple', 'class' => 'multiselect'));?>
                    </div>
                    <?php endif;?>
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                        <?php echo $this->Layout->__input('alias', array('label' => ___('Alias')));?>
                    </div>
                    <?php //echo $this->Layout->__image('attachment', array('label' => ___('Attachment')));?>
                    <?php echo $this->Layout->__input('short_body', array('label' => ___('Short description'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('body', array('label' => ___('Content'), 'type' => 'textarea', 'class' => 'redactor'));?>
                    <?php if(Configure::read('CMS.fill_tid_opts.tags') == '1') echo $this->Form->input('Tags.tags', array('label' => ___('Tags'), 'class' => 'tags', 'rel_url' => '/' . Configure::read('Config.tid') . '/item/get_tags'));?>
                    <?php if(Configure::check('CMS.fill_tid_opts.adfields')) foreach(Configure::read('CMS.fill_tid_opts.adfields') as $key => $val):?>
                        <?php echo $this->Form->input("ObjItemList.data.{$key}", array('label' => ___($val), 'type' => 'text'));?>
                    <?php endforeach;?>
                </div>
                <div id="node-5">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <div id="node-3">
                    <?php echo $this->Form->input('user_id', array('rel' => '/admin/users/users/get_json', 'class' => 'ui_autocomplete', 'type' => 'text', 'label' => ___('User')));?>
                    <?php echo $this->Form->input('order_id', array('label' => ___('Order'), 'type' => 'text'));?>
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
                    <div class="n2 cl">
                    <?php echo $this->Form->input('created', array('label' => ___('Date')));?>
                    <?php echo $this->Form->input('ObjItemList.data.days_long', array('label' => ___('Days (active)'), 'type' => 'text'));?>
                    </div>
                    <?php echo $this->Form->input('ObjItemList.data.no_img', array('label' => ___('No Image'), 'options' => ws_ny()));?>
                </div>
                <div id="node-4">
                    <?php echo $this->Layout->__input('meta_title', array('label' => ___('Title'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
                </div>
                <div id="node-6">
                    <?php echo $this->Form->input('ObjItemList.data.template', array('label' => ___('Template'), 'options' => Configure::read('CMS.layouts')));?>
                    <?php echo $this->Form->input('ObjItemList.data.template_type', array('label' => ___('Template Type')));?>
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

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
                    <li><a href="#node-2-1"><span><?php echo ___('Meta')?> <?php echo ___('Category')?></span></a></li>
                    <li><a href="#node-2-2"><span><?php echo ___('Meta')?> <?php echo ___('Product')?></span></a></li>
                    <li><a href="#node-4"><span><?php echo ___('Settings')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Template')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title')));?>
                        <?php echo $this->Layout->__input('alias', array('label' => ___('Alias')));?>
                    </div>
                    <?php echo $this->Layout->__input('body', array('label' => ___('Description'), 'type' => 'textarea', 'class' => 'redactor'));?>
                </div>
                <div id="node-2">
                    <?php echo $this->Layout->__input('meta_title', array('label' => ___('Title')));?>
                    <?php echo $this->Layout->__input('meta_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_keyw', array('label' => ___('Keywords'), 'type' => 'textarea'));?>
                </div>
                <div id="node-2-1">
                    <?php echo $this->Layout->__input('meta_base_title', array('label' => ___('Title')));?>
                    <?php echo $this->Layout->__input('meta_base_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_base_keyw', array('label' => ___('Keywords'), 'type' => 'textarea'));?>
                </div>
                <div id="node-2-2">
                    <?php echo $this->Layout->__input('meta_item_title', array('label' => ___('Title')));?>
                    <?php echo $this->Layout->__input('meta_item_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_item_keyw', array('label' => ___('Keywords'), 'type' => 'textarea'));?>
                </div>
                <div id="node-4">
                    <?php echo $this->Form->input('obj_limit', array('label' => ___('Items per page')));?>
                    <?php echo $this->Form->input('obj_order', array('label' => ___('Items order'), 'options' => Configure::read('CMS.catalog.mod_order_types')));?>
                    <?php echo $this->Form->input('obj_sn_translate', array('label' => ___('Hide not translated items'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('comment_approve', array('label' => ___('Moderators must approve all comments'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('obj_combinations', array('label' => ___('Combinations'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('obj_qnt', array('label' => ___('Stock quantity'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('obj_qnt_preorder', array('label' => ___('Preorder quantity'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('price_dec_max', array('label' => ___('Decimals for prices lower')));?>
                    <?php echo $this->Layout->__input('no_price_txt', array('label' => ___('No price text')));?>
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
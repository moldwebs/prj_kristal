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
                    <?php if(count(Configure::read('CMS.languages')) > 1):?><li><a href="#node-3"><span><?php echo ___('Languages')?></span></a></li><?php endif;?>
                    <li><a href="#node-4"><span><?php echo ___('Search')?></span></a></li>
                    <li><a href="#node-8"><span><?php echo ___('Head')?></span></a></li>
                    <li><a href="#node-6"><span><?php echo ___('Counter')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Maintenance')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php echo $this->Layout->__input('title', array('label' => ___('Site Title'), 'class' => 'req'));?>
                    <?php echo $this->Form->input('contact_phone', array('label' => ___('Contact Phone')));?>
                    <?php echo $this->Form->input('contact_email', array('label' => ___('Contact Email')));?>
                    <?php echo $this->Layout->__input('subtitle', array('label' => ___('Site Subtitle')));?>
                    <?php echo $this->Layout->__input('copyright', array('label' => ___('Copyright')));?>
                    <?php echo $this->Layout->__image2path('logo', array('label' => ___('Logo')));?>
                    <?php echo $this->Layout->__input('body', array('label' => ___('Home Page'), 'type' => 'textarea', 'class' => 'redactor'));?>
                </div>
                <div id="node-2">
                    <?php echo $this->Layout->__input('meta_title', array('label' => ___('Title')));?>
                    <?php echo $this->Layout->__input('meta_desc', array('label' => ___('Description'), 'type' => 'textarea'));?>
                    <?php echo $this->Layout->__input('meta_keyw', array('label' => ___('Keywords'), 'type' => 'textarea'));?>
                </div>
                <?php if(count(Configure::read('CMS.languages')) > 1):?>
                <div id="node-3">
                    <?php echo $this->Form->input('active_languages', array('label' => ___('Active Languages'), 'options' => Configure::read('CMS.languages'), 'selected' => json_decode($this->data['CmsSetting']['active_languages']), 'multiple' => 'multiple', 'class' => 'multiselect'));?>
                    <?php echo $this->Form->input('def_language', array('label' => ___('Default Language'), 'options' => Configure::read('CMS.languages')));?>
                </div>
                <?php endif;?>
                <div id="node-4">
                    <?php echo $this->Form->input('obj_limit', array('label' => ___('Items per page')));?>
                    <?php echo $this->Form->input('obj_order', array('label' => ___('Items order'), 'options' => Configure::read('CMS.base.mod_order_types')));?>
                </div>
                <div id="node-8">
                    <?php echo $this->Form->input('head_meta', array('label' => ___('Head code'), 'type' => 'textarea', 'rows' => '20'));?>
                </div>
                <div id="node-6">
                    <?php echo $this->Form->input('counter_script', array('label' => ___('Counter script code'), 'type' => 'textarea', 'rows' => '20'));?>
                </div>
                <div id="node-5">
                    <?php echo $this->Form->input('disable', array('label' => ___('Disable Site'), 'options' => ws_ny()));?>
                    <?php echo $this->Layout->__input('maintenance_body', array('label' => ___('Body'), 'type' => 'textarea', 'class' => 'redactor'));?>
                    <?php echo $this->Form->input('maintenance_ips', array('label' => ___('Allow Ip\'s'), 'type' => 'textarea'));?>
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

<script>
    $('#tx_create').click(function(){
        if($('#tx_field').val() != ''){
            $('#tx_box_exist').append($('#tx_box').html().replace(/tx_null_field/g, "tx_" + $('#tx_field').val()).replace(/tx_null_label/g, $('#tx_field').val()));
            $('#tx_field').val('');
        }
    });
</script>
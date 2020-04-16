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
                    <li><a href="#node-1"><span><?php echo ___('Registration')?></span></a></li>
                    <li><a href="#node-2"><span><?php echo ___('Social')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Template')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php echo $this->Form->input('confirm', array('label' => ___('Confirm registration'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('account_data', array('label' => ___('Fill account data after registration (social)'), 'options' => ws_ny()));?>
                    <?php echo $this->Form->input('url_after_register', array('label' => ___('Go to after register (url)')));?>
                    <?php echo $this->Form->input('url_after_login', array('label' => ___('Go to after login (url)')));?>
                </div>
                <div id="node-2">
                    <?php foreach(Configure::read('CMS.social_login') as $key => $val):?>
                        <div class="n3 cl">
                            <?php echo $this->Form->input($key . '_enabled', array('label' => $key, 'options' => ws_ny()));?>
                            <?php if(!empty($val)) foreach($val as $_key => $_val):?>
                                <?php echo $this->Form->input($key . '_' . $_val, array('label' => ucfirst($_val), 'type' => 'text'));?>
                            <?php endforeach;?>
                        </div>
                    <?php endforeach;?>                                    
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
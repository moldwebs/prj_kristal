<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('User', array('type' => 'file', 'class' => 'ajx_validate'));?>
        <div class="nw-table-title"><?php echo $page_title?></div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php echo ___('User')?></span></a></li>
                    <li><a href="#node-7"><span><?php echo ___('Description')?></span></a></li>
                    <li><a href="#node-8"><span><?php echo ___('Data')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Attachments')?></span></a></li>
                    <li><a href="#node-2"><span><?php echo ___('Settings')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php echo $this->Form->input('username', array('label' => ___('Name'), 'class' => 'req', 'autocomplete' => 'off'));?>
                    <?php echo $this->Form->input('usermail', array('label' => ___('Email'), 'autocomplete' => 'off'));?>
                    <?php echo $this->Form->input('password', array('label' => ___('Password'), 'class' => (empty($this->request->data['User']['id']) ? 'req' : null), 'autocomplete' => 'off', 'value' => ''));?>
                    <?php echo $this->Form->input('role', array('options' => Configure::read('CMS.user_types'), 'label' => ___('Role')));?>
                </div>
                <div id="node-7">
                    <?php echo $this->Layout->__input('body', array('label' => ___('Content'), 'type' => 'textarea', 'class' => 'redactor'));?>
                </div>
                <div id="node-5">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <div id="node-2">
                    <?php echo $this->Form->input('status', array('label' => ___('Active'), 'options' => ws_yn()));?>
                    <?php echo $this->Form->input('created', array('label' => ___('Date')));?>
                </div>
                <div id="node-8">
                    <div class="n4 cl">
                    <?php echo $this->Form->input("User.extra_1", array('label' => 'extra_1'));?>
                    <?php echo $this->Form->input("User.extra_2", array('label' => 'extra_2'));?>
                    <?php echo $this->Form->input("User.extra_3", array('label' => 'extra_3'));?>
                    
                    <?php if(!empty($this->request->data['User']['data'])) foreach($this->request->data['User']['data'] as $_key => $_val):?>
                        <?php echo $this->Form->input("User.data.{$_key}", array('label' => $_key));?>
                    <?php endforeach;?>
                    </div>
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

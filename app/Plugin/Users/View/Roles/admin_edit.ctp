<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('UserRole', array('type' => 'file', 'class' => 'ajx_validate'));?>
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
                <?php echo $this->element('/admin/edit_langs')?>
            </div>
        </div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php echo ___('Role')?></span></a></li>
                    <li><a href="#node-2"><span><?php echo ___('Settings')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                </div>
                <div id="node-2">
                    <?php echo $this->Form->input('type', array('label' => ___('Edit & Remove'), 'options' => array('0' => ___('Any conent'), '1' => ___('Belonging conent'), '2' => ___('Any conent') . ' / ' . ___('Admin check'), '3' => ___('Belonging conent') . ' / ' . ___('Admin check'))));?>
                    <?php echo $this->Form->input('status', array('label' => ___('Active'), 'options' => ws_yn()));?>
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

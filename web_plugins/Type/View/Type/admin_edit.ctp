<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjOptType', array('type' => 'file', 'class' => 'ajx_validate'));?>
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
                </ul>
                <div id="node-1">
                    <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                    <?php echo $this->Form->input('code', array('label' => ___('Code')));?>
                    <?php echo $this->Form->input('ObjOptType.data.class', array('label' => ___('Class')));?>
                    <?php echo $this->Form->input('ObjOptType.data.text', array('label' => ___('Text')));?>
                </div>
                <div id="node-5">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <div id="node-3">
                    <div class="n3 cl">
                        <?php echo $this->Form->input('auto_set', array('label' => ___('Autoset for new items'), 'options' => ws_ny()));?>
                        <?php echo $this->Form->input('auto_set_expire', array('label' => ___('Autoset expire days')));?>
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

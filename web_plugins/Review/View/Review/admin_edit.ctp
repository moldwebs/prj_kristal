<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjOptReview', array('type' => 'file', 'class' => 'ajx_validate'));?>
        <?php echo $this->Form->input('status', array('value' => '1', 'type' => 'hidden'));?>
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
            </div>
        </div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php echo ___('Content')?></span></a></li>
                    <li><a href="#node-5"><span><?php echo ___('Attachments')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php if(!empty($objects)) echo $this->Form->input('item_id', array('options' => $objects, 'label' => ___('Item'), 'class' => 'req'));?>
                    <?php echo $this->Form->input('username', array('label' => ___('User')));?>
                    <?php echo $this->Form->input('comment', array('label' => ___('Review')));?>
                    
                    <div class="n8 cl">
                        <?php foreach($rating_types as $key => $val):?>
                            <?php echo $this->Form->input('ObjOptReview.data.rating.' . $key, array('label' => $val, 'options' => $rating));?>
                        <?php endforeach;?>
                    </div>
                </div>
                <div id="node-5">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
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

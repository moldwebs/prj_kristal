<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjItemList', array('type' => 'file', 'class' => ''));?>
        <div class="nw-table-title"><?php echo $page_title?></div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php echo ___('Content')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php echo $this->Form->input('mode', array('label' => ___('Mode'), 'options' => array('1' => ___('Create'), '2' => ___('Upload'))));?>
                    <div id="box_1">
                        <?php echo $this->Form->input('title', array('label' => ___('Filename')));?>
                        <?php echo $this->Form->input('body', array('label' => ___('Body'), 'type' => 'textarea'));?>
                    </div>
                    <div id="box_2" style="display: none;">
                        <?php echo $this->Form->input('attachment', array('label' => ___('File'), 'type' => 'file'));?>
                    </div>
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
    $('#ObjItemListMode').on('change', function(){
        $('#box_1').toggle();
        $('#box_2').toggle();
    });
</script>
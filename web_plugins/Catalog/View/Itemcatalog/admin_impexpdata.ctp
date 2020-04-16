<div class="grid_16">
    <div class="nw-table">
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
            </div>
        </div>
        <div class="nw-table-content">
            <?php if(!empty($make)):?>
                <div id="make_box" style="padding: 20px;">
                    Wait...
                </div>
                <script>
                    function make_call(){
                        jQuery.ajax({
                            type: "GET",
                            url: "/admin/catalog/item/impexpdata?make=1",
                            success: function(response){
                                if (response == 'ok') {
                                    $('#make_box').html('READY');
                                }
                                else {
                                    make_call();
                                }
                            }
                
                        });
                    }
                    make_call();
                </script>
            <?php else:?>
            <div style="float: left; width: 45%; padding: 20px;">
                <?php echo $this->Form->create('Export', array('type' => 'file', 'class' => 'ajx_submit'));?>
                    <input type="hidden" name="data[Export][make]" value="1" />
                    <div class="nw-table-form">
                        <div class="n3 cl">
                            <?php if(!empty($bases)) echo $this->Form->input('base_id', array('options' => $bases, 'label' => ___('Category'), 'empty' => ___(' --- All --- ')));?>
                            <?php if(!empty($types)) echo $this->Form->input('extra_1', array('options' => $types, 'label' => ___('Type'), 'empty' => ___(' --- All --- ')));?>
                            <?php if(!empty($manufacturers)) echo $this->Form->input('extra_2', array('options' => $manufacturers, 'label' => ___('Manufacturer'), 'empty' => ___(' --- All --- ')));?>
                        </div>
                        <div class="submit">
                            <input type="submit" class="button" value="<?php ___e('Export')?>" />
                        </div>
                    </div>
                <?php echo $this->Form->end();?>
            </div>
            <div style="float: right; width: 45%; padding: 20px;">
                <?php echo $this->Form->create('Import', array('type' => 'file', 'class' => ''));?>
                    <input type="hidden" name="data[Import][make]" value="1" />
                    <div class="nw-table-form">
                        <div class="n2 cl">
                            <?php echo $this->Form->input('file', array('label' => ___('File'), 'type' => 'file', 'class' => ''));?>
                        </div>
                        <div class="submit">
                            <input type="submit" class="button long_action" value="<?php ___e('Import')?>" />
                        </div>
                    </div>
                <?php echo $this->Form->end();?>
            </div>
            <div style="clear: both;"></div>
            <?php endif;?>
        </div>
    </div>
</div>

<div class="clear"></div>
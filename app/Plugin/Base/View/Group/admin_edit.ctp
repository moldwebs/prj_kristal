<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjItemTree', array('type' => 'file', 'class' => 'ajx_validate'));?>
        <?php echo $this->Form->input('extra_1', array('value' => '6', 'type' => 'hidden'));?>
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
                    <li><a href="#node-6"><span><?php echo ___('Attachments')?></span></a></li>
                    <li><a href="#node-2"><span><?php echo ___('Settings')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n3 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                        <?php echo $this->Form->input('ObjItemTree.data.title_url', array('label' => ___('Link')));?>
                    </div>
                    <?php echo $this->Form->input('ObjItemTree.data.type', array('options' => Configure::read('CMS.mod_blocks'), 'label' => ___('Contain'), 'empty' => ___('Select...'), 'class' => ''));?>
                    <div id="contain_box">
                        <div id="box_script">
                            <?php echo $this->Layout->__input('body_script', array('label' => ___('Content'), 'type' => 'textarea'));?>
                        </div>
                        <div id="box_text">
                            <?php //echo $this->Form->input('ObjItemTree.data.text', array('label' => ___('Text'), 'type' => 'textarea'));?>
                            <?php echo $this->Layout->__input('body_text', array('label' => ___('Content'), 'type' => 'textarea'));?>
                        </div>
                        <div id="box_html">
                            <?php //echo $this->Form->input('ObjItemTree.data.html', array('label' => ___('Html'), 'type' => 'textarea', 'class' => 'redactor'));?>
                            <?php echo $this->Layout->__input('body', array('label' => ___('Content'), 'type' => 'textarea', 'class' => 'redactor'));?>
                        </div>
                        <div id="box_html_block">
                            <?php //echo $this->Form->input('ObjItemTree.data.html', array('label' => ___('Html'), 'type' => 'textarea', 'class' => 'redactor'));?>
                            <?php echo $this->Layout->__input('body_block', array('label' => ___('Content'), 'type' => 'textarea', 'class' => 'redactor'));?>
                        </div>
                        <div id="box_module">
                            <?php ___('Loading...')?>
                        </div>
                    </div>
                </div>
                <div id="node-6">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <div id="node-2">
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
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

<script>
    $('#ObjItemTreeDataType').on('change', function(){
        $('#contain_box').children('div').hide();
        if($(this).val().substring(0,4) == 'mod_' || $(this).val() == 'custom'){
            if($(this).val() == 'custom'){
                $('#contain_box > #box_module').html('').show();
            } else {
                $('#contain_box > #box_module').load('/admin/' + $(this).val().substring(4) + '/system/get_blocks?id=<?php e($this->data['ObjItemTree']['id'])?>').show();
            }
            //$('#ObjItemTreeDataCustomTemplate').trigger('change').parent().show();
        } else {
            $('#contain_box > #box_module').empty();
            $('#contain_box > #box_' + $(this).val()).show();
            //$('#ObjItemTreeDataCustomTemplate').parent().hide();
            //$('#ObjItemTreeDataCustomTemplateCode').parent().hide();
        }
        <?php if(empty($this->data['ObjItemTree']['id'])):?>
        if($(this).val() == 'script' || $(this).val() == 'text' || $(this).val() == 'html'){
            $('#ObjItemTreeDataBlockTemplate').val('0').trigger('change');
        } else {
            $('#ObjItemTreeDataBlockTemplate').val('1').trigger('change');
        }
        <?php endif;?>
    }).trigger('change');
</script>

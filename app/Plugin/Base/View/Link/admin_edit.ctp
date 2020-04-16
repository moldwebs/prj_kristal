<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('ObjItemTree', array('type' => 'file', 'class' => 'ajx_validate'));?>
        <?php echo $this->Form->input('extra_1', array('value' => '0', 'type' => 'hidden'));?>
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
                    <li><a href="#node-3"><span><?php echo ___('Template')?></span></a></li>
                    <li><a href="#node-2"><span><?php echo ___('Settings')?></span></a></li>
                </ul>
                <div id="node-1">
                    <?php echo $this->Form->input('parent_id', array('options' => $parents, 'label' => ___('Parent'), 'empty' => ___(' --- None --- ')));?>
                    <div class="n2 cl">
                        <?php echo $this->Layout->__input('title', array('label' => ___('Title'), 'class' => 'req'));?>
                        <?php echo $this->Layout->__input('alias', array('label' => ___('Alias')));?>
                    </div>
                    <?php echo $this->Form->input('ObjItemTree.data.type', array('options' => Configure::read('CMS.mod_links'), 'label' => ___('Path'), 'empty' => ___('Select...'), 'class' => 'req'));?>
                    <div id="contain_box">
                        <div id="box_url">
                            <?php echo $this->Form->input('ObjItemTree.data.url', array('label' => ___('Url')));?>
                        </div>
                        <div id="box_module">
                            <?php ___('Loading...')?>
                        </div>
                    </div>
                    <?php echo $this->Layout->__imagetypeone('icon', array('label' => ___('Icon')));?>
                </div>
                <div id="node-6">
                    <?php echo $this->Layout->__images('attachments', array('label' => false));?>
                </div>
                <div id="node-2">
                    <?php echo $this->Form->input('status', array('label' => ___('Status'), 'options' => ws_vh()));?>
                    <?php echo $this->Form->input('ObjItemTree.data.show_for', array('label' => ___('Visible For'), 'options' => am(array('' => ___('All users'), 'logged' => ___('Logged users'), 'guests' => ___('Not logged users')), array(___('Group') => Configure::read('CMS.user_types')))));?>
                    <?php echo $this->Form->input('ObjItemTree.data.cond_show', array('label' => ___('Visible Conditions') . " (Ex: data.var1=val1)", 'type' => 'textarea'));?>
                </div>
                <div id="node-3">
                    <?php echo $this->Form->input('ObjItemTree.data.css_class', array('label' => ___('CSS Class')));?>
                    <?php echo $this->Form->input('ObjItemTree.data.css_ico', array('label' => ___('CSS Icon')));?>
                </div>
            </div>
        </div>
        <div class="nw-table-footer">
            <?php if(!empty($this->data['ObjItemTree']['id'])):?>
                <button onclick="window.location='/admin/base/link/transform/<?php e($this->data['ObjItemTree']['id'])?>/2';" type="button" class="button pill primary"><?php e(___('Transform') . ' :: ' . ___('Page'))?></button>
            <?php endif;?>
            <?php echo $this->Layout->form_save();?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>

<script>
    $('#ObjItemTreeDataType').on('change', function(){
        $('#contain_box').children('div').hide();
        if($(this).val().substring(0,4) == 'mod_'){
            $('#contain_box > #box_module').load('/admin/' + $(this).val().substring(4) + '/system/get_links?id=<?php e($this->data['ObjItemTree']['id'])?>').show();
        } else {
            $('#contain_box > #box_module').empty();
            $('#contain_box > #box_' + $(this).val()).show();
        }

    }).trigger('change');
</script>
<?php if(empty($this->passedArgs[0])) $this->passedArgs[0] = '0'?>
<?php if(empty($this->passedArgs[1])) $this->passedArgs[1] = 'base'?>

<div class="grid_4">
    <div class="nw-table">
        <?php echo $this->Form->create('UserRolePerm', array('type' => 'get'));?>
        <div class="nw-table-title"><?php ___e('Filter')?></div>
        <div class="nw-table-content pd">
            <?php echo $this->Form->input('module', array('options' => $modules, 'selected' => $this->passedArgs[1], 'label' => ___('Module', true), 'empty' => false, 'onchange' => "window.location='/admin/users/roles/permissions/{$this->passedArgs[0]}/' + this.value"));?>
            <?php echo $this->Form->input('group', array('options' => $groups, 'selected' => $this->passedArgs[0], 'label' => ___('Group', true), 'empty' => array('0' => ___('All groups', true)), 'onchange' => "window.location='/admin/users/roles/permissions/' + this.value + '/{$this->passedArgs[1]}'"));?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="grid_12">
    <div class="nw-table">
        <div class="nw-table-title"><?php ___e('Permissions');?></div>
        <div class="nw-table-content pd">
            <div class="admin_permissions">
            <?php foreach($modules_actions[$this->passedArgs[1]] as $controller => $actions):?>
                <div>
                    <fieldset>
                        <legend><?php ___e(ucfirst($controller))?></legend>
                        <div class="fieldset_content">
                        <?php foreach($actions as $action):?>
                            <a href="/admin/users/roles/set_permission/<?php e($this->passedArgs[0])?>/<?php e($this->passedArgs[1])?>/<?php e($controller)?>/<?php e($action)?>" class="perm_save button primary icon <?php echo (isset($permissions[$this->passedArgs[1]][$controller][$action]) ? 'approve' : 'add')?>"><?php ___e($action)?></a>
                        <?php endforeach;?>
                        </div>
                    </fieldset>
                </div>
            <?php endforeach;?>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>

<script>
    $(function() {
        $('.perm_save').bind('click', function(){
            $.get($(this).attr('href'));
            $(this).toggleClass('add');
            $(this).toggleClass('approve');
            return false;
        });
    });
</script>
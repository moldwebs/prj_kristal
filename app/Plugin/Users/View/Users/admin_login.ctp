<div class="grid_6" style="margin: 10% 25%;">
    <div class="nw-table">
        <?php echo $this->Form->create('User', array('type' => 'file'));?>
        <div class="nw-table-title"><?php ___e('Login')?></div>
        <div class="nw-table-content">
                <?php echo $this->Form->input('usermail', array('label' => ___('Email'), 'autocomplete' => 'on'));?>
                <?php echo $this->Form->input('password', array('label' => ___('Password'), 'autocomplete' => 'on', 'value' => ''));?>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Log in'))?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>

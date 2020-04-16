<form action="<?php e($this->here)?>" method="POST" class="ajx_submit" style="width: 400px;">
    <?php echo $this->Form->input('message', array('label' => ___('Message')));?>
    <div class="input submit">
        <?php echo $this->Form->button(___('Save'), array('class' => 'button primary'))?>
    </div>
</form>
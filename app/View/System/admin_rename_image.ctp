<form action="<?php e($this->here)?>" method="POST" class="ajx_submit" style="width: 300px;">
    <?php echo $this->Form->input('ObjOptAttach.title', array('label' => ___('Title')));?>
    <?php echo $this->Form->input('ObjOptAttach.type', array('label' => ___('Type')));?>
    <div class="input submit">
        <?php echo $this->Form->button(___('Save'), array('class' => 'button primary'))?>
    </div>
</form>
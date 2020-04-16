<form action="<?php e($this->here)?>" method="POST" class="ajx_submit" style="width: 400px;">
    <?php echo $this->Form->input('qnt', array('label' => ___('Quantity')));?>
    <div class="input submit">
        <?php echo $this->Form->button(___('Make Order'), array('class' => 'button primary'))?>
    </div>
</form>
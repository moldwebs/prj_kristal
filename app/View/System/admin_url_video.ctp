<form action="<?php e($this->here)?>" method="POST" class="ajx_submit" style="width: 400px;">
    <?php echo $this->Form->input('url', array('label' => ___('Url')));?>
    <div class="input submit">
        <?php echo $this->Form->button(___('Insert'), array('class' => 'button primary'))?>
    </div>
</form>
<?php $this->extend('layout');?>

<?php echo $this->Form->create('User', array('type' => 'file', 'class' => 'form-horizontal'));?>
    
    <div class="col-sm-12">
        <div class="box-border">
            <ul>
                <li class="row">
                    <div class="col-sm-6">
                        <?php echo $this->Form->input('username', array('label' => ___('Name'), 'required' => 'required', 'autocomplete' => 'off', 'class' => 'req input form-control', 'div' => false));?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo $this->Form->input('usermail', array('label' => ___('Email'), 'type' => 'email', 'required' => 'required', 'autocomplete' => 'off', 'class' => 'req input form-control', 'div' => false));?>
                    </div>
                </li>
                <li class="row">&nbsp;</li>
                <li>
                    <button type="submit" class="button btn btn-primary"><?php ___e('Continue')?></button>
                </li>
            </ul>
        </div>
    </div>
</form>
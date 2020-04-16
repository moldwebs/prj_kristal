<?php $this->extend('layout');?>

<?php echo $this->Form->create('User', array('type' => 'file', 'class' => 'form-horizontal'));?>

    <div class="col-md-6 col-sm-12">
        <div class="box-border">
            <ul>
                <li class="row">
                    <div class="col-sm-12">
                        <?php echo $this->Form->input("opassword", array('label' => ___('Old password', true), 'type' => 'password', 'required' => 'required', 'class' => 'req input form-control', 'autocomplete' => 'off', 'div' => false));?> 
                    </div>
                </li>
                <li class="row">
                    <div class="col-sm-12">
                        <?php echo $this->Form->input("password", array('label' => ___('New password', true), 'type' => 'password', 'required' => 'required', 'class' => 'req input form-control', 'autocomplete' => 'off', 'div' => false));?>
                    </div>
                </li>
                <li class="row">
                    <div class="col-sm-12">
                        <?php echo $this->Form->input("rpassword", array('label' => ___('Repeat new password', true), 'type' => 'password', 'required' => 'required', 'class' => 'req input form-control', 'autocomplete' => 'off', 'div' => false));?>
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
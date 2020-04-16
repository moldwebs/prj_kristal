<?php $this->extend('layout');?>

<?php echo $this->Form->create('User', array('type' => 'file', 'class' => 'form-horizontal ajx_validate'));?>
    
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
                <li class="row">
                    <div class="col-sm-6">
                        <?php echo $this->Form->input('password', array('label' => ___('Password'), 'class' => 'req input form-control', 'autocomplete' => 'off', 'value' => '', 'type' => 'password', 'required' => 'required', 'div' => false));?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo $this->Form->input('rpassword', array('label' => ___('Repeat Password'), 'class' => 'req input form-control', 'autocomplete' => 'off', 'value' => '', 'type' => 'password', 'required' => 'required', 'div' => false));?>
                    </div>
                </li>
                <li class="row">&nbsp;</li>
                <li class="row">
                    <div class="col-sm-12">
                          <label><?php ___e('Security Code')?></label>
                          <input type="text" name="data[captcha]" value="" required="required" class="input form-control" />
                          <br /><img src="/system/captcha" />
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
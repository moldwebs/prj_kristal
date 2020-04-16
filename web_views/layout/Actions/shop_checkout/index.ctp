<?php $this->extend('layout');?>

<?php echo $this->Form->create('Checkout', array('type' => 'file', 'autocomplete' => 'off', 'class' => 'form-horizontal'));?>

<div style="padding: 20px;">
<div class="form-group required">
  <label class="col-sm-2 control-label" for="input-shipping-city"><?php ___e('Name')?></label>
  <div class="col-sm-10">
    <?php echo $this->Form->input('name', array('label' => false, 'required' => 'required', 'class' => 'form-control req', 'div' => false, 'autocomplete' => 'off'));?>
  </div>
</div>
<div class="form-group required">
  <label class="col-sm-2 control-label" for="input-shipping-city"><?php ___e('Phone')?></label>
  <div class="col-sm-10">
    <?php echo $this->Form->input('phone', array('label' => false, 'required' => 'required', 'class' => 'form-control req', 'div' => false, 'autocomplete' => 'off'));?>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label" for="input-shipping-city"><?php ___e('Phone 2')?></label>
  <div class="col-sm-10">
    <?php echo $this->Form->input('phone_alt', array('label' => false, 'class' => 'form-control', 'div' => false, 'autocomplete' => 'off'));?>
  </div>
</div>
<div class="form-group required">
  <label class="col-sm-2 control-label" for="input-shipping-city"><?php ___e('Email')?></label>
  <div class="col-sm-10">
    <?php echo $this->Form->input('email', array('label' => false, 'required' => 'required', 'class' => 'form-control req', 'div' => false, 'autocomplete' => 'off'));?>
  </div>
</div>

<div class="buttons">
    <div class="pull-left"><a href="javascript:history.go(-1)" class="btn btn-default"><?php ___e('Back')?></a></div>
    <div class="pull-right">
        <input type="submit" value="<?php ___e('Next')?>" id="button-shipping-method" class="btn btn-primary" />
    </div>
</div>
</div>

</form>
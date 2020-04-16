<?php $this->extend('layout');?>

<?php echo $this->Form->create('Payment', array('type' => 'file', 'class' => 'form-horizontal'));?>

<p>
    <?php echo $this->Form->input('customer_type', array('label' => false, 'required' => 'required', 'class' => 'form-control req', 'options' => array('0' => ___('Physical Person'), '1' => ___('Juridical Person')), 'div' => false));?>
</p>

<div style="padding: 10px;" id="box_juridical_data">
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-shipping-city"><?php ___e('Company name')?></label>
      <div class="col-sm-8">
        <?php echo $this->Form->input('jur_company', array('label' => false, 'required' => 'required', 'class' => 'form-control req', 'div' => false));?>
      </div>
    </div>

    <div class="form-group ">
      <label class="col-sm-4 control-label" for="input-shipping-city"><?php ___e('Juridical adress')?></label>
      <div class="col-sm-8">
        <?php echo $this->Form->input('jur_adress', array('label' => false, 'required' => '', 'class' => 'form-control ', 'div' => false));?>
      </div>
    </div>

    <div class="form-group ">
      <label class="col-sm-4 control-label" for="input-shipping-city"><?php ___e('VAT code')?></label>
      <div class="col-sm-8">
        <?php echo $this->Form->input('jur_tax', array('label' => false, 'required' => '', 'class' => 'form-control ', 'div' => false));?>
      </div>
    </div>

    <div class="form-group ">
      <label class="col-sm-4 control-label" for="input-shipping-city"><?php ___e('IBAN')?></label>
      <div class="col-sm-8">
        <?php echo $this->Form->input('jur_iban', array('label' => false, 'required' => '', 'class' => 'form-control ', 'div' => false));?>
      </div>
    </div>

    <div class="form-group ">
      <label class="col-sm-4 control-label" for="input-shipping-city"><?php ___e('Bank')?></label>
      <div class="col-sm-8">
        <?php echo $this->Form->input('jur_bank', array('label' => false, 'required' => '', 'class' => 'form-control ', 'div' => false));?>
      </div>
    </div>

    <div class="form-group ">
      <label class="col-sm-4 control-label" for="input-shipping-city"><?php ___e('Bank Code')?></label>
      <div class="col-sm-8">
        <?php echo $this->Form->input('jur_bank_code', array('label' => false, 'required' => '', 'class' => 'form-control ', 'div' => false));?>
      </div>
    </div>    

    <div class="form-group ">
      <label class="col-sm-4 control-label" for="input-shipping-city"><?php ___e('Cod Fiscal')?></label>
      <div class="col-sm-8">
        <?php echo $this->Form->input('cod_fisc', array('label' => false, 'required' => '', 'class' => 'form-control ', 'div' => false));?>
      </div>
    </div>    

    <div class="form-group ">
      <label class="col-sm-4 control-label" for="input-shipping-city"><?php ___e('Adresa Postala')?></label>
      <div class="col-sm-8">
        <?php echo $this->Form->input('post_address', array('label' => false, 'required' => '', 'class' => 'form-control ', 'div' => false));?>
      </div>
    </div>    

</div>

<div style="clear: both;">&nbsp;</div>
<p>
    <strong><?php ___e('Total for payment including delivery')?>: <?php e($basket['html_price'])?> <?php e($basket['html_currency'])?></strong>
</p>
<div>&nbsp;</div>

<p><strong><?php ___e('Select payment method')?></strong></p>
<p>
    <div style="padding-left: 30px;" id="box_payment_type"></div>
</p>

<div class="buttons">
    <div class="pull-left"><a href="javascript:history.go(-1)" class="btn btn-default"><?php ___e('Back')?></a></div>
    <div class="pull-right">
        <input type="submit" value="<?php ___e('Save')?>" id="button-shipping-method" class="btn btn-primary" />
    </div>
</div>


</form>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        $('[name="data[Payment][customer_type]"]').on('change', function(){
            if($('[name="data[Payment][customer_type]"]').val() == '1'){
                $('#box_juridical_data').show();
                $('#box_juridical_data').find('input,select').removeAttr('disabled');
            } else {
                $('#box_juridical_data').find('input,select').attr('disabled', 'disabled');
                $('#box_juridical_data').hide();
            }
            $('#box_payment_type').load('/shop/checkout/payment_type/' + $('[name="data[Payment][customer_type]"]').val());
        }).trigger('change');
    });
</script>
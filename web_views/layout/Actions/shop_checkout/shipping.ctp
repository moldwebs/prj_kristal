<?php $this->extend('layout');?>

<?php echo $this->Form->create('Checkout', array('type' => 'file', 'class' => 'form-horizontal'));?>

<?php if(empty($zones)):?>
    <input type="hidden" name="data[Checkout][zone_id]" />
<?php else:?>
<p><strong><?php ___e('Location')?></strong></p>
<p>
    <?php echo $this->Form->input('zone_id', array('label' => false, 'options' => $zones, 'class' => 'form-control req', 'required' => 'required', 'div' => false));?>
</p>
<div>&nbsp;</div>
<?php endif;?>

<p><strong><?php ___e('Select delivery method')?></strong></p>

<div style="padding-left: 30px;" id="box_shipping_zone_price"></div>

<div style="padding: 20px;" id="box_shipping_data">
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-shipping-city"><?php ___e('Street address')?></label>
      <div class="col-sm-10">
        <?php echo $this->Form->input('street', array('label' => false, 'required' => 'required', 'class' => 'form-control req', 'div' => false));?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-shipping-city"><?php ___e('Appartment / Office')?></label>
      <div class="col-sm-10">
        <?php echo $this->Form->input('appartment', array('label' => false, 'class' => 'form-control', 'div' => false));?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-shipping-country"><?php ___e('Floor')?></label>
      <div class="col-sm-10">
        <?php echo $this->Form->input('floor', array('label' => false, 'options' => $floors, 'empty' => array('0' => ___('Ground')), 'class' => 'form-control', 'div' => false));?>
      </div>
    </div>
    
    <div class="form-row" id="box_shipping_lifting_price"></div>
</div>

<div>&nbsp;</div>
<p><strong><?php ___e('Comentarii')?></strong></p>
<p>
    <textarea name="data[Checkout][comment]" rows="8" class="form-control"></textarea>
</p>

<div class="buttons">
    <div class="pull-left"><a href="/shop/basket" class="btn btn-default"><?php ___e('Back')?></a></div>
    <div class="pull-right">
        <input type="submit" value="<?php ___e('Save')?>" id="button-shipping-method" class="btn btn-primary" />
    </div>
</div>

</form>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {

        $("body").delegate('[name="data[Checkout][zone_id]"]', "change", function() {
            var obj = this;
            $('#box_shipping_zone_price').load('/shop/checkout/shipping_zone_price/' + $(obj).val(), function(){
                $('[name="data[Checkout][shipping]"]:checked').trigger('change');
            });
            $.get('/shop/checkout/shipping_zone_location/' + $(obj).val(), function(data){
                $(obj).parent().nextAll().find('[name="data[Checkout][zone_id]"]').parent().remove();
                if(data != ''){
                    $('[name="data[Checkout][zone_id]"]:last').parent().after(data);
                    $('[name="data[Checkout][zone_id]"]:last').trigger('change');
                }
            });
        });
        
        $('[name="data[Checkout][zone_id]"]').trigger('change');
        
        $("body").delegate('[name="data[Checkout][shipping]"]', "change", function() {
            if($('[name="data[Checkout][shipping]"]:checked').attr('pickup') != '1'){
                $('#box_shipping_data').show();
                $('#box_shipping_data').find('input,select').removeAttr('disabled');
                $('[name="data[Checkout][floor]"]').trigger('change');
            } else {
                $('#box_shipping_data').find('input,select').attr('disabled', 'disabled');
                $('#box_shipping_data').hide();
            }
        });
        $("body").delegate('[name="data[Checkout][floor]"]', "change", function() {
            $('#box_shipping_lifting_price').load('/shop/checkout/shipping_lifting_price/' + $('[name="data[Checkout][shipping]"]:checked').val() + '/' + $('[name="data[Checkout][floor]"]').val());
        });
    });
</script>
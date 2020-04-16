<?php if(!empty($zones)):?>
    <div>
        <?php echo $this->Form->input('Checkout.zone_id', array('label' => false, 'options' => $zones, 'class' => 'form-control req', 'required' => 'required', 'div' => false));?>
    </div>
<?php endif;?>
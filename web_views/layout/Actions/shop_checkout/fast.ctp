<?php echo $this->Form->create(false, array('type' => 'file', 'class' => 'form-horizontal ajx_validate'));?>
    <div style="width: 100%; min-width: 500px;" class="col-sm-12">
        <div class="box-border">
            <h3 class="product-title"><?php ___e('Fast Order')?></h3>
            <ul>
                <li class="row">&nbsp;</li>
                <li class="row">
                    <div class="col-sm-10">
                        <?php echo $this->Form->input('name', array('label' => ___('Name'), 'required' => 'required', 'autocomplete' => 'off', 'class' => 'req input form-control', 'div' => false));?>
                    </div>
                </li>
                <li class="row">&nbsp;</li>
                <li class="row">
                    <div class="col-sm-10">
                        <?php echo $this->Form->input('phone', array('label' => ___('Phone'), 'class' => 'req input form-control', 'autocomplete' => 'off', 'value' => '', 'required' => 'required', 'div' => false));?>
                    </div>
                </li>
                <li class="row">&nbsp;</li>
                <li class="row">&nbsp;</li>
                <li>
                    <button type="submit" class="button btn btn-primary"><?php ___e('Submit')?></button>
                </li>
            </ul>
        </div>
    </div>
</form>
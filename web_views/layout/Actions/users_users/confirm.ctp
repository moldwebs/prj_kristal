<?php $this->extend('layout');?>

<?php echo $this->Form->create('User', array('type' => 'file', 'class' => 'form-horizontal'));?>

    <div class="col-md-6 col-sm-12">
        <div class="box-border">
            <ul>
                <li class="row">
                    <div class="col-sm-12">
                        <?php echo $this->Form->input('code', array('label' => ___('Code'), 'autocomplete' => 'off', 'class' => 'req input form-control', 'required' => 'required', 'div' => false));?>
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

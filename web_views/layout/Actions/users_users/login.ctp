<?php $this->extend('layout');?>

<div class="row">
    <div class="market-info col-md-5">
        <h3 class="title_block"><?php ___e('New Customers')?></h3>
        <p><?php ___e('By creating an account with our store, you will be able to move through the checkout process faster, view and track your orders in your account and more.')?></p>
        <button onclick="window.location='/users/register'" class="button btn btn-primary theme-button marL0"><i class="fa fa-user"></i> <?php ___e('Create an account')?></button>
        <div>&nbsp;</div>
        <h3><?php ___e('Social Network')?></h3>
        <?php echo $this->aelement('/users_users/social');?>
    </div>

    <div class="col-md-1"></div>

    <div class="market-info col-md-6">
        <?php echo $this->Form->create('User', array('type' => 'file'));?>
        <h3 class="title_block"><?php ___e('Registered Customers')?></h3>
        <?php echo $this->Form->input('usermail', array('label' => ___('Email'), 'class' => 'req form-control input', 'required' => 'required', 'div' => false));?>  
        <?php echo $this->Form->input('password', array('label' => ___('Password'), 'class' => 'req form-control', 'value' => '', 'required' => 'required', 'div' => false));?>
        <p class="forgot-pass"><a href="/users/forget"><?php ___e('Forgot your password?')?></a></p>
        <button type="submit" class="button btn btn-primary theme-button marL0"><i class="fa fa-lock"></i> <?php ___e('Sign in')?></button>
        </form>
    </div>
</div>
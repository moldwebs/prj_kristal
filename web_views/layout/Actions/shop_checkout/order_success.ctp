<?php $this->extend('layout');?>

<div class="page-content">
    <div class="information-blocks login-box">
        <div class="information-entry maxWidthForm">

                <div class="form-row">
                    <label><?php ___e('Vă mulțumim comanda dvs a fost procesată cu succes')?></label>
                    <ul class="withPadding">
                        <li>
                            <?php ___e('Numărul comenzii')?>: <span class="red-message"><?php e($order['ModOrder']['id'])?></span>
                        </li>
                        <li>
                            <?php ___e('Data procesării')?>: <span class="red-message"><?php e(date("d.m.Y"))?></span>
                        </li>
                        <li>
                           <?php ___e('În timpul apropiat unul din operatorii vă va contacta!')?>
                       </li>
                   </ul>
               </div>
        </div>
    </div>  
</div>
<?php if(!empty($payment['ObjItemList']['body'])):?>
<div>&nbsp;</div>
<div>
    <?php e($payment['ObjItemList']['body'])?>
</div>
<?php endif;?>
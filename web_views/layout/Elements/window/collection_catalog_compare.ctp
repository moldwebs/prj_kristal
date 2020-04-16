<div style="padding: 20px; text-align: center;">
    <br />
    <?php ___e('Item added successfull to compare.')?>
    <?php if($get['count'] > 1):?>
    <br />
    <br />
    <a href="/catalog/compare/<?php e($get['base_id'])?>"><?php ___e('Go to compare')?></a>
    <?php endif;?>
</div>
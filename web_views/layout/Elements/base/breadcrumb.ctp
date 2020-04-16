<?php if(!empty($cms['breadcrumbs'])):?>
<div class="breadcrumb-box">
      <ul class="breadcrumb">
         <li>
            <a href="<?php eurl('/')?>"><?php ___e('Home')?></a>
         </li>
         <?php foreach($cms['breadcrumbs'] as $key => $val):?>
         <li class="active">
            <a href="<?php e($key)?>"><?php et($val, 100)?></a>
         </li>
         <?php endforeach;?>
      </ul>
</div>
<?php endif;?>
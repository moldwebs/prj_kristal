<?php if(!empty($block['data'])):?>
<ul class="nav nav-pills nav-stacked">
  <?php foreach($block['data'] as $key => $val):?>
      <li class="<?php if($val['active']) e('active')?>"><a href="<?php eurl($val['ObjItemTree']['alias'])?>"><strong><?php e($val['ObjItemTree']['title'])?></strong></a></li>
  <?php endforeach;?>
</ul>
<?php endif;?>
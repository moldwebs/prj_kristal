<?php if(!empty($menu['links'])):?>
    <?php foreach($menu['links'] as $key => $val):?>
        <li> <a href="<?php e($val['data']['url'])?>" title="<?php e($val['title'])?>" rel="nofollow" > <?php e($val['title'])?> </a></li>
     <?php endforeach;?>
<?php endif;?>
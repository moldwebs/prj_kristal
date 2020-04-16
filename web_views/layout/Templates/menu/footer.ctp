<?php if(!empty($menu['links'])):?>
    <ul class="footer_block_content bullet ">
    <?php foreach($menu['links'] as $key => $val):?>
        <li class="<?php if($key == count($menu['links'])-1) e('last')?>"><a href="<?php e($val['data']['url'])?>" ><?php e($val['title'])?></a></li>
    <?php endforeach;?>
    </ul>
<?php endif;?>
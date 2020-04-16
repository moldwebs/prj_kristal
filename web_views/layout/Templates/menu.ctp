<?php if(!empty($menu['links'])) foreach($menu['links'] as $key => $val):?>
    <a href="<?php e($val['data']['url'])?>" class="btm_links <?php if((next($menu['links']))) e('first_btm_link')?> <?php e($val['data']['css_class'])?> <?php e($val['active'] == '1' ? 'active_top' : null)?>"><?php e($val['title'])?></a>
<?php endforeach;?>
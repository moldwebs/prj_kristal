<?php if(!empty($cms['active_base'])):?>
<a target="_blank" href="/catalog/item/compare/<?php e($cms['active_base'])?>/?sys_layout=compare" class="top_compare">
	<img src="/img/compare_red.png" />
    <span><?php e(count($user_collection['compare_' . $cms['active_base']]))?></span>
</a>
<?php endif;?>
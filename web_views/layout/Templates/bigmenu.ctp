<?php if(!empty($menu['links'])):?>
    <nav class="headerMenu" role="navigation">
        <ul class="headerMenuList">
            <?php foreach($menu['links'] as $key => $val):?>
                <li class="headerMenuItem"><a class="headerMenuItemLink <?php e($val['data']['css_class'])?> <?php if(!empty($val['child'])) e('js_headerMenuSubAction')?>" data-submenu="<?php if(!empty($val['child'])) e('headerSubMenuProducts' . $key)?>" href="<?php e($val['data']['url'])?>"><?php e($val['title'])?></a></li>
            <?php endforeach;?>
        </ul>
    </nav>
<?php endif;?>
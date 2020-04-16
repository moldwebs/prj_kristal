<?php if(!empty($block['data'])):?>
<div class="layered layered-category">
    <div class="layered-content">
        <ul class="tree-menu">
            <?php foreach($block['data'] as $item):?>
            <li>
                <?php if(!empty($item['children'])):?>
                    <span></span><a href="<?php eurl($item['ObjItemTree']['alias'])?><?php e(!empty($_GET['fltr_lk__title']) ? "?fltr_lk__title={$_GET['fltr_lk__title']}" : null)?>"><?php e($item['ObjItemTree']['title'])?></a>
                    <ul>
                        <?php foreach($item['children'] as $_item):?>
                        <li><span></span><a href="<?php eurl($_item['ObjItemTree']['alias'])?><?php e(!empty($_GET['fltr_lk__title']) ? "?fltr_lk__title={$_GET['fltr_lk__title']}" : null)?>"><?php e($_item['ObjItemTree']['title'])?></a></li>
                        <?php endforeach;?>
                    </ul>
                <?php else:?>
                    <a href="<?php eurl($item['ObjItemTree']['alias'])?><?php e(!empty($_GET['fltr_lk__title']) ? "?fltr_lk__title={$_GET['fltr_lk__title']}" : null)?>"><?php e($item['ObjItemTree']['title'])?></a>
                <?php endif;?>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
<?php endif;?>
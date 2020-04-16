<?php if(!empty($menu['links'])):?>
<div class="row">
    <?php foreach($menu['links'] as $key => $val):?>
    <div class="col-sm-4">
        <div class="introduce-title"><?php e($val['title'])?></div>
        <ul  class="introduce-list">
            <?php foreach($val['child'] as $_key => $_val):?>
            <li><a href="<?php e($_val['data']['url'])?>"><?php e($_val['title'])?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
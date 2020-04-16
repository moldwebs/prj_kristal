<?php if(!empty($items)):?>
    <option value=""><?php ___e('Choose...')?></option>
    <?php foreach($items as $item):?>
        <option value="<?php e($item[$alias]['id'])?>" <?php if($selected == $item[$alias]['id']) e('selected="selected"')?>><?php e($item[$alias]['title'])?></option>
    <?php endforeach;?>
<?php endif;?>
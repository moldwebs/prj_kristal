<?php if(!empty($items)):?>
    <option value=""><?php ___e('Choose...')?></option>
    <?php foreach($items as $key => $val):?>
        <option value="<?php e($key)?>" <?php if($selected == $key) e('selected="selected"')?>><?php e($val)?></option>
    <?php endforeach;?>
<?php endif;?>
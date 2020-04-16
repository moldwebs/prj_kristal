<?php if(!empty($items)):?>
    <?php foreach($items as $item):?>
        <div class="radio">
            <label>
                <input type="radio" name="data[Checkout][lifting]" <?php e($i++ == 0 ? 'checked="checked"' : null)?> value="<?php e($item['id'])?>" />
                <?php ___e($item['title'])?><?php e(!empty($item['html_price']) ? ' - ' . $item['html_price'] : null)?>
            </label>
        </div>
    <?php endforeach;?>
<?php else:?>
<div style="font-size: 80%;" class="red-message"><?php ___e('They found no lifting methods')?></div>
<?php endif;?>

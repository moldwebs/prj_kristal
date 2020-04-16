<?php if(!empty($items)):?>
    <?php foreach($items as $key => $item):?>
        <div class="radio-inline">
            <label>
                <input type="radio" name="data[Payment][payment]" <?php e($key == 0 ? 'checked="checked"' : null)?> value="<?php e($item['id'])?>" />
                <?php e($item['title'])?><?php e(!empty($item['short_body']) ? ' - ' . $item['short_body'] : null)?>
            </label>
        </div>
    <?php endforeach;?>
<?php else:?>
<div style="font-size: 80%;" class="red-message"><?php ___e('They found no payment methods')?></div>
<?php endif;?>
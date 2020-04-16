<?php if(!empty($block['data'])):?>
    <div class="pr_list_in">
        <?php foreach($block['data'] as $item):?>
            <a href="<?php eurl($item['ObjItemList']['alias'])?>" class="pr_link">
            	<?php if(!empty($item['ObjItemList']['extra_1'])):?>
                <span class="<?php e($fill_types[$item['ObjItemList']['extra_1']]['ObjOptType']['data']['class'])?>">
                    <?php if(!empty($fill_types[$item['ObjItemList']['extra_1']]['ObjOptType']['data']['text'])):?>
                        <span><?php e($fill_types[$item['ObjItemList']['extra_1']]['ObjOptType']['data']['text'])?></span>
                    <?php endif;?>
                </span>
                <?php endif;?>
            	<span class="pr_lis_img"><img src="/getimages/142x142x2/thumb/<?php e($item['ObjOptAttachDef']['attach'])?>" alt="<?php eth($item['ObjItemList']['title'])?>"></span>
                <span class="pr_list_desc"><?php eth($item['ObjItemList']['title'], 50)?></span>
                <span class="price">
                    <?php if($item['Price']['old'] > 0):?>
                    	<span class="cut_price"><?php e($item['Price']['html_old'])?> <?php e($item['Price']['html_currency'])?></span>
                    <?php endif;?>
                    <span class="promo_price"><?php e($item['Price']['html_value'])?> <?php e($item['Price']['html_currency'])?></span>
                </span>
            </a>
        <?php endforeach;?>
    </div>
<?php endif;?>
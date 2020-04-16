<?php if(empty($block['data'])) $block['data'] = $this->requestAction('/shop/basket/get_list')?>

<?php if(!empty($block['data']['qnt'])):?>
    <div id="blockcart_top_wrap" class="blockcart_wrap blockcart_mod shopping_cart_style_1 pull-left">
      <a id="shopping_cart" href="/shop/basket" rel="nofollow" class="shopping_cart clearfix header_item">
         <div class="ajax_cart_left icon_wrap"> <i class="icon-basket icon-0x icon_btn"></i> <span class="icon_text"><?php ___e('Cart')?></span> <span class="ajax_cart_quantity amount_circle constantly_show"><?php e($block['data']['qnt'])?></span></div>
         <span class="ajax_cart_quantity ajax_cart_middle"><?php e($block['data']['qnt'])?></span> <span class="ajax_cart_product_txt ajax_cart_middle">item(s)</span> <span class="ajax_cart_split ajax_cart_middle">-</span> <span class="ajax_cart_total ajax_cart_right"> <?php e($block['data']['html_price'])?> <?php e($block['data']['html_currency'])?> </span> 
      </a>
      <div id="cart_block" class="cart_block block exclusive">
         <div class="block_content">
            <div class="cart_block_list">
               <dl class="products">
                <?php foreach($block['data']['items'] as $id => $item):?>
                <dt class="clearfix first_item">
                   <a class="cart-images" href="<?php eurl($item['data']['ObjItemList']['alias'])?>"><img src="/getimages/45x51x0/thumb/<?php e($item['data']['ObjOptAttachDef']['attach'])?>" class="replace-2x" width="45" height="51" /></a>
                   <span class="quantity-formated"><span class="quantity"><?php e($item['qnt'])?></span>x</span>
                   <a class="cart_block_product_name" href="<?php eurl($item['data']['ObjItemList']['alias'])?>"><?php e($item['data']['ObjItemList']['title'])?></a> 
                   <span class="remove_link"> <a class="" href="/shop/basket/delete/<?php e($id)?>" rel="nofollow"><i class="icon-cancel icon-small"></i></a> </span> 
                   <span class="price">
                      <?php e($item['html_price'])?> <?php e($item['html_currency'])?>
                      <div class="hookDisplayProductPriceBlock-price"></div>
                   </span>
                </dt>
                <?php endforeach;?>
               </dl>
               <div class="cart-prices">
                  <div class="cart-prices-line last-line"> <span class="price cart_block_total ajax_block_cart_total"><?php e($block['data']['html_price'])?> <?php e($block['data']['html_currency'])?></span> <span><?php ___e('Total')?></span></div>
               </div>
               <p class="cart-buttons"> <a class="btn btn-default" href="/shop/basket" rel="nofollow"><?php ___e('Check out')?></a></p>
            </div>
         </div>
      </div>
    </div>
<?php else:?>
   <div id="blockcart_top_wrap" class="blockcart_wrap blockcart_mod shopping_cart_style_1 pull-left">
      <a id="shopping_cart" href="/shop/basket" rel="nofollow" class="shopping_cart clearfix header_item">
         <div class="ajax_cart_left icon_wrap"> <i class="icon-basket icon-0x icon_btn"></i> <span class="icon_text"><?php ___e('Cart')?></span> <span class="ajax_cart_quantity amount_circle constantly_show">0</span></div>
         <span class="ajax_cart_quantity ajax_cart_middle">0</span> <span class="ajax_cart_product_txt ajax_cart_middle"><?php ___e('item(s)')?></span> <span class="ajax_cart_split ajax_cart_middle">-</span> <span class="ajax_cart_total ajax_cart_right"> <?php e($basket_data['html_price'])?> <?php e($basket_data['html_currency'])?> </span> 
      </a>
   </div>
<?php endif;?>
<?php if(empty($block['data'])) $block['data'] = $this->requestAction('/shop/basket/get_list')?>

<?php if(!empty($block['data']['qnt'])):?>
    <div id="blockcart_mobile_wrap" class="blockcart_wrap st-side-content">
    <div id="cart_block_mobile" class="cart_block block exclusive">
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
             <div class="cart-prices ">
                <div class="cart-prices-line last-line"> <span class="price cart_block_total ajax_block_cart_total"><?php e($block['data']['html_price'])?> <?php e($block['data']['html_currency'])?></span> <span><?php ___e('Total')?></span></div>
             </div>
             <p class="cart-buttons "> <a class="btn btn-default" href="/shop/basket" rel="nofollow"><?php ___e('Check out')?></a></p>
          </div>
       </div>
    </div>
    </div>
<?php else:?>
    <div id="blockcart_mobile_wrap" class="blockcart_wrap st-side-content">
    <div id="cart_block_mobile" class="cart_block block exclusive">
       <div class="block_content">
          <div class="cart_block_list">
             <p class="cart_block_no_products alert alert-warning"> <?php ___e('No products')?></p>
          </div>
       </div>
    </div>
    </div>
<?php endif;?>
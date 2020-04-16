<ul class="wa-products-icon">
    <li><a href="#" onclick="geteval('/users/collections/catalog_wish/<?php e($item['ObjItemList']['id'])?>'); return false;" title="<?php ___e('Add to wishlist')?>"><i class="fa fa-heart-o"></i></a></li>
    <li><a href="<?php eurl($item['ObjItemList']['alias'])?>?modal=1" class="quickview-box-btn" title="<?php ___e('Quick View')?>"><i class="fa fa-eye"></i></a></li>
    <li><a href="/shop/basket/add/<?php e($item['ObjItemList']['id'])?>/0/1" title="<?php ___e('Add to cart')?>"><i class="fa fa-shopping-basket"></i></a></li>
</ul>
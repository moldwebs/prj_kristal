<?php
class ModDiscount extends ShopAppModel {
    
    public $useTable = 'wb_mod_discount';
    
    public $actsAs = array('Translate' => array('title'), 'Data');

    var $virtualFields = array(
        'used' => "SELECT SUM(wb_mod_order_item.ext) FROM wb_mod_order_item WHERE type LIKE 'coupon%' AND wb_mod_order_item.item_id = ModDiscount.id AND wb_mod_order_item.order_id NOT IN (SELECT id FROM wb_mod_order WHERE onstatus = '5')",
    );
}
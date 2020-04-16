<?php
class ModOrder extends ShopAppModel {
    
    public $useTable = 'wb_mod_order';
    public $nocache = '1';
    
    public $actsAs = array('Tid', 'Translate' => array('payment'));

    public $hasMany = array(
        'ModOrderItem' => array(
            'className' => 'Shop.ModOrderItem',
            'foreignKey' => 'order_id',
            'dependent' => true,
            'order' => 'ModOrderItem.id'
        ),
        'ModOrderAction' => array(
            'className' => 'Shop.ModOrderAction',
            'foreignKey' => 'order_id',
            'dependent' => true,
            'order' => array('ModOrderAction.created' => 'desc'),
            'callbacks' => true,
            'force' => '1'
        ),
    );

    public $belongsTo = array(
        'Operator' => array(
            'className'    => 'Users.User',
            'foreignKey' => 'operator_id',
        ),
    );

    public function afterFind($results, $primary = false) {
        foreach($results as $key => $val){
            if(!empty($val[$this->alias]['data'])) {
                $results[$key][$this->alias]['data'] = json_decode($val[$this->alias]['data'], true);
            }
            if(!empty($val[$this->alias]['long_data'])) {
                $results[$key][$this->alias]['long_data'] = json_decode($val[$this->alias]['long_data'], true);
            }
            if(!empty($val['ModOrderItem'])) {
                $ModOrderItem = array();
                foreach($val['ModOrderItem'] as $_key => $_val){
                    $ModOrderItem[$_val['type']][$_val['id']] = $_val;
                }
                $results[$key]['ModOrderItem'] = $ModOrderItem;
            }
        }
        return $results;
    }    

    public function beforeSave($options = array()) {
        if(!empty($this->data[$this->alias]['data'])){
            $this->data[$this->alias]['data'] = json_encode($this->data[$this->alias]['data']);
        }
        if(!empty($this->data[$this->alias]['long_data'])){
            $this->data[$this->alias]['long_data'] = json_encode($this->data[$this->alias]['long_data']);
        }
        return true;
    }
    
    function get_data($basket = array()){

        $value_dec = Configure::read('CMS.currency_decimals');
        if($value_dec > 0){
            $value_dec_trim = '';
        } else {
            $value_dec_trim = '.00';
        }

        Configure::write('Config.tid', 'catalog');
        $basket['errors'] = array();
        if(!empty($basket['items'])){
            $currency = Configure::read('Obj.currency');
            $currencies_vals = Configure::read('Obj.currencies_vals');
            
            $ObjItemList = ClassRegistry::init('ObjItemList');
            if(Configure::read('PLUGIN.Specification') == '1') $ObjItemList->Behaviors->load('Specification.Specification', array('tid' => 'catalog'));
            $ObjItemList->Behaviors->load('Catalog.Catalog');
            
            $load_ids = array();
            foreach($basket['items'] as $b_key => $b_item) $load_ids[] = $b_item['id'];
           
            $items = $ObjItemList->find('allindex', array('tid' => false, 'conditions' => array('ObjItemList.id' => $load_ids)));
            
            foreach($basket['items'] as $b_key => $b_item){
                $item = $items[$b_item['id']];
                
                if(!($b_item['qnt'] > 0) || empty($item)){
                    unset($basket['items'][$b_key]);
                    continue;
                }
                
                if(Configure::read('CMS.settings.catalog.obj_qnt') == '1' && Configure::read('CMS.settings.catalog.obj_qnt_preorder') != '1'){
                    if($item['ObjItemList']['qnt'] < $b_item['qnt']){
                        //$b_item['qnt'] = $item['ObjItemList']['qnt'];
                        if($item['ObjItemList']['qnt'] > 0){
                            $basket['errors'][] = $item['ObjItemList']['title'] . ' ' .  ___("maximum quantity") . ' ' . $item['ObjItemList']['qnt'];
                        } else {
                            $basket['errors'][] = $item['ObjItemList']['title'] . ' ' .  ___("out of stock");                           
                        }                        
                    }
                }

                $basket['items'][$b_key]['ext_data']['wrnt'] = $item['ObjItemList']['data']['wrnt'];
                $basket['items'][$b_key]['ext_data']['extra'] = $b_item['extra'];
                if(!empty($item['ObjItemList']['rel_id'])) $basket['items'][$b_key]['ext_data']['options'] = $item['Specification'];
                
                if(!in_array($item['ObjItemList']['id'], $rel_disc_items)) foreach($items as $rel_disc_item) if(!empty($rel_disc_item['RelationValue']['product_id'])) if(array_key_exists($item['ObjItemList']['id'], $rel_disc_item['RelationValue']['product_id'])){
                    $item['Price']['value'] = ($item['Price']['value'] / 100) * (100 - $rel_disc_item['RelationValue']['product_id'][$item['ObjItemList']['id']]);
                    $item['Price'] = ws_price_format($item['Price'], $item['Price']['html_currency'], $value_dec);
                    $basket['items'][$b_key]['ext_data']['extra']['rel_discount'] = ($rel_disc_item['ObjItemList']['rel_id'] ? $rel_disc_item['ObjItemList']['rel_id'] : $rel_disc_item['ObjItemList']['id']);
                    $rel_disc_items[] = $item['ObjItemList']['id'];
                    foreach($basket['items'] as $_b_item){
                        if($_b_item['id'] == $rel_disc_item['ObjItemList']['id']){
                            if($b_item['qnt'] > $_b_item['qnt']){
                                $basket['items'][$b_key]['qnt'] = $_b_item['qnt'];
                            }
                            break;
                        }
                    } 
                }

                $basket['items'][$b_key]['data'] = $item;
                $basket['items'][$b_key]['price'] = round($item['Price']['value'], 2);
                $basket['items'][$b_key]['currency'] = $item['Price']['currency'];
                $basket['items'][$b_key]['price_total'] = round($item['Price']['value'] * $basket['items'][$b_key]['qnt'], 2);
                $basket['items'][$b_key]['price_related_total'] = $basket['items'][$b_key]['price_total'];
                if(!empty($item['RelationValue']['vendor_price'])) $basket['items'][$b_key]['ext_data']['vendor'] = array(
                    'vendor_id' => key($item['RelationValue']['vendor_price']),
                    'vendor_code' => $item['RelationValue']['vendor_code'][key($item['RelationValue']['vendor_price'])],
                    'vendor_price' => $item['RelationValue']['vendor_price'][key($item['RelationValue']['vendor_price'])],
                    'vendor_cprice' => $item['RelationValue']['vendor_cprice'][key($item['RelationValue']['vendor_price'])],
                );
                
                /*
                if(!empty($basket['items'][$item_id]['supl'])) foreach($basket['items'][$item_id]['supl'] as $_item_id => $b__item){
                    $_item = $items[$_item_id];
                    $basket['items'][$item_id]['supl'][$_item_id]['data'] = $_item;
                    $basket['items'][$item_id]['supl'][$_item_id]['price'] = $_item['Price']['value'];
                    $basket['items'][$item_id]['supl'][$_item_id]['currency'] = $_item['Price']['currency'];

                    $basket['items'][$item_id]['price_total'] = $basket['items'][$item_id]['price_total'] + (($_item['Price']['value'] * $basket['items'][$item_id]['supl'][$_item_id]['qnt']) * $basket['items'][$item_id]['qnt']);
                }
                */
                
                $basket['items'][$b_key]['html_price'] = ($basket['items'][$b_key]['price'] > 0 ? str_replace($value_dec_trim, '', number_format($basket['items'][$b_key]['price'], 2, '.', ' ')) : $item['Price']['html_value']);
                $basket['items'][$b_key]['html_currency'] = $item['Price']['html_currency'];
                $basket['items'][$b_key]['html_price_total'] = ($basket['items'][$b_key]['price_total'] > 0 ? str_replace($value_dec_trim, '', number_format($basket['items'][$b_key]['price_total'], 2, '.', ' ')) : $item['Price']['html_value']);
                $basket['items'][$b_key]['html_price_related_total'] = ($basket['items'][$b_key]['price_related_total'] > 0 ? str_replace($value_dec_trim, '', number_format($basket['items'][$b_key]['price_related_total'], 2, '.', ' ')) : $item['Price']['html_value']);

                for($i = 1; $i <= $basket['items'][$b_key]['qnt']; $i++) $basket['weights'][] = $item['ObjItemList']['data']['weight'];
                $basket['weight_total'] = $basket['weight_total'] + ($basket['items'][$b_key]['qnt'] * $item['ObjItemList']['data']['weight']);

                
                $basket['qnt'] = $basket['qnt'] + $basket['items'][$b_key]['qnt'];
                $basket['price'] = $basket['price'] + $basket['items'][$b_key]['price_total'];
                $basket['html_sub_price'] = str_replace($value_dec_trim, '', number_format($basket['price'], 2, '.', ' '));

            }

            $basket['related'] = array();
            foreach($basket['items'] as $b_key => $b_item){
                if(!empty($b_item['related'])){
                    $basket['related'][$b_item['related']][$b_key] = $b_item;
                    $basket['items'][$b_item['related']]['price_related_total'] = $basket['items'][$b_item['related']]['price_related_total'] + $b_item['price_total'];
                    $basket['items'][$b_item['related']]['html_price_related_total'] = str_replace($value_dec_trim, '', number_format($basket['items'][$b_item['related']]['price_related_total'], 2, '.', ' '));
                }
            }
            
            $basket['items_price'] = round($basket['price'], 2);

            foreach($basket['extra'] as $key => $val) if(substr($key, 0, 6) == 'coupon') unset($basket['extra'][$key]);
            
            if(!empty($basket['coupon'])){
                $cupon_error = null;
                
                $_discount = ClassRegistry::init('Shop.ModDiscount')->find('all', array('conditions' => array('ModDiscount.code' => $basket['coupon'], 'ModDiscount.type' => '1')));

                if(!function_exists('sort_1')){
                    function sort_1($a, $b) {
                        if ($a['ModDiscount']['data']['item_id'] == $b['ModDiscount']['data']['item_id']) {
                            return 0;
                        }
                        return ($a['ModDiscount']['data']['item_id'] < $b['ModDiscount']['data']['item_id']) ? 1 : -1;
                    }
                }
        
                usort($_discount, "sort_1");
                
                if(empty($_discount)){
                    $cupon_error = ___('Codul este introdus gresit.');
                } else {
                    
                    $_from_amount = $basket['price'];
                    foreach($_discount as $key => $discount){
                        if(strtotime($discount['ModDiscount']['expire']) > 0 && strtotime($discount['ModDiscount']['expire']) < strtotime(date("Y-m-d"))){
                            $cupon_error = ___('Codul este introdus expirat.');
                        } else if($discount['ModDiscount']['use_type'] > 0 && $discount['ModDiscount']['used'] >= $discount['ModDiscount']['use_type']){
                            $cupon_error = ___('Codul introdus a fost utilizat.');
                        } else {
                            
                            $from_amount = null;
                            
                            if(!empty($discount['ModDiscount']['data']['item_id'])){
                                foreach($basket['items'] as $b_key => $b_item){
                                    if($discount['ModDiscount']['data']['item_id'] == $b_item['id'] && empty($b_item['ext_data']['extra']['rel_discount'])){
                                        $from_qnt = $b_item['qnt'];
                                        if($discount['ModDiscount']['use_type'] > 0 && $from_qnt > ($discount['ModDiscount']['use_type'] - $discount['ModDiscount']['used'])){
                                            $from_qnt = ($discount['ModDiscount']['use_type'] - $discount['ModDiscount']['used']);
                                            $cupon_error = ___('Codul a fost utilizat la %s produs(e).', $from_qnt);
                                        }
                                        $from_amount = $b_item['price'] * $from_qnt;
                                        $_from_amount = $_from_amount - $b_item['price'] * $from_qnt;
                                    }
                                }
                            } else {
                                $from_qnt = '1';
                                $from_amount = $_from_amount;
                            }

                            if(!empty($from_amount)){
                                
                                if($discount['ModDiscount']['discount_type'] == '%'){
                                    $discount['ModDiscount']['price'] = - round(($from_amount / 100) * $discount['ModDiscount']['discount'], 2);
                                } else {
                                    $discount['ModDiscount']['price'] = - round((($discount['ModDiscount']['discount']*$from_qnt)*$currencies_vals[$discount['ModDiscount']['discount_type']])/$currency['value'], 2);
                                }
                                
                                $basket['extra']['coupon_' . $key] = array(
                                    'discount_type' => 'coupon', 
                                    'item_id' => $discount['ModDiscount']['id'], 
                                    'ext' => $from_qnt,
                                    'data' => $discount, 
                                    'title' => $discount['ModDiscount']['title'] . ($discount['ModDiscount']['discount_type'] == '%' ? " ({$discount['ModDiscount']['discount']} {$discount['ModDiscount']['discount_type']})" : null), 
                                    'price' => $discount['ModDiscount']['price'], 
                                    'currency' => $currency['currency'], 
                                    'html_price' => str_replace($value_dec_trim, '', number_format($discount['ModDiscount']['price'], 2, '.', ' ')), 
                                    'html_currency' => $currency['title'],
                                    'ext_data' => array(
                                        'title_trnsl' => $discount['Translates']['ModDiscount']['title']
                                    )
                                );
                                $basket['price'] = $basket['price'] + $discount['ModDiscount']['price'];
                                
                                //$cupon_error = null;
                            }

                        }
                        
                    }
                }
                
                if(!empty($cupon_error) && !empty($_POST['data']['coupon'])) $basket['errors'][] = $cupon_error;
            }

            //$shippings = $ObjItemList->find('all', array('tid' => 'shipping'));
            //foreach($shippings as $_shipping) if($_shipping['ObjItemList']['data']['auto_select'] == '1') $basket['shipping'] = $_shipping['ObjItemList']['id'];
            /*
            if(!empty($basket['shipping'])){
                $shipping = $ObjItemList->find('first', array('tid' => 'shipping', 'conditions' => array('ObjItemList.id' => $basket['shipping'])));
                if(!empty($shipping)){
                    $shipping['ObjItemList']['price'] = round(($shipping['ObjItemList']['price']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2);
                    $shipping['ObjItemList']['free_price'] = round(($shipping['ObjItemList']['data']['free_price']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2);
                    
                    $basket['extra']['shipping'] = array('item_id' => $shipping['ObjItemList']['id'], 'title' => ___('Shipping') . ' - ' . $shipping['ObjItemList']['title'], 'stitle' => $shipping['ObjItemList']['title'], 'price' => $shipping['ObjItemList']['price'], 'currency' => $currency['currency'], 'html_price' => str_replace($value_dec_trim, '', number_format($shipping['ObjItemList']['price'], 2, '.', ' ')), 'free_price' => $shipping['ObjItemList']['free_price'], 'html_free_price' => str_replace($value_dec_trim, '', number_format($shipping['ObjItemList']['free_price'], 2, '.', ' ')), 'html_currency' => $currency['title']);
                    
                    if($shipping['ObjItemList']['data']['free_price'] > 0){
                        $shipping['ObjItemList']['free_price'] = round(($shipping['ObjItemList']['data']['free_price']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2);
                        if($basket['price'] >= $shipping['ObjItemList']['free_price']){
                            $basket['extra']['shipping_free'] = array('item_id' => $shipping['ObjItemList']['id'], 'title' => ___('Free shipping'), 'price' => - $shipping['ObjItemList']['price'], 'currency' => $currency['currency'], 'html_price' => - str_replace($value_dec_trim, '', number_format($shipping['ObjItemList']['price'], 2, '.', ' ')), 'html_currency' => $currency['title']);
                            $shipping['ObjItemList']['price'] = 0;
                        } else {
                            $basket['extra']['shipping']['remain_free_price'] = $shipping['ObjItemList']['data']['free_price'] - $basket['price'];
                        }
                    }
                    
                    $basket['price'] = $basket['price'] + $shipping['ObjItemList']['price'];
                }
            }
            */
            
            if(!empty($basket['options']['shipping'])){
                $shippings = $this->get_shipping_zone_price(array('basket' => $basket, 'zone_id' => $basket['options']['shipping']['zone_id']));
                $liftings = $this->get_shipping_lifting_price(array('basket' => $basket, 'shipping_id' => $basket['options']['shipping']['shipping'], 'floor' => $basket['options']['shipping']['floor']));

                if(!empty($basket['options']['shipping']['shipping']) && empty($shippings[$basket['options']['shipping']['shipping']])){
                    //exit('ERROR');
                } else {
                    if(!empty($basket['options']['shipping']['zone_id'])) $zone = $ObjItemList->ObjItemTree->findById($basket['options']['shipping']['zone_id']);
                    $distance = ($zone['ObjItemTree']['data']['distance'] > 0 ? $zone['ObjItemTree']['data']['distance'] : 0);
                    
                    $shipping = $shippings[$basket['options']['shipping']['shipping']];
                    $basket['extra']['shipping'] = array(
                        'item_id' => $shipping['id'], 
                        'title' => $shipping['title'], 
                        'price' => $shipping['price'], 
                        'currency' => $currency['currency'], 
                        'html_price' => str_replace($value_dec_trim, '', number_format($shipping['price'], 2, '.', ' ')), 
                        'html_currency' => $currency['title'],
                    );
                    if($shipping['obj']['ObjItemList']['data']['is_pickup'] != '1'){
                        $basket['extra']['shipping']['ext_data'] = array(
                            'distance' => $distance,
                            'zone_id' => $zone['ObjItemTree']['id'],
                            'zone_title' => $zone['ObjItemTree']['title']
                        );
                        $basket['extra']['shipping']['ext'] = $distance;
                    }
                    
                    $basket['extra']['shipping']['ext_data']['title_trnsl'] = $shipping['obj']['Translates']['ObjItemList']['title'];
                    
                    $basket['price'] = $basket['price'] + $shipping['price'];
                }

                if(!empty($shipping) && $shipping['obj']['ObjItemList']['data']['is_pickup'] != '1'){
                    if(!empty($basket['options']['shipping']['lifting']) && empty($liftings[$basket['options']['shipping']['lifting']])){
                        //exit('ERROR');
                    } else {
                        $lifting = $liftings[$basket['options']['shipping']['lifting']];
                        $basket['extra']['lifting'] = array(
                            'item_id' => $lifting['id'], 
                            'title' => $lifting['title'], 
                            'price' => $lifting['price'], 
                            'currency' => $currency['currency'], 
                            'html_price' => str_replace($value_dec_trim, '', number_format($lifting['price'], 2, '.', ' ')), 
                            'html_currency' => $currency['title'],
                            'ext_data' => array(
                                'floor' => $basket['options']['shipping']['floor'],
                            ),
                            'ext' => $basket['options']['shipping']['floor']
                        );
                        $basket['price'] = $basket['price'] + $lifting['price'];
                    }
                }
            }
            

            $basket['currency'] = $currency['currency'];
            $basket['html_currency'] = $currency['title'];
            $basket['price'] = round($basket['price'], 2);

            $event = new CakeEvent('ModOrder.get_data', null, array('basket' => $basket, 'currency' => $currency, 'currencies_vals' => $currencies_vals));
            $this->getEventManager()->dispatch($event);
            if(!empty($event->result['basket'])){
                $basket = $event->result['basket'];
            }

            $basket['html_price'] = str_replace($value_dec_trim, '', number_format($basket['price'], 2, '.', ' '));
        }
        Configure::write('Config.tid', 'shop');
        return $basket;
    }
    
    
    function get_shipping_zone_price($data = array()){
        $ObjItemList = ClassRegistry::init('ObjItemList');

        $currency = Configure::read('Obj.currency');
        $currencies_vals = Configure::read('Obj.currencies_vals');
        
        $basket = $data['basket'];
        
        $zone_id = $data['zone_id'];
        if(!empty($zone_id)) $zone = $ObjItemList->ObjItemTree->findById($zone_id);
        $distance = ($zone['ObjItemTree']['data']['distance'] > 0 ? $zone['ObjItemTree']['data']['distance'] : 0);
        
        $shippings = $ObjItemList->find('all', array('tid' => 'shipping', 'order' => array('ObjItemList.order_id' => 'asc')));
        
        $items = array();
        foreach($shippings as $shipping){
            
            if(!empty($shipping['Relation']['zone_id']) && !in_array($zone_id, $shipping['Relation']['zone_id'])) continue;
            
            if($shipping['ObjItemList']['data']['is_pickup'] == '1'){
                $items[] = array(
                    'id' => $shipping['ObjItemList']['id'], 
                    'title' => $shipping['ObjItemList']['title'],
                    'pickup' => '1',
                    'obj' => $shipping
                );
                continue;
            }

            if(empty($shipping['ObjItemList']['data']['zone_price'])){
                
                $price = round(($shipping['ObjItemList']['price']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2);
                if(!empty($shipping['ObjItemList']['data']['free_price'])){
                    if($basket['items_price'] >= round(($shipping['ObjItemList']['data']['free_price']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2)){
                        $price = 0;
                    }
                }
                $items[] = array(
                    'id' => $shipping['ObjItemList']['id'], 
                    'title' => $shipping['ObjItemList']['title'],
                    'obj' => $shipping,
                    'price' => ($price > 0 ? $price : 0),
                    'html_price' => ($price > 0 ? $price . ' ' . $currency['title'] : ___('free')),
                );
                continue;
                
            } elseif(!empty($zone_id)){
                $item = array(
                    'id' => $shipping['ObjItemList']['id'], 
                    'title' => $shipping['ObjItemList']['title'],
                    'obj' => $shipping
                );
                foreach($shipping['ObjItemList']['data']['zone_price'] as $zone_price){
                    if((!empty($zone_price['min_weight']) || !empty($zone_price['max_weight'])) && (Configure::read('CMS.settings.shipping.shipping_weight_calc') != '1')){
                        $weights = $basket['weights'];
                    } else {
                        $weights = array($basket['weight_total']);
                    }
                    
                    foreach($weights as $key => $weight){
                        if(($distance >= $zone_price['min_dist'] || empty($zone_price['min_dist'])) && ($distance <= $zone_price['max_dist'] || empty($zone_price['max_dist'])) && ($weight >= $zone_price['min_weight'] || empty($zone_price['min_weight'])) && ($weight <= $zone_price['max_weight'] || empty($zone_price['max_weight']))){
                            $item['exist'] = true;
                            if(!empty($zone_price['price_fix'])){
                                $item['prices'][$key] += round(($zone_price['price_fix']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2);
                            }
                            if(!empty($zone_price['price_km'])){
                                $price = round(($zone_price['price_km']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2);
                                $item['prices'][$key] += ($price * $distance);
                            }
                            if(!empty($zone_price['price_kg'])){
                                $price = round(($zone_price['price_kg']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2);
                                $item['prices'][$key] += ($price * $weight);
                            }
                        }
                    }
                }
                if($item['exist']){
                    rsort($item['prices']);
                    foreach($item['prices'] as $key => $price){
                        if(Configure::read('CMS.settings.shipping.shipping_disc') == '1'){
                            if($key == 1) $price = $price / 100 * 50;
                            if($key > 1) $price = $price / 100 * 25;
                        }
                        $item['price'] = $item['price'] + $price;
                    }
                    $item['price'] = ($item['price'] > 0 ? $item['price'] : 0);
                    $item['html_price'] = ($item['price'] > 0 ? $item['price'] . ' ' . $currency['title'] : ___('free'));
                    $items[] = $item;
                }

            }
        }
        
        $return = array();
        if(!empty($items)) foreach($items as $item) $return[$item['id']] = $item;
        
        return $return;
    }

    function get_shipping_lifting_price($data = array()){
        $ObjItemList = ClassRegistry::init('ObjItemList');

        $currency = Configure::read('Obj.currency');
        $currencies_vals = Configure::read('Obj.currencies_vals');
        
        $defaults = Configure::read('CMS.settings.shipping.data');
        
        $basket = $data['basket'];
        $shipping_id = $data['shipping_id'];
        $floor = $data['floor'];
        
        $items = array();
        $items[] = array('title' => 'Delivery products to the house', 'id' => 'entrance', 'price' => '0', 'html_price' => ___('free'));
        
        $shipping = $ObjItemList->findById($shipping_id);
        if(!empty($shipping)){
            
            if($floor > 0){
                
                if(empty($shipping['ObjItemList']['data']['stairs_price'])) $shipping['ObjItemList']['data']['stairs_price'] = $defaults['stairs_price'];
                if(empty($shipping['ObjItemList']['data']['elevator_price'])) $shipping['ObjItemList']['data']['elevator_price'] = $defaults['elevator_price'];
                
                foreach(array('stairs' => 'Lifting products without elevator', 'elevator' => 'Lifting products with elevator') as $heft_tp => $heft_title){
                    $item[$heft_tp] = array('title' => $heft_title);

                    if(Configure::read('CMS.settings.shipping.elevator_weight_calc') != '1'){
                        $weights = $basket['weights'];
                    } else {
                        $weights = array($basket['weight_total']);
                    }
                    
                    foreach($weights as $key => $weight){
                        //if($key == 3) $weight = 116;
                        $exist = false;
                        foreach($shipping['ObjItemList']['data']["{$heft_tp}_price"] as $heft_price){
                            if(($floor >= $heft_price['min_floor'] || empty($heft_price['min_floor'])) && ($floor <= $heft_price['max_floor'] || empty($heft_price['max_floor'])) && ($weight >= $heft_price['min_weight'] || empty($heft_price['min_weight'])) && ($weight <= $heft_price['max_weight'] || empty($heft_price['max_weight']))){
                                $item[$heft_tp]['exist'] = true;
                                $exist = true;
                                if(!empty($heft_price['price_fix'])){
                                    $item[$heft_tp]['prices'][$key] += round(($heft_price['price_fix']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2);
                                }
                                if(!empty($heft_price['price_floor'])){
                                    $price = round(($heft_price['price_floor']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2);
                                    $item[$heft_tp]['prices'][$key] += ($price * $floor);
                                }
                                if(!empty($heft_price['price_kg'])){
                                    $price = round(($heft_price['price_kg']*$currencies_vals[$shipping['ObjItemList']['currency']])/$currency['value'], 2);
                                    $item[$heft_tp]['prices'][$key] += ($price * $weight);
                                }
                             }
                        }
                        if(!$exist){
                            $item[$heft_tp]['exist'] = false;
                            break;
                        }
                    }
        
                    if($item[$heft_tp]['exist']){
                        rsort($item[$heft_tp]['prices']);
                        foreach($item[$heft_tp]['prices'] as $key => $price){
                            if(Configure::read('CMS.settings.shipping.elevator_disc') == '1'){
                                if($key == 1) $price = $price / 100 * 50;
                                if($key > 1) $price = $price / 100 * 25;
                            }
                            $item[$heft_tp]['price'] = $item[$heft_tp]['price'] + $price;
                        }
                    }
                }
                
                if($item['stairs']['exist'] === true){
                    $items[] = array('title' => $item['stairs']['title'], 'id' => 'stairs', 'price' => ($item['stairs']['price'] > 0 ? $item['stairs']['price'] : 0), 'html_price' => ($item['stairs']['price'] > 0 ? $item['stairs']['price'] . ' ' . $currency['title'] : ___('free')));
                    if(Configure::read('CMS.settings.shipping.elevator_disc') == '1') if($item['stairs']['price'] > 0) $items[] = array('title' => $item['stairs']['title'] . ' (help courier)', 'id' => 'stairs_help', 'price' => ($item['stairs']['price'] > 0 ? $item['stairs']['price']/2 : 0), 'html_price' => ($item['stairs']['price'] > 0 ? ($item['stairs']['price']/2) . ' ' . $currency['title'] : ___('free')));
                }
                if($item['elevator']['exist'] === true){
                    $items[] = array('title' => $item['elevator']['title'], 'id' => 'elevator', 'price' => ($item['elevator']['price'] > 0 ? $item['elevator']['price'] : 0), 'html_price' => ($item['elevator']['price'] > 0 ? $item['elevator']['price'] . ' ' . $currency['title'] : ___('free')));
                    if(Configure::read('CMS.settings.shipping.elevator_disc') == '1') if($item['elevator']['price'] > 0) $items[] = array('title' => $item['elevator']['title'] . ' (help courier)', 'id' => 'elevator_help', 'price' => ($item['elevator']['price'] > 0 ? $item['elevator']['price']/2 : 0), 'html_price' => ($item['elevator']['price'] > 0 ? ($item['elevator']['price']/2) . ' ' . $currency['title'] : ___('free')));
                }
                
            } else {
                //$items[] = array('title' => 'Lifting products without elevator', 'id' => 'stairs', 'html_price' => ___('free'));
            }
            
        }
        
        $return = array();
        if(!empty($items)) foreach($items as $item) $return[$item['id']] = $item;
        
        return $return;
    }
    
    function item_paid($item_id = null, $user_id = null, $price = false){
        if($price && $user_id > 0) $result = ClassRegistry::init('ObjItemList')->find('first', array('tid' => false, 'conditions' => array('ObjItemList.id' => $item_id, 'ObjItemList.price' => '0')));
        if(!empty($result)){
            return true;
        } else {
            $result = $this->ModOrderItem->find('first', array('conditions' => array('ModOrderItem.item_id' => $item_id, 'ModOrder.userid' => $user_id, 'OR' => array('ModOrder.paystatus' => '1', 'ModOrderItem.price' => '0'))));
            if(!empty($result)){
                return true;
            } else {
                return false;
            }
        }
    }
}
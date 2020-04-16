<?php
$this->ObjItemList->Behaviors->load('Bonuses.Bonuses');

if($this->Session->check('Auth.User.id')){
    $user_bonuses = ClassRegistry::init('ExtraData')->query("SELECT (SUM(extra_6) - ifnull((SELECT SUM(extra_6) FROM wb_extra_data WHERE type = 'bonuses' AND extra_1 = ".sqls($this->Session->read('Auth.User.id'))." AND extra_3 = '2' AND extra_4 = '1'), 0)) as bonuses FROM wb_extra_data WHERE type = 'bonuses' AND extra_1 = ".sqls($this->Session->read('Auth.User.id'))." AND extra_3 = '1' AND extra_4 = '1'");
    $this->set('user_bonuses', $user_bonuses[0][0]['bonuses']);
}

CakeEventManager::instance()->attach(function($event){
    $basket = $event->data['basket'];
    $currency = $event->data['currency'];
    $currencies_vals = $event->data['currencies_vals'];
    
    if(!empty($basket['items'])){
        $bonuses = 0;
        foreach($basket['items'] as $key => $item){
            if(!empty($item['ext_data']['extra']['rel_discount'])) continue;
            if(!empty($item['data']['RelationValue']['bonuses_prc'])){
                $bonus = round(((($item['price'] * $currency['value'])/$currencies_vals[key(Configure::read('CMS.currency'))]) / 100) * $item['data']['RelationValue']['bonuses_prc'], 0);
                $basket['items'][$key]['infos'][___('Bonuses')] = $bonus;
                $bonuses += round($bonus * $item['qnt'], 2);
            } else if(!empty($item['data']['RelationValue']['bonuses'])){
                $bonus = $item['data']['RelationValue']['bonuses'];
                $basket['items'][$key]['infos'][___('Bonuses')] = $bonus;
                $bonuses += round($bonus * $item['qnt'], 2);
            }
        }
        if($this->Session->check('Auth.User.id')){
            $user_bonuses = ClassRegistry::init('ExtraData')->query("SELECT (SUM(extra_6) - ifnull((SELECT SUM(extra_6) FROM wb_extra_data WHERE type = 'bonuses' AND extra_1 = ".sqls($this->Session->read('Auth.User.id'))." AND extra_3 = '2' AND extra_4 = '1'), 0)) as bonuses FROM wb_extra_data WHERE type = 'bonuses' AND extra_1 = ".sqls($this->Session->read('Auth.User.id'))." AND extra_3 = '1' AND extra_4 = '1'");
            if(!empty($user_bonuses[0][0]['bonuses'])){
                
                $user_bonus = - round(($user_bonuses[0][0]['bonuses'] * $currencies_vals[key(Configure::read('CMS.currency'))])/$currency['value'], 2);
                if(abs($user_bonus) > $basket['price']) $user_bonus = - $basket['price'];
                
                $basket['extra']['discount_bonuses'] = array(
                    'discount_type' => 'bonuses',
                    'ext' => $user_bonuses[0][0]['bonuses'],
                    'title' => 'Discount Bonuses', 
                    'price' => $user_bonus, 
                    'currency' => $currency['currency'], 
                    'html_price' => $user_bonus, 
                    'html_currency' => $currency['title']
                );
                
                $basket['price'] = $basket['price'] + $user_bonus;
            }

        }
        if($bonuses > 0){
            $bonuses_price = round(($bonuses * $currencies_vals[key(Configure::read('CMS.currency'))])/$currency['value'], 2);
            $basket['extra']['bonuses'] = array(
                'title' => 'Bonuses', 
                'ext' => $bonuses,
                'price' => $bonuses_price, 
                'currency' => $currency['currency'], 
                'html_price' => $bonuses_price, 
                'html_currency' => $currency['title']
            );
        }
    }
    return array('basket' => $basket);
}, 'ModOrder.get_data');

CakeEventManager::instance()->attach(function($event){
    $order_data = ClassRegistry::init('Shop.ModOrder')->findById($event->data['order_id']);
    
    if(!empty($order_data['ModOrder']['userid'])){
        $bonuses = reset($order_data['ModOrderItem']['bonuses']);
        if(!empty($bonuses['ext'])){
            ClassRegistry::init('ExtraData')->create();
            ClassRegistry::init('ExtraData')->save(array(
                'type' => 'bonuses',
                'extra_1' => $order_data['ModOrder']['userid'],
                'extra_2' => $event->data['order_id'],
                'extra_3' => '1',
                'extra_4' => '2',
                'extra_6' => $bonuses['ext'],
            ));
        }
        $discount_bonuses = reset($order_data['ModOrderItem']['discount_bonuses']);
        if(!empty($discount_bonuses['ext'])){
            ClassRegistry::init('ExtraData')->create();
            ClassRegistry::init('ExtraData')->save(array(
                'type' => 'bonuses',
                'extra_1' => $order_data['ModOrder']['userid'],
                'extra_2' => $event->data['order_id'],
                'extra_3' => '2',
                'extra_4' => '1',
                'extra_6' => $discount_bonuses['ext'],
            ));
        }
    }
    
}, 'Order.success_admin');
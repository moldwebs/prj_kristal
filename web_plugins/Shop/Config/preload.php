<?php
    
    $basket_data = $this->Cookie->read('Basket');
    if(empty($basket_data)){
        $basket_data['qnt'] = '0';
        $basket_data['html_price'] = '0';
        $basket_data['html_currency'] = Configure::read('Obj.currency')['currency'];
    } else {
        foreach($basket_data['items'] as $item){
            $basket_data['qnt'] += $item['qnt'];
        }
    }
    $this->set('basket_data', $basket_data);

    CakeEventManager::instance()->attach(function($event){
        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail(SYS_MAIL, ___('New order'), array('template' => 'order_success_admin', 'data' => $event->data));
    }, 'Order.success_admin');

    CakeEventManager::instance()->attach(function($event){
        $ModOrder = Classregistry::init('Shop.ModOrder');
        $order = $ModOrder->find('first', array('conditions' => array('ModOrder.id' => $event->data['order_id'])));

        $ObjItemList = Classregistry::init('ObjItemList');
        $shippings = $ObjItemList->find('list', array('tid' => 'shipping'));
        $payments = $ObjItemList->find('list', array('tid' => 'payment'));
        
        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail($event->data['email'], ___('New order'), array('template' => 'order_success', 'data' => am($event->data, array('order' => $order, 'shippings' => $shippings, 'payments' => $payments))));
    }, 'Order.success');
    
    CakeEventManager::instance()->attach(function($event){
        $ModOrder = Classregistry::init('Shop.ModOrder');
        $order = $ModOrder->find('first', array('conditions' => array('ModOrder.id' => $event->data['order_id'])));

        $ObjItemList = Classregistry::init('ObjItemList');
        $shippings = $ObjItemList->find('list', array('tid' => 'shipping'));
        $payments = $ObjItemList->find('list', array('tid' => 'payment'));
        
        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail($event->data['email'], ___('Success order payment'), array('template' => 'order_success_payment', 'data' => am($event->data, array('order' => $order, 'shippings' => $shippings, 'payments' => $payments))));
    }, 'Order.pay_success');
    

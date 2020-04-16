<?php
    /*
    CakeEventManager::instance()->attach(function($event){
        $ModOrder = Classregistry::init('Shop.ModOrder');
        $order = $ModOrder->find('first', array('conditions' => array('ModOrder.id' => $event->data['order_id'])));

        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail(SYS_MAIL, ___('Order status'), array('template' => 'order_status_admin', 'data' => am($event->data, array('order' => $order))));
    }, 'Order.onstatus_admin');
    */
    CakeEventManager::instance()->attach(function($event){
        $ModOrder = Classregistry::init('Shop.ModOrder');
        $order = $ModOrder->find('first', array('conditions' => array('ModOrder.id' => $event->data['order_id'])));


        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        Configure::write('Config.tpl_language', $order['ModOrder']['data']['data_checkout']['lang']);
        $Basic_component->mail($order['ModOrder']['data']['data_checkout']['email'], ___('Order Status'), array('template' => 'order_status', 'data' => am($event->data, array('order' => $order))));
        Configure::write('Config.tpl_language', '');
        }, 'Order.onstatus');
    /*
    CakeEventManager::instance()->attach(function($event){
        $ModOrder = Classregistry::init('Shop.ModOrder');
        $order = $ModOrder->find('first', array('conditions' => array('ModOrder.id' => $event->data['order_id'])));

        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail(SYS_MAIL, ___('Order ship status'), array('template' => 'order_shipstatus_admin', 'data' => am($event->data, array('order' => $order))));
    }, 'Order.shipstatus_admin');
    */
    CakeEventManager::instance()->attach(function($event){
        $ModOrder = Classregistry::init('Shop.ModOrder');
        $order = $ModOrder->find('first', array('conditions' => array('ModOrder.id' => $event->data['order_id'])));


        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        Configure::write('Config.tpl_language', $order['ModOrder']['data']['data_checkout']['lang']);
        $Basic_component->mail($order['ModOrder']['data']['data_checkout']['email'], ___('Order ship Status'), array('template' => 'order_shipstatus', 'data' => am($event->data, array('order' => $order))));
        Configure::write('Config.tpl_language', '');
        }, 'Order.shipstatus');
    /*
    CakeEventManager::instance()->attach(function($event){
        $ModOrder = Classregistry::init('Shop.ModOrder');
        $order = $ModOrder->find('first', array('conditions' => array('ModOrder.id' => $event->data['order_id'])));

        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail(SYS_MAIL, ___('Order pay status'), array('template' => 'order_paystatus_admin', 'data' => am($event->data, array('order' => $order))));
    }, 'Order.paystatus_admin');
    */
    CakeEventManager::instance()->attach(function($event){
        $ModOrder = Classregistry::init('Shop.ModOrder');
        $order = $ModOrder->find('first', array('conditions' => array('ModOrder.id' => $event->data['order_id'])));

        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        Configure::write('Config.tpl_language', $order['ModOrder']['data']['data_checkout']['lang']);
        $Basic_component->mail($order['ModOrder']['data']['data_checkout']['email'], ___('Order Pay Status'), array('template' => 'order_paystatus', 'data' => am($event->data, array('order' => $order))));
        Configure::write('Config.tpl_language', '');
    }, 'Order.paystatus');
    

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
        Configure::write('Config.tpl_language', $order['ModOrder']['data']['data_checkout']['lang']);
        $Basic_component->mail($event->data['email'], ___('New order'), array('template' => 'order_success', 'data' => am($event->data, array('order' => $order, 'shippings' => $shippings, 'payments' => $payments))));
        Configure::write('Config.tpl_language', '');
    }, 'Order.success');
        
<?php
CakeEventManager::instance()->attach(function($event){
    if($event->data['status'] == '5'){
        ClassRegistry::init('ExtraData')->updateAll(
            array('ExtraData.extra_4' => '2'),
            array('ExtraData.type' => 'bonuses', 'ExtraData.extra_3' => '2', 'ExtraData.extra_2' => $event->data['order_id'])
        );
    } else {
        ClassRegistry::init('ExtraData')->updateAll(
            array('ExtraData.extra_4' => '1'),
            array('ExtraData.type' => 'bonuses', 'ExtraData.extra_3' => '2', 'ExtraData.extra_2' => $event->data['order_id'])
        );
    }
    if(in_array($event->data['status'], array('2', '3', '4'))){
        ClassRegistry::init('ExtraData')->updateAll(
            array('ExtraData.extra_4' => '1'),
            array('ExtraData.type' => 'bonuses', 'ExtraData.extra_3' => '1', 'ExtraData.extra_2' => $event->data['order_id'])
        );
    } else {
        ClassRegistry::init('ExtraData')->updateAll(
            array('ExtraData.extra_4' => '2'),
            array('ExtraData.type' => 'bonuses', 'ExtraData.extra_3' => '1', 'ExtraData.extra_2' => $event->data['order_id'])
        );
    }
}, 'Order.order_onstatus');
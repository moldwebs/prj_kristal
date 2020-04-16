<?php
    CakeEventManager::instance()->attach(function($event){
        $ObjItemList = ClassRegistry::init('ObjItemList');
        $rel_id = (!empty($event->data['rel_id']) ? $event->data['rel_id'] : $ObjItemList->fread('rel_id', $event->data['id']));
        $combination = $ObjItemList->find('first', array('tid' => false, 'conditions' => array('ObjItemList.rel_id' => $rel_id, 'ObjItemList.status' => '1', 'ObjItemList.price > 0'), 'order' => array('ObjItemList.price_conv' => 'asc')));
        if(empty($combination['Price']['value'])){
            $ObjItemList->updateAll(
                array('ObjItemList.price' => null, 'ObjItemList.currency' => null),
                array("ObjItemList.id" => $rel_id)
            );
        } else {
            $ObjItemList->updateAll(
                array('ObjItemList.price' => sqls($combination['Price']['value'], true), 'ObjItemList.currency' => sqls($combination['Price']['currency'], true)),
                array("ObjItemList.id" => $rel_id)
            );
        }
    }, 'Catalog.rel_id');

    CakeEventManager::instance()->attach(function($event){
        $ObjItemActions = ClassRegistry::init('ObjItemActions');
        $ObjItemActions->create();
        $ObjItemActions->save(array(
            'user_id' => Configure::read('is_admin_data.id'),
            'tid' => Configure::read('Config.tid'),
            'model' => 'Item',
            'foreign_key' => $event->data['id'],
            'action' => 'edit',
            'data' => base64_encode(json_encode($_POST)),
        ));
    }, 'Item.edit');

    CakeEventManager::instance()->attach(function($event){
        $ObjItemActions = ClassRegistry::init('ObjItemActions');
        $ObjItemActions->create();
        $ObjItemActions->save(array(
            'user_id' => Configure::read('is_admin_data.id'),
            'tid' => Configure::read('Config.tid'),
            'model' => 'Item',
            'foreign_key' => $event->data['id'],
            'action' => 'create',
            'data' => base64_encode(json_encode($_POST)),
        ));
    }, 'Item.create');

    CakeEventManager::instance()->attach(function($event){
        $ObjItemActions = ClassRegistry::init('ObjItemActions');
        $ObjItemActions->create();
        $ObjItemActions->save(array(
            'user_id' => Configure::read('is_admin_data.id'),
            'tid' => Configure::read('Config.tid'),
            'model' => 'Item',
            'foreign_key' => $event->data['item_id'],
            'action' => 'delete',
        ));
    }, 'Catalog.delete');
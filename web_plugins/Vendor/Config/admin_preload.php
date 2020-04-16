<?php
    CakeEventManager::instance()->attach(function($event){
        Classregistry::init('ExtraData')->deleteAll(array('type' => 'vendor', 'extra_2' => $event->data['item_id']));
    }, 'Catalog.delete');

    $vendors = $this->ObjItemList->find('allindex', array('tid' => 'vendor', 'order' => array('ObjItemList.order_id' => 'asc', 'ObjItemList.title' => 'asc')));
    $this->set('vendors', $vendors);
    
    $vendors_list = array();
    foreach($vendors as $vendor){
        $vendors_list[$vendor['ObjItemList']['id']] = $vendor['ObjItemList']['title'];
    }
    $this->set('vendors_list', $vendors_list);
    
    Configure::write('Obj.vendors', $vendors);
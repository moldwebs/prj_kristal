<?php
    Configure::write('Obj.types', array('ObjItemList' => $this->ObjOptType->find('list', array('fields' => array('ObjOptType.id', 'ObjOptType.order_id')))));
    $types = $this->ObjOptType->find('all');
    
    $list_types = array();
    foreach($types as $type){
        $list_types[$type['ObjOptType']['id']] = $type['ObjOptType']['title'];
    }
    $this->set('types', $list_types);
    
    foreach($types as $type){
        if(!empty($type['ObjOptType']['auto_set'])){
            CakeEventManager::instance()->attach(function($event){
                $type = $event->passData['type'];
                $save = array('model' => 'ObjItemList', 'type' => 'extra_1', 'foreign_key' => $event->data['id'], 'rel_id' => $type['ObjOptType']['id']);
                if(!empty($type['ObjOptType']['auto_set_expire'])) $save['expire'] = date("Y-m-d", strtotime("+{$type['ObjOptType']['auto_set_expire']} days"));
                Classregistry::init('ObjOptRelation')->create();
                Classregistry::init('ObjOptRelation')->save($save);
            }, 'Item.create', array('passData' => array('type' => $type)));
        }
    }
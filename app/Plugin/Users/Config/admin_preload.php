<?php
    CakeEventManager::instance()->attach(function($event){
        Classregistry::init('ExtraData')->deleteAll(array('type' => 'user_collection', 'extra_1' => $event->data['user_id']));
    }, 'User.delete');
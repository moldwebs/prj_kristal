<?php
    CakeEventManager::instance()->attach(function($event){
        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail($event->data['usermail'], ___('Success registration'), array('template' => 'user_register', 'data' => $event->data));
    }, 'User.auth');
    
    CakeEventManager::instance()->attach(function($event){
        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail($event->data['usermail'], ___('Success registration'), array('template' => 'user_register', 'data' => $event->data));
    }, 'User.register');

    CakeEventManager::instance()->attach(function($event){
        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail($event->data['usermail'], ___('Confirm registration'), array('template' => 'user_confirm', 'data' => $event->data));
    }, 'User.confirm');

    CakeEventManager::instance()->attach(function($event){
        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail($event->data['usermail'], ___('Forget password'), array('template' => 'user_forget', 'data' => $event->data));
    }, 'User.forget');
    
	CakeEventManager::instance()->attach(function($event){
        App::import('Component', 'Basic');
        $Basic_component = new BasicComponent(new ComponentCollection());
        $Basic_component->mail($event->data['email'], ___('Unsubscribe'), array('template' => 'user_unsubscribe', 'data' => $event->data));
    }, 'User.unsubscribe_request');
    
    
if(!empty($_GET['make_db']) && !empty($_GET['debug'])){
    //?debug=1&make_db=1&clear=1
    //$this->ExtraData->query("ALTER TABLE `wb_mod_order` ADD COLUMN `long_data` longtext NULL;");
    //$this->ExtraData->query("UPDATE wb_obj_item_list AS t1, wb_obj_opt_price AS t2 SET t1.price = t2.price, t1.currency = t2.currency WHERE t2.foreign_key = t1.id;");
    exit('ok');
}
<?php
    CakeEventManager::instance()->attach(function($event){
        foreach(json_decode($event->data['auth']['User']['cookies'], true) as $key => $val){
            if(is_array($val) && $key == 'Toggle'){
                foreach($val as $_key => $_val) $this->Cookie->write("{$key}.{$_key}", $_val);
            } else if(is_array($val) && $key == 'Basket'){
                $act_cookie = $this->Cookie->read($key);
                if(empty($act_cookie['items'])) $this->Cookie->write("{$key}", $val);
            } else {
                $this->Cookie->write($key, $val);
                if($key == 'lang') $this->Session->write('lang', $val);
            }
        }
        $this->Cookie->write('md5', md5(json_encode($event->data['auth']['User']['cookies'])));
    }, 'User.login');

    if($this->Session->check('Auth.User.id')){
        $user_data = ClassRegistry::init('Users.User')->findById($this->Session->read('Auth.User.id'));
        if(empty($user_data)) $this->Session->delete('Auth');
        $this->set('user_data', $user_data);
        Configure::write('user_data', $user_data);
        
        $cookies = $this->Cookie->read();
        unset($cookies['md5']);
        if(md5(json_encode($cookies)) != $this->Cookie->read('md5')){
            $this->Cookie->write('md5', md5(json_encode($cookies)));
            ClassRegistry::init('Users.User')->updateAll(array('cookies' => sqls(json_encode($cookies), true)), array('User.id' => $user_data['User']['id']));
        }
        
        $this->set('user_collection', $this->ExtraData->find('list', array('fields' => array('ExtraData.id', 'ExtraData.extra_2', 'ExtraData.extra_5'), 'conditions' => array('ExtraData.extra_1' => $this->Session->read('Auth.User.id'), 'ExtraData.type' => 'user_collection'))));
    } else {
        $this->set('user_collection', $this->Session->read('User.collections'));
    }
?>
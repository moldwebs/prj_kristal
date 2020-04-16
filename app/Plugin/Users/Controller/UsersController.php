<?php
class UsersController extends UsersAppController {

    public $uses = array('Users.User', 'Users.UserRole');

    public $paginate = array(
        'User' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'User.created' => 'desc'
            )
        )
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'register', 'logout', 'admin_login', 'admin_logout');

		if (empty($this->request->data)) {
		    if(!empty($_GET['back'])){
		        $this->Session->write('Auth.redirect', base64_decode($_GET['back']));
		    } else {
    			if (!$this->Session->check('Auth.redirect') && env('HTTP_REFERER') && strpos($this->referer(null, true), '/users/') === false) {
    				$this->Session->write('Auth.redirect', $this->referer(null, true));
    			}
		    }
		}
        
        if($this->Session->check('Auth.User.id') && in_array($this->request->params['action'], array('login', 'register', 'admin_login', 'auth'))) $this->redirect(($this->Session->check('Auth.redirect') ? $this->Session->read('Auth.redirect') : '/'));
        
        $this->Auth->authenticate = array('Form' => array('scope' => array('User.status' => '1'), 'userModel' => 'Users.User', 'fields' => array('username' => 'usermail', 'password' => 'password')));
    }
    
    public function admin_table_actions(){
        $this->User->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Users') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('User', $this->Basic->filters('User')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->User, true);
        
        if($id){
            $this->set('page_title', ___('Users') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Users') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

	public function admin_delete($id = null){
	    if($this->User->delete($id)){
	       $this->getEventManager()->dispatch(new CakeEvent('User.delete', null, array('user_id' => $id)));
	    }
        
        $this->Basic->back();
	}

	public function admin_lock($id = null){
        if(!empty($id)){
            $this->getEventManager()->dispatch(new CakeEvent('User.lock', null, array('user_id' => $id)));
            $this->User->updateAll(array('status' => sqls('2', true)), array('User.id' => $id));
        } 
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->User->toggle($id, 'status');
        $this->Basic->back();
    }

    public function admin_role($id = null, $role = null){
        if(!empty($id) && !empty($role)) $this->User->updateAll(array('role' => sqls($role, true)), array('User.id' => $id));
        $this->Basic->back();
    }

    public function admin_login(){
        $this->layout = "admin_login";
        
        $this->Auth->authenticate['Form']['scope'] = am($this->Auth->authenticate['Form']['scope'], array('User.role <>' => 'user'));
        
        if($this->request->is('post')){
            if ($this->Auth->login()){
                $this->redirect(($this->Session->check('Auth.redirect') ? $this->Session->read('Auth.redirect') : '/admin/'));
            } else {
                $this->Session->setFlash(___('Invalid email or password, try again'), 'flash');
            }
        }
    }

    public function admin_logout(){
        $this->Auth->logout();
        $this->redirect('/');
    }
    
    public function admin_export(){
        $emails = $this->User->find('list', array('fields' => array('User.id', 'User.usermail'), 'order' => array('User.usermail' => 'asc'), 'conditions' => array('User.status' => '1')));
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="emails.csv"');
        echo implode(';', $emails);
        exit;
    }
    
    public function admin_get_json(){
        $_options = $this->User->find('list', array('fields' => array('User.id', 'User.usermail'), 'order' => array('User.usermail' => 'asc'), 'conditions' => array('OR' => array('User.usermail LIKE' => '%' . sqls($_GET['term']) . '%', 'User.username LIKE' => '%' . sqls($_GET['term']) . '%'))));
        $options = array();
        foreach($_options as $key => $val) $options[] = array('id' => $key, 'label' => $val);
        exit(json_encode($options));
    }

    public function ulogin(){
        if(Configure::read('CMS.denny_users_register') == '1') exit;
        
        //$s = file_get_contents('https://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $s = curl_exec($ch);
        curl_close($ch);         
        
        $user = json_decode($s, true);

        if(!empty($user['email'])){
            if(!($this->User->find('count', array('conditions' => array('usermail' => $user['email']))) > 0)){
                if(Configure::read('CMS.settings.users.account_data') == '1') $to_account = true;
                $this->Session->setFlash('1', false, array(), 'afterregister');
                $this->User->create();
                $this->User->save(array('usermail' => $user['email'], 'username' => "{$user['first_name']} {$user['last_name']}", 'password' => '', 'status' => '1', 'data' => $user));
                $this->getEventManager()->dispatch(new CakeEvent('User.auth', $this, array('usermail' => $user['email'], 'username' => "{$user['first_name']} {$user['last_name']}")));
            }
            $user = $this->User->find('first', array('conditions' => array('usermail' => $user['email'], 'User.role <>' => 'admin')));
            if($user['User']['id'] > 0){
                $this->Session->write('Auth.User', $user['User']);
                $this->getEventManager()->dispatch(new CakeEvent('User.login', $this, array('auth' => $user)));
                $this->User->updateAll(array("User.last_enter" => sqls(date("Y-m-d H:i:s"), true)), array("User.id" => $user['User']['id']));
            }
        }
        if(Configure::read('CMS.settings.users.url_after_login') != ''){
            $this->redirect(Configure::read('CMS.settings.users.url_after_login'));
        } else {
            $this->redirect(($this->Session->check('Auth.redirect') ? $this->Session->read('Auth.redirect') : '/'));
        }
    }

    public function auth($provider = null){
        if(Configure::read('CMS.denny_users_register') == '1') exit;
        
        /*LINK EX. window.open('<?php e(FULL_BASE_URL . "/users/auth/google")?>','auth','width=640,height=350,top='+(screen.height-350)/2+',left='+(screen.width-640)/2+',status=0,toolbar=0,menubar=0,location=0,resizable=0,scrollbars=0');*/
        // http://hybridauth.sourceforge.net/userguide.html

        $this->layout = null;

        $config = array("base_url" => FULL_BASE_URL . "/users/auth", "providers" => array(), "debug_mode" => false, "debug_file" => "social.log");
        foreach(Configure::read('CMS.social_login') as $key => $val){
            $config['providers'][$key] = array(
                'enabled' => (Configure::read('CMS.settings.users.' . $key . '_enabled') == '1' ? true : false)
            );
            if(!empty($val)) foreach($val as $_key => $_val){
                $config['providers'][$key]['keys'][$_val] = Configure::read('CMS.settings.users.' . $key . '_' . $_val);
            }
        }

        App::import('Vendor', 'Users.Hybrid/Auth');
        App::import('Vendor', 'Users.Hybrid/Endpoint');
        
        if(empty($provider)){
            Hybrid_Endpoint::process();
        } else {
            try{
                $hybridauth = new Hybrid_Auth($config);
                $adapter = $hybridauth->authenticate($provider);
                $is_user_logged_in = $adapter->isUserConnected();
                $user_profile = $adapter->getUserProfile();
                if(!empty($user_profile->email)){
                    if(!($this->User->find('count', array('conditions' => array('usermail' => $user_profile->email))) > 0)){
                        if(Configure::read('CMS.settings.users.account_data') == '1') $to_account = true;
                        $this->Session->setFlash('1', false, array(), 'afterregister');
                        $this->User->create();
                        $this->User->save(array('usermail' => $user_profile->email, 'username' => $user_profile->displayName, 'password' => '', 'status' => '1', 'data' => (array)$user_profile));
                        $this->getEventManager()->dispatch(new CakeEvent('User.auth', null, array('usermail' => $user_profile->email, 'username' => $user_profile->displayName)));
                    }
                    $user = $this->User->find('first', array('conditions' => array('usermail' => $user_profile->email, 'User.role <>' => 'admin')));
                    if($user['User']['id'] > 0){
                        $this->Session->write('Auth.User', $user['User']);
                        $this->getEventManager()->dispatch(new CakeEvent('User.login', null, array('auth' => $user)));
                        $this->User->updateAll(array("User.last_enter" => sqls(date("Y-m-d H:i:s"), true)), array("User.id" => $user['User']['id']));
                    }
                }
                $adapter->logout();
                echo '<script language="javascript">if(window.opener){try { window.opener.parent.$.colorbox.close(); } catch(err) {} '.($to_account ? 'window.opener.parent.location = "/users/data?mode=auth";' : 'window.opener.parent.location.reload();').'} window.self.close();</script>';
            }
        	catch(Exception $e){
        	   $adapter->logout();
        	}
        }
        exit;
    }

    
    public function request_register(){
        if(!empty($this->request->params['requested'])){

            $save['User'] = array(
                'username' => htmlspecialchars($this->params->data['username']), 
                'usermail' => htmlspecialchars($this->params->data['usermail']), 
                'password' => $this->params->data['password'],
                'rpassword' => $this->params->data['password'],
                'data' => ws_htmlspecialchars($this->params->data['data']),
                'extra_1' => htmlspecialchars($this->params->data['extra_1']), 
                'extra_2' => htmlspecialchars($this->params->data['extra_2']), 
                'extra_3' => htmlspecialchars($this->params->data['extra_3']), 
                'status' => '1'
            );
            
            $this->User->set($save);
            if(!$this->User->validates()){
                return array('errors' => $this->User->validationErrors);
            } else {
                $this->User->create();
                $this->User->save($save);
                $save['User']['id'] = $this->User->getLastInsertId();
                return $save;            
            }
        }
    }
    
    public function register(){
        if(Configure::read('CMS.denny_users_register') == '1') exit;
        
        $this->Basic->template(array('title' => ___('Register'), 'alias' => $this->here));
        
        if($this->request->is('post')){

            $save['User'] = array(
                'id' => $this->Session->read('Auth.User.id'), 
                'username' => htmlspecialchars($this->request->data['User']['username']), 
                'usermail' => htmlspecialchars($this->request->data['User']['usermail']), 
                'password' => $this->request->data['User']['password'],
                'rpassword' => $this->request->data['User']['rpassword'],
                'data' => ws_htmlspecialchars($this->request->data['User']['data']),
                'extra_1' => htmlspecialchars($this->request->data['User']['extra_1']), 
                'extra_2' => htmlspecialchars($this->request->data['User']['extra_2']), 
                'extra_3' => htmlspecialchars($this->request->data['User']['extra_3']), 
            );

            if(!$this->Session->check('Auth.User.id')) if($_SESSION['captcha'] != $this->data['captcha']) exit(___('Incorrect Security Code'));

            $this->User->set($save);
            if(!$this->User->validates()){
                $exit = null;
                foreach($this->User->validationErrors as $errors){
                    foreach($errors as $error){
                        $exit .= $error . "\r\n";
                    }
                }
                exit($exit);
            }

            if($this->data['ajx_validate'] != '1'){
                $this->User->create();
                if($this->User->save($save) && !empty($this->request->data['User']['password'])){
                    if(Configure::read('CMS.settings.users.confirm') != '1'){
                        $this->User->updateAll(array("User.status" => '1'), array("User.id" => $this->User->id));
                        $this->request->data['User'] = array_merge($this->request->data['User'], array('id' => $this->User->id));
                        $this->Auth->login($this->request->data['User']);
                        $this->getEventManager()->dispatch(new CakeEvent('User.register', null, array('usermail' => $this->request->data['User']['usermail'], 'username' => $this->request->data['User']['username'])));
                        
                        $this->Session->setFlash('1', false, array(), 'afterregister');
    
                        if(Configure::check('CMS.settings.users.url_after_register')){
                            $this->set('redirect', Configure::read('CMS.settings.users.url_after_register'));
                        } else {
                            $this->set('redirect', ($this->Session->check('Auth.redirect') ? $this->Session->read('Auth.redirect') : '/'));
                        }
                        $this->redirect(array('action' => 'success_register'));
                        // MAKE !!!
                    } else {
                        $code = md5(uniqid());
                        $this->User->updateAll(array("User.confirm" => sqls($code, true)), array("User.id" => $this->User->id));
                        $this->getEventManager()->dispatch(new CakeEvent('User.confirm', null, array('code' => $code, 'usermail' => $this->request->data['User']['usermail'], 'username' => $this->request->data['User']['username'])));
                        $this->Session->setFlash(___('Check you email for activation code'), 'flash');
                        $this->redirect(array('action' => 'confirm'));
                    }
                    unset($_SESSION['captcha']);
                }
            }
            exit('OK');
        }
    }
    
    public function success_register(){
        $this->Basic->template(array('title' => ___('Register'), 'alias' => $this->here));
        
        $this->set('item', $this->Session->read('Auth.User'));
        if(Configure::check('CMS.settings.users.url_after_register')){
            $this->set('redirect', Configure::read('CMS.settings.users.url_after_register'));
        } else {
            $this->set('redirect', ($this->Session->check('Auth.redirect') ? $this->Session->read('Auth.redirect') : '/'));
        }
        //$this->set('redirect', ($this->Session->check('Auth.redirect') ? $this->Session->read('Auth.redirect') : '/'));
    }

    public function confirm(){
        $this->Basic->template(array('title' => ___('Confirm'), 'alias' => $this->here));
        
        if($this->request->is('post') && !empty($this->request->data['User']['code'])){
            $user = $this->User->find('first', array('st_cond' => '1', 'conditions' => array('confirm' => $this->request->data['User']['code'])));
            if(!empty($user)){
                $this->User->updateAll(array("User.status" => '1', "User.confirm" => 'NULL'), array("User.id" => $user['User']['id']));
                $this->getEventManager()->dispatch(new CakeEvent('User.register', null, array('usermail' => $user['User']['usermail'], 'username' => $user['User']['username'])));
                $this->Session->setFlash(___('Account was activated successfull'), 'flash');
                $this->redirect(array('action' => 'login'));
            } else {
                $this->Session->setFlash(___('Invalid code, try again'), 'flash');
            }
        }
    }

    public function forget(){
        $this->Basic->template(array('title' => ___('Forget password'), 'alias' => $this->here));
        
        if($this->request->is('post')){
            if($this->User->find('count', array('conditions' => array('usermail' => $this->request->data['User']['usermail']))) > 0){
                $code = md5(uniqid());
                $this->User->updateAll(array("User.forget" => sqls($code, true)), array("User.usermail" => $this->request->data['User']['usermail']));
                $this->getEventManager()->dispatch(new CakeEvent('User.forget', null, array('code' => $code, 'usermail' => $this->request->data['User']['usermail'])));             
                $this->Session->setFlash(___('Check you email for code'), 'flash');
                $this->redirect(array('action' => 'forget_password'));
            } else {
                $this->Session->setFlash(___('Invalid email, try again'), 'flash');
            }
        }
    }

    public function forget_password(){
        $this->Basic->template(array('title' => ___('Forget password'), 'alias' => $this->here));
        
        if($this->request->is('post') && !empty($this->request->data['User']['code']) && !empty($this->request->data['User']['password'])){
            $user = $this->User->find('first', array('conditions' => array('forget' => $this->request->data['User']['code'])));
            if(!empty($user)){
                $this->User->updateAll(array("User.password" => sqls(AuthComponent::password($this->request->data['User']['password']), true), "User.forget" => 'NULL'), array("User.id" => $user['User']['id']));
                $this->Session->setFlash(___('Password was update successfull'), 'flash');
                $this->redirect(array('action' => 'login'));
            } else {
                $this->Session->setFlash(___('Invalid code, try again'), 'flash');
            }
        } else if(!empty($this->request->query['code'])){
            $this->request->data['User']['code'] = $this->request->query['code'];
        }
    }

    public function login(){
        $this->Basic->template(array('title' => ___('Login'), 'alias' => $this->here));
        
        //$this->Auth->authenticate['Form']['scope'] = am($this->Auth->authenticate['Form']['scope'], array('User.role <>' => 'admin'));
        
		if($this->Session->check('Auth.User.id')) $this->redirect('/');
        if($this->request->is('post')){
            if ($this->Auth->login()){
                $this->User->updateAll(array("User.last_enter" => sqls(date("Y-m-d H:i:s"), true)), array("User.id" => $this->Session->read('Auth.User.id')));
                
                $this->getEventManager()->dispatch(new CakeEvent('User.login', null, array('auth' => $this->Session->read('Auth'))));
                
                if(Configure::check('CMS.settings.users.url_after_login')){
                    $this->redirect(Configure::read('CMS.settings.users.url_after_login'));
                } else {
                    $this->redirect(($this->Session->check('Auth.redirect') ? $this->Session->read('Auth.redirect') : '/'));
                }
            } else {
                $this->Session->setFlash(___('Invalid email or password, try again'), 'flash');
            }
        }
    }

    public function logout(){
        $this->Auth->logout();
        $this->redirect('/');
    }

    public function collections($type = null, $item_id = null, $base_id = null){
        if($this->Session->read('Auth.User.id') > 0){
            
            $id = $this->ExtraData->field('id', array('ExtraData.extra_1' => $this->Session->read('Auth.User.id'), 'ExtraData.extra_2' => $item_id, 'ExtraData.extra_5' => $type, 'ExtraData.type' => 'user_collection'));
            if($id > 0){
                $this->ExtraData->delete($id);
            } else {
                $this->ExtraData->create();
                $this->ExtraData->save(array('extra_1' => $this->Session->read('Auth.User.id'), 'extra_2' => $item_id, 'extra_5' => $type, 'type' => 'user_collection'));
            }
            
            $count = $this->ExtraData->find('count', array('conditions' => array('ExtraData.extra_1' => $this->Session->read('Auth.User.id'), 'ExtraData.extra_5' => $type, 'ExtraData.type' => 'user_collection')));
        } else {
            
            $collections = $this->Session->read('User.collections');
            if(!empty($collections[$type]) && in_array($item_id, $collections[$type])){
                unset($collections[$type][array_search($item_id, $collections[$type])]);
            } else {
                $collections[$type][] = $item_id;
            }
            $this->Session->write('User.collections', $collections);
            
            $count = count($collections[$type]);
        }
        if($this->RequestHandler->isAjax()){
            exit("mxwin('/system/window?tpl=collection_{$type}&item_id={$item_id}&base_id={$base_id}&count={$count}')");
        } else {
            $this->redirect($this->referer());
        }
    }
    
    public function data(){
        $this->Basic->template(array('title' => ___('Profile'), 'alias' => $this->here));
        
        if(!$this->Session->check('Auth.User.id')) $this->redirect('/');
        
        if(!empty($this->request->data)){
            $save['User'] = array(
                'id' => $this->Session->read('Auth.User.id'), 
                'username' => htmlspecialchars($this->request->data['User']['username']), 
                'usermail' => htmlspecialchars($this->request->data['User']['usermail']), 
                //'opassword' => $this->request->data['User']['opassword'],
                //'password' => $this->request->data['User']['password'],
                //'rpassword' => $this->request->data['User']['rpassword'],
                'data' => ws_htmlspecialchars($this->request->data['User']['data']),
                'extra_1' => htmlspecialchars($this->request->data['User']['extra_1']), 
                'extra_2' => htmlspecialchars($this->request->data['User']['extra_2']), 
                'extra_3' => htmlspecialchars($this->request->data['User']['extra_3']), 
            );
            if($this->User->save($save)){
                $this->Session->setFlash(___('Data was save successfull.'), 'flash');
                if($this->params->query['mode'] == 'auth'){
                    $this->redirect(($this->Session->check('Auth.redirect') ? $this->Session->read('Auth.redirect') : '/'));
                } else {
                    $this->redirect($this->here);
                }
            }
        } else {
            $this->request->data = $this->User->findById($this->Session->read('Auth.User.id'));
            $this->request->data['User']['password'] = null;
        }
    }

    public function password(){
        $this->Basic->template(array('title' => ___('Password'), 'alias' => $this->here));
        
        if(!$this->Session->check('Auth.User.id')) $this->redirect('/');
        
        if(!empty($this->request->data)){
            $save['User'] = array(
                'id' => $this->Session->read('Auth.User.id'), 
                'opassword' => $this->request->data['User']['opassword'],
                'password' => $this->request->data['User']['password'],
                'rpassword' => $this->request->data['User']['rpassword'],
            );
            if($this->User->save($save)){
                $this->Session->setFlash(___('Data was save successfull.'), 'flash');
                if($this->params->query['mode'] == 'auth'){
                    $this->redirect(($this->Session->check('Auth.redirect') ? $this->Session->read('Auth.redirect') : '/'));
                } else {
                    $this->redirect($this->here);
                }
            }
        } else {
            $this->request->data = $this->User->findById($this->Session->read('Auth.User.id'));
            $this->request->data['User']['password'] = null;
        }
    }

    public function admin_table_actions_newsletter(){
        $this->ObjItemList->table_actions($this->request->data);
        $this->Basic->back();
    }
    
}

<?php
class SystemComponent extends Component {

    protected $_controller = null;
    
    public function initialize(Controller $controller) {
        // The initialize method is called before the controller�s beforeFilter method.
        
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('SystemComponent1:' . date("Y-m-d H:i:s"))));
        
        $this->_controller = $controller;

        if(!empty($this->_controller->request->params['requested'])){
            Configure::write('TMP.sql_no_cache', '0');
            $this->_controller->ObjItemList->st_cond = '0';
        }        

        if(isset($this->_controller->request->params['admin'])){
        	Configure::write('Session', array(
        		'defaults' => 'database',
                'ini' => array(
                    'session.cookie_path' => '/admin',
                ),
                'handler' => array(
                    'model' => 'wb_cms_sessions'
                ),
                'cookie' => 'AdminSession',
        	));
            $this->_controller->Cookie->name = 'AdminCookie';
            $this->_controller->Cookie->path = '/admin';

            Configure::write('is_admin', '1');
            Configure::write('is_admin_data', $this->_controller->Session->read('Auth.User'));

            if(isset($this->_controller->request->query['uid']) && $this->_controller->Session->read('Auth.User.uid') == '0' && $this->_controller->Session->read('Auth.User.role') == 'admin'){
                $this->_controller->Session->write('UID', $this->_controller->request->query['uid']);
                $this->_controller->redirect($this->_controller->request->referer(true));
            }
        }
        
        if(empty($this->_controller->request->params['requested'])){
            $this->__setTranslates();
            $this->__setLanguage();
            $this->__setCurrencies();
            $this->__setSettings();
            $this->__setPlugins();
            //$this->__setCookies();
        }
        
        $this->__Maintenance();
        $this->__Permissions();
        
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('SystemComponent2:' . date("Y-m-d H:i:s"))));
    }
    
    public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true){
        if(!isset($this->_controller->request->params['admin']) && empty($this->_controller->request->params['requested']) && !$this->_controller->RequestHandler->isAjax()){
            if(substr($this->_controller->request->here, 0, strlen('/users/')) != '/users/' && substr($this->_controller->request->here, 0, strlen('/system/')) != '/system/') $this->_controller->Session->write('Auth.redirect', $_SERVER['REQUEST_URI']);
        }
    }
    

	public function startup(Controller $controller) {
	    //The startup method is called after the controller�s beforeFilter method but before the controller executes the current action handler.
        $this->__setLanguages();
        if(count($this->_controller->cms['languages']) > 1) Configure::write('Config.req_language', Configure::read('Config.language'));   
	}

   
    public function __setPlugins(){
        //CakePlugin::loadAll(array(array('bootstrap' => true)));
        foreach(App::objects('plugin') as $plugin){
            foreach(App::path('plugins') as $path){
                if(isset($this->_controller->request->params['admin'])){
                    if(is_file($path . $plugin . DS . 'Config' . DS . 'bootstrap.php')){
                        include $path . $plugin . DS . 'Config' . DS . 'bootstrap.php';
                        break;
                    }
                } else {
                    if(is_file($path . $plugin . DS . 'Config' . DS . 'bootstrap_front.php')){
                        include $path . $plugin . DS . 'Config' . DS . 'bootstrap_front.php';
                        break;
                    }
                }
            }
        }
    }

    public function __setLanguage(){
        
        $active_languages = json_decode($this->_controller->CmsSetting->field('value', array('option' => 'active_languages', 'plugin' => 'base')));
        $default_language = $this->_controller->CmsSetting->field('value', array('option' => 'def_language', 'plugin' => 'base'));
        if(empty($default_language)) $default_language = key(Configure::read('CMS.languages'));
        
        if(!empty($_GET['lang']) && !empty($active_languages) && in_array($_GET['lang'], $active_languages)){
            $active_language = $_GET['lang'];
        } else if(!empty($this->_controller->request->params['lang']) && !empty($active_languages) && in_array($this->_controller->request->params['lang'], $active_languages)){
            $active_language = $this->_controller->request->params['lang'];
        } else if($this->_controller->Session->check('lang') && !empty($active_languages) && in_array($this->_controller->Session->read('lang'), $active_languages)){
            $active_language = $this->_controller->Session->read('lang');
        } else if($this->_controller->Cookie->check('lang') && !empty($active_languages) && in_array($this->_controller->Cookie->read('lang'), $active_languages)){
            $active_language = $this->_controller->Cookie->read('lang');
        } else {
            $active_language = $default_language;
        }
        
        Configure::write('Config.language', $active_language);
        Configure::write('Config.def_language', $default_language);
        $this->_controller->Session->write('lang', $active_language);
        $this->_controller->Cookie->write('lang', $active_language);
        
        Configure::write('CMS.languages', ___array(Configure::read('CMS.languages')));

        $_all_languages = Configure::read('CMS.languages');
        $_active_languages = array($default_language => $_all_languages[$default_language]);
        if(!empty($active_languages)) foreach($_all_languages as $_lang => $lang){
            if(in_array($_lang, $active_languages) && $_lang != $default_language) $_active_languages[$_lang] = $lang;
        }
        Configure::write('CMS.activelanguages', $_active_languages);
    }

    public function __setLanguages(){
        $active_language = Configure::read('Config.language');
        $default_language = Configure::read('Config.def_language');

        //setlocale(LC_ALL, 'ro_RO');
        
        $languages = array();
        $path_locales = Configure::read('CMS.path_locales');
        $path_locales_base = Configure::read('CMS.path_locales_base');
        
        foreach(Configure::read('CMS.activelanguages') as $key => $val){
            $languages[$key]['title'] = $val;
            $languages[$key]['code'] = $key;
            $languages[$key]['scode'] = mb_substr($key, 0, 2);
            $languages[$key]['stitle'] = mb_substr($val, 0, 2);
            $languages[$key]['ltitle'] = mb_substr($val, 0, 3);
            $languages[$key]['active'] = ($key == $active_language ? 'active' : null);
            if(!empty($path_locales[$key])){
                $languages[$key]['url'] = '/' . $key . (!empty($path_locales_base) ? '/' . $path_locales_base[$key] : null) . '/' . $path_locales[$key] . '/';
            } else if(!empty($path_locales[$default_language])){
                $languages[$key]['url'] = '/' . $key . (!empty($path_locales_base) ? '/' . $path_locales_base[$default_language] : null) . '/' . $path_locales[$default_language] . '/';
            } else {
                $languages[$key]['url'] = '/' . $key . '/' . $this->_controller->request->fulluri . '/';
            }
            $languages[$key]['url'] = str_replace('//', '/', $languages[$key]['url']);
            $languages[$key]['url'] = rtrim($languages[$key]['url'], '/');
        }

        $this->_controller->cms['languages'] = $languages;
        $this->_controller->cms['language'] = $languages[Configure::read('Config.language')];
    }

    public function __setTranslates(){
        Configure::write('CMS.translates', $this->_controller->CmsTranslate->find('list', array('fields' => array('CmsTranslate.key', 'CmsTranslate.value', 'CmsTranslate.locale'))));
    }

    public function __setCurrencies(){
        Configure::write('Obj.currencies', array(key(Configure::read('CMS.currency')) => array('currency' => key(Configure::read('CMS.currency')), 'title' => reset(Configure::read('CMS.currency')), 'value' => '1')));
        Configure::write('Obj.currency', array('currency' => key(Configure::read('CMS.currency')), 'title' => reset(Configure::read('CMS.currency')), 'value' => '1'));
        Configure::write('Obj.currencies_vals', array(key(Configure::read('CMS.currency')) => '1'));
        
        $this->_controller->set('currencies', Configure::read('CMS.currency'));
    }

    public function __setSettings(){
        Configure::write('CMS.settings', $this->_controller->CmsSetting->get_list());
        $this->_controller->set('cfg', Configure::read('CMS.settings'));
    }
    
    public function __Maintenance(){
        if(!isset($this->_controller->request->params['admin']) && $this->_controller->request->params['controller'] != 'system') if(Configure::read('CMS.settings.base.disable') == '1'){
            if(!in_array($this->_controller->request->clientIp(), nl2array(Configure::read('CMS.settings.base.maintenance_ips'))))
            exit(Configure::read('CMS.settings.base.maintenance_body'));
        }
    }
    
    public function __Permissions(){
        if(isset($this->_controller->request->params['admin']) && $this->_controller->Session->check('Auth.User.id') && $this->_controller->Session->read('Auth.User.role') != 'admin'){
            Configure::write('admin_type', $this->_controller->Session->read('Auth.User.UserRole.type'));
            
            $_role_perms = Classregistry::init('Users.UserRolePerm')->find('all', array('fields' => array('UserRolePerm.controller', 'UserRolePerm.action', 'UserRolePerm.plugin'), 'conditions' => array('UserRolePerm.role' => array($this->_controller->Session->read('Auth.User.role'), '0'))));
            foreach($_role_perms as $_role_perm){
                if(substr($_role_perm['UserRolePerm']['controller'], 0, 4) == 'item'){
                    $role_perms[$_role_perm['UserRolePerm']['plugin']]['item'][$_role_perm['UserRolePerm']['action']] = '1';
                }
                if(substr($_role_perm['UserRolePerm']['controller'], 0, 4) == 'base'){
                    $role_perms[$_role_perm['UserRolePerm']['plugin']]['base'][$_role_perm['UserRolePerm']['action']] = '1';
                }
                $role_perms[$_role_perm['UserRolePerm']['plugin']][$_role_perm['UserRolePerm']['controller']][$_role_perm['UserRolePerm']['action']] = '1';
            }
            Configure::write('role_perms', $role_perms);
            
            if($this->_controller->request->params['controller'] != 'page' && !in_array($this->_controller->request->params['action'], array('admin_login', 'admin_logout'))){
                $plugin = (!empty($this->_controller->request->params['tid']) ? $this->_controller->request->params['tid'] : $this->_controller->request->params['plugin']);
                if(empty($plugin) && $this->_controller->request->params['controller'] == 'system') return true;
                $controller = (!empty($this->_controller->request->params['controller']) ? $this->_controller->request->params['controller'] : $this->_controller->request->params['plugin']);
                $action = str_replace('admin_', '', $this->_controller->request->params['action']);
                //$access = Classregistry::init('Users.UserRolePerm')->find('count', array('conditions' => array('UserRolePerm.role' => array($this->_controller->Session->read('Auth.User.role'), '0'), 'UserRolePerm.plugin' => $plugin, 'UserRolePerm.controller' => $controller, 'UserRolePerm.action' => $action)));
                $access = $role_perms[$plugin][$controller][$action];
                if(substr($action, 0, 4) == 'pbl_') $access = 1;
                if($access > 0){
                    // OK
                } else {
                    $this->_controller->redirect('/admin/base/page/deny/?tm=0');
                }
            }
        }
    }
}

?>
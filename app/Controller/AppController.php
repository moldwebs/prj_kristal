<?php
class AppController extends Controller {

    public $cmstheme = null;

    public $cms = array();

    public $uses = array('ExtraData', 'ObjItemTree', 'ObjItemList', 'ObjItemTreeNull', 'ObjItemListNull', 'ObjOptType', 'CmsSetting', 'CmsTranslate', 'CmsAlias');

	public $components = array(
		'System',
        //'DebugKit.Toolbar',
        'Stats.Stats',
		//'Security',
		'Auth',
		'Session',
		'Cookie' => array('name' => 'Cookie', 'path' => '/', 'time' => '2629743'),
		'RequestHandler',
        'Upload',
        'Basic',
        'Template',

	);

	public $helpers = array(
		'Html',
		'Form',
		'Session',
		'Text',
		'Js',
		'Time',
		'Layout',
	);

	public $paginate = array(
        'paramType' => 'querystring',
		'limit' => CMS_PAGE_LIMIT,
	);

    public function beforeFilter() {

        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('beforeFilter1:' . date("Y-m-d H:i:s"))));

        define('TIME_START_1', microtime(true));

        if(is_dir(ROOT . DS . 'web_themes')){
            if(!empty($_GET['cmstheme'])){
                $this->cmstheme = $_GET['cmstheme'];
            } else {
                $this->cmstheme = '1_kuteshop';
            }
        }

        if(!empty($this->cmstheme)){
            if(strpos($this->cmstheme, '_') !== false){
                if(is_dir(str_replace(DS . 'layout' , '', EXT_THEMES) . DS . 'template' . DS . $this->cmstheme)){
                    $themepath = "/{$this->cmstheme}/";
                } else {
                    $themepath = "/" . strstr($this->cmstheme, '-', true) . "/";
                }
            } else {
                $themepath = "/{$this->cmstheme}/";
            }
        } else {
            $themepath = '/';
        }

        $this->set('themepath', $themepath);

        if(isset($_GET['set_sys_layout'])){
            $this->Cookie->write('sys_layout', $_GET['set_sys_layout']);
        }
        if($this->Cookie->read('sys_layout')){
            $_GET['sys_layout'] = $this->Cookie->read('sys_layout');
        }

        if(!empty($_GET['sys_layout'])){
            $_GET['sys_layout'] = preg_replace("/[^a-zA-Z0-9]+/", "", $_GET['sys_layout']);

            $this->request->query['sys_layout'] =  $_GET['sys_layout'];
            $this->set('sys_layout', $_GET['sys_layout']);
        }

        if(!empty($_GET['scopefield']) && strpos($this->request->referer(true), 'scopefield=') === false) $this->Session->write('TMP.scopefield_back', $this->request->referer(true));

        $this->request->fhere = $_SERVER['REQUEST_URI'];

		$this->request->fulluri = $_SERVER['REQUEST_URI'];
		$this->request->fullruri = str_replace('//', '/', str_replace('?', '/?', $_SERVER['REQUEST_URI']));
        $this->request->fullhere = Router::reverse($this->request);
		$this->request->fullurl = $this->request->here;

        if(!empty($this->request->params['lang']) && $this->request->params['lang'] != 'rom'){
            //_pr($this->request->params['lang']);
            //_pr($_SERVER['REQUEST_URI']);
            //exit;
            //$this->redirect(str_replace('/' . $this->request->params['lang'], '/rom', $_SERVER['REQUEST_URI']));
        }

        if(!empty($this->request->params['lang'])){
            $this->request->url = str_replace("/{$this->request->params['lang']}/", '/', '/'.$this->request->url);
            $this->request->here = str_replace("/{$this->request->params['lang']}/", '/', $this->request->here);
            $this->request->fulluri = str_replace("/{$this->request->params['lang']}/", '/', $this->request->fulluri);
            $this->request->url = str_replace("/{$this->request->params['lang']}", '/', '/'.$this->request->url);
            $this->request->here = str_replace("/{$this->request->params['lang']}", '/', $this->request->here);
            $this->request->fulluri = str_replace("/{$this->request->params['lang']}", '/', $this->request->fulluri);
            $this->request->url = str_replace("{$this->request->params['lang']}/", '', '/'.$this->request->url);
            $this->request->here = str_replace("{$this->request->params['lang']}/", '', $this->request->here);
            $this->request->fulluri = str_replace("{$this->request->params['lang']}/", '', $this->request->fulluri);
        } else {
            if(!isset($this->request->params['admin']) && empty($this->request->params['requested']) && $this->request->params['controller'] != 'system'){
                if($this->request->here == '/') $this->redirect('/' . Configure::read('Config.language'));
            }
        }

        if(!empty($this->request->params['page'])) $this->request->params['query']['page'] = $this->request->params['page'];

        if(!empty($this->request->params['query'])){
            //$this->request->query = am($this->request->query, $this->request->params['query']);
            if(!empty($this->request->params['query']['page'])){
                $this->request->url = rtrim($this->request->url, "/{$this->request->params['query']['page']}");
                $this->request->here = rtrim($this->request->here, "/{$this->request->params['query']['page']}");
            }
        }

        /*
        foreach(Router::connect() as $route){
            _pr($route->template);
        }
        exit;
        */

        $this->set('pget', $this->request->params['pass']);

        if(isset($this->request->params['pass'][0]) && empty($this->request->params['pass'][0])) unset($this->request->params['pass'][0]);

        $this->request->uri = Configure::read('CMS.path_here');

        $this->Auth->loginAction = array('plugin' => 'users', 'controller' => 'users', 'action' => 'login');

        $this->viewClass = 'Cms';

        $var = $this->ObjItemList->actsAs;
        $var = $this->ObjItemTree->actsAs;

		if(empty($this->request->params['requested'])) if(is_file(CFG_ROOT . DS . 'preload.php')) include CFG_ROOT . DS . 'preload.php';
		if(empty($this->request->params['requested'])) if(is_file(EXT_CFG_ROOT . DS . 'preload.php')) include EXT_CFG_ROOT . DS . 'preload.php';

		if(isset($this->request->params['admin'])){
			$this->layout = 'admin';
            if(empty($this->request->params['requested'])) $this->__execAdminPreload();
		} else {
		   $this->Auth->allow();
           if(empty($this->request->params['requested'])){
                $this->__execSYSCron();
                $this->__execCron();
           }
           if(empty($this->request->params['requested'])) $this->__execPreload();
		}

        if(empty($this->request->params['requested'])) $this->__execAllPreload();

		if ($this->RequestHandler->isAjax()) {
			$this->layout = 'ajax';
            $this->set('is_ajax', '1');
		}

        if($this->request->query['tm'] != ''){
            $this->Session->write('Nav.active_top_menu', $this->request->here);
            $this->Session->delete('Nav.active_top_button');
        }
        if(!empty($this->request->query['tb'])) $this->Session->write('Nav.active_top_button', $this->request->here);

        //$this->cms['breadcrumbs']['/'] = 'Home';

        if (!empty($this->request->params['requested'])){
            $this->cms = Configure::read('CMS.cms');
        }

        if(!isset($this->request->params['admin']) && empty($this->request->params['requested'])) $this->Basic->template(Configure::read('CMS.settings.base'), 'base');

        if(!empty($this->request->query['test']) && empty($this->request->params['requested'])){
            //$this->Basic->mail('moldwebs@gmail.com;logicweb.work@gmail.com', 'Cerere Credit', 'TEST MSG');
            //echo 'TEST OK';
        }

        // ----------------------------------------------------------------------------------------
        Configure::write('TMP.sql_no_cache', '0');
        $this->ObjItemList->st_cond = '0';

        $this->set('uri', $this->request->here);
        $this->set('request', $this->request);

        if($this->request->here == '/'){
            $this->layout = 'home';
        }

        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('beforeFilter2:' . date("Y-m-d H:i:s"))));
    }

    public function afterFilter() {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('afterFilter1:' . date("Y-m-d H:i:s"))));
        if(!isset($this->request->params['admin']) && empty($this->request->params['requested']) && !$this->RequestHandler->isAjax()){
            //pr($this->response->statusCode());
            if($this->response->statusCode() == '200'){
                if(substr($this->request->here, 0, strlen('/users/')) != '/users/' && substr($this->request->here, 0, strlen('/system/')) != '/system/') $this->Session->write('Auth.redirect', $_SERVER['REQUEST_URI']);
            }
        }
        define('TIME_START_2', microtime(true));
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('afterFilter2:' . date("Y-m-d H:i:s"))));
    }

    public function beforeRender() {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('beforeRender1:' . date("Y-m-d H:i:s"))));
        if(!empty($this->request->query['sys_layout'])){
            if($this->request->query['sys_layout'] == 'none'){
                $this->layout = false;
            } else {
                $this->layout = $this->request->query['sys_layout'];
            }
        }

        if(!empty($_GET['is_push'])){
            $this->layout = 'default';
        }

        //$this->__execPreload();
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('beforeRender2:' . date("Y-m-d H:i:s"))));
    }

    public function set($var = null, $data = null){
        if(empty($this->request->params['requested'])){
            parent::set($var, $data);
        } else {
            return $data;
        }
    }

	public function paginate($object = null, $scope = array(), $whitelist = array()) {
	    if(!isset($this->request->params['admin']) && $this->paginate[$object]['tid'] !== false && $object == 'ObjItemList'){
            $settings = Configure::read('CMS.settings');

            if($this->Cookie->check("Toggle." . Configure::read('Config.tid') . "_limit")){
                $this->paginate[$object]['limit'] = $this->Cookie->read("Toggle." . Configure::read('Config.tid') . "_limit");
            } else if($this->Cookie->check("Paginate." . Configure::read('Config.tid') . ".limit")){
                $this->paginate[$object]['limit'] = $this->Cookie->read("Paginate." . Configure::read('Config.tid') . ".limit");
            } else if(!empty($settings[Configure::read('Config.tid')]['obj_limit'])){
                $this->paginate[$object]['limit'] = $settings[Configure::read('Config.tid')]['obj_limit'];
            }

            if($this->Cookie->check("Toggle." . Configure::read('Config.tid') . "_order")){
                unset($this->paginate[$object]['order']['ObjItemList.created']);
                $this->paginate[$object]['order'] = am($this->paginate[$object]['order'], array(ws_expl('_', $this->Cookie->read("Toggle." . Configure::read('Config.tid') . "_order"), 0) => ws_expl('_', $this->Cookie->read("Toggle." . Configure::read('Config.tid') . "_order"), 1)));
            } else if($this->Cookie->check("Paginate." . Configure::read('Config.tid') . ".order")){
                unset($this->paginate[$object]['order']['ObjItemList.created']);
                $this->paginate[$object]['order'] = am($this->paginate[$object]['order'], array(ws_expl(':', $this->Cookie->read("Paginate." . Configure::read('Config.tid') . ".order"), 0) => ws_expl(':', $this->Cookie->read("Paginate." . Configure::read('Config.tid') . ".order"), 1)));
            } else if(!empty($settings[Configure::read('Config.tid')]['obj_order'])){
                unset($this->paginate[$object]['order']['ObjItemList.created']);
                $this->paginate[$object]['order'] = am($this->paginate[$object]['order'], array(ws_expl(':', $settings[Configure::read('Config.tid')]['obj_order'], 0) => ws_expl(':', $settings[Configure::read('Config.tid')]['obj_order'], 1)));
            }
            if(!empty($settings[Configure::read('Config.tid')]['obj_sn_translate'])){
                $scope[] = "{$object}.id IN (SELECT `foreign_key` FROM `wb_cms_i18n` WHERE `model` = '{$object}' AND `locale` = '" . Configure::read('Config.language') . "')";
            }
            $this->paginate[$object]['order'] = am(array('order_id' => 'asc'), $this->paginate[$object]['order']);
        }
        //if(!$this->Cookie->check("Toggle." . Configure::read('Config.tid') . "_order")) $this->Cookie->write("Toggle." . Configure::read('Config.tid') . "_order", str_replace('ObjItemList.', '', key($this->paginate[$object]['order'])) . '_' . $this->paginate[$object]['order'][key($this->paginate[$object]['order'])]);
        //if(!$this->Cookie->check("Toggle." . Configure::read('Config.tid') . "_limit")) $this->Cookie->write("Toggle." . Configure::read('Config.tid') . "_limit", $this->paginate[$object]['limit']);
        $this->set('cms_paginate', $this->paginate[$object]);
		return $this->Components->load('Paginator', $this->paginate)->paginate($object, $scope, $whitelist);
	}

    public function __execCron(){
        if(date("G") >= 5 || 1==1){
            if($this->request->query['cron'] == '1' || Configure::read('Config.cron') == '1'){
                if(Cache::read('exec_cron') != date("Y-m-d") || $this->request->query['cron'] == '1'){
                    Cache::write('exec_cron', date("Y-m-d"));
                    set_time_limit(0);
                    if(is_file(EXT_CFG_ROOT . DS . 'cron.php')) include EXT_CFG_ROOT . DS . 'cron.php';
                    foreach(App::objects('plugin') as $plugin){
                        foreach(App::path('plugins') as $path){
                            if(is_file($path . $plugin . DS . 'Config' . DS . 'cron.php')){
                                include $path . $plugin . DS . 'Config' . DS . 'cron.php';
                                break;
                            }
                        }
                    }
                    if(empty($this->request->query['cron']) && empty($_GET['clear']) && Configure::read('debug') == '0') $this->redirect($this->here);
                    //exit('CRON-END');
                }
                if(Configure::read('Config.cron') != '1') exit('CRON-END');
            }
        }

    }

    public function __execSYSCron(){
        if(Cache::read('exec_sys_cron') != date("Y-m")){
            $db_schema = array();
            foreach($this->ExtraData->query("SHOW TABLES") as $table){
                $table = reset(reset($table));
                $columns = $this->ExtraData->query("SHOW COLUMNS FROM {$table}");
                foreach($columns as $column){
                    $column = reset($column);
                    $db_schema[$table][$column['Field']] = $column;
                }
            }
            file_put_contents(EXT_CFG_ROOT . DS . 'db.data', serialize($db_schema));
            Cache::write('exec_sys_cron', date("Y-m"));
        }
    }

    public function __execAllPreload(){
        foreach(App::objects('plugin') as $plugin){
            foreach(App::path('plugins') as $path){
                if(is_file($path . $plugin . DS . 'Config' . DS . 'all_preload.php')){
                    include $path . $plugin . DS . 'Config' . DS . 'all_preload.php';
                    break;
                }
            }
        }
    }

    public function __execPreload(){
        foreach(App::objects('plugin') as $plugin){
            foreach(App::path('plugins') as $path){
                if(is_file($path . $plugin . DS . 'Config' . DS . 'preload.php')){
                    include $path . $plugin . DS . 'Config' . DS . 'preload.php';
                    break;
                }
            }
        }
    }

    public function __execAdminPreload(){
        foreach(App::objects('plugin') as $plugin){
            foreach(App::path('plugins') as $path){
                if(is_file($path . $plugin . DS . 'Config' . DS . 'admin_preload.php')){
                    include $path . $plugin . DS . 'Config' . DS . 'admin_preload.php';
                    break;
                }
            }
        }
    }
}

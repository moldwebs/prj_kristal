<?php
class RolesController extends UsersAppController {

    public $uses = array('Users.UserRole', 'Users.UserRolePerm');

    public $paginate = array(
        'UserRole' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'UserRole.title' => 'asc'
            )
        )
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function admin_table_actions(){
        $this->UserRole->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Roles') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('UserRole', $this->Basic->filters('UserRole')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->UserRole, true);
        
        if($id){
            $this->set('page_title', ___('Roles') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Roles') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

	public function admin_delete($id = null){
	    $this->UserRole->delete($id);
        $this->Basic->back();
	}

    public function admin_permissions($role = null, $module = null){
        foreach(App::objects('plugin') as $plugin){
            foreach(App::path('plugins') as $path){
                if(is_dir($path . $plugin . DS . 'Controller')){
                    $module = strtolower($plugin);
                    if($module == 'users') continue;
                    foreach(scandir($path . $plugin . DS . 'Controller') as $file){
                        $controller = strtolower(str_replace('Controller.php', '', $file));
                        if($controller == 'system') continue;
                        if($controller == 'page') continue;
                        if($file == '..' || $file == '.' || strpos($file, '.bak') !== false) continue;
                        if(preg_match_all('/function admin\_(.*?)\(/', file_get_contents($path . $plugin . DS . 'Controller' . DS . $file), $matches)){
                            foreach($matches[1] as $match){
                                if(substr_count($match, 'pbl_')) continue;
                                if($module == 'fill'){
                                    foreach(array_keys(Configure::read('CMS.fill_tid')) as $tid) $modules_actions[$tid][$controller][] = $match;
                                } else if($module == 'comment'){
                                    foreach(array(Configure::read('CMS.fill_tid'), Configure::read('CMS.sys_tid')) as $tids){
                                        foreach(array_keys($tids) as $tid){
                                            if(!empty($tids[$tid]['opts']['comments'])) $modules_actions[$tid][$controller][] = $match;
                                        }
                                    }
                                } else if($module == 'type'){
                                    foreach(array(Configure::read('CMS.fill_tid'), Configure::read('CMS.sys_tid')) as $tids){
                                        foreach(array_keys($tids) as $tid){
                                            if(!empty($tids[$tid]['opts']['types'])) $modules_actions[$tid][$controller][] = $match;
                                        }
                                    }
                                } else if($module == 'specification'){
                                    foreach(array(Configure::read('CMS.fill_tid'), Configure::read('CMS.sys_tid')) as $tids){
                                        foreach(array_keys($tids) as $tid){
                                            if(!empty($tids[$tid]['opts']['specifications'])) $modules_actions[$tid][$controller][] = $match;
                                        }
                                    }
                                } else if($module == 'sfill'){
                                    foreach(array_keys(Configure::read('CMS.sfill_tid')) as $tid) $modules_actions[$tid][$controller][] = $match;
                                } else {
                                    $modules_actions[$module][$controller][] = $match;
                                }
                                
                            }
                        }
                    }
                }
            }
        }
        
        $modules_actions['partner_project']['comment'][] = 'add';
        $modules_actions['partner_order']['comment'][] = 'add';
        
        $this->set('modules_actions', $modules_actions);
        
        foreach(array_keys($modules_actions) as $module){
            $modules[$module] = ucfirst($module);
        }
        
        $this->set('modules', $modules);

        $groups = $this->UserRole->find('list', array('fields' => array('UserRole.id', 'UserRole.title')));
        if(Configure::read('CMS.user_perms') != '') $groups = Configure::read('CMS.user_perms') + $groups;
        $this->set('groups', $groups);
        
        $_permissions = $this->UserRolePerm->find('all', array('conditions' => array('role' => $role)));
        foreach($_permissions as $_permission){
            $permissions[$_permission['UserRolePerm']['plugin']][$_permission['UserRolePerm']['controller']][$_permission['UserRolePerm']['action']] = true;
        }
        $this->set('permissions', $permissions);
    }
    
    public function admin_set_permission($role = null, $plugin = null, $controller = null, $action = null){
        $data = array('role' => $role, 'plugin' => $plugin, 'controller' => $controller, 'action' => $action);
        if($this->UserRolePerm->find('count', array('conditions' => $data)) > 0){
            $this->UserRolePerm->deleteAll($data);
        } else {
            $this->UserRolePerm->create();
            $this->UserRolePerm->save($data);
        }
        exit();
    }

}

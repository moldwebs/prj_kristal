<?php
class LinkController extends BaseAppController {

    public function beforeFilter() {
        parent::beforeFilter();
        Configure::write('Config.tid', 'cms_link');
    }

    public function admin_table_actions(){
        $this->ObjItemTree->table_actions($this->request->data);
        $this->Basic->back();
    }

    public function admin_table_structure(){
        $this->ObjItemTree->table_structure($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Pages') . ' :: ' . ___('List'));
        
        $this->set('items', $this->ObjItemTree->find('all', array('conditions' => array('extra_1 <>' => '6'), 'order' => 'ObjItemTree.lft')));
    }
    
    public function admin_edit_menu($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Menu') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Menu') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

    public function admin_edit_page($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Pages') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Pages') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('parents', $this->ObjItemTree->TreeList(array('extra_1 <>' => '3', 'extra_1 <>' => '6')));
    }
    
    public function admin_edit_set($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Set') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Set') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('parents', $this->ObjItemTree->TreeList(array('extra_1 <>' => '3', 'extra_1 <>' => '6')));
    }
    
    public function admin_edit_list($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('List') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('List') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('parents', $this->ObjItemTree->TreeList(array('extra_1 <>' => '3', 'extra_1 <>' => '6')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Links') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Links') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('parents', $this->ObjItemTree->TreeList(array('extra_1 <>' => '3', 'extra_1 <>' => '6')));
    }

	public function admin_delete($id = null){
	    if($this->ObjItemTree->fread('tpl_obj', $id) == '1'){
	       $this->Session->setFlash(___('Forbidden to remove template element (is possible to hide).'), 'flash');
           $this->Basic->back();
	    }
        
        if($this->ObjItemTree->fread('extra_1', $id) == '5'){
	       foreach($this->ObjItemTree->find('list', array('conditions' => array('parent_id' => $id))) as $_id => $__id){
	           $this->ObjItemTree->delete($_id);
	       }
	    }
	    $this->ObjItemTree->delete($id);
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjItemTree->toggle($id, 'status');
        $this->Basic->back();
    }
    
    public function admin_transform($id = null, $type = null){
        if($type == '1'){
            $this->ObjItemTree->updateAll(array("ObjItemTree.extra_1" => sqls('0', true)), array("ObjItemTree.id" => $id));
            $this->redirect("/admin/base/link/edit/{$id}");
        } else if($type == '2'){
            $this->ObjItemTree->updateAll(array("ObjItemTree.extra_1" => sqls('2', true)), array("ObjItemTree.id" => $id));
            $this->redirect("/admin/base/link/edit_page/{$id}");
        }
        exit;
    }
    
    public function get_list($code = null){
        $menu = $this->ObjItemTree->find('first', array('conditions' => array(array('OR' => array('ObjItemTree.code' => $code, 'ObjItemTree.id' => $code)), 'ObjItemTree.extra_1' => '1')));
        $ids = $this->ObjItemTree->find('list', array('conditions' => array('ObjItemTree.lft >' => $menu['ObjItemTree']['lft'], 'ObjItemTree.rght <' => $menu['ObjItemTree']['rght'])));
        $items = $this->ObjItemTree->find('threaded', array('conditions' => array('OR' => array(array('ObjItemTree.lft >' => $menu['ObjItemTree']['lft'], 'ObjItemTree.rght <' => $menu['ObjItemTree']['rght']), array('ObjItemTree.lft IS NULL AND ObjItemTree.parent_id IN ('.sqlimplode(',', array_keys($ids)).')'))), 'order' => array('ObjItemTree.lft' => 'ASC', 'ObjItemTree.extra_3' => 'ASC', 'ObjItemTree.title' => 'ASC')));
        return $items;
    }
    
    public function get_parents($id = null){
        return $this->ObjItemTree->getPath($id, null, 0);
    }
    
}

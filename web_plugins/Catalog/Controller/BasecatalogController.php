<?php
class BasecatalogController extends CatalogAppController {

    public function admin_table_actions(){
        $this->ObjItemTree->table_actions($this->request->data);
        $this->Basic->back();
    }

    public function admin_table_structure(){
        $this->ObjItemTree->table_structure($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Categories') . ' :: ' . ___('List'));
        
        $this->set('items', $this->ObjItemTree->find('all', array('order' => 'ObjItemTree.lft')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Categories') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Categories') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('parents', $this->ObjItemTree->TreeList());
    }

	public function admin_delete($id = null){
	    $this->ObjItemTree->delete($id);
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjItemTree->toggle($id, 'status');
        $this->Basic->back();
    }

    public function index(){
        //$this->ObjItemTree->virtualFields['count'] = "(SELECT COUNT(*) FROM `wb_obj_item_list` WHERE `wb_obj_item_list`.`base_id` = ObjItemTree.id AND `wb_obj_item_list`.`status` = '1')";
        
        if(!empty($_GET['base_id'])){
            $base = $this->ObjItemTree->find('first', array('conditions' => array('ObjItemTree.id' => $_GET['base_id'])));
            $this->set('base', $base);
            $items = $this->ObjItemTree->find('threaded', array('conditions' => array('ObjItemTree.rght <' => $base['ObjItemTree']['rght'], 'ObjItemTree.lft >' => $base['ObjItemTree']['lft']), 'order' => array('ObjItemTree.lft' => 'ASC')));            
        } else {
            $items = $this->ObjItemTree->find('threaded', array('order' => array('ObjItemTree.lft' => 'ASC')));
        }
        //$items = $this->Template->__get_active_tree($items);
        
        return $this->set('items', $items);
    }

    public function get_list($base_id = null, $simple = 0){
        $conditions = array();

        if(empty($simple)){
                    
            //$this->ObjItemTree->virtualFields['count'] = "(SELECT COUNT(*) FROM `wb_obj_item_list` WHERE `wb_obj_item_list`.`base_id` = ObjItemTree.id AND `wb_obj_item_list`.`status` = '1' AND `wb_obj_item_list`.`extra_1` = '6')";

            if($base_id == 'active') $base_id = (!empty($this->cms['active_base']) ? $this->cms['active_base'] : null);
            if(!empty($base_id)) $conditions = am($conditions, array('ObjItemTree.id' => (is_array($base_id) ? $base_id : $this->ObjItemTree->childrens($base_id, 'id', false))));
            
            $items = $this->ObjItemTree->find('threaded', array('conditions' => $conditions, 'order' => array('ObjItemTree.lft' => 'ASC')));
        } else {
            if($catalog_base_id = Configure::read('CMS.catalog_base_id')){
                $conditions = am($conditions, array("ObjItemTree.parent_id = {$catalog_base_id}"));
            } else {
                $conditions = am($conditions, array('ObjItemTree.parent_id IS NULL'));
            }
            
            $items = $this->ObjItemTree->find('threaded', array('conditions' => $conditions, 'order' => array('ObjItemTree.lft' => 'ASC')));
        }
        
        return $this->Template->__get_active_tree($items, $this->cms['active_base']);
    }
    
    public function get_tree($base_id = null){
        $conditions = array();
        
        $items = $this->ObjItemTree->TreeList(array('ObjItemTree.parent_id IS NULL'));
        
        return $items;
    }
    
    public function get_clist($base_id = null){
        $conditions = array();
        
        $conditions = am($conditions, array("ObjItemTree.parent_id" => $base_id));
        
        $items = $this->ObjItemTree->find('all', array('conditions' => $conditions, 'order' => array('ObjItemTree.lft' => 'ASC'), 'limit' => $this->params['named']['mod_limit']));

        
        return array('base' => $this->ObjItemTree->findById($base_id), 'items' => $items);
    }
}

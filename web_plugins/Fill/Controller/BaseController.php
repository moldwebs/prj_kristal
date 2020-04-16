<?php
class BaseController extends FillAppController {

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
        $this->ObjItemTree->virtualFields['count'] = "(SELECT COUNT(*) FROM `wb_obj_item_list` WHERE `wb_obj_item_list`.`base_id` = ObjItemTree.id AND `wb_obj_item_list`.`status` = '1')";
        
        $items = $this->ObjItemTree->find('threaded', array('order' => array('ObjItemTree.lft' => 'ASC')));
        $items = $this->Template->__get_active_tree($items);
        
        return $this->set('items', $items);
    }

    public function get_list($base_id = null){
        $conditions = array();
        
        $this->ObjItemTree->virtualFields['count'] = "(SELECT COUNT(*) FROM `wb_obj_item_list` WHERE `wb_obj_item_list`.`base_id` = ObjItemTree.id AND `wb_obj_item_list`.`status` = '1')";
                
        if($base_id == 'active') $base_id = (!empty($this->cms['active_base']) ? $this->cms['active_base'] : null);
        if($base_id) $conditions = am($conditions, array('ObjItemTree.id' => (is_array($base_id) ? $base_id : $this->ObjItemTree->childrens($base_id, 'id', false))));
        if(!empty($this->params['named']['mod_limit']) && $this->params['named']['mod_limit'] == '1') $conditions = am($conditions, array('ObjItemTree.parent_id' => $base_id));
        
        $items = $this->ObjItemTree->find('threaded', array('conditions' => $conditions, 'order' => array('ObjItemTree.lft' => 'ASC')));
        
        unset($this->ObjItemTree->virtualFields['count']);
        
        return $this->Template->__get_active_tree($items, $this->cms['active_base']);
    }
}

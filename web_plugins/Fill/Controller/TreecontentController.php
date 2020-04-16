<?php
class TreecontentController extends FillAppController {

    public function beforeFilter() {
        parent::beforeFilter();
        
        Configure::write('Config.tid', Configure::read('Config.tid') . '_treecontent');
        
        Configure::write('top_buttons_add', array(
            'list' => array('title' => ___('Content') . ' :: ' . ___('List'), 'url' => array('controller' => 'Treecontent', 'action' => 'index')),
            'section' => array('title' => ___('Section') . ' :: ' . ___('Create'), 'url' => array('controller' => 'Treecontent', 'action' => 'edit_section')),
            'edit' => array('title' => ___('Content') . ' :: ' . ___('Create'), 'url' => array('controller' => 'Treecontent', 'action' => 'edit')),
        ));
        
        if(isset($this->request->params['admin'])) $this->ObjItemTree->Behaviors->attach('Scopefield', array('field' => 'extra_3', 'value' => $this->request->query['scopefield']));
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
        $this->set('page_title', ___('Content Tree') . ' :: ' . ___('List'));
        
        $this->set('items', $this->ObjItemTree->find('all', array('order' => 'ObjItemTree.lft')));
    }

    public function admin_edit_section($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Section') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Section') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Content') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Content') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('parents', $this->ObjItemTree->TreeList(array('extra_1' => '1')));
    }

	public function admin_delete($id = null){
	    $this->ObjItemTree->delete($id);
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjItemTree->toggle($id, 'status');
        $this->Basic->back();
    }

    public function index($id = null){
        $this->cms['active_item'] = $id;
        
        $item = $this->Basic->load($id, $this->ObjItemList);
        $this->set('item', $item);
    }

    public function view($id = null){
        $this->cms['active_item'] = $id;
        
        $item = $this->Basic->load($id, $this->ObjItemTree);

        $this->set('item', $item);
    }

    public function get_list($item_id = null){
        if(empty($item_id)) exit;
        $conditions = array();
        
        $this->ObjItemTree->virtualFields['progress'] = "(SELECT COUNT(*) FROM `wb_obj_opt_relation` WHERE `wb_obj_opt_relation`.`type` = `ObjItemTree`.`id` AND `wb_obj_opt_relation`.`rel_id` = '".$this->Session->read('Auth.User.id')."')";
        
        if($item_id == 'active') $item_id = (!empty($this->cms['active_item']) ? $this->cms['active_item'] : null);
        if($item_id) $conditions = am($conditions, array('ObjItemTree.extra_3' => $item_id));
        
        $items = $this->ObjItemTree->find('threaded', array('conditions' => $conditions, 'order' => array('ObjItemTree.lft' => 'ASC')));
        return $items;
    }
    
    public function get_list_tree($item_id = null){
        if(empty($item_id)) exit;
        $conditions = array();
        
        $this->ObjItemTree->virtualFields['progress'] = "(SELECT COUNT(*) FROM `wb_obj_opt_relation` WHERE `wb_obj_opt_relation`.`type` = `ObjItemTree`.`id` AND `wb_obj_opt_relation`.`rel_id` = '".$this->Session->read('Auth.User.id')."')";
        
        if($item_id == 'active') $item_id = (!empty($this->cms['active_item']) ? $this->cms['active_item'] : null);
        if($item_id) $conditions = am($conditions, array('ObjItemTree.extra_3' => $item_id));
        
        $items = $this->ObjItemTree->TreeList($conditions);
        return $items;
    }
    
    public function get_into($item_id = null){
        $conditions = array();
        
        $this->ObjItemTree->virtualFields['progress'] = "(SELECT COUNT(*) FROM `wb_obj_opt_relation` WHERE `wb_obj_opt_relation`.`type` = `ObjItemTree`.`id` AND `wb_obj_opt_relation`.`rel_id` = '".$this->Session->read('Auth.User.id')."')";
        
        if($item_id == 'active') $item_id = (!empty($this->cms['active_item']) ? $this->cms['active_item'] : null);
        if($item_id) $conditions = am($conditions, array('ObjItemTree.parent_id' => $item_id));
        
        $items = $this->ObjItemTree->find('threaded', array('conditions' => $conditions, 'order' => array('ObjItemTree.lft' => 'ASC')));
        return $items;
    }
    
    public function set_progress($id = null, $item_id = null){
        $this->ObjItemTree->ObjOptRelation->insert_new(array('model' => 'Treecontent', 'type' => $id, 'foreign_key' => $item_id, 'rel_id' => $this->Session->read('Auth.User.id')));
        exit('OK');
    }
}

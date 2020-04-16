<?php
class SliderItemController extends SliderAppController {

    function admin_table_actions(){
        $this->ObjItemTree->table_actions($this->request->data);
        $this->Basic->back();
    }

    function admin_table_structure(){
        $this->ObjItemTree->table_structure($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Slider') . ' :: ' . ___('List'));
        
        $this->set('items', $this->ObjItemTree->find('all', array('order' => 'ObjItemTree.lft')));
    }
    
    public function admin_edit_slider($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Slider') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Slider') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }
    
    public function admin_edit($id = null){
        
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Item') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Item') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('panels', $this->ObjItemTree->TreeList(array('extra_1' => '1')));
    }

	function admin_delete($id = null){
	    if($this->ObjItemTree->fread('tpl_obj', $id) == '1'){
	       $this->Session->setFlash(___('Forbidden to remove template element (is possible to hide).'), 'flash');
           $this->Basic->back();
	    }
	    if($this->ObjItemTree->fread('extra_1', $id) == '1'){
	       foreach($this->ObjItemTree->find('list', array('conditions' => array('parent_id' => $id))) as $_id => $__id){
	           $this->ObjItemTree->delete($_id);
	       }
	    }
	    $this->ObjItemTree->delete($id);
        $this->Basic->back();
	}

    function admin_status($id = null){
        $this->ObjItemTree->toggle($id, 'status');
        $this->Basic->back();
    }

    public function get_list($base_id = null){
        $conditions = array();

        if($base_id > 0){
            $conditions = am($conditions, array('ObjItemTree.parent_id' => $base_id));
            
            $slider = $this->ObjItemTree->find('first', array('conditions' => array('ObjItemTree.id' => $base_id)));
        } else {
            $slider = $this->ObjItemTree->find('first', array('conditions' => array('ObjItemTree.code' => $base_id, 'ObjItemTree.tid' => Configure::read('Config.tid'))));
            
            $conditions = am($conditions, array('ObjItemTree.parent_id' => $slider['ObjItemTree']['id']));
        }
        
        $items = $this->ObjItemTree->find('all', array('conditions' => $conditions, 'order' => 'ObjItemTree.lft'));
        
        return array('items' => $items, 'slider' => $slider);
    }
}

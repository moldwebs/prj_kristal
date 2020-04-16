<?php
class BlockController extends BaseAppController {

    public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        Configure::write('Config.tid', 'cms_block');
    }

    function admin_table_actions(){
        $this->ObjItemTree->table_actions($this->request->data);
        $this->Basic->back();
    }

    function admin_table_structure(){
        $this->ObjItemTree->table_structure($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Blocks') . ' :: ' . ___('List'));
        
        $this->set('items', $this->ObjItemTree->find('all', array('conditions' => array('extra_1 <>' => '6'), 'order' => 'ObjItemTree.lft')));
    }
    
    public function admin_edit_panel($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Panels') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Panels') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }
    
    public function admin_edit($id = null){
        
        //$this->ObjItemTree->validate = array('parent_id' => 'alphaNumeric');
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Blocks') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Blocks') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('panels', $this->ObjItemTree->TreeList(array('extra_1' => '1')));
        
        //pr($this->ObjItemList->find('first', array('recursive' => 2)));
    }

    public function admin_edit_group($id = null){
        
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Group') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Group') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('panels', $this->ObjItemTree->TreeList(array('extra_1' => '1')));
    }

	function admin_delete($id = null){
	    if($this->ObjItemTree->fread('tpl_obj', $id) == '1'){
	       $this->Session->setFlash(___('Forbidden to remove template element (is possible to hide).'), 'flash');
           $this->Basic->back();
	    }
	    if($this->ObjItemTree->fread('extra_1', $id) == '2'){
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
}

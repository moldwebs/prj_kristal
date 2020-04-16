<?php
class GroupController extends BaseAppController {

    public $paginate = array(
        'ObjItemTree' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemTree.extra_3' => 'asc',
                'ObjItemTree.title' => 'asc'
            )
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();
        Configure::write('Config.tid', 'cms_block');

        Configure::write('top_buttons_add', array(
            'list' => array('title' => ___('Content') . ' :: ' . ___('List'), 'url' => array('action' => 'index')),
            'edit' => array('title' => ___('Content') . ' :: ' . ___('Create'), 'url' => array('action' => 'edit')),
        ));
        
        CmsNav::remove('base.children.blocks.buttons.create_panel');
        CmsNav::remove('base.children.blocks.buttons.create');
        CmsNav::remove('base.children.blocks.buttons.create_group');
        
        if(isset($this->request->params['admin'])){
            $this->ObjItemTree->Behaviors->attach('Scopefield', array('field' => 'parent_id', 'value' => $this->request->query['scopefield']));
            $this->ObjItemTree->Behaviors->detach('Tree');
        }
    }
        
    public function admin_table_actions(){
        $this->ObjItemTree->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Items') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ObjItemTree', $this->Basic->filters('ObjItemTree')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Items') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Items') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

	public function admin_delete($id = null){
	    $this->ObjItemTree->delete($id);
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjItemTree->toggle($id, 'status');
        $this->Basic->back();
    }
    
	public function admin_order($id = null, $order = null){
        $this->ObjItemTree->updateAll(array("ObjItemTree.lft" => sqls($order, true)), array("ObjItemTree.id" => $id));
        $this->Basic->back();
	}
}

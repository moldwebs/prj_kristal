<?php
class TypeController extends TypeAppController {

    public $paginate = array(
        'ObjOptType' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjOptType.created' => 'desc'
            )
        )
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function admin_table_actions(){
        $this->ObjOptType->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Types') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ObjOptType', $this->Basic->filters('ObjOptType')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjOptType, true);
        
        if($id){
            $this->set('page_title', ___('Types') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Types') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

	public function admin_order($id = null, $order = null){
        $this->ObjOptType->updateAll(array("ObjOptType.order_id" => sqls($order, true)), array("ObjOptType.id" => $id));
        $this->Basic->back();
	}
    
	public function admin_delete($id = null){
	    $this->ObjOptType->delete($id);
        $this->Basic->back();
	}

    public function get_list($base_id = null){
        return $this->ObjOptType->find('list');
    }
}

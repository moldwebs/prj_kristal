<?php
class DepositController extends CatalogAppController {

    public $paginate = array(
        'ObjItemList' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemList.title' => 'asc'
            )
        )
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        Configure::write('Config.tid', 'deposit');
    }
    
    public function admin_table_actions(){
        $this->ObjItemList->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Deposit') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemList, true);
        
        if($id){
            $this->set('page_title', ___('Deposit') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Deposit') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

	public function admin_order($id = null, $order = null){
        $this->ObjItemList->updateAll(array("ObjItemList.order_id" => sqls($order, true)), array("ObjItemList.id" => $id));
        $this->Basic->back();
	}
    
	public function admin_delete($id = null){
	    $this->ObjItemList->delete($id);
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjItemList->toggle($id, 'status');
        $this->Basic->back();
    }

    public function get_list($base_id = null){
        if($base_id > 0){
            return $this->ObjItemList->find('all', array('conditions' => array(), 'joins' => array(array('table' => $this->ObjItemList->useTable, 'alias' => 'ObjItemListFound', 'type' => 'INNER', 'conditions' => array('ObjItemListFound.extra_2 = ObjItemList.id', 'ObjItemListFound.status' => '1', 'ObjItemListFound.base_id' => $base_id))), 'tid' => 'deposit', 'order' => array('ObjItemList.title' => 'asc'), 'group' => 'ObjItemList.id'));
        } else {
            return $this->ObjItemList->find('all', array('conditions' => array(), 'joins' => array(array('table' => $this->ObjItemList->useTable, 'alias' => 'ObjItemListFound', 'type' => 'INNER', 'conditions' => array('ObjItemListFound.extra_2 = ObjItemList.id', 'ObjItemListFound.status' => '1'))), 'tid' => 'deposit', 'order' => array('ObjItemList.title' => 'asc'), 'group' => 'ObjItemList.id'));
        }
    }
}

<?php
class ShippingController extends ShopAppController {

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
        
        Configure::write('Config.tid', 'shipping');

    }
    
    public function admin_table_actions(){
        $this->ObjItemList->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Shipping') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemList, true);
        
        if($id){
            $this->set('page_title', ___('Shipping') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Shipping') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
        
        $this->set('zones', $this->ObjItemTree->TreeList(array('tid' => 'shipping_zone')));
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

    public function admin_settings(){
        $this->Basic->simple_add_settings(Configure::read('Config.tid'), $this->CmsSetting);
    }
}

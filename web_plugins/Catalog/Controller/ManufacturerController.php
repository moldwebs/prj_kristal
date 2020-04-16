<?php
class ManufacturerController extends CatalogAppController {

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
        
        Configure::write('Config.tid', 'manufacturer');
    }
    
    public function admin_table_actions(){
        $this->ObjItemList->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Manufacturer') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemList, true);
        
        if($id){
            $this->set('page_title', ___('Manufacturer') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Manufacturer') . ' :: ' . ___('Create'));
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
            return $this->ObjItemList->find('all', array('conditions' => array(), 'joins' => array(array('table' => $this->ObjItemList->useTable, 'alias' => 'ObjItemListFound', 'type' => 'INNER', 'conditions' => array('ObjItemListFound.extra_2 = ObjItemList.id', 'ObjItemListFound.status' => '1', 'ObjItemListFound.base_id' => $base_id))), 'tid' => 'manufacturer', 'order' => array('ObjItemList.title' => 'asc'), 'group' => 'ObjItemList.id'));
        } else {
            return $this->ObjItemList->find('all', array('conditions' => array(), 'joins' => array(array('table' => $this->ObjItemList->useTable, 'alias' => 'ObjItemListFound', 'type' => 'INNER', 'conditions' => array('ObjItemListFound.extra_2 = ObjItemList.id', 'ObjItemListFound.status' => '1'))), 'tid' => 'manufacturer', 'order' => array('ObjItemList.title' => 'asc'), 'group' => 'ObjItemList.id'));
        }
    }
    public function get_clist(){
        if($catalog_base_id = Configure::read('CMS.catalog_base_id') && 1==2){
            $conditions = array("ObjItemList.id IN (SELECT foreign_key FROM wb_obj_opt_attachment WHERE model = 'ObjItemList') AND ObjItemList.id IN (SELECT extra_2 FROM wb_obj_item_list WHERE base_id = '{$catalog_base_id}')");
        } else {
            $conditions = array("ObjItemList.id IN (SELECT foreign_key FROM wb_obj_opt_attachment WHERE model = 'ObjItemList') AND ObjItemList.id IN (SELECT extra_2 FROM wb_obj_item_list WHERE status = '1')");
        }
        return $this->ObjItemList->find('all', array('tid' => 'manufacturer', 'conditions' => $conditions, 'order' => (!empty($this->params['named']['mod_order']) ? array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)) : array('ObjItemList.title' => 'asc')), 'limit' => $this->params['named']['mod_limit']));
    }
}

<?php
class PromoController extends CatalogAppController {

    public $paginate = array(
        'ObjItemList' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemList.created' => 'desc'
            )
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();

        $this->set('bases', $this->ObjItemTree->TreeList());
        $this->set('manufacturers', $this->ObjItemList->find('list', array('tid' => 'manufacturer', 'order' => array('title' => 'asc'))));

        Configure::write('Config.tid', 'catalog_promo');

        $this->set('parents', $this->ObjItemList->find('list', array('tid' => 'catalog_promo', 'order' => array('title' => 'asc'))));
    }

    public function admin_table_actions(){
        $this->ObjItemList->table_actions($this->request->data);
        $this->Basic->back();
    }

    public function admin_index(){
        $this->set('page_title', ___('Promo') . ' :: ' . ___('List'));

        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
    }

    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemList, true);

        if($id){
            $this->set('page_title', ___('Promo') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Promo') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

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

}

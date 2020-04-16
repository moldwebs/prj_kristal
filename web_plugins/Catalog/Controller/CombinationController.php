<?php
class CombinationController extends CatalogAppController {

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
        
        if(isset($this->request->params['admin']) && !empty($this->request->query['scopefield'])) $this->ObjItemList->Behaviors->attach('Scopefield', array('field' => 'rel_id', 'value' => $this->request->query['scopefield']));
    
        Configure::write('top_buttons_replace', array(
            'back' => array('title' => ___('Back'), 'url' => $this->Session->read('TMP.scopefield_back')),
            'list' => array('title' => ___('Combinations') . ' :: ' . ___('List'), 'url' => array('action' => 'index')),
            'edit' => array('title' => ___('Combinations') . ' :: ' . ___('Create'), 'url' => array('action' => 'edit')),
        ));
        
        if(Configure::read('PLUGIN.Specification') == '1') $this->ObjItemList->Behaviors->load('Specification.Specification', array('admin' => '1'));
    }
    
    public function admin_table_actions(){
        $this->ObjItemList->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        
        $scope_data = $this->ObjItemListNull->find('first', array('tid' => false, 'conditions' => array('ObjItemList.id' => $_GET['scopefield'])));
        
        $this->set('page_title', $scope_data['ObjItemList']['title'] . ' :: ' .  ___('Combinations') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemList, true, 'Catalog.rel_id');
        
        if($id){
            $this->set('page_title', ___('Combinations') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Combinations') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('parent', $this->ObjItemListNull->find('first', array('tid' => false, 'conditions' => array('ObjItemList.id' => $this->request->query['scopefield']))));
        if($this->Session->read('Auth.User.role') == 'admin') $this->set('users', $this->ObjItemList->User->find('list', array('fields' => array('User.id', 'User.username'), 'order' => array('User.username' => 'asc'))));
    }

	public function admin_order($id = null, $order = null){
        $this->ObjItemList->updateAll(array("ObjItemList.order_id" => sqls($order, true)), array("ObjItemList.id" => $id));
        $this->Basic->back();
	}

	public function admin_quantity($id = null, $quantity = null){
        $this->ObjItemList->updateAll(array("ObjItemList.qnt" => sqls($quantity, true)), array("ObjItemList.id" => $id));
        $this->Basic->back();
	}

	public function admin_delete($id = null){
	    $rel_id = $this->ObjItemList->fread('rel_id', $id);
	    $this->ObjItemList->delete($id);
        $this->getEventManager()->dispatch(new CakeEvent('Catalog.delete', null, array('item_id' => $id)));
        $this->getEventManager()->dispatch(new CakeEvent('Catalog.rel_id', null, array('id' => $id, 'rel_id' => $rel_id)));
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjItemList->toggle($id, 'status');
        $this->getEventManager()->dispatch(new CakeEvent('Catalog.rel_id', null, array('id' => $id)));
        $this->Basic->back();
    }

   public function admin_set_price($id = null, $value = null){
        $this->ObjItemList->updateAll(
            array('ObjItemList.price' => sqls($value, true)),
            array("ObjItemList.id" => $id)
        );
        $this->getEventManager()->dispatch(new CakeEvent('Catalog.rel_id', null, array('id' => $id)));
        exit;
    }

   public function admin_set_currency($id = null, $value = null){
        $this->ObjItemList->updateAll(
            array('ObjItemList.currency' => sqls($value, true)),
            array("ObjItemList.id" => $id)
        );
        $this->getEventManager()->dispatch(new CakeEvent('Catalog.rel_id', null, array('id' => $id)));
        exit;
    }

    public function admin_rel_prices(){
        $rel_ids = $this->ObjItemListNull->find('list', array('tid' => false, 'fields' => array('ObjItemList.id', 'ObjItemList.rel_id'), 'conditions' => array(
            'ObjItemList.status' => '1', 
            'ObjItemList.price > 0', 
            'ObjItemList.rel_id > 0',
            //'ObjItemList.rel_id = 53974',
        ), 'group' => array('ObjItemList.rel_id')));
        foreach($rel_ids as $rel_id){
            $this->getEventManager()->dispatch(new CakeEvent('Catalog.rel_id', null, array('rel_id' => $rel_id)));
        }
        exit('OK:rel_prices');
    }

}

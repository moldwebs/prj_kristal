<?php
class CustomerController extends ShopAppController {

     public $uses = array('Users.User');

    public $paginate = array(
        'User' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'User.created' => 'desc'
            )
        ),
        'ModDiscount' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ModDiscount.created' => 'desc'
            )
        ),
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->set('discount_types', am($this->viewVars['currencies'], array('%' => '%')));
    }
    
    public function admin_table_actions(){
        $this->ModDiscount->table_actions($this->request->data);
        $this->Basic->back();
    }

	public function admin_customers(){
	   $this->set('page_title', ___('Customers') . ' :: ' . ___('List'));
       
       $this->set('items', $this->paginate('User', $this->Basic->filters('User')));
       
       $this->set('groups', $this->ModDiscount->find('list', array('conditions' => array('ModDiscount.type' => '0'))));
	}
    
    public function admin_customer_group($id = null, $group = null){
        if(!empty($id)){
            ClassRegistry::init('ObjOptRelation')->deleteAll(array('ObjOptRelation.model' => 'User', 'ObjOptRelation.foreign_key' => $id, 'ObjOptRelation.type' => 'customer'));
            if(!empty($group)){
                ClassRegistry::init('ObjOptRelation')->create();
                ClassRegistry::init('ObjOptRelation')->save(array('model' => 'User', 'foreign_key' => $id, 'type' => 'customer', 'rel_id' => $group));
            }
        }
        $this->Basic->back();
    }

	public function admin_index(){
	   $this->set('page_title', ___('Customer Group') . ' :: ' . ___('List'));
       
       $this->set('items', $this->paginate('ModDiscount', array('ModDiscount.type' => '0')));
	}

	public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ModDiscount, true);
        
        if($id){
            $this->set('page_title', ___('Customer Group') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Customer Group') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }
    
	public function admin_delete($id = null){
	    if($this->ModDiscount->delete($id)){
	       ClassRegistry::init('ObjOptRelation')->deleteAll(array('ObjOptRelation.model' => 'User', 'ObjOptRelation.rel_id' => $id, 'ObjOptRelation.type' => 'customer'));
	    }
        $this->Basic->back();
	}
    
}

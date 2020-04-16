<?php
class BonusesController extends AppController {

    public $uses = array('Users.User');

    public $paginate = array(
        'User' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'User.created' => 'desc'
            )
        ),
        'ObjItemList' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemList.created' => 'desc'
            ),
            'conditions' => array('ObjItemList.rel_id IS NULL')
        ),
        'ObjItemTree' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemTree.lft' => 'asc'
            )
        )
    );
    

    public function beforeFilter() {
        Configure::write('Config.tid', 'catalog');
        parent::beforeFilter();
    }
    
    public function admin_customers(){
        $this->set('page_title', ___('Customers'));
        
        $this->User->virtualFields['bonus_active'] = "SELECT (SUM(extra_6) - ifnull((SELECT SUM(extra_6) FROM wb_extra_data WHERE type = 'bonuses' AND extra_1 = User.id AND extra_3 = '2' AND extra_4 = '1'),0)) FROM wb_extra_data WHERE type = 'bonuses' AND extra_1 = User.id AND extra_3 = '1' AND extra_4 = '1'";
        $this->User->virtualFields['bonus_used'] = "SELECT SUM(extra_6) FROM wb_extra_data WHERE type = 'bonuses' AND extra_1 = User.id AND extra_3 = '2' AND extra_4 = '1'";
        
        $this->set('items', $this->paginate('User', $this->Basic->filters('User')));
    }
 
    public function admin_bases(){
        $this->set('page_title', ___('Categories'));
        
        $this->set('items', $this->paginate('ObjItemTree', $this->Basic->filters('ObjItemTree')));
        
        $this->set('bases', $this->ObjItemTree->TreeList());
    }
 
    public function admin_items(){
        $this->set('page_title', ___('Items'));
        
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
        
        $this->set('bases', $this->ObjItemTree->TreeList());
    }

	public function admin_set_bonuses($model = null, $id = null, $value = null){
        $this->ObjItemList->ObjOptRelation->update_insert(array('value' => $value), array('model' => $model, 'type' => 'bonuses', 'foreign_key' => $id), true);
        $this->Basic->back();
	} 
    
	public function admin_set_bonuses_prc($model = null, $id = null, $value = null){
        $this->ObjItemList->ObjOptRelation->update_insert(array('value' => $value), array('model' => $model, 'type' => 'bonuses_prc', 'foreign_key' => $id), true);
        $this->Basic->back();
	} 
    
}

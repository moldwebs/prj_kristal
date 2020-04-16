<?php
class DiscountController extends ShopAppController {

    public $paginate = array(
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
        $this->set('use_types', array('1' => ___('Single-use'), '2' => ___('Reusable')));
    }
    
    public function admin_table_actions(){
        $this->ModDiscount->table_actions($this->request->data);
        $this->Basic->back();
    }


	function admin_index_1(){
	   $this->set('page_title', ___('Discount') . ' :: ' . ___('List'));
       
       $this->set('items', $this->paginate('ModDiscount', array('ModDiscount.type' => '1')));
	}
    
	function admin_index_2(){
	   $this->set('page_title', ___('Discount') . ' :: ' . ___('List'));
       
       $this->set('items', $this->paginate('ModDiscount', array('ModDiscount.type' => '2')));
	}

	function admin_index_3(){
	   $this->set('page_title', ___('Discount') . ' :: ' . ___('List'));
       
       $this->set('items', $this->paginate('ModDiscount', array('ModDiscount.type' => '3')));
	}

	function admin_edit_1($id = null){
        $this->Basic->save_load($id, $this->ModDiscount, true);
        
        if($id){
            $this->set('page_title', ___('Discount') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Discount') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

	function admin_edit_2($id = null){
        $this->Basic->save_load($id, $this->ModDiscount, true);
        
        if($id){
            $this->set('page_title', ___('Discount') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Discount') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

	function admin_edit_3($id = null){
        $this->Basic->save_load($id, $this->ModDiscount, true);
        
        if($id){
            $this->set('page_title', ___('Discount') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Discount') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }
    
	public function admin_delete($id = null){
	    $this->ModDiscount->delete($id);
        $this->Basic->back();
	}
    
}

<?php
class NewsletterController extends NewsletterAppController {

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
    }
    
    public function admin_table_actions(){
        $this->ObjItemList->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Newsletter') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
    }

	public function admin_delete($id = null){
	    $this->ObjItemList->delete($id);
        $this->Basic->back();
	}

    public function admin_export(){
        $emails = $this->ObjItemList->find('list', array('fields' => array('ObjItemList.id', 'ObjItemList.title'), 'order' => array('ObjItemList.title' => 'asc')));
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="emails.csv"');
        echo implode(';', $emails);
        exit;
    }

    public function add(){
        if(!empty($this->data['email'])){
            if(!($this->ObjItemList->find('count', array('tid' => 'newsletter', 'st_cond' => '1', 'conditions' => array('ObjItemList.title' => htmlspecialchars($this->data['email'])))) > 0)){
                $this->ObjItemList->create();
                $this->ObjItemList->save(
                    array(
                        'title' => htmlspecialchars($this->data['email']),
                        'tid' => 'newsletter',
                    )
                );
                $this->Session->setFlash(___('Your email was save successfull.'), 'flash');
            } else {
                $this->Session->setFlash(___('Your email is present in newsletters.'), 'flash');
            }
        }
        $this->redirect($this->request->referer(true));
    }

}

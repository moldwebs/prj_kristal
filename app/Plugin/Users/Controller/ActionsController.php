<?php
class ActionsController extends AppController {

    public $uses = array('ObjItemActions');

    public $paginate = array(
        'ObjItemActions' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemActions.created' => 'desc'
            )
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();
    }


    public function admin_index(){
        $this->set('page_title', ___('Actions') . ' :: ' . ___('List'));

        $this->set('items', $this->paginate('ObjItemActions', $this->Basic->filters('ObjItemActions')));

        $this->set('users', ClassRegistry::init('Users.User')->find('list', array('fields' => array('id', 'username'), 'conditions' => array("role <> 'user'"), 'order' => array('username' => 'asc'))));
    }

}

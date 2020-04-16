<?php
class ReviewController extends ReviewAppController {
    
    public $uses = array('Review.ObjOptReview');

    public $paginate = array(
        'ObjOptReview' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjOptReview.created' => 'desc'
            )
        )
    );

    
    public function beforeFilter() {
        parent::beforeFilter();

        //$this->set('objects', $this->ObjItemList->find('list'));
    }
    
    public function admin_table_actions(){
        $this->ObjOptReview->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Reviews') . ' :: ' . ___('List'));

        $this->ObjOptReview->bindModel(
            array(
                'belongsTo' => array(
                    'ObjItemList' => array(
                        'className' => 'ObjItemListNull',
                        'foreignKey' => 'item_id',
                    ),
                ),
            ), false
        );
        
        $this->set('items', $this->paginate('ObjOptReview', $this->Basic->filters('ObjOptReview')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjOptReview, true);
        
        $this->set('page_title', ___('Reviews') . ' :: ' . ___('Edit'));
        $this->request->edit_mode = 'modify';
        
        $this->set('rating_types', $this->ObjItemList->find('list', array('tid' => Configure::read('Config.tid') . '_review')));
        $rating = array();
        for($i = 5; $i >= 0.1; $i = $i - 0.1){
            $rating["{$i}"] = "{$i}";
        }
        $this->set('rating', $rating);
    }

	public function admin_delete($id = null){
	    $this->ObjOptReview->delete($id);
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjOptReview->toggle($id, 'status');
        $this->getEventManager()->dispatch(new CakeEvent('Review.count', null, array('id' => $id, 'tid' => Configure::read('Config.tid'), 'item_id' => $this->ObjOptReview->fread('item_id', $id))));
        $this->Basic->back();
    }

    public function add($item_id = null){
        
        if(empty($item_id)) $item_id = $this->data['item_id'];
        
        if(!empty($this->data)){
            if(!$this->Session->check('Auth.User.id')) if($_SESSION['captcha'] != $this->data['captcha']) exit(___('Incorrect Security Code'));

            if(empty($this->data['comment'])) exit(___('Invalid comment'));
            if(count($this->data['attachments_exist']) > 5) exit(___('Maximum %d image(s)', 5));
            
            if($this->ObjOptReview->find('count', array('conditions' => array('ObjOptReview.comment' => htmlspecialchars($this->data['comment']), 'ObjOptReview.item_id' => htmlspecialchars($item_id)))) >= 1) exit(___('This review exist'));
            if($this->Session->check('Auth.User.id')) if($this->ObjOptReview->find('count', array('conditions' => array('ObjOptReview.userid' => $this->Session->read('Auth.User.id'), 'DATE_FORMAT(`ObjOptReview`.`created`, \'%Y-%m-%d-%h\')' => date("Y-m-d-H")))) >= 10) exit(___('Maximum %d review(s) per hour', 10));
            
            if($this->data['ajx_validate'] != '1'){
                if(!$this->Session->check('Auth.User.id')) if($_SESSION['captcha'] != $this->data['captcha'] || empty($_SESSION['captcha'])) exit(___('Incorrect Security Code'));
                
                if((count($this->data['attachments'])-1) > 5) exit(___('Maximum %d image(s)', 5));
                
                $user_data = array('username' => $this->data['username'], 'usermail' => $this->data['usermail']);
                if($this->Session->check('Auth.User.id')) $user_data = array('username' => $this->Session->read('Auth.User.username'), 'usermail' => $this->Session->read('Auth.User.usermail'));
                
                $this->ObjOptReview->create();
                $this->ObjOptReview->save(
                    array(
                        'userid' => $this->Session->read('Auth.User.id'),
                        'item_id' => htmlspecialchars($item_id),
                        'comment' => htmlspecialchars($this->data['comment']),
                        'username' => htmlspecialchars($user_data['username']),
                        'usermail' => htmlspecialchars($user_data['usermail']),
                        'userip' => $this->request->clientIp(),
                        'usersession' => $this->Session->id(),
                        'status' => (Configure::read('CMS.settings.' . Configure::read('Config.tid') . '.comment_approve') == '1' ? '0' : '1'),
                        'attachments' => $this->data['attachments'],
                        'data' => $this->data['data']
                    )
                );
                
               
                $this->getEventManager()->dispatch(new CakeEvent('Review.add', $this, array('id' => $this->ObjOptReview->getInsertID(), 'tid' => Configure::read('Config.tid'), 'item_id' => htmlspecialchars($item_id))));
                $this->getEventManager()->dispatch(new CakeEvent('Review.count', null, array('id' => $this->ObjOptReview->getInsertID(), 'tid' => Configure::read('Config.tid'), 'item_id' => htmlspecialchars($item_id))));
                $this->Session->setFlash(___('Your review was save successfull.'), 'flash');
                unset($_SESSION['captcha']);
                $this->redirect($this->request->referer(true));
            }
            exit('OK');
        }
    }

    public function get_info($item_id = null){
        $info = $this->ObjOptReview->query("SELECT SUM(rate) AS v1, COUNT(*) AS v2 FROM wb_obj_opt_comment WHERE item_id = '".sqls($item_id)."' AND status = '1'");
        return array('qnt' => $info[0][0]['v2'], 'rate' => round($info[0][0]['v1']/$info[0][0]['v2'], 1));
    }

    public function get_full_list($item_id = null){
        $items = $this->ObjOptReview->find('all', array('conditions' => array('ObjOptReview.item_id' => $item_id), 'order' => array('ObjOptReview.created' => 'desc')));
        
        $return = array();
        foreach($items as $item){
            $return['items'][] = $item;
            $return['rate'] += $item['ObjOptReview']['rate'];
            if(!empty($item['ObjOptReview']['data']['rating'])) foreach($item['ObjOptReview']['data']['rating'] as $key => $val){
                $return['ratign'][$key] += $val;
            }
        }
        if(!empty($return['ratign'])) foreach($return['ratign'] as $key => $val){
            $return['ratign'][$key] = round($val / count($return['items']), 1);
        }
        
        $return['rate'] = round($return['rate'] / count($return['items']), 1);
        
        $return['rating_types'] = $this->ObjItemList->find('list', array('tid' => Configure::read('Config.tid') . '_review'));
        
        return $return;
    }

    public function get_list($item_id = null, $limit = 3){
        if($item_id == 'active' && empty($this->cms['active_item'])) return false;
        
        if($item_id == 'active'){
            $items = $this->ObjOptReview->find('all', array('conditions' => array('ObjOptReview.item_id' => $this->cms['active_item']), 'order' => array('ObjOptReview.created' => 'desc')));
        } else if($item_id > 0){
            $items = $this->ObjOptReview->find('all', array('conditions' => array('ObjOptReview.item_id' => $item_id), 'order' => array('ObjOptReview.created' => 'desc'), 'limit' => $limit));
        } else {
            $items = $this->ObjOptReview->find('all', array('conditions' => array('ObjOptReview.parent_id IS NULL'), 'order' => array('ObjOptReview.' . str_replace('ObjItemList.', '', ws_expl(':', $this->params['named']['mod_order'], 0)) => str_replace('ObjItemList.', '', ws_expl(':', $this->params['named']['mod_order'], 1))), 'limit' => $this->params['named']['mod_limit']));
        }
        
        return $items;
    }
}

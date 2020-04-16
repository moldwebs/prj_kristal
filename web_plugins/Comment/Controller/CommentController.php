<?php
class CommentController extends CommentAppController {
    
    public $uses = array('Comment.ObjOptComment');

    public $paginate = array(
        'ObjOptComment' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjOptComment.created' => 'desc'
            )
        )
    );

    public function admin_table_actions(){
        $this->ObjOptComment->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Comments') . ' :: ' . ___('List'));

        $this->ObjOptComment->bindModel(
            array(
                'belongsTo' => array(
                    'ObjItemList' => array(
                        'className' => 'ObjItemListNull',
                        'foreignKey' => 'item_id',
                    ),
                    'ObjItemTree' => array(
                        'className' => 'ObjItemTreeNull',
                        'foreignKey' => 'item_id',
                    ),
                ),
                'hasMany' => array(
                    'ObjOptCommentRespond' => array(
                        'className' => 'ObjOptComment',
                        'foreignKey' => 'parent_id',
                        'conditions' => array('ObjOptCommentRespond.is_resp' => '1'),
                    )
                ),
            ), false
        );
        
        $this->set('items', $this->paginate('ObjOptComment', am($this->Basic->filters('ObjOptComment'), array('ObjOptComment.is_resp <> 1'))));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjOptComment, true);
        
        $this->set('page_title', ___('Comments') . ' :: ' . ___('Edit'));
        $this->request->edit_mode = 'modify';
    }

	public function admin_delete($id = null){
	    $this->ObjOptComment->delete($id);
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjOptComment->toggle($id, 'status');
        $this->Basic->back();
    }

    public function admin_respond($id = null){
        if(!empty($this->data['respond']) && !empty($id)){
                $this->ObjOptComment->updateAll(array("ObjOptComment.status" => '1'), array("ObjOptComment.id" => $id));
                $this->ObjOptComment->deleteAll(array("ObjOptComment.parent_id" => $id, 'ObjOptComment.is_resp' => '1'));
                $item_id = $this->ObjOptComment->fread('item_id', $id);
                $tid = $this->ObjOptComment->fread('tid', $id);
                $this->ObjOptComment->create();
                $this->ObjOptComment->save(
                    array(
                        'userid' => $this->Session->read('Auth.User.id'),
                        'item_id' => $item_id,
                        'tid' => $tid,
                        'comment' => $this->data['respond'],
                        'status' => '1',
                        'parent_id' => $id,
                        'is_resp' => '1'
                    )
                );
        }
        exit('OK');
    }

    public function admin_add(){
        if(!empty($this->data)){
            $this->ObjOptComment->create();
            $this->ObjOptComment->save(
                array(
                    'userid' => $this->Session->read('Auth.User.id'),
                    'item_id' => htmlspecialchars($this->data['item_id']),
                    'comment' => htmlspecialchars($this->data['comment']),
                    'username' => htmlspecialchars($this->data['username']),
                    'usermail' => htmlspecialchars($this->data['usermail']),
                    'resp_mail' => htmlspecialchars($this->data['resp_mail']),
                    'userip' => $this->request->clientIp(),
                    'usersession' => $this->Session->id(),
                    'status' => '1',
                    'attachments' => $this->data['attachments'],
                )
            );
            $this->getEventManager()->dispatch(new CakeEvent('Comment.add', $this, array('id' => $this->ObjOptComment->getInsertID())));
            $this->redirect($this->request->referer(true));
        }
    }

    public function add(){
        if(!empty($this->data)){
            if(!$this->Session->check('Auth.User.id')) if($_SESSION['captcha'] != $this->data['captcha']) exit(___('Incorrect Security Code'));

            if(empty($this->data['comment'])) exit(___('Invalid comment'));
            
            if($this->data['ajx_validate'] != '1'){
                if(!$this->Session->check('Auth.User.id')) if($_SESSION['captcha'] != $this->data['captcha'] || empty($_SESSION['captcha'])) exit(___('Incorrect Security Code'));
                
                $this->ObjOptComment->create();
                $this->ObjOptComment->save(
                    array(
                        'userid' => $this->Session->read('Auth.User.id'),
                        'item_id' => htmlspecialchars($this->data['item_id']),
                        'comment' => htmlspecialchars($this->data['comment']),
                        'username' => htmlspecialchars($this->data['username']),
                        'usermail' => htmlspecialchars($this->data['usermail']),
                        'resp_mail' => htmlspecialchars($this->data['resp_mail']),
                        'userip' => $this->request->clientIp(),
                        'usersession' => $this->Session->id(),
                        'status' => (Configure::read('CMS.settings.' . Configure::read('Config.tid') . '.comment_approve') == '1' ? '0' : '1'),
                        'attachments' => $this->data['attachments'],
                    )
                );
                $this->getEventManager()->dispatch(new CakeEvent('Comment.add', $this, array('id' => $this->ObjOptComment->getInsertID())));
                $this->Session->setFlash(___('Your message was save successfull.'), 'flash');
                unset($_SESSION['captcha']);
                $this->redirect($this->request->referer(true));
            }
            exit('OK');
        }
    }

    public function view($id = null){
        $comment = $this->ObjOptComment->find('first', array('conditions' => array('ObjOptComment.id' => $id, 'ObjOptComment.is_resp <> 1')));
        $response = $this->ObjOptComment->find('first', array('conditions' => array('ObjOptComment.parent_id' => $id, 'ObjOptComment.is_resp = 1')));

        $this->cms['active_item'] = $comment['ObjOptComment']['item_id'];

        $this->set('comment', $comment);
        $this->set('response', $response);
    }


    public function get_list($item_id = null){
        if($item_id == 'active' && empty($this->cms['active_item'])) return false;
        
        if($item_id == 'active'){
            $items = $this->ObjOptComment->find('threaded', array('conditions' => array('ObjOptComment.item_id' => $this->cms['active_item']), 'order' => array('ObjOptComment.lft' => 'ASC')));
        } else {
            if($item_id > 0){
                $items = $this->ObjOptComment->find('all', array('conditions' => array('ObjOptComment.parent_id IS NULL', 'ObjOptComment.item_id' => $item_id), 'order' => array('ObjOptComment.' . str_replace('ObjItemList.', '', ws_expl(':', $this->params['named']['mod_order'], 0)) => str_replace('ObjItemList.', '', ws_expl(':', $this->params['named']['mod_order'], 1))), 'limit' => $this->params['named']['mod_limit']));
            } else {
                $items = $this->ObjOptComment->find('all', array('conditions' => array('ObjOptComment.parent_id IS NULL'), 'order' => array('ObjOptComment.' . str_replace('ObjItemList.', '', ws_expl(':', $this->params['named']['mod_order'], 0)) => str_replace('ObjItemList.', '', ws_expl(':', $this->params['named']['mod_order'], 1))), 'limit' => $this->params['named']['mod_limit']));
            }
            
        }
        
        return $items;
    }
}

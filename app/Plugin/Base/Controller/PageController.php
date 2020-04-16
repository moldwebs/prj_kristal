<?php
class PageController extends BaseAppController {

    public $uses = array();

    public $paginate = array(
        'ObjItemTree' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemTree.lft' => 'asc',
                'ObjItemTree.extra_3' => 'asc',
                'ObjItemTree.created' => 'desc',
                'ObjItemTree.title' => 'asc'
            )
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();
        Configure::write('Config.tid', 'cms_link');
    }
    
    public function admin_index(){
        
    }

    public function admin_deny(){
        if($this->RequestHandler->isAjax()){
            header("HTTP/1.0 404 Not Found");  
            exit(___('You not have permissions to access this page.'));
        }
    }

    public function index(){
        $data = Configure::read('CMS.settings.base');
        $item = array('title' => $data['title'], 'body' => $data['body'], 'meta_title' => $data['meta_title'], 'meta_desc' => $data['meta_desc'], 'meta_keyw' => $data['meta_keyw']);
        $this->set('item', $item);
        
        //$this->Basic->template($item);
    }

    public function view($id = null, $layout = 'default', $tpl_type = ''){
        if($id > 0){
            $data = $this->ObjItemTree->findById($id);
            if($data['ObjItemTree']['extra_1'] == '5'){
                $this->set('base', $data);
                $this->request->query['hfltr_eq__parent_id'] = $id;
                $items = $this->paginate('ObjItemTree', $this->Basic->filters('ObjItemTree'));
                foreach($items as $key => $val) $items[$key]['ObjItemList'] = $val['ObjItemTree'];
                $this->set('items', $items);
                $this->Basic->template($data['ObjItemTree']);
                $this->render('list_index');
            } else if($data['ObjItemTree']['extra_1'] == '6'){
                $data['ObjItemList'] = $data['ObjItemTree'];
                $this->set('item', $data);
                $this->Basic->template($data['ObjItemTree']);
                $this->render('list_view');
            } else {
                //$item = array('title' => $data['ObjItemTree']['title'], 'body' => $data['ObjItemTree']['body'], 'data' => $data);
                //$this->set('item', am($data, array('title' => $data['ObjItemTree']['title'], 'body' => $data['ObjItemTree']['body'])));
                $data['ObjItemList'] = $data['ObjItemTree'];
                $this->set('item', $data);
                $this->Basic->template($data['ObjItemTree']);
            }
        } else {
            if(!empty($tpl_type)) $this->set('tpl_type', $tpl_type);
            if($layout == 'none') $this->layout = false; else if($layout != 'default') $this->layout = $layout;
            $this->render($id);
        }
    }

}

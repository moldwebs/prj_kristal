<?php
class SearchController extends BaseAppController {

    public $uses = array('ObjOptSearch');

    public $paginate = array(
        'ObjItemList' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemList.created' => 'desc'
            ),
            'tid' => false
        ),
        'ObjOptSearch' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjOptSearch.created' => 'desc'
            ),
            'group' => array(
                'ObjOptSearch.model', 
                'ObjOptSearch.foreign_key'
            ),
            'tid' => false,
            'st_cond' => '1'
        )
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
        Configure::write('Config.tid', 'base');
    }

    public function ___index(){
        $this->Basic->template(array('title' => ___('Search'), 'alias' => $this->here));
        
        if(strlen(trim($this->params->query['search'])) > 2){
            $conditions = array('OR' => array("ObjItemList.title LIKE '%".sqls($this->params->query['search'])."%'", "ObjItemList.id IN (SELECT foreign_key FROM wb_obj_opt_field WHERE model = 'ObjItemList' AND field = 'body' AND value LIKE '%".sqls($this->params->query['search'])."%')"));
            $this->set('items', $this->paginate('ObjItemList', $conditions));
        } else {
            $this->redirect($this->referer());
        }
    }

    public function index(){
        $this->Basic->template(array('title' => ___('Search'), 'alias' => $this->here));
        
        if(strlen(trim($this->params->query['search'])) > 2){
            $conditions = array("`ObjOptSearch`.`value` LIKE '%".sqls($this->params->query['search'])."%'", "`ObjOptSearch`.`foreign_key` IN (SELECT foreign_key FROM wb_cms_alias)");
            $ids = $this->paginate('ObjOptSearch', $conditions);
            
            $to_load = array();
            foreach($ids as $id){
                $to_load[$id['ObjOptSearch']['model']][] = $id['ObjOptSearch']['foreign_key'];
            }
            
            $loaded = array();
            foreach($to_load as $model => $_ids){
                $loaded[$model] = $this->{$model}->find('allindex', array('tid' => false, 'conditions' => array("{$model}.id" => $_ids)));
            }
            
            $items = array();
            foreach($ids as $id){
                $data = $loaded[$id['ObjOptSearch']['model']][$id['ObjOptSearch']['foreign_key']];
                $data['ObjItemList'] = $data[$id['ObjOptSearch']['model']];
                $items[] = $data;
            }
            
            $this->set('items', $items);
        } else {
            $this->redirect($this->referer());
        }
        $this->render('index');
    }
    
    public function full(){
        $this->Basic->template(array('title' => ___('Search'), 'alias' => $this->here));
        
        if(strlen(trim($this->params->query['search'])) > 2){
            $conditions = array("MATCH(`ObjOptSearch`.`value`) AGAINST('".sqls($this->params->query['search'])."')");
            $ids = $this->paginate('ObjOptSearch', $conditions);
            
            $to_load = array();
            foreach($ids as $id){
                $to_load[$id['ObjOptSearch']['model']][] = $id['ObjOptSearch']['foreign_key'];
            }
            
            $loaded = array();
            foreach($to_load as $model => $_ids){
                $loaded[$model] = $this->{$model}->find('allindex', array('tid' => false, 'conditions' => array("{$model}.id" => $_ids)));
            }
            
            $items = array();
            foreach($ids as $id){
                $data = $loaded[$id['ObjOptSearch']['model']][$id['ObjOptSearch']['foreign_key']];
                $data['ObjItemList'] = $data[$id['ObjOptSearch']['model']];
                $items[] = $data;
            }
            
            $this->set('items', $items);
        } else {
            $this->redirect($this->referer());
        }
        $this->render('index');
    }
    
    public function generate(){
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        
        $this->ObjItemList->searchgenerate();
        $this->ObjItemTree->searchgenerate();
        exit('OK');
    }

}

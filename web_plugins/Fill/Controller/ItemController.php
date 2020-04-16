<?php
class ItemController extends FillAppController {

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
        $this->set('page_title', ___('Items') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
        
        $this->set('bases', $this->ObjItemTree->TreeList());
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemList, true);
        
        if($id){
            $this->set('page_title', ___('Items') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Items') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('bases', $this->ObjItemTree->TreeList());
        //if($this->Session->read('Auth.User.role') == 'admin') $this->set('users', $this->ObjItemList->User->find('list', array('fields' => array('User.id', 'User.username'), 'order' => array('User.username' => 'asc'))));
    }

	public function admin_order($id = null, $order = null){
        $this->ObjItemList->updateAll(array("ObjItemList.order_id" => sqls($order, true)), array("ObjItemList.id" => $id));
        $this->Basic->back();
	}

	public function admin_delete($id = null){
	    $this->ObjItemList->delete($id);
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjItemList->toggle($id, 'status');
        $this->Basic->back();
    }
    
    public function index($base_id = null){
        if($base_id > 0){
            $this->cms['active_base'] = $base_id;
            $childs = $this->ObjItemList->ObjItemTree->children($base_id);
            $this->request->query['hfltr_eqorrel__base_id'] = (!empty($childs) ? am(array($base_id), Set::extract('/ObjItemTree/id', $childs)) : $base_id);

            $parents = $this->ObjItemList->ObjItemTree->getPath($base_id, null, 1);
            if(!empty($parents)) foreach($parents as $parent){
                $this->cms['breadcrumbs'][ws_url($parent['ObjItemTree']['alias'])] = $parent['ObjItemTree']['title'];
            }
            $this->set('base', $this->Basic->load($base_id, $this->ObjItemList->ObjItemTree));
        }
        return $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
    }

    public function view($id = null, $redirect = false){
        $this->cms['active_item'] = $id;
        
        $item = $this->Basic->load($id, $this->ObjItemList);
        $this->set('item', $item);
        
        $this->cms['active_base'] = $item['ObjItemList']['base_id'];
        $parents = $this->ObjItemList->ObjItemTree->getPath($this->cms['active_base'], null, 1);
        if(!empty($parents)) foreach($parents as $parent){
            array_insert($this->cms['breadcrumbs'], count($this->cms['breadcrumbs']) - 1, array(ws_url($parent['ObjItemTree']['alias']) => $parent['ObjItemTree']['title'])); 
        }
        
        $this->set('relbases', $this->ObjItemTree->find('list', array('conditions' => array('ObjItemTree.id' => $item['Relation']['base_id']))));
        
        if($redirect) $this->redirect($item['ObjItemList']['code']);
    }

    public function get_list($base_id = null){
        if($base_id == 'active' && empty($this->cms['active_base'])) return false;
        if($base_id == 'similar' && empty($this->cms['active_item'])) return false;
        if($base_id == 'category' && empty($this->cms['active_item'])) return false;
        
        $conditions = array();
        
        if(!empty($this->params['named']['mod_type'])) $conditions = am(array("(ObjItemList.extra_1 IN (".sqlimplode(',', $this->params['named']['mod_type']).") OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'extra_1' AND `rel_id` IN (".sqlimplode(',', $this->params['named']['mod_type']).")))"), $conditions);

        if($base_id == 'group'){
            $items = array();
            foreach($this->params['named']['mod_group_bases'] as $_base_id){
                $_conditions = am($conditions, array("(ObjItemList.base_id IN (".sqlimplode(',', (is_array($_base_id) ? $_base_id : $this->ObjItemList->ObjItemTree->childrens($_base_id, 'id'))).") OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', (is_array($_base_id) ? $_base_id : $this->ObjItemList->ObjItemTree->childrens($_base_id, 'id'))).")))"));
                $_item = $this->ObjItemList->find('first', array('conditions' => $_conditions, 'order' => (!empty($this->params['named']['mod_order']) ? array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)) : array('ObjItemList.title' => 'asc')), 'limit' => $this->params['named']['mod_limit']));
                if(!empty($_item)) $items[] = $_item;
            }
        } else if($base_id == 'similar'){
            $items = array();
            $item = $this->ObjItemList->findById($this->cms['active_item']);
            if(!empty($item['Relation']['tags'])){
                $items = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.id <>' => $this->cms['active_item'], "ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'tags' AND `rel_id` IN (".sqlimplode(',', array_keys($item['Relation']['tags']))."))"), 'order' => array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)), 'limit' => $this->params['named']['mod_limit']));
            }
            if(count($items) < $this->params['named']['mod_limit'] && !empty($item['ObjItemList']['base_id'])){
                $_items = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.id <>' => $this->cms['active_item'], "(ObjItemList.base_id IN ({$item['ObjItemList']['base_id']}) OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'base_id' AND `rel_id` IN ({$item['ObjItemList']['base_id']})))"), 'order' => array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)), 'limit' => ($this->params['named']['mod_limit'] - count($items))));
                $items = am($items, $_items);
            }
        } else if($base_id == 'related'){
            $items = array();
            if(count($items) < $this->params['named']['mod_limit'] && !empty($item['ObjItemList']['base_id'])){
                $base = $this->ObjItemList->ObjItemTree->findById($item['ObjItemList']['base_id']);
                if(!empty($base['Relation']['base_id'])){
                    $_items = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.id NOT IN (' . sqlimplode(',', (!empty($items) ? Set::extract('/ObjItemList/id', $items) : array('0'))) . ')', 'ObjItemList.id <>' => $this->cms['active_item'], "(ObjItemList.base_id IN (".sqlimplode(',', $base['Relation']['base_id']).") OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', $base['Relation']['base_id']).")))"), 'order' => array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)), 'limit' => ($this->params['named']['mod_limit'] - count($items))));
                    $items = am($items, $_items);
                }
            }
        } else {
            if($base_id == 'active') $base_id = (!empty($this->cms['active_base']) ? $this->cms['active_base'] : null);
            if($base_id == 'category'){
                $conditions = am($conditions, array('ObjItemList.id <>' => $this->cms['active_item'], 'ObjItemList.base_id' => $this->ObjItemList->fread('base_id', $this->cms['active_item'])));
            } else {
                //if($base_id) $conditions = am($conditions, array('ObjItemList.base_id' => (is_array($base_id) ? $base_id : $this->ObjItemList->ObjItemTree->childrens($base_id, 'id'))));
                if($base_id) $conditions = am($conditions, array('ObjItemList.id <>' => $this->cms['active_item'], "(ObjItemList.base_id IN (".sqlimplode(',', (is_array($base_id) ? $base_id : $this->ObjItemList->ObjItemTree->childrens($base_id, 'id'))).") OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', (is_array($base_id) ? $base_id : $this->ObjItemList->ObjItemTree->childrens($base_id, 'id'))).")))"));
            }
            
            $items = $this->ObjItemList->find('all', array('conditions' => $conditions, 'order' => (!empty($this->params['named']['mod_order']) ? array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)) : array('ObjItemList.title' => 'asc')), 'limit' => $this->params['named']['mod_limit']));
        }
        
        return $items;
    }
    
    public function get_tabs($base_id = null){
        $items = array();
        $items['popular'] = $this->ObjItemList->find('all', array('order' => array('ObjItemList.qnt' => 'desc'), 'limit' => $this->params['named']['mod_limit']));
        $items['latest'] = $this->ObjItemList->find('all', array('order' => array('ObjItemList.created' => 'desc'), 'limit' => $this->params['named']['mod_limit']));
        $items['comment'] = $this->ObjItemList->find('all', array('order' => array('ObjItemList.comment_count' => 'desc'), 'limit' => $this->params['named']['mod_limit']));
        return $items;
    }

    function rate($id){
        $data = $this->ObjItemList->fread('qnt', $id);
        if($this->Cookie->read("ObjItemListRate.{$id}") == '1') exit($data);
        $this->ObjItemList->updateAll(
            array("ObjItemList.qnt" => sqls($data+1)),
            array("ObjItemList.id" => $id)
        );
        $this->Cookie->write("ObjItemListRate.{$id}", '1');
        echo $data+1;
        exit;
    }

    public function tag($tag_id = null){
        if($tag_id > 0){
            $this->request->query['hfltr_eqrel__tags'] = $tag_id;
        }
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
        $this->render('index');
    }
    
    public function get_tags($search = null){
        $conditions = array();
        if(!empty($search)) $conditions = array("ObjOptTag.title LIKE '%".sqls($search)."%'");
        $tags = ClassRegistry::init('ObjOptTag')->find('list', array('conditions' => $conditions, 'order' => array('ObjOptTag.title' => 'asc')));
        exit(!empty($tags) ? json_encode(array_values($tags)) : null);
    }
    
    public function get_count($base_id = null){
        return $this->ObjItemList->find('count', array('conditions' => array('ObjItemList.base_id' => $base_id)));
    }
    
 
    
}

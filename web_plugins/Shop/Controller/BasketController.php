<?php
class BasketController extends ShopAppController {

    public function beforeFilter() {
        parent::beforeFilter();

		if (empty($this->request->data)) {
			if (!$this->Session->check('Basketredirect') && env('HTTP_REFERER') && strpos($this->referer(null, true), '/shop/') === false) {
				$this->Session->write('Basketredirect', $this->referer(null, true));
			}
		}
    }
    
    public function get_list(){
        return $this->ModOrder->get_data($this->Cookie->read('Basket'));
    }

    public function add($id = null, $qnt = 0, $to_basket = '0'){
        $basket = $this->Cookie->read('Basket');
        
        if(!is_array($basket)) $basket = array();
        
        if(!empty($_POST['id']) && empty($_GET['id'])) $_GET['id'] = $_POST['id'];
        if(!empty($_POST['qnt']) && empty($_GET['qnt'])) $_GET['qnt'] = $_POST['qnt'];
        if(!empty($_POST['related']) && empty($_GET['related'])) $_GET['related'] = $_POST['related'];
        if(!empty($_POST['extra']) && empty($_GET['extra'])) $_GET['extra'] = $_POST['extra'];
        
        if(!empty($_GET['id'])){
            $id = $_GET['id'];
            if(empty($_GET['qnt'])) $_GET['qnt'] = '1';
        }

        if(!empty($this->data['qnt'])) $qnt = $this->data['qnt'];
        if(!empty($_GET['qnt']) && empty($this->data['qnt'])) $qnt = $_GET['qnt'];
        if(!($qnt > 0)) $qnt = 1;

        $uniqid = uniqid();
        
        if(Configure::read('CMS.settings.shop.basket_add_new_item') == '1' || !empty($_GET['related'])){
            $basket['items'][$uniqid] = array('id' => $id, 'qnt' => $qnt, 'extra' => $_GET['extra']);
        } else {
            $found = false;
            foreach($basket['items'] as $key => $val){
                if($val['id'] == $id && $val['extra'] == $_GET['extra']){
                    $basket['items'][$key]['qnt'] += $qnt;
                    $found = true;
                    break;
                }
            }
            if(!$found) $basket['items'][$uniqid] = array('id' => $id, 'qnt' => $qnt, 'extra' => $_GET['extra']);
        }
        
        if(!empty($_GET['related'])){
            foreach($_GET['related'] as $_id){
                $basket['items'][uniqid()] = array('id' => $_id, 'qnt' => ($_POST['related_qnt'][$_id] > 0 ? $_POST['related_qnt'][$_id] : 1) * $qnt, 'extra' => $_GET['extra'], 'rel_qnt' => ($_POST['related_qnt'][$_id] > 0 ? $_POST['related_qnt'][$_id] : 1), 'related' => $uniqid);
            }
        }

        $result = $this->ModOrder->get_data($basket);
        if(!empty($result['errors'])){
            $this->Session->setFlash(implode("\n", $result['errors']), 'flash');
        } else {
            if(!($to_basket > 0)) $this->Session->setFlash("/system/window?tpl=basket_add&id={$_GET['id']}", 'window');
        }
        
        $this->Cookie->write('Basket', $basket);
        
        $this->redirect(($to_basket > 0 ? ($to_basket < 2 ? array('action' => 'index') : '/shop/checkout/index') : $this->referer()));
    }


    public function delete($id = null, $supl = null){
        $basket = $this->Cookie->read('Basket');
        
        if(!empty($id)){
            foreach($basket['items'] as $_id => $item) if($item['related'] == $id) unset($basket['items'][$_id]);
            if(isset($basket['items'][$id])) unset($basket['items'][$id]);
        } else {
            $basket = null;
        }
        
        $this->Cookie->write('Basket', $basket);
        
        $this->redirect($this->referer());
    }
    
    public function qnt($id = null, $qnt = 0){
        $basket = $this->Cookie->read('Basket');
        
        if(!empty($basket['items'][$id])){
            $basket['items'][$id]['qnt'] = $qnt;
            if(!($basket['items'][$id]['qnt'] > 0)){
                foreach($basket['items'] as $_id => $item) if($item['related'] == $id) unset($basket['items'][$_id]);
                unset($basket['items'][$id]);
            } else {
                foreach($basket['items'] as $_id => $item) if($item['related'] == $id) $basket['items'][$_id]['qnt'] = $basket['items'][$_id]['rel_qnt'] * $qnt;
            }
        }

        $result = $this->ModOrder->get_data($basket);
        if(!empty($result['errors'])){
            $this->Session->setFlash(implode("\n", $result['errors']), 'flash');
        }
        
        $this->Cookie->write('Basket', $basket);
        
        $this->redirect($this->referer());
    }

    public function extra($id = null, $type = null, $value){
        
        $basket = $this->Cookie->read('Basket');
        
        if(!empty($basket['items'][$id])){
            if($value == 'false' || $value == '0'){
                unset($basket['items'][$id]['extra'][$type]);
            } else {
                $basket['items'][$id]['extra'][$type] = ($value == 'true' ? '1' : $value);
            }
        }

        $result = $this->ModOrder->get_data($basket);
        if(!empty($result['errors'])){
            $this->Session->setFlash(implode("\n", $result['errors']), 'flash');
        }
        
        $this->Cookie->write('Basket', $basket);
        
        $this->redirect($this->referer());
    }

    public function update(){
        $basket = $this->Cookie->read('Basket');

        if(!empty($this->data['qnt'])) foreach($this->data['qnt'] as $id => $qnt){
            $basket['items'][$id]['qnt'] = $qnt;
            if(!($basket['items'][$id]['qnt'] > 0)){
                foreach($basket['items'] as $_id => $item) if($item['related'] == $id) unset($basket['items'][$_id]);
                unset($basket['items'][$id]);
            } else {
                foreach($basket['items'] as $_id => $item) if($item['related'] == $id) $basket['items'][$_id]['qnt'] = $basket['items'][$_id]['rel_qnt'] * $qnt;
            }
        }

        if(!empty($this->data['qnt'])) foreach($basket['items'] as $id => $item){
            if(!empty($this->data['extra'][$id])){
                $basket['items'][$id]['extra'] = $this->data['extra'][$id];
            } else {
                $basket['items'][$id]['extra'] = array();
            }
        }
        
        if(!empty($this->data['coupon'])){
            $basket['coupon'] = $this->data['coupon'];
        }
        if(isset($this->params->query['bonus'])){
            $basket['bonus'] = $this->params->query['bonus'];
        }  

        if(isset($this->params->query['shipping'])){
            $basket['options']['shipping']['shipping'] = $this->params->query['shipping'];
        }
        if(isset($this->params->query['zone'])){
            $basket['options']['shipping']['zone_id'] = $this->params->query['zone'];
        }      
        
        $result = $this->ModOrder->get_data($basket);
        if(!empty($result['errors'])){
            $this->Session->setFlash(implode("\n", $result['errors']), 'flash');
        }

        $this->Cookie->write('Basket', $basket);
        
        $this->redirect($this->referer());
    }
    
    public function ext($id = null, $ext = 0){
        $basket = $this->Cookie->read('Basket');
        
        if(!empty($basket['items'][$id])){
            $basket['items'][$id]['ext'] = $ext;
        }
        
        $this->Cookie->write('Basket', $basket);
        
        $this->redirect($this->referer());
    }
    
    public function index(){
        $this->Basic->template(array('title' => ___('Basket'), 'alias' => $this->here));

        $_basket = $this->Cookie->read('Basket');
        //if(!empty($_basket['options'])) unset($_basket['options']);
        
        $basket = $this->ModOrder->get_data($this->Cookie->read('Basket'));
        $this->set('basket', $basket);

        foreach($_basket['items'] as $key => $val) if(!array_key_exists($key, $basket['items'])) unset($_basket['items'][$key]);
        $this->Cookie->write('Basket', $_basket);
        
        $_shipping = $this->ObjItemList->find('all', array('tid' => 'shipping'));
        foreach($_shipping as $__shipping){
            $shipping[$__shipping['ObjItemList']['id']] = $__shipping['ObjItemList']['title'];
            if($__shipping['ObjItemList']['data']['auto_select'] == '1') $shipping_auto = $__shipping['ObjItemList']['id'];
        }
        $this->set('shipping', $shipping);
        $this->set('shipping_auto', $shipping_auto);
        
        $this->set('zones', $this->ObjItemTree->TreeList(array('tid' => 'shipping_zone', 'parent_id IS NULL')));
        $this->set('payments', $this->ObjItemList->find('list', array('tid' => 'payment', 'order' => array('ObjItemList.order_id' => 'asc'))));

    }
    
}

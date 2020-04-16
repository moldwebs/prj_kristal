<?php
class OrderController extends ShopAppController {

    public $paginate = array(
        'ModOrder' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ModOrder.created' => 'desc'
            ),
            'conditions' => array('ModOrder.paystatus <> 99')
        ),
        'ObjItemList' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemList.created' => 'desc'
            ),
            'tid' => false
        )
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('sys_order_statuses', array('0' => ___('New', true), '1' => ___('In processing', true), '3' => ___('Custom made', true), '4' => ___('Custom ready', true), '2' => ___('Agreed', true), '5' => ___('Canceled', true)));
        $this->set('sys_order_ship_statuses', array('0' => ___('Waiting', true), '1' => ___('New', true), '2' => ___('In processing', true), '3' => ___('Postponed', true), '4' => ___('Shipped', true), '5' => ___('Partial delivered', true), '6' => ___('Delivered', true), '7' => ___('Canceled', true)));
        $this->set('sys_order_pay_statuses', array('0' => ___('Not Paid', true), '1' => ___('Paid', true), '2' => ___('Waiting payment', true)));
    }
    
    public function admin_table_actions(){
        $this->ModOrder->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Orders') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ModOrder', $this->Basic->filters('ModOrder')));
        
        $ids = array();
        foreach($this->viewVars['items'] as $item) if(!empty($item['ModOrder']['data']['data_shipping']['zone_id'])) $ids[] = $item['ModOrder']['data']['data_shipping']['zone_id'];
        if(!empty($ids)){
            $this->set('zones_list', $this->ObjItemTree->find('allindex', array('tid' => 'shipping_zone', 'conditions' => array('ObjItemTree.id' => $ids))));
            $this->set('zones_parents', $this->ObjItemTree->find('list', array('tid' => 'shipping_zone', 'conditions' => array('ObjItemTree.parent_id IS NULL'))));
        }
        
        $this->set('shippings', $this->ObjItemList->find('list', array('tid' => 'shipping')));
        $this->set('payments', $this->ObjItemList->find('list', array('tid' => 'payment')));
        $this->set('vendors', $this->ObjItemList->find('list', array('tid' => 'vendor')));
    }
    
    public function admin_view($id = null, $user_id = null){
        $this->set('page_title', ___('Order') . ' :: ' . $id);
        
        if($id > 0){
            $item = $this->Basic->load($id, $this->ModOrder);
        } else if($user_id > 0){
            $item = $this->ModOrder->find('first', array('conditions' => array('ModOrder.userid' => $user_id)));
        }
        $this->set('item', $item);
        
        $this->set('zones_list', $this->ObjItemTree->find('allindex', array('tid' => 'shipping_zone', 'conditions' => array('ObjItemTree.id' => $item['ModOrder']['data']['data_shipping']['zone_id']))));
        $this->set('zones_parents', $this->ObjItemTree->find('list', array('tid' => 'shipping_zone', 'conditions' => array('ObjItemTree.parent_id IS NULL'))));
        $this->set('shippings', $this->ObjItemList->find('list', array('tid' => 'shipping', 'conditions' => array('ObjItemList.id' => $item['ModOrderItem']['shipping'][0]['item_id']))));
        $this->set('payments', $this->ObjItemList->find('list', array('tid' => 'payment', 'conditions' => array('ObjItemList.id' => $item['ModOrder']['data']['data_payment']['payment']))));

        $user = ClassRegistry::init('Users.User')->find('first', array('conditions' => array('User.id' => $item['ModOrder']['userid'])));
        $this->set('user', $user);
        
        $this->set('vendors', $this->ObjItemList->find('list', array('tid' => 'vendor')));
    }

    public function admin_edit($id = null){
        $this->set('page_title', ___('Order') . ' :: ' . ($id > 0 ? $id : ___('Create')));
        
        if(!empty($this->data)){
            $save_data = $this->data;
        }
        
        if(!empty($id)){
        
            $item = $this->Basic->load($id, $this->ModOrder);
            
            if(empty($item['ModOrder']['onstatus'])){
                $item['ModOrder']['onstatus'] = '1';
                $item['ModOrder']['operator_id'] = $this->Session->read('Auth.User.id');
    
                $this->ModOrder->updateAll(
                    array('ModOrder.onstatus' => sqls($item['ModOrder']['onstatus'], true)),
                    array("ModOrder.id" => $id)
                );
        
                $this->ModOrder->updateAll(
                    array('ModOrder.operator_id' => sqls($item['ModOrder']['operator_id'], true)),
                    array("ModOrder.id" => $id, "ModOrder.operator_id IS NULL")
                );
            }
            
            $this->set('item', $item);
            $this->data = $item;
    
            $user = ClassRegistry::init('Users.User')->find('first', array('conditions' => array('User.id' => $item['ModOrder']['userid'])));
            $this->set('user', $user);
            
            $__items = $this->ObjItemList->find('allindex', array('tid' => 'catalog', 'conditions' => array('ObjItemList.id' => Set::extract('/item_id', $item['ModOrderItem']['item']))));
            $vendor_items = array();
            foreach($__items as $__item){
                $vendor_items[] = $__item['ObjItemList']['id'];
                if(!empty($__item['ObjItemList']['rel_id'])) $vendor_items[] = $__item['ObjItemList']['rel_id'];
            }
            
            $vendors = $this->ObjItemList->find('list', array('tid' => 'vendor'));
            $_vendor_prices = $this->ExtraData->find('all', array('conditions' => array('ExtraData.type' => 'vendor', 'ExtraData.extra_2' => $vendor_items)));
            $vendor_prices = array();
            foreach($_vendor_prices as $_vendor_price){
                $_vendor_price = $_vendor_price['ExtraData'];
                $vendor_prices[$_vendor_price['extra_2']][$_vendor_price['extra_1']] = $vendors[$_vendor_price['extra_1']] . ' [' . $_vendor_price['extra_6'] . ' ' . $_vendor_price['extra_7'] . ']' . ' [' . $_vendor_price['data']['price'] . ' ' . $_vendor_price['data']['currency'] . ']' . ' [' . (!empty($_vendor_price['extra_5']) ? $_vendor_price['extra_5'] : $__items[$_vendor_price['extra_2']]['RelationValue']['vendor_code'][$_vendor_price['extra_1']]) . ']';
            }
            
            foreach($__items as $__item){
                if(!empty($__item['ObjItemList']['rel_id']) && empty($vendor_prices[$__item['ObjItemList']['id']])) $vendor_prices[$__item['ObjItemList']['id']] = $vendor_prices[$__item['ObjItemList']['rel_id']];
            }
            
            $this->set('vendor_prices', $vendor_prices);

        } else {
            $item = array();
            $item['ModOrder']['onstatus'] = '1';
            $item['ModOrder']['operator_id'] = $this->Session->read('Auth.User.id');
            $item['ModOrder']['currency'] = Configure::read('Obj.currency')['currency'];
           
            $this->set('item', $item);
        }

        $this->set('zones', $this->ObjItemTree->TreeList(array('tid' => 'shipping_zone')));
        $this->set('shippings', $this->ObjItemList->find('list', array('tid' => 'shipping', 'conditions' => array('ObjItemList.status' => '1'), 'order' => array('ObjItemList.order_id' => 'asc'))));
        $this->set('liftings', array('entrance' => ___('Delivery products to the house'), 'stairs' => ___('Lifting products without elevator'), 'stairs_help' => ___('Lifting products without elevator (help courier)'), 'elevator' => ___('Lifting products with elevator'), 'elevator_help' => ___('Lifting products with elevator (help courier)')));
        $this->set('payments', $this->ObjItemList->find('list', array('tid' => 'payment', 'conditions' => array('ObjItemList.status' => '1'), 'order' => array('ObjItemList.order_id' => 'asc'))));

        $operators = ClassRegistry::init('Users.User')->find('list', array('fields' => array('id', 'username'), 'conditions' => array("role <> 'user'"), 'order' => array('username' => 'asc')));
        $this->set('operators', $operators);

        if(!empty($save_data['ModOrder'])){
            
            if(empty($save_data['ModOrder']['item'])) exit(json_encode(array('status' => 'MESSAGE', 'message' => ___('No items present'))));
            
            if(!empty($save_data['ajx_validate'])) exit(json_encode(array('status' => 'SUCCESS')));
            
            if(empty($id)){

                $save = $item['ModOrder'];
                
                $this->ModOrder->create();
                $this->ModOrder->save($save);

                $id = $this->ModOrder->getLastInsertId();
                
                $new_order = true;
            }
            
            
            if($save_data['saction'] == '10'){
                
                $save = $item['ModOrder'];
                $save['id'] = null;
                $save['onstatus'] = '0';
                $save['shipstatus'] = '0';
                $save['created'] = null;
                
                $this->ModOrder->create();
                $this->ModOrder->save($save);
                
                $order_id = $this->ModOrder->getLastInsertId();
                
                foreach($item['ModOrderItem'] as $key => $items){
                    foreach($items as $item){
                        $save = $item;
                        $save['id'] = null;
                        $save['order_id'] = $order_id;
                        $save['created'] = null;

                        $this->ModOrderItem->create();
                        $this->ModOrderItem->save($save);
                    }
                }

                $this->redirect(array('action' => 'edit', $order_id));
            }
            
            
            $order_price = 0;
            $order_qnt = 0;
            
            foreach($item['ModOrderItem']['item'] as $key => $val){
                if(!array_key_exists($val['id'], $save_data['ModOrder']['item'])){
                    $this->ModOrderItem->delete($val['id']);
                }
            }
            
            foreach($save_data['ModOrder']['item'] as $key => $val){
                if(!empty($item['ModOrderItem']['item'][$key])){
                    $val = array_replace_recursive($item['ModOrderItem']['item'][$key], $val);
                    $val['price_total'] = $val['price'] * $val['quantity'];
                } else {
                    $this->ModOrderItem->create();
                    $val['order_id'] = $id;
                    $val['type'] = 'item';
                    $val['price_total'] = $val['price'] * $val['quantity'];
                }

                if($val['data']['vendor']['vendor_id'] > 0){
                    $vendor_price = $this->ExtraData->find('first', array('conditions' => array('ExtraData.type' => 'vendor', 'ExtraData.extra_1' => $val['data']['vendor']['vendor_id'], 'ExtraData.extra_2' => $val['item_id'])));
                    //$val['data']['vendor']['vendor_code'] = $vendor_price['ExtraData']['extra_5'];
                    $val['data']['vendor']['vendor_code'] = $this->ObjItemList->ObjOptRelation->field('value', array('type' => 'vendor_code', 'rel_id' => $val['data']['vendor']['vendor_id'], 'foreign_key' => $val['item_id']));
                    $val['data']['vendor']['vendor_price'] = $vendor_price['ExtraData']['data']['c_price_orig'] . ':' . $vendor_price['ExtraData']['data']['c_currency_orig'];
                    $val['data']['vendor']['vendor_cprice'] = $vendor_price['ExtraData']['data']['price_orig'] . ':' . $vendor_price['ExtraData']['data']['currency_orig'];
                } else {
                    $val['data']['vendor'] = array();
                }
                
                $order_price = $order_price + $val['price_total'];
                $order_qnt = $order_qnt + $val['quantity'];
                
                $this->ModOrderItem->save($val);
            }
            
            foreach($save_data['ModOrder']['sextra'] as $key => $val){
                if(!empty($item['ModOrderItem'][$key])){
                    $val = array_replace_recursive(reset($item['ModOrderItem'][$key]), $val);
                    $val['price_total'] = $val['price'] * $val['quantity'];
                } else {
                    $this->ModOrderItem->create();
                    $val['order_id'] = $id;
                    $val['type'] = $key;
                    $val['price_total'] = $val['price'] * $val['quantity'];
                }
                
                $order_price = $order_price + $val['price_total'];
                
                if($key == 'shipping'){
                    $shipping = $this->ObjItemList->find('first', array('tid' => 'shipping', 'conditions' => array('ObjItemList.id' => $val['item_id'])));
                    $val['title'] = $shipping['ObjItemList']['title'];
                    $val['data']['title_trnsl'] = $shipping['Translates']['ObjItemList']['title'];
                }
                if($key == 'lifting'){
                    $lifting = array('entrance' => ('Delivery products to the house'), 'stairs' => ('Lifting products without elevator'), 'stairs_help' => ('Lifting products without elevator (help courier)'), 'elevator' => ('Lifting products with elevator'), 'elevator_help' => ('Lifting products with elevator (help courier)'));
                    $val['title'] = $lifting[$val['item_id']];
                }
                
                $this->ModOrderItem->save($val);
            }

            foreach($item['ModOrderItem']['extra'] as $key => $val){
                if(!array_key_exists($val['id'], $save_data['ModOrder']['extra'])){
                    $this->ModOrderItem->delete($val['id']);
                }
            }
            foreach($save_data['ModOrder']['extra'] as $key => $val){
                if(!empty($item['ModOrderItem']['extra'][$key])){
                    $val = array_replace_recursive($item['ModOrderItem']['extra'][$key], $val);
                    $val['price_total'] = $val['price'] * $val['quantity'];
                } else {
                    $this->ModOrderItem->create();
                    $val['order_id'] = $id;
                    $val['type'] = 'extra';
                    $val['price_total'] = $val['price'] * $val['quantity'];
                }
                
                $order_price = $order_price + $val['price_total'];
                
                $this->ModOrderItem->save($val);
            }

            foreach($item['ModOrderItem']['payment'] as $key => $val){
                if(!array_key_exists($val['id'], $save_data['ModOrder']['payment'])){
                    $this->ModOrderItem->delete($val['id']);
                }
            }
            foreach($save_data['ModOrder']['payment'] as $key => $val){
                if(!empty($item['ModOrderItem']['payment'][$key])){
                    $val = array_replace_recursive($item['ModOrderItem']['payment'][$key], $val);
                    $val['price_total'] = $val['price'] * $val['quantity'];
                } else {
                    $this->ModOrderItem->create();
                    $val['order_id'] = $id;
                    $val['type'] = 'payment';
                    $val['price_total'] = $val['price'] * $val['quantity'];
                }
                $this->ModOrderItem->save($val);
            }
            
            foreach($save_data['ModOrder']['actions'] as $key => $val){
                $val['order_id'] = $id;
                $val['data'] = json_decode($val['data'], true);
                $this->ModOrder->ModOrderAction->create();
                $this->ModOrder->ModOrderAction->save($val);
            }
            
            foreach(array('userid', 'operator_id', 'extra_1', 'extra_2', 'extra_3', 'extra_4', 'onstatus', 'shipstatus', 'paystatus') as $field){
                if($item['ModOrder'][$field] != $save_data['ModOrder'][$field]){
                    $this->ModOrder->updateAll(
                        array("ModOrder.{$field}" => sqls($save_data['ModOrder'][$field], true)),
                        array("ModOrder.id" => $id)
                    );
                    if($field == 'onstatus') $this->__set_onstatus($id, $save_data['ModOrder'][$field]);
                    if($field == 'shipstatus') $this->__set_shipstatus($id, $save_data['ModOrder'][$field]);
                    if($field == 'paystatus') $this->__set_paystatus($id, $save_data['ModOrder'][$field]);
                }
            }
            
            $order_data = $item['ModOrder']['data'];
            if(!empty($save_data['ModOrder']['data_extra'])) $order_data['data_extra'] = array_replace_recursive((is_array($order_data['data_extra']) ? $order_data['data_extra'] : array()), $save_data['ModOrder']['data_extra']);
            if(!empty($save_data['ModOrder']['data_checkout'])) $order_data['data_checkout'] = array_replace_recursive((is_array($order_data['data_checkout']) ? $order_data['data_checkout'] : array()), $save_data['ModOrder']['data_checkout']);
            if(!empty($save_data['ModOrder']['data_payment'])) $order_data['data_payment'] = array_replace_recursive((is_array($order_data['data_payment']) ? $order_data['data_payment'] : array()), $save_data['ModOrder']['data_payment']);
            if(!empty($save_data['ModOrder']['data_shipping'])) $order_data['data_shipping'] = array_replace_recursive((is_array($order_data['data_shipping']) ? $order_data['data_shipping'] : array()), $save_data['ModOrder']['data_shipping']);
            if(!empty($save_data['pay_data'])) $order_data['pay_data']['dates'] = $save_data['pay_data'];
            
            $this->ModOrder->updateAll(
                array("ModOrder.data" => sqls(json_encode($order_data), true)),
                array("ModOrder.id" => $id)
            );
            
            $this->ModOrder->updateAll(
                array("ModOrder.price" => sqls($order_price, true), "ModOrder.quantity" => sqls($order_qnt, true)),
                array("ModOrder.id" => $id)
            );
            
            if($new_order){
                $this->getEventManager()->dispatch(new CakeEvent('Order.success_admin', null, array('order_id' => $id)));
                $this->getEventManager()->dispatch(new CakeEvent('Order.success', null, array('order_id' => $id, 'email' => $order_data['data_checkout']['email'])));
            }
            
            if($save_data['saction'] == '1') $this->redirect(array('action' => 'edit', $id));
            if($save_data['saction'] == '2') $this->Basic->back();
            
            exit('OK');
        } else {
            $this->Basic->wback();
        }
    }
    
    public function admin_delivery($currency){
        Configure::write('Obj.currency', Configure::read('Obj.currencies')[$currency]);

        $basket = array();
        foreach($this->data['ModOrder']['item'] as $item){
            $basket['weights'][] = ($item['weight'] * $item['quantity']);
            $basket['weight_total'] = $basket['weight_total'] + ($item['weight'] * $item['quantity']);
            $basket['items_price'] = $basket['items_price'] + ($item['price'] * $item['quantity']);
        }

        $shippings = $this->ModOrder->get_shipping_zone_price(array('basket' => $basket, 'zone_id' => $this->data['ModOrder']['data_shipping']['zone_id']));
        $liftings = $this->ModOrder->get_shipping_lifting_price(array('basket' => $basket, 'shipping_id' => $this->data['ModOrder']['sextra']['shipping']['item_id'], 'floor' => $this->data['ModOrder']['data_shipping']['floor']));

        $shipping = $shippings[$this->data['ModOrder']['sextra']['shipping']['item_id']]['price'];
        $lifting = $liftings[$this->data['ModOrder']['sextra']['lifting']['item_id']]['price'];

        exit(json_encode(array('shipping' => ($shipping > 0 ? $shipping : 0), 'lifting' => ($lifting > 0 ? $lifting : 0))));
    }
    
    
    public function admin_customer($id = null){
        $conditions = $this->Basic->filters('ModOrder')['FLTRS'];
        if(!empty($id)) $conditions[] = array('ModOrder.userid' => $id);
        $this->set('items', $this->paginate('ModOrder', array('OR' => $conditions)));

        $ids = array();
        foreach($this->viewVars['items'] as $item) if(!empty($item['ModOrder']['data']['data_shipping']['zone_id'])) $ids[] = $item['ModOrder']['data']['data_shipping']['zone_id'];
        if(!empty($ids)){
            $this->set('zones_list', $this->ObjItemTree->find('allindex', array('tid' => 'shipping_zone', 'conditions' => array('ObjItemTree.id' => $ids))));
            $this->set('zones_parents', $this->ObjItemTree->find('list', array('tid' => 'shipping_zone', 'conditions' => array('ObjItemTree.parent_id IS NULL'))));
        }
        
        $ids = array();
        foreach($this->viewVars['items'] as $item) if(!empty($item['ModOrderItem']['shipping'][0])) $ids[] = $item['ModOrderItem']['shipping'][0]['item_id'];
        if(!empty($ids)){
            $this->set('shippings', $this->ObjItemList->find('list', array('tid' => 'shipping', 'conditions' => array('ObjItemList.id' => $ids))));
        }

        $ids = array();
        foreach($this->viewVars['items'] as $item) if(!empty($item['ModOrder']['data']['data_payment']['payment'])) $ids[] = $item['ModOrder']['data']['data_payment']['payment'];
        if(!empty($ids)){
            $this->set('payments', $this->ObjItemList->find('list', array('tid' => 'payment', 'conditions' => array('ObjItemList.id' => $ids))));
        }
        
        $this->set('vendors', $this->ObjItemList->find('list', array('tid' => 'vendor')));
    }

    public function admin_add_customer(){
        if(!empty($_GET['id'])){
            
            $user = ClassRegistry::init('Users.User')->findById($_GET['id']);
            $this->set('user', $user);
            
            $checkout = $this->ModOrder->find('first', array('conditions' => array('ModOrder.userid' => $_GET['id']), 'order' => array('ModOrder.created' => 'desc')));
            $this->set('checkout', $checkout);
            
            $this->set('append', '1');
        } else if(!empty($_GET['search'])){
            $items = ClassRegistry::init('Users.User')->find('all', array('conditions' => $this->Basic->filters('User'), 'limit' => 50, 'order' => array('User.username' => 'asc')));
            $this->set('items', $items);
            $this->set('search', '1');
        }
    }
    
    public function admin_add_item(){
        Configure::write('Config.tid', 'catalog');
        Configure::write('TMP.force_combinations', '1');
        
        if(Configure::read('PLUGIN.Specification') == '1') $this->ObjItemList->Behaviors->load('Specification.Specification');
        if(Configure::read('CMS.settings.catalog.obj_combinations') == '1') $this->ObjItemList->Behaviors->load('Catalog.Combination');
        $this->ObjItemList->Behaviors->load('Catalog.Catalog');

        if(!empty($_GET['id'])){
            $currency = $_GET['currency'];
            
            Configure::write('TMP.force_all', '1');
            $item = $this->ObjItemList->findById($_GET['id']);
            $_item = array(
                'id' => uniqid(),
                'item_id' => $item['ObjItemList']['id'],
                'title' => $item['ObjItemList']['title'],
                'code' => $item['ObjItemList']['code'],
                'quantity' => '1',
                'price' => ($currency == $item['Price']['currency'] ? $item['Price']['value'] : $item['Price']['currency_vals'][$currency]),
                'currency' => $currency,
                'vendor_id' => key($item['RelationValue']['vendor_price']),
                'weight' => $item['ObjItemList']['data']['weight'],
            );
            $this->set('_item', $_item);
            $this->set('append', '1');

            $vendors = $this->ObjItemList->find('list', array('tid' => 'vendor'));
            $_vendor_prices = $this->ExtraData->find('all', array('conditions' => array('ExtraData.type' => 'vendor', 'ExtraData.extra_2' => array($item['ObjItemList']['id'], $item['ObjItemList']['rel_id']))));
            $vendor_prices = array();
            foreach($_vendor_prices as $_vendor_price){
                $_vendor_price = $_vendor_price['ExtraData'];
                $vendor_prices[$_vendor_price['extra_2']][$_vendor_price['extra_1']] = $vendors[$_vendor_price['extra_1']] . ' [' . $_vendor_price['extra_6'] . ' ' . $_vendor_price['extra_7'] . ']' . ' [' . $_vendor_price['data']['price'] . ' ' . $_vendor_price['data']['currency'] . ']' . ' [' . (!empty($_vendor_price['extra_5']) ? $_vendor_price['extra_5'] : $item['RelationValue']['vendor_code'][$_vendor_price['extra_1']]) . ']';
            }
            if(!empty($item['ObjItemList']['rel_id']) && empty($vendor_prices[$item['ObjItemList']['id']])) $vendor_prices[$item['ObjItemList']['id']] = $vendor_prices[$item['ObjItemList']['rel_id']];
            $this->set('vendor_prices', $vendor_prices);
        } else if(!empty($_GET['search'])){
            $items = $this->ObjItemList->find('all', array('tid' => 'catalog', 'conditions' => $this->Basic->filters('ObjItemList'), 'limit' => 50, 'order' => array('ObjItemList.title' => 'asc')));
            $this->set('items', $items);
            $this->set('search', '1');
        } else {
            $this->set('bases', $this->ObjItemTree->TreeList(array('tid' => 'catalog')));
        }
    }
    
    public function admin_add_service(){
        if(!empty($_POST['data'])){
            $currency = $_GET['currency'];
            
            $item = $this->ObjItemList->findById($_GET['id']);
            $_item = array(
                'id' => uniqid(),
                'title' => $_POST['data']['title'],
                'quantity' => '1',
                'price' => $_POST['data']['value'],
                'currency' => $currency
            );
            $this->set('_item', $_item);
            $this->set('append', '1');
        }
    }
    
    public function admin_add_discount(){
        if(!empty($_POST['data'])){
            $currency = $_GET['currency'];
            
            $item = $this->ObjItemList->findById($_GET['id']);
            $_item = array(
                'id' => uniqid(),
                'title' => $_POST['data']['title'],
                'quantity' => '1',
                'price' => -$_POST['data']['value'],
                'currency' => $currency
            );
            $this->set('_item', $_item);
            $this->set('append', '1');
        }
    }
    
    public function admin_add_payment(){
        $payment_types = array('' => ___('Other')) + $this->ObjItemList->find('list', array('tid' => 'payment'));
        
        if(!empty($_POST['data'])){
            $currency = $_GET['currency'];
            
            $_item = array(
                'id' => uniqid(),
                'item_id' => $_POST['data']['payment_id'],
                'title' => $_POST['data']['title'],
                'quantity' => '1',
                'price' => $_POST['data']['value'],
                'currency' => $currency
            );
            $this->set('_item', $_item);
            $this->set('append', '1');
        }
        
        $this->set('payment_types', $payment_types);
    }
    
    public function admin_add_action(){
        if(!empty($_POST['data'])){
            $_item = array(
                'id' => uniqid(),
                'message' => $_POST['data']['comment'],
                'type' => ($_POST['data']['status'] != '' ? 'onstatus' : ''),
                'created' => date("Y-m-d H:i:s"),
                'Operator' => array('username' => $this->Session->read('Auth.User.username')),
                'operator_id' => $this->Session->read('Auth.User.id'),
                'data' => array(
                    'action' => ($_POST['data']['status'] != '' ? 'Set Status' : ''),
                    'id' => $_POST['data']['status'],
                    'value' => $this->viewVars['sys_order_statuses'][$_POST['data']['status']],
                )
                
            );
            $this->set('_item', $_item);
            $this->set('append', '1');
        }
    }
    
	public function admin_delete($id = null){
	    $items = $this->ModOrderItem->find('list', array('conditions' => array('ModOrderItem.order_id' => $id), 'fields' => array('ModOrderItem.item_id', 'ModOrderItem.quantity')));
        if($this->ModOrder->delete($id)){
            if(Configure::read('CMS.settings.catalog.obj_qnt') == '1'){
                foreach($items as $item_id => $qnt){
                    $this->ObjItemList->updateAll(
                        array('ObjItemList.qnt' => 'ObjItemList.qnt + ' . (int)$qnt),
                        array("ObjItemList.id" => $item_id)
                    );
                }
            }
        }
        $this->Basic->back();
	}

   public function __set_onstatus($id = null, $value = null){
        $this->getEventManager()->dispatch(new CakeEvent('Order.order_onstatus', null, array('status' => $value, 'order_id' => $id)));
        
        if(Configure::read('CMS.settings.catalog.obj_qnt') == '1' && ($value == '5' || ($value != '5' && $this->ModOrder->fread('onstatus', $id) == '5'))){
            $items = $this->ModOrderItem->find('list', array('conditions' => array('ModOrderItem.order_id' => $id), 'fields' => array('ModOrderItem.item_id', 'ModOrderItem.quantity')));
            foreach($items as $item_id => $qnt){
                $this->ObjItemList->updateAll(
                    array('ObjItemList.qnt' => 'ObjItemList.qnt ' . ($value == '5' ? '+' : '-') . ' ' . (int)$qnt),
                    array("ObjItemList.id" => $item_id)
                );
            }
        }
        $this->ModOrder->updateAll(
            array('ModOrder.onstatus' => sqls($value, true)),
            array("ModOrder.id" => $id)
        );

        $this->ModOrder->updateAll(
            array('ModOrder.operator_id' => sqls($this->Session->read('Auth.User.id'), true)),
            array("ModOrder.id" => $id, "ModOrder.operator_id IS NULL")
        );
        
        if($value == '2'){
            $this->ModOrder->updateAll(
                array('ModOrder.shipstatus' => sqls('1', true)),
                array("ModOrder.id" => $id, "ModOrder.shipstatus = 0")
            );
        }
        

        $this->ModOrder->ModOrderAction->create();
        $this->ModOrder->ModOrderAction->save(array(
            'order_id' => $id,
            'operator_id' => $this->Session->read('Auth.User.id'),
            'type' => 'onstatus',
            'data' => array('action' => 'Set Status', 'id' => $value, 'value' => $this->viewVars['sys_order_statuses'][$value])
        ));
        $action_id = $this->ModOrder->ModOrderAction->getLastInsertId();

        $this->getEventManager()->dispatch(new CakeEvent('Order.onstatus_admin', null, array('order_id' => $id, 'status' => $this->viewVars['sys_order_statuses'][$value])));
        $this->getEventManager()->dispatch(new CakeEvent('Order.onstatus', null, array('order_id' => $id, 'status' => $this->viewVars['sys_order_statuses'][$value])));
        
        return $action_id;
   }
   
   public function admin_set_onstatus($id = null, $value = null){
        $action_id = $this->__set_onstatus($id, $value);

        exit("ajx_win_url('/admin/shop/order/action/{$action_id}');");
        exit('OK');
    }

   public function __set_shipstatus($id = null, $value = null){
        $this->getEventManager()->dispatch(new CakeEvent('Order.order_shipstatus', null, array('status' => $value, 'order_id' => $id)));

        $this->ModOrder->updateAll(
            array('ModOrder.shipstatus' => sqls($value, true)),
            array("ModOrder.id" => $id)
        );

        $this->ModOrder->ModOrderAction->create();
        $this->ModOrder->ModOrderAction->save(array(
            'order_id' => $id,
            'operator_id' => $this->Session->read('Auth.User.id'),
            'type' => 'shipstatus',
            'data' => array('action' => 'Set Shipping Status', 'id' => $value, 'value' => $this->viewVars['sys_order_ship_statuses'][$value])
        ));
        $action_id = $this->ModOrder->ModOrderAction->getLastInsertId();

        $this->getEventManager()->dispatch(new CakeEvent('Order.shipstatus_admin', null, array('order_id' => $id, 'status' => $this->viewVars['sys_order_ship_statuses'][$value])));
        $this->getEventManager()->dispatch(new CakeEvent('Order.shipstatus', null, array('order_id' => $id, 'status' => $this->viewVars['sys_order_ship_statuses'][$value])));
        
        return $action_id;
   }

   public function admin_set_shipstatus($id = null, $value = null){
        $action_id = $this->__set_shipstatus($id, $value);

        exit("ajx_win_url('/admin/shop/order/action/{$action_id}');");
        exit('OK');
    }

   public function __set_paystatus($id = null, $value = null){
        $this->getEventManager()->dispatch(new CakeEvent('Order.order_paystatus', null, array('status' => $value, 'order_id' => $id)));
        
        $this->ModOrder->updateAll(
            array('ModOrder.paystatus' => sqls($value, true)),
            array("ModOrder.id" => $id)
        );

        $this->ModOrder->ModOrderAction->create();
        $this->ModOrder->ModOrderAction->save(array(
            'order_id' => $id,
            'operator_id' => $this->Session->read('Auth.User.id'),
            'type' => 'paystatus',
            'data' => array('action' => 'Set Payment Status', 'id' => $value, 'value' => $this->viewVars['sys_order_pay_statuses'][$value])
        ));
        $action_id = $this->ModOrder->ModOrderAction->getLastInsertId();

        $this->getEventManager()->dispatch(new CakeEvent('Order.paystatus_admin', null, array('order_id' => $id, 'status' => $this->viewVars['sys_order_pay_statuses'][$value])));
        $this->getEventManager()->dispatch(new CakeEvent('Order.paystatus', null, array('order_id' => $id, 'status' => $this->viewVars['sys_order_pay_statuses'][$value])));
        
        return $action_id;                
   }

   public function admin_set_paystatus($id = null, $value = null){
        $action_id = $this->__set_paystatus($id, $value);

        exit("ajx_win_url('/admin/shop/order/action/{$action_id}');");

        exit('OK');
    }    
    
    public function admin_action($action_id = null){
        if(!empty($this->data)){
            $this->ModOrder->ModOrderAction->updateAll(
                array('ModOrderAction.message' => sqls($this->data['message'], true)),
                array("ModOrderAction.id" => $action_id)
            );
            $this->getEventManager()->dispatch(new CakeEvent('Order.action_admin', null, array('action_id' => $action_id)));
            $this->getEventManager()->dispatch(new CakeEvent('Order.action', null, array('action_id' => $action_id)));
            exit("ajx_win_close();");
        }
    }
    
	public function admin_set_extra($tp = null, $id = null, $value = null){
        if(in_array($tp, array('1', '2', '3', '4'))) $this->ModOrder->updateAll(array("ModOrder.extra_" . $tp => sqls($value, true)), array("ModOrder.id" => $id));
        $this->Basic->back();
	}

    public function admin_statistics(){
        $this->set('page_title', ___('Items') . ' :: ' . ___('List'));
        
        $_GET['othfltr_date_start'] = sqls($_GET['othfltr_date_start']);
        $_GET['othfltr_date_end'] = sqls($_GET['othfltr_date_end']);
        $this->ObjItemList->virtualFields['stats'] = "(SELECT SUM(wb_mod_order_item.quantity) FROM wb_mod_order_item WHERE wb_mod_order_item.item_id = ObjItemList.id".(!empty($_GET['othfltr_date_start']) ? " AND wb_mod_order_item.created >= '{$_GET['othfltr_date_start']}'" : null).(!empty($_GET['othfltr_date_end']) ? " AND wb_mod_order_item.created <= '{$_GET['othfltr_date_end']}'" : null).")";
        
        $this->paginate['ObjItemList'] = array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemList.stats' => 'desc'
            ),
            'tid' => 'catalog',
            'conditions' => array('stats >' => '0')
        );
        
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));

        $this->set('bases', $this->ObjItemTree->TreeList(array('tid' => 'catalog')));
        $this->set('manufacturers', $this->ObjItemList->find('list', array('tid' => 'manufacturer', 'order' => array('title' => 'asc'))));
    }

    public function admin_print($id){

        $order = $this->Basic->load($id, $this->ModOrder);

        //Configure::write('Config.language', 'rom');

        $zones_list = $this->ObjItemTree->find('allindex', array('tid' => 'shipping_zone', 'conditions' => array('ObjItemTree.id' => $order['ModOrder']['data']['data_shipping']['zone_id'])));
        $zones_parents = $this->ObjItemTree->find('list', array('tid' => 'shipping_zone', 'conditions' => array('ObjItemTree.parent_id IS NULL')));
        $shippings = $this->ObjItemList->find('list', array('tid' => 'shipping'));
        $payments = $this->ObjItemList->find('list', array('tid' => 'payment'));
        
        $vendors = $this->ObjItemList->find('list', array('tid' => 'vendor'));
        
        
        $order_options = array();
        foreach($order['ModOrderItem'] as $_type => $_item) if($_type != 'item') $order_options[$_item['type']] = $_item;
        
        if(!file_exists(EXT_VIEWS . DS . 'Prints' . DS . 'order.ctp')) exit('ERROR');
        
        App::import('Vendor', 'Shop.mpdf/mpdf');
        
        $mpdf = new mPDF('','A4', 0, '', 5, 5, 5, 5, 0, 0);
        
        $mpdf->setBasePath(EXT_VIEWS . DS . 'Prints' . DS);
        
        ob_start();
        echo eval('?>' . file_get_contents(EXT_VIEWS . DS . 'Prints' . DS . 'order.ctp') . '<?');
        $html = ob_get_clean();
        
        //$html = file_get_contents(EXT_VIEWS . DS . 'Prints' . DS . 'order.ctp');
        
        //$html = utf8_decode($html);
        
        $mpdf->WriteHTML($html);
        $mpdf->Output('Order' . '-' . $id .'.pdf', 'I');
        
        exit;
    }
    
    public function history(){
        if(!$this->Session->check('Auth.User.id')) $this->redirect('/users/login');
        
        $this->Basic->template(array('title' => ___('Orders'), 'alias' => $this->here));
        
        $this->set('items', $this->paginate('ModOrder', array('ModOrder.userid' => $this->Session->read('Auth.User.id'))));
    }

    public function saved(){
        if(!$this->Session->check('Auth.User.id')) $this->redirect('/users/login');
        
        $this->Basic->template(array('title' => ___('Orders'), 'alias' => $this->here));
        
        $this->paginate['ModOrder']['conditions'][0] = 'ModOrder.paystatus = 99';
        
        $this->set('items', $this->paginate('ModOrder', array('ModOrder.userid' => $this->Session->read('Auth.User.id'))));
    }

    public function view($order_id = null){
        if(!$this->Session->check('Auth.User.id')) $this->redirect('/users/login');
        
        $order = $this->ModOrder->findById($order_id);
        
        if($order['ModOrder']['userid'] != $this->Session->read('Auth.User.id')) $this->redirect('/');      

        $this->Basic->template(array('title' => ___('Order') . ': #' . str_pad($order['ModOrder']['id'], 5, '0', STR_PAD_LEFT), 'alias' => $this->here));
        
        if(empty($order['ModOrderAction'])){
            $order['ModOrderAction'][] = array(
                'created' => $order['ModOrder']['created'],
                'type' => 'onstatus',
                'data' => array('id' => '0')
            );
        }
        
        $this->set('order', $order);

        $this->set('zones_list', $this->ObjItemTree->find('allindex', array('tid' => 'shipping_zone', 'conditions' => array('ObjItemTree.id' => $order['ModOrder']['data']['data_shipping']['zone_id']))));
        $this->set('zones_parents', $this->ObjItemTree->find('list', array('tid' => 'shipping_zone', 'conditions' => array('ObjItemTree.parent_id IS NULL'))));
        $this->set('shippings', $this->ObjItemList->find('list', array('tid' => 'shipping', 'conditions' => array('ObjItemList.id' => $order['ModOrder']['data']['data_shipping']['shipping']))));
        $this->set('payments', $this->ObjItemList->find('list', array('tid' => 'payment', 'conditions' => array('ObjItemList.id' => $order['ModOrder']['data']['data_payment']['payment']))));
    }

    public function index(){
        if(!$this->Session->check('Auth.User.id')) $this->redirect('/users/login');
        
        $this->Basic->template(array('title' => ___('Items'), 'alias' => $this->here));

        $items_ids = Set::extract('/ModOrderItem/item_id', $this->ModOrder->ModOrderItem->find('all', array('fields' => array('ModOrderItem.id', 'ModOrderItem.item_id'), 'conditions' => array('OR' => array('ModOrder.paystatus' => '1', 'ModOrderItem.price' => '0'), 'ModOrder.userid' => $this->Session->read('Auth.User.id')))));
        $items_ids = am($items_ids, Set::extract('/ObjItemList/id', $this->ObjItemList->find('all', array('tid' => false, 'fields' => array('ObjItemList.id', 'ObjItemList.title'), 'conditions' => array('ObjItemList.extra_3' => '1')))));
        $items_ids = am($items_ids, Set::extract('/ExtraData/extra_1', $this->ExtraData->find('all', array('fields' => array('ExtraData.id', 'ExtraData.extra_1'), 'conditions' => array('ExtraData.extra_3' => $this->Session->read('Auth.User.id'))))));
        $bases_ids = $this->ObjItemList->find('list', array('tid' => false, 'fields' => array('ObjItemList.id', 'ObjItemList.base_id'), 'conditions' => array('ObjItemList.id' => $items_ids)));

        $this->set('bases', $this->ObjItemTree->find('list', array('tid' => false, 'fields' => array('ObjItemTree.id', 'ObjItemTree.title'), 'conditions' => array('ObjItemTree.id' => $bases_ids))));
        $this->set('items', $this->paginate('ObjItemList', am($this->Basic->filters('ObjItemList'), array('ObjItemList.id' => $items_ids))));
    }

    
}

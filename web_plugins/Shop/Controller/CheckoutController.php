<?php
class CheckoutController extends ShopAppController {

    public function beforeFilter() {
        parent::beforeFilter();

        if($this->params['action'] != 'fast' && $this->params['action'] != 'pay_success'){
            if(Configure::read('CMS.settings.shop.req_register') == '1' && !$this->Session->check('Auth.User.id')) $this->redirect('/users/login');
            if(!empty($_GET['req_register_try'])) $this->Session->write('Checkout.req_register_try', '1');
            if(Configure::read('CMS.settings.shop.req_register') == '2' && !$this->Session->check('Auth.User.id') && !$this->Session->check('Checkout.req_register_try')){
                $this->redirect('/users/login');
            }
        }

    }

    public function checkout(){
        $this->Cookie->write('Checkout', $this->request->data['Checkout']);
        $this->Cookie->write('Payment', $this->request->data['Payment']);

        $basket = $this->Cookie->read('Basket');
        $basket['options']['shipping']['street'] = $this->request->data['Checkout']['street'];
        $this->Cookie->write('Basket', $basket);

        $this->redirect(array('action' => 'order'));
    }

    public function index(){
        $this->Basic->template(array('title' => ___('Checkout'), 'alias' => $this->here));

        $basket = $this->ModOrder->get_data($this->Cookie->read('Basket'));
        if(empty($basket['qnt'])) $this->redirect('/shop/basket/index/');


        if($this->ObjItemList->find('count', array('tid' => 'shipping')) > 0 && empty($basket['options']['shipping'])){
            $this->redirect(array('action' => 'shipping'));
        }

        if(!empty($basket['shipping']) && empty($this->request->data)){
            //$shipping = $this->ObjItemList->find('first', array('tid' => 'shipping', 'conditions' => array('ObjItemList.id' => $basket['shipping'])));
            //if($shipping['ObjItemList']['data']['req_checkout'] != '1') $this->redirect('/shop/checkout/order/');
        }

        $this->set('basket', $basket);
        if(!empty($this->request->data)){
            $checkout_data = $this->request->data['Checkout'];
            $checkout_data['lang'] = Configure::read('Config.language');
            $this->Cookie->write('Checkout', $checkout_data);

            $redirect = true;

            if(!empty($this->request->data['Checkout']['password']) && !empty($this->request->data['Checkout']['email'])){
                $user_data = $this->requestAction('users/request_register', array('data' => array(
                    'username' => $this->request->data['Checkout']['name'] . ' ' . $this->request->data['Checkout']['lname'],
                    'usermail' => $this->request->data['Checkout']['email'],
                    'password' => $this->request->data['Checkout']['password'],
                )));
                if(!empty($user_data['User']['id'])){
                    $this->Auth->login($user_data['User']);
                } else {
                    $redirect = false;
                    $this->Session->setFlash(___('This email is already registered'), 'flash');
                }
            }

            if($redirect){
                if(!empty($this->viewVars['sys_payments'])){
                    $this->redirect(array('action' => 'payment'));
                } else {
                    $this->redirect(array('action' => 'order'));
                }
            }

        } else {
            if($this->Cookie->check('Checkout')){
                $this->request->data = array('Checkout' => $this->Cookie->read('Checkout'));
            }
            if(empty($this->request->data['Checkout']) && $this->Session->check('Auth.User.id')){
                $Checkout = $this->ModOrder->find('first', array('conditions' => array('ModOrder.userid' => $this->Session->read('Auth.User.id')), 'order' => array('ModOrder.created' => 'desc')));
                if(!empty($Checkout)) $this->request->data['Checkout'] = $Checkout['ModOrder']['data'];
            }
            if(empty($this->request->data['Checkout'])){
                $this->request->data['Checkout'] = $this->Session->read('Auth.User.data');
                $this->request->data['Checkout']['name'] = $this->Session->read('Auth.User.username');
                $this->request->data['Checkout']['email'] = $this->Session->read('Auth.User.usermail');
            }
        }
        $this->set('shipping', $this->ObjItemList->find('list', array('tid' => 'shipping')));
    }

    public function shipping(){
        $this->Basic->template(array('title' => ___('Shipping'), 'alias' => $this->here));

        $basket = $this->ModOrder->get_data($this->Cookie->read('Basket'));
        if(empty($basket['qnt'])) $this->redirect('/shop/basket/index/');

        $this->set('basket', $basket);
        if(!empty($this->request->data)){
            $basket = $this->Cookie->read('Basket');
            $basket['options']['shipping'] = $this->request->data['Checkout'];
            $this->Cookie->write('Basket', $basket);
            $this->redirect(array('action' => 'index'));
        } else {
            if($this->Cookie->check('Checkout')){
                $this->request->data = array('Checkout' => $this->Cookie->read('Checkout'));
            }
            if(empty($this->request->data['Checkout']) && $this->Session->check('Auth.User.id')){
                $Checkout = $this->ModOrder->find('first', array('conditions' => array('ModOrder.userid' => $this->Session->read('Auth.User.id')), 'order' => array('ModOrder.created' => 'desc')));
                if(!empty($Checkout)) $this->request->data['Checkout'] = $Checkout['ModOrder']['data'];
            }
        }
        $this->set('shipping', $this->ObjItemList->find('all', array('tid' => 'shipping')));

        $this->set('zones', $this->ObjItemTree->TreeList(array('tid' => 'shipping_zone', 'parent_id IS NULL')));

        $floors = array();
        for($i=1;$i<=(Configure::read('CMS.settings.shipping.max_floor') > 0 ? Configure::read('CMS.settings.shipping.max_floor') : 100);$i++) $floors[$i] = ___('Floor') . ' ' . $i;
        $this->set('floors', $floors);

    }

    public function shipping_zone_location($zone_id = null){
        if(empty($zone_id)) exit;
        $this->set('zones', $this->ObjItemTree->TreeList(array('tid' => 'shipping_zone', 'parent_id' => $zone_id)));
    }

    public function shipping_zone_price($zone_id = null){
        $basket = $this->ModOrder->get_data($this->Cookie->read('Basket'));
        $items = $this->ModOrder->get_shipping_zone_price(array('basket' => $basket, 'zone_id' => $zone_id));
        $this->set('items', $items);
    }

    public function shipping_lifting_price($shipping_id = null, $floor = null){
        $basket = $this->ModOrder->get_data($this->Cookie->read('Basket'));
        $items = $this->ModOrder->get_shipping_lifting_price(array('basket' => $basket, 'shipping_id' => $shipping_id, 'floor' => $floor));
        $this->set('items', $items);
    }


    public function payment(){
        $this->Basic->template(array('title' => ___('Payment'), 'alias' => $this->here));

        $basket = $this->ModOrder->get_data($this->Cookie->read('Basket'));
        if(empty($basket['qnt'])) $this->redirect('/shop/basket/index/');

        if(!empty($this->request->data)){
            $this->Cookie->write('Payment', $this->request->data['Payment']);
            $this->redirect(array('action' => 'order'));
        } else {
            if($this->Cookie->check('Payment')){
                $this->request->data = array('Payment' => $this->Cookie->read('Payment'));
            }
            if(empty($this->request->data['Payment']) && $this->Session->check('Auth.User.id')){
                $Payment = $this->ModOrder->find('first', array('conditions' => array('ModOrder.userid' => $this->Session->read('Auth.User.id')), 'order' => array('ModOrder.created' => 'desc')));
                if(!empty($Payment)) $this->request->data['Payment'] = $Payment['ModOrder']['data'];
            }
        }

        $this->set('basket', $basket);
        $this->set('checkout', $this->Cookie->read('Checkout'));
    }


    public function payment_type($customer_type = null){

        $conditions = array();

        if($customer_type > 0){
            $conditions['ObjItemList.extra_1'] = array('0', '2');
        } else {
            $conditions['ObjItemList.extra_1'] = array('0', '1');
        }

        $payments = $this->ObjItemList->find('all', array('tid' => 'payment', 'conditions' => $conditions, 'order' => array('ObjItemList.order_id' => 'asc')));

        $items = array();
        foreach($payments as $payment){
            $item = array('id' => $payment['ObjItemList']['id'], 'title' => $payment['ObjItemList']['title'], 'short_body' => $payment['ObjItemList']['short_body']);
            $items[] = $item;
        }

        $this->set('items', $items);
    }

    public function order(){
        $basket = $this->ModOrder->get_data($this->Cookie->read('Basket'));
        if(empty($basket['qnt'])) $this->redirect('/shop/basket/index/');

        if(!empty($basket['order_id'])) $this->ModOrder->delete($basket['order_id']);

        $this->ModOrder->create();
        $this->ModOrder->save(array(
            'userid' => $this->Session->read('Auth.User.id'),
            'quantity' => $basket['qnt'],
            'price' => $basket['price'],
            'currency' => $basket['currency'],
            'paystatus' => $this->Cookie->check('Payment.payment') ? '0' : ($basket['price'] > 0 ? '2' : '1'),
            'data' => am(ws_htmlspecialchars(array('data_checkout' => $this->Cookie->read('Checkout'), 'data_payment' => $this->Cookie->read('Payment'), 'data_shipping' => $basket['options']['shipping'])), array('userip' => $this->request->clientIp(), 'payment' => null))
        ));

        $order_id = $this->ModOrder->getLastInsertId();
        $this->___save_items($basket, $order_id);

        if($this->Cookie->check('Payment.payment')){
            $this->redirect(array('action' => 'pay', $this->Cookie->read('Payment.payment'), $order_id));
        } else {
            $this->getEventManager()->dispatch(new CakeEvent('Order.success_admin', null, array('order_id' => $order_id)));
            $this->getEventManager()->dispatch(new CakeEvent('Order.success', null, array('order_id' => $order_id, 'email' => $this->Cookie->read('Checkout.email'))));
            $this->redirect(array('action' => 'order_success', $order_id));
        }
    }

    public function save($order_id = null){

        if(!$this->Session->check('Auth.User.id')) $this->redirect('/users/login');

        if(!empty($order_id)){
            $order = $this->ModOrder->find('first', array('conditions' => array('ModOrder.id' => $order_id, 'ModOrder.paystatus' => '99', 'ModOrder.userid' => $this->Session->read('Auth.User.id'))));

            $basket = array();
            $basket['order_id'] = $order['ModOrder']['id'];
            $basket['qnt'] = $order['ModOrder']['quantity'];
            $basket['price'] = $order['ModOrder']['price'];
            $basket['currency'] = $order['ModOrder']['currency'];

            foreach($order['ModOrderItem'] as $item){
                $basket['items'][$item['item_id']] = $item;
            }

            //$basket = $this->ModOrder->get_data($basket);
            $this->Cookie->write('Basket', $basket);

            $this->redirect('/shop/basket/index/');
        }

        $basket = $this->ModOrder->get_data($this->Cookie->read('Basket'));
        if(empty($basket['qnt'])) $this->redirect('/shop/basket/index/');

        $this->ModOrder->create();
        $this->ModOrder->save(array(
            'userid' => $this->Session->read('Auth.User.id'),
            'quantity' => $basket['qnt'],
            'price' => $basket['price'],
            'currency' => $basket['currency'],
            'paystatus' => '99',
            'data' => am(ws_htmlspecialchars(array('data_checkout' => $this->Cookie->read('Checkout'), 'data_payment' => $this->Cookie->read('Payment'), 'data_shipping' => $basket['options']['shipping'])), array('userip' => $this->request->clientIp(), 'payment' => null))
        ));

        $order_id = $this->ModOrder->getLastInsertId();

        $this->___save_items($basket, $order_id);

        if($this->Cookie->check('Basket')){
            $this->Cookie->delete('Basket');
            $this->redirect($this->here);
        }

        $this->Session->setFlash(___('Order was saved successfull.'), 'flash');
        $this->redirect('/shop/order/history');
    }

    public function order_success($order_id = null){
        $this->Basic->template(array('title' => ___('Order'), 'alias' => $this->here));

        if($this->Cookie->check('Basket')){
            $this->Cookie->delete('Basket');
            $this->redirect($this->here);
        }

        $order = $this->ModOrder->findById($order_id);
        $payment = $this->ObjItemList->find('first', array('tid' => 'payment', 'conditions' => array('ObjItemList.id' => $order['ModOrder']['payment'])));

        preg_match_all("/\{(.*?)\}/", $payment['ObjItemList']['body'], $matches);
        if(!empty($matches[1])) foreach($matches[1] as $match){
            $fields = explode(".", $match);
            if($fields[0] == 'order'){
                $payment['ObjItemList']['body'] = str_replace("{{$match}}", $order['ModOrder'][$fields[1]], $payment['ObjItemList']['body']);
            }
        }

        //$this->getEventManager()->dispatch(new CakeEvent('Order.success', null, array('order_id' => $order_id, 'email' => $this->Cookie->read('Checkout.email'))));

        $this->set(compact('order', 'payment'));
    }

    public function pay($payment_id = null, $order_id = null, $transaction_id = null){
        if($transaction_id) $transaction = Classregistry::init('Payment.ModTransaction')->find('first', array('st_cond' => '1', 'tid' => false, 'conditions' => array('ModTransaction.code' => $transaction_id)));
        $order = $this->ModOrder->findById($order_id);
        $payment = $this->ObjItemList->find('first', array('tid' => 'payment', 'conditions' => array('ObjItemList.id' => $payment_id)));

        if(!empty($order['ModOrder']['payment']) && !empty($order['ModOrder']['data']['pay_data']) && 1==1) exit;

        if(!empty($this->request->data) && (empty($order['ModOrder']['data']['pay_data']) || 1==2)){

            $pay_data = $this->request->data;

            if(!empty($_FILES)){
                foreach($_FILES as $file_name => $file){
                    $file_name = str_replace('_', ' ', $file_name);
                    $pay_data_files['files'][$file_name]['name'] = $file['name'];
                    $pay_data_files['files'][$file_name]['type'] = $file['type'];
                    $pay_data_files['files'][$file_name]['base64'] = base64_encode(file_get_contents($file['tmp_name']));
                }
            }

            $ModOrder_data = $order['ModOrder']['data'];
            $ModOrder_data['pay_data'] = ws_htmlspecialchars($pay_data);

            $ModOrder_long_data = $order['ModOrder']['long_data'];
            $ModOrder_long_data['pay_data'] = ws_htmlspecialchars($pay_data_files);

            $this->ModOrder->updateAll(
                array('ModOrder.data' => sqls(json_encode($ModOrder_data), true), 'ModOrder.long_data' => sqls(json_encode($ModOrder_long_data), true)),
                array("ModOrder.id" => $order_id)
            );

            if(!empty($pay_data['amount'])){
                $this->ModOrderItem->create();
                $this->ModOrderItem->save(array(
                    'order_id' => $order_id,
                    'item_id' => $payment_id,
                    'type' => 'payment',
                    'title' => 'Credit Payment Form',
                    'price' => $pay_data['amount'],
                    'currency' => $order['ModOrder']['currency'],
                    'quantity' => '1',
                    'ext' => 'info',
                    'price_total' => $pay_data['amount']
                ));
            }

            $this->redirect(array('action' => 'order_success', $order_id));
        } else if($this->Session->check('Auth.User.id')){
            $last_pay_data = $this->ModOrder->find('first', array('conditions' => array('ModOrder.payment' => $payment['ObjItemList']['id'], 'ModOrder.userid' => $this->Session->read('Auth.User.id'), "ModOrder.data LIKE '%\"pay_data\":%'"), 'order' => array('ModOrder.created' => 'desc')));
            $this->set('pay_data', $last_pay_data['ModOrder']['data']['pay_data']);
        }

        // TRANSLATE UPDATE
        $this->ModOrder->updateAll(
            array('ModOrder.payment' => sqls($payment['ObjItemList']['id'], true)),
            array("ModOrder.id" => $order_id)
        );

        if($payment['ObjItemList']['code'] == 'custom' || $transaction_id){
            $this->ModOrder->updateAll(
                array('ModOrder.paystatus' => '2'),
                array("ModOrder.id" => $order_id)
            );

            $this->Basic->template(array('title' => ___('Payment'), 'alias' => $this->here));

            if($this->Cookie->check('Basket')){
                $this->Cookie->delete('Basket');
                $this->redirect($this->here);
            }

            preg_match_all("/\{(.*?)\}/", $payment['ObjItemList']['body'], $matches);
            if(!empty($matches[1])) foreach($matches[1] as $match){
                $fields = explode(".", $match);
                if($fields[0] == 'order'){
                    $payment['ObjItemList']['body'] = str_replace("{{$match}}", $order['ModOrder'][$fields[1]], $payment['ObjItemList']['body']);
                }
            }

            $this->set(compact('order', 'payment'));

            if($transaction_id) $this->set('transaction', $transaction);

            $this->getEventManager()->dispatch(new CakeEvent('Order.success_admin', null, array('order_id' => $order_id, 'payment_id' => $payment_id)));
            $this->getEventManager()->dispatch(new CakeEvent('Order.success', null, array('order_id' => $order_id, 'payment_id' => $payment_id, 'email' => $order['ModOrder']['data']['data_checkout']['email'])));

            $pay_ctp = ($payment['ObjItemList']['code'] != 'custom' ? 'payment_' . $payment['ObjItemList']['code'] : 'payment_' . $payment_id);

            if(file_exists(EXT_VIEWS . DS . 'Actions' . DS . 'shop_checkout' . DS . $pay_ctp . '.ctp')){
                $this->render($pay_ctp);
            } else {
                $this->redirect(array('action' => 'order_success', $order_id));
            }

        } else {
            //$transaction_id = uniqid(rand());
            $transaction_id = hexdec(uniqid());

            $payment_cfg = Configure::read('CMS.payment_type.' . $payment['ObjItemList']['code']);
            if(!empty($payment_cfg['currency'])){
                $currencies = Configure::read('Obj.currencies_vals');
                $order['ModOrder']['price'] = round(($order['ModOrder']['price'] * $currencies[$order['ModOrder']['currency']]) / $currencies[$payment_cfg['currency']], 2);
                $order['ModOrder']['currency'] = $payment_cfg['currency'];
            }

            Classregistry::init('Payment.ModTransaction')->create();
            Classregistry::init('Payment.ModTransaction')->save(array(
                'code' => $transaction_id,
                'pay_type' => $payment['ObjItemList']['code'],
                'description' => ___('Payment for order', true) . ' #' . $order_id,
                'amount' => $order['ModOrder']['price'],
                'currency' => $order['ModOrder']['currency'],
                'extra_id' => $order_id,
                'data' => array(
                    'back_url' => '/shop/checkout/pay_success/' . $transaction_id,
                    'fail_url' => '/shop/checkout/fail/'
                )
            ));

            $this->redirect('/payment/pay/' . $transaction_id);
        }
    }

    public function pay_success($transaction_id){
        $transaction = Classregistry::init('Payment.ModTransaction')->find('first', array('st_cond' => '1', 'tid' => false, 'conditions' => array('ModTransaction.code' => $transaction_id)));
        if($transaction['ModTransaction']['status'] == '1' && $transaction['ModTransaction']['used'] != '1'){
            $this->ModOrder->updateAll(
                array('ModOrder.paystatus' => '1', 'ModOrder.transaction_id' => $transaction['ModTransaction']['id']),
                array("ModOrder.id" => $transaction['ModTransaction']['extra_id'])
            );
            Classregistry::init('Payment.ModTransaction')->updateAll(
                array('ModTransaction.used' => '1'),
                array("ModTransaction.id" => $transaction['ModTransaction']['id'])
            );
            $order = $this->ModOrder->findById($transaction['ModTransaction']['extra_id']);
            $this->getEventManager()->dispatch(new CakeEvent('Order.success_admin', null, array('order_id' => $transaction['ModTransaction']['extra_id'])));
            $this->getEventManager()->dispatch(new CakeEvent('Order.success', null, array('order_id' => $transaction['ModTransaction']['extra_id'], 'email' => $order['ModOrder']['data']['data_checkout']['email'])));
			$this->getEventManager()->dispatch(new CakeEvent('Order.pay_success', null, array('order_id' => $transaction['ModTransaction']['extra_id'], 'email' => $order['ModOrder']['data']['data_checkout']['email'])));
        } else if($transaction['ModTransaction']['status'] != '1'){
            exit('<p style="text-align:center;"><h2>Please wait...</h2></p><script>window.setTimeout(function(){location.reload()},3000)</script>');
        }
        if(empty($this->request->params['requested'])){
            $this->redirect(array('action' => 'order_success', $transaction['ModTransaction']['extra_id']));
        } else {
            return;
        }

    }

    public function fail(){

    }

    public function fast($item_id = null){
        if(!empty($this->data)){

            if(empty($item_id)) $item_id = $this->data['item_id'];

            if(empty($this->data['name'])) exit(___('Introduceti numele'));
            if(empty($this->data['phone'])) exit(___('Introduceti telefonul'));
            if(empty($item_id)) exit(___('Selectati produsul'));


            if($this->data['ajx_validate'] != '1'){

                $basket['items'][uniqid()] = array('id' => $item_id, 'qnt' => 1);

                $basket = $this->ModOrder->get_data($basket);

                $this->ModOrder->create();
                $this->ModOrder->save(array(
                    'userid' => $this->Session->read('Auth.User.id'),
                    'quantity' => '1',
                    'price' => $basket['price'],
                    'currency' => $basket['currency'],
                    'paystatus' => ($basket['price'] > 0 ? '2' : '1'),
                    'data' => am(ws_htmlspecialchars(array('data_checkout' => array('name' => $this->data['name'], 'phone' => $this->data['phone'], 'email' => $this->data['email'], 'lang' => Configure::read('Config.language')))), array('userip' => $this->request->clientIp(), 'payment' => null, 'fast' => '1'))
                ));

                $order_id = $this->ModOrder->getLastInsertId();

                $this->___save_items($basket, $order_id);

                $this->getEventManager()->dispatch(new CakeEvent('Order.success_admin', null, array('order_id' => $order_id)));
                if(!empty($this->data['email'])) $this->getEventManager()->dispatch(new CakeEvent('Order.success', null, array('order_id' => $order_id, 'email' => $this->data['email'])));


                $this->Session->setFlash(___('Order was send successfull.'), 'flash');
                //$this->redirect($this->request->referer(true));
            }
            exit('OK');
        }
    }


    function ___save_items($basket, $order_id){
        foreach($basket['items'] as $id => $item){
            $this->ModOrderItem->create();
            $this->ModOrderItem->save(array(
                'order_id' => $order_id,
                'item_id' => $item['data']['ObjItemList']['id'],
                'rel_item_id' => (!empty($item['related']) ? $rel_item_id : ''),
                'type' => 'item',
                'title' => $item['data']['ObjItemList']['title'],
                'code' => $item['data']['ObjItemList']['code'],
                'weight' => $item['data']['ObjItemList']['data']['weight'],
                'price' => $item['price'],
                'currency' => $item['currency'],
                'quantity' => $item['qnt'],
                'ext' => $item['ext'],
                'price_total' => $item['price_total'],
                'data' => $item['ext_data']
            ));
            $rel_item_id = $this->ModOrderItem->getLastInsertId();

            if(Configure::read('CMS.settings.catalog.obj_qnt') == '1'){
                $this->ObjItemList->updateAll(
                    array('ObjItemList.qnt' => 'ObjItemList.qnt - ' . (int)$item['qnt']),
                    array("ObjItemList.id" => $item['data']['ObjItemList']['id'])
                );
            }
        }

        if(!empty($basket['extra'])) foreach($basket['extra'] as $type => $item){
            $this->ModOrderItem->create();
            $this->ModOrderItem->save(array(
                'order_id' => $order_id,
                'item_id' => $item['item_id'],
                'type' => $type,
                'title' => $item['title'],
                'code' => $item['code'],
                'price' => $item['price'],
                'currency' => $item['currency'],
                'quantity' => '1',
                'ext' => $item['ext'],
                'price_total' => $item['price'],
                'data' => $item['ext_data']
            ));
        }
    }

    public function credit(){
        $this->Basic->template(array('title' => ___('Credit'), 'alias' => $this->here));

        $basket = $this->ModOrder->get_data($this->Cookie->read('Basket'));
        $this->set('basket', $basket);

        $transaction_id = $_GET['code'];
        $transaction = Classregistry::init('Payment.ModTransaction')->find('first', array('st_cond' => '1', 'tid' => false, 'conditions' => array('ModTransaction.code' => $transaction_id)));
        if($transaction['ModTransaction']['used'] != '1'){
            $order = $this->ModOrder->findById($transaction['ModTransaction']['extra_id']);
            $order_id = $order['ModOrder']['id'];
        } else {
            $this->redirect('/');
        }

        if(!empty($this->request->data)){

            $pay_data = $this->request->data;

            //_pr($order);
            //_pr($transaction);
            //_pr($pay_data);
            //exit;

            if(!empty($_FILES)){
                foreach($_FILES as $file_name => $file){
                    $file_name = str_replace('_', ' ', $file_name);
                    $pay_data_files['files'][$file_name]['name'] = $file['name'];
                    $pay_data_files['files'][$file_name]['type'] = $file['type'];
                    $pay_data_files['files'][$file_name]['base64'] = base64_encode(file_get_contents($file['tmp_name']));
                }
            }

            $ModOrder_data = $order['ModOrder']['data'];
            $ModOrder_data['pay_data']['dates'] = ws_htmlspecialchars($pay_data);

            $ModOrder_long_data = $order['ModOrder']['long_data'];
            $ModOrder_long_data['pay_data'] = ws_htmlspecialchars($pay_data_files);

            $this->ModOrder->updateAll(
                array('ModOrder.data' => sqls(json_encode($ModOrder_data), true), 'ModOrder.long_data' => sqls(json_encode($ModOrder_long_data), true)),
                array("ModOrder.id" => $order_id)
            );

            if(!empty($pay_data['amount'])){
                $this->ModOrderItem->create();
                $this->ModOrderItem->save(array(
                    'order_id' => $order_id,
                    'item_id' => $order['ModOrder']['payment'],
                    'type' => 'payment',
                    'title' => 'Credit',
                    'price' => $pay_data['amount'],
                    'currency' => $order['ModOrder']['currency'],
                    'quantity' => '1',
                    'ext' => 'info',
                    'price_total' => $pay_data['amount']
                ));
            }

            $this->redirect(array('action' => 'order_success', $order_id));
        }

        $credit['amount'] = $order['ModOrder']['price'];
        $credit['name'] = $order['ModOrder']['data']['data_checkout']['name'];
        $credit['phone'] = $order['ModOrder']['data']['data_checkout']['phone'];
        $credit['months'] = ($this->Session->check('Tmp.months') ? $this->Session->read('Tmp.months') : '6');

        $this->set('order', $order);
        $this->set('credit', $credit);
    }

    public function credit_amount(){
            if(!empty($_GET['item_id'])){
                $item = $this->ObjItemList->find('first', array('tid' => 'catalog', 'conditions' => array('ObjItemList.id' => $_GET['item_id'])));

                if(!empty($item['Relation']['extra_1'])) foreach(Configure::read('CMS.credit_types') as $id_type => $mnth_type){
                    if(in_array($id_type, $item['Relation']['extra_1'])){
                        $installment = $mnth_type;
                        break;
                        break;
                    }
                }

                if($installment > 0 && $_GET['months'] == $installment){
                    exit(round($_GET['amount']/$_GET['months'], 0));
                }
            }
        
            include_once(dirname(dirname(dirname(__FILE__))) . DS . 'Payment' . DS . 'Vendor' . DS . $_GET['payment'] . '.php');
            $payment_obj = new PaymentMake();
            $result = $payment_obj->calcs(array(
                'amount' => $_GET['amount'],
                'months' => $_GET['months'],
            ));
            exit(round($result, 0));
    }

    public function creditcalc($item_id = null){

        $item = $this->ObjItemList->find('first', array('tid' => 'catalog', 'conditions' => array('ObjItemList.id' => $item_id)));

        if(!empty($item['Relation']['extra_1'])) foreach(Configure::read('CMS.credit_types') as $id_type => $mnth_type){
            if(in_array($id_type, $item['Relation']['extra_1'])){
                $installment = $mnth_type;
                break;
                break;
            }
        }

        if(!empty($this->data)){
            $this->redirect('/shop/basket/add/'.$item_id.'/0/1');
            exit('OK');
        }

        $credit['amount'] = $item['Price']['value'];
        //$credit['months'] = ($this->Session->check('Tmp.months') ? $this->Session->read('Tmp.months') : '6');
        $credit['months'] = ($installment > 0 ? $installment : ($this->Session->check('Tmp.months') ? $this->Session->read('Tmp.months') : '6'));
        $credit['item_id'] = $item_id;

        $this->set('credit', $credit);

    }

}

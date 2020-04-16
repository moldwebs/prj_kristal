<?php
class PaymentController extends PaymentAppController {

    public $paginate = array(
        'ObjItemList' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemList.created' => 'desc'
            )
        ),
        'ModTransaction' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ModTransaction.id' => 'desc'
            ),
            'tid' => false
        ),
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        $payment_types = array('custom' => ___('Custom'));
        foreach(Configure::read('CMS.payment_type') as $pay_type => $data) $payment_types[$pay_type] = $data['title'];
        $this->set('payment_types', $payment_types);
    }

    public function admin_table_actions(){
        $this->ObjItemList->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Payment') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemList, true);
        
        if($id){
            $this->set('page_title', ___('Payment') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Payment') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
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

    public function admin_transactions(){
        $this->set('page_title', ___('Transaction') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ModTransaction', $this->Basic->filters('ModTransaction')));
    }

    
    public function pay($transaction_id = null){
        $transaction = $this->ModTransaction->find('first', array('st_cond' => '1', 'tid' => false, 'conditions' => array('ModTransaction.code' => $transaction_id)));
        $payment = $this->ObjItemList->find('first', array('tid' => 'payment', 'conditions' => array('ObjItemList.code' => $transaction['ModTransaction']['pay_type'])));

        if(!empty($payment) && !empty($transaction)){

            $payment_cfg = Configure::read('CMS.payment_type.' . $payment['ObjItemList']['code']);
            if(!empty($payment_cfg['currency']) && $payment_cfg['currency'] != $transaction['ModTransaction']['currency']){

                $this->ModTransaction->updateAll(
                    array('ModTransaction.orig_amount' => sqls($transaction['ModTransaction']['amount'], true), 'ModTransaction.orig_currency' => sqls($transaction['ModTransaction']['currency'], true)),
                    array("ModTransaction.code" => $transaction_id)
                );

                $currencies = Configure::read('Obj.currencies_vals');
                $transaction['ModTransaction']['amount'] = round(($transaction['ModTransaction']['amount'] * $currencies[$transaction['ModTransaction']['currency']]) / $currencies[$payment_cfg['currency']], 2);
                $transaction['ModTransaction']['currency'] = $payment_cfg['currency'];

                $this->ModTransaction->updateAll(
                    array('ModTransaction.amount' => sqls($transaction['ModTransaction']['amount'], true), 'ModTransaction.currency' => sqls($transaction['ModTransaction']['currency'], true)),
                    array("ModTransaction.code" => $transaction_id)
                );
            }

            include_once(dirname(dirname(__FILE__)) . DS . 'Vendor' . DS . $payment['ObjItemList']['code'] . '.php');
            
            $payment_obj = new PaymentMake();
            
            $payment_obj->makePayment(array(
                'pay' => $payment['ObjItemList']['data'][$payment['ObjItemList']['code']],
                'pay_id' => $payment['ObjItemList']['id'], 
                'transaction' => $transaction,
                'Checkout' => $this->Cookie->read('Checkout'),
            ));
        } else exit('ERROR');
        
        exit;
    }

    public function callback($pay_type = null){
        file_put_contents(LOGS . DS . 'pay_callback.log', "---{$_SERVER['REQUEST_URI']}---{$_SERVER['HTTP_REFERER']}---".date("Y-m-d H:i:s")."---\r\n" . print_r($_POST, true) . "\r\n", FILE_APPEND);
        $payment = $this->ObjItemList->find('first', array('tid' => 'payment', 'conditions' => array('ObjItemList.code' => $pay_type)));
        
        if(!empty($payment)){
            
            include_once(dirname(dirname(__FILE__)) . DS . 'Vendor' . DS . $payment['ObjItemList']['code'] . '.php');
            
            $payment_obj = new PaymentMake();

            if(method_exists($payment_obj, 'getTransId')){
                $trans_id = $payment_obj->getTransId();
                $transaction = $this->ModTransaction->find('first', array('st_cond' => '1', 'tid' => false, 'conditions' => array('ModTransaction.code' => $trans_id)));
            } else {
                $transaction = array();
            }
            
            
            $data = $payment_obj->checkPayment(array(
                'pay' => $payment['ObjItemList']['data'][$payment['ObjItemList']['code']],
                'pay_id' => $payment['ObjItemList']['id'], 
                'transaction' => $transaction, 
            ));
            
            if($data['status'] == '1'){
                $transaction = $this->ModTransaction->find('first', array('st_cond' => '1', 'tid' => false, 'conditions' => array('ModTransaction.code' => $data['id'])));
                if(($transaction['ModTransaction']['amount'] - $transaction['ModTransaction']['paid_amount']) <= $data['amount']){
                    $this->ModTransaction->updateAll(
                        array('ModTransaction.paid_amount' => ($transaction['ModTransaction']['paid_amount'] + $data['amount']), 'ModTransaction.paid_ext_id' => sqls($transaction['ModTransaction']['paid_ext_id'] . "[{$data['ext_id']},{$data['amount']}]", true), 'ModTransaction.status' => '1', 'ModTransaction.ext_id' => sqls($data['ext_id'], true)),
                        array("ModTransaction.code" => $data['id'])
                    );
                    $call = $this->requestAction($transaction['ModTransaction']['data']['back_url']);
                }
            } else if($data['status'] == '2'){
                $transaction = $this->ModTransaction->find('first', array('st_cond' => '1', 'tid' => false, 'conditions' => array('ModTransaction.code' => $data['id'])));
                $this->ModTransaction->updateAll(
                    array('ModTransaction.paid_amount' => ($transaction['ModTransaction']['paid_amount'] + $data['amount']), 'ModTransaction.paid_ext_id' => sqls($transaction['ModTransaction']['paid_ext_id'] . "[{$data['ext_id']},{$data['amount']}]", true)),
                    array("ModTransaction.code" => $data['id'])
                );
            }
            
            $payment_obj->afterPayment();
        } else exit('ERROR');
        
        exit;
    }
    
}

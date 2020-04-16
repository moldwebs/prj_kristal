<?php
class PaymentAppController extends AppController {
    
    public $uses = array('Payment.ModTransaction');
    
    public function beforeFilter() {
        Configure::write('Config.tid', 'payment');
        parent::beforeFilter();
    }
}

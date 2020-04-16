<?php
class CurrencyAppController extends AppController {
    
    public $uses = array('Currency.ModCurrency');
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->set('currencies', $this->ModCurrency->find('list', array('fields' => array('currency', 'title'), 'order' => array('is_default' => 'DESC'))));
    }
}

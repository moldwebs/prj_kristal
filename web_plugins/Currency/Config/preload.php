<?php
    $currencies = ClassRegistry::init('Currency.ModCurrency')->find('all', array('order' => array('is_default' => 'DESC')));
    if(!empty($currencies)){

        $this->set('sys_currency', ClassRegistry::init('Currency.ModCurrency')->find('allindex', array('order' => array('is_default' => 'DESC'))));
        
        foreach($currencies as $currency){
            $currencies_vals[$currency['ModCurrency']['currency']] = $currency['ModCurrency']['value'];
            $_currencies[$currency['ModCurrency']['currency']] = $currency['ModCurrency'];
        }
        Configure::write('Obj.currencies_vals', $currencies_vals);
        
        Configure::write('Obj.currency', $currencies[0]['ModCurrency']);
        
        if($this->Cookie->check('Toggle.currency')){
            foreach($currencies as $currency){
                if($currency['ModCurrency']['currency'] == $this->Cookie->read('Toggle.currency')){
                    Configure::write('Obj.currency', $currency['ModCurrency']);
                }
            }
        }

        Configure::write('Obj.currencies', $_currencies);
        
        $this->ObjItemList->Behaviors->load('Currency.Currency');
        $this->ObjItemListNull->Behaviors->load('Currency.Currency');
    } else {
        Configure::write('Obj.currencies', array(key(Configure::read('CMS.currency')) => array('currency' => key(Configure::read('CMS.currency')), 'title' => reset(Configure::read('CMS.currency')), 'value' => '1')));
        Configure::write('Obj.currency', array('currency' => key(Configure::read('CMS.currency')), 'title' => reset(Configure::read('CMS.currency')), 'value' => '1'));
        Configure::write('Obj.currencies_vals', array(key(Configure::read('CMS.currency')) => '1'));
    }

    $currencies = ClassRegistry::init('Currency.ModCurrency')->find('list', array('fields' => array('currency', 'title'), 'order' => array('is_default' => 'DESC')));
    $this->set('currencies', (!empty($currencies) ? $currencies : Configure::read('CMS.currency')));
    
    $this->cms['currency'] = Configure::read('Obj.currency');
?>
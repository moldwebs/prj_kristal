<?php
    $currencies = ClassRegistry::init('Currency.ModCurrency')->find('list', array('fields' => array('currency', 'title'), 'order' => array('is_default' => 'DESC')));
    $this->set('currencies', (!empty($currencies) ? $currencies : Configure::read('CMS.currency')));

    $currencies = ClassRegistry::init('Currency.ModCurrency')->find('all', array('order' => array('is_default' => 'DESC')));
    if(!empty($currencies)){

        Configure::write('Obj.currency', $currencies[0]['ModCurrency']);
        
        foreach($currencies as $currency){
            $currencies_vals[$currency['ModCurrency']['currency']] = $currency['ModCurrency']['value'];
            $_currencies[$currency['ModCurrency']['currency']] = $currency['ModCurrency'];
        }
        Configure::write('Obj.currencies_vals', $currencies_vals);
        Configure::write('Obj.currencies', $_currencies);
    }
        
    $this->ObjItemList->Behaviors->load('Currency.Currency');
?>
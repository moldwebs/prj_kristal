<?php
    $__data = file_get_contents('http://bnm.md/md/official_exchange_rates?get_xml=1&date=' . date("d.m.Y"));
    if(!empty($__data)){
        $_data = Xml::toArray(Xml::build($__data));
        Cache::write('valutes', $_data, 'short');
    }
    
    foreach($_data['ValCurs']['Valute'] as $__data){
        $data[$__data['CharCode']] = $__data;
    }

    $currencies = ClassRegistry::init('Currency.ModCurrency')->find('all', array('conditions' => array('ModCurrency.autoupdate' => '1'), 'order' => array('ModCurrency.is_default' => 'DESC')));
    if(!empty($currencies)){
        foreach($currencies as $currency) if(!empty($data[$currency['ModCurrency']['currency']]['Value'])){
            ClassRegistry::init('Currency.ModCurrency')->updateAll(
                array("ModCurrency.value" => sqls($data[$currency['ModCurrency']['currency']]['Value'], true)),
                array("ModCurrency.currency" => $currency['ModCurrency']['currency'])
            );
        }
    }
?>
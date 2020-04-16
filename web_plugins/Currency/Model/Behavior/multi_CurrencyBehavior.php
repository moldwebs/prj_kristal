<?php
class CurrencyBehavior extends ModelBehavior {

    public function setup(Model $model, $settings = array()) {
    	$this->settings[$model->alias] = $settings;
    }   

   public function beforeFind(Model $model, $query) {
        if(empty($model->hasOne['ObjOptPrice'])) return $query;
        
        $this->settings[$model->alias]['currencies_vals'] = Configure::read('Obj.currencies_vals');
        $this->settings[$model->alias]['currency'] = Configure::read('Obj.currency');
        $this->settings[$model->alias]['currencies'] = Configure::read('Obj.currencies');
        
        if(count($this->settings[$model->alias]['currencies_vals']) > 1){

            if(!empty($model->hasOne['ObjOptPrice'])) $model->virtualFields['price_conv'] = "((`ObjOptPrice`.`price` * ModCurrency.value) / {$this->settings[$model->alias]['currency']['value']})";

            if(empty($model->hasOne['ModCurrency'])) $model->bindModel(
                array('hasOne' => array(
                        'ModCurrency' => array(
                            'className' => 'Currency.ModCurrency',
                            'foreignKey' => false,
                            'conditions' => array("ModCurrency.currency = ObjOptPrice.currency"),
                            'dependent' => false,
                            'callbacks' => false,
                            'force' => '1'
                        )
                    ),
                ), false
            );

            if(!empty($query['order'])){
                $order = array();
                foreach($query['order'] as $key => $val){
                    if(is_array($val)){
                        foreach($val as $_key => $_val){
                            $good_key = $_key;
                            if($_key == 'ObjItemList.price') $good_key = ($_val == 'asc' ? 'ISNULL(ObjItemList__price_conv), ' : null) . 'ObjItemList.price_conv';
                            $order[$key][$good_key] = $_val;
                        }
                    } else {
                        $good_key = $key;
                        if($key == 'ObjItemList.price') $good_key = ($_val == 'asc' ? 'ISNULL(ObjItemList__price_conv), ' : null) . 'ObjItemList.price_conv';
                        $order[$good_key] = $val;
                    }
                }
                $query['order'] = $order;
            }
            

        } else {
            $model->virtualFields['price_conv'] = "`ObjOptPrice`.`price`";
        }
            
        return $query;
   }

    public function afterFind(Model $model, $results, $primary) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('CurrencyBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"))));
        if(empty($model->hasOne['ObjOptPrice'])) return $results;
        
        if(!empty($model->findQueryExtra)) $findQueryExtra = $model->findQueryExtra;

        if(!empty($results)) foreach($results as $key => $result){
            $results[$key][$model->alias]['price'] = $result['ObjOptPrice']['price'];
            $results[$key][$model->alias]['currency'] = $result['ObjOptPrice']['currency'];
            $results[$key][$model->alias]['data']['old_price'] = $result['ObjOptPrice']['old_price'];
        }

        $value_dec = Configure::read('CMS.currency_decimals');
        
        if(!empty($results)){
            $user_data = Configure::read('user_data');
            if(!empty($user_data['Relation']['customer'])) $customer_group = reset($user_data['Relation']['customer']);
            if(!empty($customer_group)){
                $customer_discount = ClassRegistry::init('Shop.ModDiscount')->findById($customer_group);
            }
            
            foreach($results as $key => $result){
                
                if(!empty($result[$model->alias]['price'])){
                
                    $make_discount = true;
                    
                    if(!empty($result['RelationValue']['promo_price'])){
                        $result[$model->alias]['data']['old_price'] = $result[$model->alias]['price'];
                        $result[$model->alias]['price'] = $result['RelationValue']['promo_price'];
                        $make_discount = false;
                    }
    
                    if($make_discount) if(!empty($customer_discount) && $customer_discount['ModDiscount']['discount'] > 0){
                        
                        if($customer_discount['ModDiscount']['discount_type'] == '%'){
                            $discount_value = round(($result[$model->alias]['price'] / 100) * $customer_discount['ModDiscount']['discount'], 2);
                        } else {
                            $discount_value = round(($customer_discount['ModDiscount']['discount'] * $this->settings[$model->alias]['currencies_vals'][$customer_discount['ModDiscount']['discount_type']]) /$this->settings[$model->alias]['currency']['value'], 2);
                        }
                        
                        $result[$model->alias]['price'] = $result[$model->alias]['price'] - $discount_value;
                        
                        if(!empty($result['RelationValue']['vendor_price'])){
                            list($vendor_price, $vendor_price_currency) = explode(':', reset($result['RelationValue']['vendor_price']));
                            $vendor_price = round(($vendor_price*$this->settings[$model->alias]['currencies_vals'][$vendor_price_currency])/$result['ModCurrency']['value'], 2);
                            if($result[$model->alias]['price'] < $vendor_price){
                                $result[$model->alias]['price'] = $vendor_price;
                            }
                        }
                    }
                    
                    if(!empty($findQueryExtra['discount'])){
                        if(!empty($findQueryExtra['discount'][$result[$model->alias]['id']])){
                            $result[$model->alias]['data']['old_price'] = $result[$model->alias]['price'];
                            $result[$model->alias]['price'] = ($result[$model->alias]['price'] / 100) * (100 - $findQueryExtra['discount'][$result[$model->alias]['id']]);
                            $results[$key][$model->alias]['data']['price_extra'] = 'discount';
                        }
                    }
                }
                
                if(Configure::read('CMS.currency_no_convert') == '1') $result['ModCurrency'] = array();
                
                if(!empty($result['ModCurrency'])){
                    
                    $currency_vals = array();
                    $currency_html_vals = array();
                    foreach($this->settings[$model->alias]['currencies'] as $currency){
                        if($currency['currency'] == $this->settings[$model->alias]['currency']['currency']) continue;
                        $currency_vals[$currency['currency']] = round((($result[$model->alias]['price']*$result['ModCurrency']['value'])/$currency['value']), 2);
                        $currency_html_vals[] = str_replace($value_dec_trim, '', number_format((($result[$model->alias]['price']*$result['ModCurrency']['value'])/$currency['value']), $value_dec, '.', ' ')) . ' ' . $currency['title'];
                    }
                    
                    $results[$key]['Price'] = array(
                        'value' => round(($result[$model->alias]['price']*$result['ModCurrency']['value'])/$this->settings[$model->alias]['currency']['value'], 2), 
                        'old' => round(($result[$model->alias]['data']['old_price']*$result['ModCurrency']['value'])/$this->settings[$model->alias]['currency']['value'], 2), 
                        'currency' => $this->settings[$model->alias]['currency']['currency'],
                        'currency_html_vals' => $currency_html_vals,
                        'currency_vals' => $currency_vals
                    );
                    $results[$key]['Price'] = ws_price_format($results[$key]['Price'], $this->settings[$model->alias]['currency']['title'], $value_dec);
                    /*
                    $results[$key]['Price']['html_value'] = str_replace($value_dec_trim, '', number_format($results[$key]['Price']['value'], $value_dec, '.', ' '));
                    $results[$key]['Price']['html_currency'] = $this->settings[$model->alias]['currency']['title'];
                    $results[$key]['Price']['html_old'] = str_replace($value_dec_trim, '', number_format($results[$key]['Price']['old'], $value_dec, '.', ' '));
                    */
                } else {
                    $results[$key]['Price'] = array(
                        'value' => $result[$model->alias]['price'], 
                        'old' => $result[$model->alias]['data']['old_price'], 
                        'currency' => $result[$model->alias]['currency']
                    );
                    $results[$key]['Price'] = ws_price_format($results[$key]['Price'], $this->settings[$model->alias]['currencies'][$result[$model->alias]['currency']]['title'], $value_dec);
                    /*
                    $results[$key]['Price']['html_value'] = str_replace($value_dec_trim, '', number_format($results[$key]['Price']['value'], $value_dec, '.', ' '));
                    $results[$key]['Price']['html_currency'] = $this->settings[$model->alias]['currencies'][$result[$model->alias]['currency']]['title'];
                    $results[$key]['Price']['html_old'] = str_replace($value_dec_trim, '', number_format($results[$key]['Price']['old'], $value_dec, '.', ' '));
                    */
                }
                if(!($results[$key]['Price']['value'] > 0) && !($results[$key]['Price']['old'] > 0) && $no_price_txt = Configure::read('CMS.settings.'.$result[$model->alias]['tid'].'.no_price_txt')){
                    $results[$key]['Price']['html_value'] = $no_price_txt;
                    $results[$key]['Price']['html_currency'] = '';
                }
            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('CurrencyBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"))));
        return $results;
    } 

}
?>
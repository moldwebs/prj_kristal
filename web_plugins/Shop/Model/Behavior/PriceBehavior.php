<?php
/*
class PriceBehavior extends ModelBehavior {

    public function setup(Model $model, $settings = array()) {
    	$this->settings[$model->alias] = $settings;
    }   

   public function beforeFind(Model $model, $query) {
        $this->settings[$model->alias]['currencies_vals'] = Configure::read('Obj.currencies_vals');
        $this->settings[$model->alias]['currency'] = Configure::read('Obj.currency');
        $this->settings[$model->alias]['currencies'] = Configure::read('Obj.currencies');
        
        if(count($this->settings[$model->alias]['currencies_vals']) > 1){
            
            if(!empty($query['order'])){
                $order = array();
                foreach($query['order'] as $key => $val){
                    if(is_array($val)){
                        foreach($val as $_key => $_val){
                            $good_key = $_key;
                            if($_key == 'ObjItemList.price') $good_key = 'ObjItemList.price_conv';
                            $order[$key][$good_key] = $_val;
                        }
                    } else {
                        $good_key = $key;
                        if($key == 'ObjItemList.price') $good_key = 'ObjItemList.price_conv';
                        $order[$good_key] = $val;
                    }
                }
                $query['order'] = $order;
            }
                                    
            $model->virtualFields['price_conv'] = "((`{$model->alias}`.`price` * ModCurrency.value) / {$this->settings[$model->alias]['currency']['value']})";
            
            $model->bindModel(
                array('hasMany' => array(
                		'ObjOptPrices' => array(
                            'className' => 'Currency.ObjOptPrice',
                            'foreignKey' => 'foreign_key',
                            'conditions' => array('ObjOptPrices.is_default <>' => '1'),
                            'dependent' => true,
                            'callbacks' => true
                		),
                    ),
                ), false
            );

            $model->bindModel(
                array('hasOne' => array(
                        'ModCurrency' => array(
                            'className' => 'Currency.ModCurrency',
                            'foreignKey' => false,
                            'conditions' => array("ModCurrency.currency = {$model->alias}.currency"),
                            'dependent' => false,
                            'callbacks' => false,
                            'force' => '1'
                        )
                    ),
                ), false
            );
        }
        
        return $query;
   }

    public function afterFind(Model $model, $results, $primary) {

        //$value_dec = 2;
        $value_dec = 0;

        if(!empty($results)){
            $user_data = Configure::read('user_data');
            if(!empty($user_data['Relation']['customer'])) $customer_group = reset($user_data['Relation']['customer']);
            if(!empty($customer_group)){
                $customer_discount = ClassRegistry::init('Shop.ModDiscount')->findById($customer_group);
            }
            
            
            foreach($results as $key => $result){
                if(Configure::read('CMS.currency_no_convert') == '1') $result['ModCurrency'] = array();
                if(!empty($result['ModCurrency'])){
                    $results[$key]['Price'] = array('value' => round(($result[$model->alias]['price']*$result['ModCurrency']['value'])/$this->settings[$model->alias]['currency']['value'], 2), 'old' => round(($result[$model->alias]['data']['old_price']*$result['ModCurrency']['value'])/$this->settings[$model->alias]['currency']['value'], 2), 'currency' => $this->settings[$model->alias]['currency']['currency']);
                    $results[$key]['Price']['html_value'] = str_replace('.00', '', number_format($results[$key]['Price']['value'], $value_dec, '.', ' '));
                    $results[$key]['Price']['html_currency'] = $this->settings[$model->alias]['currency']['title'];
                    $results[$key]['Price']['html_old'] = str_replace('.00', '', number_format($results[$key]['Price']['old'], $value_dec, '.', ' '));
                } else {
                    $results[$key]['Price'] = array('value' => $result[$model->alias]['price'], 'old' => $result[$model->alias]['data']['old_price'], 'currency' => $result[$model->alias]['currency']);
                    $results[$key]['Price']['html_value'] = str_replace('.00', '', number_format($results[$key]['Price']['value'], $value_dec, '.', ' '));
                    $results[$key]['Price']['html_currency'] = $this->settings[$model->alias]['currencies'][$result[$model->alias]['currency']]['title'];
                    $results[$key]['Price']['html_old'] = str_replace('.00', '', number_format($results[$key]['Price']['old'], $value_dec, '.', ' '));
                }
                if(!($results[$key]['Price']['value'] > 0) && $no_price_txt = Configure::read('CMS.settings.'.$result[$model->alias]['tid'].'.no_price_txt')){
                    $results[$key]['Price']['html_value'] = $no_price_txt;
                    $results[$key]['Price']['html_currency'] = '';
                } else if(!empty($customer_discount) && $customer_discount['ModDiscount']['discount'] > 0){
                    $results[$key]['Price']['html_real_value'] = $results[$key]['Price']['html_value'];
                    
                    if($customer_discount['ModDiscount']['discount_type'] == '%'){
                        $discount_value = round(($results[$key]['Price']['value'] / 100) * $customer_discount['ModDiscount']['discount'], 2);
                    } else {
                        $discount_value = round(($customer_discount['ModDiscount']['discount'] * $this->settings[$model->alias]['currencies_vals'][$customer_discount['ModDiscount']['discount_type']]) /$this->settings[$model->alias]['currency']['value'], 2);
                    }
                    
                    $results[$key]['Price']['value'] = $results[$key]['Price']['value'] - $discount_value;
                    $results[$key]['Price']['html_value'] = str_replace('.00', '', number_format($results[$key]['Price']['value'], $value_dec, '.', ' '));
                }
            }
        }
        return $results;
    } 

    public function __afterSave(Model $model, $created){
        $model->ObjOptPrice->deleteAll(array('ObjOptPrice.foreign_key' => $model->id, 'ObjOptPrice.is_default' => '1'));
        if(!empty($model->data['ObjOptPrice'])){
            $model->ObjOptPrice->create();
            $model->ObjOptPrice->save(array('foreign_key' => $model->id, 'is_default' => '1', 'old_value' => $model->data['ObjOptPrice']['old_value'], 'value' => $model->data['ObjOptPrice']['value'], 'currency' => $model->data['ObjOptPrice']['currency']));
        }
    	return true;
    }

}
*/
?>
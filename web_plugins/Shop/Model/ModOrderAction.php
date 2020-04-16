<?php
class ModOrderAction extends ShopAppModel {
    
    public $useTable = 'wb_mod_order_action';
    public $nocache = '1';
    
    public $actsAs = array('Translate' => array('message'));

    public function afterFind($results, $primary = false) {
        foreach($results as $key => $val){
            if(!empty($val[$this->alias]['data'])) {
                $results[$key][$this->alias]['data'] = json_decode($val[$this->alias]['data'], true);
            }
            if(!empty($val[$this->alias]['operator_id'])) {
                $results[$key][$this->alias]['Operator'] = ClassRegistry::init('Users.User')->findById($results[$key][$this->alias]['operator_id'])['User'];
            }
        }
        return $results;
    }    

    public function beforeSave($options = array()) {
        if(!empty($this->data[$this->alias]['data'])){
            $this->data[$this->alias]['data'] = json_encode($this->data[$this->alias]['data']);
        }
        return true;
    }

}
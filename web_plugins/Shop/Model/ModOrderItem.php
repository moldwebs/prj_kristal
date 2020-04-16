<?php
class ModOrderItem extends ShopAppModel {
    
    public $useTable = 'wb_mod_order_item';
    public $nocache = '1';
    
    public $actsAs = array('Translate' => array('title'));

    public function afterFind($results, $primary = false) {
        foreach($results as $key => $val){
            if(!empty($val[$this->alias]['data'])) {
                $results[$key][$this->alias]['data'] = json_decode($val[$this->alias]['data'], true);
            }

            if(!empty($results[$key][$this->alias]['data']['title_trnsl'][Configure::read('Config.language')])) {
                $results[$key][$this->alias]['title'] = $results[$key][$this->alias]['data']['title_trnsl'][Configure::read('Config.language')];
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
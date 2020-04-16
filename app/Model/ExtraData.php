<?php
class ExtraData extends AppModel {
    
    public $useTable = 'wb_extra_data';
    public $nocache = '1';
    public $st_cond = '1';

    public function afterFind($results, $primary = false) {
        foreach($results as $key => $val){
            if(!empty($val[$this->alias]['data'])) {
                $results[$key][$this->alias]['data'] = json_decode($val[$this->alias]['data'], true);
            }
        }
        return $results;
    }    

    public function beforeSave($options = array()) {
        if(!empty($this->data['ExtraData']['data'])){
            $this->data['ExtraData']['data'] = json_encode($this->data['ExtraData']['data']);
        }
        return true;
    }
    
}
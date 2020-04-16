<?php
class CmsSetting extends AppModel {
    
    public $useTable = 'wb_cms_settings';
    public $custom_cache = 'settings';
    
    public function get_list($plugin = null){
        if($plugin){
            $_results = $this->find('allc', array('conditions' => array("{$this->alias}.plugin" => $plugin)));
        } else {
            $_results = $this->find('allc');
        }
        
        $locale = Configure::read('Config.language');
        $results = array();
        foreach($_results as $result){
            if(substr($result[$this->alias]['value'], 0, 2) == '{"') $result[$this->alias]['value'] = json_decode($result[$this->alias]['value'], true);
            if(strpos($result[$this->alias]['option'], '__') > 0){
                $_locale = ltrim(strstr($result[$this->alias]['option'], '__'), '__');
                $_key = strstr($result[$this->alias]['option'], '__', true);
                if($plugin){
                    $results['Translates'][$this->alias][$_key][$_locale] = $result[$this->alias]['value'];
                    if($_locale == $locale && !empty($result[$this->alias]['value'])) $results[$this->alias][$_key] = $result[$this->alias]['value'];
                } else {
                    if($_locale == $locale && !empty($result[$this->alias]['value'])) $results[$result[$this->alias]['plugin']][$_key] = $result[$this->alias]['value'];
                }
            } else {
                if($plugin){
                    $results[$this->alias][$result[$this->alias]['option']] = $result[$this->alias]['value'];
                } else {
                    if(empty($results[$result[$this->alias]['plugin']][$result[$this->alias]['option']])) $results[$result[$this->alias]['plugin']][$result[$this->alias]['option']] = $result[$this->alias]['value'];
                }
            }
        }
        return $results;
    }
}
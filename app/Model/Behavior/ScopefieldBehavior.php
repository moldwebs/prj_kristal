<?php
class ScopefieldBehavior extends ModelBehavior {

    public function setup(Model $Model, $settings = array()) {
    	$this->settings[$Model->alias] = $settings;
        $this->settings[$Model->alias]['value'] = ($this->settings[$Model->alias]['value'] > 0 ? $this->settings[$Model->alias]['value'] : '0');
    }   
   
   public function beforeFind(Model $Model, $query) {
        if($query[$this->settings[$Model->alias]['field']] !== false){
            $query['conditions']["{$Model->alias}.{$this->settings[$Model->alias]['field']}"] = $this->settings[$Model->alias]['value'];
        }
        return $query;
   } 

	public function beforeSave(Model $Model) {
        if(empty($Model->data[$Model->alias][$this->settings[$Model->alias]['field']])){
            $Model->data[$Model->alias][$this->settings[$Model->alias]['field']] = $this->settings[$Model->alias]['value'];
        }
		return true;
	}
}
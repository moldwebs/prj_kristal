<?php
class FieldsBehavior extends ModelBehavior {

	public function setup(Model $model, $settings = array()) {

        $model->bindModel(
            array('hasMany' => array(
                    'ObjOptField' => array(
                        'className' => 'ObjOptField',
                        'foreignKey' => 'foreign_key',
                        'conditions' => array('ObjOptField.model' => $model->name),
                        'dependent' => true,
                        'callbacks' => true
                    )
                )
            ), false
        );
        
		$this->settings[$model->alias] = $settings;
	}  

   public function afterFind(Model $model, $results, $primary) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('FieldsBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        if(!empty($results)){
            foreach($results as $key => $result){
                if(!empty($result['ObjOptField'])){
                    foreach($result['ObjOptField'] as $_key => $_val){
                        $results[$key][$_val['model']][$_val['field']] = $_val['value'];
                        if(!empty($_val['Translates']['ObjOptField']['value'])){
                            foreach($_val['Translates']['ObjOptField']['value'] as $locale => $value){
                                $results[$key]['Translates'][$_val['model']][$_val['field']][$locale] = $value;
                            }
                        }
                    }
                    unset($results[$key]['ObjOptField']);
                }
                if(!empty($model->belongsTo)){
                    foreach($model->belongsTo as $assoc => $assocData){
                        if(!empty($result[$assoc]['ObjOptField'])){
                            foreach($result[$assoc]['ObjOptField'] as $_key => $_val){
                                $results[$key][$assoc][$_val['field']] = $_val['value'];
                            }
                            unset($results[$key][$assoc]['ObjOptField']);
                        }
                    }
                }
                if(!empty($model->hasOne)){
                    foreach($model->hasOne as $assoc => $assocData){
                        if(!empty($result[$assoc]['ObjOptField'])){
                            foreach($result[$assoc]['ObjOptField'] as $_key => $_val){
                                $results[$key][$assoc][$_val['field']] = $_val['value'];
                            }
                            unset($results[$key][$assoc]['ObjOptField']);
                        }
                    }
                }
            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('FieldsBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        return $results;
   } 

    public function afterSave(Model $model, $created){
        $model->ObjOptField->deleteAll(array('ObjOptField.model' => $model->alias, 'ObjOptField.foreign_key' => $model->id));
        foreach($model->data[$model->alias] as $key => $val){
            if(is_array($val)) continue;
            if(!isset($model->_schema[$key]) && (!empty($val) || !empty($model->data['Translates'][$model->alias][$key]))){
                $model->ObjOptField->create();
                $model->ObjOptField->save(array('ObjOptField' => array('model' => $model->alias, 'foreign_key' => $model->id, 'field' => $key, 'value' => $val), 'Translates' => array('ObjOptField' => array('value' => $model->data['Translates'][$model->alias][$key]))));
            }
        }
    	return true;
    }

}
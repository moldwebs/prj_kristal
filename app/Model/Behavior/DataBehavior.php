<?php
class DataBehavior extends ModelBehavior {

    public function setup(Model $model, $settings = array()) {
    
        $model->bindModel(
            array('hasMany' => array(
                    'ObjOptData' => array(
                        'className' => 'ObjOptData',
                        'foreignKey' => 'foreign_key',
                        'conditions' => array('ObjOptData.model' => $model->alias),
                        'dependent' => true,
                        'callbacks' => true,
                        'force' => '1'
                    )
                )
            ), false
        );
        
    	$this->settings[$model->alias] = $settings;
    }   
    
    public function afterFind(Model $model, $results, $primary) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('DataBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        if(!empty($results)){
            foreach($results as $key => $result){
                if(!empty($result['ObjOptData'][0]['data'])){
                    $results[$key][$result['ObjOptData'][0]['model']]['data'] = json_decode($result['ObjOptData'][0]['data'], true);
                    unset($results[$key]['ObjOptData']);
                }
                if(!empty($model->belongsTo)){
                    foreach($model->belongsTo as $assoc => $assocData){
                        if(!empty($result[$assoc]['ObjOptData'][0]['data'])){
                            $results[$key][$assoc]['data'] = json_decode($result[$assoc]['ObjOptData'][0]['data'], true);
                            unset($results[$key][$assoc]['ObjOptData']);
                        }
                    }
                }
                if(!empty($model->hasOne)){
                    foreach($model->hasOne as $assoc => $assocData){
                        if(!empty($result[$assoc]['ObjOptData'][0]['data'])){
                            $results[$key][$assoc]['data'] = json_decode($result[$assoc]['ObjOptData'][0]['data'], true);
                            unset($results[$key][$assoc]['ObjOptData']);
                        }
                    }
                }
            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('DataBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        return $results;
    } 

    public function afterSave(Model $model, $created){
        $model->ObjOptData->deleteAll(array('ObjOptData.model' => $model->alias, 'ObjOptData.foreign_key' => $model->id));
        if(!empty($model->data[$model->alias]['data'])){
            $model->ObjOptData->create();
            $model->ObjOptData->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'data' => json_encode($model->data[$model->alias]['data'])));
        }
    	return true;
    }
}
<?php
class RelationBehavior extends ModelBehavior {

    public function setup(Model $model, $settings = array()) {
    
        $model->bindModel(
            array('hasMany' => array(
                    'ObjOptRelation' => array(
                        'className' => 'ObjOptRelation',
                        'foreignKey' => 'foreign_key',
                        'order' => 'ObjOptRelation.rel_id ASC',
                        'conditions' => array('ObjOptRelation.model' => $model->alias),
                        'dependent' => true,
                        'callbacks' => true
                    )
                )
            ), false
        );
        
    	$this->settings[$model->alias] = $settings;
    }   

	public function beforeSave(Model $model) {
        if(!empty($model->data['Relation'])) foreach($model->data['Relation'] as $type => $rels){
            if(empty($model->data[$model->alias][$type])) $model->data[$model->alias][$type] = $rels[0];
        }
		return true;
	}
    
    public function afterFind(Model $model, $results, $primary) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('RelationBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        if(!empty($results)){
            foreach($results as $key => $result){
                if(!empty($result['ObjOptRelation'])){
                    foreach($result['ObjOptRelation'] as $rel){
                        if($rel['type'] == 'event_expire'){
                            $results[$key]['RelationEventExpire'][$rel['value']] = $rel['expire'];
                            continue;
                        }
                        if($rel['value'] != ''){
                            if(!empty($rel['rel_id'])){
                                $results[$key]['RelationValue'][$rel['type']][$rel['rel_id']] = (!empty($results[$key]['RelationValue'][$rel['type']][$rel['rel_id']]) ? am((is_array($results[$key]['RelationValue'][$rel['type']][$rel['rel_id']]) ? $results[$key]['RelationValue'][$rel['type']][$rel['rel_id']] : array($results[$key]['RelationValue'][$rel['type']][$rel['rel_id']])), array($rel['value'])) : $rel['value']);
                            } else {
                                $results[$key]['RelationValue'][$rel['type']] = (!empty($results[$key]['RelationValue'][$rel['type']][$rel['rel_id']]) ? am((is_array($results[$key]['RelationValue'][$rel['type']][$rel['rel_id']]) ? $results[$key]['RelationValue'][$rel['type']][$rel['rel_id']] : array($results[$key]['RelationValue'][$rel['type']][$rel['rel_id']])), array($rel['value'])) : $rel['value']);
                            }
                            
                        } else {
                            /*
                            if(empty($results[$key]['Relation'][$rel['type']])){
                                $results[$key]['Relation'][$rel['type']] = $rel['rel_id'];
                            } else {
                                if(!is_array($results[$key]['Relation'][$rel['type']])){
                                    $results[$key]['Relation'][$rel['type']] = array($results[$key]['Relation'][$rel['type']] => $results[$key]['Relation'][$rel['type']]);
                                }
                                $results[$key]['Relation'][$rel['type']][$rel['rel_id']] = $rel['rel_id'];
                            }
                            */
                            $results[$key]['Relation'][$rel['type']][$rel['rel_id']] = $rel['rel_id'];
                        }
                        if(!empty($rel['expire'])){
                            if(!empty($rel['rel_id'])){
                                $results[$key]['RelationExpire'][$rel['type']][$rel['rel_id']] = $rel['expire'];
                            } else {
                                $results[$key]['RelationExpire'][$rel['type']] = $rel['expire'];
                            }
                        }
                    }
                    unset($results[$key]['ObjOptRelation']);
                }
            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('RelationBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        return $results;
    } 

    public function afterSave(Model $model, $created){
        if(!empty($model->data['RelationRemove'])) foreach($model->data['RelationRemove'] as $type => $val){
            $model->ObjOptRelation->deleteAll(array('ObjOptRelation.model' => $model->alias, 'ObjOptRelation.foreign_key' => $model->id, 'ObjOptRelation.type' => $type));
        }
        
        if(!empty($model->data['Relation'])) foreach($model->data['Relation'] as $type => $rels){
            $model->ObjOptRelation->deleteAll(array('ObjOptRelation.model' => $model->alias, 'ObjOptRelation.foreign_key' => $model->id, 'ObjOptRelation.type' => $type));
            if(!empty($rels) && !is_array($rels)) $rels = array($rels);
            if(!empty($rels)) foreach($rels as $rel_id) if(!empty($rel_id)){
                $model->ObjOptRelation->create();
                $model->ObjOptRelation->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'type' => $type, 'rel_id' => $rel_id, 'expire' => (!empty($model->data['RelationExpire'][$type][$rel_id]) ? $model->data['RelationExpire'][$type][$rel_id] : null)));
            }
        }
        if(!empty($model->data['RelationValue'])) foreach($model->data['RelationValue'] as $type => $rels){
            $model->ObjOptRelation->deleteAll(array('ObjOptRelation.model' => $model->alias, 'ObjOptRelation.foreign_key' => $model->id, 'ObjOptRelation.type' => $type));
            if(is_array($rels)){
                foreach($rels as $rel_id => $rel_val){
                    if(is_array($rel_val)){
                        foreach($rel_val as $_rel_val) if($_rel_val != ''){
                            $model->ObjOptRelation->create();
                            $model->ObjOptRelation->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'type' => $type, 'rel_id' => $rel_id, 'value' => trim($_rel_val)));
                        }
                    } else if($rel_val != ''){
                        $model->ObjOptRelation->create();
                        $model->ObjOptRelation->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'type' => $type, 'rel_id' => $rel_id, 'value' => trim($rel_val)));
                    }
                }
            } else {
                $val = $rels;
                $model->ObjOptRelation->create();
                $model->ObjOptRelation->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'type' => $type, 'value' => trim($val), 'expire' => (!empty($model->data['RelationExpire'][$type]) ? $model->data['RelationExpire'][$type] : null)));
            }
        }
        if(!empty($model->data['RelationEventExpire'])) foreach($model->data['RelationEventExpire'] as $type => $val){
            $model->ObjOptRelation->deleteAll(array('ObjOptRelation.model' => $model->alias, 'ObjOptRelation.foreign_key' => $model->id, 'ObjOptRelation.type' => 'event_expire'));
            if(!empty($val)){
                $model->ObjOptRelation->create();
                $model->ObjOptRelation->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'type' => 'event_expire', 'value' => $type, 'expire' => $val));
            }
        }
    	return true;
    }
}
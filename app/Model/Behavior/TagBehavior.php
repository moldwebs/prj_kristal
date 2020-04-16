<?php
class TagBehavior extends ModelBehavior {

    public function setup(Model $model, $settings = array()) {
        
    	$this->settings[$model->alias] = $settings;
    }   
    
    public function afterFind(Model $model, $results, $primary) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('TagBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        if(!empty($results)){
            $find_tags = array();
            foreach($results as $key => $result){
                if(!empty($result['Relation']['tags'])) $find_tags = am($find_tags, $result['Relation']['tags']);
            }
            if(!empty($find_tags)){
                $tags = ClassRegistry::init('ObjOptTag')->find('list', array('conditions' => array('ObjOptTag.id' => $find_tags)));
                foreach($results as $key => $result){
                    if(!empty($result['Relation']['tags'])) foreach($result['Relation']['tags'] as $_key => $_val){
                        $results[$key]['Relation']['tags'][$_key] = $tags[$_val];
                    }
                    $results[$key]['Tags']['tags'] = json_encode(array_values($results[$key]['Relation']['tags']));
                }
            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('TagBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        return $results;
    } 

    public function afterSave(Model $model, $created){
        if(!empty($model->data['Tags'])) foreach($model->data['Tags'] as $type => $rels){
            $model->ObjOptRelation->deleteAll(array('ObjOptRelation.model' => $model->alias, 'ObjOptRelation.foreign_key' => $model->id, 'ObjOptRelation.type' => $type));
            if(!empty($rels)){
                foreach(json_decode($rels) as $_rel_id){
                    $rel_id = ClassRegistry::init('ObjOptTag')->insert_new(array('title' => $_rel_id));
                    $model->ObjOptRelation->create();
                    $model->ObjOptRelation->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'type' => $type, 'rel_id' => $rel_id));
                }
            }
        }
    	return true;
    }
}
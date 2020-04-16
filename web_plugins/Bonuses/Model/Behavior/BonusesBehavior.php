<?php
class BonusesBehavior extends ModelBehavior {

    public function setup(Model $model, $settings = array()) {
    	$this->settings[$model->alias] = $settings;
    }   

    public function afterFind(Model $model, $results, $primary) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('BonusesBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"))));
        $base_bonuses = array();
        if(!empty($results)){
            foreach($results as $key => $result){
                if($result[$model->alias]['tid'] != 'catalog') continue;
                if(empty($result['RelationValue']['bonuses']) && empty($result['RelationValue']['bonuses_prc'])){
                    if(!isset($base_bonuses['prc'][$result[$model->alias]['base_id']])){
                        $base_bonuses['prc'][$result[$model->alias]['base_id']] = $model->ObjOptRelation->field('value', array('model' => 'ObjItemTree', 'type' => 'bonuses_prc', 'foreign_key' => $result[$model->alias]['base_id']));
                    }
                    if(!isset($base_bonuses['val'][$result[$model->alias]['base_id']])){
                        $base_bonuses['val'][$result[$model->alias]['base_id']] = $model->ObjOptRelation->field('value', array('model' => 'ObjItemTree', 'type' => 'bonuses', 'foreign_key' => $result[$model->alias]['base_id']));
                    }
                    if(isset($base_bonuses['prc'][$result[$model->alias]['base_id']])){
                        $results[$key]['RelationValue']['bonuses_prc'] = $base_bonuses['prc'][$result[$model->alias]['base_id']];
                    }
                    if(isset($base_bonuses['val'][$result[$model->alias]['base_id']])){
                        $results[$key]['RelationValue']['bonuses'] = $base_bonuses['val'][$result[$model->alias]['base_id']];
                    }
                }
            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('BonusesBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"))));
        return $results;
    } 

}
?>
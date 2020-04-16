<?php
class CombinationBehavior extends ModelBehavior {

    public function setup(Model $model, $settings = array()) {
    
    	$this->settings[$model->alias] = $settings;
    }   

   public function beforeFind(Model $model, $query) {
        if($query['tid'] != 'catalog') return $query;

        if(Configure::read('is_admin') != '1'){
            if(!empty($query['conditions']['9999'])){
                foreach(array('4') as $fltr_tp){
                    foreach($query['conditions']['9999'][$fltr_tp] as $key => $val){
                        if(!(substr_count($val, 'specification') > 0)) continue;
                        $_val = str_replace("{$model->alias}.id IN", "Combination.id IN", $val);
                        $query['conditions']['9999'][$fltr_tp][$key] = "({$val} OR {$model->alias}.id IN (SELECT `Combination`.`rel_id` FROM `wb_obj_item_list` AS Combination WHERE Combination.status = '1' AND {$_val}))";
                    }
                }
            }
        } else {
            
            $model->virtualFields['combinations'] = "(SELECT COUNT(*) FROM wb_obj_item_list WHERE wb_obj_item_list.rel_id = ObjItemList.id)";
            
            if(!empty($query['conditions']['9999'])){
                
                $db = $model->getDataSource();
                $subQuery = $db->buildStatement(
                    array(
                        'fields'     => array('Combination.rel_id'),
                        'table'      => $db->fullTableName($model),
                        'alias'      => 'Combination',
                        'joins'      => $query['joins'],
                        'conditions' => $query['conditions']['9999']
                    ),
                    $model
                );
                $subQuery = str_replace($model->alias, 'Combination', $subQuery);
                $subQuery = str_replace("model = 'Combination'", "model = '{$model->alias}'", $subQuery);
                
                $query['conditions']['9999'] = array('OR' => array($query['conditions']['9999'], array("{$model->alias}.id IN ({$subQuery})")));
                
            }
        }
        
        return $query;
   }

   
    public function afterFind(Model $model, $results, $primary) {
        
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('CombinationBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"))));
        
        if($model->__tid != 'catalog') return $results;
        
        if(!empty($results) && (Configure::read('is_admin') != '1' || Configure::read('TMP.force_combinations') == '1') && Configure::read('TMP.no_combinations') != '1'){
            Configure::write('TMP.no_combinations', '1');
            
            $ids = Set::extract("/{$model->alias}/id", $results);
            if(!empty($ids)){
                $_combinations = Classregistry::init('ObjItemList')->find('all', array('conditions' => array("{$model->alias}.rel_id" => $ids)));
                foreach($_combinations as $combination){
                    $combinations[$combination[$model->alias]['rel_id']][$combination[$model->alias]['id']] = $combination;
                }
                foreach($results as $key => $result){
                    $comb_prices = array();
                    foreach($combinations[$result[$model->alias]['id']] as $_key => $_combination){
                        $combinations[$result[$model->alias]['id']][$_key][$model->alias]['combination_title'] = $_combination[$model->alias]['title'];
                        $combinations[$result[$model->alias]['id']][$_key][$model->alias]['title'] = "{$result[$model->alias]['title']} ({$_combination[$model->alias]['title']})";
                        if(empty($_combination['ObjOptAttachOriginal']['file'])) $combinations[$result[$model->alias]['id']][$_key]['ObjOptAttachOriginal'] = $result['ObjOptAttachDef'];
                        if(empty($_combination['Price']['value'])) $combinations[$result[$model->alias]['id']][$_key]['Price'] = $result['Price'];
                        if(empty($comb_prices['min']['Price']['value']) || $combinations[$result[$model->alias]['id']][$_key]['Price']['value'] < $comb_prices['min']['Price']['value']) $comb_prices['min']['Price'] = $combinations[$result[$model->alias]['id']][$_key]['Price'];
                        if(empty($comb_prices['max']['Price']['value']) || $combinations[$result[$model->alias]['id']][$_key]['Price']['value'] > $comb_prices['max']['Price']['value']) $comb_prices['max']['Price'] = $combinations[$result[$model->alias]['id']][$_key]['Price'];
                    }
                    if(!empty($combinations[$result[$model->alias]['id']])){
                        if(count($combinations[$result[$model->alias]['id']]) == 1 && 1==2){
                            $combination = reset($combinations[$result[$model->alias]['id']]);
                            $results[$key]['Price'] = $combination['Price'];
                            $results[$key][$model->alias]['id'] = $combination[$model->alias]['id'];
                            $results[$key][$model->alias]['code'] = $combination[$model->alias]['code'];
                            $results[$key][$model->alias]['title'] = $combination[$model->alias]['title'];
                        } else {
                            $results[$key]['Combinations'] = $combinations[$result[$model->alias]['id']];
                            if($comb_prices['min']['Price']['html_value'] != $comb_prices['max']['Price']['html_value']){
                                $results[$key]['Price']['html_value'] = "{$comb_prices['min']['Price']['html_value']} - {$comb_prices['max']['Price']['html_value']}";
                            } else {
                                $results[$key]['Price']['html_value'] = "{$comb_prices['min']['Price']['html_value']}";
                            }
                            $results[$key]['Price']['html_currency'] = $comb_prices['min']['Price']['html_currency'];
                            unset($results[$key]['Price']['currency_html_vals']);
                            unset($results[$key]['Price']['old']);
                        }
                        if(empty($results[$key]['ObjOptAttachDef']['attach'])){
                            foreach($combinations[$result[$model->alias]['id']] as $combination){
                                if(!empty($combination['ObjOptAttachOriginal']['attach'])){
                                    $results[$key]['ObjOptAttachDef'] = $combination['ObjOptAttachOriginal'];
                                    break;
                                } else if(!empty($combination['ObjOptAttachDef']['attach'])){
                                    //$results[$key]['ObjOptAttachDef'] = $combination['ObjOptAttachDef'];
                                    //break;
                                }
                            }
                        }
                    }
                }
            }
            
            Configure::write('TMP.no_combinations', '0');
        }
        
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('CombinationBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"))));
        
        return $results;
    }

    public function afterDelete(Model $model){
        $ids = $model->find('list', array('tid' => false, 'conditions' => array("{$model->alias}.rel_id" => $model->id)));
        if(!empty($ids)) foreach($ids as $key => $val){
            $model->delete($key);
        }
    }

}
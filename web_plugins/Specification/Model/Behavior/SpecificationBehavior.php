<?php
class SpecificationBehavior extends ModelBehavior {

    public function setup(Model $model, $settings = array()) {
    	$this->settings[$model->alias] = $settings;
    }   
    
    public function afterFind(Model $model, $results, $primary) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('SpecificationBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"))));
        
        if($model->__tid != 'catalog') return $results;
        
        if(!empty($this->settings[$model->alias]['tid'])){
            $tid = $this->settings[$model->alias]['tid'];
        } else {
            $tid = Configure::read('Config.tid');
        }
        
        if((Configure::read('is_admin') != '1' || Configure::read('TMP.force_combinations') == '1') || $this->settings[$model->alias]['admin'] == '1'){
        
            $load_specifications = array();
            if(!empty($results)){
                if($model->findQueryType == 'all' || Configure::read('TMP.force_all') == '1'){
                    foreach($results as $key => $result){
                        if(!empty($result['RelationValue']['specification'])){
                            foreach($result['RelationValue']['specification'] as $_key => $_val){
                                if(is_array($_val)){
                                    foreach($_val as $__val) $load_specifications[$_key][] = $__val;
                                } else {
                                    $load_specifications[$_key][] = $_val;
                                }
                            }
                        }
                    }
                    if(!empty($load_specifications)){
                        $specifications = Classregistry::init('Specification.Specification')->find('allindex', array('tid' => $tid . '_specification', 'conditions' => array('Specification.id' => array_keys($load_specifications), 'Specification.status' => '1'), 'order' => array('Specification.lft' => 'asc')));
                        $extract = array();
                        foreach($specifications as $specification){
                            if(in_array($specification['Specification']['extra_2'], array('6', '7', '9'))){
                                if(!empty($load_specifications[$specification['Specification']['id']])) $extract[] = array('SpecificationValue.base_id' => $specification['Specification']['id'], 'SpecificationValue.id' => $load_specifications[$specification['Specification']['id']]);
                            }
                        }
                        if(!empty($extract)) $specification_values = Classregistry::init('Specification.SpecificationValue')->find('list', array('tid' => $tid . '_specification', 'fields' => array('SpecificationValue.id', 'SpecificationValue.title', 'SpecificationValue.base_id'), 'conditions' => array('OR' => $extract), 'order' => array('SpecificationValue.order_id' => 'asc')));
                    }
                    
                    $specification_values_full = array();
                    
                    if(!empty($specifications)){
                        foreach($results as $key => $result){
                            
                            $results[$key]['ObjOptAttachOriginal'] = $results[$key]['ObjOptAttachDef'];
                            
                            if(!empty($result['RelationValue']['specification'])){
                                $img_specif = false;
                                foreach($result['RelationValue']['specification'] as $_key => $_val){
                                    if(!empty($result['ObjItemList']['rel_id']) && $specifications[$_key]['Specification']['extra_2'] == '9' && (Configure::read('is_admin') != '1' || Configure::read('TMP.force_combinations') == '1')){
                                        if(empty($specification_values_full[$_val])){
                                            $specification_values_full[$_val] = Classregistry::init('Specification.SpecificationValue')->find('first', array('conditions' => array('SpecificationValue.id' => $_val)));
                                        }
                                        if(!empty($specification_values_full[$_val]['ObjOptAttachDef']['file'])){
                                            $results[$key]['ObjOptAttachDef'] = $specification_values_full[$_val]['ObjOptAttachDef'];
                                            $results[$key]['ObjOptAttachColor'] = $specification_values_full[$_val]['ObjOptAttachDef'];
                                            $results[$key]['specification_img'][$_key] = $specification_values_full[$_val]['ObjOptAttachDef'];
                                        } else if(!empty($specification_values_full[$_val]['SpecificationValue']['data']['img_color'])){
                                            $results[$key]['ObjOptAttachDef'] = array(
                                                'attach' => 'get_color_' . strtolower($specification_values_full[$_val]['SpecificationValue']['data']['img_color']) . '.png',
                                                'file' => 'get_color_' . strtolower($specification_values_full[$_val]['SpecificationValue']['data']['img_color']) . '.png'
                                            );
                                            $results[$key]['ObjOptAttachColor'] = array(
                                                'attach' => 'get_color_' . strtolower($specification_values_full[$_val]['SpecificationValue']['data']['img_color']) . '.png',
                                                'file' => 'get_color_' . strtolower($specification_values_full[$_val]['SpecificationValue']['data']['img_color']) . '.png'
                                            );
                                            $results[$key]['specification_img'][$_key] = array(
                                                'attach' => 'get_color_' . strtolower($specification_values_full[$_val]['SpecificationValue']['data']['img_color']) . '.png',
                                                'file' => 'get_color_' . strtolower($specification_values_full[$_val]['SpecificationValue']['data']['img_color']) . '.png'
                                            );
                                        }
                                    }
                                    
                                    if($specifications[$_key]['Specification']['extra_2'] == '3'){
                                        $_val = ($_val == '1' ? ___('Yes') : ($_val == '0' ? ___('No') : null));
                                    } else if(!empty($specification_values[$_key])){
                                        if(is_array($_val)){
                                            $_val_a = array();
                                            foreach($specification_values[$_key] as $sv_k => $sv_v){
                                                if(in_array($sv_k, $_val)) $_val_a[] = $sv_v;
                                            }
                                            /*
                                            foreach($_val as $__val){
                                                $_val_a[] =  $specification_values[$_key][$__val];
                                            }
                                            */
                                            $_val = implode(', ', $_val_a);
                                        } else {
                                            $_val = $specification_values[$_key][$_val];
                                        }
                                    }
                                    
                                    if(!empty($specifications[$_key]['Specification']['measure'])){
                                        $_val .= ' ' . $specifications[$_key]['Specification']['measure'];
                                    }
                                    
                                    $results[$key]['Specification'][$specifications[$_key]['Specification']['title']] = $_val;
                                    if($specifications[$_key]['Specification']['data']['in_desc'] == '1'){
                                        $results[$key]['specification_desc'][] = $specifications[$_key]['Specification']['title'] . ': ' . $_val;
                                        $results[$key]['specifications_desc'][$specifications[$_key]['Specification']['title']] = $_val;
                                    }
                                    $results[$key]['specifications_vals'][$specifications[$_key]['Specification']['id']] = $_val;
                                    
                                    if($specifications[$_key]['Specification']['data']['is_title'] != '1') $results[$key]['specification_all'][] = ($specifications[$_key]['Specification']['extra_2'] == '3' ? $specifications[$_key]['Specification']['title'] : $specifications[$_key]['Specification']['title'] . ': ' . $_val);
                                }
                                if(!empty($result[$model->alias]['rel_id'])){
                                    if(empty($result[$model->alias]['title'])){
                                        $results[$key][$model->alias]['title'] = implode(', ', $results[$key]['specifications_vals']);
                                    }
                                }
                                
                                if(empty($result[$model->alias]['list_body'])){
                                    $results[$key][$model->alias]['list_body'] = implode(', ', $results[$key]['specification_desc']);
                                    $results[$key]['Translates'][$model->alias]['list_body'][Configure::read('Config.language')] = implode(', ', $results[$key]['specification_desc']);
                                }
                            }
                        }
                    }
                } else if($model->findQueryType == 'first'){
                    $result = $results[0];
    
                    $bases = am(array('NULL'), Set::extract('/Specification/id', Classregistry::init('Specification.Specification')->getPath($result[$model->alias]['base_id'], 'id')));
    
                    $specifications = Classregistry::init('Specification.Specification')->find('allindex', array('tid' => $tid . '_specification', 'conditions' => array('OR' => array('Specification.extra_3' => $bases, "Specification.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'Specification' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', $bases)."))", "Specification.parent_id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'Specification' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', $bases)."))"), 'Specification.status' => '1'), 'order' => array('FIELD(Specification.extra_3, '.sqlimplode(',', $bases).')', 'Specification.lft')));                
                    
                    $extract = array();
                    foreach($specifications as $specification){
                        if(in_array($specification['Specification']['extra_2'], array('6', '7', '9'))){
                            if(!empty($result['RelationValue']['specification'][$specification['Specification']['id']])) $extract[] = array('SpecificationValue.base_id' => $specification['Specification']['id'], 'SpecificationValue.id' => $result['RelationValue']['specification'][$specification['Specification']['id']]);
                        }
                    }
                    if(!empty($extract)) $specification_values = Classregistry::init('Specification.SpecificationValue')->find('list', array('tid' => $tid . '_specification', 'fields' => array('SpecificationValue.id', 'SpecificationValue.title', 'SpecificationValue.base_id'), 'conditions' => array('OR' => $extract), 'order' => array('SpecificationValue.order_id' => 'asc')));
                    
                    if(!empty($specifications)){
                        foreach($specifications as $specification){
                            if($specification['Specification']['extra_1'] == '1'){
                                $result['Specifications'][$specification['Specification']['id']]['title'] = $specification['Specification']['title'];
                                $result['Specification'][] = $specification['Specification']['title'];
                            } else {
                                if(isset($result['RelationValue']['specification'][$specification['Specification']['id']])){
                                    
                                    $_val = $result['RelationValue']['specification'][$specification['Specification']['id']];
                                    
                                    if($specification['Specification']['extra_2'] == '3'){
                                        if($_val != '1') continue;
                                        $_val = ($_val == '1' ? ___('Yes') : ($_val == '0' ? ___('No') : null));
                                    } else if(!empty($specification_values[$specification['Specification']['id']])){
                                        if(is_array($_val)){
                                            $_val_a = array();
                                            foreach($specification_values[$specification['Specification']['id']] as $sv_k => $sv_v){
                                                if(in_array($sv_k, $_val)) $_val_a[] = $sv_v;
                                            }
                                            /*
                                            foreach($_val as $__val){
                                                $_val_a[] = $specification_values[$specification['Specification']['id']][$__val];
                                            }
                                            */
                                            $_val = implode(', ', $_val_a);
                                        } else {
                                            $_val = $specification_values[$specification['Specification']['id']][$_val];
                                        }
                                    }
    
                                    if(!empty($specification['Specification']['measure'])){
                                        $_val .= ' ' . $specification['Specification']['measure'];
                                    }
                                    
                                    $result['Specification'][] = array(
                                        'title' => $specification['Specification']['title'], 
                                        'value' => $_val,
                                        'type' => $specification['Specification']['extra_2']
                                    );
                                    
                                    $result['Specifications'][$specification['Specification']['parent_id']]['data'][] = array(
                                        'title' => $specification['Specification']['title'], 
                                        'value' => $_val,
                                        'type' => $specification['Specification']['extra_2']
                                    );
                                    
                                    if($specification['Specification']['data']['in_desc'] == '1'){
                                        $result['specification_desc'][] = $specification['Specification']['title'] . ':' . $_val;
                                        $result['specifications_desc'][$specification['Specification']['title']] = $_val;
                                    }
                                    
                                    if($specification['Specification']['data']['is_title'] != '1') $result['specification_all'][] = ($specification['Specification']['extra_2'] == '3' ? $specification['Specification']['title'] : $specification['Specification']['title'] . ': ' . $_val);
    
                                }
                            }
                        }
                        $last = false;
                        foreach($result['Specification'] as $key => $val){
                            if(is_array($val)){
                                $found = true;
                            } else {
                                if($last !== false && !$found) unset($result['Specification'][$last]);
                                $found = false;
                                $last = $key;
                            }
                        }
                        if($last && !$found) unset($result['Specification'][$last]);
                        
                        foreach($result['Specifications'] as $key => $val){
                            if(empty($val['data'])) unset($result['Specifications'][$key]);
                        }
    
                    }
                    
                    $results[0] = $result;
                }
            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('SpecificationBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"))));
        return $results;
    } 
    
    public function beforeSave(Model $model) {
        if(!empty($model->data['RelationValue']['specification'])){
            $specifications = Classregistry::init('Specification.Specification')->find('allindex', array('tid' => Configure::read('Config.tid') . '_specification', 'conditions' => array('Specification.id' => array_keys($model->data['RelationValue']['specification'])), 'order' => array('Specification.lft')));
            $extract = array();
            foreach($specifications as $specification){
                if($specification['Specification']['data']['is_title'] == '1' && in_array($specification['Specification']['extra_2'], array('6', '7', '9'))){
                    if(!empty($model->data['RelationValue']['specification'][$specification['Specification']['id']])) $extract[] = array('SpecificationValue.base_id' => $specification['Specification']['id'], 'SpecificationValue.id' => $model->data['RelationValue']['specification'][$specification['Specification']['id']]);
                }
            }
            if(!empty($extract)){
                $specification_values = Classregistry::init('Specification.SpecificationValue')->find('list', array('tid' => Configure::read('Config.tid') . '_specification', 'fields' => array('SpecificationValue.base_id', 'SpecificationValue.title'), 'conditions' => array('OR' => $extract)));
            }
            $title = array();
            foreach($specifications as $specification){
                if($specification['Specification']['data']['is_title'] == '1'){
                    if(in_array($specification['Specification']['extra_2'], array('6', '7', '9'))){
                        if(!empty($specification_values[$specification['Specification']['id']])) $title[] = $specification_values[$specification['Specification']['id']];
                    } else {
                        if(!empty($model->data['RelationValue']['specification'][$specification['Specification']['id']])) $title[] = $model->data['RelationValue']['specification'][$specification['Specification']['id']];
                    }
                }
            }
            if(!empty($title)) $model->data[$model->alias]['title'] = implode(' ', $title);
        }
    }

}
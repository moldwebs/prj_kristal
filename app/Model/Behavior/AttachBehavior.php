<?php
class AttachBehavior extends ModelBehavior {

    public function setup(Model $model, $settings = array()) {

        $cond = array('ObjOptAttach.model' => $model->name);
        if(Configure::read('is_admin') != '1'){
            $cond['ObjOptAttach.is_def <>'] = '1';
            $cond['OR'] = array("ObjOptAttach.locale = ''", "ObjOptAttach.locale IS NULL", 'ObjOptAttach.locale' => Configure::read('Config.language'));
        }
    
        $model->bindModel(
            array('hasOne' => array(
                    'ObjOptAttachDef' => array(
                        'className' => 'ObjOptAttach',
                        'foreignKey' => 'foreign_key',
                        'conditions' => array('ObjOptAttachDef.model' => $model->name, 'ObjOptAttachDef.is_def' => '1'),
                        'dependent' => true,
                        'callbacks' => true,
                        'recursive' => (Configure::read('is_admin') == '1' ? 0 : 2)
                    )
                ), 'hasMany' => array(
                    'ObjOptAttach' => array(
                        'className' => 'ObjOptAttach',
                        'foreignKey' => 'foreign_key',
                        'conditions' => $cond,
                        'order' => array('ObjOptAttach.order_id' => 'ASC'),
                        'dependent' => true,
                        'callbacks' => true
                    )
                )
            ), false
        );
        
    	$this->settings[$model->alias] = $settings;
    }   

    public function afterFind(Model $model, $results, $primary) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('AttachBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        if(!empty($results)){
            foreach($results as $key => $result){
                $results[$key]['ObjOptAttachs'] = array();
                
                if(!empty($result['ObjOptAttachDef']['file'])){
                    $results[$key]['ObjOptAttachDef']['attach'] = $result['ObjOptAttachDef']['uid'] . '_' . $result['ObjOptAttachDef']['file'];
                
                
                    if($result['ObjOptAttachDef']['location'] == 'video'){
                        $results[$key]['ObjOptAttachs']['video'][] = $results[$key]['ObjOptAttachDef'];
                        unset($results[$key]['ObjOptAttachDef']);
                    } else if(!in_array(ws_ext($result['ObjOptAttachDef']['file']), ws_ext_img())){
                        $results[$key]['ObjOptAttachDef']['file_ext'] = ws_ext($result['ObjOptAttachDef']['file']);
                        $results[$key]['ObjOptAttachDef']['stitle'] = str_replace('.' . ws_ext($result['ObjOptAttachDef']['file']), '', $result['ObjOptAttachDef']['title']);
                        $results[$key]['ObjOptAttachs']['files'][] = $results[$key]['ObjOptAttachDef'];
                        unset($results[$key]['ObjOptAttachDef']);
                    } else {
                        $results[$key]['ObjOptAttachs']['allimages'][] = $results[$key]['ObjOptAttachDef'];
                    }
                    
                    $results[$key]['ObjOptAttachs']['all'][] = $results[$key]['ObjOptAttachDef'];
                }
                
                if(!empty($result['ObjOptAttach'])){
                    foreach($result['ObjOptAttach'] as $_key => $_val){
                        $results[$key]['ObjOptAttach'][$_key]['attach'] = $_val['uid'] . '_' . $_val['file'];
                        if($_val['is_def'] == '2'){
                            $results[$key]['ObjOptAttachType'][$_val['type']] = $results[$key]['ObjOptAttach'][$_key];
                            unset($results[$key]['ObjOptAttach'][$_key]);
                        } else if($_val['is_def'] == '3'){
                            if(Configure::read('is_admin') != '1'){
                                if($model->findQueryType != 'first'){
                                    $results[$key]['ObjOptAttachDef'] = $results[$key]['ObjOptAttach'][$_key];
                                } else {
                                    unset($results[$key]['ObjOptAttach'][$_key]);
                                }
                            } 
                        } else {
                            if(Configure::read('is_admin') != '1' && !empty($results[$key]['ObjOptAttachDef']['locale']) && $results[$key]['ObjOptAttachDef']['locale'] != Configure::read('Config.language') && $_val['locale'] == Configure::read('Config.language')){
                                $results[$key]['ObjOptAttachDef'] = $results[$key]['ObjOptAttach'][$_key];
                                unset($results[$key]['ObjOptAttach'][$_key]);
                                continue;
                            }
                            if(in_array(ws_ext($_val['file']), ws_ext_img())){
                                $results[$key]['ObjOptAttachs']['images'][] = $results[$key]['ObjOptAttach'][$_key];
                                $results[$key]['ObjOptAttachs']['allimages'][] = $results[$key]['ObjOptAttach'][$_key];
                            } else if($_val['location'] == 'video'){
                                $results[$key]['ObjOptAttachs']['video'][] = $results[$key]['ObjOptAttach'][$_key];
                            } else {
                                $results[$key]['ObjOptAttach'][$_key]['file_ext'] = ws_ext($_val['file']);
                                $results[$key]['ObjOptAttach'][$_key]['stitle'] = str_replace('.' . ws_ext($_val['file']), '', $_val['title']);
                                $results[$key]['ObjOptAttachs']['files'][] = $results[$key]['ObjOptAttach'][$_key];
                            }
                        }
                        $results[$key]['ObjOptAttachs']['all'][] = $results[$key]['ObjOptAttach'][$_key];
                    }
                }
                if(!empty($model->belongsTo)){
                    foreach($model->belongsTo as $assoc => $assocData){
                        if(!empty($result[$assoc]['ObjOptAttachDef']['file'])){
                            $results[$key][$assoc]['ObjOptAttachDef']['attach'] = $result[$assoc]['ObjOptAttachDef']['uid'] . '_' . $result[$assoc]['ObjOptAttachDef']['file'];
                        }
                    }
                }
                if(!empty($model->hasOne)){
                    foreach($model->hasOne as $assoc => $assocData){
                        if(!empty($result[$assoc]['ObjOptAttachDef']['file'])){
                            $results[$key][$assoc]['ObjOptAttachDef']['attach'] = $result[$assoc]['ObjOptAttachDef']['uid'] . '_' . $result[$assoc]['ObjOptAttachDef']['file'];
                        }
                    }
                }
            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('AttachBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        return $results;
    } 
    
    public function afterSave(Model $model, $created){
        App::import('Component', 'Upload');
        $upload_component = new UploadComponent(new ComponentCollection());
        $upload_component->set_tid(md5($model->data[$model->alias]['tid'].$model->alias));

        if(!empty($model->data['attachments_remove']) && !empty($model->id)){
            $model->ObjOptAttach->item_foreign_key = $model->id;
            foreach($model->data['attachments_remove'] as $key => $val){
                if($val == '1') $model->ObjOptAttach->delete($key);
            }
        }

        if(!empty($model->data['attachonetype'])){
            foreach($model->data['attachonetype'] as $type => $file){
                $upload = $upload_component->upload($file);
                if(is_array($upload)){
                    $model->ObjOptAttach->deleteAll(array('ObjOptAttach.type' => $type, 'ObjOptAttach.is_def' => '2', 'ObjOptAttach.model' => $model->alias, 'ObjOptAttach.foreign_key' => $model->id));
                    $model->ObjOptAttach->create();
                    $model->ObjOptAttach->save(array('type' => $type, 'is_def' => '2', 'model' => $model->alias, 'foreign_key' => $model->id, 'location' => $upload['path'], 'file' => $upload['file'], 'title' => $upload['title'], 'ext' => $upload['ext'], 'size' => $upload['size']));
                }
            }
        }


        if(!empty($model->data[$model->alias]['attachtypes'])){
            foreach($model->data[$model->alias]['attachtypes'] as $type => $file){
                $upload = $upload_component->upload($file);
                if(is_array($upload)){
                    $model->ObjOptAttach->create();
                    $model->ObjOptAttach->save(array('type' => $type, 'model' => $model->alias, 'foreign_key' => $model->id, 'location' => $upload['path'], 'file' => $upload['file'], 'title' => $upload['title'], 'ext' => $upload['ext'], 'size' => $upload['size'], 'order_id' => $inc++));
                }
            }
        }

        if(!empty($model->data[$model->alias]['attachtype'])){
            foreach($model->data[$model->alias]['attachtype'] as $type => $file){
                $upload = $upload_component->upload($file);
                if(is_array($upload)){
                    $model->ObjOptAttach->create();
                    $model->ObjOptAttach->save(array('type' => $type, 'is_def' => '2', 'model' => $model->alias, 'foreign_key' => $model->id, 'location' => $upload['path'], 'file' => $upload['file'], 'title' => $upload['title'], 'ext' => $upload['ext'], 'size' => $upload['size']));
                }
            }
        }

        $img_upl = array();
        $inc = (empty($model->data['attachments_args']['order']) ? 0 : count($model->data['attachments_args']['order']));

        if(empty($model->data[$model->alias]['attachment']) && !empty($model->data['attachment'])) $model->data[$model->alias]['attachment'] = $model->data['attachment'];
        if(!empty($model->data[$model->alias]['attachment'])){
            $file = $model->data[$model->alias]['attachment'];
            $upload = $upload_component->upload($file);
            if(is_array($upload)){
                $model->ObjOptAttach->create();
                $model->ObjOptAttach->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'location' => $upload['path'], 'file' => $upload['file'], 'title' => $upload['title'], 'ext' => $upload['ext'], 'size' => $upload['size'], 'order_id' => '1', 'is_def' => '1'));
            }
        }
        
        if(empty($model->data[$model->alias]['attachments']) && !empty($model->data['attachments'])) $model->data[$model->alias]['attachments'] = $model->data['attachments'];
        if(!empty($model->data[$model->alias]['attachments'])){
            foreach($model->data[$model->alias]['attachments'] as $key => $file){
                $upload = $upload_component->upload($file);
                if(is_array($upload)){
                    $model->ObjOptAttach->create();
                    $model->ObjOptAttach->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'location' => $upload['path'], 'file' => $upload['file'], 'title' => $upload['title'], 'ext' => $upload['ext'], 'size' => $upload['size'], 'order_id' => sqls($inc++), 'is_def' => (($inc == 1 && empty($model->data['attachments_args'])) ? '1' : '0')));
                    $img_upl['new_' . md5($file['name'])] = $model->ObjOptAttach->getInsertID();
                }
            }
        }

        if(!empty($model->data['video_attachments'])){
            foreach($model->data['video_attachments'] as $key => $file){
                $model->ObjOptAttach->create();
                $model->ObjOptAttach->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'location' => 'video', 'file' => $file, 'order_id' => sqls($inc++), 'is_def' => (($inc == 1 && empty($model->data['attachments_args'])) ? '1' : '0')));
                $img_upl['video_' . $key] = $model->ObjOptAttach->getInsertID();
            }
        }

        if(!empty($model->data['url_attachments'])){
            foreach($model->data['url_attachments'] as $key => $file){
                $upload = $upload_component->upload_url($file);
                if(is_array($upload)){
                    $model->ObjOptAttach->create();
                    $model->ObjOptAttach->save(array('model' => $model->alias, 'foreign_key' => $model->id, 'location' => $upload['path'], 'file' => $upload['file'], 'title' => $upload['title'], 'ext' => $upload['ext'], 'size' => $upload['size'], 'order_id' => sqls($inc++), 'is_def' => (($inc == 1 && empty($model->data['attachments_args'])) ? '1' : '0')));
                    $img_upl['url_' . $key] = $model->ObjOptAttach->getInsertID();
                }
            }
        }

        $inc = 0;
        if(!empty($model->data['attachments_args']['order'])){
            foreach($model->data['attachments_args']['order'] as $key => $val){
                $model->ObjOptAttach->updateAll(
                    array("ObjOptAttach.order_id" => sqls($inc++, true), "ObjOptAttach.is_def" => (($inc == 1 && empty($model->data[$model->alias]['data']['no_main_img'])) ? '1' : ($inc == 1 && $model->data[$model->alias]['data']['no_main_img'] == '3' ? '3' : '0')), "ObjOptAttach.title" => sqls($model->data['attachments_args']['title'][$key], true), "ObjOptAttach.source" => sqls($model->data['attachments_args']['source'][$key], true), "ObjOptAttach.locale" => sqls($model->data['attachments_args']['locale'][$key], true), "ObjOptAttach.type" => sqls($model->data['attachments_args']['type'][$key], true)),
                    array("ObjOptAttach.id" => ($key > 0 ? $key : $img_upl[$key]))
                );
            }
        }

    	return true;
    }
}
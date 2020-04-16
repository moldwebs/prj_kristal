<?php
class AliasBehavior extends ModelBehavior {

	public function setup(Model $model, $settings = array()) {

		$default = array(
			'className' => 'CmsAlias',
			'foreignKey' => 'foreign_key',
            'dependent' => true,
            'callbacks' => true,
            'recursive' => 2
		);

		$associations['CmsAlias'] = array_merge($default, array('conditions' => array(
			'CmsAlias.model' => $model->name
		)));

        $model->bindModel(array('hasMany' => $associations), false);

		$this->settings[$model->alias] = $settings;
	}

	public function beforeValidate(Model $model) {
	    $_locale = Configure::read('Config.def_language');

	    if(!empty($model->data[$model->alias]['alias'])) $model->data['Translates'][$model->alias]['alias'][$_locale] = $model->data[$model->alias]['alias'];
        if(!empty($model->data['Translates'][$model->alias]['alias'])) foreach($model->data['Translates'][$model->alias]['alias'] as $locale => $content) if(!empty($content)){
            $found = $model->CmsAlias->find('count', array('conditions' => array('CmsAlias.alias' => $model->data['Translates'][$model->alias]['alias'][$locale], 'CmsAlias.foreign_key <>' => $model->id)));
            $i = 1;
            while($found > 0){
                if($found = $model->CmsAlias->find('count', array('conditions' => array('CmsAlias.alias' => $model->data['Translates'][$model->alias]['alias'][$locale], 'CmsAlias.foreign_key <>' => $model->id))) > 0){
                    $model->data['Translates'][$model->alias]['alias'][$locale] = $content . '-' . $i++;
                }
            }
            if(strlen($model->data['Translates'][$model->alias]['alias'][$locale]) <= 3){
                $model->data['Translates'][$model->alias]['alias'][$locale] = $model->data['Translates'][$model->alias]['alias'][$locale] . '-1';
            }
        }
		return true;
	}

    public function afterFind(Model $model, $results, $primary) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('AliasBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        if(!empty($results)){
            $locale = Configure::read('Config.language');
            $def_locale = Configure::read('Config.def_language');

            foreach($results as $key => $result){

                if(!empty($result['CmsAlias'])){
                    foreach($result['CmsAlias'] as $CmsAlias){
                        $results[$key]['Translates'][$model->alias]['alias'][$CmsAlias['locale']] = $CmsAlias['alias'];
                        if(!empty($CmsAlias['alias']) && ($CmsAlias['locale'] == $locale || empty($results[$key][$CmsAlias['model']]['alias']))){
                            $results[$key][$CmsAlias['model']]['alias'] = $CmsAlias['alias'];
                            //if($CmsAlias['locale'] == $locale) break;
                        }
                    }
                    unset($results[$key]['CmsAlias']);
                }
                if(!empty($model->belongsTo)){
                    foreach($model->belongsTo as $assoc => $assocData){
                        if(!empty($result[$assoc]['CmsAlias'])){
                            foreach($result[$assoc]['CmsAlias'] as $CmsAlias){
                                $results[$key]['Translates'][$assoc]['alias'][$CmsAlias['locale']] = $CmsAlias['alias'];
                                if(!empty($CmsAlias['alias']) && ($CmsAlias['locale'] == $locale || empty($results[$key][$assoc]['alias']))){
                                    $results[$key][$assoc]['alias'] = $CmsAlias['alias'];
                                    //if($CmsAlias['locale'] == $locale) break;
                                }
                            }
                            unset($results[$key][$assoc]['CmsAlias']);
                        }
                    }
                }
                if(!empty($model->hasOne)){
                    foreach($model->hasOne as $assoc => $assocData){
                        if(!empty($result[$assoc]['CmsAlias'])){
                            foreach($result[$assoc]['CmsAlias'] as $CmsAlias){
                                $results[$key]['Translates'][$assoc]['alias'][$CmsAlias['locale']] = $CmsAlias['alias'];
                                if(!empty($CmsAlias['alias']) && ($CmsAlias['locale'] == $locale || empty($results[$key][$assoc]['alias']))){
                                    $results[$key][$assoc]['alias'] = $CmsAlias['alias'];
                                    //if($CmsAlias['locale'] == $locale) break;
                                }
                            }
                            unset($results[$key][$assoc]['CmsAlias']);
                        }
                    }
                }

                if($model->name == 'ObjItemList') if(!empty($results[$key]['ObjItemList']['alias']) && !empty($results[$key]['ObjItemTree']['alias'])){
                    $results[$key]['ObjItemList']['alias'] = $results[$key]['ObjItemTree']['alias'] . '/' . $results[$key]['ObjItemList']['alias'];
                }

            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('AliasBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        return $results;
    }

    public function afterSave(Model $model, $created){
        $model->CmsAlias->deleteAll(array('CmsAlias.model' => $model->alias, 'CmsAlias.foreign_key' => $model->id));
        if(!empty($model->data['Translates'][$model->alias]['alias'])) foreach($model->data['Translates'][$model->alias]['alias'] as $locale => $content) if(!empty($content)){
            $model->CmsAlias->create();
            $model->CmsAlias->save(array('locale' => $locale, 'model' => $model->alias, 'foreign_key' => $model->id, 'tid' => (!empty($model->data[$model->alias]['tid']) ? $model->data[$model->alias]['tid'] : Configure::read('Config.tid')), 'alias' => $content));
        }
        unset($model->data[$model->alias]['alias']);
        unset($model->data['Translates'][$model->alias]['alias']);
    	return true;
    }

}
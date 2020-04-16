<?php

App::uses('I18n', 'I18n');
App::uses('I18nModel', 'Model');

class TranslateBehavior extends ModelBehavior {

	public function setup(Model $model, $settings = array()) {

		$default = array(
			'className' => 'I18nModel',
			'foreignKey' => 'foreign_key',
            'dependent' => true,
            'callbacks' => true,
            'recursive' => 2
		);

		$associations['I18n'] = array_merge($default, array('conditions' => array(
			'I18n.model' => $model->name,
			'I18n.field' => $settings
		)));
        
        if(count(Configure::read('CMS.activelanguages')) > 1) $model->bindModel(array('hasMany' => $associations), false);
        
		$this->settings[$model->alias] = $settings;
	}
   
    public function afterFind(Model $model, $results, $primary) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('TranslateBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        if(count(Configure::read('CMS.activelanguages')) > 1) if(!empty($results)){
            $locale = Configure::read('Config.language');
            $def_locale = Configure::read('Config.def_language');
    
            foreach($results as $key => $result){
                if(!empty($result['I18n'])){
                    foreach($result['I18n'] as $i18n){
                        if(!empty($i18n['content']) && $i18n['locale'] == $locale && isset($result[$i18n['model']][$i18n['field']])) $results[$key][$i18n['model']][$i18n['field']] = $i18n['content'];
                        $results[$key]['Translates'][$i18n['model']][$i18n['field']][$i18n['locale']] = $i18n['content'];
                    }
                    unset($results[$key]['I18n']);
                }
                
                foreach($this->settings[$model->alias] as $field) if(empty($results[$key]['Translates'][$model->alias][$field][$def_locale])) $results[$key]['Translates'][$model->alias][$field][$def_locale] = $results[$key][$model->alias][$field];
                
                if(!empty($model->belongsTo)){
                    foreach($model->belongsTo as $assoc => $assocData){
                        if(!empty($result[$assoc]['I18n'])){
                            foreach($result[$assoc]['I18n'] as $i18n){
                                if(!empty($i18n['content']) && $i18n['locale'] == $locale && isset($result[$assoc][$i18n['field']])) $results[$key][$assoc][$i18n['field']] = $i18n['content'];
                                $results[$key]['Translates'][$assoc][$i18n['field']][$i18n['locale']] = $i18n['content'];
                            }
                            unset($results[$key][$assoc]['I18n']);
                        }
                    }
                }
                if(!empty($model->hasOne)){
                    foreach($model->hasOne as $assoc => $assocData){
                        if(!empty($result[$assoc]['I18n'])){
                            foreach($result[$assoc]['I18n'] as $i18n){
                                if(!empty($i18n['content']) && $i18n['locale'] == $locale && isset($result[$assoc][$i18n['field']])) $results[$key][$assoc][$i18n['field']] = $i18n['content'];
                                $results[$key]['Translates'][$assoc][$i18n['field']][$i18n['locale']] = $i18n['content'];
                            }
                            unset($results[$key][$assoc]['I18n']);
                        }
                    }
                }
            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('TranslateBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"). ' / ' . microtime(true))));
        return $results;
    } 

	public function beforeValidate(Model $model) {
        if(count(Configure::read('CMS.activelanguages')) > 1) foreach($model->data['Translates'] as $key => $val){
            foreach($val as $_key => $_val){
                if(isset($_val[Configure::read('Config.def_language')]) && empty($model->data[$key][$_key])) $model->data[$key][$_key] = $_val[Configure::read('Config.def_language')];
                if(empty($model->data[$key][$_key])){
                    foreach($_val as $__val){
                        if(!empty($__val)){
                            $model->data[$key][$_key] = $__val;
                            break;
                        }
                    }
                }
            }
        }
		return true;
	}
   
    public function afterSave(Model $model, $created){
        if(count(Configure::read('CMS.activelanguages')) > 1){
            $model->I18n->deleteAll(array('I18n.model' => $model->alias, 'I18n.foreign_key' => $model->id));
            if(!empty($model->data['Translates'][$model->alias])) foreach(array_keys($model->data['Translates'][$model->alias]) as $field){
                $model->data['Translates'][$model->alias][$field][Configure::read('Config.def_language')] = $model->data[$model->alias][$field];
                if(isset($model->_schema[$field])) foreach($model->data['Translates'][$model->alias][$field] as $locale => $content) if(!empty($content)){
                    $model->I18n->create();
                    $model->I18n->save(array('locale' => $locale, 'model' => $model->alias, 'foreign_key' => $model->id, 'field' => $field, 'content' => $content));
                }
            }
        }
    	return true;
    }

}
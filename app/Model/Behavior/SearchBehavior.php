<?php
class SearchBehavior extends ModelBehavior {

	public function setup(Model $model, $settings = array()) {
		$this->settings[$model->alias] = $settings;
	}
    
    public function searchgenerate(Model $model){
        $ObjOptSearch = ClassRegistry::init('ObjOptSearch');
        
        $results = $model->find('all', array('tid' => false, 'st_cond' => '1', 'conditions' => array("`{$model->alias}`.`tid` NOT LIKE 'cms\_%'")));
        foreach($results as $result){
            $ObjOptSearch->deleteAll(array('ObjOptSearch.model' => $model->alias, 'ObjOptSearch.foreign_key' => $result[$model->alias]['id']));
            foreach($this->settings[$model->alias] as $field) if(!empty($result[$model->alias][$field])){
                $ObjOptSearch->create();
                $ObjOptSearch->save(array('ObjOptSearch' => array('tid' => $result[$model->alias]['tid'], 'created' => $result[$model->alias]['created'], 'model' => $model->alias, 'foreign_key' => $result[$model->alias]['id'], 'field' => $field, 'value' => ws_clean($result[$model->alias][$field]))));

                if(count(Configure::read('CMS.activelanguages')) > 1) foreach($result['Translates'][$model->alias][$field] as $locale => $content) if(!empty($content)){
                    $ObjOptSearch->create();
                    $ObjOptSearch->save(array('ObjOptSearch' => array('locale' => $locale, 'tid' => $result[$model->alias]['tid'], 'created' => $result[$model->alias]['created'], 'model' => $model->alias, 'foreign_key' => $result[$model->alias]['id'], 'field' => $field, 'value' => ws_clean($content))));
                }
            }
        }
    }

    public function afterSave(Model $model, $created){
        $ObjOptSearch = ClassRegistry::init('ObjOptSearch');
        
        $ObjOptSearch->deleteAll(array('ObjOptSearch.model' => $model->alias, 'ObjOptSearch.foreign_key' => $model->id));
        foreach($this->settings[$model->alias] as $field) if(!empty($model->data[$model->alias][$field])){
            $ObjOptSearch->create();
            $ObjOptSearch->save(array('ObjOptSearch' => array('model' => $model->alias, 'foreign_key' => $model->id, 'field' => $field, 'value' => ws_clean($model->data[$model->alias][$field]))));

            if(count(Configure::read('CMS.activelanguages')) > 1) foreach($model->data['Translates'][$model->alias][$field] as $locale => $content) if(!empty($content)){
                $ObjOptSearch->create();
                $ObjOptSearch->save(array('ObjOptSearch' => array('locale' => $locale, 'model' => $model->alias, 'foreign_key' => $model->id, 'field' => $field, 'value' => ws_clean($content))));
            }
        }
    	return true;
    }

    public function beforeDelete(Model $model, $cascade = true){
        $model->bindModel(
            array('hasMany' => array(
                    'ObjOptSearch' => array(
                        'className' => 'ObjOptSearch',
                        'foreignKey' => 'foreign_key',
                        'conditions' => array('ObjOptSearch.model' => $model->name),
                        'dependent' => true,
                        'callbacks' => true
                    )
                )
            ), false
        );
    }

}
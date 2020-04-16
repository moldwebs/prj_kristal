<?php
class UidBehavior extends ModelBehavior {

    public function beforeFind(Model $model, $query) {

        if(!empty($query['conditions']['FLTRS'])){
            $query['conditions'][9999] = $query['conditions']['FLTRS'];
            unset($query['conditions']['FLTRS']);
        }

        if($query['tid'] !== false){
            if(empty($query['conditions']["{$model->alias}.tid"])){
                if(!empty($query['tid'])){
                    $tid = $query['tid'];
                } else if(Configure::read('Config.tid')){
                    $tid = Configure::read('Config.tid');
                }
            } else {
                $tid = $query['conditions']["{$model->alias}.tid"];
            }
        } else $tid = false;

        //$cms_uid = get_uid($model, 'beforeFind', $tid);

        if(!is_array($query['conditions'])) $query['conditions'] = array($query['conditions']);
        $query['conditions']["{$model->alias}.uid"] = cms_uid($model->alias, $tid);

        $show_hidden = true;
        if(Configure::read('is_admin') != '1') $show_hidden = false;
        if((Configure::read('user_data.User.role') == 'admin' || Configure::read('user_data.User.role') > 0 || $_GET['tkey'] == TMP_KEY) && (!empty($query['conditions']["{$model->alias}.id"]) && !is_array($query['conditions']["{$model->alias}.id"]))) $show_hidden = true;

        if(empty($query['st_cond']) && empty($model->st_cond)){
            if(array_key_exists('status', $model->schema()) && !$show_hidden){
                if(array_key_exists('usersession', $model->schema())){
                    $query['conditions'][] = array('OR' => array("{$model->alias}.status" => '1', "{$model->alias}.usersession" => session_id()));
                } else {
                    if($model->alias == 'ObjItemList' && $tid == 'catalog' && 1==2){
                        $query['order'] = am(array("FIELD(`{$model->alias}`.`status`,1,0)" => 'ASC'), $query['order']);
                    } else {
                        $query['conditions']["{$model->alias}.status"] = '1';
                    }
                }
            }
            //if(array_key_exists('created', $model->schema()) && !$show_hidden && Configure::read('CMS.show_future') != '1') $query['conditions'][] = "{$model->alias}.created <= NOW()";

            if(Configure::read('is_admin') != '1' && Configure::check("Obj.types.{$tid}") && $model->alias == 'ObjItemList' && empty($query['tid']) && !array_key_exists('RAND()', $query['order'][0])){
                $existm = false;
                $types = array('0' => '0');
                foreach(Configure::read("Obj.types.{$tid}") as $key => $val){
                    if($val != '0') $types[$key] = $val;
                    if($val > 0) $existm = true;
                }
                if(count($types) > 1){
                    if(!$existm) unset($types['0']);
                    arsort($types);
                    //$query['order'] = am(array("FIELD(`{$model->alias}`.`extra_1`,".sqlimplode(",", array_keys($types)).")" => 'DESC'), $query['order']);
                    //$query['order'] = am(array("DATE_FORMAT(`{$model->alias}`.`created`, '%Y-%m-%d')" => 'DESC'), $query['order']);
                }
            }
        }
        return $query;
    }

    public function afterFind(Model $model, $results, $primary){
        //$cms_uid = get_uid($model, 'afterFind', Configure::read('Config.tid'));
    }

	public function beforeSave(Model $model, $options = array()) {
	    //$cms_uid = get_uid($model, 'beforeSave', Configure::read('Config.tid'));

        if(is_array($model->actsAs) && array_key_exists('Relation', $model->actsAs)){
            Configure::write('TMP.event_no_cache', '1');
        }

        if(Configure::check('is_admin_data.id') && Configure::read('is_admin') == '1' && array_key_exists('user_id', $model->schema())){
            if(Configure::read('is_admin_data.role') != 'admin'){
                if(Configure::read('is_admin_data.UserRole.type') == '1' || Configure::read('is_admin_data.UserRole.type') == '3'){
                    if(!empty($model->id)){
                        $obj_user_id = (int)$model->field('user_id');
                        $user_id = (int)Configure::read('is_admin_data.id');
                        if($obj_user_id != $user_id){
                            //_pr($obj_user_id);
                            //_pr($user_id);
                            //exit();
                            return false;
                        } else {
                            //_pr($obj_user_id);
                            //_pr($user_id);
                            //exit();
                        }
                    }
                }

                if(Configure::read('is_admin_data.UserRole.type') == '2' || Configure::read('is_admin_data.UserRole.type') == '3'){
                    $model->data[$model->alias]["status"] = '0';
                }
            }
            if(Configure::read('is_admin') == '1' && (empty($model->data[$model->alias]["user_id"]) || Configure::read('is_admin_data.role') != 'admin')){
                if(empty($model->id)) $model->data[$model->alias]["user_id"] = Configure::read('is_admin_data.id');
            }
        }
	    $model->data[$model->alias]["uid"] = cms_uid($model->alias, Configure::read('Config.tid'));;
		return true;
	}

    public function beforeDelete(Model $model, $cascade = true){

        if(Configure::check('is_admin_data.id') && Configure::read('is_admin') == '1' && array_key_exists('user_id', $model->schema())){
            if(Configure::read('is_admin_data.role') != 'admin' && Configure::read('is_admin_data.UserRole.type') != '1'){
                if(!empty($model->id)){
                    $obj_user_id = (int)$model->field('user_id');
                    $user_id = (int)Configure::read('is_admin_data.id');
                    if($obj_user_id != $user_id){
                        return false;
                    }
                }
            }
          }


        //$cms_uid = get_uid($model, 'beforeDelete');
        $cms_uid = cms_uid($model->alias, Configure::read('Config.tid'));

        $obj_uid = $model->field('uid');
        if($obj_uid == $cms_uid && $obj_uid !== false){
            return true;
        } else {
            return false;
        }
    }
}
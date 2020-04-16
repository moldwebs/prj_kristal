<?php
class TidBehavior extends ModelBehavior {
   
   public function beforeFind(Model $model, $query) {
        $model->_tid = Configure::read('Config.tid');
        $model->__tid = Configure::read('Config.tid');
        if($model->findQueryType != 'first'){
            if($query['tid'] !== false){
                if(empty($query['conditions']["{$model->alias}.tid"])){
                    if(!empty($query['tid'])){
                        $query['conditions']["{$model->alias}.tid"] = $query['tid'];
                    } else if(Configure::read('Config.tid')){
                        $query['conditions']["{$model->alias}.tid"] = Configure::read('Config.tid');
                        $query['tid'] = Configure::read('Config.tid');
                    }
                }
                if(($model->alias == 'ObjOptComment' || $model->alias == 'ObjOptCommentRespond') && !empty($query['conditions']["{$model->alias}.tid"])) $query['conditions']["{$model->alias}.tid"] = array($query['conditions']["{$model->alias}.tid"], $query['conditions']["{$model->alias}.tid"] . '_content');
                $model->__tid = $query['tid'];
            }
        }
        return $query;
   } 

	public function beforeSave(Model $model) {
        if(empty($model->data[$model->alias]["tid"]) && Configure::read('Config.tid')){
            $model->data[$model->alias]["tid"] = Configure::read('Config.tid');
        }
		return true;
	}
}
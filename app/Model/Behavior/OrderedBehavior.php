<?php
class OrderedBehavior extends ModelBehavior {

	public function beforeSave(model $model) {
		if(isset($model->data[$model->alias]['order_id']) && $model->data[$model->alias]['order_id'] == '') $model->data[$model->alias]['order_id'] = '0';
		return true;
	}
    	
    public function beforeFind(model $model, $query) {
        if(!array_key_exists('RAND()', $query['order'][0]) && (array_key_exists("title", $query['order'][0]) || array_key_exists("$model->alias.title", $query['order'][0]))) $query['order'] = am(array("{$model->alias}.order_id" => 'asc'), $query['order']);
        return $query;
	}

}
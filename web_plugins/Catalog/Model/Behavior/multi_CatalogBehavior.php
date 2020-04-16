<?php
class CatalogBehavior extends ModelBehavior {

    public function setup(Model $model, $settings = array()) {

        $model->bindModel(
            array('belongsTo' => array(
                    'Manufacturer' => array(
                        'className' => 'ObjItemList',
                        'foreignKey' => 'extra_2',
                    )
                )
            ), false
        );

        $model->bindModel(
            array('hasOne' => array(
                    'ObjOptPrice' => array(
                        'className' => 'ObjOptPrice',
                        'foreignKey' => 'foreign_key',
                        'conditions' => array('ObjOptPrice.is_default' => '1'),
                        'dependent' => true,
                        'callbacks' => true,
                        'force' => '1'
                    )
                )
            ), false
        );

    	$this->settings[$model->alias] = $settings;
    }   

	public function beforeValidate(Model $model) {
	    if($model->find('count', array('conditions' => array('ObjItemList.id <>' => $model->data['ObjItemList']['id'], 'ObjItemList.title' => $model->data['ObjItemList']['title']))) > 0){
	       $model->validationErrors = array('title][rus' => array(___('This title already exist.')));
           return false;
	    }
		return true;
	}

    public function beforeFind(Model $model, $query) {
    
        if(!$model->Behaviors->enabled('Currency')) $model->virtualFields['price_conv'] = "`ObjOptPrice`.`price`";

        return $query;
    } 
   
    public function afterFind(Model $model, $results, $primary) {
        
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('CatalogBehavior1:' . "({$model->_tid})" . date("Y-m-d H:i:s"))));
        
        if(!empty($model->findQueryExtra)) $findQueryExtra = $model->findQueryExtra;
        
        if(!empty($results)) foreach($results as $key => $result){
            $results[$key][$model->alias]['price'] = $result['ObjOptPrice']['price'];
            $results[$key][$model->alias]['currency'] = $result['ObjOptPrice']['currency'];
            $results[$key][$model->alias]['data']['old_price'] = $result['ObjOptPrice']['old_price'];
            if(Configure::read('CMS.catalog_title_manufacturer') == '1'){
                $results[$key][$model->alias]['title'] = $result['Manufacturer']['title'] . ' ' . $result[$model->alias]['title'];
            }
        }
        
        if(!empty($results) && Configure::read('is_admin') != '1' || Configure::read('TMP.force_combinations') == '1'){
            if(!$model->Behaviors->enabled('Currency')){
                $currency = Configure::read('CMS.currency');
                foreach($results as $key => $result){
                    if(!empty($result[$model->alias]['price'])){
                        if(!empty($result['RelationValue']['promo_price'])){
                            $result[$model->alias]['data']['old_price'] = $result[$model->alias]['price'];
                            $result[$model->alias]['price'] = $result['RelationValue']['promo_price'];
                        }

                        if(!empty($findQueryExtra['discount'])){
                            if(!empty($findQueryExtra['discount'][$result[$model->alias]['id']])){
                                $result[$model->alias]['data']['old_price'] = $result[$model->alias]['price'];
                                $result[$model->alias]['price'] = ($result[$model->alias]['price'] / 100) * (100 - $findQueryExtra['discount'][$result[$model->alias]['id']]);
                                $results[$key][$model->alias]['data']['price_extra'] = 'discount';
                            }
                        }
                        
                        $results[$key]['Price'] = array('value' => $result[$model->alias]['price'], 'old' => $result[$model->alias]['data']['old_price'], 'currency' => $result[$this->alias]['currency']);
                        $results[$key]['Price'] = ws_price_format($results[$key]['Price'], reset($currency));

                        /*
                        $results[$key]['Price']['html_value'] = str_replace('.00', '', number_format($results[$key]['Price']['value'], 2, '.', ' '));
                        $results[$key]['Price']['html_currency'] = reset($currency);
                        $results[$key]['Price']['html_old'] = str_replace('.00', '', number_format($results[$key]['Price']['old'], 2, '.', ' '));
                        */
                    } else if($no_price_txt = Configure::read('CMS.settings.'.$result[$model->alias]['tid'].'.no_price_txt')){
                        $results[$key]['Price']['html_value'] = $no_price_txt;
                        $results[$key]['Price']['html_currency'] = '';
                    }
                }
            }
            
            if(Configure::read('CMS.settings.catalog.obj_qnt') != '1') foreach($results as $key => $result){
                if(empty($result['ObjItemList']['qnt'])) $results[$key]['ObjItemList']['qnt'] = '1';
            }

            if(Configure::read('TMP.no_combinations') != '1' && Configure::read('TMP.no_relid') != '1'){
                Configure::write('TMP.no_relid', '1');
                $rel_ids = array();
                foreach($results as $key => $result){
                    if(!empty($result['ObjItemList']['rel_id'])) $rel_ids[$key] = $result['ObjItemList']['rel_id'];
                }
                if(!empty($rel_ids)){
                    $rel_items = Classregistry::init('ObjItemList')->find('allindex', array('tid' => false, 'conditions' => array("ObjItemList.id" => $rel_ids)));
                    foreach($results as $key => $result){
                        if(empty($result['ObjItemList']['rel_id'])) continue;
                        $rel_id = $result['ObjItemList']['rel_id'];
                        $rel_item_data = $rel_items[$rel_id];
                        $rel_item_data['ObjItemList']['id'] = $result['ObjItemList']['id'];
                        $rel_item_data['ObjItemList']['rel_id'] = $result['ObjItemList']['rel_id'];
                        $rel_item_data['ObjItemList']['code'] = $result['ObjItemList']['code'];
                        $rel_item_data['ObjItemList']['qnt'] = $result['ObjItemList']['qnt'];
                        $rel_item_data['ObjItemList']['extra_3'] = $result['ObjItemList']['extra_3'];
                        $rel_item_data['RelationValue'] = am($rel_item_data['RelationValue'], $result['RelationValue']);
                        $rel_item_data['Relation'] = am($rel_item_data['Relation'], $result['Relation']);
                        $rel_item_data['Specification'] = $result['Specification'];
                        $rel_item_data['specification_desc'] = $result['specification_desc'];
                        $rel_item_data['specifications_desc'] = $result['specifications_desc'];
                        $rel_item_data['specifications_vals'] = $result['specifications_vals'];
                        $rel_item_data['specification_all'] = $result['specification_all'];
                        $rel_item_data['specification_img'] = $result['specification_img'];
                        $rel_item_data['ObjItemList']['orig_title'] = $rel_item_data['ObjItemList']['title'];
                        $rel_item_data['ObjItemList']['title'] = $rel_item_data['ObjItemList']['title'] . " ({$result['ObjItemList']['title']})";
                        if(empty($rel_item_data['ObjOptAttachDef']['attach'])){
                            $rel_item_data['ObjOptAttachDef'] = (!empty($result['ObjOptAttachOriginal']) ? $result['ObjOptAttachOriginal'] : $result['ObjOptAttachDef']);
                        }
                        if(!empty($result['Price']['value'])){
                            $rel_item_data['Price'] = $result['Price'];
                            $rel_item_data['ModCurrency'] = $result['ModCurrency'];
                        }
                        $results[$key] = $rel_item_data;
                    }
                }
                Configure::write('TMP.no_relid', '0');
            }
        }
        
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('CatalogBehavior2:' . "({$model->_tid})" . date("Y-m-d H:i:s"))));
        
        return $results;
    }

    public function afterSave(Model $model, $created){
        $model->ObjOptPrice->deleteAll(array('ObjOptPrice.foreign_key' => $model->id, 'ObjOptPrice.is_default' => '1'));
        $model->ObjOptPrice->create();
        $model->ObjOptPrice->save(array('foreign_key' => $model->id, 'is_default' => '1', 'price' => $model->data['ObjOptPrice']['price'], 'currency' => $model->data['ObjOptPrice']['currency'], 'old_price' => $model->data['ObjOptPrice']['old_price']));
    	return true;
    }

}
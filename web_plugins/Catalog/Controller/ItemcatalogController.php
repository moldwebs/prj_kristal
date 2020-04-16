<?php
class ItemcatalogController extends CatalogAppController {

    public $paginate = array(
        'ObjItemList' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemList.created' => 'desc'
            ),
            'conditions' => array('ObjItemList.rel_id IS NULL')
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();

        Configure::write('TMP.no_rel_id' , '1');

        $this->ObjItemList->Behaviors->load('Catalog.Catalog');
        if(empty($this->request->params['requested'])){
            if(Configure::read('PLUGIN.Specification') == '1') $this->ObjItemList->Behaviors->load('Specification.Specification');
            if(Configure::read('CMS.settings.catalog.obj_combinations') == '1') $this->ObjItemList->Behaviors->load('Catalog.Combination');
        }

        $this->set('manufacturers', $this->ObjItemList->find('list', array('tid' => 'manufacturer', 'order' => array('title' => 'asc'))));
        $this->set('deposits', $this->ObjItemList->find('list', array('tid' => 'deposit', 'order' => array('title' => 'asc'))));
    }

    public function afterFilter() {
        parent::afterFilter();

        Configure::write('TMP.no_rel_id' , '0');
    }

    public function admin_table_actions(){
        $this->ObjItemList->table_actions($this->request->data);
        $this->Basic->back();
    }

    public function admin_index(){
        $this->set('page_title', ___('Items') . ' :: ' . ___('List'));

        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));

        $this->set('bases', $this->ObjItemTree->TreeList());
    }

    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemList, true, 'Item');

        if($id){
            $this->set('page_title', ___('Items') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Items') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('bases', $this->ObjItemTree->TreeList());
        if($this->Session->read('Auth.User.role') == 'admin') $this->set('users', $this->ObjItemList->User->find('list', array('fields' => array('User.id', 'User.username'), 'order' => array('User.username' => 'asc'))));
    }

	public function admin_order($id = null, $order = null){
        $this->ObjItemList->updateAll(array("ObjItemList.order_id" => sqls($order, true)), array("ObjItemList.id" => $id));
        $this->Basic->back();
	}

	public function admin_set_field($field = null, $id = null, $value = null){
	    $field = sqls($field);
        $this->ObjItemList->updateAll(array("ObjItemList.{$field}" => sqls($value, true)), array("ObjItemList.id" => $id));
        $this->Basic->back();
	}

	public function admin_set_relation($type = null, $id = null, $rel_id = null, $action = null, $expire = null){
	    if($action > 0){
	       $this->ObjItemList->ObjOptRelation->update_insert(array('expire' => $expire), array('model' => 'ObjItemList', 'type' => $type, 'foreign_key' => $id, 'rel_id' => $rel_id), true);
	    } else {
	       $this->ObjItemList->ObjOptRelation->deleteAll(array('model' => 'ObjItemList', 'type' => $type, 'foreign_key' => $id, 'rel_id' => $rel_id));
	    }
        $this->Basic->back();
	}

	public function admin_quantity($id = null, $quantity = null){
        $this->ObjItemList->updateAll(array("ObjItemList.qnt" => sqls($quantity, true)), array("ObjItemList.id" => $id));
        $this->Basic->back();
	}

	public function admin_delete($id = null){
	    $this->ObjItemList->delete($id);
        $this->getEventManager()->dispatch(new CakeEvent('Catalog.delete', null, array('item_id' => $id)));
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjItemList->toggle($id, 'status');
        $this->Basic->back();
    }

   public function admin_set_price($id = null, $value = null){
        $this->ObjItemList->updateAll(
            array('ObjItemList.price' => sqls($value, true)),
            array("ObjItemList.id" => $id)
        );
        exit;
    }

   public function admin_set_currency($id = null, $value = null){
        $this->ObjItemList->updateAll(
            array('ObjItemList.currency' => sqls($value, true)),
            array("ObjItemList.id" => $id)
        );
        exit;
    }

   public function admin_set_deposit($id = null, $value = null){
        $this->ObjItemList->updateAll(
            array('ObjItemList.extra_3' => sqls($value, true)),
            array("ObjItemList.id" => $id)
        );
        exit;
    }


    public function admin_pbl_related($isid = null, $id = null){
        if($isid > 0){
            $item = $this->Basic->load($id, $this->ObjItemList);
            unset($this->paginate['ObjItemList']['conditions'][0]);
            $this->set('items', $this->paginate('ObjItemList', array('ObjItemList.id' => array_keys($item['RelationValue']['product_id']))));
            $this->set('related', '1');
            $this->set('relation_values', $item['RelationValue']['product_id']);
        } else {
            $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
        }
    }

    public function admin_pbl_similar($isid = null, $id = null){
        if($isid > 0){
            $item = $this->Basic->load($id, $this->ObjItemList);
            unset($this->paginate['ObjItemList']['conditions'][0]);
            $this->set('items', $this->paginate('ObjItemList', array('ObjItemList.id' => $item['Relation']['product_id_similar'])));
            $this->set('related', '1');
        } else {
            $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
        }
    }

    public function admin_import(){
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        Configure::write('TMP.sql_no_cache', '1');

        /*
        $list = $this->ObjItemList->find('list', array('conditions' => array('ObjItemList.status <> 1', 'ObjItemList.base_id' => '0')));
        foreach($list as $id => $title){
            $this->ObjItemList->delete($id);
        }
        exit('ok');
        */

        $this->set('page_title', ___('Items') . ' :: ' . ___('Import'));
        if(!empty($this->request->data)){
            $path_parts = pathinfo(strtolower($this->request->data['Import']['pricelist']['name']));
            if($path_parts['extension'] == 'xls' || $path_parts['extension'] == 'xlsx'){
                App::import('Vendor', 'Catalog.phpexcel/excelreader');
                $Reader = new SpreadsheetReader($this->request->data['Import']['pricelist']['tmp_name'], $this->request->data['Import']['pricelist']['name']);
                foreach($Reader as $row) $data[] = $row;
            } else {
                if($handle = utf8_fopen_read($this->request->data['Import']['pricelist']['tmp_name'], "r")){
                //if($handle = fopen($this->request->data['Import']['pricelist']['tmp_name'], "r")){
                    while($_data = fgetcsv($handle, 0, ";")){
                        $data[] = $_data;
                    }
                }
            }
            foreach($data as $key => $_data){
                $price = ereg_replace('[^0-9.,]', '', $_data[($this->request->data['ImportData']['col_price'] - 1)]);
                $price = str_replace(',', '.', $price);
                if(empty($price) || empty($_data[($this->request->data['ImportData']['col_name'] - 1)])) continue;
                $id = $this->ObjItemList->field('id', array('code' => $_data[($this->request->data['ImportData']['col_code'] - 1)]));


                $save_data = array();
                $save_data_data = array();
                $save_data['price'] = sqls($price, true);
                $save_data['currency'] = sqls($this->request->data['ImportData']['col_currency'], true);
                if(!empty($_data[($this->request->data['ImportData']['col_wrnt'] - 1)])) $save_data_data['wrnt'] = $_data[($this->request->data['ImportData']['col_wrnt'] - 1)];


                if(!empty($id)){
                    $save_data = array();
                    $save_data['ObjItemList.price'] = sqls($price, true);
                    $save_data['ObjItemList.currency'] = sqls($this->request->data['ImportData']['col_currency'], true);
                    if(!empty($this->request->data['ImportData']['col_qnt'])) $save_data['ObjItemList.qnt'] = sqls($_data[($this->request->data['ImportData']['col_qnt'] - 1)], true);
                    if(!empty($this->request->data['ImportData']['col_extra_1'])) $save_data['ObjItemList.extra_1'] = sqls($_data[($this->request->data['ImportData']['col_extra_1'] - 1)], true);
                    if(!empty($this->request->data['ImportData']['col_extra_2'])) $save_data['ObjItemList.extra_2'] = sqls($_data[($this->request->data['ImportData']['col_extra_2'] - 1)], true);
                    if(!empty($this->request->data['ImportData']['col_extra_3'])) $save_data['ObjItemList.extra_3'] = sqls($_data[($this->request->data['ImportData']['col_extra_3'] - 1)], true);
                    if(!empty($this->request->data['ImportData']['col_extra_4'])) $save_data['ObjItemList.extra_4'] = sqls($_data[($this->request->data['ImportData']['col_extra_4'] - 1)], true);
                    if(!empty($this->request->data['ImportData']['col_extra_5'])) $save_data['ObjItemList.extra_5'] = sqls($_data[($this->request->data['ImportData']['col_extra_5'] - 1)], true);

                    $this->ObjItemList->updateAll($save_data, array("ObjItemList.id" => $id));

                    if(!empty($this->request->data['ImportData']['col_wrnt'])){
                        $this->ObjItemList->updateAllData(
                            array('wrnt' => sqls($_data[($this->request->data['ImportData']['col_wrnt'] - 1)], true)),
                            $id
                        );
                    }

                    if(!empty($this->request->data['ImportData']['col_qnt']) && $this->request->data['ImportData']['hide_qnt'] == '1'){
                        if($_data[($this->request->data['ImportData']['col_qnt'] - 1)] > 0){
                            $this->ObjItemList->updateAll(array('ObjItemList.status' => '1'), array("ObjItemList.id" => $id));
                        } else {
                            $this->ObjItemList->updateAll(array('ObjItemList.status' => '0'), array("ObjItemList.id" => $id));
                        }
                    }

                } else if($this->request->data['ImportData']['new_prd'] == '1'){
                    $this->ObjItemList->create();
                    $this->ObjItemList->save(array('ObjItemList' => array(
                        'title' => $_data[($this->request->data['ImportData']['col_name'] - 1)],
                        'alias' => ws_alias($_data[($this->request->data['ImportData']['col_name'] - 1)]),
                        'code' => $_data[($this->request->data['ImportData']['col_code'] - 1)],
                        'price' => $price,
                        'currency' => $this->request->data['ImportData']['col_currency'],
                        'short_body' => $_data[($this->request->data['ImportData']['col_desc'] - 1)],
                        'qnt' => $_data[($this->request->data['ImportData']['col_qnt'] - 1)],
                        'extra_1' => $_data[($this->request->data['ImportData']['col_extra_1'] - 1)],
                        'extra_2' => $_data[($this->request->data['ImportData']['col_extra_2'] - 1)],
                        'extra_3' => $_data[($this->request->data['ImportData']['col_extra_3'] - 1)],
                        'extra_4' => $_data[($this->request->data['ImportData']['col_extra_4'] - 1)],
                        'extra_5' => $_data[($this->request->data['ImportData']['col_extra_5'] - 1)],
                        'data' => array(
                            'wrnt' => $_data[($this->request->data['ImportData']['col_wrnt'] - 1)]
                        )
                    )));
                }
            }

            $this->CmsSetting->update_insert(array("value" => json_encode($this->request->data['ImportData'])), array("option" => 'import_data', "plugin" => 'catalog_import'));

            Cache::clear();

            $this->redirect('/admin/catalog/item?tb=1');
        } else {
            $this->set('import_data', Configure::read('CMS.settings.catalog_import.import_data'));
        }
    }

    public function admin_impexpdata(){
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        Configure::write('TMP.sql_no_cache', '1');
        Configure::write('PassCurrencyBehaviorbeforeFind', '1');
        Configure::write('PassCurrencyBehaviorafterFind', '1');

        if(!empty($_GET['test']) && 1==2){
            $this->ObjItemList->Behaviors->unload('Alias');
            $this->ObjItemList->Behaviors->unload('Search');
            $this->ObjItemList->Behaviors->unload('Attach');
            $this->ObjItemList->Behaviors->unload('Relation');
            $this->ObjItemList->Behaviors->unload('Catalog.Catalog');
            $this->ObjItemList->Behaviors->unload('Currency.Currency');
            if(Configure::read('PLUGIN.Specification') == '1') $this->ObjItemList->Behaviors->unload('Specification.Specification');

            $this->ObjItemList->unbindModel(array(
                'hasMany' => array('CmsAlias', 'ObjOptAttach', 'ObjOptRelation'),
                'hasOne' => array('ObjOptAttachDef', 'ModCurrency'),
                'belongsTo' => array('Manufacturer', 'User', 'ObjItemTree'),
            ));

            $this->ObjItemList->bindModel(
                array('hasMany' => array(
                        'ObjOptRelation' => array(
                            'className' => 'ObjOptRelation',
                            'foreignKey' => 'foreign_key',
                            'conditions' => array('ObjOptRelation.model' => 'ObjItemList', 'ObjOptRelation.type' => 'extra_1'),
                        )
                    )
                ), false
            );

            $limit = 100;
            $conditions = array('ObjItemList.status' => '1');
            $bases = $this->ObjItemTree->find('list');

            $step = 100;
            for($ic=0;$ic <= 100;$ic++){
                $limit = ($step * $ic) . ",{$step}";
                $items = $this->ObjItemList->find('all', array('conditions' => $conditions, 'order' => array('ObjItemList.created' => 'desc'), 'limit' => $limit));
                if(empty($items)) break;
            }
            _pr('e3');
            _pr($items[0]);
            return ;
        }

        $this->set('page_title', ___('Data') . ' :: ' . ___('Export') . ' / ' . ___('Import'));

        $types = $this->ObjOptType->find('list', array('fields' => array('ObjOptType.id', 'ObjOptType.title')));

        if(!empty($this->data['Export'])){

            $file_name = md5(uniqid()) . '.csv';

            $fp = fopen(sys_get_temp_dir() . DS . $file_name, 'w');
            fwrite($fp, "\xEF\xBB\xBF");

            $this->ObjItemList->Behaviors->unload('Alias');
            $this->ObjItemList->Behaviors->unload('Search');
            $this->ObjItemList->Behaviors->unload('Attach');
            $this->ObjItemList->Behaviors->unload('Relation');
            $this->ObjItemList->Behaviors->unload('Catalog.Catalog');
            $this->ObjItemList->Behaviors->unload('Currency.Currency');
            if(Configure::read('PLUGIN.Specification') == '1') $this->ObjItemList->Behaviors->unload('Specification.Specification');

            $this->ObjItemList->unbindModel(array(
                'hasMany' => array('CmsAlias', 'ObjOptAttach', 'ObjOptRelation'),
                'hasOne' => array('ObjOptAttachDef', 'ModCurrency'),
                'belongsTo' => array('Manufacturer', 'User', 'ObjItemTree'),
            ), false);

            $this->ObjItemList->bindModel(
                array('hasMany' => array(
                        'ObjOptRelation' => array(
                            'className' => 'ObjOptRelation',
                            'foreignKey' => 'foreign_key',
                            'conditions' => array('ObjOptRelation.model' => 'ObjItemList', 'ObjOptRelation.type' => 'extra_1'),
                        )
                    )
                ), false
            );

            $csv_row = array('id', 'code', 'new_code', 'category', 'manufacturer', 'title', 'price', 'currency', 'old_price', 'qnt');
            foreach(array('title', 'meta_title', 'meta_desc', 'meta_keyw') as $field){
                foreach(Configure::read('CMS.activelanguages') as $_lang => $lang){
                    $csv_row[] = $field . '__' . $_lang;
                }
            }
            foreach($types as $_type => $type){
                $csv_row[] = $_type . '_TP_' . $type;
            }

            $csv_row[] = md5($file_name);

            //$csv_out = implode(';', $csv_row);
            fputcsv($fp, $csv_row, ';');

            $limit = 10000;

            //$conditions = array('ObjItemList.status' => '1');
            $conditions = array();

            if(!empty($this->data['Export']['base_id'])) $conditions['ObjItemList.base_id'] = $this->data['Export']['base_id'];
            if(!empty($this->data['Export']['extra_2'])) $conditions['ObjItemList.extra_2'] = $this->data['Export']['extra_2'];
            if(!empty($this->data['Export']['extra_1'])) $conditions = am(array("(ObjItemList.extra_1 IN (".sqls($this->data['Export']['extra_1']).") OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'extra_1' AND `rel_id` IN (".sqls($this->data['Export']['extra_1']).")))"), $conditions);

            $bases = $this->ObjItemTree->find('list');
            $brands = $this->ObjItemList->find('list', array('tid' => 'manufacturer'));

            $step = 100;
            for($ic=0;$ic <= 100;$ic++){
                $limit = ($step * $ic) . ",{$step}";
                $items = $this->ObjItemList->find('all', array('conditions' => $conditions, 'order' => array('ObjItemList.base_id' => 'asc', 'ObjItemList.title' => 'asc'), 'limit' => $limit));
                if(empty($items)) break;
                foreach($items as $item){
                    $csv_row = array($item['ObjItemList']['id'], $item['ObjItemList']['code'], '', $bases[$item['ObjItemList']['base_id']], $brands[$item['ObjItemList']['extra_2']], $item['ObjItemList']['title'], $item['ObjItemList']['price'], $item['ObjItemList']['currency'], $item['ObjItemList']['data']['old_price'], ($item['ObjItemList']['status'] == '1' ? '1' : '0'));
                    //$csv_row = array($item['ObjItemList']['id'], $item['ObjItemList']['code'], '', $bases[$item['ObjItemList']['base_id']], $brands[$item['ObjItemList']['extra_2']], $item['ObjItemList']['title'], $item['ObjItemList']['price'], $item['ObjItemList']['currency'], $item['ObjItemList']['data']['old_price'], ($item['ObjItemList']['qnt'] > 0 ? $item['ObjItemList']['qnt'] : ($item['ObjItemList']['status'] == '1' ? '1' : '0')));
                    foreach(array('title', 'meta_title', 'meta_desc', 'meta_keyw') as $field){
                        foreach(Configure::read('CMS.activelanguages') as $_lang => $lang){
                            $csv_row[] = $item['Translates']['ObjItemList'][$field][$_lang];
                        }
                    }
                    foreach($types as $_type => $type){
                        $found = false;
                        if(!empty($item['ObjOptRelation'])) foreach($item['ObjOptRelation'] as $rel){
                            if($rel['rel_id'] == $_type) $found = true;
                        }
                        $csv_row[] = ($found ? '1' : '0');
                    }

                    $csv_row[] = md5($file_name);

                    //$csv_out = implode(';', $csv_row);
                    fputcsv($fp, $csv_row, ';');
                }
            }


            //file_put_contents(sys_get_temp_dir() . DS . $file_name, $csv_out);
            fclose($fp);

            exit("window.location = '".'/system/download?file=' . base64_encode($file_name)."'");
        }

        if(!empty($this->data['Import'])){
            $headers = array();

            if($handle = utf8_fopen_read($this->request->data['Import']['file']['tmp_name'], "r")){
                $this->ExtraData->deleteAll(array('type' => 'catalog_imp_exp'));
                while($_data = fgetcsv($handle, 0, ";")){
                    if(!empty($_GET['test'])){
                        _pr($_data);
                        exit;
                    }
                    if(empty($headers)){
                        $headers = $_data;
                        $head_md5 = $_data[count($_data)-1];
                    } else {
                        $__data = array();
                        foreach($_data as $key => $val){
                            $save_key = $headers[$key];
                            if(strpos($save_key, '_TP_') !== false) $save_key = 'rel_type_' . strstr($save_key, '_TP_', true);
                            $__data[$save_key] = $val;
                        }
                        if($__data[$head_md5] != $head_md5){
                            _pr($__data);
                            exit('FILE ERROR:'.$__data[$head_md5].'/'.$head_md5);
                        }

                        $this->ExtraData->create();
                        $this->ExtraData->save(array(
                            'type' => 'catalog_imp_exp',
                            'extra_5' => $__data['code'],
                            'extra_6' => $__data['qnt'],
                            'data' => $__data
                        ));

                    }
                }

                $this->set('make', '1');
            } else {
                $this->Session->setFlash(___('File required.'), 'flash');
                $this->redirect($this->request->referer(true));
            }
        }

        if(!empty($_GET['fmake'])){
            $this->set('make', '1');
        }

        if(!empty($_GET['make'])){
            $records = $this->ExtraData->find('all', array('conditions' => array('type' => 'catalog_imp_exp'), 'limit' => 100));
            if(empty($records)){
                exit('ok');
            } else {
                //sleep(5);
                //exit('next');
            }
            foreach($records as $record){
                $__data = $record['ExtraData']['data'];
                $id = $this->ObjItemList->field('id', array('code' => $__data['code']));
                if(!empty($id)){
                    $save_data = array();
                    $save_data['ObjItemList.price'] = sqls($__data['price'], true);
                    $save_data['ObjItemList.currency'] = sqls($__data['currency'], true);
                    $save_data['ObjItemList.title'] = sqls($__data['title'], true);
                    $save_data['ObjItemList.qnt'] = sqls($__data['qnt'], true);
                    $save_data['ObjItemList.status'] = ($__data['qnt'] > 0 ? '1' : '0');
                    if(!empty($__data['new_code'])) $save_data['ObjItemList.code'] = sqls($__data['new_code'], true);

                    $this->ObjItemList->updateAll($save_data, array("ObjItemList.id" => $id));

                    foreach($types as $_type => $type){
                        if(!empty($__data["rel_type_{$_type}"])){
                            $this->ObjItemList->ObjOptRelation->create();
                            $this->ObjItemList->ObjOptRelation->save(array('model' => 'ObjItemList', 'type' => 'extra_1', 'foreign_key' => $id, 'rel_id' => $_type));
                        } else {
                            $this->ObjItemList->ObjOptRelation->deleteAll(array('model' => 'ObjItemList', 'type' => 'extra_1', 'foreign_key' => $id, 'rel_id' => $_type));
                        }
                    }

                    foreach(array('title', 'meta_title', 'meta_desc', 'meta_keyw') as $field){
                        $_id = $this->ObjItemList->ObjOptField->update_insert(array('value' => $__data["{$field}__" . Configure::read('Config.def_language')]), array('model' => 'ObjItemList', 'foreign_key' => $id, 'field' => $field), true);
                        foreach(Configure::read('CMS.activelanguages') as $_lang => $lang){
                            $this->ObjItemList->I18n->update_insert(array('content' => $__data["{$field}__{$_lang}"]), array('model' => 'ObjOptField', 'locale' => $_lang, 'foreign_key' => $_id, 'field' => 'value'), true);
                        }
                    }

                    $this->ObjItemList->updateAllData(array('old_price' => sqls($__data['old_price'])), $id);
                }

                $this->ExtraData->delete($record['ExtraData']['id']);
            }
            exit('next');
        }

        $this->set('bases', $this->ObjItemTree->TreeList());
    }

    public function admin_makeorder($id = null){
        if(!empty($this->data)){
            $item = $this->ObjItemList->find('first', array('conditions' => array('ObjItemList.id' => $id)));
            if($item['ObjItemList']['qnt'] < $this->data['qnt']){
                exit("alert('".___('Invalid quantity.')."');");
            }

            ClassRegistry::init('Shop.ModOrder')->create();
            ClassRegistry::init('Shop.ModOrder')->save(array(
                'userid' => $this->Session->read('Auth.User.id'),
                'quantity' => $this->data['qnt'],
                'price' => ($item['ObjItemList']['price'] * $this->data['qnt']),
                'currency' => $item['ObjItemList']['currency'],
                'paystatus' => '1',
                'onstatus' => '3',
                'tid' => 'shop',
                'data' => array('name' => ___('Stock order'))
            ));

            $order_id = ClassRegistry::init('Shop.ModOrder')->getLastInsertId();

            ClassRegistry::init('Shop.ModOrderItem')->create();
            ClassRegistry::init('Shop.ModOrderItem')->save(array(
                'order_id' => $order_id,
                'item_id' => $id,
                'type' => 'item',
                'title' => $item['ObjItemList']['title'],
                'code' => $item['ObjItemList']['code'],
                'price' => $item['ObjItemList']['price'],
                'currency' => $item['ObjItemList']['currency'],
                'quantity' => $this->data['qnt'],
                'price_total' => ($item['ObjItemList']['price'] * $this->data['qnt'])
            ));
            $this->ObjItemList->updateAll(
                array('ObjItemList.qnt' => 'ObjItemList.qnt - ' . (int)$this->data['qnt']),
                array("ObjItemList.id" => $id)
            );

            exit("ajx_win_close();$('input[ajx_change=\"/admin/catalog/item/quantity/{$id}\"]').val('".($item['ObjItemList']['qnt'] - $this->data['qnt'])."')");
        }
    }

    // -----------------------------------------------------------------------------------------------------

    public function xml_data(){
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        Configure::write('TMP.sql_no_cache', '1');
        Configure::write('PassCurrencyBehaviorbeforeFind', '1');
        Configure::write('PassCurrencyBehaviorafterFind', '1');

        $file_name = sys_get_temp_dir() . DS . 'yandex_yml' . '.xml';
        if(file_exists($file_name)){
            //exit(file_get_contents($file_name));
        }

        $fp = fopen($file_name, 'w');
        $xml_data = array();

        $this->ObjItemList->Behaviors->unload('Alias');
        $this->ObjItemList->Behaviors->unload('Search');
        $this->ObjItemList->Behaviors->unload('Attach');
        $this->ObjItemList->Behaviors->unload('Relation');
        $this->ObjItemList->Behaviors->unload('Catalog.Catalog');
        $this->ObjItemList->Behaviors->unload('Currency.Currency');
        if(Configure::read('PLUGIN.Specification') == '1') $this->ObjItemList->Behaviors->unload('Specification.Specification');

        $this->ObjItemList->unbindModel(array(
            'hasMany' => array('CmsAlias', 'ObjOptAttach', 'ObjOptRelation'),
            'hasOne' => array('ObjOptAttachDef', 'ModCurrency'),
            'belongsTo' => array('Manufacturer', 'User', 'ObjItemTree'),
        ), false);

        $this->ObjItemList->bindModel(
            array('hasMany' => array(
                    'ObjOptRelation' => array(
                        'className' => 'ObjOptRelation',
                        'foreignKey' => 'foreign_key',
                        'conditions' => array('ObjOptRelation.model' => 'ObjItemList', 'ObjOptRelation.type' => 'extra_1'),
                    )
                )
            ), false
        );

        $limit = 100;

        $conditions = array('ObjItemList.status' => '1');

        $bases = $this->ObjItemTree->find('list');
        $brands = $this->ObjItemList->find('list', array('tid' => 'manufacturer'));

        $step = 50;
        for($ic=0;$ic <= 200;$ic++){
            $limit = ($step * $ic) . ",{$step}";
            $items = $this->ObjItemList->find('all', array('conditions' => $conditions, 'order' => array('ObjItemList.base_id' => 'asc', 'ObjItemList.title' => 'asc'), 'limit' => $limit));
            if(empty($items)) break;
            foreach($items as $item){
                _pr($item);
                exit;
                $xml_data['offers'][] = array(
                    'url' => 'www',
                    'price' => 'www',
                    'currencyId' => $item[''],
                    'categoryId' => 'www',
                    'picture' => ''
                );
            }
        }

        $xml = new SimpleXMLElement('<root/>');
        array_walk_recursive($xml_data, array ($xml, 'addChild'));
        $xml_data_xml = $xml->asXML();

        fwrite($fp, $xml_data_xml);
        fclose($fp);

        echo $xml_data_xml;

        exit;
    }

    public function breadcrumbs_bases($id, $url){
        $childs = array();

        $_childs = $this->ObjItemTree->find('all', array('order' => array('ObjItemTree.lft' => 'asc'), 'conditions' => array('ObjItemTree.parent_id' => $id)));
        if(empty($_childs)){
            $_childs = $this->ObjItemList->find('all', array('order' => array('ObjItemList.order_id' => 'asc'), 'conditions' => array('ObjItemList.base_id' => $id)));
            //pr($_childs);
            foreach($_childs as $child){
                $childs[ws_url($child['ObjItemList']['alias'])] = $child['ObjItemList']['title'];
            }
        } else {
            foreach($_childs as $child){
                $childs[ws_url($child['ObjItemTree']['alias'])] = $child['ObjItemTree']['title'];
            }
        }


        $breadcrumbs_bases = $this->viewVars['breadcrumbs_bases'];
        $breadcrumbs_bases[$url] = $childs;
        $this->set('breadcrumbs_bases', $breadcrumbs_bases);
    }

    public function index($base_id = null){

        //if(!$base_id > 0 && empty($_GET['fltr_lk__title'])) if($catalog_base_id = Configure::read('CMS.catalog_base_id')) $base_id = $catalog_base_id;

        if($base_id > 0){
            $this->cms['active_base'] = $base_id;
            $childs = $this->ObjItemList->ObjItemTree->children($base_id);
            $this->request->query['hfltr_eqorrel__base_id'] = (!empty($childs) ? am(array($base_id), Set::extract('/ObjItemTree/id', $childs)) : $base_id);

            $parents = $this->ObjItemList->ObjItemTree->getPath($base_id, null, 1, Configure::read('CMS.catalog_base_id'));
            if(!empty($parents)) foreach($parents as $parent){
                $this->cms['breadcrumbs'][ws_url($parent['ObjItemTree']['alias'])] = $parent['ObjItemTree']['title'];
                //$this->breadcrumbs_bases($parent['ObjItemTree']['id'], ws_url($parent['ObjItemTree']['alias']));
            }

            $base = $this->Basic->load($base_id, $this->ObjItemList->ObjItemTree);
            if(!empty($base['ObjItemTree']['rel_id'])) $this->request->query['hfltr_eqorrel__base_id'] = (is_array($this->request->query['hfltr_eqorrel__base_id']) ? am($this->request->query['hfltr_eqorrel__base_id'], $base['ObjItemTree']['rel_id']) : am(array($this->request->query['hfltr_eqorrel__base_id']), $base['ObjItemTree']['rel_id']));

            if(!empty($childs) && Configure::read('CMS.catalog_bases_blocks') == '1') {
                //$this->set('base_id', $base_id);
                //$this->render('index_blocks');
                //return ;
            }

            if(Configure::read('CMS.max_price') == '1'){
                $currency = Configure::read('Obj.currency');
                $data = $this->ObjItemListNull->find('first', array('fields' => array((count(Configure::read('Obj.currencies_vals')) > 1 ? "MAX((`ObjItemList`.`price` * ModCurrency.value) / {$currency['value']}) AS max_price_conv" : "MAX(`ObjItemList`.`price`) AS max_price_conv")), 'conditions' => array('ObjItemList.base_id' => $this->request->query['hfltr_eqorrel__base_id'])));
                if(!empty($data[0]['max_price_conv'])) $base['ObjItemTree']['data']['fltr_price_max'] = ceil($data[0]['max_price_conv']);
            }

            $this->set('base', $base);
        } else {
            if(empty($_GET['fltr_lk__title']) && Configure::read('CMS.catalog_bases_blocks') == '1') {
                //$this->set('base_id', '0');
                //$this->render('index_blocks');
                //return ;
            }

            if(Configure::read('CMS.max_price') == '1'){
                $currency = Configure::read('Obj.currency');
                $data = $this->ObjItemListNull->find('first', array('fields' => array((count(Configure::read('Obj.currencies_vals')) > 1 ? "MAX((`ObjItemList`.`price` * ModCurrency.value) / {$currency['value']}) AS max_price_conv" : "MAX(`ObjItemList`.`price`) AS max_price_conv"))));
                $this->set('fltr_max_price', ceil($data[0]['max_price_conv']));
            }
        }

        if($base_id > 0){
            if(Configure::read('CMS.spec_counts') == '1') Configure::write('TMP.spec_items', $this->ObjItemList->find('list', array('fields' => array('ObjItemList.id'), 'conditions' => $this->Basic->filters('ObjItemList'))));
            //$this->set('manufacturers', $this->ObjItemList->find('list', array(array('fields' => array('id', 'title')), 'conditions' => array(), 'joins' => array(array('table' => $this->ObjItemList->useTable, 'alias' => 'ObjItemListFound', 'type' => 'INNER', 'conditions' => array('ObjItemListFound.extra_2 = ObjItemList.id', 'ObjItemListFound.id' => Configure::read('TMP.spec_items')))), 'tid' => 'manufacturer', 'order' => array('title' => 'asc'), 'group' => 'ObjItemList.id')));
            $_fltr_manufacturers = $this->ObjItemListNull->find('list', array('virtual' => array('cnt' => 'COUNT(*)'), 'fields' => array('title', 'cnt', 'id'), 'conditions' => array(), 'joins' => array(array('table' => $this->ObjItemListNull->useTable, 'alias' => 'ObjItemListFound', 'type' => 'INNER', 'conditions' => array('ObjItemListFound.extra_2 = ObjItemList.id', 'ObjItemListFound.base_id' => $this->request->query['hfltr_eqorrel__base_id'], 'ObjItemListFound.status' => '1'))), 'tid' => 'manufacturer', 'order' => array('title' => 'asc'), 'group' => 'ObjItemList.id'));
            foreach($_fltr_manufacturers as $key => $val){
                $fltr_manufacturers[$key] = key($val);
                $fltr_manufacturers_count[$key] = reset($val);
            }
            $this->set('fltr_manufacturers', $fltr_manufacturers);
            $this->set('fltr_manufacturers_count', $fltr_manufacturers_count);
        }

        if(!($base_id > 0) && !empty($_GET['fltr_lk__title'])){
            if($_GET['fltr_eq__base_id'] == Configure::read('CMS.catalog_base_id')) $this->request->query['fltr_eq__base_id'] = null;
            $this->Basic->template(array('title' => ___('Search'), 'alias' => $this->here));
            $this->set('fltr_manufacturers', null);
        }

        if(!($base_id > 0) && !empty($_GET['fltr_eq__extra_2'])){
            $manufacturer = $this->ObjItemListNull->find('first', array('tid' => 'manufacturer', 'conditions' => array('ObjItemList.id' => $_GET['fltr_eq__extra_2'])));
            $this->Basic->template(array('title' => $manufacturer['ObjItemList']['title'], 'alias' => $this->here));
        }

        if(!($base_id > 0) && !empty($_GET['fltr_eqorrel__extra_1'])){
            //$type = $this->ObjOptType->find('first', array('tid' => 'type', 'conditions' => array('ObjOptType.id' => $_GET['fltr_eqorrel__extra_1'])));
            //$this->Basic->template(array('title' => $type['ObjOptType']['title'], 'alias' => $this->here));
        }

        //$this->paginate['ObjItemList']['order'] = array('FIELD(ObjItemList.extra_1, 6, 0)' => 'asc', 'ObjItemList.order_id' => 'asc') + $this->paginate['ObjItemList']['order'];

        return $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
    }

    public function search(){

        if(strlen($_GET['search']) < 2) $this->redirect($this->referer());

        $manufacturers_ids = $this->ObjItemList->find('list', array('fields' => array('id', 'title'), 'conditions' => array('ObjItemList.title LIKE' => "%{$_GET['search']}%"), 'tid' => 'manufacturer'));

        if(!empty($manufacturers_ids)){
            $conditions = array('OR' => array('ObjItemList.title LIKE' => "%{$_GET['search']}%", 'ObjItemList.extra_2' => array_keys($manufacturers_ids)));
        } else {
            $conditions = array('ObjItemList.title LIKE' => "%{$_GET['search']}%");
        }

        $this->set('items', $this->paginate('ObjItemList', $conditions));

        $this->render('index');
    }

    public function manufacturer($manufacturer_id = null){
        Configure::write('Config.tid', 'manufacturer');
        $this->Basic->load($manufacturer_id, $this->ObjItemList);
        Configure::write('Config.tid', 'catalog');

        $this->request->query['hfltr_eqorrel__extra_2'] = $manufacturer_id;

        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));

        $this->render('index');
    }

    public function promo($promo_id = null){
        Configure::write('Config.tid', 'catalog_promo');

        if(!empty($promo_id)){
            $childs = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.rel_id' => $promo_id), 'order' => array('ObjItemList.created' => 'desc')));
        }
        if(empty($promo_id) || !empty($childs)){
            $this->Basic->template(array('title' => ___('Promo'), 'alias' => $this->here));

            if(!empty($childs)){
                $promo = $childs;
            } else {
                $promo = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.rel_id IS NULL'), 'order' => array('ObjItemList.created' => 'desc')));
            }
            $this->set('promo', $promo);
            $this->render('promo');
        } else {

            $promo = $this->ObjItemList->findByid($promo_id);

            $this->Basic->template(array('title' => $promo['ObjItemList']['title'], 'alias' => $this->here));
            $this->set('description_for_action', $promo['ObjItemList']['body']);

            Configure::write('Config.tid', 'catalog');

            if(!empty($promo['ObjItemList']['base_id']) || !empty($promo['ObjItemList']['data']['list']) || 1==2){
                if(!empty($promo['ObjItemList']['base_id'])){
                    $childs = $this->ObjItemList->ObjItemTree->children($promo['ObjItemList']['base_id']);
                    $this->request->query['hfltr_eqorrel__base_id'] = (!empty($childs) ? am(array($promo['ObjItemList']['base_id']), Set::extract('/ObjItemTree/id', $childs)) : $promo['ObjItemList']['base_id']);
                }
                if(!empty($promo['ObjItemList']['extra_2'])) $this->request->query['hfltr_eq__extra_2'] = $promo['ObjItemList']['extra_2'];
                if(!empty($promo['ObjItemList']['extra_1'])) $this->request->query['hfltr_eqorrel__extra_1'] = $promo['ObjItemList']['extra_1'];

                $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));

                $this->render('index');
            } else {
                $_GET['fltr_eq__extra_2'] = $promo['ObjItemList']['extra_2'];
                $_GET['fltr_eqorrel__extra_1'] = $promo['ObjItemList']['extra_1'];

                $this->render('index_blocks');
                //$this->render('index');
            }
        }
    }

    public function view($id = null, $redirect = false){
        $this->cms['active_item'] = $id;

        $item = $this->Basic->load($id, $this->ObjItemList);
        $this->set('item', $item);

        if(!empty($item['ObjItemList']['rel_id'])) $this->redirect('/catalog/item/view/' . $item['ObjItemList']['rel_id'] . "?rel_id={$item['ObjItemList']['id']}");

        $this->cms['active_base'] = $item['ObjItemList']['base_id'];
        $parents = $this->ObjItemList->ObjItemTree->getPath($this->cms['active_base'], null, 1, Configure::read('CMS.catalog_base_id'));
        if(!empty($parents)) foreach($parents as $parent){
            array_insert($this->cms['breadcrumbs'], count($this->cms['breadcrumbs']) - 1, array(ws_url($parent['ObjItemTree']['alias']) => $parent['ObjItemTree']['title']));
            //$this->breadcrumbs_bases($parent['ObjItemTree']['id'], ws_url($parent['ObjItemTree']['alias']));
        }
    }

    public function compare($base_id = null){
        $this->Basic->template(array('title' => ___('Compare'), 'alias' => $this->here));

        $bases_ids = $this->ObjItemList->find('list', array('fields' => array('ObjItemList.id', 'ObjItemList.base_id'), 'conditions' => array('ObjItemList.id' => $this->viewVars['user_collection']['catalog_compare'])));
        $bases = $this->ObjItemList->ObjItemTree->find('list', array('conditions' => array("ObjItemTree.id" => $bases_ids)));
        foreach($bases as $key => $val){
            $bases[$key] = array('title' => $val, 'count' => array_count_values($bases_ids)[$key]);
        }

        $this->set('bases', $bases);

        if(!empty($base_id)){
            $this->set('items', $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.base_id' => $base_id, 'ObjItemList.id' => $this->viewVars['user_collection']['catalog_compare']))));
            $this->set('specifications', $this->requestAction('/catalog/specification/get_list/' . $base_id));
        }
    }

    public function wishlist($base_id = null){
        $this->Basic->template(array('title' => ___('Wishlist'), 'alias' => $this->here));

        $bases_ids = $this->ObjItemList->find('list', array('fields' => array('ObjItemList.id', 'ObjItemList.base_id'), 'conditions' => array('ObjItemList.id' => $this->viewVars['user_collection']['catalog_wish'])));
        $bases = $this->ObjItemList->ObjItemTree->find('list', array('conditions' => array("ObjItemTree.id" => $bases_ids)));
        foreach($bases as $key => $val){
            $bases[$key] = array('title' => $val, 'count' => array_count_values($bases_ids)[$key]);
        }

        $this->set('bases', $bases);
        if(!empty($base_id)){
            $this->set('items', $this->paginate('ObjItemList', array('ObjItemList.base_id' => $base_id, 'ObjItemList.id' => $this->viewVars['user_collection']['catalog_wish'])));
        } else {
            $this->set('items', $this->paginate('ObjItemList', array('ObjItemList.id' => $this->viewVars['user_collection']['catalog_wish'])));
        }
    }

    public function get_list_groups($base_id = null){
        $items = array();

        $_conditions = ($base_id > 0 ? array('ObjItemTree.parent_id' => $base_id) : array('ObjItemTree.parent_id IS NULL'));
        $childs = $this->ObjItemList->ObjItemTree->find('all', array('conditions' => $_conditions, 'order' => array('ObjItemTree.lft')));

        $conditions = array('ObjItemList.rel_id IS NULL');
        if(!empty($this->params['named']['mod_type'])) $conditions = am(array("(ObjItemList.extra_1 IN (".sqls($this->params['named']['mod_type']).") OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'extra_1' AND `rel_id` IN (".sqls($this->params['named']['mod_type']).")))"), $conditions);
        if(!empty($this->params['named']['mod_extra_2'])) $conditions = am(array("(ObjItemList.extra_2 = '".sqls($this->params['named']['mod_extra_2'])."')"), $conditions);


        if(!empty($childs)){
            foreach($childs as $child){
                $_base_id = $child['ObjItemTree']['id'];
                $_conditions = am($conditions, array("(ObjItemList.base_id IN (".sqlimplode(',', (is_array($_base_id) ? $_base_id : $this->ObjItemList->ObjItemTree->childrens($_base_id, 'id')))."))"));
                $items[$_base_id] = $child;
                $items[$_base_id]['items'] = $this->ObjItemList->find('all', array('conditions' => $_conditions, 'order' => (!empty($this->params['named']['mod_order']) ? array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)) : array('ObjItemList.views' => 'asc')), 'limit' => $this->params['named']['mod_limit']));
            }
        }

        return $items;
    }

    public function get_list($base_id = null){

        //Configure::write('TMP.no_rel_id' , '0');

        if($base_id == 'active' && empty($this->cms['active_base'])) return false;
        if($base_id == 'similar' && empty($this->cms['active_item'])) return false;
        if($base_id == 'category' && empty($this->cms['active_item'])) return false;

        $conditions = array('ObjItemList.rel_id IS NULL');
        if(!empty($this->params['named']['mod_type'])) $conditions = am(array("(ObjItemList.extra_1 IN (".sqlimplode(',', $this->params['named']['mod_type']).") OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'extra_1' AND `rel_id` IN (".sqlimplode(',', $this->params['named']['mod_type']).")))"), $conditions);
        if(!empty($this->params['named']['mod_extra_2'])) $conditions = am(array("(ObjItemList.extra_2 = '".sqls($this->params['named']['mod_extra_2'])."')"), $conditions);

        if($base_id == 'group'){
            $items = array();
            if(empty($this->params['named']['mod_group_bases'])){
                $_conditions = am($conditions, array('ObjItemList.tid' => Configure::read('Config.tid')));
                for($i=1;$i<=$this->params['named']['mod_limit'];$i++){
                    $_item = $this->ObjItemList->find('list', array('fields' => array('ObjItemList.id', 'ObjItemList.base_id'), 'conditions' => $_conditions, 'order' => (!empty($this->params['named']['mod_order']) ? array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)) : array('ObjItemList.title' => 'asc')), 'limit' => 1));
                    if(!empty($_item)){
                        $items[] = key($_item);
                        $_conditions[] = "ObjItemList.base_id <> '".reset($_item)."'";
                    }
                }
            } else {
                foreach($this->params['named']['mod_group_bases'] as $_base_id){
                    $_conditions = am($conditions, array("(ObjItemList.base_id IN (".sqlimplode(',', (is_array($_base_id) ? $_base_id : $this->ObjItemList->ObjItemTree->childrens($_base_id, 'id'))).") OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', (is_array($_base_id) ? $_base_id : $this->ObjItemList->ObjItemTree->childrens($_base_id, 'id'))).")))"));
                    $_item = $this->ObjItemList->find('list', array('fields' => array('ObjItemList.id'), 'conditions' => $_conditions, 'order' => (!empty($this->params['named']['mod_order']) ? array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)) : array('ObjItemList.title' => 'asc')), 'limit' => $this->params['named']['mod_limit']));
                    if(!empty($_item)) $items[] = reset($_item);
                }
            }
            if(!empty($items)){
                $items = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.id' => $items)));
            }
        } else if($base_id == 'similars'){
            $items = array();
            $item = $this->ObjItemList->findById($this->cms['active_item']);
            if(!empty($item['Relation']['tags'])){
                $items = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.id <>' => $this->cms['active_item'], "ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'tags' AND `rel_id` IN (".sqlimplode(',', array_keys($item['Relation']['tags']))."))"), 'order' => array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)), 'limit' => $this->params['named']['mod_limit']));
            }
            if(count($items) < $this->params['named']['mod_limit'] && !empty($item['ObjItemList']['base_id'])){
                $_items = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.id <>' => $this->cms['active_item'], "(ObjItemList.base_id IN ({$item['ObjItemList']['base_id']}) OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'base_id' AND `rel_id` IN ({$item['ObjItemList']['base_id']})))"), 'order' => array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)), 'limit' => ($this->params['named']['mod_limit'] - count($items))));
                $items = am($items, $_items);
            }
        } else if($base_id == 'similar'){
            $items = array();
            $item = $this->ObjItemList->findById($this->cms['active_item']);
            if(!empty($item['Relation']['product_id_similar'])){
                $items = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.id <>' => $this->cms['active_item'], 'ObjItemList.id' => $item['Relation']['product_id_similar']), 'order' => array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)), 'limit' => $this->params['named']['mod_limit']));
            }
        } else if($base_id == 'related'){
            $items = array();
            $item = $this->ObjItemList->findById($this->cms['active_item']);
            if(!empty($item['RelationValue']['product_id'])){
                $items = $this->ObjItemList->find('all', array('extra' => array('discount' => $item['RelationValue']['product_id']), 'conditions' => array('ObjItemList.id <>' => $this->cms['active_item'], 'ObjItemList.id' => array_keys($item['RelationValue']['product_id'])), 'order' => array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)), 'limit' => $this->params['named']['mod_limit']));
            }
            /*
            if(count($items) < $this->params['named']['mod_limit'] && !empty($item['ObjItemList']['base_id'])){
                $base = $this->ObjItemList->ObjItemTree->findById($item['ObjItemList']['base_id']);
                if(!empty($base['Relation']['base_id'])){
                    $_items = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.id NOT IN (' . sqlimplode(',', (!empty($items) ? Set::extract('/ObjItemList/id', $items) : array('0'))) . ')', 'ObjItemList.id <>' => $this->cms['active_item'], "(ObjItemList.base_id IN (".sqlimplode(',', $base['Relation']['base_id']).") OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', $base['Relation']['base_id']).")))"), 'order' => array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)), 'limit' => ($this->params['named']['mod_limit'] - count($items))));
                    $items = am($items, $_items);
                }
            }
            */
        } else {
            if($base_id == 'active') $base_id = (!empty($this->cms['active_base']) ? $this->cms['active_base'] : null);
            if($base_id == 'category'){
                $conditions = am($conditions, array('ObjItemList.id <>' => $this->cms['active_item'], 'ObjItemList.base_id' => $this->ObjItemList->fread('base_id', $this->cms['active_item'])));
            } else {
                //if($base_id) $conditions = am($conditions, array('ObjItemList.base_id' => (is_array($base_id) ? $base_id : $this->ObjItemList->ObjItemTree->childrens($base_id, 'id'))));
                if($base_id) $conditions = am($conditions, array('ObjItemList.id <>' => $this->cms['active_item'], "(ObjItemList.base_id IN (".sqlimplode(',', (is_array($base_id) ? $base_id : $this->ObjItemList->ObjItemTree->childrens($base_id, 'id'))).") OR ObjItemList.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'ObjItemList' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', (is_array($base_id) ? $base_id : $this->ObjItemList->ObjItemTree->childrens($base_id, 'id'))).")))"));
            }

            $items = $this->ObjItemList->find('all', array('conditions' => $conditions, 'order' => (!empty($this->params['named']['mod_order']) ? array(ws_expl(':', $this->params['named']['mod_order'], 0) => ws_expl(':', $this->params['named']['mod_order'], 1)) : array('ObjItemList.title' => 'asc')), 'limit' => $this->params['named']['mod_limit']));
        }

        //Configure::write('TMP.no_rel_id' , '1');

        return $items;
    }

    public function get_tags($search = null){
        $conditions = array();
        if(!empty($search)) $conditions = array("ObjOptTag.title LIKE '%".sqls($search)."%'");
        $tags = ClassRegistry::init('ObjOptTag')->find('list', array('conditions' => $conditions, 'order' => array('ObjOptTag.title' => 'asc')));
        exit(!empty($tags) ? json_encode(array_values($tags)) : null);
    }

    public function get_autocomplete(){
        $items = $this->ObjItemList->find('all', array('conditions' => array('CONCAT_WS(\' \', Manufacturer.title, ObjItemTree.title, ObjItemList.title, ObjItemList.code, ObjItemList.id) LIKE' . sqls('%' . $_GET['term'] . '%', true)), 'order' => array('ObjItemList.title' => 'asc'), 'limit' => 15));
        $return = array();
        foreach($items as $item){
            $return[] = array(
                'id' => $item['ObjItemList']['id'],
                //'label' => htmlspecialchars($item['ObjItemList']['title']),
                'label' => $item['ObjItemList']['title'],
                'label_add' => ' ' . ___('in') . ' ' . $item['ObjItemTree']['title'],
                'lbl_title' => $item['ObjItemList']['title'],
                'lbl_base' => $item['ObjItemTree']['title'],
                'url_base' => ws_url($item['ObjItemTree']['alias']),
                'url' => ws_url($item['ObjItemList']['alias']),
                'title' => $item['ObjItemList']['title'],
                'image' => "/getimages/45x45x0/thumb/{$item['ObjOptAttachDef']['attach']}",
                'price' => $item['Price']['html_value'] . ' ' . $item['Price']['html_currency'],
            );
        }
        exit(json_encode($return));
    }

    public function get_count(){
        return $this->ObjItemList->find('count');
    }

    public function admin_get_json(){
        $products = $this->ObjItemList->find('list', array('fields' => array('ObjItemList.id', 'ObjItemList.title'), 'order' => array('ObjItemList.title' => 'asc'), 'conditions' => array('OR' => array('ObjItemList.title LIKE' => '%' . sqls($_GET['term']) . '%', 'ObjItemList.code LIKE' => '%' . sqls($_GET['term']) . '%'))));
        $return = array();
        foreach($products as $key => $val) $return[] = array('id' => $key, 'label' => $val);
        exit(json_encode($return));
    }


}

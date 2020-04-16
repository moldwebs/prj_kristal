<?php
class VendorController extends VendorAppController {

    public $paginate = array(
        'ObjItemList' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemList.title' => 'asc'
            )
        )
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function admin_table_actions(){
        $this->ObjItemList->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Vendors') . ' :: ' . ___('List'));
        
        $this->set('items', $this->paginate('ObjItemList', $this->Basic->filters('ObjItemList')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemList, true);
        
        if($id){
            $this->set('page_title', ___('Vendors') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Vendors') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
        
        $this->set('bases', $this->ObjItemTree->TreeList(array('tid' => 'catalog')));
    }

	public function admin_order($id = null, $order = null){
        $this->ObjItemList->updateAll(array("ObjItemList.order_id" => sqls($order, true)), array("ObjItemList.id" => $id));
        $this->Basic->back();
	}

	public function admin_set_data_field($id = null, $field = null, $value = null){
        $this->ObjItemList->updateAllData(array($field => $value), $id);
        $this->Basic->back();
	}
    
	public function admin_delete($id = null){
	    if($this->ObjItemList->delete($id)){
	       $this->ExtraData->deleteAll(array('type' => 'vendor', 'extra_1' => $id));
	    }
        $this->Basic->back();
	}

	public function admin_set_data($field = null, $item_id = null, $vendor_id = null, $value = null){
        
        $conditions = array('type' => 'vendor', 'extra_1' => $vendor_id, 'extra_2' => $item_id);
        
        $data = $this->ExtraData->find('first', array('conditions' => $conditions));
        if(!empty($data['ExtraData']['id']) && $data['ExtraData']['id'] > 0){
            $this->ExtraData->id = $data['ExtraData']['id'];
        } else {
            $this->ExtraData->create();
            $data = array('ExtraData' => $conditions);
        }
        
        if($field == 'extra_6' && empty($data['ExtraData']['extra_7'])){
            $data['ExtraData']['extra_7'] = Configure::read("Obj.vendors.{$vendor_id}.ObjItemList.data.col_currency");
        }

        if($field == 'extra_6' || $field == 'extra_7'){
            //$data['ExtraData']['data']['price_calc'] = $data['ExtraData']['extra_6'];
            //$data['ExtraData']['data']['currency_calc'] = $data['ExtraData']['extra_7'];
        }
        
        if($field == 'price_calc'){
            $data['ExtraData']['data']['price_calc'] = $value;
            if(empty($data['ExtraData']['data']['currency_calc'])) $data['ExtraData']['data']['currency_calc'] = Configure::read("Obj.vendors.{$vendor_id}.ObjItemList.data.col_currency_calc");
            if(empty($data['ExtraData']['data']['currency_calc'])) $data['ExtraData']['data']['currency_calc'] = $data['ExtraData']['extra_7'];
        } else if($field == 'currency_calc'){
            $data['ExtraData']['data']['currency_calc'] = $value;
        } else if($field == 'extra'){
            $data['ExtraData']['data']['extra'] = $value;
        } else {
            $data['ExtraData'][$field] = $value;
        }
        
        $this->ExtraData->save($data);
        
        if($field == 'extra_6' || $field == 'extra_7' || $field == 'price_calc' || $field == 'currency_calc') $this->admin_update($item_id);

        $this->Basic->back();
	}

    public function admin_item_price($id = null){
        $results = $this->ExtraData->find('all', array('conditions' => array('ExtraData.type' => 'vendor', 'ExtraData.extra_2' => $id)));

        $vendor_data = array();
        foreach($results as $result){
            $vendor_data[$result['ExtraData']['extra_1']] = $result['ExtraData'];
        }
        foreach(Configure::read("Obj.vendors") as $vendor) if(empty($vendor_data[$vendor['ObjItemList']['id']])) $vendor_data[$vendor['ObjItemList']['id']]['extra_7'] = $vendor['ObjItemList']['data']['col_currency'];
        $this->set('vendor_data', $vendor_data);

        $this->set('vendor_codes', $this->ObjItemList->ObjOptRelation->find('list', array('fields' => array('rel_id', 'value'), 'conditions' => array('type' => 'vendor_code', 'foreign_key' => $id))));

        $this->set('item_id', $id);
    }

    public function admin_item($id = null){
        if(!empty($id)){
            $results = $this->ExtraData->find('all', array('conditions' => array('ExtraData.type' => 'vendor', 'ExtraData.extra_2' => $id)));
    
            $vendor_data = array();
            foreach($results as $result){
                $vendor_data[$result['ExtraData']['extra_1']] = $result['ExtraData'];
            }
            foreach(Configure::read("Obj.vendors") as $vendor) if(empty($vendor_data[$vendor['ObjItemList']['id']])){
                $vendor_data[$vendor['ObjItemList']['id']]['extra_7'] = $vendor['ObjItemList']['data']['col_currency'];
                $vendor_data[$vendor['ObjItemList']['id']]['data']['currency_calc'] = (!empty($vendor['ObjItemList']['data']['col_currency_calc']) ? $vendor['ObjItemList']['data']['col_currency_calc'] : $vendor['ObjItemList']['data']['col_currency']);
            }
            $this->set('vendor_data', $vendor_data);
    
            $this->set('item_id', $id);
        }
        $values = json_decode(base64_decode(urldecode($_GET['data'])), true);
        $this->request->data['RelationValue'] = $values;
        
        if(!empty($_GET['base_id'])){
            Configure::write('Config.tid', 'catalog');
            $bases_path = Set::extract('/ObjItemTree/id', $this->ObjItemTree->getPath($_GET['base_id'], array('id')));
            $vendors = $this->viewVars['vendors'];
            foreach($vendors as $key => $val){
                if(empty($val['ObjItemList']['data']['params'])) continue;
                if(array_key_exists($val['ObjItemList']['id'], $values['vendor_code'])) continue;
                $found = false;
                foreach($val['ObjItemList']['data']['params'] as $param){
                    if(in_array($param['base_id'], $bases_path)){
                        $found = true;
                        break;
                    }
                }
                if(!$found) unset($vendors[$key]);
            }
            $this->set('vendors', $vendors);
        }
        
    }

    public function admin_import(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        if(!empty($this->request->data)){
            $path_parts = pathinfo(strtolower($this->request->data['Import']['pricelist']['name']));
            if($path_parts['extension'] == 'xls' || $path_parts['extension'] == 'xlsx'){
                App::import('Vendor', 'Catalog.phpexcel/excelreader');
                $Reader = new SpreadsheetReader($this->request->data['Import']['pricelist']['tmp_name'], $this->request->data['Import']['pricelist']['name']);
                foreach($Reader as $row) $data[] = $row;
            } else {
                if($handle = fopen($this->request->data['Import']['pricelist']['tmp_name'], "r")){
                    while($_data = fgetcsv($handle, 1000, ";")){
                        $data[] = $_data;
                    }
                }
            }

            if(!empty($data)){
                $vendor = Configure::read("Obj.vendors.{$this->request->data['Import']['vendor_id']}.ObjItemList");
                
                $this->ObjItemList->updateAll(array("ObjItemList.created" => sqls(date("Y-m-d H:i:s"), true)), array("ObjItemList.id" => $vendor['id']));
                $this->ExtraData->deleteAll(array('ExtraData.type' => 'vendor', 'ExtraData.extra_1' => $vendor['id']));
                
                foreach($data as $key => $_data){
                    if(!empty($_data[($vendor['data']['col_code']-1)])){
                        $this->ExtraData->create();
                        $save = array(
                            'type' => 'vendor',
                            "extra_1" => $vendor['id'],
                            "extra_5" => trim($_data[($vendor['data']['col_code']-1)]),
                            "extra_6" => sqls(ws_number($_data[($vendor['data']['col_price']-1)])), 
                            "extra_7" => sqls($vendor['data']['col_currency']), 
                        );
                        
                        if(!empty($vendor['data']['col_price_calc'])){
                            $save['data']['price_calc'] = ws_number($_data[($vendor['data']['col_price_calc']-1)]);
                            $save['data']['currency_calc'] = sqls(!empty($vendor['data']['col_currency_calc']) ? $vendor['data']['col_currency_calc'] : $vendor['data']['col_currency']);
                        }
                        if(!empty($vendor['data']['col_extra'])){
                            $save['data']['extra'] = trim($_data[($vendor['data']['col_extra']-1)]);
                        }
                        if(!empty($vendor['data']['col_title'])){
                            $save['data']['extra'] = trim($_data[($vendor['data']['col_title']-1)]);
                        }
                        if(!empty($save['data'])) $save['data'] = sqls($save['data']);
                        $this->ExtraData->save($save);
                    } 
                }
                $this->Session->setFlash(___('Pricelist imported successfull.'), 'flash');
            } else {
                $this->Session->setFlash(___('No data for import.'), 'flash');
            }
            $this->redirect($this->request->referer(true));
        }
    }
    
    public function admin_update($upd_item_id = null){
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $this->set('page_title', ___('Vendors') . ' :: ' . ___('Update'));
        
        if(empty($upd_item_id)){
            if(empty($_GET['getlog']) && file_exists(LOGS . DS . 'vendor_update.log') && filemtime(LOGS . DS . 'vendor_update.log') < (time() - 30)){
                unlink(LOGS . DS . 'vendor_update.log');
            } else {
                //echo date("Y-m-d H:i:s") . '/' . date("Y-m-d H:i:s", filemtime(LOGS . DS . 'vendor_update.log'));
            }
            if(file_exists(LOGS . DS . 'vendor_update.log')){
                $this->set('data', file_get_contents(LOGS . DS . 'vendor_update.log'));
                return;
            } else {
                file_put_contents(LOGS . DS . 'vendor_update.log', 'BEGIN: ' . date("Y-m-d H:i:s"));
            }
        }
        
        $currencies = Configure::read('Obj.currencies_vals');
        $currency = Configure::read('Obj.currency');
        
        $vendors = Configure::read("Obj.vendors");
        
        Configure::write('Config.tid', 'catalog');
        
        $prices = array();
        $rel_ids = array();
        $items = array();
        $bases = array();

        $item_code2id_cond = array('type' => 'vendor_code', "value != ''");
        if(!empty($upd_item_id)) $item_code2id_cond['foreign_key'] = $upd_item_id;
        $item_code2id = $this->ObjItemList->ObjOptRelation->find('list', array('fields' => array('value', 'foreign_key', 'rel_id'), 'conditions' => $item_code2id_cond));
        
        if(empty($upd_item_id)) file_put_contents(LOGS . DS . 'vendor_update.log', "\r\n" . 'GET PRICES: ' . date("Y-m-d H:i:s"), FILE_APPEND);
        
        if(!empty($upd_item_id)){
            $rows = $this->ExtraData->find('all', array('conditions' => array('ExtraData.type' => 'vendor', 'ExtraData.extra_2' => $upd_item_id)));
        } else {
            $rows = $this->ExtraData->find('all', array('conditions' => array('ExtraData.type' => 'vendor')));
        }
        
        if(empty($upd_item_id)) file_put_contents(LOGS . DS . 'vendor_update.log', "\r\n" . 'CONVERT PRICES: ', FILE_APPEND);
        
        $data = array();
        foreach($rows as $row){
            
            if(empty($upd_item_id)) file_put_contents(LOGS . DS . 'vendor_update.log', ' . ', FILE_APPEND);
            
            $item_id = null;

            $vendor_id = $row['ExtraData']['extra_1'];
            $vendor = $vendors[$vendor_id]['ObjItemList']['data'];
            
            if(!empty($_GET['test'])) _pr($vendor);
            
            if(!empty($item_code2id[$vendor_id][$row['ExtraData']['extra_5']])){
                $item_id = $item_code2id[$vendor_id][$row['ExtraData']['extra_5']];
            } else if(!empty($row['ExtraData']['extra_2'])){
                $item_id = $row['ExtraData']['extra_2'];
                $item_code2id[$vendor_id][uniqid()] = $item_id;
            }
            if(empty($item_id)) continue;
            if(empty($row['ExtraData']['extra_6'])) continue;
            
            if(empty($items[$item_id])){
                $rel_id = $this->ObjItemList->field('rel_id', array('ObjItemList.id' => $item_id));
                if(!empty($rel_id)){
                    if(!in_array($rel_id, $rel_ids)) $rel_ids[] = $rel_id;
                    $items[$item_id]['base_id'] = $this->ObjItemList->field('base_id', array('ObjItemList.id' => $rel_id));
                } else {
                    $items[$item_id]['base_id'] = $this->ObjItemList->field('base_id', array('ObjItemList.id' => $item_id));
                }
                $items[$item_id]['coeficient'] = $this->ObjItemList->ObjOptRelation->field('value', array('type' => 'vendor_percent', 'rel_id' => '1', 'foreign_key' => $item_id));
                $items[$item_id]['coeficient_fix'] = $this->ObjItemList->ObjOptRelation->field('value', array('type' => 'vendor_fix', 'rel_id' => '1', 'foreign_key' => $item_id));
                if(empty($bases[$items[$item_id]['base_id']])){
                    $bases[$items[$item_id]['base_id']] = array_reverse(Set::extract('/ObjItemTree/id', $this->ObjItemTree->getPath($items[$item_id]['base_id'], array('id'))));
                }
            }

            $item = $items[$item_id];

            if(!empty($_GET['test'])) _pr($item);
            if(!empty($_GET['test'])) _pr($bases[$item['base_id']]);

            $price = array(
                'vendor_id' => $vendor_id, 
                'price' => $row['ExtraData']['extra_6'],
                'extra_4' => $row['ExtraData']['extra_4'],
                'currency' => $row['ExtraData']['extra_7'],
                'price_orig' => $row['ExtraData']['extra_6'],
                'currency_orig' => $row['ExtraData']['extra_7'],
                'c_price_orig' => $row['ExtraData']['extra_6'],
                'c_currency_orig' => $row['ExtraData']['extra_7'],
            );
            
            if((!empty($vendor['col_price_calc']) || !empty($row['ExtraData']['extra_4']))){
                if(!empty($row['ExtraData']['data']['price_calc'])){
                    if(empty($row['ExtraData']['data']['currency_calc'])) $row['ExtraData']['data']['currency_calc'] = $row['ExtraData']['extra_7'];
                    $price['price'] = $row['ExtraData']['data']['price_calc'];
                    $price['currency'] = $row['ExtraData']['data']['currency_calc'];
                } else {
                    //continue;
                }
            }

            if(!empty($vendor['conv_currency'])){
                //$rate = (!empty($vendor['conv_rate']) ? ($vendor['conv_rate']) : ($currencies[$vendor['col_currency']]));
                $rate = (!empty($vendor['conv_rate']) ? ($vendor['conv_rate']) : ($currencies[$price['currency']]));
                $price['conv_rate'] = $rate;
                $price['price'] = $price['price'] * $rate;
                $price['currency'] = $currency['currency'];
                $price['price_orig'] = $price['price'];
                $price['currency_orig'] = $currency['currency'];
            }

            $coeficient = false;
            if(!empty($item['coeficient'])){
                $coeficient = $item['coeficient'];
            } else {
                if(!empty($vendor['params'])) foreach($bases[$item['base_id']] as $base_id){
                    foreach($vendor['params'] as $params) if($params['base_id'] == $base_id){
                        if(($price['price'] >= $params['range_min'] || empty($params['range_min'])) && ($price['price'] <= $params['range_max'] || empty($params['range_max']))){
                            $coeficient = $params['coeficient'];
                            break;
                        }
                    }
                    if($coeficient) break;
                }
                if(!$coeficient){
                    $coeficient = $vendor['coeficient'];
                }
            }
            $coeficient_fix = false;
            if(!empty($item['coeficient_fix'])){
                $coeficient_fix = $item['coeficient_fix'];
            } else {
                if(!empty($vendor['params'])) foreach($bases[$item['base_id']] as $base_id){
                    foreach($vendor['params'] as $params) if($params['base_id'] == $base_id){
                        if(!empty($_GET['test'])) _pr($base_id.'/'.$params['base_id']);
                        if(($price['price'] >= $params['range_min'] || empty($params['range_min'])) && ($price['price'] <= $params['range_max'] || empty($params['range_max']))){
                            $coeficient_fix = $params['coeficient_fix'];
                            break;
                        }
                    }
                    if($coeficient_fix) break;
                }
                if(!$coeficient_fix){
                    $coeficient_fix = $vendor['coeficient_fix'];
                }
            }
            
            if(!empty($coeficient)){
                $price['price'] = ($price['price'] / 100) * (100 + $coeficient);
                $price['coeficient'] = $coeficient;
            }
            if(!empty($coeficient_fix)){
                $price['price'] = $price['price'] + $coeficient_fix;
                $price['coeficient_fix'] = $coeficient_fix;
            }

            $this->ExtraData->updateAll(array(
                "ExtraData.extra_2" => $item_id,
                "ExtraData.data" => sqls(json_encode(am($row['ExtraData']['data'], $price)), true),
            ), array("ExtraData.id" => $row['ExtraData']['id']));

            $prices[$item_id][$vendor_id] = $price;
        }
        
        if(!empty($_GET['test'])) _pr($prices);
        
        $items_price = array();
        foreach($prices as $item_id => $vendor_prices){
            foreach($vendor_prices as $vendor_id => $price){
                if(!($price['price'] > 0)) continue;
                if(empty($items_price[$item_id])){
                    $items_price[$item_id] = $price;
                } else if((($price['price']*$currencies[$price['currency']])/$currency['value']) < (($items_price[$item_id]['price']*$currencies[$items_price[$item_id]['currency']])/$currency['value'])){
                    $items_price[$item_id] = $price;
                }
            }
        }

        //$items = $this->ObjItemList->ObjOptRelation->find('list', array('fields' => array('ObjOptRelation.foreign_key', 'ObjOptRelation.foreign_key'), 'conditions' => array('ObjOptRelation.type' => 'vendor_code', "ObjOptRelation.value != ''"), 'group' => array('ObjOptRelation.foreign_key')));
        
        $ObjOptPrice = ClassRegistry::init('ObjOptPrice');
        
        if(empty($upd_item_id)) file_put_contents(LOGS . DS . 'vendor_update.log', "\r\n" . 'SAVE PRICES: ', FILE_APPEND);
        foreach($item_code2id as $items){
            foreach($items as $item_id){
                if(empty($upd_item_id)) file_put_contents(LOGS . DS . 'vendor_update.log', ' . ', FILE_APPEND);
                
                $this->ObjItemList->ObjOptRelation->deleteAll(array('model' => 'ObjItemList', 'type' => 'vendor_price', 'foreign_key' => $item_id));
                
                if($items_price[$item_id]['price'] > 0){
                    $price = $items_price[$item_id];
                    if(empty($price['extra_4'])){
                        $this->ObjItemList->updateAll(array(
                            "ObjItemList.status" => '1',
                            //"ObjItemList.price" => sqls($price['price'], true),
                            //"ObjItemList.currency" => sqls($price['currency'], true),
                        ), array("ObjItemList.id" => $item_id));
                        $ObjOptPrice->updateAll(
                            array('ObjOptPrice.price' => sqls($price['price'], true), 'ObjOptPrice.currency' => sqls($price['currency'], true)),
                            array("ObjOptPrice.foreign_key" => $item_id, "ObjOptPrice.is_default" => '1')
                        );
                    }
                    $this->ObjItemList->ObjOptRelation->update_insert(array('rel_id' => $price['vendor_id'], 'value' => "{$price['c_price_orig']}:{$price['c_currency_orig']}"), array('model' => 'ObjItemList', 'type' => 'vendor_price', 'foreign_key' => $item_id));
                    $this->ObjItemList->ObjOptRelation->update_insert(array('rel_id' => $price['vendor_id'], 'value' => "{$price['price_orig']}:{$price['currency_orig']}"), array('model' => 'ObjItemList', 'type' => 'vendor_cprice', 'foreign_key' => $item_id));
                } else {
                    $this->ObjItemList->updateAll(array(
                        "ObjItemList.status" => '0',
                    ), array("ObjItemList.id" => $item_id));
                }
            }
        }
        
        if(!empty($rel_ids)) foreach($rel_ids as $rel_id){
            $this->getEventManager()->dispatch(new CakeEvent('Catalog.rel_id', null, array('rel_id' => $rel_id)));
        }
        
        if(empty($upd_item_id)) file_put_contents(LOGS . DS . 'vendor_update.log', "\r\n" . 'END: ' . date("Y-m-d H:i:s"), FILE_APPEND);
        
        if(!empty($upd_item_id)) return; 
        
        $this->Session->setFlash(___('Products updated successfull.'), 'flash');
        $this->redirect($this->request->referer(true));
    }
    
    public function admin_compare($vendor_id = null){
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $vendor = Configure::read("Obj.vendors.{$vendor_id}.ObjItemList");
        
        if(!empty($this->request->data)){
            if(!empty($this->request->data['Import']['pricelist_1']['size'])){
                $path_parts = pathinfo(strtolower($this->request->data['Import']['pricelist_1']['name']));
                if($path_parts['extension'] == 'xls' || $path_parts['extension'] == 'xlsx'){
                    App::import('Vendor', 'Catalog.phpexcel/excelreader');
                    $Reader = new SpreadsheetReader($this->request->data['Import']['pricelist_1']['tmp_name'], $this->request->data['Import']['pricelist_1']['name']);
                    foreach($Reader as $row) $data_1[] = $row;
                } else {
                    if($handle = fopen($this->request->data['Import']['pricelist_1']['tmp_name'], "r")){
                        while($_data = fgetcsv($handle, 1000, ";")){
                            $data_1[] = $_data;
                        }
                    }
                }
            } else {
                $data_1 = array();
                $rows = $this->ExtraData->find('all', array('conditions' => array('ExtraData.type' => 'vendor', "ExtraData.extra_1" => $vendor_id)));
                foreach($rows as $row){
                    $data_1[] = array(
                        ($vendor['data']['col_code']-1) => $row['ExtraData']['extra_5'],
                        ($vendor['data']['col_title']-1) => $row['ExtraData']['data']['title'],
                        ($vendor['data']['col_price']-1) => $row['ExtraData']['extra_6'],
                    );
                }
            }

            $path_parts = pathinfo(strtolower($this->request->data['Import']['pricelist_2']['name']));
            if($path_parts['extension'] == 'xls' || $path_parts['extension'] == 'xlsx'){
                App::import('Vendor', 'Catalog.phpexcel/excelreader');
                $Reader = new SpreadsheetReader($this->request->data['Import']['pricelist_2']['tmp_name'], $this->request->data['Import']['pricelist_2']['name']);
                foreach($Reader as $row) $data_2[] = $row;
            } else {
                if($handle = fopen($this->request->data['Import']['pricelist_2']['tmp_name'], "r")){
                    while($_data = fgetcsv($handle, 1000, ";")){
                        $data_2[] = $_data;
                    }
                }
            }
            
            if(!empty($data_1) && !empty($data_2)){
                
                $data_1_items = array();
                foreach($data_1 as $row){
                    if(empty($row[($vendor['data']['col_code']-1)])) continue;
                    $data_1_items[$row[($vendor['data']['col_code']-1)]] = array('title' => $row[($vendor['data']['col_title']-1)], 'price' => $row[($vendor['data']['col_price']-1)]);
                }
                
                $data_2_items = array();
                foreach($data_2 as $row){
                    if(empty($row[($vendor['data']['col_code']-1)])) continue;
                    $data_2_items[$row[($vendor['data']['col_code']-1)]] = array('title' => $row[($vendor['data']['col_title']-1)], 'price' => $row[($vendor['data']['col_price']-1)]);
                }
                
                $items = array();
                
                foreach($data_1_items as $item_code => $item){
                    if(empty($data_2_items[$item_code])){
                        $items[] = array('code' => $item_code, 'title' => $item['title'], 'price_old' => $item['price'], 'act' => 'delete');
                    }
                }
                
                foreach($data_2_items as $item_code => $item){
                    if(empty($data_1_items[$item_code])){
                        $items[] = array('code' => $item_code, 'title' => $item['title'], 'price_old' => $data_1_items[$item_code]['price'], 'price' => $item['price'], 'act' => 'new');
                    } else if($item['price'] != $data_1_items[$item_code]['price']){
                        $items[] = array('code' => $item_code, 'title' => $item['title'], 'price_old' => $data_1_items[$item_code]['price'], 'price' => $item['price'], 'act' => 'modify');
                    }
                }
                
                if(empty($items)) $items = ___('No new actions');
                
                $this->set('items', $items);
                $this->set('vendor', $vendor);
            } else {
                $this->Session->setFlash(___('No data for compare.'), 'flash');
                $this->redirect($this->request->referer(true));
            }
        }
    }
    
}

<?php
class ExportController extends CatalogAppController {

    public $step = 100;
    public $pages = 1000;
    public $base_id = 130;
    public $store_url = '';

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

        $this->store_url = str_replace('force.', '', FULL_BASE_URL);

        //$this->step = 6;
        //$this->pages = 1;
        //$this->base_id = 6694;
        $this->base_id = null;

        //$this->set('manufacturers', $this->ObjItemList->find('list', array('tid' => 'manufacturer', 'order' => array('title' => 'asc'))));
    }

    public function afterFilter() {
        parent::afterFilter();

        Configure::write('TMP.no_rel_id' , '0');
    }

    public function xml_upload_specs(){
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        Configure::write('TMP.sql_no_cache', '1');
        Configure::write('PassCurrencyBehaviorbeforeFind', '1');
        Configure::write('PassCurrencyBehaviorafterFind', '1');

        $this->ObjItemList->Behaviors->unload('Search');
        //$this->ObjItemList->Behaviors->unload('Attach');
        //$this->ObjItemList->Behaviors->unload('Relation');
        //$this->ObjItemList->Behaviors->unload('Catalog.Catalog');
        $this->ObjItemList->Behaviors->unload('Currency.Currency');

        //$this->ObjItemList->Behaviors->load('Catalog.Catalog');
        //$this->ObjItemList->Behaviors->load('Specification.Specification');

        $file_name = sys_get_temp_dir() . DS . 'xml_upload_specs_' . date("Y-m-d") . '.xml';
        if(file_exists($file_name)){
            if(!empty($_GET['force'])){
                unlink($file_name);
            } else {
                header('Content-type: application/xml');
                exit(file_get_contents($file_name));
            }
        }

        $fp = fopen($file_name, 'w');

        $manufacturers = $this->ObjItemList->find('list', array('tid' => 'manufacturer'));

        $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><products></products>');

        $bases = $this->ObjItemTree->find('all', array('conditions' => array('ObjItemTree.status' => '1'), 'order' => array('ObjItemTree.lft' => 'asc')));
        $xml_data_array = array();
        foreach($bases as $base){

            if(empty($base['Translates']['ObjItemTree']['title']['rus'])) $base['Translates']['ObjItemTree']['title']['rus'] = $base['Translates']['ObjItemTree']['title']['rom'];
            if(empty($base['Translates']['ObjItemTree']['title']['rom'])) $base['Translates']['ObjItemTree']['title']['rom'] = $base['Translates']['ObjItemTree']['title']['rus'];

            if(empty($base['Translates']['ObjItemTree']['alias']['rus'])) $base['Translates']['ObjItemTree']['alias']['rus'] = $base['Translates']['ObjItemTree']['alias']['rom'];
            if(empty($base['Translates']['ObjItemTree']['alias']['rom'])) $base['Translates']['ObjItemTree']['alias']['rom'] = $base['Translates']['ObjItemTree']['alias']['rus'];

            $_xml_data_array = array(
                'category_id' => $base['ObjItemTree']['id'],
                'parent_id' => $base['ObjItemTree']['parent_id'],
                'title_ru' => $base['Translates']['ObjItemTree']['title']['rus'],
                'title_ro' => $base['Translates']['ObjItemTree']['title']['rom'],
                'description_ru' => $base['Translates']['ObjItemTree']['list_body']['rus'],
                'description_ro' => $base['Translates']['ObjItemTree']['list_body']['rom'],
                'image' => (!empty($base['ObjOptAttachType']['icon']['file']) ? $this->store_url . DS . $base['ObjOptAttachType']['icon']['location'] . '/large/' . $base['ObjOptAttachType']['icon']['file'] : ''),
                'category_url_ru' => $this->store_url . '/rus/' . $base['Translates']['ObjItemTree']['alias']['rus'],
                'category_url_ro' => $this->store_url . '/rom/' . $base['Translates']['ObjItemTree']['alias']['rom'],
                'order' => $base['ObjItemTree']['lft'],
                'visible' => $base['ObjItemTree']['status'],
            );

            $xml_data_array[] = $_xml_data_array;
        }
        $catalog = $xml_data->addChild('categories');
        $this->array_to_xml($xml_data_array, $catalog, 'category');

        // -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

        $specifications_data = array();

        $xml_data_array = array();
        for($ic=0;$ic <= 100;$ic++){
            $limit = (100 * $ic) . ",100";

            $specifications = Classregistry::init('Specification.Specification')->find('all', array('tid' => 'catalog_specification', 'st_cond' => '1', 'conditions' => array('Specification.status' => '1', 'Specification.extra_2 > 0'), 'order' => array('Specification.lft' => 'asc'), 'limit' => $limit));
            if(empty($specifications)) break;
            foreach($specifications as $specification){

                if(empty($specification['Translates']['Specification']['title']['rus'])) $specification['Translates']['Specification']['title']['rus'] = $specification['Translates']['Specification']['title']['rom'];
                if(empty($specification['Translates']['Specification']['title']['rom'])) $specification['Translates']['Specification']['title']['rom'] = $specification['Translates']['Specification']['title']['rus'];

                if(empty($specification['Translates']['Specification']['measure']['rus'])) $specification['Translates']['Specification']['measure']['rus'] = $specification['Translates']['Specification']['measure']['rom'];
                if(empty($specification['Translates']['Specification']['measure']['rom'])) $specification['Translates']['Specification']['measure']['rom'] = $specification['Translates']['Specification']['measure']['rus'];

                $_xml_data_array = array(
                    'category_id' => $specification['Specification']['extra_3'],
                    'feature_id' => $specification['Specification']['id'],
                    'type' => ($specification['Specification']['extra_2'] == '2' ? '1' : '3'),
                    'unit_ru' => $specification['Translates']['Specification']['measure']['rus'],
                    'unit_ro' => $specification['Translates']['Specification']['measure']['rom'],
                    'feature_ru' => $specification['Translates']['Specification']['title']['rus'],
                    'feature_ro' => $specification['Translates']['Specification']['title']['rom'],
                );

                $xml_data_array[] = $_xml_data_array;

                $specifications_data[$specification['Specification']['id']] = array(
                    'category_id' => $specification['Specification']['extra_3'],
                    'group_id' => $specification['Specification']['parent_id'],
                    'type' => $specification['Specification']['extra_2'],
                );

            }
        }
        $features = $xml_data->addChild('features');
        $this->array_to_xml($xml_data_array, $features, 'feature');

        // -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

        $xml_data_array = array();
        for($ic=0;$ic <= 100;$ic++){
            $limit = (100 * $ic) . ",100";

            $specifications = Classregistry::init('Specification.Specification')->find('all', array('tid' => 'catalog_specification', 'st_cond' => '1', 'conditions' => array('Specification.status' => '1', 'Specification.extra_1 = 1'), 'order' => array('Specification.lft' => 'asc'), 'limit' => $limit));
            if(empty($specifications)) break;
            foreach($specifications as $specification){

                if(empty($specification['Translates']['Specification']['title']['rus'])) $specification['Translates']['Specification']['title']['rus'] = $specification['Translates']['Specification']['title']['rom'];
                if(empty($specification['Translates']['Specification']['title']['rom'])) $specification['Translates']['Specification']['title']['rom'] = $specification['Translates']['Specification']['title']['rus'];

                $_xml_data_array = array(
                    'group_id' => $specification['Specification']['id'],
                    'group_name_ru' => $specification['Translates']['Specification']['title']['rus'],
                    'group_name_ro' => $specification['Translates']['Specification']['title']['rom'],
                );

                $xml_data_array[] = $_xml_data_array;
            }
        }
        $groups = $xml_data->addChild('groups');
        $this->array_to_xml($xml_data_array, $groups, 'group');

        // -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

        $conditions = array();
        $conditions = array('ObjItemList.status' => '1');
        if(!empty($this->base_id)) $conditions = $conditions + array('ObjItemList.base_id' => $this->base_id);

        $xml_data_array = array();
        for($ic=0;$ic <= $this->pages;$ic++){
            $limit = ($this->step * $ic) . ",{$this->step}";
            $items = $this->ObjItemList->find('all', array('conditions' => $conditions, 'order' => array('ObjItemList.created' => 'desc'), 'limit' => $limit));
            if(empty($items)) break;
            foreach($items as $item){

                $features = array();
                foreach($item['RelationValue']['specification'] as $key => $val){

                    if(in_array($specifications_data[$key]['type'], array('6', '7', '9')) && (is_numeric($val) || is_array($val))){
                        $val_ru = '';
                        $val_ro = '';
                        if(!is_array($val)) $val = array($val);
                        foreach($val as $_val){
                            $specification_values = $this->ExtraData->query("SELECT locale, content FROM wb_cms_i18n WHERE model = 'SpecificationValue' AND field = 'title' AND foreign_key = '{$_val}'");
                            if(empty($specification_values)){
                                $specification_value = $this->ExtraData->query("SELECT title FROM wb_obj_item_list WHERE tid = 'catalog_specification' AND id = '{$_val}'");
                                if(empty($specification_value)){
                                    continue;
                                    //exit("SPECVAL BASE({$item['ObjItemTree']['id']}), PRD({$item['ObjItemList']['id']}), SPEC({$key}), SPECVAL({$_val})");
                                }
                                $specification_values = array(
                                    array('wb_cms_i18n' => array(
                                        'locale' => 'rus',
                                        'content' => $specification_value[0]['wb_obj_item_list']['title'],
                                    )),
                                    array('wb_cms_i18n' => array(
                                        'locale' => 'rom',
                                        'content' => $specification_value[0]['wb_obj_item_list']['title'],
                                    )),
                                );
                            }
                            if(empty($specification_values)) continue;
                            foreach($specification_values as $specification_value){
                                if($specification_value['wb_cms_i18n']['locale'] == 'rus') $val_ru[] = $specification_value['wb_cms_i18n']['content'];
                                if($specification_value['wb_cms_i18n']['locale'] == 'rom') $val_ro[] = $specification_value['wb_cms_i18n']['content'];
                            }
                        }
                        if(empty($val_ru)) continue;
                        $val_ru = implode(', ', $val_ru);
                        $val_ro = implode(', ', $val_ro);
                    } else if(in_array($specifications_data[$key]['type'], array('3'))){
                        $val_ru = ($val > 0 ? 'Да' : 'Нет');
                        $val_ro = ($val > 0 ? 'Da' : 'Nu');
                    } else {
                        $val_ru = $val;
                        $val_ro = $val;
                    }

                    $features[] = array(
                        'feature' => array(
                            'feature_id' => $key,
                            'category_id' => $specifications_data[$key]['category_id'],
                            'group_id' => ($specifications_data[$key]['group_id'] > 0 ? $specifications_data[$key]['group_id'] : '0'),
                            'values' => array(
                                'value_ru' => $val_ru,
                                'value_ro' => $val_ro,
                            ),
                        ),
                    );
                }

                $_xml_data_array = array(
                    'id' => $item['ObjItemList']['id'],
                    'features' => $features
                );

                $xml_data_array[] = $_xml_data_array;
            }
        }

        $products_list = $xml_data->addChild('products_list');
        $this->array_to_xml($xml_data_array, $products_list, 'product');

        $xml_data_xml = $xml_data->asXML();

        fwrite($fp, $xml_data_xml);
        fclose($fp);

        header('Content-type: application/xml');

        exit($xml_data_xml);
    }

    public function xml_price_upload(){
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        Configure::write('TMP.sql_no_cache', '1');
        Configure::write('PassCurrencyBehaviorbeforeFind', '1');
        Configure::write('PassCurrencyBehaviorafterFind', '1');

        $this->ObjItemList->Behaviors->unload('Search');
        //$this->ObjItemList->Behaviors->unload('Attach');
        $this->ObjItemList->Behaviors->unload('Relation');
        $this->ObjItemList->Behaviors->unload('Catalog.Catalog');
        $this->ObjItemList->Behaviors->unload('Currency.Currency');

        $file_name = sys_get_temp_dir() . DS . 'xml_price_upload_' . date("Y-m-d-H") . '.xml';
        if(file_exists($file_name)){
            if(!empty($_GET['force'])){
                unlink($file_name);
            } else {
                header('Content-type: application/xml');
                exit(file_get_contents($file_name));
            }
        }

        $fp = fopen($file_name, 'w');

        $conditions = array();
        //$conditions = array('ObjItemList.status' => '1');
        if(!empty($this->base_id)) $conditions = $conditions + array('ObjItemList.base_id' => $this->base_id);

        $manufacturers = $this->ObjItemList->find('list', array('tid' => 'manufacturer'));

        $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><products></products>');
        for($ic=0;$ic <= $this->pages;$ic++){
            $limit = ($this->step * $ic) . ",{$this->step}";
            $items = $this->ObjItemList->find('all', array('conditions' => $conditions, 'order' => array('ObjItemList.created' => 'desc'), 'limit' => $limit));
            if(empty($items)) break;
            foreach($items as $item){

                $manufacturer = $manufacturers[$item['ObjItemList']['extra_2']];

                if(empty($item['Translates']['ObjItemList']['title']['rus'])) $item['Translates']['ObjItemList']['title']['rus'] = $item['Translates']['ObjItemList']['title']['rom'];
                if(empty($item['Translates']['ObjItemList']['title']['rom'])) $item['Translates']['ObjItemList']['title']['rom'] = $item['Translates']['ObjItemList']['title']['rus'];

                $_xml_data_array = array(
                    'id' => $item['ObjItemList']['id'],
                    'model_name' => $manufacturer . ' ' . $item['ObjItemList']['title'],
                    'name_ru' => $manufacturer . ' ' . $item['Translates']['ObjItemList']['title']['rus'],
                    'name_ro' => $manufacturer . ' ' . $item['Translates']['ObjItemList']['title']['rom'],
                    'price' => $item['ObjItemList']['price'],
                    'currency' => $item['ObjItemList']['currency'],
                    'warranty' => $item['ObjItemList']['data']['wrnt'],
                    'url' => $this->store_url . '/rus/' . $item['ObjItemList']['alias'],
                    'available' => $item['ObjItemList']['status'],
                    'is_visible' => $item['ObjItemList']['status'],
                    'local_delivery_cost' => 0,
                    'moldova_delivery' => 0,
                    'moldova_delivery_cost' => 0,
                );

                if(!empty($item['ObjOptAttachs']['allimages'])){
                    foreach($item['ObjOptAttachs']['allimages'] as $key => $attach){
                        $_xml_data_array['images'][]['image'] = $this->store_url . DS . $attach['location'] . '/large/' . $attach['file'];
                    }
                }

                $xml_data_array[] = $_xml_data_array;
            }
        }

        $this->array_to_xml($xml_data_array, $xml_data, 'product');
        $xml_data_xml = $xml_data->asXML();

        fwrite($fp, $xml_data_xml);
        fclose($fp);

        header('Content-type: application/xml');

        exit($xml_data_xml);
    }

    public function xml_upload_products(){
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        Configure::write('TMP.sql_no_cache', '1');
        Configure::write('PassCurrencyBehaviorbeforeFind', '1');
        Configure::write('PassCurrencyBehaviorafterFind', '1');

        $this->ObjItemList->Behaviors->unload('Search');
        //$this->ObjItemList->Behaviors->unload('Attach');
        //$this->ObjItemList->Behaviors->unload('Relation');
        //$this->ObjItemList->Behaviors->unload('Catalog.Catalog');
        $this->ObjItemList->Behaviors->unload('Currency.Currency');

        //$this->ObjItemList->Behaviors->load('Catalog.Catalog');
        //$this->ObjItemList->Behaviors->load('Specification.Specification');

        $file_name = sys_get_temp_dir() . DS . 'xml_upload_products_' . date("Y-m-d") . '.xml';
        if(file_exists($file_name)){
            if(!empty($_GET['force'])){
                unlink($file_name);
            } else {
                header('Content-type: application/xml');
                exit(file_get_contents($file_name));
            }
        }

        $fp = fopen($file_name, 'w');

        $conditions = array();
        $conditions = array('ObjItemList.status' => '1');

        if(!empty($this->base_id)) $conditions = $conditions + array('ObjItemList.base_id' => $this->base_id);

        $bases = $this->ObjItemTree->find('all', array('conditions' => array('ObjItemTree.status' => '1'), 'order' => array('ObjItemTree.lft' => 'asc')));
        $manufacturers = $this->ObjItemList->find('list', array('tid' => 'manufacturer'));

        $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><products></products>');

        $catalog = $xml_data->addChild('catalog');
        foreach($bases as $base){
            $category = $catalog->addChild('category', $base['ObjItemTree']['title']);
            $category->addAttribute('id', $base['ObjItemTree']['id']);
            if(!empty($base['ObjItemTree']['parent_id'])) $category->addAttribute('parent_id', $base['ObjItemTree']['parent_id']);
        }

        $xml_data_array = array();
        for($ic=0;$ic <= $this->pages;$ic++){
            $limit = ($this->step * $ic) . ",{$this->step}";
            $items = $this->ObjItemList->find('all', array('conditions' => $conditions, 'order' => array('ObjItemList.created' => 'desc'), 'limit' => $limit));
            if(empty($items)) break;
            foreach($items as $item){
                $manufacturer = $manufacturers[$item['ObjItemList']['extra_2']];

                if(empty($item['Translates']['ObjItemList']['title']['rus'])) $item['Translates']['ObjItemList']['title']['rus'] = $item['Translates']['ObjItemList']['title']['rom'];
                if(empty($item['Translates']['ObjItemList']['title']['rom'])) $item['Translates']['ObjItemList']['title']['rom'] = $item['Translates']['ObjItemList']['title']['rus'];

                $_xml_data_array = array(
                    'id' => $item['ObjItemList']['id'],
                    'category_id' => $item['ObjItemList']['base_id'],
                    'vendor_name' => $manufacturer,
                    'model_name' => $manufacturer . ' ' . $item['ObjItemList']['title'],
                    'name_ru' => $manufacturer . ' ' . $item['Translates']['ObjItemList']['title']['rus'],
                    'name_ro' => $manufacturer . ' ' . $item['Translates']['ObjItemList']['title']['rom'],
                    'description_ru' => $item['Translates']['ObjItemList']['list_body']['rus'],
                    'description_ro' => $item['Translates']['ObjItemList']['list_body']['rom'],
                    'price' => $item['ObjItemList']['price'],
                    'currency' => $item['ObjItemList']['currency'],
                    'warranty' => $item['ObjItemList']['data']['wrnt'],
                    'url' => $this->store_url . '/rus/' . $item['ObjItemList']['alias'],
                );

                if(!empty($item['ObjOptAttachs']['allimages'])){
                    foreach($item['ObjOptAttachs']['allimages'] as $key => $attach){
                        $_xml_data_array['images'][]['image'] = $this->store_url . DS . $attach['location'] . '/large/' . $attach['file'];
                    }
                }

                $xml_data_array[] = $_xml_data_array;

                //_pr($_xml_data_array);
                //_pr($item);
                //exit;
                //break;break;

                //$tmp[] = $item['ObjItemList']['id'];
            }
        }

        //pr($tmp);
        //exit;

        $this->array_to_xml($xml_data_array, $xml_data, 'product');
        $xml_data_xml = $xml_data->asXML();

        fwrite($fp, $xml_data_xml);
        fclose($fp);

        header('Content-type: application/xml');

        exit($xml_data_xml);
    }

    function array_to_xml( $data, &$xml_data, $def_key = 'item') {
        foreach( $data as $key => $value ) {
            if( is_numeric($key) ){
                $key = $def_key; //dealing with <0/>..<n/> issues
                //$subnode = $xml_data;
            } else {
                //$subnode = $xml_data->addChild($key);
            }
            if( is_array($value) ) {
                if(count($value) == 1 && !is_numeric(key($value))){
                    $key = key($value);
                    $value = reset($value);
                    //_pr('c1'.$key);
                    //_pr('c1'.$value);
                } else {
                    //_pr('c2'.$key);
                    //_pr('c2'.$value);
                }
                if(is_array($value)){
                        $subnode = $xml_data->addChild($key);
                        $this->array_to_xml($value, $subnode, $key);
                } else {
                    $xml_data->addChild("{$key}", htmlspecialchars("{$value}"));
                }
            } else {
                //_pr('c3'.$key);
                //_pr('c3'.$value);
                $xml_data->addChild("{$key}", htmlspecialchars("{$value}"));
            }
         }
    }

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
            $limit = ($this->step * $ic) . ",{$this->step}";
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
}

<?php
class MigrateController extends CatalogAppController {

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

        exit;
        
        Configure::write('TMP.no_rel_id' , '1');
        
        $this->ObjItemList->Behaviors->load('Catalog.Catalog');
        if(Configure::read('PLUGIN.Specification') == '1') $this->ObjItemList->Behaviors->load('Specification.Specification');
        if(Configure::read('CMS.settings.catalog.obj_combinations') == '1') $this->ObjItemList->Behaviors->load('Catalog.Combination');

    }
    
    public function afterFilter() {
        parent::afterFilter();
        
        Configure::write('TMP.no_rel_id' , '0');
    }

    public function admin_adjust_all(){
        //$this->ExtraData->query("UPDATE `wb_obj_item_list` SET wb_obj_item_list.status = '0' WHERE wb_obj_item_list.extra_3 = '1132' AND wb_obj_item_list.tid = 'catalog_specification' AND wb_obj_item_list.id NOT IN (SELECT wb_obj_opt_relation.value FROM wb_obj_opt_relation WHERE wb_obj_opt_relation.rel_id = wb_obj_item_list.base_id AND wb_obj_opt_relation.type = 'specification' AND wb_obj_opt_relation.value > 0)");
    }

    public function admin_imgs_all(){
        exit;
        App::import('Component', 'Upload');
        $upload_component = new UploadComponent(new ComponentCollection());

        $items = '1';
        while(!empty($items)){
            $items = $this->ObjItemList->ObjOptAttach->find('all', array('conditions' => array('ObjOptAttach.size IS NULL'), 'limit' => 500));
            foreach($items as $item){
                if(in_array(ws_ext($item['ObjOptAttach']['file']), ws_ext_img())) if(!file_exists(UPL_DIR . DS . $item['ObjOptAttach']['location'] . DS . 'thumb' . DS . $item['ObjOptAttach']['file'])){
                    copy(UPL_DIR . DS . $item['ObjOptAttach']['location'] . DS . 'large' . DS . $item['ObjOptAttach']['file'], UPL_DIR . DS . $item['ObjOptAttach']['location'] . DS . 'thumb' . DS . $item['ObjOptAttach']['file']);
                    $upload_component->resize(UPL_DIR . DS . $item['ObjOptAttach']['location'] . DS . 'thumb' . DS . $item['ObjOptAttach']['file'], '400', '400');
                }
                $this->ObjItemList->ObjOptAttach->updateAll(array("ObjOptAttach.size" => sqls(filesize(UPL_DIR . DS . $item['ObjOptAttach']['location'] . DS . 'large' . DS . $item['ObjOptAttach']['file']), true)), array("ObjOptAttach.id" => $item['ObjOptAttach']['id']));
            }
        }
        
        return 'ok';
        
        if(empty($items)){
            Cache::clear();
            exit('END');
        }
        exit('RELOAD<script>window.location.reload();</script>');
    }

    public function admin_tree_all(){
        global $new_items, $items;
        
        $scopefield_key = 'extra_3';
        
        if(empty($items)){
            $tids = $this->ExtraData->query("SELECT DISTINCT(`tid`) FROM `wb_obj_item_tree`");
            foreach($tids as $_tid){
                $tid = $_tid['wb_obj_item_tree']['tid'];

                $scopefields = $this->ExtraData->query("SELECT DISTINCT(`{$scopefield_key}`) FROM `wb_obj_item_tree` WHERE `tid` = '{$tid}'");
                foreach($scopefields as $_scopefield){
                    $scopefield = $_scopefield['wb_obj_item_tree'][$scopefield_key];

                    $items = array();
                    $_items = $this->ExtraData->query("SELECT * FROM `wb_obj_item_tree` WHERE `tid` = '{$tid}' AND `{$scopefield_key}` = '{$scopefield}' ORDER BY `lft` ASC LIMIT 200");
                    foreach($_items as $_item){
                        $_item = $_item['wb_obj_item_tree'];
                        $items[($_item['parent_id'] > 0 ? $_item['parent_id'] : 0)][$_item['id']] = $_item;
                    }
                    
                    $new_items = array();
                    $left = 1;
                    $sDepth = 0;
                    
                    foreach($items[0] as $item){
                        $left = $this->_recursiveArray($item, $sDepth + 1, $left);
                    }
    
                    foreach($new_items as $new_item){
                        $this->ExtraData->query("UPDATE `wb_obj_item_tree` SET lft = '{$new_item['left']}', rght = '{$new_item['right']}' WHERE `tid` = '{$tid}' AND `id` = '{$new_item['id']}'");
                    }

                }
            }
        }
        return 'ok';
    }

    function _recursiveArray($item, $depth, $left) {
        global $new_items, $items;
        
        $right = $left + 1;
        
        if(!empty($items[$item['id']])){
            $depth++;
            foreach($items[$item['id']] as $_item){
                $right = $this->_recursiveArray($_item, $depth, $right);
            }
            $depth--;
        }
        
        $new_items[] = array('id' => $item['id'], 'parent_id' => $item['parent_id'], 'left' => $left, 'right' => $right, 'depth' => $depth);
    
    	$left = $right + 1;
        
    	return $left;
    }

    public function admin_remove_all(){
        //exit;
        if(empty($items)){
            $items = $this->ObjItemList->find('list', array('tid' => 'manufacturer', 'limit' => 100));
            foreach($items as $key => $val){
                $this->ObjItemList->delete($key);
            }
            $step = '1';
        }
        if(empty($items)){
            $items = $this->ObjItemList->find('list', array('tid' => 'catalog', 'limit' => 100));
            foreach($items as $key => $val){
                $this->ObjItemList->delete($key);
            }
            $step = '2';
        }
        if(empty($items)){
            $items = $this->ObjItemTree->find('list', array('tid' => 'catalog', 'limit' => 100));
            foreach($items as $key => $val){
                $this->ObjItemTree->delete($key);
            }
            $step = '3';
        }
        if(empty($items)){
            $spec = ClassRegistry::init('Specification.Specification');
            $items = $spec->find('list', array('tid' => 'catalog_specification', 'limit' => 100));
            foreach($items as $key => $val){
                $spec->delete($key);
            }
            $step = '4';
        }
        if(empty($items)){
            $spec_val = ClassRegistry::init('Specification.SpecificationValue');
            $items = $spec_val->find('list', array('tid' => 'catalog_specification', 'limit' => 100));
            foreach($items as $key => $val){
                $spec_val->delete($key);
            }
            $step = '5';
        }
        if(empty($items)){
            //$this->ExtraData->query("DELETE FROM wb_obj_opt_data WHERE model = 'Specification'");
            $step = '6';
        }
        if(empty($items)){
            //$this->ExtraData->query("DELETE FROM wb_obj_opt_relation WHERE type = 'specification'");
            $step = '7';
        }
        if(empty($items)){
            //$this->ExtraData->query("DELETE FROM wb_cms_i18n WHERE model IN ('Specification', 'SpecificationValue')");
            //$this->ExtraData->query("DELETE FROM wb_cms_i18n WHERE model IN ('ObjOptField')");
            $step = '8';
        }
        if(1==2){
            $items = scandir(UPL_DIR . DS . UPL_IMAGES . DS . 'large' . DS);
            foreach($items as $file){
                unlink(UPL_DIR . DS . UPL_IMAGES . DS . 'large' . DS . $file);
            }
            $items = scandir(UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS);
            foreach($items as $file){
                unlink(UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . $file);
            }
            $step = '9';
        }
        if(empty($items)){
            Cache::clear();
            exit('END');
        }
        exit('RELOAD:'.$step.'<script>window.location.reload();</script>');
    }
    
        
}

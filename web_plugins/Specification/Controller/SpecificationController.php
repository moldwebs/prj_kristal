<?php
class SpecificationController extends SpecificationAppController {

    public $uses = array('Specification.Specification', 'Specification.SpecificationValue', 'ObjOptRelation');

    public $paginate = array(
        'SpecificationValue' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'SpecificationValue.title' => 'asc'
            )
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();

        Configure::write('Config.before_tid', Configure::read('Config.tid'));
        Configure::write('Config.tid', Configure::read('Config.before_tid') . '_specification');

        if(!empty($this->request->query['scopefield'])){
            Configure::write('top_buttons_replace', array(
                'list' => array('title' => ___('Specification') . ' :: ' . ___('List'), 'url' => array('action' => 'index')),
                'section' => array('title' => ___('Section') . ' :: ' . ___('Create'), 'url' => array('action' => 'edit_section')),
                'edit' => array('title' => ___('Specification') . ' :: ' . ___('Create'), 'url' => array('action' => 'edit')),
            ));
        }

        if(isset($this->request->params['admin'])) $this->Specification->Behaviors->attach('Scopefield', array('field' => 'extra_3', 'value' => $this->request->query['scopefield']));

        $this->set('spec_types', array(
            '6' => 'Select',
            '7' => 'Select (Multiple)',
            '2' => 'Numeric',
            '10' => 'Numeric (Multiple)',
            '3' => 'Yes/No',
            '1' => 'Choice',
            '8' => 'Select (Year)',
            '4' => 'Select (Text)',
            '5' => 'Select (Numeric)',
            '9' => 'Select (Images)',
        ));
    }

    public function admin_table_actions(){
        $this->Specification->table_actions($this->request->data);
        $this->Basic->back();
    }

    public function admin_table_structure(){
        $this->Specification->table_structure($this->request->data);
        $this->Basic->back();
    }

    public function admin_index(){
        $this->set('page_title', ___('Specifications') . ' :: ' . ___('List'));

        $this->set('items', $this->Specification->find('all', array('order' => 'Specification.lft')));

        $this->set('bases', $this->ObjItemTree->TreeList(array('ObjItemTree.tid' => Configure::read('Config.before_tid'))));

        //if(!empty($this->request->query['scopefield'])) $this->set('oitems', $this->Specification->find('all', array('conditions' => array('OR' => array("Specification.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'Specification' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', array($this->request->query['scopefield']))."))", "Specification.parent_id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'Specification' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', array($this->request->query['scopefield']))."))")), 'order' => 'Specification.lft')));
    }

    public function admin_edit_section($id = null){
        $this->Basic->save_load($id, $this->Specification, true);

        if($id){
            $this->set('page_title', ___('Section') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Section') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
        $this->set('bases', $this->ObjItemTree->TreeList(array('ObjItemTree.tid' => Configure::read('Config.before_tid'))));
    }

    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->Specification, true);

        if($id){
            $this->set('page_title', ___('Specification') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Specification') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }

        $this->set('parents', $this->Specification->TreeList(array('Specification.extra_1' => '1')));
        $this->set('depends', $this->Specification->TreeList(array('Specification.extra_1 <>' => '1', 'Specification.id <>' => $id)));

        $this->set('bases', $this->ObjItemTree->TreeList(array('ObjItemTree.tid' => Configure::read('Config.before_tid'))));
    }

	public function admin_delete($id = null){
	    $this->Specification->delete($id);
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->Specification->toggle($id, 'status');
        $this->Basic->back();
    }

    public function admin_set_description($id = null){
        $this->Specification->datatoggle($id, 'in_desc');
        $this->Basic->back();
    }

    public function admin_set_filter($id = null){
        $this->Specification->datatoggle($id, 'in_filter');
        $this->Basic->back();
    }

    public function admin_set_option($id = null){
        $this->Specification->datatoggle($id, 'in_option');
        $this->Basic->back();
    }

    // VALUES ---------------------------------------------------------------------

    public function admin_value_table_actions(){
        $this->SpecificationValue->table_actions($this->request->data);
        $this->Basic->back();
    }

    public function admin_value_index($spec_id = null){
        $this->set('page_title', ___('Values') . ' :: ' . ___('List'));

        $this->SpecificationValue->virtualFields['count'] = "(SELECT COUNT(*) FROM `wb_obj_opt_relation` WHERE type = 'specification' AND rel_id = SpecificationValue.base_id AND value = SpecificationValue.id)";

        $depend_id = $this->Specification->fread('extra_4', $spec_id);
        if(!empty($depend_id)){
            $this->set('depends', $this->SpecificationValue->find('list', array('conditions' => array('SpecificationValue.base_id' => $depend_id), 'order' => array('SpecificationValue.title' => 'asc'))));
        }

        $this->set('items', $this->paginate('SpecificationValue', am($this->Basic->filters('SpecificationValue'), array('SpecificationValue.base_id' => $spec_id))));
    }

    public function admin_value_edit($id = null){
        if(strpos($this->request->data['SpecificationValue']['title'], ';') !== false){
            $tmp = explode(';', $this->request->data['SpecificationValue']['title']);
            $this->request->data['SpecificationValue']['title'] = $tmp[0];
            foreach(array_keys(Configure::read('CMS.activelanguages')) as $locale_ind => $locale){
                $this->request->data['Translates']['SpecificationValue']['title'][$locale] = $tmp[$locale_ind];
            }
        }

        $this->Basic->save_load($id, $this->SpecificationValue, true);

        $depend_id = $this->Specification->fread('extra_4', $this->data['SpecificationValue']['base_id']);
        if(!empty($depend_id)){
            $this->set('depends', $this->SpecificationValue->find('list', array('conditions' => array('SpecificationValue.base_id' => $depend_id), 'order' => array('SpecificationValue.title' => 'asc'))));
        }

        if($id){
            $this->set('page_title', ___('Values') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Values') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

	public function admin_value_order($id = null, $order = null){
        $this->SpecificationValue->updateAll(array("SpecificationValue.order_id" => sqls($order, true)), array("SpecificationValue.id" => $id));
        $this->Basic->back();
	}

	public function admin_value_delete($id = null){
	    $this->SpecificationValue->delete($id);
        $this->Basic->back();
	}

    public function admin_value_status($id = null){
        $this->SpecificationValue->toggle($id, 'status');
        $this->Basic->back();
    }

    public function admin_value_img_index($spec_id = null){
        $this->set('page_title', ___('Values') . ' :: ' . ___('List'));

        $this->set('items', $this->paginate('SpecificationValue', am($this->Basic->filters('SpecificationValue'), array('SpecificationValue.base_id' => $spec_id))));
    }

    public function admin_value_img_edit($id = null){
        $this->Basic->save_load($id, $this->SpecificationValue, true);

        if($id){
            $this->set('page_title', ___('Values') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Values') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

	public function admin_value_img_delete($id = null){
	    $this->SpecificationValue->delete($id);
        $this->Basic->back();
	}

    public function admin_value_img_status($id = null){
        $this->SpecificationValue->toggle($id, 'status');
        $this->Basic->back();
    }

    public function item($base_id = null, $tp = '1'){
        $this->Specification->Behaviors->detach('Scopefield');

        //Cache::clear();
        Configure::write('TMP.sql_no_cache', '1');

        Configure::write('Config.tid', Configure::read('Config.before_tid'));
        $this->ObjItemTree->st_cond = 1;
        $bases = am(array('NULL'), Set::extract('/ObjItemTree/id', $this->ObjItemTree->getPath($base_id, 'id')));

        Configure::write('Config.tid', Configure::read('Config.before_tid') . '_specification');

        $items = $this->Specification->find('allindex', array('conditions' => array('OR' => array('Specification.extra_3' => $bases, "Specification.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'Specification' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', $bases)."))", "Specification.parent_id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'Specification' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', $bases)."))"), 'Specification.status' => '1'), 'order' => array('FIELD(Specification.extra_3, '.sqlimplode(',', $bases).')', 'Specification.lft')));
        foreach($items as $key => $val){
            if($tp == '2' && empty($val['Specification']['data']['in_option'])) unset($items[$key]);
            //if($tp != '2' && !empty($val['Specification']['data']['in_option'])) unset($items[$key]);
        }
        $options = $this->SpecificationValue->find('list', array('fields' => array('SpecificationValue.id', 'SpecificationValue.title', 'SpecificationValue.base_id'), 'conditions' => array('SpecificationValue.base_id' => Set::extract('/Specification[extra_4<1]/id', $items), 'SpecificationValue.status' => '1'), 'order' => array('SpecificationValue.title' => 'asc')));
        foreach($options as $id => $option){
            $items[$id]['SpecificationValue'] = $option;
        }

        $this->set('items', $items);

        $values = json_decode(base64_decode(urldecode($_GET['data'])), true);
        $this->request->data['RelationValue']['specification'] = $values;
    }

    public function get_list($base_id = null, $all = 0){

        if($base_id == 'active') $base_id = $this->cms['active_base'];

        $this->Specification->Behaviors->detach('Scopefield');

        Configure::write('Config.tid', Configure::read('Config.before_tid'));
        $base_rel_id = $this->ObjItemTree->field('rel_id', array('ObjItemTree.id' => $base_id));
        if(!empty($base_rel_id)) $base_id = $base_rel_id;
        $bases = am(array('NULL'), Set::extract('/ObjItemTree/id', $this->ObjItemTree->getPath($base_id, 'id')));

        Configure::write('Config.tid', Configure::read('Config.before_tid') . '_specification');

        $items = $this->Specification->find('allindex', array('conditions' => array('OR' => array('Specification.extra_3' => $bases, "Specification.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'Specification' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', $bases)."))", "Specification.parent_id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = 'Specification' AND `type` = 'base_id' AND `rel_id` IN (".sqlimplode(',', $bases)."))"), 'Specification.status' => '1'), 'order' => array('FIELD(Specification.extra_3, '.sqlimplode(',', $bases).')', 'Specification.lft')));
        $options = $this->SpecificationValue->find('list', array('fields' => array('SpecificationValue.id', 'SpecificationValue.title', 'SpecificationValue.base_id'), 'conditions' => array('SpecificationValue.base_id' => (!empty($all) ? Set::extract('/Specification/id', $items) : Set::extract('/Specification[extra_4<1]/id', $items)), 'SpecificationValue.status' => '1'), 'order' => array('SpecificationValue.order_id' => 'asc', 'SpecificationValue.title' => 'asc')));
        $options_images = $this->SpecificationValue->find('all', array('conditions' => array('SpecificationValue.base_id' => Set::extract('/Specification[extra_2=9]/id', $items), 'SpecificationValue.status' => '1'), 'order' => array('SpecificationValue.title' => 'asc')));
        $options_values = $this->ObjOptRelation->find('list', array('virtual' => array('cnt' => 'COUNT(*)'), 'fields' => array('ObjOptRelation.value', 'ObjOptRelation.cnt', 'ObjOptRelation.rel_id'), 'conditions' => array('ObjOptRelation.rel_id' => Set::extract('/Specification[extra_4<1]/id', $items), 'ObjOptRelation.type' => 'specification', "ObjOptRelation.value != ''", "ObjOptRelation.foreign_key IN (SELECT id FROM wb_obj_item_list WHERE `status` = 1)"), 'group' => array('ObjOptRelation.rel_id', 'ObjOptRelation.value')));
        $maxs = $this->ObjOptRelation->find('ialllist', array('fields' => array('ObjOptRelation.rel_id', 'MAX(ObjOptRelation.value*1) AS ObjOptRelation__maxval'), 'conditions' => array('ObjOptRelation.type' => 'specification', 'ObjOptRelation.rel_id' => Set::extract('/Specification[extra_2=2]/id', $items)), 'group' => array('ObjOptRelation.rel_id')));

        foreach($items as $key => $val){
            if($val['Specification']['extra_2'] == '3'){
                $options[$key] = array('1' => ___('Yes'), '0' => ___('No'));
            }
        }

        foreach($options_values as $spec_id => $vals){
            $spec_values = array();
            $spec_values_count = array();
            if(!empty($options[$spec_id])){
                foreach($options[$spec_id] as $key => $val){
                    if($vals[$key] > 0){
                        $spec_values[$key] = $val;
                        $spec_values_count[$key] = $vals[$key];
                    }
                }
            } else {
                foreach($vals as $key => $val){
                    if($val > 0){
                        $spec_values[$key] = $key;
                        $spec_values_count[$key] = $val;
                    }
                }
                if(!empty($spec_values)) natsort($spec_values);
            }

            if(!empty($spec_values)){
                $items[$spec_id]['SpecificationValue'] = $spec_values;
                $items[$spec_id]['SpecificationCount'] = $spec_values_count;
            }
        }

        foreach($options_images as $option_image){
            if(empty($option_image['ObjOptAttachDef']['file']) && !empty($option_image['SpecificationValue']['data']['img_color'])){
                $option_image['ObjOptAttachDef']['attach'] = 'get_color_' . strtolower($option_image['SpecificationValue']['data']['img_color']) . '.png';
                $option_image['ObjOptAttachDef']['file'] = 'get_color_' . strtolower($option_image['SpecificationValue']['data']['img_color']) . '.png';
                $option_image['ObjOptAttachDef']['color'] = $option_image['SpecificationValue']['data']['img_color'];
            }
            if(!empty($option_image['ObjOptAttachDef']['file'])) $items[$option_image['SpecificationValue']['base_id']]['SpecificationValueImage'][$option_image['SpecificationValue']['id']] = $option_image['ObjOptAttachDef'];
        }

        foreach($maxs as $id => $max){
            $items[$id]['SpecificationMaxValue'] = $max;
        }

        foreach($items as $key => $val){
            if(in_array($val['Specification']['extra_2'], array('1', '3', '5', '6', '7', '8', '10'))){
                $items[$key]['Specification']['tp_fltr'] = 'select';
            }
            if(in_array($val['Specification']['extra_2'], array('2'))){
                $items[$key]['Specification']['tp_fltr'] = 'range';
            }
            if(in_array($val['Specification']['extra_2'], array('4'))){
                $items[$key]['Specification']['tp_fltr'] = 'select';
            }
            if(in_array($val['Specification']['extra_2'], array('9'))){
                $items[$key]['Specification']['tp_fltr'] = 'color';
            }

            if(!$all && $items[$key]['Specification']['tp_fltr'] == 'select'){
                if(empty($items[$key]['SpecificationValue'])) unset($items[$key]);
            }
        }

        return $items;
    }


    public function get_depends($depend_id = null){
        if(strpos($depend_id, ',') !== false) $depend_id = explode(',', $depend_id);
        Configure::write('Config.tid', Configure::read('Config.before_tid') . '_specification');
        $_options = $this->SpecificationValue->find('list', array('fields' => array('SpecificationValue.id', 'SpecificationValue.title'), 'conditions' => array('SpecificationValue.status' => '1', 'SpecificationValue.extra_4' => $depend_id), 'order' => array('SpecificationValue.title' => 'asc')));
        $options = array();
        foreach($_options as $key => $val) $options[] = array('id' => $key, 'title' => $val);
        exit(json_encode($options));
    }

}

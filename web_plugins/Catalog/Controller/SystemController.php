<?php
class SystemController extends CatalogAppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function admin_settings(){
        $this->Basic->simple_add_settings(Configure::read('Config.tid'), $this->CmsSetting);
    }

    public function admin_get_blocks(){
        $this->layout = false;
        
        $this->data = $this->ObjItemTree->findById($_GET['id']);
        
        $options = array(
            '['.Configure::read('Config.tid').'/items][/'.Configure::read('Config.tid').'/item/get_list/related]' => ___('Related items'), 
            '['.Configure::read('Config.tid').'/items][/'.Configure::read('Config.tid').'/item/get_list/similar]' => ___('Similar items'), 
            '['.Configure::read('Config.tid').'/items][/'.Configure::read('Config.tid').'/item/get_list/category]' => ___('Category items'), 
            '['.Configure::read('Config.tid').'/search]' => ___('Search'), 
            '['.Configure::read('Config.tid').'/latest_comments][/'.Configure::read('Config.tid').'/comment/get_list/]' => ___('Latest comments'), 
            '['.Configure::read('Config.tid').'/comments][/'.Configure::read('Config.tid').'/comment/get_list/active/]' => ___('Input comments'), 
            '['.Configure::read('Config.tid').'/manufacturers][/' . Configure::read('Config.tid') . '/manufacturer/get_list/]' => ___('Manufacturers'), 
            '['.Configure::read('Config.tid').'/cmanufacturers][/' . Configure::read('Config.tid') . '/manufacturer/get_clist/]' => ___('Manufacturers Logos'), 
            '['.Configure::read('Config.tid').'/menu][/' . Configure::read('Config.tid') . '/base/get_list/]' => ___('Categories'), 
            '['.Configure::read('Config.tid').'/menu][/' . Configure::read('Config.tid') . '/base/get_list/active]' => ___('Child categories of active'),
            '['.Configure::read('Config.tid').'/bases][/' . Configure::read('Config.tid') . '/base/get_list/]' => ___('Categories (items mode)'), 
            '['.Configure::read('Config.tid').'/bases][/' . Configure::read('Config.tid') . '/base/get_list/active]' => ___('Child categories of active (items mode)'),
            '['.Configure::read('Config.tid').'/bases][/' . Configure::read('Config.tid') . '/base/get_list_type/]' => ___('Categories (type mode)'), 
            '['.Configure::read('Config.tid').'/bases][/' . Configure::read('Config.tid') . '/base/get_list_type/active]' => ___('Child categories of active (type mode)'),
        );

        if(Configure::read('PLUGIN.Specification') == '1') $options['['.Configure::read('Config.tid').'/filter][/'.Configure::read('Config.tid').'/specification/get_list/active]'] = ___('Filter');

        $options[___('Items from')]['['.Configure::read('Config.tid').'/items][/'.Configure::read('Config.tid').'/item/get_list]'] = ___('All categories');
        $options[___('Items from')]['['.Configure::read('Config.tid').'/items][/'.Configure::read('Config.tid').'/item/get_list/active]'] = ___('Active category');
        $options[___('Items from')]['['.Configure::read('Config.tid').'/items][/'.Configure::read('Config.tid').'/item/get_list/group]'] = ___('Group categories');
        
        $bases = $this->ObjItemTree->TreeList();
        foreach($bases as $key => $val){
            $options[___('Items of category')]['['.Configure::read('Config.tid').'/items][/'.Configure::read('Config.tid').'/item/get_list/'.$key.']'] = $val;
            $options[___('Categories of category')]['['.Configure::read('Config.tid').'/cbases][/'.Configure::read('Config.tid').'/base/get_clist/'.$key.']'] = $val;
        }

        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.mod_show', array('label' => ___('Show'), 'options' => $options, 'empty' => ___('Select...'), 'class' => 'req'));
        echo '<div class="n4 cl">';
        echo $form->input('ObjItemTree.data.mod_order', array('label' => ___('Order'), 'options' => Configure::read('CMS.catalog.mod_order_types'), 'class' => 'req'));
        echo $form->input('ObjItemTree.data.mod_limit', array('label' => ___('Limit'), 'value' => (empty($this->data['ObjItemTree']['data']['mod_limit']) ? '3' : $this->data['ObjItemTree']['data']['mod_limit']), 'class' => 'req'));
        echo '</div>';
        $types = $this->ObjOptType->find('allindex');
        if(!empty($types)) echo $form->input('ObjItemTree.data.mod_type', array('label' => ___('Type'), 'options' => ws_qlist($types), 'multiple' => 'multiple'));
        echo '</div>';
        exit;
    }

    public function admin_get_links(){
        $this->layout = false;
        
        $this->data = $this->ObjItemTree->findById($_GET['id']);
        
        $options = array(
            '/'.Configure::read('Config.tid'). '/' . "{".Configure::read('Config.tid').".CmsSetting.0}" => ___('Module page'),
            '/'.Configure::read('Config.tid') . '/' . 'wishlist' => ___('Wishlist'),
            '/'.Configure::read('Config.tid') . '/' . 'compare' => ___('Compare'),
            '/'.Configure::read('Config.tid'). '/' . "{".Configure::read('Config.tid').".CmsSetting.0}" . '/' . '?sort=created&direction=desc' => ___('New items'),
            '/'.Configure::read('Config.tid'). '/' . "{".Configure::read('Config.tid').".CmsSetting.0}" . '/' . '?sort=views&direction=desc' => ___('Popular items'),
            '/'.Configure::read('Config.tid'). '/' . "{".Configure::read('Config.tid').".CmsSetting.0}" . '/' . '?sort=created&direction=desc&fltr_dt__old_price=any' => ___('Discount items'),
        );

        $types = ws_qlist($this->ObjOptType->find('allindex'));
        foreach($types as $key => $val){
            $options[___('Type')]['/'.Configure::read('Config.tid').'/'."{".Configure::read('Config.tid').".CmsSetting.0}".'?fltr_eqorrel__extra_1=' . $key] = $val;
        }
        
        $bases = $this->ObjItemTree->TreeList();
        foreach($bases as $key => $val){
            $options[___('Category')]['/'.Configure::read('Config.tid').'/item/index/' . $key . "{".Configure::read('Config.tid').".ObjItemTree.{$key}}"] = $val;
        }

        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.url', array('label' => ___('Category'), 'options' => $options, 'class' => 'req'));
        echo '</div>';
        exit;
    }

    public function admin_get_linkset(){
        $this->layout = false;
        
        $this->data = $this->ObjItemTree->findById($_GET['id']);
        
        $options = array('/' . Configure::read('Config.tid') . '/base/get_list/' => ___('All categories'));

        $bases = $this->ObjItemTree->TreeList();
        foreach($bases as $key => $val){
            $options['/' . Configure::read('Config.tid') . '/base/get_list/' . $key] = $val;
        }

        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.mod_show', array('label' => ___('Show'), 'options' => $options, 'class' => 'req'));
        echo $form->input('ObjItemTree.data.mod_show_mode', array('label' => ___('Mode'), 'options' => array('0' => ___('All items'), '1' => ___('Items of active'), '2' => ___('Items of active with parent'), '3' => ___('Childs of active'), '4' => ___('Active item with childs'), '5' => ___('Childs of active or active'), 's1' => ___('Show from level 1'), 's2' => ___('Show from level 2'), 's3' => ___('Show from level 3'), 's4' => ___('Show from level 4'), 's5' => ___('Show from level 5'), 's6' => ___('Show from level 6'), 's7' => ___('Show from level 7'), 's8' => ___('Show from level 8'), 's9' => ___('Show from level 9'), 's10' => ___('Show from level 10'))));
        echo '</div>';
        exit;
    }
}

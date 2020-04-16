<?php
class SystemController extends BaseAppController {

    public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
    }

    function admin_settings(){
        $this->Basic->simple_add_settings('base', $this->CmsSetting);
    }

    public function admin_get_blocks(){
        $this->layout = false;

        //$this->data = json_decode(base64_decode(urldecode($_GET['data'])), true);
        $this->data = $this->ObjItemTree->findById($_GET['id']);

        $options = array('[base/language]' => ___('Language'), '[base/search]' => ___('Search'), '[base/breadcrumbs]' => ___('Breadcrumbs'));

        $menus = $this->ObjItemTree->find('list', array('tid' => 'cms_link', 'conditions' => array('extra_1' => '1')));
        foreach($menus as $key => $val){
            $options[___('Menu')]['[menu]['.$key.']'] = $val;
        }

        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.mod_show', array('label' => ___('Show'), 'options' => $options, 'empty' => ___('Select...'), 'class' => 'req'));
        echo '</div>';
        exit;
    }

    public function admin_get_links(){
        $this->layout = false;

        //$this->data = json_decode(base64_decode(urldecode($_GET['data'])), true);
        $this->data = $this->ObjItemTree->findById($_GET['id']);

        $options = array('/' => ___('Home page'), '/search/' => ___('Search'));

        $menus = $this->ObjItemTree->find('list', array('tid' => 'cms_link', 'conditions' => array('extra_1' => '2')));
        foreach($menus as $key => $val){
            $options[___('Page')]['/pages/' . $key . "{cms_link.ObjItemTree.{$key}}"] = $val;
        }

        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.url', array('label' => ___('Show'), 'options' => $options, 'empty' => ___('Select...'), 'class' => 'req'));
        echo '</div>';
        exit;
    }

    public function admin_get_linkset(){
        $this->layout = false;

        //$this->data = json_decode(base64_decode(urldecode($_GET['data'])), true);
        $this->data = $this->ObjItemTree->findById($_GET['id']);

        $options = array();

        $menus = $this->ObjItemTree->find('list', array('tid' => 'cms_link', 'conditions' => array('extra_1' => '1')));
        foreach($menus as $key => $val){
            $options[___('Menu')]['/base/link/get_list/' . $key] = $val;
        }

        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.mod_show', array('label' => ___('Show'), 'options' => $options, 'empty' => ___('Select...'), 'class' => 'req'));
        echo $form->input('ObjItemTree.data.mod_show_mode', array('label' => ___('Mode'), 'options' => array('0' => ___('All items'), '1' => ___('Items of active'), '2' => ___('Items of active with parent'), '3' => ___('Childs of active'), '4' => ___('Active item with childs'), '5' => ___('Childs of active or active'), 's1' => ___('Show from level 1'), 's2' => ___('Show from level 2'), 's3' => ___('Show from level 3'), 's4' => ___('Show from level 4'), 's5' => ___('Show from level 5'), 's6' => ___('Show from level 6'), 's7' => ___('Show from level 7'), 's8' => ___('Show from level 8'), 's9' => ___('Show from level 9'), 's10' => ___('Show from level 10'))));
        echo '</div>';
        exit;
    }
}

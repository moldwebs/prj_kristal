<?php
class SystemController extends ShopAppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function admin_settings(){
        $this->Basic->simple_add_settings(Configure::read('Config.tid'), $this->CmsSetting);
    }

    public function admin_get_blocks(){
        $this->layout = false;
        
        $this->data = $this->ObjItemTree->findById($_GET['id']);
        
        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.mod_show', array('options' => array(
            '[shop/basket][/shop/basket/get_list]' => ___('Basket'), 
        ), 'label' => ___('Show'), 'empty' => ___('Select...'), 'class' => 'req'));
        echo '</div>';
        exit;
    }

    public function admin_get_links(){
        $this->layout = false;
        
        $this->data = $this->ObjItemTree->findById($_GET['id']);
        
        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.url', array('options' => array(
            '/shop/basket' => ___('Basket'),
            '/shop/order/history' => ___('Orders'),
            '/shop/order/saved' => ___('Saved'),
        ), 'label' => ___('Show'), 'empty' => ___('Select...'), 'class' => 'req'));
        echo '</div>';
        exit;
    }

}

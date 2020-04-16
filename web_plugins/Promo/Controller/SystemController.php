<?php
class SystemController extends PromoAppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function admin_get_blocks(){
        $this->layout = false;
        
        $this->data = $this->ObjItemTree->findById($_GET['id']);
        
        $options = array();

        //$bases = $this->ObjItemTree->TreeList(array('extra_1' => '1'));
        $bases = $this->ObjItemTree->find('all', array('conditions' => array('extra_1' => '1')));
        foreach($bases as $key => $val){
            $options['[promo/items,'.$val['ObjItemTree']['code'].'][/promo/promo_item/get_list/'.$val['ObjItemTree']['id'].']'] = $val['ObjItemTree']['title'];
        }

        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.mod_show', array('label' => ___('Show'), 'options' => $options, 'empty' => ___('Select...'), 'class' => 'req'));
        echo '</div>';
        exit;
    }
}

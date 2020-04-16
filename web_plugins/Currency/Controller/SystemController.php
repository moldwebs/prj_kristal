<?php
class SystemController extends CurrencyAppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function admin_get_blocks(){
        $this->layout = false;
        
        $this->data = $this->ObjItemTree->findById($_GET['id']);
        
        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.mod_show', array('options' => array(
            '[currency/currency]' => ___('Currency'), 
        ), 'label' => ___('Show'), 'empty' => ___('Select...'), 'class' => 'req'));
        echo '</div>';
        exit;
    }

}

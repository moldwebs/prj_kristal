<?php
class SystemController extends UsersAppController {

    public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
    }   
    
     public function admin_settings(){
        $this->Basic->simple_add_settings('users', $this->CmsSetting);
    }
    
    public function admin_get_blocks(){
        $this->layout = false;
        
        $this->data = $this->ObjItemTree->findById($_GET['id']);
        
        $view = new View($this);
        $form = $view->loadHelper('Form');
        echo '<div class="n2 cl">';
        echo $form->input('ObjItemTree.data.mod_show', array('options' => array(
            '[users/newsletters]' => ___('Newsletters'), 
            '[users/login]' => ___('Authorization'), 
            '[users/online]' => ___('Online Users')
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
            '/users/data/' => ___('Data'), 
            '/users/password/' => ___('Password'), 
            '/users/register/' => ___('Register'), 
            '/users/login/' => ___('Login'), 
            '/users/logout/' => ___('Logout')
        ), 'label' => ___('Show'), 'empty' => ___('Select...'), 'class' => 'req'));
        echo '</div>';
        exit;
    }
}

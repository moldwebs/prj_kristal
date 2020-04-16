<?php
class User extends UsersAppModel {
    
    public $useTable = 'wb_user';
    public $nocache = '1';

    public $actsAs = array('Fields', 'Data', 'Relation', 'Attach');

    public $belongsTo = array(
        'UserRole' => array(
            'className'    => 'Users.UserRole',
            'foreignKey' => 'role',
        )
    );

    var $validate = array(
        'usermail' => array(
            'usermail-1' => array(
                'rule' => array('valid_usermail'),
                'message' => 'Invalid email.'
            ),
            'usermail-2' => array(
                'rule' => array('dupl_usermail'),
                'message' => 'Duplicate email.'
            ),
        ),
        /*
        'username' => array(
            'username-1' => array(
                'rule' => array('valid_username'),
                'message' => 'Invalid name.'
            ),
            'username-2' => array(
                'rule' => array('dupl_username'),
                'message' => 'Duplicate name.'
            ),
        ),
        */
        'rpassword' => array(
            'rule' => array('valid_rpassword'),
            'message' => 'Invalid password.'
        ),
        'opassword' => array(
            'rule' => array('valid_opassword'),
            'message' => 'Invalid password.'
        ),
	);
    
    function valid_usermail(){
        if(!empty($this->data['User']['usermail'])){
            return Validation::email($this->data['User']['usermail']);
        }
        return true;
    }
    
    function valid_username(){
        return Validation::alphaNumeric($this->data['User']['username']);
    }

    function dupl_usermail(){
        if(!empty($this->data['User']['usermail'])){
            if($this->find('count', array('st_cond' => '1', 'conditions' => array('User.id <>' => $this->data['User']['id'], 'User.usermail' => $this->data['User']['usermail']))) > 0) return false;
        }
        return true;
    }
    
    function dupl_username(){
        if($this->find('count', array('st_cond' => '1', 'conditions' => array('User.id <>' => $this->data['User']['id'], 'User.username' => $this->data['User']['username'], 'User.usermail' => $this->data['User']['usermail']))) > 0) return false;
        return true;
    }
    
    function valid_rpassword(){
        if($this->data['User']['id'] > 0){
           if(!empty($this->data['User']['password'])) if($this->data['User']['rpassword'] != $this->data['User']['password']) return false;
        } else {
            if($this->data['User']['rpassword'] != $this->data['User']['password']) return false;
        }
        return true;
    }

    function valid_opassword(){
        if($this->data['User']['id'] > 0){
            $opassword = $this->field('password', array('User.id' => $this->data['User']['id']));
            if(empty($opassword) || $opassword == AuthComponent::password($this->data['User']['opassword']) || $this->data['User']['no_passw'] == '1') return true;
            return false;
        } else {
            return true;
        }
    }
    
    public function afterValidate(){
        if (!empty($this->data['User']['rpassword'])) unset($this->data['User']['rpassword']);
        if (!empty($this->data['User']['opassword'])) unset($this->data['User']['opassword']);
    }

    public function beforeSave() {
        if (!empty($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        } else {
            unset($this->data['User']['password']);
        }
        return true;
    }
}
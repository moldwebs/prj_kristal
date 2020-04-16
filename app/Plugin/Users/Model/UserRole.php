<?php
class UserRole extends UsersAppModel {
    
    public $useTable = 'wb_user_role';
    
    public $actsAs = array('Translate' => array('title'));

}
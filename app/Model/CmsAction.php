<?php
class CmsAction extends AppModel {
    
    public $useTable = 'wb_cms_actions';

    public $belongsTo = array(
        'User' => array(
            'className'    => 'Users.User',
            'foreignKey' => 'user_id',
        ),
    );
}
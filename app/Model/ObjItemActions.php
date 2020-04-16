<?php
class ObjItemActions extends AppModel {
    
    public $useTable = 'wb_obj_item_actions';

    public $belongsTo = array(
        'User' => array(
            'className'    => 'Users.User',
            'foreignKey' => 'user_id',
        ),
    );
}
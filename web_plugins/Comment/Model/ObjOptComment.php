<?php
class ObjOptComment extends CommentAppModel {
    
    public $useTable = 'wb_obj_opt_comment';

    public $actsAs = array('Tree', 'Tid', 'Data', 'Attach');

    public $recursive = 2;

    public $belongsTo = array(
        'User' => array(
            'className'    => 'Users.User',
            'foreignKey' => 'userid',
        )
    );
}
<?php
class ObjItemTreeNull extends AppModel {
    
    public $useTable = 'wb_obj_item_tree';
    
    public $alias = 'ObjItemTree';
    
    public $actsAs = array('Tree', 'Tid', 'Alias', 'Translate' => array('title', 'alias', 'title_prod'), 'Fields', 'Data');
}
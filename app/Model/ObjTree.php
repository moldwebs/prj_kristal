<?php
class ObjTree extends AppModel {
    
    public $useTable = 'wb_obj_item_tree';
    
    public $alias = 'ObjTree';
    
    public $qalias = 'ObjItemList';
    
    public $actsAs = array('Tree', 'Tid', 'Data');
}
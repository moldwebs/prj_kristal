<?php
class ObjList extends AppModel {
    
    public $useTable = 'wb_obj_item_list';
    
    public $alias = 'ObjList';
    
    public $qalias = 'ObjItemList';
    
    public $actsAs = array('Tid', 'Data');


}
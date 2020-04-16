<?php
class ObjItemListNull extends AppModel {
    
    public $useTable = 'wb_obj_item_list';
    
    public $alias = 'ObjItemList';
    
    public $actsAs = array('Tid', 'Alias', 'Translate' => array('title', 'alias'), 'Fields', 'Data');


}
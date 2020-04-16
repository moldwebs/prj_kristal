<?php
class ObjItemTree extends AppModel {
    
    public $useTable = 'wb_obj_item_tree';
    
    public $actsAs = array('Tree', 'Tid', 'Alias', 'Translate' => array('title', 'alias', 'title_prod'), 'Search' => array('title', 'body'), 'Fields', 'Data', 'Relation', 'Attach');

    public $recursive = 2;
}
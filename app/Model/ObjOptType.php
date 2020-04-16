<?php
class ObjOptType extends AppModel {
    
    public $useTable = 'wb_obj_opt_type';
    
    public $actsAs = array('Tid', 'Translate' => array('title'), 'Attach', 'Data');

    public function afterDelete(){
        Classregistry::init('ObjOptRelation')->deleteAll(array('ObjOptRelation.type' => 'item_type', 'ObjOptRelation.rel_id' => $this->id));
    }
    
}
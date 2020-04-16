<?php
class ObjOptTag extends AppModel {
    
    public $useTable = 'wb_obj_opt_tag';
    
    public $actsAs = array('Tid');

    public function afterDelete(){
        Classregistry::init('ObjOptRelation')->deleteAll(array('ObjOptRelation.type' => 'item_tag', 'ObjOptRelation.rel_id' => $this->id));
    }
    
}
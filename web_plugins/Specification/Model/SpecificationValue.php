<?php
class SpecificationValue extends SpecificationAppModel {
    
    public $useTable = 'wb_obj_item_list';
    
    public $actsAs = array('Tid', 'Translate' => array('title'), 'Fields', 'Data', 'Ordered', 'Attach');

    public $recursive = 2;

    public function afterDelete(){
        $this->getEventManager()->dispatch(new CakeEvent('SpecificationValue.delete', null, array('item_id' => $this->id)));
    }
}
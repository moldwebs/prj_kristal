<?php
    $this->set('fill_types', $this->ObjOptType->find('allindex', array('tid' => false, 'order' => array('ObjOptType.order_id' => 'asc'))));
    Configure::write('Obj.types', $this->ObjOptType->find('list', array('fields' => array('ObjOptType.id', 'ObjOptType.order_id', 'ObjOptType.tid'))));
?>
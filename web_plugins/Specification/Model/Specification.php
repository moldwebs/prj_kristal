<?php
class Specification extends SpecificationAppModel {
    
    public $useTable = 'wb_obj_item_tree';
    
    public $actsAs = array('Tree', 'Tid', 'Translate' => array('title'), 'Fields', 'Data', 'Relation');

    public $recursive = 2;

    public function afterFind($results, $primary = false) {
        if(!empty($results)){
            foreach($results as $key => $result){
                if(!empty($results[$key]['Specification']['measure'])){
                    $results[$key]['Specification']['mtitle'] = $results[$key]['Specification']['title'] . " ({$results[$key]['Specification']['measure']})";
                    $results[$key]['Specification']['ctitle'] = $results[$key]['Specification']['title'] . ", {$results[$key]['Specification']['measure']}";
                } else {
                    $results[$key]['Specification']['mtitle'] = $results[$key]['Specification']['title'];
                    $results[$key]['Specification']['ctitle'] = $results[$key]['Specification']['title'];
                }
            }
        }
        return $results;
    }  

    public function afterDelete(){
        $SpecificationValue = Classregistry::init('Specification.SpecificationValue');
        foreach($SpecificationValue->find('list', array('fields' => array('SpecificationValue.id', 'SpecificationValue.id'), 'conditions' => array('SpecificationValue.base_id' => $this->id))) as $key){
            $SpecificationValue->delete($key);
        }
        $this->getEventManager()->dispatch(new CakeEvent('Specification.delete', null, array('item_id' => $this->id)));
    }
}
<?php
class ObjOptReview extends AppModel {
    
    public $useTable = 'wb_obj_opt_comment';

    public $actsAs = array('Tid', 'Data', 'Attach');

    public $recursive = 2;

    public $belongsTo = array(
        'User' => array(
            'className'    => 'Users.User',
            'foreignKey' => 'userid',
        )
    );

    public function beforeSave($options = array()) {
        if(!empty($this->data['ObjOptReview']['data']['rating'])){
            $this->data['ObjOptReview']['rate'] = round(array_sum($this->data['ObjOptReview']['data']['rating'])/count($this->data['ObjOptReview']['data']['rating']), 2);
        }
        return true;
    }

	public function afterSave($created) {
        $item_id = $this->data['ObjOptReview']['item_id'];
        if(empty($item_id)) $item_id = $this->read()['ObjOptReview']['item_id'];
        $this->getEventManager()->dispatch(new CakeEvent('Review.count', null, array('tid' => Configure::read('Config.tid'), 'item_id' => $item_id)));
        return true;
	}
    
    public function beforeDelete() {
        $this->item_id = $this->read()['ObjOptReview']['item_id'];
    }

    public function afterDelete(){
        $item_id = $this->item_id;
        $this->getEventManager()->dispatch(new CakeEvent('Review.count', null, array('tid' => Configure::read('Config.tid'), 'item_id' => $item_id)));
        return true;
    }
}
<?php
    $expires = $this->ExtraData->query("SELECT * FROM `wb_obj_opt_relation` WHERE `expire` IS NOT NULL AND `type` = 'event_expire' AND `expire` <= NOW()");
    if(!empty($expires)) foreach($expires as $expire){
        if($expire['wb_obj_opt_relation']['value'] == 'status_0'){
            if($expire['wb_obj_opt_relation']['model'] == 'ObjItemTree'){
                $this->ObjItemTree->updateAll(
                    array('ObjItemTree.status' => '0'),
                    array("ObjItemTree.id" => $expire['wb_obj_opt_relation']['foreign_key'])
                );
            } else if($expire['wb_obj_opt_relation']['model'] == 'ObjItemList'){
                $this->ObjItemList->updateAll(
                    array('ObjItemList.status' => '0'),
                    array("ObjItemList.id" => $expire['wb_obj_opt_relation']['foreign_key'])
                );
            }
        } else {
            $this->getEventManager()->dispatch(new CakeEvent("RelationEventExpire.{$expire['wb_obj_opt_relation']['value']}", null, $expire['wb_obj_opt_relation']));
        }
    }
    $this->ExtraData->query("DELETE FROM `wb_obj_opt_relation` WHERE `expire` IS NOT NULL AND `expire` <= NOW()");
    $this->ExtraData->query("DELETE FROM `wb_obj_opt_relation` WHERE `type` = 'extra_1' AND `model` = 'ObjItemList' AND foreign_key IN (SELECT id FROM wb_obj_item_list WHERE tid = 'catalog' AND status = '0')");
	$this->ExtraData->query("UPDATE wb_obj_item_list SET wb_obj_item_list.order_id = '0' WHERE wb_obj_item_list.tid = 'catalog' AND wb_obj_item_list.id IN (SELECT wb_obj_opt_relation.foreign_key FROM wb_obj_opt_relation WHERE wb_obj_opt_relation.type = 'extra_1' AND wb_obj_opt_relation.model = 'ObjItemList')");
    $this->ExtraData->query("UPDATE wb_obj_item_list SET wb_obj_item_list.order_id = '1' WHERE wb_obj_item_list.tid = 'catalog' AND wb_obj_item_list.id NOT IN (SELECT wb_obj_opt_relation.foreign_key FROM wb_obj_opt_relation WHERE wb_obj_opt_relation.type = 'extra_1' AND wb_obj_opt_relation.model = 'ObjItemList')");
    
    

?>
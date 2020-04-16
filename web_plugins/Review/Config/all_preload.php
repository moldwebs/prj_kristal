<?php

    CakeEventManager::instance()->attach(function($event){
        $ObjOptRelation = ClassRegistry::init('ObjOptRelation');

        $item_id = $event->data['item_id'];
        $info = $ObjOptRelation->query("SELECT SUM(rate) AS v1, COUNT(*) AS v2 FROM wb_obj_opt_comment WHERE item_id = '".sqls($item_id)."' AND status = '1'");
        $rate = array('qnt' => $info[0][0]['v2'], 'rate' => round($info[0][0]['v1']/$info[0][0]['v2'], 1));
        $ObjOptRelation->update_insert(array('value' => "{$rate['qnt']}"), array('model' => 'ObjItemList', 'type' => 'review_qnt', 'foreign_key' => $item_id));
        $ObjOptRelation->update_insert(array('value' => "{$rate['rate']}"), array('model' => 'ObjItemList', 'type' => 'rating_rate', 'foreign_key' => $item_id));

    }, 'Review.count');
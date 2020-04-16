<?php
class ObjItemList extends AppModel {
    
    public $useTable = 'wb_obj_item_list';
    
    public $actsAs = array('Tid', 'Alias', 'Translate' => array('title', 'alias'), 'Search' => array('title', 'body'), 'Fields', 'Data', 'Relation', 'Tag', 'Ordered', 'Attach');

    public $recursive = 2;

    var $virtualFields = array(
        //'base_title' => "SELECT content FROM `wb_cms_i18n` WHERE `wb_cms_i18n`.`foreign_key` = `ObjItemTree`.`id` AND `wb_cms_i18n`.`model` = 'ObjItemTree' AND `wb_cms_i18n`.`field` = 'title' AND `wb_cms_i18n`.`locale` = 'rom'",
        //'price_conv' => "`ObjItemList`.`price`",
        //'qnt_comments' => "SELECT COUNT(*) FROM `wb_obj_opt_comment` WHERE `wb_obj_opt_comment`.`item_id` = `ObjItemList`.`id` AND `wb_obj_opt_comment`.`status` = '1'",
    );

    public $belongsTo = array(
        'ObjItemTree' => array(
            'className'    => 'ObjItemTree',
            'foreignKey' => 'base_id',
        ),
        'User' => array(
            'className'    => 'Users.User',
            'foreignKey' => 'user_id',
        ),
    );

    public function afterFind($results, $primary = false) {
        foreach($results as $key => $result){
            if(!empty($result['ObjItemList']['short_body'])){
                $results[$key]['ObjItemList']['list_body'] = $result['ObjItemList']['short_body'];
            } else if(!empty($result['ObjItemList']['body'])){
                $results[$key]['ObjItemList']['list_body'] = $result['ObjItemList']['body'];
            }
        }
        return $results;
    }  

}
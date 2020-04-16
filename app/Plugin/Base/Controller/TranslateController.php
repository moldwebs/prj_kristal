<?php
class TranslateController extends BaseAppController {

    public $uses = array();

    public $paginate = array(
        'CmsTranslate' => array(
            'paramType' => 'querystring',
            'order' => array(
                'CmsTranslate.key' => 'asc'
            ),
            'limit' => 1000,
        )
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function admin_index($lang = null){
        $this->set('page_title', ___('Translates'));
        
        $_translates = Configure::read('CMS.translates');

        foreach(array('web_views' . DS . 'layout') as $scan_path){
            $all_files = get_all_files(ROOT . DS . $scan_path);
            foreach($all_files as $file){
                $data = file_get_contents($file);
                if(preg_match_all("/___(e|)\('(?<trnsl>(.*?))'(,(.*)|)\)/", $data, $matches)){
                    foreach($matches['trnsl'] as $match){
                        if(in_array($match, $translates)) continue;
                        if(array_key_exists($match, $_translates[$lang])) continue;
                        $translates[] = $match;
                    }
                }
            }
        }
        
        $locales = Configure::read('CMS.activelanguages');
        if(!empty($translates)){
            foreach($translates as $translate){
                foreach($locales as $locale => $_locale){
                    $this->CmsTranslate->insert_new(array('locale' => $locale, 'key' => $translate));
                }
            }
            Cache::clearGroup('translates'); 
        }
               
        
        $this->set('items', $this->paginate('CmsTranslate', array('CmsTranslate.locale' => $lang)));
    }
    
    public function admin_save($id = null, $value = ''){
        if(!empty($_GET['value'])) $value = urldecode($_GET['value']);
        $this->CmsTranslate->id = $id;
        $this->CmsTranslate->save(array('value' => $value));
        Cache::clearGroup('translates');
        $this->Basic->back();
    }

    public function admin_edit(){
        foreach(Configure::read('CMS.activelanguages') as $_lng => $lng){
            $this->CmsTranslate->create();
            $this->CmsTranslate->save(array('locale' => $_lng, 'key' => $this->data['CmsTranslate']['key'], 'value' => $this->data['value'][$_lng]));
            Cache::clearGroup('translates');
        }
        $this->redirect($this->request->referer(true));
    }

	function admin_delete($id = null){
	    $this->CmsTranslate->delete($id);
        Cache::clearGroup('translates');
        $this->Basic->back();
	}

    function ___admin_translate_model($model = null, $tid = null){
        $model_obj = Classregistry::init($model);
        if(!empty($this->data)){
            foreach($this->data['Translate'] as $id => $translates){
                foreach($translates as $lng => $val){
                    if(trim($val) == '') continue;
                    if($lng == Configure::read('Config.language')){
                        $model_obj->updateAll(
                            array("title" => sqls($val, true)),
                            array("id" => $id)
                        );
                    }
                    $this->Query->query("delete from `i18n` where `uid` = '".CMS_UID."' AND `locale` = '".sqls($lng)."' and `model` = '{$model_obj->alias}' and `foreign_key` = '".sqls($id)."' and `field` = 'title' limit 1");
                    $this->Query->query("insert into `i18n`(`uid`, `locale`, `model`, `foreign_key`, `field`, `content`) values ('".CMS_UID."', '".sqls($lng)."','{$model_obj->alias}','".sqls($id)."','title','".sqls($val)."')");
                }
            }
            exit;
        }
        $conditions = array();
        if(isset($model_obj->_schema['tid'])) $conditions['tid'] = $tid;
        if(isset($this->params['named']['flt_name'])) $conditions[$this->params['named']['flt_name']] = $this->params['named']['flt_value'];
        if(isset($this->params['named']['order'])) $order = $this->params['named']['order']; else $order = 'title';
        $items = $model_obj->find('all', array('recursive' => '-1', 'order' => $order, 'conditions' => $conditions));
        $this->set('items', $items);
        $this->set('obj_name', $model_obj->name);
        $this->set('pass_model', $model);
        $this->set('pass_tid', $tid);
    }
}

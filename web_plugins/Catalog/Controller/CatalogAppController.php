<?php
class CatalogAppController extends AppController {
    
    public function beforeFilter() {
        Configure::write('Config.tid', 'catalog');
        parent::beforeFilter();
        if(!isset($this->request->params['admin']) && !(Configure::read('CMS.catalog_base_id') > 0)) $this->Basic->template(Configure::read('CMS.settings.' . 'catalog'), 'catalog');
    }
}

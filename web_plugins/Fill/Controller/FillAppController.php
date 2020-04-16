<?php
class FillAppController extends AppController {
    public function beforeFilter() {
        Configure::write('Config.tid', $this->request->params['tid']);
        Configure::write('CMS.fill_tid_opts', Configure::read('CMS.fill_tid.'.$this->request->params['tid'].'.opts'));
        parent::beforeFilter();
        if(!isset($this->request->params['admin'])) $this->Basic->template(Configure::read('CMS.settings.' . $this->request->params['tid']), $this->request->params['tid']);
    }
}

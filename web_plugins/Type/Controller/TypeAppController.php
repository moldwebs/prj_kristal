<?php
class TypeAppController extends AppController {
    public function beforeFilter() {
        Configure::write('Config.tid', $this->request->params['tid']);
        parent::beforeFilter();
    }
}

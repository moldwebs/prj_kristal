<?php
class SpecificationAppController extends AppController {
    public function beforeFilter() {
        Configure::write('Config.tid', $this->request->params['tid']);
        parent::beforeFilter();
    }
}

<?php
class CommentAppController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        Configure::write('Config.tid', $this->request->params['tid']);
    }
}

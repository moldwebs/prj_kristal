<?php
class UsersAppController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        if(!isset($this->request->params['admin'])) $this->Basic->template(Configure::read('CMS.settings.' . 'users'), true);
    }
}

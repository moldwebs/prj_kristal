<?php
class VendorAppController extends AppController {
    public function beforeFilter() {
        Configure::write('Config.tid', 'vendor');
        parent::beforeFilter();
    }
}

<?php
class PromoAppController extends AppController {
    public function beforeFilter() {
        Configure::write('Config.tid', 'cms_promo');
        parent::beforeFilter();
    }
}

<?php
class NewsletterAppController extends AppController {
    public function beforeFilter() {
        Configure::write('Config.tid', 'newsletter');
        parent::beforeFilter();
    }
}

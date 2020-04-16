<?php
class SliderAppController extends AppController {
    public function beforeFilter() {
        Configure::write('Config.tid', 'cms_slider');
        parent::beforeFilter();
    }
}

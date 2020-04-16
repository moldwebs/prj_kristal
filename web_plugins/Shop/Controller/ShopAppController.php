<?php
class ShopAppController extends AppController {
    
    public $uses = array('Shop.ModOrder', 'Shop.ModOrderItem', 'Shop.ModDiscount');
    
    public function beforeFilter() {
        Configure::write('Config.tid', 'shop');
        parent::beforeFilter();

        if(!isset($this->request->params['admin']) && empty($this->request->params['requested']) && !$this->RequestHandler->isAjax()){
            if(strpos($_SERVER["HTTP_REFERER"], '/users/') === false && strpos($_SERVER["HTTP_REFERER"], '/system/') === false && strpos($_SERVER["HTTP_REFERER"], '/shop/') === false){
                $this->Session->write('basket_back', $_SERVER['HTTP_REFERER']);
            }
        }
        $this->set('basket_back', $this->Session->read('basket_back'));
    }
}

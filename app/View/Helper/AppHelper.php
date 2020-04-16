<?php
App::uses('Helper', 'View');

/**
 * Application helper
 *
 * This file is the base helper of all other helpers
 *
 * PHP version 5
 *
 * @category Helpers
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class AppHelper extends Helper {

	public function url($url = null, $full = false, $query = true) {
        if(is_array($url)){
    	    if(!empty($url['?']['page']) && Configure::read('is_admin') != '1'){
    	       $url['action'] = $this->params->here;
               $url['controller'] = false;
               $url['plugin'] = false;
               //if($url['?']['page'] > 1) $url[0] = $url['?']['page']; else unset($url[0]);
               foreach($url['?'] as $key => $val){
                    if(substr($key, 0, 5) == 'fltr_' || $key == 'limit' || $key == 'search' || $key == 'page'){
                        // OK
                    } else if(substr($key, 0, 6) == 'hfltr_') {
                        unset($url['?'][$key]);
                    } else if(!empty($url['?']['page']) && !in_array($key, array_keys($_GET))){
                        unset($url['?'][$key]);
                    }
               }
    	    } else {
    	       if(empty($url['plugin']) && !empty($this->params['tid'])){
    	           if(!empty($this->params['controller'])){
    	               $url['plugin'] = $this->params['tid'];
    	           } else {
    	               $url['tid'] = $this->params['tid'];
    	           }
                   
    	       }
    	    }
            
            if(!empty($this->request->query['scopefield']) && ($url['plugin'] == $this->params['plugin'] || empty($url['plugin'])) && ($url['controller'] == $this->params['controller'] || empty($url['controller']))){
                if(!is_array($url['?'])) $url['?'] = array();
                $url['?']['scopefield'] = $this->request->query['scopefield'];
            }
            
            if(!$query) unset($url['?']);
            if(empty($url['controller']) && !empty($this->request->params['pass'])){
                //foreach($this->request->params['pass'] as $key => $val) unset($url[$key]);
            }
        }
        $nurl = parent::url($url, $full);
        if(!empty($this->params['tid'])) $nurl = str_replace('/fill/', "/{$this->params['tid']}/", $nurl);
        if(!empty($this->params['tid'])) $nurl = str_replace('/sfill/', "/{$this->params['tid']}/", $nurl);
        $nurl = str_replace('/base/page/index', "", $nurl);
        if(Configure::read('is_admin') != '1' && strpos($nurl, 'http://') === false && strpos($_SERVER['REQUEST_URI'], '/' . Configure::read('Config.language')) !== false) $nurl = (count(Configure::read('CMS.activelanguages')) > 1 ? '/' . Configure::read('Config.language') : null) . $nurl;
		return $nurl;
	}
    
}

<?php
App::uses('CmsView', 'View');

class TemplateComponent extends Component {

    protected $_controller = null;
    protected $_view = null;
    protected $_ids = array();

    public function beforeRender(Controller $controller) {
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('TemplateComponent1:' . date("Y-m-d H:i:s"))));

        define('TIME_START_TPL', microtime(true));

        $this->_controller = $controller;

        Configure::write('TMP.sql_no_cache', '0');
        $this->_controller->ObjItemList->st_cond = '0';

        Configure::write('Config.view_tid', Configure::read('Config.tid'));

		Configure::write('CMS.request_query', $this->_controller->request->query);
        Configure::write('CMS.cms', $this->_controller->cms);
        if(!isset($this->_controller->request->params['admin'])){
            $this->_controller->set('cms', $this->_controller->cms);
            $this->_controller->set('tpl', $this);
            $this->_controller->set('tid', Configure::read('Config.tid'));

            /*
            $tpltoggle = $this->_controller->Session->read('Toggle');
            if(!empty($tpltoggle)){
                $this->_controller->Cookie->write('Toggle', $tpltoggle, true, '1 year');
            } else {
                $this->_controller->Session->write('Toggle', $this->_controller->Cookie->read('Toggle'));
            }
            */
            $tpltoggle = $this->_controller->Cookie->read('Toggle');

            Configure::write('CMS.cache_key', md5(serialize($tpltoggle).Configure::read('Config.language').$this->_controller->Session->read('Auth.User.id')));

            $this->_controller->set('tpltoggle', $tpltoggle);
            $this->_controller->set('tplcookie', $this->_controller->Cookie);
    		$this->_view = new CmsView($this->_controller);
            $this->__get_setings();
            $this->__set_tpl_vars();
            if(!empty($_GET['ajx_all']) || !$this->_controller->RequestHandler->isAjax()){
                define('TIME_START_TPL_1', microtime(true));
                $this->__get_menus();
                define('TIME_START_TPL_2', microtime(true));
                $this->__get_blocks();
                define('TIME_START_TPL_3', microtime(true));
            }
        }
        Configure::write('EXEC_TIME_LOGS', am(Configure::read('EXEC_TIME_LOGS'), array('TemplateComponent2:' . date("Y-m-d H:i:s"))));
    }

    public function __get_setings(){
        //$this->_controller->set('cfg', Configure::read('CMS.settings'));
    }

    public function __set_tpl_vars(){
        $tpl_vars = array(
            'lang' => Configure::read('CMS.language'),
        );

        $this->_controller->set('tpl_vars', $tpl_vars);
    }

    public function get($url, $data = array()){
        $cache_key = Configure::read('CMS.cache_key');

        if(strpos($url, 'base/get_list') !== false || strpos($url, '/active') !== false || strpos($url, '/related') !== false || strpos($url, '/similar') !== false || strpos($url, '/category') !== false){
            $cache_key = $cache_key . md5($this->_controller->cms['active_base'].$this->_controller->cms['active_item']);
        }

        if($content = Cache::read('requestAction_cache_' . $cache_key . '_' . md5($url) . '_' . md5(json_encode($data)), 'req_act')){
            return ($content == 'NULL' ? null : $content);
        }

        $content = $this->requestAction($url, $data);

        Cache::write('requestAction_cache_' . $cache_key . '_' . md5($url) . '_' . md5(json_encode($data)), (empty($content) ? 'NULL' : $content), 'req_act');

        return $content;
    }

    public function element($code = null, $action = null, $data = array()){
        if($action) $block_data = $this->get($action);
        $content = $this->_view->element($code, array('block' => array('data' => $block_data), 'data' => $data));
        return $content;
    }

    public function belement($code = null, $data = array()){
        $content = $this->_view->element($code, array('block' => array('data' => $data)));
        return $content;
    }

    public function telement($code = null, $data = array()){
        $content = $this->_view->telement($code, array('data' => $data));
        return $content;
    }

    public function custom($code = null){
        $content = $this->_view->telement('custom/' . $code);
        return $content;
    }

    public function block($code = null, $verify = false){
        if($verify){
            if(!empty($this->_controller->viewVars['blocks'][$this->_controller->viewVars['blocks_location'][$code]]['blocks'][$code]['content'])) return true; else return false;
        } else {
            if(!empty($this->_controller->viewVars['blocks'][$this->_controller->viewVars['blocks_location'][$code]]['blocks'][$code]['content'])) return $this->_controller->viewVars['blocks'][$this->_controller->viewVars['blocks_location'][$code]]['blocks'][$code]['content'];
        }
        return false;
    }

    public function blocks($code = null, $verify = false){
        if($verify){
            if(!empty($this->_controller->viewVars['blocks'][$code])) return true; else return false;
        } else {
            if(!empty($this->_controller->viewVars['blocks'][$code])) return $this->_view->telement((!empty($this->_controller->viewVars['blocks'][$code]['data']['data']['custom_template']) ? 'custom/' . $this->_controller->viewVars['blocks'][$code]['data']['data']['custom_template'] : "blocks,{$code}"), array('blocks' => $this->_controller->viewVars['blocks'][$code]['blocks']));
        }
        return false;
    }

    public function menu($code = null, $custom = false){
        return $this->_view->telement((!empty($custom) ? 'custom/' . $custom : "menu,{$code}"), array('menu' => $this->_controller->viewVars['menus'][$code]));
    }

    public function bigmenu($code = null, $custom = false){
        return $this->_view->telement((!empty($custom) ? 'custom/' . $custom : "bigmenu,{$code}"), array('menu' => $this->_controller->viewVars['menus'][$code]));
    }

    public function slider($code = null, $size = null){
        if(!empty($size)) $size = explode('x', $size);
        $block_data = $this->get("/slider/slider_item/get_list/{$code}");
        return $this->_view->element("slider/items,{$code}", array('block' => array('data' => $block_data), 'size' => $size));
    }

    public function promo($code = null, $tpl = null){
        if(!$tpl) $tpl = $code;
        $block_data = $this->get("/promo/promo_item/get_list/{$code}");
        return $this->_view->element("promo/items,{$tpl}", array('block' => array('data' => $block_data)));
    }

    public function widget($code = null){
        $data = $this->_view->telement("widgets/{$code}");
        if(preg_match_all("/{{(.*?)=\"(.*?)\"}}/si", $data, $matches)){
            foreach($matches[2] as $key => $value){
                if(preg_match_all("/\[(.*?)=\"(.*?)\"\]/si", $value, $_matches)){
                    $found = false;
                    foreach($_matches[2] as $_key => $_value){
                        if($_matches[1][$_key] == Configure::read('Config.language')){
                            $value = $_value;
                            $found = true;
                        }
                    }
                    if(!$found) $value = $_value;
                }
                $data = str_replace($matches[0][$key], $value, $data);
            }
        }
        return $data;
    }

    public function __get_menus(){
        //$ids = $this->_controller->ObjItemTree->find('list', array('tid' => 'cms_link', 'conditions' => array()));
        //if(empty($ids)) return array();
        //$_menus = $this->_controller->ObjItemTree->find('threaded', array('tid' => 'cms_link', 'conditions' => array('(ObjItemTree.lft IS NOT NULL OR (ObjItemTree.lft IS NULL AND ObjItemTree.parent_id IN ('.sqlimplode(',', array_keys($ids)).')))'), 'order' => array('ObjItemTree.lft' => 'ASC', 'ObjItemTree.extra_3' => 'ASC', 'ObjItemTree.title' => 'ASC')));
        $_menus = $this->_controller->ObjItemTree->find('threaded', array('tid' => 'cms_link', 'conditions' => array('ObjItemTree.extra_1 <>' => '6'), 'order' => array('ObjItemTree.lft' => 'ASC', 'ObjItemTree.title' => 'ASC')));
        foreach($_menus as $_menu){
            if($_menu['ObjItemTree']['extra_1'] == '1'){
                $menus[$_menu['ObjItemTree']['code']]['data'] = $_menu['ObjItemTree'];
                $menus[$_menu['ObjItemTree']['code']]['links'] = $this->__get_menu_content($_menu);
                //$menus[$_menu['ObjItemTree']['code']]['links'] = $this->__get_menu_data($_menu['children']);
                $this->_ids[$_menu['ObjItemTree']['id']] = $_menu['ObjItemTree']['code'];
            }
        }
        $this->_controller->set('menus', $menus);
    }

    public function __get_menu_content($_menu){
        if(!empty($_menu['ObjItemTree']['data']['cache']))  if($content = Cache::read('menu_data_cache_' . Configure::read('CMS.cache_key') . '_' . $_menu['ObjItemTree']['id'], 'req_act')){
            return ($content == 'NULL' ? null : $content);
        }

        $content = $this->__get_menu_data($_menu['children']);

        Cache::write('menu_data_cache_' . Configure::read('CMS.cache_key') . '_' . $_menu['ObjItemTree']['id'], (empty($content) ? 'NULL' : $content), 'req_act');

        return $content;
    }

    public function __get_menu_data($menu, $level = 0){
        $active = false;
        $data = array();
        if(!empty($menu)) foreach($menu as $val){
            if(!empty($val['ObjItemList'])) $val['ObjItemTree'] = $val['ObjItemList'];
            if($this->__showit($val['ObjItemTree']['data'])){
                if($val['ObjItemTree']['extra_1'] == '2') $val['ObjItemTree']['data']['url'] = "/pages/{$val['ObjItemTree']['id']}{cms_link.ObjItemTree.{$val['ObjItemTree']['id']}}";
                if(empty($val['ObjItemTree']['data']['url']) && !empty($val['ObjItemTree']['data']['mod_show'])){
                    $val['ObjItemTree']['data']['url'] = '#';
                    $val['children'] = $this->get("{$val['ObjItemTree']['data']['mod_show']}");
                } else if(empty($val['ObjItemTree']['data']['url'])){
                    if(!empty($val['ObjItemList']['alias'])){
                        $val['ObjItemTree']['data']['url'] = $val['ObjItemList']['alias'];
                    } else if($val['ObjItemTree']['alias']){
                        $val['ObjItemTree']['data']['url'] = $val['ObjItemTree']['alias'];
                    } else {
                        $val['ObjItemTree']['data']['url'] = "/{$val['ObjItemTree']['tid']}/item/index/{$val['ObjItemTree']['id']}{{$val['ObjItemTree']['tid']}.".(!empty($val['ObjItemList']) ? 'ObjItemList' : 'ObjItemTree').".{$val['ObjItemTree']['id']}}";
                    }
                }

                $val['ObjItemTree']['data']['url'] = ws_url($val['ObjItemTree']['data']['url']);

                list($active, $childs) = (!empty($val['children']) ? $this->__get_menu_data($val['children'], ($level+1)) : array(false, array()));
                if(!empty($val['ObjItemTree']['data']['mod_show_mode'])){
                    $childs = $this->__get_menu_data_active($childs, $val['ObjItemTree']['data']['mod_show_mode']);
                }

                if(!empty($val['ObjItemTree']['data']['mod_show']) && $val['ObjItemTree']['data']['set_link'] != '1'){
                    if(!empty($childs)) $data = am($data, $childs);
                } else {
                    if($val['ObjItemTree']['data']['url'] == $this->_controller->here || $val['ObjItemTree']['data']['url'] == $this->_controller->request->fulluri || $val['ObjItemTree']['data']['url'] == $this->_controller->request->fullruri){
                        $active = true;
                    } else {
                        if($val['ObjItemTree']['data']['url'] != '/' && (array_key_exists($val['ObjItemTree']['data']['url'], $this->_controller->cms['breadcrumbs']))){
                            $active = true;
                        }
                    }
                    foreach($childs as $child) if($child['active']) $active = true;
                    $data[] = array('active' => $active, 'title' => $val['ObjItemTree']['title'], 'code' => $val['ObjItemTree']['code'], 'image' => $val['ObjOptAttachDef']['file'], 'data' => $val['ObjItemTree']['data'], 'item' => $val, 'child' => $childs);
                }
            }
        }
        return ($level > 0 ? array($active, $data) : $data);
    }

    public function __get_menu_data_active($data, $mode, $active_tree = null, $level = 0){
        if(substr($mode, 0, 1) == 's'){
            $__mode = 'level';
            $_mode = substr($mode, 1);
        } else {
            $__mode = 'case';
            $_mode = $mode;
        }

        if(!$active_tree){
            $active_tree = $this->__get_menu_active_tree($data);
        }

        if(!empty($active_tree)) foreach($data as $key => $val){
            if($__mode == 'level' && $key == $active_tree[$level]){
                if($level == ($_mode-1)){
                    return array($val);
                } else {
                    return $this->__get_menu_data_active($val['child'], $mode, $active_tree, ($level+1));
                }
            } else if($__mode == 'case' && !empty($active_tree) && $key == $active_tree[$level]){
                if($_mode == '1'){
                    if($level == count($active_tree)-1){
                        return array($data[$active_tree[$level]]);
                    } else {
                        return $this->__get_menu_data_active($val['child'], $mode, $active_tree, ($level+1));
                    }
                } else if($_mode == '2'){
                    if(count($active_tree) < 2 && $level == count($active_tree)-1){
                        return array($data[$active_tree[$level]]);
                    } else if(count($active_tree) > 2 && $level == count($active_tree)-1){
                        return $val['child'];
                    } else if($level == count($active_tree)-2){
                        return array($data[$active_tree[$level]]);
                    } else {
                        return $this->__get_menu_data_active($val['child'], $mode, $active_tree, ($level+1));
                    }
                } else if($_mode == '3'){
                    if($level == count($active_tree)-1){
                        return $val['child'];
                    } else {
                        return $this->__get_menu_data_active($val['child'], $mode, $active_tree, ($level+1));
                    }
                } else if($_mode == '4'){
                    if($level == count($active_tree)-1){
                        return array($val);
                    } else {
                        return $this->__get_menu_data_active($val['child'], $mode, $active_tree, ($level+1));
                    }
                } else if($_mode == '5'){
                    if($level == count($active_tree)-1){
                        return (!empty($val['child']) ? $val['child'] : $data);
                    } else {
                        return $this->__get_menu_data_active($val['child'], $mode, $active_tree, ($level+1));
                    }
                }
            }
        }
        if($__mode == 'case' && $_mode == '5'){
            return $data;
        }
        return false;
    }


    public function __get_menu_active_tree($data, $level = 0, $parent = array()){
        $results = array();
        foreach ($data as $key => $el) {
            if ($el['data']['url'] == $this->_controller->here || $el['data']['url'] == $this->_controller->request->fullurl){
                $results = $parent + array($level => $key);
            }
            if(!empty($el['child'])){
                $results = $results + $this->__get_menu_active_tree($el['child'], ($level + 1), ($parent + array($level => $key)));
            }
        }
        return count($results) > 0 ? $results : array();
    }


    public function ___get_menu_active_tree($data){
        //http://stackoverflow.com/questions/8656682/getting-all-children-for-a-deep-multidimensional-array
        foreach($data as $key => $val){
            if($val['data']['url'] == $this->_controller->here || $val['data']['url'] == $this->_controller->request->fullurl) return array($key);
        }
        foreach($data as $key => $val){
            if(!empty($val['child'])){
                $ret = $this->__get_menu_active_tree($val['child']);
                if($ret){
                    $ret[] = $key;
                    return $ret;
                }
            }
        }
        return null;
    }

    public function __get_active_tree($data, $active = null){
        $return_data = array();
        foreach($data as $key => $val){
            $return_data[$key] = $val;
            if($val['ObjItemTree']['id'] == $active) $return_data[$key]['active'] = true; else $return_data[$key]['active'] = false;
            if(!empty($val['children'])){
                $return_data[$key]['children'] = $this->__get_active_tree($val['children'], $active);
                foreach($return_data[$key]['children'] as $_key => $_val){
                    if($_val['active']) $return_data[$key]['active'] = true;
                    if(!empty($_val['ObjItemTree']['count'])) $return_data[$key]['ObjItemTree']['count'] = $return_data[$key]['ObjItemTree']['count'] + $_val['ObjItemTree']['count'];
                }
            }
        }
        return $return_data;
    }

    public function __get_blocks(){
        $_blocks = $this->_controller->ObjItemTree->find('threaded', array('tid' => 'cms_block', 'conditions' => array("ObjItemTree.rght IS NOT NULL"), 'order' => array('ObjItemTree.lft' => 'ASC')));
        foreach($_blocks as $_block){
            if($_block['ObjItemTree']['extra_1'] == '1' && $this->__showit($_block['ObjItemTree']['data'])){
                $blocks[$_block['ObjItemTree']['code']]['data'] = $_block['ObjItemTree'];
                foreach($_block['children'] as $__block){
                    if($this->__showit($__block['ObjItemTree']['data'])){
                        $__block['ObjItemTree']['content'] = $this->__get_block_data($__block, $_block['ObjItemTree']['code']);
                        if(!empty($__block['ObjItemTree']['content'])){
                            $blocks[$_block['ObjItemTree']['code']]['blocks'][$__block['ObjItemTree']['code']] = $__block['ObjItemTree'];
                            $blocks_location[$__block['ObjItemTree']['code']] = $_block['ObjItemTree']['code'];
                        }
                    }
                    $this->_ids[$__block['ObjItemTree']['id']] = $__block['ObjItemTree']['code'];
                }
            } else {
                if($this->__showit($_block['ObjItemTree']['data'])){
                    $_block['ObjItemTree']['content'] = $this->__get_block_data($_block);
                    if(!empty($_block['ObjItemTree']['content'])){
                        $blocks['']['blocks'][$_block['ObjItemTree']['code']] = $_block['ObjItemTree'];
                        $blocks_location[$_block['ObjItemTree']['code']] = '';
                    }
                }
            }
            $this->_ids[$_block['ObjItemTree']['id']] = $_block['ObjItemTree']['code'];
        }
        $this->_controller->set('blocks', $blocks);
        $this->_controller->set('blocks_location', $blocks_location);
    }

    public function __get_block_data($block, $panel_code = null){

        if(!empty($block['ObjItemTree']['data']['cache'])) if($content = Cache::read('block_cache_' . Configure::read('CMS.cache_key') . '_' . $block['ObjItemTree']['id'], 'req_act')){
            return ($content == 'NULL' ? null : $content);
        }

        if($block['ObjItemTree']['extra_1'] == '2'){
            $tp_block = 'groupblock';
            $content = 0;
            $block['children'] = $this->_controller->ObjItemTree->find('threaded', array('tid' => 'cms_block', 'conditions' => array("ObjItemTree.parent_id" => $block['ObjItemTree']['id']), 'order' => array('ObjItemTree.lft' => 'ASC')));
            if(!empty($block['children'])) foreach($block['children'] as $children){
                $_content = $this->__get_block_content($children['ObjItemTree'], $panel_code);
                if(!empty($_content)) $content = 1;
                $contents[] = $_content;
                $titles[] = array('title' => $children['ObjItemTree']['title'], 'url' => $children['ObjItemTree']['data']['title_url']);
            }
            preg_match_all('/\[(.*?)\]/i', $block['ObjItemTree']['data']['mod_show'], $opts);
            $module_code = str_replace('/', '-', $opts[1][0]);
            if(!empty($content)) $content = $this->_view->telement((!empty($block['ObjItemTree']['data']['custom_block_template']) ? 'custom/' . $block['ObjItemTree']['data']['custom_block_template'] : (!empty($block['ObjItemTree']['data']['extension_block_template']) ? (!empty($panel_code) ? "{$tp_block}_{$block['ObjItemTree']['data']['extension_block_template']},{$panel_code},{$module_code}" : "{$tp_block}_{$block['ObjItemTree']['data']['extension_block_template']}") : (!empty($panel_code) ? "{$tp_block},{$panel_code},{$module_code}" : "block"))), array('cfg' => $this->_controller->viewVars['cfg'], 'vars' => nl2array($block['ObjItemTree']['data']['variables']), 'block' => $block, 'css' => $block['ObjItemTree']['data']['css_class'], 'header_css' => $block['ObjItemTree']['data']['header_css'], 'code_before' => $block['ObjItemTree']['data']['code_before'], 'code_after' => $block['ObjItemTree']['data']['code_after'], 'code' => $block['ObjItemTree']['code'], 'title' => ($block['ObjItemTree']['data']['block_template_header'] == '1' ? $block['ObjItemTree']['title'] : false), 'titles' => $titles, 'bodys' => $contents));
        } else {
            $tp_block = 'block';
            $content = $this->__get_block_content($block['ObjItemTree'], $panel_code);
            preg_match_all('/\[(.*?)\]/i', $block['ObjItemTree']['data']['mod_show'], $opts);
            $module_code = str_replace('/', '-', $opts[1][0]);
            if(!empty($content) && $block['ObjItemTree']['data']['block_template'] == '1') $content = $this->_view->telement((!empty($block['ObjItemTree']['data']['custom_block_template']) ? 'custom/' . $block['ObjItemTree']['data']['custom_block_template'] : (!empty($block['ObjItemTree']['data']['extension_block_template']) ? (!empty($panel_code) ? "{$tp_block}_{$block['ObjItemTree']['data']['extension_block_template']},{$panel_code},{$module_code}" : "{$tp_block}_{$block['ObjItemTree']['data']['extension_block_template']}") : (!empty($panel_code) ? "{$tp_block},{$panel_code},{$module_code}" : "block"))), array('cfg' => $this->_controller->viewVars['cfg'], 'vars' => nl2array($block['ObjItemTree']['data']['variables']), 'block' => $block, 'css' => $block['ObjItemTree']['data']['css_class'], 'header_css' => $block['ObjItemTree']['data']['header_css'], 'code_before' => $block['ObjItemTree']['data']['code_before'], 'code_after' => $block['ObjItemTree']['data']['code_after'], 'code' => $block['ObjItemTree']['code'], 'title' => ($block['ObjItemTree']['data']['block_template_header'] == '1' ? $block['ObjItemTree']['title'] : false), 'body' => $content));
        }

        if(!empty($content)){
            if(!empty($block['ObjItemTree']['data']['cache'])){
                Cache::write('block_cache_' . Configure::read('CMS.cache_key') . '_' . $block['ObjItemTree']['id'], (empty($content) ? 'NULL' : $content), 'req_act');
            }
            return $content;
        }
        return false;
    }

    public function __get_block_content($block, $style = null){
        /*
        if(strpos($block['data']['mod_show'], 'active') === false && strpos($block['data']['mod_show'], 'related') === false) if($content = Cache::read('block_content_cache_' . Configure::read('CMS.cache_key') . '_' . $block['id'], 'req_act')){
            return ($content == 'NULL' ? null : $content);
        }
        */

        if(!empty($block['data']['mod_show'])){
            preg_match_all('/\[(.*?)\]/i', $block['data']['mod_show'], $opts);
            if(substr($opts[1][0], -4) == 'menu'){
                if($opts[1][1] > 0){
                    $block_data = $this->_controller->viewVars['menus'][$this->__id2code($opts[1][1])];
                } else {
                    $block_data['links'] = $this->__get_menu_data($this->get($opts[1][1], array('named' => $block['data'])));
                }
                $content = $this->_view->telement((!empty($block['data']['custom_template']) ? 'custom/' . $block['data']['custom_template'] : (!empty($block['data']['extension_template']) ? $opts[1][0] . '_' . $block['data']['extension_template'] : $opts[1][0] . ',' . $style)), array('style' => $style, 'cfg' => $this->_controller->viewVars['cfg'], 'menu' => $block_data, 'block' => array('menu' => $block_data, 'block' => $block, 'vars' => nl2array($block['data']['variables']))));
                if(!empty($content) && substr(trim($content), 0, 3) != '<ul' && empty($block['data']['custom_template'])) $content = '<ul class="menu">' . $content . '</ul>';
            } else {
                if(!empty($opts[1][1])) $block_data = $this->get($opts[1][1], array('named' => $block['data']));
                if(!empty($block['data']['custom_template'])){
                    $content = $this->_view->telement((!empty($block['data']['custom_template']) ? 'custom/' . $block['data']['custom_template'] : (!empty($block['data']['extension_template']) ? $opts[1][0] . '_' . $block['data']['extension_template'] : $opts[1][0] . ',' . $style)), array('style' => $style, 'cfg' => $this->_controller->viewVars['cfg'], 'block' => array('data' => $block_data, 'block' => $block, 'vars' => nl2array($block['data']['variables']))));
                } else {
                    $content = $this->_view->element((!empty($block['data']['custom_template']) ? 'custom/' . $block['data']['custom_template'] : (!empty($block['data']['extension_template']) ? $opts[1][0] . '_' . $block['data']['extension_template'] : $opts[1][0] . ',' . $style)), array('style' => $style, 'cfg' => $this->_controller->viewVars['cfg'], 'block' => array('data' => $block_data, 'block' => $block, 'vars' => nl2array($block['data']['variables']))));
                }

            }
        } else {
            if($block['data']['type'] == 'html'){
                $content = $block['body'];
            } else if($block['data']['type'] == 'html_block'){
                $content = $block['body_block'];
            } else if($block['data']['type'] == 'text'){
                $content = $block['body_text'];
            } else if($block['data']['type'] == 'script'){
                $content = $block['body_script'];
            } else if($block['data']['type'] == 'custom'){
                $content = null;
            } else {
                $content = $block['data'][$block['data']['type']];
            }
            if(!empty($block['data']['custom_template'])){
                $content = $this->_view->telement('custom/' . $block['data']['custom_template'], array('style' => $style, 'cfg' => $this->_controller->viewVars['cfg'], 'block' => array('data' => $content, 'block' => $block, 'vars' => nl2array($block['data']['variables']))));
            }
        }

        //Cache::write('block_content_cache_' . Configure::read('CMS.cache_key') . '_' . $block['id'], (empty($content) ? 'NULL' : $content), 'req_act');

        return $content;
    }

    public function __showit($data){

        if(!empty($data['show_for'])){
            if($data['show_for'] == 'guests'){
                if($this->_controller->Session->check('Auth.User.id')) return false;
            } else if($data['show_for'] == 'logged'){
                if(!$this->_controller->Session->check('Auth.User.id')) return false;
            } else if($this->_controller->Session->read('Auth.User.role') != $data['show_for']){
                return false;
            }
        }

        $conds = nl2array($data['cond_show']);
        if(!empty($conds)){
            $show = false;
            foreach($conds as $key => $val){
                $step = $this->_controller->viewVars;
                if(strpos($key, '.') !== false){
                    foreach(explode('.', $key) as $path){
                        $step = $step[$path];
                    }
                } else {
                    $step = $step[$key];
                }
                if($val == 'EMPTY'){
                    if(empty($step)) $show = true;
                } else if($val == 'NOTEMPTY'){
                    if(!empty($step)) $show = true;
                } else {
                    if($step == $val) $show = true;
                }
            }
            if(!$show) return false;
        }

        $conds = nl2array($data['no_show_on']);
        if(!empty($conds)) foreach($conds as $key => $val){
            if(substr($val, -1) == '*'){
                $val = rtrim($val, '*');
                if(strpos($this->_controller->here, $val) !== false) return false;
                if(strpos($this->_controller->request->uri, $val) !== false) return false;
            } else {
                if($this->_controller->here == $val || $val == $this->_controller->request->fullurl) return false;
                if($this->_controller->request->uri == $val) return false;
            }
        }

        $conds = nl2array($data['show_on']);
        if(!empty($conds)){
            $show = false;
            foreach($conds as $key => $val){
                if(substr($val, -1) == '*'){
                    $val = rtrim($val, '*');
                    if(strpos($this->_controller->here, $val) !== false) $show = true;
                    if(strpos($this->_controller->request->uri, $val) !== false) $show = true;
                } else {
                    if($this->_controller->here == $val || $val == $this->_controller->request->fullurl) $show = true;
                    if($this->_controller->request->uri == $val) $show = true;
                }
            }
            if(!$show) return false;
        }

        return true;
    }

    public function __id2code($id = null){
        return $this->_ids[$id];
    }

}

?>
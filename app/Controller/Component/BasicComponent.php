<?php
class BasicComponent extends Component {

    protected $_controller = null;

	public function initialize(Controller $controller) {
		$this->_controller = $controller;
	}

    public function mail($to = null, $subject = null, $body, $attachments = array(), $from = null, $reply_to = null){
        if(empty($to)) $to = SYS_MAIL;
        if(strpos($to, ';') !== false) $to = explode(';', $to);
        if(empty($subject)) $subject = 'SYSTEM MESSAGE';
        if(empty($from)) $from = array(MAIL_FROM => MAIL_SEDER);

        if(!is_array($to)) if(preg_match('/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD', $to) !== 1) return false;

        App::uses('CakeEmail', 'Network/Email');
        if(Configure::read('debug') == '2'){
            $Email = new CakeEmail('test');
        } else {
            $Email = new CakeEmail('default');
        }

        $Email->emailFormat('html');
        $Email->from($from);
        $Email->to($to);
        $Email->subject($subject);
        if(!empty($reply_to)) $Email->replyTo($reply_to);
        if(!empty($attachments)) foreach($attachments as $attachment) $Email->addAttachments($attachment);
        if(is_array($body)){
            if(!file_exists(EXT_VIEWS . DS . 'Emails' . DS . 'html' . DS .  $body['template'] . '.ctp')) return false;
            $Email->template($body['template']);
            $Email->viewVars(am($body['data'], array('cfg' => Configure::read('CMS.settings'))));
            return $Email->send();
        } else {
            return $Email->send($body);
        }
    }

    public function template($data = array(), $module = false, $_obj_data = array()){
        if (!empty($this->_controller->request->params['requested'])) return;

        if($module || empty($data['data'])){
            $data['data'] = $data;
        }

        if(!empty($data['data']['template'])) $this->_controller->layout = $data['data']['template'];
        if(!empty($data['data']['template_type'])) $this->_controller->set('tpl_type', $data['data']['template_type']);
        if(!empty($data['title'])) $this->_controller->set('title_for_layout', $data['title']);
        if(!empty($data['title'])) $this->_controller->set('title_for_action', $data['title']);
        if(!empty($data['title']) && ($module || $data['tid'] == 'cms_link')) $this->_controller->set('title_for_page', $data['title']);
        if(!empty($data['data']['meta_title']) && !$module) $this->_controller->set('title_for_layout', $data['data']['meta_title']);

        if($data['tid'] == 'catalog' && empty($module)){
            $tp = (!empty($data['lft']) ? 'base' : 'item');
            $cfg = $this->_controller->viewVars['cfg']['catalog'];

            if(!empty($cfg["meta_{$tp}_title"])){
                $data['meta_title'] = str_replace("{{keyw}}", $data['title'], $cfg["meta_{$tp}_title"]);
                if(!empty($_obj_data['Price'])) $data['meta_title'] = str_replace("{{price}}", "{$_obj_data['Price']['html_value']} {$_obj_data['Price']['html_currency']}", $data['meta_title']);
            }
            if(!empty($cfg["meta_{$tp}_desc"])){
                $data['meta_desc'] = str_replace("{{keyw}}", $data['title'], $cfg["meta_{$tp}_desc"]);
                if(!empty($_obj_data['Price'])) $data['meta_desc'] = str_replace("{{price}}", "{$_obj_data['Price']['html_value']} {$_obj_data['Price']['html_currency']}", $data['meta_desc']);
            }
            if(!empty($cfg["meta_{$tp}_keyw"])){
                $data['meta_keyw'] = str_replace("{{keyw}}", $data['title'], $cfg["meta_{$tp}_keyw"]);
                $data['meta_keyw'] = str_replace("{{keyw_brands}}", $data['title_prod'], $data['meta_keyw']);
            }
            //pr($data);
            //pr($this->_controller->viewVars['cfg']['catalog']);
        }

        if(!empty($data['meta_title'])){
            $this->_controller->set('meta_title_for_layout', $data['meta_title']);
            $this->_controller->set('title_for_layout', $data['meta_title']);
        }
        if(!empty($data['meta_desc'])) $this->_controller->set('meta_desc_for_layout', $data['meta_desc']);
        if(!empty($data['meta_keyw'])) $this->_controller->set('meta_keyw_for_layout', $data['meta_keyw']);

        if(!empty($data['data']['meta_title'])){
            $this->_controller->set('meta_title_for_layout', $data['data']['meta_title']);
            $this->_controller->set('title_for_layout', $data['data']['meta_title']);
        }
        if(!empty($data['data']['meta_desc'])) $this->_controller->set('meta_desc_for_layout', $data['data']['meta_desc']);
        if(!empty($data['data']['meta_keyw'])) $this->_controller->set('meta_keyw_for_layout', $data['data']['meta_keyw']);
        if(empty($this->_controller->viewVars['meta_title_for_layout'])){
            $this->_controller->set('meta_title_for_layout', $this->_controller->viewVars['title_for_layout']);
        }

        if(!empty($data['alias'])){
            $this->_controller->cms['breadcrumbs'][ws_url($data['alias'])] = $data['title'];
        } else if($module){
            if($module != 'base') $this->_controller->cms['breadcrumbs'][ws_url($module == 'base' ? '/' : '/' . $module . '/')] = ($module == 'base' ? ___('Home') : ___(ucfirst($module)));
            $this->_controller->set('title_for_action', ___(ucfirst($module)));
            $this->_controller->set('title_for_page', ___(ucfirst($module)));
        } else if(!empty($data['title'])){
            $this->_controller->cms['breadcrumbs'][$this->_controller->here] = $data['title'];
        }
    }

    public function wback(){
        if($this->_controller->request->referer(true) != $this->_controller->request->fullhere){
            $this->_controller->Session->write('BackUrl', $this->_controller->request->referer(true));
        }
    }

    public function back($message = null){
        if($this->_controller->request->is('ajax')) exit;
        if($this->_controller->Session->check('BackUrl')){
            $referer = $this->_controller->Session->read('BackUrl');
            $this->_controller->Session->delete('BackUrl');
            $this->_controller->redirect($referer);
        }
        $this->_controller->redirect($this->_controller->request->referer(true));
    }

    public function filters($alias = null, $check = array()){
        $filters = array();

        if(empty($check)) $check = $this->_controller->request->query;

        if($this->_controller->Cookie->check('Toggle')) foreach($this->_controller->Cookie->read('Toggle') as $key => $val){
            $chk_key = Configure::read('Config.tid') . '_fltr_';
            if(substr($key, 0, strlen($chk_key)) == $chk_key) $check[str_replace($chk_key, 'fltr_', $key)] = $val;
        }

        if(!empty($check)) foreach($check as $key => $val) if($val != ''){
            $_key = str_replace('hfltr_', 'fltr_', $key);
            if(substr($_key, 0, 5) == 'fltr_'){
                $this->_controller->request->data[$alias][$key] = $val;
                $field = preg_replace('/fltr_(\w+)__(\w+)/i', "$2", $_key);
                $cond = preg_replace('/fltr_(\w+)__(\w+)/i', "$1", $_key);
                switch ($cond) {
                    case 'eq':
                        if($val == 'NULL'){
                            $filters[1][$alias . '.' . $field] = '';
                        } else {
                            if(!is_array($val) && strpos($val, ',') !== false){
                                $val = explode(',', $val);
                            }
                            $filters[1][$alias . '.' . $field] = $val;
                        }
                        break;
                    case 'eqmin':
                        $filters[2][$alias . '.' . $field . ' >='] = $val;
                        break;
                    case 'eqmax':
                        $filters[3][$alias . '.' . $field . ' <='] = $val;
                        break;
                    case 'relval':
                        $rel_id = sqls(preg_replace('/(\w+)(\D)(\d+)/i', "$3", $field));
                        $field = sqls(preg_replace('/(\w+)(\D)(\d+)/i', "$1$2", $field));
                        if(is_array($val)){
                            $relval_depends_cond = array();
                            foreach($val as $_val){
                                $_val = sqls($_val);
                                if(!is_array($_val) && strpos($_val, ',') !== false){
                                    $_val = explode(',', $_val);
                                    $relval_depends_cond[] = "(`rel_id` = '{$rel_id}' AND `value` = '{$_val[1]}')";
                                    if(!empty($relval_depends['vals'][$relval_depends['rels'][$_val[0]]][$_val[0]])) unset($relval_depends['vals'][$relval_depends['rels'][$_val[0]]][$_val[0]]);
                                    $relval_depends['rels'][$_val[1]] = $rel_id;
                                    $relval_depends['vals'][$rel_id][$_val[1]] = $_val[1];
                                } else {
                                    $relval_depends['rels'][$_val] = $rel_id;
                                    $relval_depends['vals'][$rel_id][$_val] = $_val;
                                }
                            }
                            if(!empty($relval_depends_cond)){
                                if(!empty($relval_depends['vals'][$relval_depends['rels'][$_val[0]]])){
                                    foreach($relval_depends['vals'][$relval_depends['rels'][$_val[0]]] as $__val){
                                        $relval_depends_cond[] = "(`rel_id` = '{$relval_depends['rels'][$_val[0]]}' AND `value` = '{$__val}')";
                                        $relval_depends_cond_added[$rel_id][] = "(`rel_id` = '{$relval_depends['rels'][$_val[0]]}' AND `value` = '{$__val}')";
                                    }
                                }
                                if(!empty($relval_depends_cond_added[$relval_depends['rels'][$_val[0]]])){
                                    foreach($relval_depends_cond_added[$relval_depends['rels'][$_val[0]]] as $__val){
                                        $relval_depends_cond[] = $__val;
                                    }
                                }
                                $filters[4][] = "({$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND (".implode(' OR ', $relval_depends_cond).")))";
                            } else {
                                //$val = sqls($val);
                                //foreach($val as $_val){
                                //    $filters[4][] = "({$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` = '{$rel_id}' AND `value` = '{$_val}'))";
                                //}
                                $val = sqls($val, true);
                                $filters[4][] = "({$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` = '{$rel_id}' AND `value` IN (".implode(',', $val).")))";
                            }
                        } else {
                            $val = sqls($val);
                            $filters[4][] = "({$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` = '{$rel_id}' AND `value` = '{$val}'))";
                        }
                        break;
                    case 'relvalmin':
                        $rel_id = sqls(preg_replace('/(\w+)(\D)(\d+)/i', "$3", $field));
                        $field = sqls(preg_replace('/(\w+)(\D)(\d+)/i', "$1$2", $field));
                        $val = sqls($val);
                        $filters[5][] = "({$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` = '{$rel_id}' AND `value` >= {$val}))";
                        break;
                    case 'relvalmax':
                        $rel_id = sqls(preg_replace('/(\w+)(\D)(\d+)/i', "$3", $field));
                        $field = sqls(preg_replace('/(\w+)(\D)(\d+)/i', "$1$2", $field));
                        $val = sqls($val);
                        $filters[6][] = array("({$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` = '{$rel_id}' AND `value` <= {$val}))");
                        break;
                    case 'eqrel':
                        $field = sqls($field);
                        if(is_array($val)){
                            $val = sqls($val, true);
                            $filters[7][] = "{$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` IN (".implode(',', $val)."))";
                        } else {
                            $val = sqls($val);
                            $filters[7][] = "{$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` = '{$val}')";
                        }
                        break;
                    case 'relexist':
                        $field = sqls($field);
                        if(is_array($val)){
                            $val = sqls($val, true);
                            $filters[71][] = "{$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` IN (".implode(',', $val).") AND `value` != '')";
                        } else {
                            $val = sqls($val);
                            if($field == 'vendor_code'){
                                $filters[71]['OR'][] = "{$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` = '{$val}' AND `value` != '')";
                                $filters[71]['OR'][] = "{$alias}.id IN (SELECT `extra_2` FROM `wb_extra_data` WHERE `extra_1` = '{$val}' AND `type` = 'vendor')";
                            } else {
                                $filters[71][] = "{$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` = '{$val}' AND `value` != '')";
                            }
                        }
                        break;
                    case 'eqorrel':
                        $field = sqls($field);
                        if(is_array($val)){
                            $val = sqls($val, true);
                            $filters[8][] = "({$alias}.{$field} IN (".implode(',', $val).") OR {$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` IN (".implode(',', $val).")))";
                        } else {
                            $val = sqls($val);
                            $filters[8][] = "({$alias}.{$field} = '{$val}' OR {$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `rel_id` = '{$val}'))";
                        }
                        break;
                    case 'reltype':
                        $field = sqls($field);
                        $val = sqls($val);
                        $filters[9][] = "({$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_relation` WHERE `model` = '{$alias}' AND `type` = '{$field}' AND `value` = '{$val}'))";
                        break;
                    case 'lk':
						$val = trim(str_replace(' ', '%', $val));
                        $val = sqls($val);
                        if($alias == 'ObjItemList' && $field == 'title' && Configure::read('CMS.fill_tid.'.Configure::read('Config.tid').'.opts.treecontent') == '1'){
                            $filters[10]['OR'] = array($alias . '.' . $field . ' LIKE' => "%{$val}%", "{$alias}.id IN (SELECT `extra_3` FROM `wb_obj_item_tree` WHERE `tid` = '".Configure::read('Config.tid')."_treecontent' AND `title` LIKE '%{$val}%')");
                        } else if($alias == 'ObjItemList' && $field == 'title'){
                            if(Configure::read('Config.tid') == 'catalog'){
                                $filters[10]['OR'] = array('CONCAT_WS(\'\', Manufacturer.title, ObjItemTree.title, ObjItemList.title, ObjItemList.code, ObjItemList.id) LIKE' => "%{$val}%", $alias . '.' . 'code' => "{$val}", "{$alias}.id IN (SELECT `foreign_key` FROM `wb_cms_i18n` WHERE `model` = 'ObjItemList' AND `content` LIKE '%{$val}%')", "ObjItemTree.id IN (SELECT `foreign_key` FROM `wb_cms_i18n` WHERE `model` = 'ObjItemTree' AND `content` LIKE '%{$val}%')");
                            } else {
                                $filters[10]['OR'] = array($alias . '.' . $field . ' LIKE' => "%{$val}%", $alias . '.' . 'code' => "{$val}", "{$alias}.id IN (SELECT `foreign_key` FROM `wb_cms_i18n` WHERE `model` = 'ObjItemList' AND `content` LIKE '%{$val}%')");
                            }
                        } else {
                            $filters[10][$alias . '.' . $field . ' LIKE'] = "%{$val}%";
                        }
                        break;
                    case 'fl':
                        $filters[11][$alias . '.' . $field . ' LIKE'] = "{$val}%";
                        break;
                    case 'dt':
                        $field = sqls($field);
                        $val = sqls($val);
                        if($val == 'any'){
                            $filters[12][] = "{$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_data` WHERE `data` REGEXP '\"{$field}\"\:\".*\"' AND `data` NOT LIKE '%\"{$field}\":\"\"%')";
                        } else {
                            $val = str_replace('.', '_', $val);
                            $filters[12][] = "{$alias}.id IN (SELECT `foreign_key` FROM `wb_obj_opt_data` WHERE `data` REGEXP '\"{$field}\"\:\".*{$val}.*\"')";
                        }
                        break;
                    case 'extra':
                        $field = sqls($field);
                        $val = sqls($val);
                        $filters[15][] = "{$alias}.id IN (SELECT `extra_2` FROM `wb_extra_data` WHERE `extra_1` = '{$val}' AND `type` = '{$field}')";
                        break;
                    case 'objdt':
                        $field = sqls($field);
                        $val = sqls($val);
                        if($val == 'any'){
                            $filters[13][] = "({$alias}.data REGEXP '\"{$field}\"\:\".*\"' AND {$alias}.data NOT LIKE '%\"{$field}\":\"\"%')";
                        } else {
                            $val = str_replace('.', '_', $val);
                            $filters[13][] = "({$alias}.data REGEXP '\"{$field}\"\:\".*{$val}.*\"')";
                        }
                        break;
                }
                if(substr_count($cond, 'eqdate') > 0){
                    $val = sqls($val);
                    $filters[14][] = "DATE_FORMAT({$alias}.{$field}, '%".sqlimplode('%',str_split(str_replace('eqdate', '', $cond)))."') = '{$val}'";
                }

            }
        }
        return (!empty($filters) ? array('FLTRS' => $filters) : array());
    }

    public function simple_add_settings($plugin = null, Model $model){
        if(!empty($this->_controller->request->data)){

            foreach($this->_controller->request->data['Translates'] as $key => $val){
                foreach($val as $_key => $_val){
                    if(!empty($_val[Configure::read('Config.def_language')])) $this->_controller->request->data[$key][$_key] = $_val[Configure::read('Config.def_language')];
                }
            }

			$model->deleteAll(array('CmsSetting.plugin' => $plugin));
            $this->_controller->CmsAlias->deleteAll(array('CmsAlias.model' => 'CmsSetting', 'CmsAlias.tid' => $plugin));

            foreach($this->_controller->request->data[$model->alias] as $key => $val){
                $model->update_insert(array("value" => (is_array($val) ? json_encode($val) : $val)), array("option" => $key, "plugin" => $plugin));
                foreach(array_keys(Configure::read('CMS.activelanguages')) as $locale){
                    if($locale != Configure::read('Config.def_language')) $val = $this->_controller->request->data['Translates'][$model->alias][$key][$locale];
                    if($key == 'alias' && !empty($val)){
                        $this->_controller->CmsAlias->create();
                        $this->_controller->CmsAlias->save(array('tid' => $plugin, 'locale' => $locale, 'model' => 'CmsSetting', 'alias' => $val));
                    }
                    $model->update_insert(array("value" => (is_array($val) ? json_encode($val) : $val)), array("option" => $key . '__' . $locale, "plugin" => $plugin));
                }
            }

            Cache::clearGroup('settings');

            $this->_controller->redirect($this->_controller->here);
        } else {
            $this->_controller->request->data = $model->get_list($plugin);
        }
    }

    public function save_load($id = null, Model $model, $redirect = false, $event = false){
        if(!empty($this->_controller->request->data)){
            $save_data = $this->_controller->request->data;

            if($save_data['saction'] == '4'){
                $id = null;
                $save_data['saction'] = '2';
                $is_copy = true;
            }

            if(isset($save_data[$model->alias]['alias']) && empty($save_data[$model->alias]['alias']) && !empty($save_data[$model->alias]['title'])) $save_data[$model->alias]['alias'] = ws_alias($save_data[$model->alias]['title']);

            if($id > 0) $save_data[$model->alias]['id'] = $id;


            if(!empty($this->_controller->request->data['ajx_validate']) && $this->_controller->request->data['ajx_validate'] == '1'){
                $model->set($save_data);
                if ($model->validates()){
                    exit(json_encode(array('status' => 'SUCCESS')));
                } else {
                    exit(json_encode(array('status' => 'ERROR', 'errors' => $model->validationErrors)));
                }
            }

            if(!$id) $model->create();
            if ($model->save($save_data)){
                if(!$id){
                    $ins_id = $model->getLastInsertId();
                    if(isset($save_data[$model->alias]['code']) && (empty($save_data[$model->alias]['code']) || $is_copy)){
                        $model->updateAll(array("{$model->alias}.code" => sqls($ins_id, true), "{$model->alias}.created" => sqls(date("Y-m-d H:i:s"), true)), array("{$model->alias}.id" => $ins_id));
                    }
                    if($event) $this->_controller->getEventManager()->dispatch(new CakeEvent((strpos($event, '.') === false ? "{$event}.create" : $event), null, array('id' => $ins_id)));
                } else {
                    if($event) $this->_controller->getEventManager()->dispatch(new CakeEvent((strpos($event, '.') === false ? "{$event}.edit" : $event), null, array('id' => $id)));
                }
                if($this->_controller->RequestHandler->isAjax()) exit($ins_id);
                if($redirect){
                    if($save_data['saction'] == '1'){
                        foreach($save_data[$model->alias] as $key => $val) if(in_array($key, array('parent_id', 'base_id', 'extra_1', 'extra_2', 'extra_3'))){
                            $this->_controller->Session->write("Saction.{$model->alias}.{$key}", $val);
                        }
                        if(empty($_GET['no_redirect'])) $this->_controller->redirect($this->_controller->request->fullhere);
                    }
                    if($save_data['saction'] == '1_1'){
                        $this->_controller->redirect($this->_controller->request->fullhere . (strpos($this->_controller->request->fullhere, '?') !== false ? "{$this->_controller->request->fullhere}&cp_id={$ins_id}" : "{$this->_controller->request->fullhere}?cp_id={$ins_id}"));
                    }
                    if($save_data['saction'] == '2'){
                        $referer = $this->_controller->Session->read('BackUrl');
                        $this->_controller->Session->delete('BackUrl');
						if(empty($referer)) $referer = $this->_controller->request->referer(true);
                        $this->_controller->redirect($referer);
                    }
                    if($save_data['saction'] == '3'){
                        $this->_controller->Session->write('NBackUrl', '1');
                        $this->_controller->redirect($this->_controller->request->here . '/' . $ins_id);
                    }
                }
            } else {
                pr($model->invalidFields());
                exit("ERROR");
            }
        } else {
            if($this->_controller->Session->read('NBackUrl')){
                $this->_controller->Session->delete('NBackUrl');
            } else {
                $referer = ifstrstr($this->_controller->request->referer(true), '?', true);
                if($referer != $this->_controller->request->here) $this->_controller->Session->write('BackUrl', $this->_controller->request->referer(true));
            }
            if($id){
                $model->recursive = 2;
                $this->_controller->request->data = am($this->_controller->request->data, $model->findById($id));
                if(!empty($this->_controller->request->data[$model->alias]['title'])) $this->_controller->set('edit_obj', ' :: ' . (!empty($this->_controller->request->data[$model->alias]['alias']) ? '<a target="_blank" href="'.ws_url($this->_controller->request->data[$model->alias]['alias']).'?tkey='.TMP_KEY.'">'.$this->_controller->request->data[$model->alias]['title'].'</a>' : $this->_controller->request->data[$model->alias]['title']));
            } else {
                if($this->_controller->Session->read("Saction.{$model->alias}")) foreach($this->_controller->Session->read("Saction.{$model->alias}") as $key => $val){
                    $this->_controller->request->data[$model->alias][$key] = $val;
                }
                $this->_controller->Session->delete('Saction');
                if(!empty($_GET['cp_id'])){
                    $cp_dt = $model->findById((int)$_GET['cp_id']);
                    unset($cp_dt['ObjOptAttach']);
                    unset($cp_dt[$model->alias]['code']);
                    $this->_controller->request->data = am($this->_controller->request->data, $cp_dt);
                }
            }
        }
    }

    public function load($id = null, Model $model){
        if(empty($id)) return false;
        if(Configure::read('CMS.settings.' . Configure::read('Config.tid') . '.obj_no_views_count') != '1' && $this->_controller->Cookie->read("{$model->alias}_views.{$id}") != '1'){
            if(array_key_exists('views', $model->schema())){
                $nocache = $model->nocache;
                $model->nocache = '1';
                $model->updateAll(array("{$model->alias}.views" => "`{$model->alias}`.`views` + 1"), array("{$model->alias}.id" => $id));
                $model->nocache = $nocache;
                //$this->_controller->Cookie->write("{$model->alias}_views.{$id}", '1');
            }

        }
        $data = $model->findById($id);
        if(empty($data)) $this->_controller->redirect('/');
        $this->template($data[$model->alias], false, $data);
        return $data;
    }

}

?>
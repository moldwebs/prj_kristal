<?php
App::uses('Model', 'Model');

class AppModel extends Model {
    
    public $actsAs = array('Uid');

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        
        if(!empty($this->qalias)){
            $this->alias = $this->qalias;
            //$this->name = $this->qalias;
        }
    }

    public function getquery($all = false){
        $log = $this->getDataSource()->getLog(false, false);
        krsort($log['log']);
        if($all) return $log['log'];
        foreach($log['log'] as $_log){
            if(strpos($_log['query'], $this->alias) !== false && strpos($_log['query'], 'I18n') === false) return $_log;
        }
        return false;
    }

    public function squery($query){
        $db = $this->getDataSource();
        $subQuery = $db->buildStatement($query, $this);
        return $subQuery;
    }
    
    public function bquery($conditions, $field = 'id'){
        $db = $this->getDataSource();
        $subQuery = $db->buildStatement(array('conditions' => $conditions, 'fields' =>  array("{$this->alias}.{$field}"), 'table' => $db->fullTableName($this), 'alias' => "{$this->alias}"), $this);
        return $subQuery;
    }

	public function _query($sql) {
	    $t = microtime(true);
		$db = $this->getDataSource();
        $return = $db->__execute($sql);
        $db->took = round((microtime(true) - $t) * 1000, 0);
        $db->logQuery($sql);
        return $return;
	}
        
	public function afterValidate(){
	   foreach($this->validationErrors as $key => $val){
	       if(is_array($val)){
	           foreach($val as $_key => $_val){
	               $this->validationErrors[$key][$_key] = ___($_val);
	           }
	       } else {
	           $this->validationErrors[$key] = ___($val);
	       }
	   }
	}
    
    public function invalidate($field, $value = true) {
		return parent::invalidate($field, ___($value));
	}

    public function fread($field = null, $id = null){
        $data = $this->read($field, $id);
        return $data[$this->alias][$field];
    }
    
    public function TreeList($conditions = array(), $symb = TREESYMB){
        if(!empty($conditions['add_opts'])){
            if(!empty($conditions['add_opts']['parent_id'])){
                $data = $this->findById($conditions['add_opts']['parent_id']);
                $conditions[$this->alias . '.lft >'] = $data[$this->alias]['lft'];
                $conditions[$this->alias . '.rght <'] = $data[$this->alias]['rght'];
            }
            if(!empty($conditions['add_opts']['first'])){
                $conditions = am($conditions, array($this->alias . '.parent_id IS NULL'));
            }
            unset($conditions['add_opts']);
        }
        
        //foreach($this->_associations as $assoc) if(!empty($this->{$assoc})) foreach($this->{$assoc} as $assoc_key => $assoc_val) if(!in_array($assoc_key, array('I18n'))) unset($this->{$assoc}[$assoc_key]);
        
        if(!empty($conditions['tid'])){
            $bef_tid = Configure::read('Config.tid');
            Configure::write('Config.tid', $conditions['tid']);
            unset($conditions['tid']);
        }
        
        $return = $this->generateTreeList($conditions, null, null, $symb, 1);
        
        if(!empty($bef_tid)){
            Configure::write('Config.tid', $bef_tid);
        }
        return $return;
    }
    
    public function table_actions($data = array()){
        if(!empty($data['table-action']) && !empty($data['item'])){
            $data['item'] = array_reverse($data['item'], true);
            foreach($data['item'] as $item){
                if($data['table-action'] == 'show'){
                    $this->updateAll(
                        array("{$this->alias}.status" => '1'),
                        array("{$this->alias}.id" => $item)
                    );
                } else if($data['table-action'] == 'hide'){
                    $this->updateAll(
                        array("{$this->alias}.status" => '0'),
                        array("{$this->alias}.id" => $item)
                    );
                } else if($data['table-action'] == 'remove'){
                    $this->delete($item);
                }
            }
        }
        return true;
    }

    public function table_structure($data = array()){
        if(!empty($data['tree-structure'])){
            foreach(unserialize($data['tree-structure']) as $item){
                $this->updateAll(
                    array("{$this->alias}.parent_id" => ($item['parent_id']>0) ? sqls($item['parent_id']) : 'NULL', "{$this->alias}.lft" => sqls($item['left']), "{$this->alias}.rght" => sqls($item['right']), "{$this->alias}.depth" => sqls($item['depth'])),
                    array("{$this->alias}.id" => $item['item_id'])
                );
            }
        }
        return true;
    }

    public function toggle($id = null, $field = null, $val = '1'){
        $field_value = $this->fread($field, $id);
        if($val > 1){
            if($field_value != $val) $field_value = $val; else $field_value = '1';
        } else {
           if($field_value != $val) $field_value = $val; else $field_value = '0'; 
        }
        $this->updateAll(
            array("{$this->alias}.{$field}" => sqls($field_value, true)),
            array("{$this->alias}.id" => $id)
        );
        return true;
    }

    public function datatoggle($id = null, $field = null){
        $data = json_decode($this->ObjOptData->field('data', array('ObjOptData.foreign_key' => $id, 'ObjOptData.model' => $this->alias)), true);
        $data[$field] = abs($data[$field]-1);
        $this->ObjOptData->updateAll(
            array("ObjOptData.data" => sqls(json_encode($data), true)),
            array('ObjOptData.foreign_key' => $id, 'ObjOptData.model' => $this->alias)
        );
        return true;
    }

    public function updateAllData($fields = array(), $id = null){
        $data = json_decode($this->ObjOptData->field('data', array('ObjOptData.foreign_key' => $id, 'ObjOptData.model' => $this->alias)), true);
        $data = am($data, $fields);
        $this->ObjOptData->updateAll(
            array("ObjOptData.data" => sqls(json_encode($data), true)),
            array('ObjOptData.foreign_key' => $id, 'ObjOptData.model' => $this->alias)
        );
        return true;
    }

    public function updateAllField($id = null, $field = array(), $data = null){
        $this->ObjOptField->update_insert(array('value' => $data), array('model' => $this->alias, 'foreign_key' => $id, 'field' => $field, ));
        return true;
    }

    public function delete($id = null, $cascade = true){
        if(in_array('Tree', $this->actsAs)){
            $return = $this->removeFromTree($id, true);
        } else {
            $return = parent::delete($id, $cascade);
        }
        if($this->alias != 'Session' && $this->nocache != '1' && Configure::read('TMP.sql_no_cache') != '1' && Configure::read('TMP.event_no_cache') != '1') Cache::clearGroup('query');
        return $return;
    }
    
    public function update_insert($fields = array(), $conditions = array(), $ins_cond = true){
        $data = $this->find('first', array('conditions' => $conditions));
        if(!empty($data[$this->alias]['id']) && $data[$this->alias]['id'] > 0){
            $this->id = $data[$this->alias]['id'];
        } else {
            $this->create();
        }
        $this->save(array($this->alias => ($ins_cond ? am($fields, $conditions) : $fields)));
        return $this->id;
    }

    public function increment_insert($conditions = array(), $field = null){
        if($this->find('count', array('conditions' => $conditions))){
            $this->updateAll(array("`{$this->alias}`.`{$field}`" => "`{$field}` + 1"), $conditions);
        } else {
            $this->create();
            $this->save(array($this->alias => am($conditions, array("{$field}" => '1'))));
        }
    }
    
    public function insert_new($conditions = array()){
        $data = $this->find('first', array('conditions' => $conditions));
        if(!empty($data[$this->alias]['id']) && $data[$this->alias]['id'] > 0){
            return $data[$this->alias]['id'];
        } else {
            $this->create();
            $this->save(array($this->alias => $conditions));
            return $this->id;
        }
        return false;
    }
    
    public function __getAssociations(){
        $assocs = array();
        foreach($this->_associations as $assoc){
            if (!empty($this->{$assoc})) $assocs[$assoc] = array_keys($this->{$assoc});
        }
        return $assocs;
    }
    
    public function updateAll($fields, $conditions = true){
        $_microtime_start = microtime(true);
        //$this->unbindModel($this->__getAssociations(), true);
        $conditions["{$this->alias}.uid"] = get_uid($this, 'updateAll');
        $return = parent::updateAll($fields, $conditions);
        if((microtime(true) - $_microtime_start) > 0.1) Configure::write('EXEC_TIME_LOGS_read', am(Configure::read('EXEC_TIME_LOGS_read'), array('updateAll-' . $this->alias . '-'.round(microtime(true) - $_microtime_start, 4).'s')));
        if($this->alias != 'Session' && $this->nocache != '1' && Configure::read('TMP.sql_no_cache') != '1' && Configure::read('TMP.event_no_cache') != '1')  Cache::clearGroup('query');
        return $return;
    }
    
    public function deleteAll($conditions, $cascade = false, $callbacks = false){
        $_microtime_start = microtime(true);
        //$this->unbindModel($this->__getAssociations(), true);
        $conditions["{$this->alias}.uid"] = get_uid($this, 'deleteAll');
        $return = parent::deleteAll($conditions, $cascade, $callbacks);
        if((microtime(true) - $_microtime_start) > 0.1) Configure::write('EXEC_TIME_LOGS_read', am(Configure::read('EXEC_TIME_LOGS_read'), array('deleteAll-' . $this->alias . '-'.round(microtime(true) - $_microtime_start, 4).'s')));
        if($this->alias != 'Session' && $this->nocache != '1' && Configure::read('TMP.sql_no_cache') != '1' && Configure::read('TMP.event_no_cache') != '1')  Cache::clearGroup('query');
        return $return;
    }

    public function save($data = null, $validate = true, $fieldList = array()) {
        $_microtime_start = microtime(true);
        $return = parent::save($data, $validate, $fieldList);
        if((microtime(true) - $_microtime_start) > 0.1) Configure::write('EXEC_TIME_LOGS_read', am(Configure::read('EXEC_TIME_LOGS_read'), array('save-' . $this->alias . '-'.round(microtime(true) - $_microtime_start, 4).'s')));
        if(Configure::read('TMP.event_no_cache') == '1'){
            if(is_array($this->actsAs) && array_key_exists('Relation', $this->actsAs)){
                Configure::write('TMP.event_no_cache', '0');
            }
        }
        if($this->alias != 'Session' && $this->nocache != '1' && Configure::read('TMP.sql_no_cache') != '1' && Configure::read('TMP.event_no_cache') != '1'){
            Configure::write('EXEC_TIME_LOGS_read', am(Configure::read('EXEC_TIME_LOGS_read'), array('cachclear-' . $this->alias)));
            Cache::clearGroup('query');
        }  
        return $return;
    }
    
    public function options($tid = null) {
        $return = array();
        
        $parents = $this->ObjItemTree->find('list', array('tid' => $tid, 'fields' => array('ObjItemTree.id', 'ObjItemTree.title'), 'order' => array('ObjItemTree.lft' => 'asc')));
        $options = $this->find('list', array('tid' => $tid, 'fields' => array('ObjItemList.id', 'ObjItemList.title', 'ObjItemList.base_id'), 'order' => array('ObjItemList.order_id' => 'asc', 'ObjItemList.title' => 'asc')));
        $options_codes = $this->find('list', array('tid' => $tid, 'fields' => array('ObjItemList.id', 'ObjItemList.code')));
        
        foreach($parents as $key => $val){
            if(empty($options[$key])) continue;
            $return['data'][$key] = array('title' => $val, 'options' => $options[$key]);
            $return['list'][$val] = $options[$key];
            foreach($options[$key] as $_key => $_val) $return['slist'][$_key] = $_val . " ({$val})";
            foreach($options[$key] as $_key => $_val) $return['cslist'][$_key] = $options_codes[$_key];
        }
        
        return $return;
    }

    public function __find($type = 'first', $query = array()){
        $_type = $type;
        if($type == 'allc') $_type = 'all';
        if($type == 'allindex') $_type = 'all';
        if($type == 'allindex'){
            $_data = parent::find($_type, $query);
            $data = array();
            if(!empty($_data)) foreach($_data as $key => $val){
                $data[$val[$this->alias]['id']] = $val;
            }
            return $data;
        } else {
            return parent::find($_type, $query);
        }
    }

	public function find($type = 'first', $query = array()) {
		$btype = $type;
        
        if($type == 'allc') $type = 'all';
        if($type == 'allindex') $type = 'all';
        if($type == 'ilist') $type = 'all';
        if($type == 'ialllist') $type = 'all';
        if($type == 'icount') $type = 'list';
        if($type == 'ids'){
            $type = 'all';
            $query['fields'] = array('id');
        }
        
        $before_model = array();
        
        $this->findQueryType = $type;
        
        $before_model['findQueryExtra'] = $this->findQueryExtra;
        $before_model['virtualFields'] = $this->virtualFields;
        
        if(!empty($query['extra'])) $this->findQueryExtra = $query['extra'];
        if(!empty($query['virtual'])) $this->virtualFields = am($this->virtualFields, $query['virtual']);
		
        $this->id = $this->getID();

		$query = $this->buildQuery($type, $query);
		if (is_null($query)) {
			return null;
		}
        
        if(!empty($this->custom_cache)){
            if(!($results = Cache::read(md5(serialize($query)), $this->custom_cache))){

                $results = $this->getDataSource()->read($this, $query);
                
        		$this->resetAssociations();
        
        		if ($query['callbacks'] === true || $query['callbacks'] === 'after') {
        			$results = $this->_filterResults($results);
        		}
                
                //_pr($results);
                
                Cache::write(md5(serialize($query)), $results, $this->custom_cache);
            }
        } else {
            Configure::write('TMP.sql_no_cache_before', Configure::read('TMP.sql_no_cache'));
            
            if($this->alias == 'Session' || $this->nocache == '1' || $query['nocache'] == '1') Configure::write('TMP.sql_no_cache', '1');
            
            $results = $this->getDataSource()->read($this, $query);
            
    		$this->resetAssociations();
    
    		if ($query['callbacks'] === true || $query['callbacks'] === 'after') {
    			$results = $this->_filterResults($results);
    		}
            
            Configure::write('TMP.sql_no_cache', Configure::read('TMP.sql_no_cache_before'));
        }
        
		$this->findQueryType = null;
        $this->findQueryExtra = $before_model['findQueryExtra'];
        $this->virtualFields = $before_model['virtualFields'];

        if($btype == 'allindex'){
            $indexfield = (!empty($query['indexfield']) ? $query['indexfield'] : 'id');
            $data = array();
            if(!empty($results)) foreach($results as $key => $val){
                if(!empty($query['indexfield1'])){
                    $data[$val[$this->alias][$indexfield]][$val[$this->alias][$query['indexfield1']]] = $val;
                } else {
                    $data[$val[$this->alias][$indexfield]] = $val;
                }
            }
            return $data;
        }

        if($btype == 'ilist'){
            $data = array();
            if(!empty($results)) foreach($results as $key => $val){
                $data[$val[0][key($val[0])]] = $val[0][key($val[0])];
            }
            return $data;
        }

        if($btype == 'iall'){
            $data = array();
            if(!empty($results)) foreach($results as $key => $val){
                $data[$key] = $val;
                if(!empty($val[0])){
                    foreach($val[0] as $_key => $_val) $data[$key][$this->alias][str_replace("{$this->alias}__", '', $_key)] = $_val;
                    unset($data[$key][0]);
                }
            }
            return $data;
        }

        if($btype == 'ialllist'){
            $data = array();
            if(!empty($results)) foreach($results as $key => $val){
                $data[$key] = $val;
                if(!empty($val[0])){
                    foreach($val[0] as $_key => $_val) $data[$key][$this->alias][str_replace("{$this->alias}__", '', $_key)] = $_val;
                    unset($data[$key][0]);
                }
            }
            
            $_data = array();
            if(!empty($data)) foreach($data as $key => $val){
                $_data[array_values($val[$this->alias])[0]] = array_values($val[$this->alias])[1];
            }
            return $_data;
        }

        if($btype == 'icount'){
            $data = array();
            if(!empty($results)) foreach($results as $key => $val){
                $data[$val[$this->alias][key($val[$this->alias])]] = $val[0][key($val[0])];
            }
            return $data;
        }

        if($btype == 'ids'){
            $data = array();
            if(!empty($results)) foreach($results as $key => $val){
                $data[$val[$this->alias]['id']] = $val[$this->alias]['id'];
            }
            return $data;
        }
        
		if ($type === 'all') {
			return $results;
		}

		if ($this->findMethods[$type] === true) {
			return $this->{'_find' . ucfirst($type)}('after', $query, $results);
		}
	}

}

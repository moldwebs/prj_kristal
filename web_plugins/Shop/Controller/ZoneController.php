<?php
class ZoneController extends ShopAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        Configure::write('Config.tid', 'shipping_zone');
        
        $this->set('parents', $this->ObjItemTree->TreeList(array('ObjItemTree.parent_id IS NULL')));
    }

    public function admin_table_actions(){
        $this->ObjItemTree->table_actions($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_table_structure(){
        $this->ObjItemTree->table_structure($this->request->data);
        $this->Basic->back();
    }
    
    public function admin_index(){
        $this->set('page_title', ___('Shipping Zone') . ' :: ' . ___('List'));
        
        $this->set('items', $this->ObjItemTree->find('all', array('order' => 'ObjItemTree.lft')));
    }
    
    public function admin_edit($id = null){
        $this->Basic->save_load($id, $this->ObjItemTree, true);
        
        if($id){
            $this->set('page_title', ___('Shipping Zone') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Shipping Zone') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }
    
	public function admin_delete($id = null){
	    $this->ObjItemTree->delete($id);
        $this->Basic->back();
	}

    public function admin_status($id = null){
        $this->ObjItemTree->toggle($id, 'status');
        $this->Basic->back();
    }

    public function admin_import(){
        exit;
        
        App::import('Vendor', 'Catalog.phpexcel/excelreader');
        $Reader = new SpreadsheetReader(ROOT . DS . 'web_views' . DS . 'template' . DS . 'shop_zones.xlsx', 'shop_zones.xlsx');
        foreach($Reader as $row) $data[] = $row;
        foreach($data as $_data){
            if(trim($_data[0]) == '') continue;
            
            $_data[2] = str_replace(',', '.', str_replace('km', '', strtolower($_data[2])));
            
            if($_data[0] != $last[0]){
                $this->ObjItemTree->create();
                $this->ObjItemTree->save(array(
                    'Translates' => array('ObjItemTree' => array('title' => array(
                        'rom' => trim($_data[0]),
                        'rus' => trim($_data[3]),
                    ))),
                    'ObjItemTree' => array(
                        'status' => '1',
                        'data' => array('distance' => trim($_data[2]))
                    )
                ));
                $parent_id = $this->ObjItemTree->getLastInsertId();
            }

            $this->ObjItemTree->create();
            $this->ObjItemTree->save(array(
                'Translates' => array('ObjItemTree' => array('title' => array(
                    'rom' => trim($_data[1]),
                    'rus' => trim($_data[4]),
                ))),
                'ObjItemTree' => array(
                    'parent_id' => $parent_id,
                    'status' => '1',
                    'data' => array('distance' => trim($_data[2]))
                )
            ));

            $last = $_data;
        }
        _pr($data);
        exit;
    }

}

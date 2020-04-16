<?php
class FileController extends BaseAppController {

    public function beforeFilter() {
        parent::beforeFilter();
        Configure::write('Config.tid', 'cms_file');
    }

    function admin_table_actions(){
        if(!empty($this->request->data['table-action']) && !empty($this->request->data['item'])){
            foreach($this->request->data['item'] as $item){
                if($this->request->data['table-action'] == 'remove'){
                    unlink(UPL_DIR . DS . UPL_FILES . DS . base64_decode($item));
                }
            }
        }
        $this->Basic->back();
    }

    public function admin_index(){
        $this->set('page_title', ___('Files') . ' :: ' . ___('List'));
        
        if(!empty($this->request->query['fltr_lk__title'])) $this->request->data['ObjItemList']['fltr_lk__title'] = $this->request->query['fltr_lk__title'];
        
        foreach(scandir(UPL_DIR . DS . UPL_FILES) as $file){
            if($file == '.' || $file == '..') continue;
            if(!empty($this->request->data['ObjItemList']['fltr_lk__title']) && substr_count(strtolower($file), strtolower($this->request->data['ObjItemList']['fltr_lk__title'])) == 0) continue;
            $items[] = array('title' => $file, 'created' => date ("Y-m-d H:i:s", filemtime(UPL_DIR . DS . UPL_FILES . DS . $file)));
        }
        
        if(!empty($items)) usort($items, function($a, $b) { return strnatcmp($a["title"], $b["title"]); });
        
        $this->set('items', $items);
    }
    
    public function admin_edit(){
        $this->set('page_title', ___('Files') . ' :: ' . ___('Create'));
        $this->request->edit_mode = 'create';
        
        if(!empty($this->data)){
            if($this->data['ObjItemList']['mode'] == '1'){
                if(!in_array(ws_ext($this->data['ObjItemList']['title']), Configure::read('CMS.denny_upload_files_ext')) && ws_ext($this->data['ObjItemList']['title']) != ''){
                    file_put_contents(UPL_DIR . DS . UPL_FILES . DS . ws_name($this->data['ObjItemList']['title']) . '.' . ws_ext($this->data['ObjItemList']['title']), $this->data['ObjItemList']['body']);
                }
            } else {
                $upload = $this->Upload->upload($this->data['ObjItemList']['attachment'], false);
            }
            $this->Basic->back();
        } else {
            $this->Session->write('BackUrl', $this->request->referer(true));
        }
    }

	function admin_delete($id = null){
	    unlink(UPL_DIR . DS . UPL_FILES . DS . base64_decode($id));
        $this->Basic->back();
	}

    public function admin_template(){
        $this->set('page_title', ___('Files') . ' :: ' . ___('Template'));
        
    }
}

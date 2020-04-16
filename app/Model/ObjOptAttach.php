<?php
class ObjOptAttach extends AppModel {
    
    public $useTable = 'wb_obj_opt_attachment';
    
    public function beforeDelete() {
        App::import('Component', 'Upload');
        $upload_component = new UploadComponent(new ComponentCollection());
        
        $data = $this->read();
        if(!empty($data['ObjOptAttachDef'])) $data['ObjOptAttach'] = $data['ObjOptAttachDef'];
        if($data['ObjOptAttach']['foreign_key'] != $this->item_foreign_key && !empty($this->item_foreign_key)) return false;
        if($data['ObjOptAttach']['location'] == 'video') return true;
        $upload_component->delete($data['ObjOptAttach']['file']);
        return true;
    }
}
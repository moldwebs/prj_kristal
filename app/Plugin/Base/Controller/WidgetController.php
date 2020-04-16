<?php
class WidgetController extends BaseAppController {

    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->widget_path = (!empty($this->cmstheme) ? str_replace('web_views', 'web_themes', EXT_VIEWS) . DS . $this->cmstheme : EXT_VIEWS) . DS .  'Templates' . DS . 'widgets';
    }
        
    public function admin_index(){
        $this->set('page_title', ___('Items') . ' :: ' . ___('List'));
        
        $items = array();
        foreach(glob($this->widget_path . DS . '*') as $widget){
            $nfo = pathinfo($widget);
            $items[] = array('title' => ucfirst($nfo['filename']), 'id' => $nfo['filename'], 'status' => ($nfo['extension'] == 'ctp' ? '1' : '0'));
        }
        
        $this->set('items', $items);
    }
    
    public function admin_edit($id = null){
        
        
        if($id){
            $this->set('page_title', ___('Items') . ' :: ' . ___('Edit'));
            $this->request->edit_mode = 'modify';
        } else {
            $this->set('page_title', ___('Items') . ' :: ' . ___('Create'));
            $this->request->edit_mode = 'create';
        }
    }

    public function admin_status($id = null){
        if(file_exists($this->widget_path . DS . $id . '.ctp')){
            rename($this->widget_path . DS . $id . '.ctp', $this->widget_path . DS . $id . '.ctph');
        } else {
            rename($this->widget_path . DS . $id . '.ctph', $this->widget_path . DS . $id . '.ctp');
        }
        $this->Basic->back();
    }
}

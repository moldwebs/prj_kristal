<?php
class SystemController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->Auth->allow('admin_js');
        
        $this->layout = false;
    }

	function toggle($name = null, $value = null){
	    if(!empty($name)){
	       //$this->Session->write("Toggle." . $name, $value);
	       $this->Cookie->write("Toggle." . $name, $value, true, '1 year');
            if($this->RequestHandler->isAjax()){
                exit('OK');
            } else {
                if(empty($_GET['back'])){
                    $this->redirect($this->referer());
                } else {
                    $this->redirect($_GET['back']);
                }
            }
	    } 
	}


    public function download(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $file = base64_decode($_GET['file']);
        $file = str_replace('..', '', $file);
        $file = str_replace('/',  '', $file);

        $fullPath = sys_get_temp_dir() . DS . $file;
        if(!file_exists($fullPath)){
            exit('ERROR');
        }

        if ($fd = fopen ($fullPath, "r")) {
            $fsize = filesize($fullPath);
            //header('Content-Type: application/octet-stream; charset=UTF-8');
            header("Content-type: application/octet-stream");
            header("Content-Disposition: filename=\"".$file."\"");
            header("Content-length: $fsize");
            header("Cache-control: private"); //use this to open files directly
            //echo "\xEF\xBB\xBF";
            while(!feof($fd)) {
                $buffer = fread($fd, 2048);
                echo $buffer;
            }
        }
        fclose ($fd);
        unlink($fullPath);
        exit;
    }
    
    public function mail(){
        if(!empty($this->data['form'])){
            if($_SESSION['captcha'] != $this->data['captcha']) exit(___('Incorrect Security Code'));
            
            if($this->data['ajx_validate'] != '1'){
               
                $body = null;
                foreach($this->data['form'] as $key => $val){
                    if($key == 'captcha') continue;
                    $body .= "{$key}: {$val}<br>";
                }
                
                $attachments = array();
                if(!empty($this->data['file'])){
                    foreach($this->data['file'] as $file) $attachments[] = array($file['name'] => $file['tmp_name']);
                }
                
                $reply_to = null;
                if(!empty($this->data['email'])) $reply_to = $this->data['email'];
                
                $this->Basic->mail(null, null, $body, $attachments, null, $reply_to);
                $this->Session->setFlash(___('Your message was sent successfull.'), 'flash');
                unset($_SESSION['captcha']);
                $this->redirect($this->request->referer(true));
            }
            exit('OK');
        }
    }

    public function admin_testmail(){
        $this->Basic->mail(null, null, 'TEST EMAIL');
        exit('OK');
    }
    
    function window(){
        $this->set('get', $this->params->query);
        $this->render("elements/window/".$this->params->query['tpl']);
    }
    
    public function get_url($alias = null, $selected = null){
        $this->layout = false;
        $this->set('alias', $alias);
        $this->set('selected', $selected);
        $this->set('items', $this->requestAction($this->params->query['url']));
        $this->render($this->params->query['render']);
    }
    
    public function captcha(){
        App::import('Vendor', 'captcha/captcha');
        $captcha = new SimpleCaptcha();
        $captcha->CreateImage();
        exit;
    }
    
    public function js(){
        header('Content-Type: application/javascript');
        echo eval('?>' . file_get_contents(ROOT . DS . 'app' . DS . 'webroot' . DS . 'js' . DS . 'sys_front.js') . '<?');
        exit;
    }
    
    public function admin_js(){
        header('Content-Type: application/javascript');
        echo eval('?>' . file_get_contents(ROOT . DS . 'app' . DS . 'webroot' . DS . 'js' . DS . 'sys.js') . '<?');
        exit;
    }
    
    public function admin_clear(){
        Cache::clear();
        $this->Basic->back();
    }
    
    public function admin_crop_image($id = null){
        $item = $this->ObjItemList->ObjOptAttach->findById($id);
        if(!empty($this->data)){
            if($this->data['w'] > 0 && $this->data['h'] > 0) $this->Upload->crop($item['ObjOptAttach']['file'], $this->data);
            exit('ajx_win_close();');
        }
        $this->set('item', $item);
    }
    
    public function admin_thumb_image($id = null){
        $item = $this->ObjItemList->ObjOptAttach->findById($id);
        if(!empty($this->data)){
            if($this->data['w'] > 0 && $this->data['h'] > 0) $this->Upload->crop($item['ObjOptAttach']['file'], $this->data, true);
            exit('ajx_win_close();');
        }
        $this->set('item', $item);
    }
    
    public function admin_rename_image($id = null){
        if(!empty($this->data)){
            $this->ObjItemList->ObjOptAttach->updateAll(
                array("ObjOptAttach.title" => sqls($this->data['ObjOptAttach']['title'], true), "ObjOptAttach.type" => sqls($this->data['ObjOptAttach']['type'], true)),
                array("ObjOptAttach.id" => $id)
            );
            exit('ajx_win_close();');
        } else {
            $this->data = $this->ObjItemList->ObjOptAttach->findById($id);
        }
    }

    public function admin_url_image($id = null){
        if(!empty($this->data)){
                $id = md5(uniqid());
                $output = '<div>';
                $output .= '<div class="Attach" style="float: left; position: relative;"><img align="middle" src="' . $this->data['url'] . '"></a><a onclick="$(this).parent().parent().remove();" class="ico ico_remove"></a>' . '<span></span></div>';
                $output .= '<div style="float: left; width: 900px;"><div class="n4 cl">';
                $output .= '<input type="hidden" name="data[attachments_args][order][url_'.$id.']" value="1">';
                $output .= '<input type="hidden" name="data[url_attachments]['.$id.']" value="' . $this->data['url'] . '">';
                $output .= '<div class="input text"><label>'.___('Title').'</label><input name="data[attachments_args][title][url_'.$id.']" value="" type="text" /></div>';
                $output .= '<div class="input text"><label>'.___('Source').'</label><input name="data[attachments_args][type][url_'.$id.']" value="" type="text" /></div>';
                $output .= '</div></div>';
                $output .= '<div style="clear: both;"></div>';
                $output .= '</div>';
                
            exit('ajx_win_close();$(\'.attachments_box\').append(\''.$output.'\');');
        }
    }
    
    public function admin_url_video($id = null){
        if(!empty($this->data)){
                $id = md5(uniqid());
                $output = '<div>';
                $output .= '<div class="Attach" style="float: left; position: relative;"><img align="middle" src="' . ws_video_img(ws_video_file($this->data['url'])) . '"></a><a onclick="$(this).parent().parent().remove();" class="ico ico_remove"></a>' . '<span></span></div>';
                $output .= '<div style="float: left; width: 900px;"><div class="n4 cl">';
                $output .= '<input type="hidden" name="data[attachments_args][order][video_'.$id.']" value="1">';
                $output .= '<input type="hidden" name="data[video_attachments]['.$id.']" value="' . ws_video_file($this->data['url']) . '">';
                $output .= '<div class="input text"><label>'.___('Title').'</label><input name="data[attachments_args][title][video_'.$id.']" value="" type="text" /></div>';
                $output .= '<div class="input text"><label>'.___('Source').'</label><input name="data[attachments_args][type][video_'.$id.']" value="" type="text" /></div>';
                $output .= '</div></div>';
                $output .= '<div style="clear: both;"></div>';
                $output .= '</div>';
                
            exit('ajx_win_close();$(\'.attachments_box\').append(\''.$output.'\');');
        }
    }
    
    public function admin_upload_file(){
        $upload = $this->Upload->upload($_FILES['file'], false);
        echo stripslashes(json_encode(array('filelink' => DS . $upload['file'], 'filename' => $upload['file'])));   
        exit;
    }
    
    public function admin_upload_image(){
        $upload = $this->Upload->upload($_FILES['file'], false);
        echo stripslashes(json_encode(array('filelink' => DS . $upload['file'])));
        //$path_parts = pathinfo($_FILES['file']['name']);
        //$file_data = file_get_contents($_FILES['file']['tmp_name']);
        //echo stripslashes(json_encode(array('filelink' => 'data:image/'.$path_parts['extension'].';base64,' . base64_encode($file_data))));
        exit;
    }
    
    public function admin_get_image_list(){
        $results = array();
        foreach(scandir(UPL_DIR . DS . UPL_FILES) as $file){
            if($file == '.' || $file == '..' || !in_array(ws_ext($file), ws_ext_img())) continue;
            $results[] = array('title' => $file, 'thumb' => DS . $file, 'image' => DS . $file);
        }
        echo stripslashes(json_encode($results));
        exit;
    }

    public function admin_get_file_list(){
        $results = array();
        foreach(scandir(UPL_DIR . DS . UPL_FILES) as $file){
            if($file == '.' || $file == '..' || in_array(ws_ext($file), ws_ext_img())) continue;
            $results[] = array('title' => $file, 'thumb' => '/img/ext/' . ws_ext($file) . '.png', 'image' => DS . $file);
        }
        echo stripslashes(json_encode($results));
        exit;
    }

    public function admin_save_image(){
        if(in_array(ws_ext($_POST['filename']), ws_ext_img())){
            file_put_contents(UPL_DIR . DS . UPL_FILES . DS . $_POST['filename'], base64_decode(explode(',', $_POST['data'])[1]));
        }
        exit($_POST['filename']);
    }

    public function admin_ckeditor_templates(){
        $data = array(
            'imagesPath' => '',
            'templates' => array()
        );

        if(is_dir(EXT_TPL . DS . 'web_configs' . DS . 'templates')){
            App::uses('Xml', 'Utility');
            
            foreach(scandir(EXT_TPL . DS . 'web_configs' . DS . 'templates') as $dir){
                if($dir == '.' || $dir == '..') continue;
                {
                    $xmlArray = Xml::toArray(Xml::build(file_get_contents(EXT_TPL . DS . 'web_configs' . DS . 'templates' . DS . $dir . DS . 'info.xml')));
                    if(!empty($xmlArray['template']['hide'])) continue;
                    $data['templates'][] = array(
                        'title' => $xmlArray['template']['title'],
                        'image' => "/web_configs/templates/{$dir}/{$xmlArray['template']['image']}",
                        'description' => $xmlArray['template']['description'],
                        'html' => file_get_contents(EXT_TPL . DS . 'web_configs' . DS . 'templates' . DS . $dir . DS . 'html.html')
                    );
                }
            }
        }
        exit(json_encode($data));
    }

    public function admin_ckeditor_upload(){
        $upload = $this->Upload->upload($_FILES['upload'], false);
        if(!empty($upload['file'])){
            echo '<script>history.back();</script>';
        } else {
            _pr($upload);
            echo $upload;
        }
        exit;
    }

    public function admin_ckeditor_browse(){
        $count = 0;
        
        $dir = UPL_DIR . DS . UPL_FILES . DS;

        $files = glob("$dir*.{jpg,jpe,jpeg,png,gif,ico}", GLOB_BRACE);
        usort($files, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));
        
        for($i=count($files)-1; $i >= 0; $i--){
            $image = $files[$i];
            $image_pathinfo = pathinfo($image);
            $image_extension = $image_pathinfo['extension'];
            $image_filename = $image_pathinfo['filename'];
            $image_basename = $image_pathinfo['basename'];

            // image src/url
            $protocol = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $site = $protocol. $_SERVER['SERVER_NAME'] .'/';
            //$image_url = $site.$useruploadfolder."/".$image_basename;
            $image_url = "/".$image_basename;

            $size = getimagesize($image);
            $image_height = $size[0];
            $file_size_byte = filesize($image);
            $file_size_kilobyte = ($file_size_byte/1024);
            $file_size_kilobyte_rounded = round($file_size_kilobyte,1);
            $filesizetemp = $file_size_kilobyte_rounded;
            $filesizefinal = round($filesizefinal + $filesizetemp) . " KB";
            $calcsize = round($filesizefinal + $filesizetemp);
            $count = ++$count;

            ?>
                <div class="fileDiv"
                     onclick="showEditBar('<?php echo $image_url; ?>','<?php echo $image_height; ?>','<?php echo $count; ?>','<?php echo $image_basename; ?>');"
                     ondblclick="showImage('<?php echo $image_url; ?>','<?php echo $image_height; ?>','<?php echo $image_basename; ?>');"
                     data-imgid="<?php echo $count; ?>">
                    <div class="imgDiv"><img class="fileImg lazy" src="<?php echo $image_url; ?>"></div>
                    <p class="fileDescription"><span class="fileMime"><?php echo $image_extension; ?></span> <?php echo $image_filename; ?><?php if($file_extens == "yes"){echo ".$image_extension";} ?></p>
                    <p class="fileTime"><?php echo date ("F d Y H:i", filemtime($image)); ?></p>
                    <p class="fileTime"><?php echo $filesizetemp; ?> KB</p>
                </div>
            <?php

        }
        exit;
    }

    public function sitemap(){
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');        
        
        App::uses('Xml', 'Utility');

        $aliases = array();
        $_aliases = ClassRegistry::init('CmsAlias')->find('allc');
        foreach($_aliases as $_alias){
            $aliases[$_alias['CmsAlias']['tid']][$_alias['CmsAlias']['model']][$_alias['CmsAlias']['foreign_key']][$_alias['CmsAlias']['locale']] = $_alias['CmsAlias']['alias'];
        }
        //$aliases = Configure::read('CMS.aliases');
        $_locale = Configure::read('Config.language');
        
        $xmlArray = array();
        
        $xmlArray['urlset'] = array('xmlns:' => 'http://www.sitemaps.org/schemas/sitemap/0.9');

        /*
        $items = $this->ObjItemTree->find('all', array('tid' => 'cms_link', 'conditions' => array('ObjItemTree.extra_1' => '2'), 'order' => array('ObjItemTree.lft' => 'asc')));
        foreach($items as $item){
            $xmlArray['urlset']['url'][] = array(
                //'oth' => $item['ObjItemTree']['alias'] . '/' . $item['ObjItemTree']['title'],
                'loc' => FULL_BASE_URL . rtrim(ws_url($item['ObjItemTree']['alias'], false), '/'),
                'lastmod' => date("Y-m-d", strtotime($item['ObjItemTree']['created'])),
                'priority' => '0.5',
            );
        }
        */

        $locales = Configure::read('CMS.activelanguages');
        
        $items = $this->ObjItemTree->find('list', array('fields' => array('id', 'created'), 'tid' => 'cms_link', 'conditions' => array('ObjItemTree.extra_1' => '2'), 'order' => array('ObjItemTree.lft' => 'asc')));
        foreach($items as $id => $created) foreach($locales as $_locale => $_locale_name){
            if(empty($aliases['cms_link']['ObjItemTree'][$id][$_locale])) continue;
            
            if(count($locales) > 1) Configure::write('Config.req_language', $_locale);
            
            $xmlArray['urlset']['url'][] = array(
                'loc' => FULL_BASE_URL . rtrim(ws_url($aliases['cms_link']['ObjItemTree'][$id][$_locale], false), '/'),
                'lastmod' => date("Y-m-d", strtotime($created)),
                'priority' => '0.5',
            );
        }
        
        $tids = array();
        foreach(Configure::read('CMS.fill_tid') as $_tid => $__tid) $tids[] = $_tid;
        foreach(Configure::read('CMS.sys_tid') as $_tid => $__tid) $tids[] = $_tid;
        
        foreach($tids as $tid){
            $items = $this->ObjItemTree->find('list', array('fields' => array('id', 'created'), 'tid' => $tid, 'order' => array('ObjItemTree.lft' => 'asc')));
            foreach($items as $id => $created) foreach($locales as $_locale => $_locale_name){
                if(empty($aliases[$tid]['ObjItemTree'][$id][$_locale])) continue;
                
                if(count($locales) > 1) Configure::write('Config.req_language', $_locale);
                
                $xmlArray['urlset']['url'][] = array(
                    'loc' => FULL_BASE_URL . rtrim(ws_url($aliases[$tid]['ObjItemTree'][$id][$_locale], false), '/'),
                    'lastmod' => date("Y-m-d", strtotime($created)),
                    'priority' => '0.7',
                );
            }
            $items = $this->ObjItemList->find('list', array('fields' => array('id', 'created'), 'tid' => $tid, 'order' => array('ObjItemList.created' => 'desc')));
            foreach($items as $id => $created) foreach($locales as $_locale => $_locale_name){
                if(empty($aliases[$tid]['ObjItemList'][$id][$_locale])) continue;
                
                if(count($locales) > 1) Configure::write('Config.req_language', $_locale);
                
                $xmlArray['urlset']['url'][] = array(
                    'loc' => FULL_BASE_URL . rtrim(ws_url($aliases[$tid]['ObjItemList'][$id][$_locale], false), '/'),
                    'lastmod' => date("Y-m-d", strtotime($created)),
                    'priority' => '0.6',
                );
            }
        }
        
        $xmlObject = Xml::fromArray($xmlArray);
        $xmlString = $xmlObject->asXML();

        header('Content-Type: application/xml; charset=utf-8');
       
        echo $xmlString;
        exit;
        
    }
}

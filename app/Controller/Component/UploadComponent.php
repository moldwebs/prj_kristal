<?php
App::uses('Component', 'Controller');

class UploadComponent extends Component {
    
    protected $_controller = null;
    
    public $_tid = null;

	public function startup(Controller $controller) {
		//$this->_controller = $controller;
	}
    
    public function set_tid($tid){
        $this->_tid = $tid;
    }
    
    public function upload($file, $image = true){

        //if(ws_ext($file['name']) == 'mp4') return $this->upload_google($file);
        
        if(in_array(ws_ext($file['name']), Configure::read('CMS.denny_upload_files_ext')) || ws_ext($file['name']) == '') return ___('Invalid file type.');
        if(Configure::read('CMS.allow_upload_files') != '1' && !in_array(ws_ext($file['name']), ws_ext_img())) return ___('Invalid file type');
        if($file['size'] > UPL_MAX_SIZE || $file['size'] == 0) return ___('Invalid file size');
        
        $ws_name = ws_alias(ws_name($file['name']));

        if(!empty($this->_tid)) $ws_name = $this->_tid . '_' . $ws_name;
        
        if($image){
            $destination = UPL_IMAGES;
            //$file_name = md5(uniqid()). '.' . ws_ext($file['name']);
            if(file_exists(UPL_DIR . DS . $destination . DS . 'large' . DS . $ws_name. '.' . ws_ext($file['name']))){
                $file_name = $ws_name . '_' . uniqid() . '.' . ws_ext($file['name']);
            } else {
                $file_name = $ws_name . '.' . ws_ext($file['name']);
            }
        } else {
            $destination = UPL_FILES;
            $file_name = $ws_name . '.' . ws_ext($file['name']);
        }
        
        if(!is_dir(UPL_DIR . DS . $destination)) mkdir(UPL_DIR . DS . $destination, 0777, true);
        
        if($image){
            if(!is_dir(UPL_DIR . DS . $destination . DS . 'large')) mkdir(UPL_DIR . DS . $destination . DS . 'large', 0777, true);
            if(!move_uploaded_file($file['tmp_name'], UPL_DIR . DS . $destination . DS . 'large' . DS . $file_name)) return ___('Error moving file upload');

            if(in_array(ws_ext($file_name), ws_ext_img())){
//                if(ws_ext($file_name) != 'gif') $this->resize(UPL_DIR . DS . $destination . DS . 'large' . DS . $file_name , '2000', '2000');
                if(!is_dir(UPL_DIR . DS . $destination . DS . 'thumb')) mkdir(UPL_DIR . DS . $destination . DS . 'thumb', 0777, true);
                copy(UPL_DIR . DS . $destination . DS . 'large' . DS . $file_name, UPL_DIR . DS . $destination . DS . 'thumb' . DS . $file_name);
                $this->resize(UPL_DIR . DS . $destination . DS . 'thumb' . DS . $file_name , '400', '400');
            }
        } else {
            if(!move_uploaded_file($file['tmp_name'], UPL_DIR . DS . $destination . DS . $file_name)) return ___('Error moving file upload');
        }
        return array('file' => $file_name, 'title' => $file['name'], 'path' => $destination, 'ext' => ws_ext($file_name), 'size' => filesize(UPL_DIR . DS . $destination . DS . 'large' . DS . $file_name));
    }
    
    public function upload_google($file){
        require_once ROOT . DS . 'app' . DS . 'Vendor' . DS . 'google-api-php-client/src/Google_Client.php';
        require_once ROOT . DS . 'app' . DS . 'Vendor' . DS . 'google-api-php-client/src/contrib/Google_DriveService.php';
        
        $client = new Google_Client();
        // Get your credentials from the APIs Console
        $client->setClientId('345369542106-cup9i3d6h55dc045bh24al31p2avp3be.apps.googleusercontent.com');
        $client->setClientSecret('lqCYFRuXAfLisqU5kIt_fZ9P');
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
        $client->setScopes(array('https://www.googleapis.com/auth/drive'));
        
        $service = new Google_DriveService($client);
        
        $authUrl = $client->createAuthUrl();
        $authCode = trim(fgets(STDIN));
        
        // Exchange authorization code for access token
        $accessToken = $client->authenticate($authCode);
        $client->setAccessToken($accessToken);
        
        //Insert a file
        $gfile = new Google_DriveFile();
        $gfile->setTitle('My document');
        $gfile->setDescription('A test document');
        $gfile->setMimeType('text/plain');
        
        //$data = file_get_contents('document.txt');
        
        $createdFile = $service->files->insert($gfile, array(
          'data' => 'test ok',
          'mimeType' => 'text/plain',
        ));
        pr(json_decode($createdFile));
        exit;
    }
    
    public function upload_url($file, $image = true){
        
        if(strpos($file, 'hidemyass') !== false){
            $file = 'http' . base64_decode(urldecode(substr($file, strrpos($file, '/') + 1)));
            $encoded = true;
        }
        
        $store_file = (strpos($file, '?') !== false ? uniqid() . '.png' : $file);
        
        if(in_array(ws_ext($store_file), Configure::read('CMS.denny_upload_files_ext')) || ws_ext($store_file) == '') return ___('Invalid file type.');
        if(Configure::read('CMS.allow_upload_files') != '1' && !in_array(ws_ext($store_file), ws_ext_img())) return ___('Invalid file type');
                   
        $ws_name = ws_alias(ws_name($store_file));
        if(!empty($this->_tid)) $ws_name = $this->_tid . '_' . $ws_name;
        
        if($image){
            $destination = UPL_IMAGES;
            //$file_name = md5(uniqid()). '.' . ws_ext($store_file);
            if(file_exists(UPL_DIR . DS . $destination . DS . 'large' . DS . $ws_name. '.' . ws_ext($store_file))){
                $file_name = $ws_name . '_' . uniqid() . '.' . ws_ext($store_file);
            } else {                      
                $file_name = $ws_name . '.' . ws_ext($store_file);
            }
        } else {
            $destination = UPL_FILES;
            $file_name = $ws_name . '.' . ws_ext($store_file);
        }
        
        if(strpos($_SERVER['HTTP_HOST'], 'wrk.') !== false || $encoded) $file = 'http://1.hidemyass.com/ip-1/encoded/'.base64_encode(str_replace('http', '', $file));
        
        if(!is_dir(UPL_DIR . DS . $destination)) mkdir(UPL_DIR . DS . $destination, 0777, true);
        
        if($image){
            if(!is_dir(UPL_DIR . DS . $destination . DS . 'large')) mkdir(UPL_DIR . DS . $destination . DS . 'large', 0777, true);
            if(!copy($file, UPL_DIR . DS . $destination . DS . 'large' . DS . $file_name)) return ___('Error moving file upload');

            if(in_array(ws_ext($file_name), ws_ext_img())){
                if(ws_ext($file_name) != 'gif') $this->resize(UPL_DIR . DS . $destination . DS . 'large' . DS . $file_name , '2000', '2000');
                if(!is_dir(UPL_DIR . DS . $destination . DS . 'thumb')) mkdir(UPL_DIR . DS . $destination . DS . 'thumb', 0777, true);
                copy(UPL_DIR . DS . $destination . DS . 'large' . DS . $file_name, UPL_DIR . DS . $destination . DS . 'thumb' . DS . $file_name);
                $this->resize(UPL_DIR . DS . $destination . DS . 'thumb' . DS . $file_name , '400', '400');
            }
        } else {
            if(!copy($file, UPL_DIR . DS . $destination . DS . $file_name)) return ___('Error moving file upload');
        }
        return array('file' => $file_name, 'path' => $destination);
    }

    public function delete($file, $image = true) {
        if($image){
            if(is_file(UPL_DIR . DS . UPL_IMAGES . DS . 'large' . DS . $file)) unlink(UPL_DIR . DS . UPL_IMAGES . DS . 'large' . DS . $file);
            if(is_file(UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . $file)) unlink(UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . $file);
        } else {
            if(is_file(UPL_DIR . DS . UPL_FILES . DS . $file)) unlink(UPL_DIR . DS . UPL_FILES . DS . $file);
        }
    }
    
    public function resize($src , $maxw, $maxh){
        $srcInfo = getimagesize($src);
        switch ($srcInfo[2]) {
            case 1: 
                $srcImg = imagecreatefromgif($src); 
                break;
            case 2: 
                $srcImg = imagecreatefromjpeg($src);  
                break;
            case 3: 
                $srcImg = imagecreatefrompng($src); 
                break;
            default: 
                $srcImg = false;  
                break;
        }
        if (!$srcImg) return false;
        
        $imageWidth  = $srcInfo[0];
        $imageHeight = $srcInfo[1];
        
        if($imageWidth <= $maxw && $imageHeight <= $maxh) return true;
        
        $wRatio = $imageWidth / $maxw;
        $hRatio = $imageHeight / $maxh;
        $maxRatio = max($wRatio, $hRatio);
        if ($maxRatio > 1) {
            $outputWidth = $imageWidth / $maxRatio;
            $outputHeight = $imageHeight / $maxRatio;
        } else {
            $outputWidth = $imageWidth;
            $outputHeight = $imageHeight;
        }

        $newImg = imagecreatetruecolor($outputWidth, $outputHeight);

        // ------------------------------------------------------------------------------------------------------------------------------
        if(($srcInfo[2] == 1) OR ($srcInfo[2]==3)){
            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
            imagefilledrectangle($newImg, 0, 0, $outputWidth, $outputHeight, imagecolorallocatealpha($newImg, 255, 255, 255, 127));
        }
        
        if($allocate_white) imagefilledrectangle($newImg, 0, 0, $outputWidth, $outputHeight, imagecolorallocate($newImg, 255, 255, 255));
        // ------------------------------------------------------------------------------------------------------------------------------
        
        imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $outputWidth, $outputHeight, $srcInfo[0], $srcInfo[1]);
        
        switch ($srcInfo[2]) {
            case 1: 
                $resized_img = imagegif($newImg, $src); 
                break;
            case 2: 
                $resized_img = imagejpeg($newImg, $src, 100);  
                break;
            case 3: 
                $resized_img = imagepng($newImg, $src, 0); 
                break;
            default: 
                $resized_img = false;  
                break;
        }
        
        imagedestroy($newImg);
        imagedestroy($srcImg);
        
        if($resized_img) return true;
        return false;
    }

    public function crop($file_name, $data, $is_thumb = false){
        
        $src = UPL_DIR . DS . UPL_IMAGES . DS . 'large' . DS . $file_name;
        
        $srcInfo = getimagesize($src);
        
        switch ($srcInfo[2]) {
            case 1: 
                $srcImg = imagecreatefromgif($src); 
                break;
            case 2: 
                $srcImg = imagecreatefromjpeg($src);  
                break;
            case 3: 
                $srcImg = imagecreatefrompng($src); 
                break;
            default: 
                $srcImg = false;  
                break;
        }
        if (!$srcImg) return false;
        
        $newImg = imagecreatetruecolor($data['w'], $data['h']);

        // ------------------------------------------------------------------------------------------------------------------------------
        if(($srcInfo[2] == 1) OR ($srcInfo[2]==3)){
            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
            imagefilledrectangle($newImg, 0, 0, $data['w'], $data['h'], imagecolorallocatealpha($newImg, 255, 255, 255, 127));
        }
        
        if($allocate_white) imagefilledrectangle($newImg, 0, 0, $data['w'], $data['h'], imagecolorallocate($newImg, 255, 255, 255));
        // ------------------------------------------------------------------------------------------------------------------------------

        imagecopy($newImg, $srcImg, 0, 0, $data['x1'], $data['y1'], $data['w'], $data['h']);
        //imagecopyresampled($newImg, $srcImg, $data['x1'], $data['y1'], 0, 0, $data['w'], $data['h'], $data['w'], $data['h']);

        if($is_thumb) $src = UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . $file_name;

        switch ($srcInfo[2]) {
            case 1: 
                $resized_img = imagegif($newImg, $src); 
                break;
            case 2: 
                $resized_img = imagejpeg($newImg, $src, 100);  
                break;
            case 3: 
                $resized_img = imagepng($newImg, $src, 0); 
                break;
            default: 
                $resized_img = false;  
                break;
        }
        
        imagedestroy($newImg);
        imagedestroy($srcImg);

        if($is_thumb){
            $this->resize(UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . $file_name , '400', '400');
        } else {
            copy(UPL_DIR . DS . UPL_IMAGES . DS . 'large' . DS . $file_name, UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . $file_name);
            $this->resize(UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . $file_name , '400', '400');
        }
        
        if($resized_img) return true;
        return false;
    }

}

?>
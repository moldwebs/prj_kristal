<?php
define('UPL_IMAGES', 'uploads' . DS . CMS_UID . DS . 'images');
define('UPL_FILES', 'uploads' . DS . CMS_UID . DS . 'files');

if(!is_dir(UPL_DIR . DS . UPL_IMAGES)) mkdir(UPL_DIR . DS . UPL_IMAGES, 0777, true);
if(!is_dir(UPL_DIR . DS . UPL_FILES)) mkdir(UPL_DIR . DS . UPL_FILES, 0777, true);

if(!is_writable(UPL_DIR . DS . UPL_IMAGES)) exit(UPL_DIR . DS . UPL_IMAGES . 'ATTACHMENTS IS NOT WRITABLE');

if(file_get_contents(LOGS . DS . 'host.log') != $_SERVER['HTTP_HOST']){
    //ws_rmdir(LOGS, true);
    ws_rmdir(CACHE, true);
    file_put_contents(LOGS . DS . 'host.log', $_SERVER['HTTP_HOST']);
}

$load_file = false;
if(is_file(UPL_DIR . DS . 'uploads' . DS . CMS_UID . DS . 'files' . DS . urldecode($_SERVER['REQUEST_URI']))) $load_file = UPL_DIR . DS . 'uploads' . DS . CMS_UID . DS . 'files' . DS . urldecode($_SERVER['REQUEST_URI']); else
if(!empty($_COOKIE['img_uid']) && is_file(UPL_DIR . DS . 'uploads' . DS . $_COOKIE['img_uid'] . DS . 'files' . DS . urldecode($_SERVER['REQUEST_URI']))) $load_file = UPL_DIR . DS . 'uploads' . DS . $_COOKIE['img_uid'] . DS . 'files' . DS . urldecode($_SERVER['REQUEST_URI']); else
if(CMS_UID > 0 && is_file(UPL_DIR . DS . 'uploads' . DS . '0' . DS . 'files' . DS . urldecode($_SERVER['REQUEST_URI']))) $load_file = UPL_DIR . DS . 'uploads' . DS . '0' . DS . 'files' . DS . urldecode($_SERVER['REQUEST_URI']);

if($load_file){
    if(substr(mime_content_type($load_file), 0, 5) == 'image'){
        header("Content-type:" . mime_content_type($load_file));
    } else {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($load_file).'"');
        header('Content-Length: ' . filesize($load_file));
    }
    readfile($load_file);
    exit;
}

App::uses('Security', 'Utility');

//mysql_query("SET time_zone = 'Europe/Chisinau';");
//$CmsAlias->query("SET time_zone = 'Europe/Chisinau';");

$data = $CmsAlias->query("SELECT id FROM wb_user WHERE `uid` = '".CMS_UID."' AND `password` IS NOT NULL LIMIT 1");
if(empty($data[0]['wb_user']['id'])){
    $CmsAlias->query("INSERT INTO wb_user(`uid`, `role`, `username`, `usermail`, `password`, `created`, `status`) VALUES('".CMS_UID."', 'admin', 'Administrator', 'admin@mail.com', '".Security::hash(Configure::read('Security.salt'), null, true)."', '".date("Y-m-d H:i:s")."', '1')");
    Cache::clear();
}
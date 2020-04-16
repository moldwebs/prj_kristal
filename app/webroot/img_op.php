<?php 
ini_set('display_errors', 0);
ini_set('memory_limit', '-1');
set_time_limit(0);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(dirname(__FILE__))));

define('CMS_UID', '0');

require_once ROOT . DS . 'config' . DS . 'defines.php';
require_once ROOT . DS . 'config' . DS . 'functions.php';

define('UPL_IMAGES', 'uploads' . DS . CMS_UID . DS . 'images');
define('UPL_FILES', 'uploads' . DS . CMS_UID . DS . 'files');

require_once ROOT . DS . 'web_config' . DS . 'database.php';
$db_con = new DATABASE_CONFIG();
$dbi = mysql_connect($db_con->default['host'], $db_con->default['login'], $db_con->default['password'], TRUE);
mysql_select_db($db_con->default['database'], $dbi);
if(isset($db_con->default['encoding']) && $db_con->default['encoding'] != '') mysql_query("SET NAMES '".$db_con->default['encoding']."'", $dbi);


if($_GET['op'] == 'tid'){
    foreach(scandir(UPL_DIR . DS . UPL_IMAGES . DS . 'large') as $file){
        if($file == '.' || $file == '..') continue;
    
        $r = mysql_query("SELECT wb_obj_opt_attachment.id, wb_obj_opt_attachment.model, wb_obj_item_list.tid FROM wb_obj_opt_attachment 
        INNER JOIN wb_obj_item_list ON (wb_obj_item_list.id = wb_obj_opt_attachment.foreign_key)
        WHERE wb_obj_opt_attachment.model = 'ObjItemList' AND wb_obj_opt_attachment.file = '".mysql_escape_string($file)."'", $dbi);
        if(mysql_num_rows($r) > 0){
            $row = mysql_fetch_assoc($r);
            
            if(substr($file, 0, strlen(md5($row['tid'].$row['model']) . '_')) == md5($row['tid'].$row['model']) . '_') continue;
            
            rename(UPL_DIR . DS . UPL_IMAGES . DS . 'large' . DS . $file, UPL_DIR . DS . UPL_IMAGES . DS . 'large' . DS . md5($row['tid'].$row['model']) . '_' . $file);
            rename(UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . $file, UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . md5($row['tid'].$row['model']) . '_' . $file);
            mysql_query("UPDATE wb_obj_opt_attachment SET file = '".mysql_escape_string(md5($row['tid'].$row['model']) . '_' . $file)."' WHERE id = '{$row['id']}'", $dbi);
            //print_r($row);
            //exit($file);
        }
    }
}

if($_GET['op'] == 'dupl'){
    $r = mysql_query("SELECT * FROM wb_obj_opt_attachment GROUP BY file", $dbi);
    while($row = mysql_fetch_assoc($r)){
        $_r = mysql_query("SELECT * FROM wb_obj_opt_attachment WHERE id != '{$row['id']}' AND file = '{$row['file']}'", $dbi);
        if(mysql_num_rows($_r) > 0){
            while($_row = mysql_fetch_assoc($_r)){
                
                $file = $_row['file'];
                
                $path_parts = pathinfo($file);
                $nfile = $path_parts['filename'] . '_' . uniqid() . '.' . $path_parts['extension'];
                
                //print_r($path_parts);
                //print_r($row);
                //print_r($_row);
                //print_r($file);
                //print_r("<hr>");
                //print_r($nfile);
                //exit;
                
                copy(UPL_DIR . DS . UPL_IMAGES . DS . 'large' . DS . $file, UPL_DIR . DS . UPL_IMAGES . DS . 'large' . DS . $nfile);
                copy(UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . $file, UPL_DIR . DS . UPL_IMAGES . DS . 'thumb' . DS . $nfile);
                mysql_query("UPDATE wb_obj_opt_attachment SET file = '{$nfile}' WHERE id = '{$_row['id']}'", $dbi);
                
            }
        }
    }
    
    

}

exit('ok');
?>
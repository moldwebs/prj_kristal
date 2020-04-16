<?php
ini_set('display_errors', 0);
ini_set('memory_limit', '-1');
set_time_limit(0);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(dirname(__FILE__))));

$_GET['get_image'] = str_replace('../', '', $_GET['get_image']);
$expl = explode('/', $_GET['get_image']);

if(count($expl) > 2 || ereg('[0-9x]', $expl[0])){
    $image = '/' . $expl[1] . '/' . $expl[2];
    list($w, $h, $mode) = explode('x', $expl[0]);
} else {
    $image = '/' . $expl[0] . '/' . $expl[1];
    $w = 0;
    $h = 0;
    $mode = 0;
}

$path_parts = pathinfo($image);

if(preg_match("/get_color_(.*?).png/", $path_parts['basename'], $match)){
    $im = imagecreatetruecolor($w, $h);
    list($r, $g, $b) = sscanf('#'.strtoupper($match[1]), "#%02x%02x%02x");
    $fill = imagecolorallocate($im, $r, $g, $b);

    imagefilledrectangle($im, 0, 0, $w, $h, $fill);

    header ("Content-type: image/png");

    imagepng($im, NULL, 0);
    imagedestroy($im);
    exit;
}

if(strpos($path_parts['filename'], '_') !== false){
    define('CMS_UID', strstr($path_parts['filename'], '_', true));
    if(substr($path_parts['filename'], 0, 2) == CMS_UID . '_') $path_parts['filename'] = substr($path_parts['filename'], 2);
    $image = str_replace('/' . CMS_UID . '_', '/', $image);
} else {
    define('CMS_UID', '0');
}

require_once ROOT . DS . 'config' . DS . 'defines.php';
require_once ROOT . DS . 'config' . DS . 'functions.php';

define('UPL_IMAGES', 'uploads' . DS . CMS_UID . DS . 'images');
define('UPL_FILES', 'uploads' . DS . CMS_UID . DS . 'files');

if($path_parts['extension'] == 'youtube') $path_parts['extension'] = 'video';

if($path_parts['extension'] == 'video'){
    if(substr_count($image, '/large/') > 0){
        $img = "http://img.youtube.com/vi/{$path_parts['filename']}/maxresdefault.jpg";
    } else {
        $img = "http://img.youtube.com/vi/{$path_parts['filename']}/mqdefault.jpg";
    }
} else {
    $img = UPL_DIR . DS . UPL_IMAGES . $image;
}



if(strpos($img, 'http://') !== false){

} else if(!is_file($img)){
    if(substr_count($image, '/large/') > 0 && is_file(EXT_TPL . DS . 'img' . DS . 'no_image_big.png')){
        $img = EXT_TPL . DS . 'img' . DS . 'no_image_big.png';
    } else if(is_file(EXT_TPL . DS . 'img' . DS . 'no_image_small.png')){
        $img = EXT_TPL . DS . 'img' . DS . 'no_image_small.png';
    } else {
        $img = ROOT . DS . 'app' . DS . 'webroot' . DS . 'img' . DS . 'no-picture.png';
    }
}

if(substr_count($image, '/large/') > 0 && substr_count($image, md5('catalogObjItemList')) > 0){
    if(is_file(UPL_DIR . DS . UPL_FILES . DS . 'watermark.png')) $logo_img = UPL_DIR . DS . UPL_FILES . DS . 'watermark.png';
    /*
    require_once CFG_ROOT . DS . 'database.php';
    $db_con = new DATABASE_CONFIG();
    $dbi = mysql_connect($db_con->default['host'], $db_con->default['login'], $db_con->default['password'], TRUE);
    mysql_select_db($db_con->default['database'], $dbi);
    if(isset($db_con->default['encoding']) && $db_con->default['encoding'] != '') mysql_query("SET NAMES '".$db_con->default['encoding']."'", $dbi);
    $path_parts = pathinfo($img);
    $r = mysql_query("SELECT `type` FROM wb_obj_opt_attachment WHERE file = '".mysql_escape_string($path_parts['basename'])."'", $dbi);
    $row = mysql_fetch_assoc($r);
    if(!empty($row['type'])) $logo_img = false;
    */
}

if(!$logo_img){
    if($w == 0 && $h == 0){
        $imgInfo = getimagesize($img);
        if($imgInfo[0] < 500 && $mode == '0'){
            $w = 500;
            $h = 500;
        } else {
            header ("Content-type: image/jpeg");
            exit(file_get_contents($img));
        }
    }
}

if(!in_array(ws_ext($img), ws_ext_img())){
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($img).'"');
    header('Content-Length: ' . filesize($img));
    readfile($img);
    exit;
}

if (!function_exists("imagecreatefrombmp")) {
    function imagecreatefrombmp( $filename ) {
        $file = fopen( $filename, "rb" );
        $read = fread( $file, 10 );
        while( !feof( $file ) && $read != "" )
        {
            $read .= fread( $file, 1024 );
        }
        $temp = unpack( "H*", $read );
        $hex = $temp[1];
        $header = substr( $hex, 0, 104 );
        $body = str_split( substr( $hex, 108 ), 6 );
        if( substr( $header, 0, 4 ) == "424d" )
        {
            $header = substr( $header, 4 );
            // Remove some stuff?
            $header = substr( $header, 32 );
            // Get the width
            $width = hexdec( substr( $header, 0, 2 ) );
            // Remove some stuff?
            $header = substr( $header, 8 );
            // Get the height
            $height = hexdec( substr( $header, 0, 2 ) );
            unset( $header );
        }
        $x = 0;
        $y = 1;
        $image = imagecreatetruecolor( $width, $height );
        foreach( $body as $rgb )
        {
            $r = hexdec( substr( $rgb, 4, 2 ) );
            $g = hexdec( substr( $rgb, 2, 2 ) );
            $b = hexdec( substr( $rgb, 0, 2 ) );
            $color = imagecolorallocate( $image, $r, $g, $b );
            imagesetpixel( $image, $x, $height-$y, $color );
            $x++;
            if( $x >= $width )
            {
                $x = 0;
                $y++;
            }
        }
        return $image;
    }
}

//$img = str_replace('/thumb/', '/large/', $img);

$imgInfo = getimagesize($img);
switch ($imgInfo[2]) {
    case 1: $im = imagecreatefromgif($img); break;
    case 2: $im = imagecreatefromjpeg($img);  break;
    case 3: $im = imagecreatefrompng($img); break;
    case 6: $im = imagecreatefrombmp($img); break;
    default: $im = false;  break;
}
if (!$im) return false;

if(1==2){
//find the size of the borders
$b_top = 0;
$b_btm = 0;
$b_lft = 0;
$b_rt = 0;

//top
for(; $b_top < $imgInfo[1]; ++$b_top) {
  for($x = 0; $x < $imgInfo[0]; ++$x) {
    $imagecolorat = imagecolorat($im, $x, $b_top);
    if($imagecolorat != 0xFFFFFF && $imagecolorat != 0xFEFEFE && $imagecolorat != 0xFDFDFD && $imagecolorat != 0xFCFCFC && $imagecolorat != 0xFBFBFB && $imagecolorat != 0xFAFAFA) {
       break 2; //out of the 'top' loop
    }
  }
}

//bottom
for(; $b_btm < $imgInfo[1]; ++$b_btm) {
  for($x = 0; $x < $imgInfo[0]; ++$x) {
    $imagecolorat = imagecolorat($im, $x, $imgInfo[1] - $b_btm-1);
    if($imagecolorat != 0xFFFFFF && $imagecolorat != 0xFEFEFE && $imagecolorat != 0xFDFDFD && $imagecolorat != 0xFCFCFC && $imagecolorat != 0xFBFBFB && $imagecolorat != 0xFAFAFA) {
       break 2; //out of the 'bottom' loop
    }
  }
}

//left
for(; $b_lft < $imgInfo[0]; ++$b_lft) {
  for($y = 0; $y < $imgInfo[1]; ++$y) {
    $imagecolorat = imagecolorat($im, $b_lft, $y);
    if($imagecolorat != 0xFFFFFF && $imagecolorat != 0xFEFEFE && $imagecolorat != 0xFDFDFD && $imagecolorat != 0xFCFCFC && $imagecolorat != 0xFBFBFB && $imagecolorat != 0xFAFAFA) {
       $b_lft_imagecolorat = $imagecolorat;
       break 2; //out of the 'left' loop
    }
  }
}

//right
for(; $b_rt < $imgInfo[0]; ++$b_rt) {
  for($y = 0; $y < $imgInfo[1]; ++$y) {
    $imagecolorat = imagecolorat($im, $imgInfo[0] - $b_rt-1, $y);
    if($imagecolorat != 0xFFFFFF && $imagecolorat != 0xFEFEFE && $imagecolorat != 0xFDFDFD && $imagecolorat != 0xFCFCFC && $imagecolorat != 0xFBFBFB && $imagecolorat != 0xFAFAFA) {
       break 2; //out of the 'right' loop
    }
  }
}
}
if(!empty($_GET['test'])){
    print_r(imagecolorsforindex($im, $b_lft_imagecolorat));
    print_r($imgInfo[0]);
    print_r($b_lft);
    exit;
}


//$b_lft = 0;
//$b_rt = 0;

$imgInfo[0] = $imgInfo[0]-($b_lft+$b_rt);
$imgInfo[1] = $imgInfo[1]-($b_top+$b_btm);

//-------------------------

$newX = $b_lft;
$newY = $b_top;

$posX = 0;
$posY = 0;

$w = ($w > 0 ? $w : $imgInfo[0]);
$h = ($h > 0 ? $h : $imgInfo[1]);

$nWidth = $w;
$nHeight = $h;

if($mode == '1'){
    $ratioX = $w / $imgInfo[0];
    $ratioY = $h / $imgInfo[1];

    if ($ratioX < $ratioY) {
        $newX = round(($imgInfo[0] - ($w / $ratioY))/2);
        $newY = 0;
        $imgInfo[0] = round($w / $ratioY);
        $imgInfo[1] = $imgInfo[1];
    } else {
        $newX = 0;
        $newY = round(($imgInfo[1] - ($h / $ratioX))/2);
        $imgInfo[0] = $imgInfo[0];
        $imgInfo[1] = round($h / $ratioX);
    }
} else if($mode == '2'){
    $hx = (100 / ($imgInfo[0] / $w)) * .01;
    $hx = @round ($imgInfo[1] * $hx);

    $wx = (100 / ($imgInfo[1] / $h)) * .01;
    $wx = @round ($imgInfo[0] * $wx);

    if ($hx < $h) {
        $nHeight = (100 / ($imgInfo[0] / $w)) * .01;
        $nHeight = @round ($imgInfo[1] * $nHeight);
    } else {
        $nWidth = (100 / ($imgInfo[1] / $h)) * .01;
        $nWidth = @round ($imgInfo[0] * $nWidth);
    }

    $w = $nWidth;
    $h = $nHeight;
} else {
    $hx = (100 / ($imgInfo[0] / $w)) * .01;
    $hx = @round ($imgInfo[1] * $hx);

    $wx = (100 / ($imgInfo[1] / $h)) * .01;
    $wx = @round ($imgInfo[0] * $wx);

    if ($hx < $h) {
        $nHeight = (100 / ($imgInfo[0] / $w)) * .01;
        $nHeight = @round ($imgInfo[1] * $nHeight);
        $posY = ($h - $nHeight)/2;
    } else {
        $nWidth = (100 / ($imgInfo[1] / $h)) * .01;
        $nWidth = @round ($imgInfo[0] * $nWidth);
        $posX = ($w - $nWidth)/2;
    }
    $allocate_white = true;
}




if($_GET['test'] == '1'){
    $newImg = imagecreatetruecolor($w, $h);

    //if($allocate_white) imagefilledrectangle($newImg, 0, 0, $w, $h, imagecolorallocate($newImg, 255, 255, 255));

    imagecopyresampled($newImg, $im, $posX, $posY, $newX, $newY, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
} else {
    $newImg = imagecreatetruecolor($w, $h);

    /* Check if this image is PNG or GIF, then set if Transparent*/
    if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
        if(!$logo_img) imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
        imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, imagecolorallocatealpha($newImg, 255, 255, 255, 127));
    }

    if($allocate_white) imagefilledrectangle($newImg, 0, 0, $w, $h, imagecolorallocate($newImg, 255, 255, 255));

    imagecopyresampled($newImg, $im, $posX, $posY, $newX, $newY, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
}




if($logo_img){

    $destWidth = imagesx($newImg);
    $destHeight = imagesy($newImg);

    $myCopyright = imagecreatefrompng($logo_img);

    $_srcWidth = imagesx($myCopyright);
    $_srcHeight = imagesy($myCopyright);

    $srcWidth = $_srcWidth * ($destWidth / 1000);
    $srcHeight = $_srcHeight * ($destWidth / 1000);

    $destX=$destWidth/2 - $srcWidth/2;
    $destY=$destHeight/2 - $srcHeight/2;

    //$destX=$destWidth - $srcWidth;
    //$destX=10;
    //$destY=$destHeight - $srcHeight - 5;

    $newCopyright = imagecreatetruecolor($srcWidth, $srcHeight);
    imagealphablending($newCopyright, false);
    imagesavealpha($newCopyright, true);
    imagefilledrectangle($newCopyright, 0, 0, $srcWidth, $srcHeight, imagecolorallocatealpha($newCopyright, 255, 255, 255, 127));
    imagecopyresampled($newCopyright, $myCopyright, 0, 0, 0, 0, $srcWidth, $srcHeight, $_srcWidth, $_srcHeight);

    imagecopy($newImg, $newCopyright, $destX, $destY, 0, 0, $srcWidth, $srcHeight);
}

header ("Content-type: image/jpeg");

//Generate the file, and rename it to $newfilename
switch ($imgInfo[2]) {
    case 1: $resized_img = imagegif($newImg, NULL); break;
    case 2: $resized_img = imagejpeg($newImg, NULL, 100);  break;
    case 3: $resized_img = imagepng($newImg, NULL, 0); break;
    case 6: $resized_img = imagepng($newImg, NULL, 0); break;
    default: $resized_img = false;  break;
}

imagedestroy($newImg);
imagedestroy($im);

?>

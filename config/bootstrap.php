<?php
require_once ROOT . DS . 'config' . DS . 'defines.php';
require_once CFG_ROOT . DS . 'functions.php';

App::import('Lib', 'CmsNav');

if(!empty($_GET['debug'])){
    Configure::write('debug', 2);
} else if(strpos($_SERVER['HTTP_HOST'], 'wrk.') !== false){
     Configure::write('debug', 0);
} else {
    Configure::write('debug', 0);
} 

//CakePlugin::load('DebugKit');

if(!empty($_GET['clear']) || Configure::read('debug') == '3') Cache::clearGroup('query');
//if(!empty($_GET['clearall'])) Cache::clear();


define('EXT_THEMES', str_replace('web_views', 'web_themes', EXT_VIEWS));

App::build(array('views' => array(EXT_THEMES . DS, EXT_VIEWS . DS), 'plugins' => array(EXT_PLUGINS . DS)));

require_once EXT_CFG_ROOT . DS . 'config.php';
require_once EXT_CFG_ROOT . DS . 'email.php';

foreach(App::objects('plugin') as $plugin){
    foreach(App::path('plugins') as $path){
        if(is_file($path . $plugin . DS . 'Config' . DS . 'config.php')){
            require_once $path . $plugin . DS . 'Config' . DS . 'config.php';
            break;
        }
    }
}

$layouts = array('' => 'Default');
foreach(scandir(EXT_VIEWS . DS . 'Layouts') as $file){
    if($file == '.' || $file == '..' || $file == 'default.ctp') continue;
    $layouts[strtolower(ws_name($file))] = ucfirst(ws_name($file));
}
Configure::write('CMS.layouts', $layouts);

$custom = array();
if(is_dir(EXT_VIEWS . DS . 'Templates' . DS . 'custom')) foreach(scandir(EXT_VIEWS . DS . 'Templates' . DS . 'custom') as $file){
    if($file == '.' || $file == '..') continue;
    $custom[strtolower(ws_name($file))] = (ws_name($file));
}
Configure::write('CMS.custom', $custom);

define('TMP_KEY', md5(Configure::read('Security.salt') . date("YmdH")));

Configure::write('EXEC_TIME_LOGS', array('BEGIN:' . date("Y-m-d H:i:s")));

Cache::config('short', array(
    'engine' => 'File',
    'duration' => '+30 minutes',
    //'duration' => '+1 day',
    'path' => CACHE,
    'prefix' => 'cake_short_',
    'groups' => array('hour')
));

Cache::config('long', array(
    'engine' => 'File',
    'duration' => '+30 minutes',
    //'duration' => '+1 day',
    'path' => CACHE,
    'prefix' => 'cake_day_',
    'groups' => array('day')
));

Cache::config('query', array(
    'engine' => 'File',
    'duration' => '+30 minutes',
    //'duration' => '+1 day',
    'path' => CACHE,
    'prefix' => 'cake_query_',
    'groups' => array('query')
));

Cache::config('req_act', array(
    'engine' => 'File',
    'duration' => '+30 minutes',
    //'duration' => '+1 day',
    'path' => CACHE,
    'prefix' => 'cake_req_act_',
    'groups' => array('req_act')
));

Cache::config('settings', array(
    'engine' => 'File',
    'duration' => '+30 minutes',
    //'duration' => '+1 day',
    'path' => CACHE,
    'prefix' => 'cake_settings_',
    'groups' => array('settings')
));

Cache::config('translates', array(
    'engine' => 'File',
    'duration' => '+30 minutes',
    //'duration' => '+1 day',
    'path' => CACHE,
    'prefix' => 'cake_translates_',
    'groups' => array('translates')
));

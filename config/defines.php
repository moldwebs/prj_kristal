<?php
define('CFG_ROOT', ROOT . DS . 'config');
define('EXT_CFG_ROOT', ROOT . DS . 'web_config');
define('EXT_TPL', ROOT . DS . 'web_views' . DS . 'template');
define('EXT_VIEWS', ROOT . DS . 'web_views' . DS . 'layout');
define('EXT_PLUGINS', ROOT . DS . 'web_plugins');

define('UPL_DIR', ROOT . DS . 'web_views' . DS . 'attachments');

if(defined(FULL_BASE_URL)) define('BASE_URL', str_replace('http' . (env('HTTPS') ? 's' : null) . '://', '', FULL_BASE_URL));

define('TREESYMB', '|&_nbsp;&_nbsp;&_nbsp;&_nbsp;');
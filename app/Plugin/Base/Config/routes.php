<?php
    $home_module = Configure::read('CMS.home_module');

    Router::connect('/:lang', (!empty($home_module) ? $home_module : array('plugin' => 'base', 'controller' => 'page', 'action' => 'index')), array('lang' => '[a-z]{3}'));
    Router::connect('/:lang/:plugin/:controller/:action/*', array(), array('lang' => '[a-z]{3}'));
    
    config_add('CMS.path_alias', array('cms_link' => array('ObjItemTree' => array('plugin' => 'base', 'controller' => 'page', 'action' => 'view'))));

    Router::connect('/system/sys.js', array('controller' => 'system', 'action' => 'js'));
	Router::connect('/', (!empty($home_module) ? $home_module : array('plugin' => 'base', 'controller' => 'page', 'action' => 'index')));
	Router::connect('/pages/*', array('plugin' => 'base', 'controller' => 'page', 'action' => 'view'));
	Router::connect('/:lang/pages/*', array('plugin' => 'base', 'controller' => 'page', 'action' => 'view'), array('lang' => '[a-z]{3}'));
	Router::connect('/search/:page', array('plugin' => 'base', 'controller' => 'search', 'action' => 'index'), array('lang' => '[a-z]{3}'));
	Router::connect('/search/*', array('plugin' => 'base', 'controller' => 'search', 'action' => 'index'));
	Router::connect('/:lang/search/:page', array('plugin' => 'base', 'controller' => 'search', 'action' => 'index'), array('lang' => '[a-z]{3}'));
	Router::connect('/:lang/search/*', array('plugin' => 'base', 'controller' => 'search', 'action' => 'index'), array('lang' => '[a-z]{3}'));
	Router::connect('/fullsearch/:page', array('plugin' => 'base', 'controller' => 'search', 'action' => 'full'), array('lang' => '[a-z]{3}'));
	Router::connect('/fullsearch/*', array('plugin' => 'base', 'controller' => 'search', 'action' => 'full'));
	Router::connect('/:lang/fullsearch/:page', array('plugin' => 'base', 'controller' => 'search', 'action' => 'full'), array('lang' => '[a-z]{3}'));
	Router::connect('/:lang/fullsearch/*', array('plugin' => 'base', 'controller' => 'search', 'action' => 'full'), array('lang' => '[a-z]{3}'));
    
    Router::connect('/admin/', array('admin' => true, 'plugin' => 'base', 'controller' => 'page', 'action' => 'index'));
    Router::connect('/admin', array('admin' => true, 'plugin' => 'base', 'controller' => 'page', 'action' => 'index'));

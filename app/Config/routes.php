<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
    App::uses('ClassRegistry', 'Utility');
    
    $CmsAlias = ClassRegistry::init('CmsAlias');

    if(is_file(EXT_PLUGINS . DS . 'Uid' . DS . 'Config' . DS . 'uid.php')){
        include (EXT_PLUGINS . DS . 'Uid' . DS . 'Config' . DS . 'uid.php');
    } else {
        define('CMS_UID', 0);
    }
    
    include (CFG_ROOT . DS . 'construct.php');
    
	CakePlugin::routes();
    
    $aliases = array();
    $_aliases = $CmsAlias->find('allc', array('conditions' => array('OR' => array('CmsAlias.model' => 'CmsSetting', 'CmsAlias.tid' => 'cms_link'))));
    foreach($_aliases as $_alias){
        $aliases[$_alias['CmsAlias']['tid']][$_alias['CmsAlias']['model']][$_alias['CmsAlias']['foreign_key']][$_alias['CmsAlias']['locale']] = $_alias['CmsAlias']['alias'];
    }
    Configure::write('CMS.aliases', $aliases);

    App::import('Lib', 'ModelRoute');
    Router::connect('/:lang', array(), array('routeClass' => 'ModelRoute', array('lang' => '[a-z]{3}')));
    
    Router::connect('/:lang/:plugin/:controller/*', array(), array('lang' => '[a-z]{3}'));
    
    Router::connect('/sitemap.xml', array('controller' => 'system', 'action' => 'sitemap'));
    
/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';

    Router::parseExtensions('rss');
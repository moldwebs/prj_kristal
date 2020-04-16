<?php
$tids = Configure::read('CMS.fill_tid');

if(!empty($tids)) foreach($tids as $tid => $data){
    config_add('CMS.path_alias', array($tid => array('ObjItemTree' => array('plugin' => 'fill', 'controller' => 'item', 'action' => 'index', 'tid' => $tid), 'ObjItemList' => array('plugin' => 'fill', 'controller' => 'item', 'action' => 'view', 'tid' => $tid), 'CmsSetting' => array('plugin' => 'fill', 'controller' => 'item', 'action' => 'index', 'tid' => $tid))));
    
    if($data['opts']['treecontent'] == '1') config_add('CMS.path_alias', array($tid . '_treecontent' => array('ObjItemTree' => array('plugin' => 'fill', 'controller' => 'treecontent', 'action' => 'view', 'tid' => $tid))));

    Router::connect("/{$tid}", array('plugin' => 'fill', 'controller' => 'item', 'tid' => $tid));
    Router::promote();
    Router::connect("/{$tid}/bases", array('plugin' => 'fill', 'controller' => 'base', 'tid' => $tid));
    Router::connect("/{$tid}/:page", array('plugin' => 'fill', 'controller' => 'item', 'tid' => $tid), array('query' => array('page' => '[0-9]{3}')));
    Router::connect("/{$tid}/:controller/:action/*", array('plugin' => 'fill', 'tid' => $tid));
    Router::connect("/{$tid}/:controller/*", array('plugin' => 'fill', 'tid' => $tid));
    Router::connect("/:lang/{$tid}/bases", array('plugin' => 'fill', 'controller' => 'base', 'tid' => $tid), array('lang' => '[a-z]{3}'));
    Router::connect("/:lang/{$tid}", array('plugin' => 'fill', 'controller' => 'item', 'tid' => $tid), array('lang' => '[a-z]{3}'));
    Router::connect("/:lang/{$tid}/:page", array('plugin' => 'fill', 'controller' => 'item', 'tid' => $tid), array('lang' => '[a-z]{3}', 'query' => array('page' => '[0-9]{3}')));
    Router::connect("/:lang/{$tid}/:controller/:action/*", array('plugin' => 'fill', 'tid' => $tid), array('lang' => '[a-z]{3}'));
    Router::connect("/:lang/{$tid}/:controller/*", array('plugin' => 'fill', 'tid' => $tid), array('lang' => '[a-z]{3}'));
    Router::connect("/admin/{$tid}/:controller/:action/*", array('admin' => true, 'plugin' => 'fill', 'tid' => $tid));
    Router::connect("/admin/{$tid}/:controller/*", array('admin' => true, 'plugin' => 'fill', 'tid' => $tid));
}


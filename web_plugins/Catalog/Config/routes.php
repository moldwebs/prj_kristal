<?php
    $plugin = 'catalog';
    config_add('CMS.path_alias', array($plugin => array(
        'ObjItemTree' => array('plugin' => $plugin, 'controller' => "item{$plugin}", 'action' => 'index'), 
        'ObjItemList' => array('plugin' => $plugin, 'controller' => "item{$plugin}", 'action' => 'view'), 
        'CmsSetting' => array('plugin' => $plugin, 'controller' => "item{$plugin}", 'action' => 'index')
    ),'manufacturer' => array(
        'ObjItemList' => array('plugin' => $plugin, 'controller' => "item{$plugin}", 'action' => 'manufacturer'), 
    ),'catalog_promo' => array(
        'ObjItemList' => array('plugin' => $plugin, 'controller' => "item{$plugin}", 'action' => 'promo'), 
    )
    ));


    Router::connect("/{$plugin}/base/:action/*", array('plugin' => $plugin, 'controller' => "base{$plugin}"));
    Router::connect("/{$plugin}/base/*", array('plugin' => $plugin, 'controller' => "base{$plugin}", 'action' => 'index'));
    Router::connect("/{$plugin}/item/:action/*", array('plugin' => $plugin, 'controller' => "item{$plugin}"));
    Router::connect("/{$plugin}/item/*", array('plugin' => $plugin, 'controller' => "item{$plugin}", 'action' => 'index'));
    Router::connect("/admin/{$plugin}/base/:action/*", array('admin' => true, 'plugin' => $plugin, 'controller' => "base{$plugin}"));
    Router::connect("/admin/{$plugin}/base/*", array('admin' => true, 'plugin' => $plugin, 'controller' => "base{$plugin}", 'action' => 'index'));
    Router::connect("/admin/{$plugin}/item/:action/*", array('admin' => true, 'plugin' => $plugin, 'controller' => "item{$plugin}"));
    Router::connect("/admin/{$plugin}/item/*", array('admin' => true, 'plugin' => $plugin, 'controller' => "item{$plugin}", 'action' => 'index'));

    Router::connect("/admin/{$plugin}/:controller/:action/*", array('admin' => true, 'plugin' => $plugin));
    Router::connect("/admin/{$plugin}/:controller/*", array('admin' => true, 'plugin' => $plugin));
    
    foreach(array('', '/:lang') as $before){
        Router::connect($before . "/{$plugin}/bases", array('plugin' => $plugin, 'controller' => "base{$plugin}"), array('lang' => '[a-z]{3}'));
        Router::connect($before . "/{$plugin}/compare/*", array('plugin' => $plugin, 'controller' => "item{$plugin}", 'action' => 'compare'), array('lang' => '[a-z]{3}'));
        Router::connect($before . "/{$plugin}/wishlist/*", array('plugin' => $plugin, 'controller' => "item{$plugin}", 'action' => 'wishlist'), array('lang' => '[a-z]{3}'));
        Router::connect($before . "/{$plugin}/item/:action/*", array('plugin' => $plugin, 'controller' => "item{$plugin}"), array('lang' => '[a-z]{3}'));
        Router::connect($before . "/{$plugin}/:page", array('plugin' => $plugin, 'controller' => "item{$plugin}"), array('query' => array('page' => '[0-9]{3}', 'lang' => '[a-z]{3}')));
        Router::connect($before . "/{$plugin}/:controller/:action/*", array('plugin' => $plugin), array('lang' => '[a-z]{3}'));
        Router::connect($before . "/{$plugin}/:controller/*", array('plugin' => $plugin), array('lang' => '[a-z]{3}'));
        Router::connect($before . "/{$plugin}", array('plugin' => $plugin, 'controller' => "item{$plugin}"), array('lang' => '[a-z]{3}'));
    }

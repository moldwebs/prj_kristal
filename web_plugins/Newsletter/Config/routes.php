<?php
    $plugin = 'newsletter';

    foreach(array('', '/:lang') as $before){
        Router::connect($before . "/{$plugin}/:action", array('plugin' => $plugin, 'controller' => $plugin), array('lang' => '[a-z]{3}'));
        Router::connect($before . "/{$plugin}/:action/*", array('plugin' => $plugin, 'controller' => $plugin), array('lang' => '[a-z]{3}'));
        Router::connect($before . "/{$plugin}/:controller/:action/*", array('plugin' => $plugin), array('lang' => '[a-z]{3}'));
        Router::connect($before . "/{$plugin}/:controller/*", array('plugin' => $plugin), array('lang' => '[a-z]{3}'));
        Router::connect($before . "/{$plugin}", array('plugin' => $plugin, 'controller' => $plugin), array('lang' => '[a-z]{3}'));
    }

    Router::promote();

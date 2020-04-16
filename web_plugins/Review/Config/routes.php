<?php
    Configure::write('PLUGIN.Review', '1');
    
    Router::connect("/admin/:tid/rating/*", array('admin' => true, 'plugin' => 'review', 'controller' => 'rating', 'tid' => $tid));
    Router::promote();
    Router::connect("/admin/:tid/rating/:action/*", array('admin' => true, 'plugin' => 'review', 'controller' => 'rating', 'tid' => $tid));
    Router::promote();
    Router::connect("/admin/:tid/review/*", array('admin' => true, 'plugin' => 'review', 'tid' => $tid));
    Router::promote();
    Router::connect("/admin/:tid/review/:action/*", array('admin' => true, 'plugin' => 'review', 'tid' => $tid));
    Router::promote();
    Router::connect("/:tid/review/*", array('plugin' => 'review', 'tid' => $tid));
    Router::promote();
    Router::connect("/:tid/review/:action/*", array('plugin' => 'review', 'tid' => $tid));
    Router::promote();

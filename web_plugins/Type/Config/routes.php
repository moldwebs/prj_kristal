<?php
    Configure::write('PLUGIN.Type', '1');
    
    Router::connect("/admin/:tid/type/*", array('admin' => true, 'plugin' => 'type', 'tid' => $tid));
    Router::promote();
    Router::connect("/admin/:tid/type/:action/*", array('admin' => true, 'plugin' => 'type', 'tid' => $tid));
    Router::promote();
    Router::connect("/:tid/type/*", array('plugin' => 'type', 'tid' => $tid));
    Router::promote();
    Router::connect("/:tid/type/:action/*", array('plugin' => 'type', 'tid' => $tid));
    Router::promote();

<?php
    Configure::write('PLUGIN.Specification', '1');
    
    Router::connect("/admin/:tid/specification/*", array('admin' => true, 'plugin' => 'specification', 'tid' => $tid));
    Router::promote();
    Router::connect("/admin/:tid/specification/:action/*", array('admin' => true, 'plugin' => 'specification', 'tid' => $tid));
    Router::promote();
    Router::connect("/:tid/specification/*", array('plugin' => 'specification', 'tid' => $tid));
    Router::promote();
    Router::connect("/:tid/specification/:action/*", array('plugin' => 'specification', 'tid' => $tid));
    Router::promote();

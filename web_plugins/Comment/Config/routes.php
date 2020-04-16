<?php
    Configure::write('PLUGIN.Comment', '1');
    
    Router::connect("/admin/:tid/comment/*", array('admin' => true, 'plugin' => 'comment', 'tid' => $tid));
    Router::promote();
    Router::connect("/admin/:tid/comment/:action/*", array('admin' => true, 'plugin' => 'comment', 'tid' => $tid));
    Router::promote();
    Router::connect("/:tid/comment/*", array('plugin' => 'comment', 'tid' => $tid));
    Router::promote();
    Router::connect("/:tid/comment/:action/*", array('plugin' => 'comment', 'tid' => $tid));
    Router::promote();

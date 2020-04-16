<?php
Router::connect("/users/messages/*", array('plugin' => 'users', 'controller' => 'messages'));
Router::connect("/users/:action/*", array('plugin' => 'users', 'controller' => 'users'));
Router::connect('/:lang/users/:action/*', array('plugin' => 'users', 'controller' => 'users'), array('lang' => '[a-z]{3}'));
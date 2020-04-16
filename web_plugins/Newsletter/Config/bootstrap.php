<?php

config_add('CMS.mod_links', array('mod_newsletter' => ___('Newsletters')));

CmsNav::add('users.children.newsletter', array(
	'title' => ___('Newsletters'),
	'url' => array(
		'plugin' => 'newsletter',
		'admin' => true,
		'controller' => 'newsletter',
		'action' => 'index',
	),
	'weight' => 35,
    'buttons' => array(
        'list' => array('title' => ___('Newsletters') . ' :: ' . ___('List'), 'url' => array('plugin' => 'newsletter', 'admin' => true, 'controller' => 'newsletter', 'action' => 'index')),
        'export' => array('title' => ___('Newsletters') . ' :: ' . ___('Export'), 'url' => array('plugin' => 'newsletter', 'admin' => true, 'controller' => 'newsletter', 'action' => 'export')),
    ),	
));

<?php
config_add('CMS.mod_blocks', array('mod_users' => ___('Users')));
config_add('CMS.mod_links', array('mod_users' => ___('Users')));

App::import('Model', 'Users.UserRole');
$UserRole = new UserRole();
Configure::write('CMS.user_types', amc(___array(Configure::read('CMS.user_types')), $UserRole->find('list')));

CmsNav::add('users', array(
	'title' => ___('Users'),
	'url' => array(
		'plugin' => 'users',
		'admin' => true,
		'controller' => 'users',
		'action' => 'index',
	),
	'weight' => 1000,
	'children' => array(
		'users' => array(
			'title' => ___('Users'),
			'url' => array(
				'plugin' => 'users',
				'admin' => true,
				'controller' => 'users',
				'action' => 'index',
			),
			'weight' => 10,
            'buttons' => array(
                'list' => array('title' => ___('Users') . ' :: ' . ___('List'), 'url' => array('plugin' => 'users', 'admin' => true, 'controller' => 'users', 'action' => 'index')),
                'create' => array('title' => ___('Users') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'users', 'admin' => true, 'controller' => 'users', 'action' => 'edit')),
                'export' => array('title' => ___('Users') . ' :: ' . ___('Export'), 'url' => array('plugin' => 'users', 'admin' => true, 'controller' => 'users', 'action' => 'export')),
            ),		
        ),
		'roles' => array(
			'title' => ___('Roles'),
			'url' => array(
				'plugin' => 'users',
				'admin' => true,
				'controller' => 'roles',
				'action' => 'index',
			),
			'weight' => 20,
            'buttons' => array(
                'list' => array('title' => ___('Roles') . ' :: ' . ___('List'), 'url' => array('plugin' => 'users', 'admin' => true, 'controller' => 'roles', 'action' => 'index')),
                'create' => array('title' => ___('Roles') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'users', 'admin' => true, 'controller' => 'roles', 'action' => 'edit')),
                'permissions' => array('title' => ___('Roles') . ' :: ' . ___('Permissions'), 'url' => array('plugin' => 'users', 'admin' => true, 'controller' => 'roles', 'action' => 'permissions')),
            ),		
		),
		'actions' => array(
			'title' => ___('Actions'),
			'url' => array(
				'plugin' => 'users',
				'admin' => true,
				'controller' => 'actions',
				'action' => 'index',
			),
            'weight' => 30,
		),
		'settings' => array(
			'title' => ___('Settings'),
			'url' => array(
				'plugin' => 'users',
				'admin' => true,
				'controller' => 'system',
				'action' => 'settings',
			),
            'weight' => 40,
		),
	),
));
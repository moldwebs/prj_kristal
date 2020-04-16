<?php
    CmsNav::add('catalog.children.vendor', array(
			'title' => ___('Vendors'),
			'url' => array(
				'plugin' => 'vendor',
				'admin' => true,
				'controller' => 'vendor',
				'action' => 'index'
			),
            'weight' => 25,
            'buttons' => array(
                'list' => array('title' => ___('Vendors') . ' :: ' . ___('List'), 'url' => array('plugin' => 'vendor', 'admin' => true, 'controller' => 'vendor', 'action' => 'index')),
                'create' => array('title' => ___('Vendors') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'vendor', 'admin' => true, 'controller' => 'vendor', 'action' => 'edit')),
                'update' => array('title' => ___('Products') . ' :: ' . ___('Price') . ' / ' . ___('Status'), 'class' => 'long_action_redirect', 'url' => array('plugin' => 'vendor', 'admin' => true, 'controller' => 'vendor', 'action' => 'update')),
                'logs' => array('title' => ___('Logs') . ' :: ' . ___('Price') . ' / ' . ___('Status'), 'url' => '/admin/vendor/vendor/update?getlog=1'),
            ),
    ));
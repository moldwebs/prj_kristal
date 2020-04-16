<?php
$tid = 'shop';
$title = ___('Shop');

config_add('CMS.mod_blocks', array('mod_' . $tid => $title));
config_add('CMS.mod_links', array('mod_' . $tid => $title));

$add_menu = array(
	'title' => $title,
	'url' => array(
		'plugin' => $tid,
		'admin' => true,
		'controller' => 'order',
		'action' => 'index',
	),
	'weight' => 50,
	'children' => array(
		'order' => array(
			'title' => ___('Orders'),
			'url' => array(
				'plugin' => $tid,
				'admin' => true,
				'controller' => 'order',
				'action' => 'index',
			),
            'weight' => 20,
            'buttons' => array(
                'list' => array('title' => ___('Orders') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'order', 'action' => 'index')),
                'create' => array('title' => ___('Orders') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'order', 'action' => 'edit')),
            ),
		),
		'shipping' => array(
        	'title' => ___('Shipping'),
        	'url' => array(
        		'plugin' => $tid,
        		'admin' => true,
        		'controller' => 'shipping',
        		'action' => 'index',
        	),
            'weight' => 22,
            'buttons' => array(
                'list' => array('title' => ___('Shipping') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'shipping', 'action' => 'index')),
                'create' => array('title' => ___('Shipping') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'shipping', 'action' => 'edit')),
                'settings' => array('title' => ___('Shipping') . ' :: ' . ___('Settings'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'shipping', 'action' => 'settings')),
            ),
		),
		'zone' => array(
        	'title' => ___('Shipping Zone'),
        	'url' => array(
        		'plugin' => $tid,
        		'admin' => true,
        		'controller' => 'zone',
        		'action' => 'index',
        	),
            'weight' => 22,
            'buttons' => array(
                'list' => array('title' => ___('Shipping Zone') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'zone', 'action' => 'index')),
                'create' => array('title' => ___('Shipping Zone') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'zone', 'action' => 'edit')),
            ),
		),
		'customer' => array(
        	'title' => ___('Customers'),
        	'url' => array(
        		'plugin' => $tid,
        		'admin' => true,
        		'controller' => 'customer',
        		'action' => 'customers',
        	),
            'weight' => 25,
            'buttons' => array(
                'customers' => array('title' => ___('Customers') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'customer', 'action' => 'customers')),
                'list' => array('title' => ___('Customer Group') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'customer', 'action' => 'index')),
                'create' => array('title' => ___('Customer Group') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'customer', 'action' => 'edit')),
            ),
		),
		'discount' => array(
			'title' => ___('Discount'),
			'url' => array(
				'plugin' => $tid,
				'admin' => true,
				'controller' => 'discount',
				'action' => 'index_1',
			),
            'weight' => 30,
            'buttons' => array(
                'index_1' => array('title' => ___('Discount coupons'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'discount', 'action' => 'index_1')),
                //'index_2' => array('title' => ___('Discount on the amount of the order'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'discount', 'action' => 'index_2')),
                //'index_3' => array('title' => ___('Cumulative discounts'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'discount', 'action' => 'index_3')),
            ),
		),
		'statistics' => array(
			'title' => ___('Statistics'),
			'url' => array(
				'plugin' => $tid,
				'admin' => true,
				'controller' => 'order',
				'action' => 'statistics',
			),
            'weight' => 100,
		),
		'settings' => array(
			'title' => ___('Settings'),
			'url' => array(
				'plugin' => $tid,
				'admin' => true,
				'controller' => 'system',
				'action' => 'settings',
			),
            'weight' => 100,
		),
	),
);

CmsNav::add($tid, $add_menu);
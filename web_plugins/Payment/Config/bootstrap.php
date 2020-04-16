<?php
$add_menu = array(
	'title' => ___('Payment'),
	'url' => array(
		'plugin' => 'payment',
		'admin' => true,
		'controller' => 'payment',
		'action' => 'index',
	),
    'weight' => 36,
    'buttons' => array(
        'list' => array('title' => ___('Payment') . ' :: ' . ___('List'), 'url' => array('plugin' => 'payment', 'admin' => true, 'controller' => 'payment', 'action' => 'index')),
        'create' => array('title' => ___('Payment') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'payment', 'admin' => true, 'controller' => 'payment', 'action' => 'edit')),
        'transactions' => array('title' => ___('Payment') . ' :: ' . ___('Transactions'), 'url' => array('plugin' => 'payment', 'admin' => true, 'controller' => 'payment', 'action' => 'transactions')),
    ),
);

CmsNav::add('base.children.payment', $add_menu);
<?php
    config_add('CMS.mod_blocks', array('mod_currency' => ___('Currency')));

    CmsNav::add('base.children.currency', array(
        	'title' => ___('Currency'),
        	'url' => array(
        		'plugin' => 'currency',
        		'admin' => true,
        		'controller' => 'currency',
        		'action' => 'index',
        	),
            'weight' => 35,
            'buttons' => array(
                'list' => array('title' => ___('Currency') . ' :: ' . ___('List'), 'url' => array('plugin' => 'currency', 'admin' => true, 'controller' => 'currency', 'action' => 'index')),
                'create' => array('title' => ___('Currency') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'currency', 'admin' => true, 'controller' => 'currency', 'action' => 'edit')),
            ),
    ));
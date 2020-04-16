<?php

    CmsNav::add('shop.children.bonuses', array(
			'title' => ___('Bonuses'),
			'url' => array(
				'plugin' => 'bonuses',
				'admin' => true,
				'controller' => 'bonuses',
				'action' => 'customers'
			),
            'weight' => 90,
            'buttons' => array(
                'customers' => array('title' => ___('Customers'), 'url' => array('plugin' => 'bonuses', 'admin' => true, 'controller' => 'bonuses', 'action' => 'customers')),
                'bases' => array('title' => ___('Categories'), 'url' => array('plugin' => 'bonuses', 'admin' => true, 'controller' => 'bonuses', 'action' => 'bases')),
                'items' => array('title' => ___('Items'), 'url' => array('plugin' => 'bonuses', 'admin' => true, 'controller' => 'bonuses', 'action' => 'items')),
            ),
    ));

<?php
    config_add('CMS.mod_blocks', array('mod_promo' => ___('Promo')));

    CmsNav::add('base.children.promo', array(
			'title' => ___('Promo'),
			'url' => array(
				'plugin' => 'promo',
				'admin' => true,
				'controller' => 'promo_item',
				'action' => 'index'
			),
            'weight' => 25,
            'buttons' => array(
                'list' => array('title' => ___('Promo') . ' :: ' . ___('List'), 'url' => array('plugin' => 'promo', 'admin' => true, 'controller' => 'promo_item', 'action' => 'index')),
                'create_panel' => array('title' => ___('Promo') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'promo', 'admin' => true, 'controller' => 'promo_item', 'action' => 'edit_promo')),
                'create' => array('title' => ___('Item') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'promo', 'admin' => true, 'controller' => 'promo_item', 'action' => 'edit')),
            ),
    ));
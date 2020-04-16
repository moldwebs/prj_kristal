<?php
    config_add('CMS.mod_blocks', array('mod_slider' => ___('Slider')));

    CmsNav::add('base.children.slider', array(
			'title' => ___('Slider'),
			'url' => array(
				'plugin' => 'slider',
				'admin' => true,
				'controller' => 'slider_item',
				'action' => 'index'
			),
            'weight' => 25,
            'buttons' => array(
                'list' => array('title' => ___('Slider') . ' :: ' . ___('List'), 'url' => array('plugin' => 'slider', 'admin' => true, 'controller' => 'slider_item', 'action' => 'index')),
                'create_panel' => array('title' => ___('Slider') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'slider', 'admin' => true, 'controller' => 'slider_item', 'action' => 'edit_slider')),
                'create' => array('title' => ___('Item') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'slider', 'admin' => true, 'controller' => 'slider_item', 'action' => 'edit')),
            ),
    ));
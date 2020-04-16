<?php
$tids = Configure::read('CMS.fill_tid');

if(!empty($tids)) foreach($tids as $tid => $data){

    $title = ___($data['title']);

    config_add('CMS.mod_blocks', array('mod_' . $tid => $title));
    config_add('CMS.mod_links', array('mod_' . $tid => $title));
    config_add('CMS.mod_linkset', array('mod_' . $tid => $title));

    $add_menu = array(
    	'title' => $title,
    	'url' => array(
    		'plugin' => $tid,
    		'admin' => true,
    		'controller' => 'item',
    		'action' => 'index',
    	),
    	'weight' => 40,
    	'children' => array(
    		'item' => array(
    			'title' => ___('Items'),
    			'url' => array(
    				'plugin' => $tid,
    				'admin' => true,
    				'controller' => 'item',
    				'action' => 'index',
    			),
                'weight' => 20,
                'buttons' => array(
                    'list' => array('title' => ___('Items') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'item', 'action' => 'index')),
                    'create' => array('title' => ___('Items') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'item', 'action' => 'edit')),
                ),
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
    
    if($data['opts']['bases'] == '1'){
        $add_menu['children']['base'] = array(
			'title' => ___('Categories'),
			'url' => array(
				'plugin' => $tid,
				'admin' => true,
				'controller' => 'base',
				'action' => 'index',
			),
            'weight' => 10,
            'buttons' => array(
                'list' => array('title' => ___('Categories') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'base', 'action' => 'index')),
                'create' => array('title' => ___('Categories') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'base', 'action' => 'edit')),
            ),
		);
    }

    if($data['opts']['comments'] == '1' && Configure::read('PLUGIN.Comment') == '1'){
        $add_menu['children']['comments'] = array(
			'title' => ___('Comments'),
			'url' => array(
				'plugin' => $tid,
				'admin' => true,
				'controller' => 'comment',
				'action' => 'index'
			),
            'weight' => 80,
		);
    }

    if($data['opts']['types'] == '1' && Configure::read('PLUGIN.Type') == '1'){
        $add_menu['children']['types'] = array(
			'title' => ___('Types'),
			'url' => array(
				'plugin' => $tid,
				'admin' => true,
				'controller' => 'type',
				'action' => 'index',
			),
            'weight' => 90,
            'buttons' => array(
                'list' => array('title' => ___('Types') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'type', 'action' => 'index')),
                'create' => array('title' => ___('Types') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'type', 'action' => 'edit')),
            ),
		);
    }

    CmsNav::add($tid, $add_menu);
}


<?php
$tid = 'catalog';
$title = ___('Catalog');
$opts = Configure::read('CMS.sys_tid.'.$tid.'.opts');

config_add('CMS.mod_blocks', array('mod_' . $tid => $title));
config_add('CMS.mod_links', array('mod_' . $tid => $title));
config_add('CMS.mod_linkset', array('mod_' . $tid => $title));

Configure::write('CMS.catalog.mod_order_types', 
    am(array(
        'ObjItemList.price:desc' => ___('By price') . ' (' . ___('Descending') . ')',
        'ObjItemList.price:asc' => ___('By price') . ' (' . ___('Ascending') . ')',
    ), Configure::read('CMS.base.mod_order_types'))
);

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
			'title' => ___('Products'),
			'url' => array(
				'plugin' => $tid,
				'admin' => true,
				'controller' => 'item',
				'action' => 'index',
			),
            'weight' => 20,
            'buttons' => array(
                'list' => array('title' => ___('Products') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'item', 'action' => 'index')),
                'create' => array('title' => ___('Products') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'item', 'action' => 'edit')),
                'import' => array('title' => ___('Products') . ' :: ' . ___('Import'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'item', 'action' => 'import')),
                'impexpdata' => array('title' => ___('Data') . ' :: ' . ___('Export') . ' / ' . ___('Import'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'item', 'action' => 'impexpdata')),
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

if(!empty($opts['bases'])) $add_menu['children']['base'] = array(
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

if(!empty($opts['manufacturers'])) $add_menu['children']['manufacturer'] = array(
	'title' => ___('Manufacturers'),
	'url' => array(
		'plugin' => $tid,
		'admin' => true,
		'controller' => 'manufacturer',
		'action' => 'index',
	),
    'weight' => 15,
    'buttons' => array(
        'list' => array('title' => ___('Manufacturers') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'manufacturer', 'action' => 'index')),
        'create' => array('title' => ___('Manufacturers') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'manufacturer', 'action' => 'edit')),
    ),
);

if(!empty($opts['promo'])) $add_menu['children']['promo'] = array(
	'title' => ___('Promo'),
	'url' => array(
		'plugin' => $tid,
		'admin' => true,
		'controller' => 'promo',
		'action' => 'index',
	),
    'weight' => 15,
    'buttons' => array(
        'list' => array('title' => ___('Promo') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'promo', 'action' => 'index')),
        'create' => array('title' => ___('Promo') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'promo', 'action' => 'edit')),
    ),
);

/*
$add_menu['children']['deposit'] = array(
	'title' => ___('Deposits'),
	'url' => array(
		'plugin' => $tid,
		'admin' => true,
		'controller' => 'deposit',
		'action' => 'index',
	),
    'weight' => 16,
    'buttons' => array(
        'list' => array('title' => ___('Deposits') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'deposit', 'action' => 'index')),
        'create' => array('title' => ___('Deposits') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'deposit', 'action' => 'edit')),
    ),
);
*/

if(Configure::read('PLUGIN.Review') == '1' && !empty($opts['review'])) $add_menu['children']['review'] = array(
	'title' => ___('Reviews'),
	'url' => array(
		'plugin' => $tid,
		'admin' => true,
		'controller' => 'review',
		'action' => 'index'
	),
    'weight' => 50,
    'buttons' => array(
        'list' => array('title' => ___('Reviews') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'review', 'action' => 'index')),
        //'create' => array('title' => ___('Reviews') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'review', 'action' => 'edit')),
        'rating' => array('title' => ___('Rating') . ' :: ' . ___('Types'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'rating', 'action' => 'index')),
    ),
);

if(Configure::read('PLUGIN.Comment') == '1' && !empty($opts['comments'])) $add_menu['children']['comments'] = array(
	'title' => ___('Comments'),
	'url' => array(
		'plugin' => $tid,
		'admin' => true,
		'controller' => 'comment',
		'action' => 'index'
	),
    'weight' => 50,
);

if(Configure::read('PLUGIN.Type') == '1' && !empty($opts['types'])) $add_menu['children']['types'] = array(
	'title' => ___('Types'),
	'url' => array(
		'plugin' => $tid,
		'admin' => true,
		'controller' => 'type',
		'action' => 'index',
	),
    'weight' => 60,
    'buttons' => array(
        'list' => array('title' => ___('Types') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'type', 'action' => 'index')),
        'create' => array('title' => ___('Types') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'type', 'action' => 'edit')),
    ),
);

if(Configure::read('PLUGIN.Specification') == '1' && !empty($opts['specifications'])) $add_menu['children']['specification'] = array(
	'title' => ___('Specifications'),
	'url' => array(
		'plugin' => $tid,
		'admin' => true,
		'controller' => 'specification',
		'action' => 'index',
	),
    'weight' => 45,
    'buttons' => array(
        'list' => array('title' => ___('Specifications') . ' :: ' . ___('List'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'specification', 'action' => 'index')),
        'section' => array('title' => ___('Section') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'specification', 'action' => 'edit_section')),
        'create' => array('title' => ___('Specifications') . ' :: ' . ___('Create'), 'url' => array('plugin' => $tid, 'admin' => true, 'controller' => 'specification', 'action' => 'edit')),
    ),
);

CmsNav::add($tid, $add_menu);
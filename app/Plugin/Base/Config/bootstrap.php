<?php
config_add('CMS.mod_blocks', array('html' => ___('Html'), 'html_block' => ___('Html Block'), 'text' => ___('Text'), 'script' => ___('Code'), 'custom' => ___('Custom'), 'mod_base' => ___('System')));
config_add('CMS.mod_links', array('url' => ___('Url'), 'mod_base' => ___('System')));
config_add('CMS.mod_linkset', array('mod_base' => ___('System')));

Configure::write('CMS.base.mod_order_types', 
    array(
    'ObjItemList.created:desc' => ___('By date') . ' (' . ___('Descending') . ')',
    'ObjItemList.created:asc' => ___('By date') . ' (' . ___('Ascending') . ')',
    'ObjItemList.title:desc' => ___('By title') . ' (' . ___('Descending') . ')', 
    'ObjItemList.title:asc' => ___('By title') . ' (' . ___('Ascending') . ')',
    'ObjItemList.views:desc' => ___('By views') . ' (' . ___('Descending') . ')',
    'ObjItemList.views:asc' => ___('By views') . ' (' . ___('Ascending') . ')',
    'ObjItemList.comment_count:desc' => ___('By comments') . ' (' . ___('Descending') . ')',
    'ObjItemList.comment_count:asc' => ___('By comments') . ' (' . ___('Ascending') . ')',
    'RAND()' => ___('Random'),
    )
);

CmsNav::add('base', array(
	'title' => ___('Administration'),
	'url' => '/admin/?tm=0',
	'weight' => 10,
	'children' => array(
		'pages' => array(
			'title' => ___('Pages'),
			'url' => array(
				'plugin' => 'base',
				'admin' => true,
				'controller' => 'link',
				'action' => 'index',
			),
            'weight' => 10,
            'buttons' => array(
                'list' => array('title' => ___('Pages') . ' :: ' . ___('List'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'link', 'action' => 'index')),
                'create_menu' => array('title' => ___('Menu') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'link', 'action' => 'edit_menu')),
                'create_page' => array('title' => ___('Pages') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'link', 'action' => 'edit_page')),
                'create' => array('title' => ___('Links') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'link', 'action' => 'edit')),
                'create_list' => array('title' => ___('List') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'link', 'action' => 'edit_list')),
                'create_set' => array('title' => ___('Set') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'link', 'action' => 'edit_set')),
            ),
		),
		'blocks' => array(
			'title' => ___('Blocks'),
			'url' => array(
				'plugin' => 'base',
				'admin' => true,
				'controller' => 'block',
				'action' => 'index',
			),
            'weight' => 20,
            'buttons' => array(
                'list' => array('title' => ___('Blocks') . ' :: ' . ___('List'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'block', 'action' => 'index')),
                'widgets_list' => array('title' => ___('Widgets') . ' :: ' . ___('List'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'widget', 'action' => 'index')),
                'create_panel' => array('title' => ___('Panels') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'block', 'action' => 'edit_panel')),
                'create' => array('title' => ___('Blocks') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'block', 'action' => 'edit')),
                'create_group' => array('title' => ___('Group') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'block', 'action' => 'edit_group')),
            ),
		),
		'files' => array(
			'title' => ___('Files'),
			'url' => array(
				'plugin' => 'base',
				'admin' => true,
				'controller' => 'file',
				'action' => 'index',
			),
            'weight' => 30,
            'buttons' => array(
                'list' => array('title' => ___('Files') . ' :: ' . ___('List'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'file', 'action' => 'index')),
                'create' => array('title' => ___('Files') . ' :: ' . ___('Create'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'file', 'action' => 'edit')),
                'template' => array('title' => ___('Files') . ' :: ' . ___('Template'), 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'file', 'action' => 'template')),
            ),
		),
		'settings' => array(
			'title' => ___('Settings'),
			'url' => array(
				'plugin' => 'base',
				'admin' => true,
				'controller' => 'system',
				'action' => 'settings',
			),
            'weight' => 40,
		),
	),
));

$active_languages = Configure::read('CMS.activelanguages');
if(count($active_languages) > 1 || 1==1){
    $buttons = array();
    foreach($active_languages as $_lang => $lang){
        $buttons[$_lang] = array('title' => $lang, 'url' => array('plugin' => 'base', 'admin' => true, 'controller' => 'translate', 'action' => 'index', $_lang));
    }
    CmsNav::add('base.children.translates', array(
			'title' => ___('Translates'),
			'url' => array(
				'plugin' => 'base',
				'admin' => true,
				'controller' => 'translate',
				'action' => 'index',
                Configure::read('Config.language')
			),
            'weight' => 35,
            'buttons' => $buttons,
    ));
}

if(Configure::read('CMS.page_list') != '1') CmsNav::remove('base.children.pages.buttons.create_list');
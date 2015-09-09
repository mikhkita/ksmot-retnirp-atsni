<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Insta Printer Tomsk',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
	),

	'modules'=>array(
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'4815162342',
        ),
    ),

	'defaultController'=>'site',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// 'db'=>array(
		// 	'connectionString' => 'sqlite:protected/data/blog.db',
		// 	'tablePrefix' => 'tbl_',
		// ),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=hashtag',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
			// 'tablePrefix' => 'tbl_',
			'tablePrefix' => '',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName' => false,
            'appendParams' => false,
            'rules'=>array(
            	''=>'site/login',
            	'admin'=>'site/login',
            	'admin/mark/<mark_id:\d+>/'=>'carModel/adminindex',
            	'admin/mark/<mark_id:\d+>/<model_id:\d+>/'=>'engine/adminindex',
            	'<code:\w+>.html'=>array('page/index', 'caseSensitive'=>false),
            	'<controller:\w+>/<id:\d+>'=>array('<controller>/index', 'caseSensitive'=>false),
            	// 'admin/<controller:\w+>/<filter_id:\d+>'=>array('<controller>/adminIndex', 'caseSensitive'=>false),
            	'admin/<controller:\w+>'=>array('<controller>/adminIndex', 'caseSensitive'=>false),
            	'admin/<controller:\w+>/<action:\w+>'=>array('<controller>/admin<action>', 'caseSensitive'=>false),
				'<controller:\w+>/<action:\w+>'=>array('<controller>/<action>', 'caseSensitive'=>false),
			),
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
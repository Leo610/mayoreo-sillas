<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'Sistema roscator',
	'theme' => 'ordaweb',
	'language' => 'es',
	// The language to translate the application into
	'sourceLanguage' => 'es',
	// The language in the source files (application language)

	// preloading 'log' component
	'preload' => array('log'),

	// autoloading model and component classes
	'import' => array(
		'application.models.*',
		'application.components.*',
		'application.helpers.*',
	),

	'modules' => array(
		// uncomment the following to enable the Gii tool

		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => '123456?dvb',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			#'ipFilters' => array('127.0.0.1', '::1', '201.172.235.200', '192.168.0.21', '192.168.0.35'),
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			#'ipFilters'=>array('127.0.0.1','::1','*'),
		),

	),

	// application components
	'components' => array(
		'authManager' => array(
			"class" => "CDbAuthManager",
			"connectionID" => "db",
		),
		'ePdf' => array(
			'class' => 'ext.yii-pdf.EYiiPdf',
			'params' => array(
				'mpdf' => array(
					'librarySourcePath' => 'application.vendor.mpdf.*',
					'constants' => array(
						'_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
					),
					'class' => 'mpdf',
					// the literal class filename to be loaded from the vendors folder
					/*'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
																																																				  'mode'              => '', //  This parameter specifies the mode of the new document.
																																																				  'format'            => 'A4', // format A4, A5, ...
																																																				  'default_font_size' => 0, // Sets the default document font size in points (pt)
																																																				  'default_font'      => '', // Sets the default font-family for the new document.
																																																				  'mgl'               => 15, // margin_left. Sets the page margins for the new document.
																																																				  'mgr'               => 15, // margin_right
																																																				  'mgt'               => 16, // margin_top
																																																				  'mgb'               => 16, // margin_bottom
																																																				  'mgh'               => 9, // margin_header
																																																				  'mgf'               => 9, // margin_footer
																																																				  'orientation'       => 'P', // landscape or portrait orientation
																																																			  )*/
				),
				'HTML2PDF' => array(
					'librarySourcePath' => 'application.vendor.html2pdf.*',
					'classFile' => 'html2pdf.class.php',
					// For adding to Yii::$classMap
					/*'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
																																																				  'orientation' => 'P', // landscape or portrait orientation
																																																				  'format'      => 'A4', // format A4, A5, ...
																																																				  'language'    => 'en', // language: fr, en, it ...
																																																				  'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
																																																				  'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
																																																				  'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
																																																			  )*/
				)
			),
		),
		'mail' => array(
			'class' => 'ext.yii-mail.YiiMail',
			'transportType' => 'smtp',
			'transportOptions' => array(
				'host' => 'mail.mayoreodesillas.com',
				'username' => 'sistema@mayoreodesillas.com',
				'password' => '2X=L{]@OFNqm',
				'port' => '25',
			),
			'viewPath' => 'application.views.mail',
		),
		// 'mail' => array(
		// 	'class' => 'ext.yii-mail.YiiMail',
		// 	'transportType' => 'smtp',
		// 	'transportOptions' => array(
		// 		'host' => 'mail.g8-crm.com',
		// 		'username' => 'noreply@g8-crm.com',
		// 		'password' => 'noreply?dvb?090423',
		// 		'port' => '26',
		// 	),
		// 	'viewPath' => 'application.views.mail',
		// ),

		'user' => array(
			// There you go, use our 'extended' version
			'class' => 'application.components.EWebUser',
			// enable cookie-based authentication
			'allowAutoLogin' => true,
		),

		// uncomment the following to enable URLs in path-format

		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'urlSuffix' => '',
			'rules' => array(
				'site/login/<slug>' => 'site/login',
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),
		),


		'db' => require(dirname(__FILE__) . '/database.php'),
		//'dbadvert' => '',

		// Archivo de base de datos dinamico
		//'db'=>require(dirname(__FILE__).'/db.php'),

		'errorHandler' => array(
			// use 'site/error' action to display errors
			'errorAction' => 'site/error',
		),

		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
				array(
					'class' => 'CCustomLogRoute',
					'levels' => 'error, warning',
					'categories' => 'system.db.*',
				),
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params' => array(
		// this is used in contact page
		'adminEmail' => 'sistema@mayoreodesillas.com',
		'frommail' => 'sistema@mayoreodesillas.com',
		'calidadimagen' => 80,
		'maxwidthimgnormal' => 1000,
		'maxheightimgnormal' => 1000,
		'slidermaxwidth' => 2000,
		'slidermaxheight' => 2000,
		'maxwidthimgsmall' => 300,
		'maxheightimgsmall' => 300,
	),
);
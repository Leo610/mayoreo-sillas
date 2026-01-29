<?php

// This is the database connection configuration.
return array(
	'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database

	'connectionString' => 'mysql:host=localhost;dbname=mayoreodesillas_ventelia_sistemaroscator',
	 'emulatePrepare' => true,
	 'username' => 'mayoreodesillas_nubograma',
	 'password' => 'auphaXTkOO~P',
	 'charset' => 'utf8',

	'enableParamLogging' => false,
	'enableProfiling' => false,
	// 'connectionString' => 'mysql:host=localhost;dbname=ventelia_demos',
	/*'connectionString' => 'mysql:host=localhost;dbname=ventelia_sistemaroscator',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
	'enableParamLogging' => false,
	'enableProfiling' => false,*/

);
<?php
	
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
	ini_set('display_errors', 0); 
	define('C5_EXECUTE', true);
	
	define('DIR_BASE', dirname(__FILE__) . '/web');
	define('DIR_CONFIG_SITE', DIR_BASE . '/config');

	$corePath = DIR_BASE . '/concrete';
	
	// load the parameters from the site.php file
	require(DIR_CONFIG_SITE . '/site.php');
	
	/****************************** LOAD THE CORE ***************************/
	
	## Startup check ##	
	require($corePath . '/config/base_pre.php');
	
	## Load the base config file ##
	require($corePath . '/config/base.php');
	
	## Required Loading
	require($corePath . '/startup/required.php');
	
	## Setup timezone support
	require($corePath . '/startup/timezone.php'); // must be included before any date related functions are called (php 5.3 +)
	
	## First we ensure that dispatcher is not being called directly
	require($corePath . '/startup/file_access_check.php');
	
	require($corePath . '/startup/localization.php');
	
	## Autoload core classes
	spl_autoload_register(array('Loader', 'autoloadCore'), true);
	
	## Load the database ##
	Loader::database();
	
	require($corePath . '/startup/autoload.php');
	
	## Exception handler
	require($corePath . '/startup/exceptions.php');
	
	## Set default permissions for new files and directories ##
	require($corePath . '/startup/file_permission_config.php');
	
	## Startup check, install ##	
	require($corePath . '/startup/magic_quotes_gpc_check.php');
	
	## Default routes for various content items ##
	require($corePath . '/config/theme_paths.php');
	
	## Load session handlers
	require($corePath . '/startup/session.php');
	
	## Startup check ##	
	require($corePath . '/startup/encoding_check.php');
	
	## Required for command line installation, with RedisPageCache ##
	require( DIR_BASE . '/packages/concrete_redis/libraries/concrete_redis.php' );
	require( DIR_BASE . '/packages/concrete_redis/libraries/page_cache/type/redis.php' );
	
	
	/**
	 * Custom stuff to work w/ Pagoda
	 */
	try {
		
		print "Core loaded - running install with database credentials: \n";
		
		// start mysql connection test
		print "Testing MySQL Connection. \n";
		print "Server: " . DB_SERVER . "Database: " . DB_DATABASE . ", Username: " . DB_USERNAME . ", Password: " . DB_PASSWORD . "\n";
		
		// test that we can connect to the database		
		Loader::db($_SERVER['DB1_HOST'], $_SERVER['DB1_USER'], $_SERVER['DB1_PASS'], $_SERVER['DB1_NAME']);
		
		print "Connected to MySQL Server. \n";
		
		$cnt = Loader::controller('/install');
		$cnt->on_start();
		
		print "Install controller loaded. \n";
		
		// look at the database; if tables exist, dont do a fresh install
		try {
			print "Running table presence test. \n";
			$tables = Loader::db()->GetCol("SHOW TABLES");
			if( count($tables) ){
				throw new Exception(t("Database already populated with %s tables; no fresh install required.", count($tables)));
			}
			print "Database is empty - proceed. \n";
		}catch(Exception $e){
			throw $e;
		}
		
		// if we get here, we're installing a new instance
		$spl = Loader::startingPointPackage('blank');
		
		define('INSTALL_USER_EMAIL', 'change@me.com');
		define('INSTALL_USER_PASSWORD_HASH', User::encryptPassword('c5@dmin', PASSWORD_SALT));
		define('SITE', 'Concrete5 Pagoda');
		
		$routines = $spl->getInstallRoutines();
		print "Install routines loaded, starting installation... \n";
		try {
			foreach($routines AS $routine){
				print $routine->getProgress() . '%: ' . $routine->getText() . "\n";
				call_user_func(array($spl, $routine->getMethod()));
			}
		}catch(Exception $ex){
			throw $ex;
		}
		
		if( !isset($ex) ){
			Config::save('SEEN_INTRODUCTION', 1);
			
			print "Enabling Pretty URLs. \n";
			Config::save('URL_REWRITING', 1);
			
			print "Installation Complete! \n";
			
			// login info
			print "**** Change these after logging in! **** \n";
			print "Login with credentials: \n";
			print "user: admin \n";
			print "pass: c5@dmin \n";
		}
		
	}catch(Exception $e){
		print $e->getMessage() . "\n";
	}

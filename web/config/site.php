<?php

	if( !(isset($_SERVER['PAGODA_PRODUCTION']) && $_SERVER['PAGODA_PRODUCTION'] == 'true') ) {
		
		require __DIR__ . '/site.local.php';
		
		/**************************** SAMPLE *****************************
		$_SERVER['DB1_HOST'] = 'localhost';
		$_SERVER['DB1_USER'] = 'root';
		$_SERVER['DB1_PASS'] = 'chardonn';
		$_SERVER['DB1_NAME'] = 'pro-guide-direct';
		
		// enable all url rewriting
		define('URL_REWRITING_ALL', true);
		
		// redis, and redis full page cache library
		define('REDIS_CONNECTION_HANDLE', '127.0.0.1:6379');
		*****************************************************************/
		
	}else{
		
		// enable all url rewriting
		define('URL_REWRITING_ALL', true);
		
		// Pagoda's master-master db setup causes auto-incrementing of Groups to be
		// different than normal; and C5 uses constants for a couple mandatory groupIDs.
		// Override the defaults here.
		define('REGISTERED_GROUP_ID', '5');
		define('ADMIN_GROUP_ID', '9');
		
		define('REDIS_CONNECTION_HANDLE', 'tunnel.pagodabox.com:6379');
		
	}

	define('DB_SERVER',     $_SERVER['DB1_HOST']);
    define('DB_USERNAME',   $_SERVER['DB1_USER']);
    define('DB_PASSWORD',   $_SERVER['DB1_PASS']);
	define('DB_DATABASE',   $_SERVER['DB1_NAME']);
	define('PASSWORD_SALT', '6NVukfgwAgqaOi3SMlsWwEqURSe4Xh8pBApvhOauP7blC2kx1FKsHxcjGSXMqP3N');
	
	// settings
	define('PAGE_TITLE_FORMAT', '%2$s');
	
	// use redis page cache
	define('PAGE_CACHE_LIBRARY', 'Redis');
	
	// enable application profiler
	define('ENABLE_APPLICATION_PROFILER', true);
	define('ENABLE_APPLICATION_PROFILER_DATABASE', true);

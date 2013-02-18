<?php

	if( !(isset($_SERVER['PAGODA_PRODUCTION']) && $_SERVER['PAGODA_PRODUCTION'] == 'true') ) {
		
		require __DIR__ . '/site.local.php';
		
	}else{
		
		// enable all url rewriting
		define('URL_REWRITING_ALL', true);
		
		// Pagoda's master-master db setup causes auto-incrementing of Groups to be
		// different than normal; and C5 uses constants for a couple mandatory groupIDs.
		// Override the defaults here.
		define('REGISTERED_GROUP_ID', '5');
		define('ADMIN_GROUP_ID', '9');
		
	}

	define('DB_SERVER',     $_SERVER['DB1_HOST']);
    define('DB_USERNAME',   $_SERVER['DB1_USER']);
    define('DB_PASSWORD',   $_SERVER['DB1_PASS']);
	define('DB_DATABASE',   $_SERVER['DB1_NAME']);
	define('PASSWORD_SALT', '6NVukfgwAgqaOi3SMlsWwEqURSe4Xh8pBApvhOauP7blC2kx1FKsHxcjGSXMqP3N');
	
	// settings
	define('PAGE_TITLE_FORMAT', '%2$s');

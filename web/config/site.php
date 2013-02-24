<?php
	
	/**
	 * PAGODABOX PRODUCTION SETTINGS
	 */
	if( isset($_SERVER['PAGODA_PRODUCTION']) && ((bool) $_SERVER['PAGODA_PRODUCTION'] === true) ) {
		
		// enable all url rewriting
		define('URL_REWRITING_ALL', true);
		
		// needed for successful installation on Pagodabox. see issue
		// http://www.concrete5.org/developers/bugs/5-6-0-2/install-fails-with-mysql-auto-increment-offset-set/
		define('REGISTERED_GROUP_ID', '5');
		define('ADMIN_GROUP_ID', '9');
		
		// connect to Redis cache
		define('REDIS_CONNECTION_HANDLE', 'tunnel.pagodabox.com:6379');
		
		// the following depend on the constant REDIS_CONNECTION_HANDLE being defined
		if( defined('REDIS_CONNECTION_HANDLE') ){
			// use Redis as the page cache library
			define('PAGE_CACHE_LIBRARY', 'Redis');
		
			// if using the FluidDNS package
			define('PAGE_TITLE_FORMAT', '%2$s');
		}
		
		// application profiler. disable this for live sites! (just comment out)
		define('ENABLE_APPLICATION_PROFILER', true);
	
	/**
	 * LOCAL DEVELOPMENT OR STAGING
	 * 
	 * Is the site running locally? Then create a site.local.php file in the /config folder,
	 * and DO NOT TRACK IT IN THE REPO (default settings in .gitignore). Any team members, 
	 * or other environments (eg. dev or staging) you want to run the site on should have 
	 * their own site.local.php file.
	 */
	}else{
		
		require __DIR__ . '/site.local.php';
		
		/**************************** SAMPLE *****************************
		$_SERVER['DB1_HOST'] = 'localhost';
		$_SERVER['DB1_USER'] = 'root';
		$_SERVER['DB1_PASS'] = '';
		$_SERVER['DB1_NAME'] = '';
		
		// enable url rewriting. use locally if you have an Apache VirtualHost setup
		define('URL_REWRITING_ALL', true);
		
		// if you have Redis installed on your local machine...
		define('REDIS_CONNECTION_HANDLE', '127.0.0.1:6379');
		*****************************************************************/
		
	}


	// server variables are set by Pagoda, or by you in site.local.php
	define('DB_SERVER',     $_SERVER['DB1_HOST']);
    define('DB_USERNAME',   $_SERVER['DB1_USER']);
    define('DB_PASSWORD',   $_SERVER['DB1_PASS']);
	define('DB_DATABASE',   $_SERVER['DB1_NAME']);
	define('PASSWORD_SALT', '6NVukfgwAgqaOi3SMlsWwEqURSe4Xh8pBApvhOauP7blC2kx1FKsHxcjGSXMqP3N');
	
	// marketplace settings and such
	define('ENABLE_MARKETPLACE_SUPPORT', false);

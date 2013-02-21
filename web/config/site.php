<?php

	/**
	 * Is the site running locally? Then create a site.local.php file in the /config folder,
	 * and DO NOT TRACK IT IN THE REPO. Any team members, or other environments (eg. dev or staging)
	 * you want to run the site on should have their own site.local.php file.
	 */
	if( !(isset($_SERVER['PAGODA_PRODUCTION']) && $_SERVER['PAGODA_PRODUCTION'] == 'true') ) {
		
		require __DIR__ . '/site.local.php';
		
		/**************************** SAMPLE *****************************
		$_SERVER['DB1_HOST'] = 'localhost';
		$_SERVER['DB1_USER'] = 'root';
		$_SERVER['DB1_PASS'] = '';
		$_SERVER['DB1_NAME'] = '';
		
		// enable all url rewriting
		define('URL_REWRITING_ALL', true);
		*****************************************************************/
		
	}else{
		
		// enable all url rewriting
		define('URL_REWRITING_ALL', true);
		
		// needed for Pagoda install
		define('REGISTERED_GROUP_ID', '5');
		define('ADMIN_GROUP_ID', '9');
		
	}

	// server variables are set by Pagoda, or by you in site.local.php
	define('DB_SERVER',     $_SERVER['DB1_HOST']);
	define('DB_USERNAME',   $_SERVER['DB1_USER']);
	define('DB_PASSWORD',   $_SERVER['DB1_PASS']);
	define('DB_DATABASE',   $_SERVER['DB1_NAME']);
	define('PASSWORD_SALT', '6NVukfgwAgqaOi3SMlsWwEqURSe4Xh8pBApvhOauP7blC2kx1FKsHxcjGSXMqP3N');

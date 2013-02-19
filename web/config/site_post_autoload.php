<?php

	// determine if the request is being executed on a subdomain
	$domain = parse_url( $_SERVER['HTTP_HOST'], PHP_URL_PATH );
	if( substr_count($domain, '.') > 1 ){
		$sections = explode('.', $domain, 2);
		define('REQUEST_SUB_DOMAIN', $sections[0]);
		define('REQUEST_BASE_DOMAIN', $sections[1]);
	}

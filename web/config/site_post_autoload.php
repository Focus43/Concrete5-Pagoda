<?php

	// determine if the request is being executed on a subdomain
	$domain = parse_url( $_SERVER['HTTP_HOST'], PHP_URL_PATH );
	if( substr_count($domain, '.') > 1 ){
		$sections = explode('.', $domain, 2);
		define('CURRENT_SUBDOMAIN', $sections[0]);
	}

<?php

	Loader::model('multisite_domain', 'multisite');
	$domainMappings = Cache::get('MultisiteDomain', 'domains');
	print_r($domainMappings);
	if( is_array($domainMappings) ){
		print_r($domainMappings);
	}
	
	exit;

	// parse request domain, and explode into array
	$domain   = parse_url( $_SERVER['HTTP_HOST'], PHP_URL_PATH );
	$sections = explode('.', $domain);
	
	// setup root and subdomain constants
	$dot_ = array_pop($sections);
	$base = array_pop($sections);
	define('REQUEST_ROOT_DOMAIN', "{$base}.{$dot_}");
	define('REQUEST_SUB_DOMAIN', empty($sections) ? null : join('.', $sections));
	//$path = join('/', array_reverse($sections));
	
	// see if we're dealing with a subdomain *first*
	if( !is_null(REQUEST_SUB_DOMAIN) ){
		echo 'sub!';
	}

	// if $sections is now empty, we're handling only a base domain
	if( empty($sections) ){
		echo 'thats all!';
	}
	
	
	
	exit;
	
	// are we dealing with a subdomain?
	if( substr_count($domain, '.') > 1 ){
		define('REQUEST_SUB_DOMAIN', $sections[0]);
		echo 'sub domain';
		exit;
	}
	
	// are we pointing the root domain to a subpage?
	if( substr_count($domain, '.') === 1 ){
		echo REQUEST_ROOT_DOMAIN;exit;
		$mapper = Cache::get('MultisiteDomain', REQUEST_ROOT_DOMAIN);
		print_r($mapper);exit;
		if( $mapper instanceof MultisiteDomain ){
			echo 'ok';
		}
	}
	
	exit;
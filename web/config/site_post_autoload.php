<?php

	Loader::library('concrete_redis', 'multisite');
	
	// parse request domain, and explode into array
	$domain   = parse_url( $_SERVER['HTTP_HOST'], PHP_URL_PATH );
	$sections = explode('.', $domain);
	
	// setup root and subdomain constants
	$dot_ = array_pop($sections);
	$base = array_pop($sections);
	define('REQUEST_ROOT_DOMAIN', "{$base}.{$dot_}");
	define('REQUEST_SUB_DOMAIN', empty($sections) ? null : join('.', $sections));
	
	// see if the current domain is registered as a root domain. means that a subdomain
	// can *technically* be the root
	$domainAsRoot = ConcreteRedis::db()->hget('domain_paths', $domain);
	if( !($domainAsRoot === null) ){
		define('REQUEST_SUB_DOMAIN_IS_ROOT', true);
		$domainData = json_decode($domainAsRoot);
	
	// if the above failed, check the absolute root domain for an entry
	}else{
		$absoluteRoot = ConcreteRedis::db()->hget('domain_paths', REQUEST_ROOT_DOMAIN);
		if( !($absoluteRoot === null) ){
			$domainData = json_decode($absoluteRoot);
		}
	}
	
	// if domain data was found in Redis, set constants
	if( !($domainData === null) ){
		define('REQUEST_RESOLVE_ROOT_PATH', $domainData->path);
		define('REQUEST_RESOLVE_WILDCARDS', (bool) $domainData->resolveWildcards);
		define('REQUEST_RESOLVE_WILDCARDS_PATH', $domainData->wildcardRootPath);
	}

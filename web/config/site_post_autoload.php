<?php

	// if the Redis connection handle is defined, we're using Redis for something
	if( defined('REDIS_CONNECTION_HANDLE') ){
		$classes = array('ConcreteRedis'	=> array('library', 'concrete_redis', 'concrete_redis'));
		
		// if the page cache library is set to Redis, add it to the autoloader classes
		if( defined('PAGE_CACHE_LIBRARY') && (PAGE_CACHE_LIBRARY === 'Redis') ){
			$classes['RedisPageCache'] = array('library', 'page_cache/type/redis', 'concrete_redis');
		}
		
		Loader::registerAutoload($classes);
	}
	
	
	// if the ConcreteRedis class exists at this point (eg. was autoloaded), then query
	// for FluidDNS settings
	if( class_exists('ConcreteRedis') ){
		
		// wrap everything in a try so we can fail gracefully
		try {
			// first things first; count the number of cached domain records. if none,
			// let Concrete5 route as default (eg. any domain works)
			$domainRecords = ConcreteRedis::db()->hlen('domain_paths');
			if( $domainRecords >= 1 ){
				// parse request domain, and explode into array
				$_domain  = parse_url( $_SERVER['HTTP_HOST'] );
                $domain   = $_domain['host'];
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
					if( !(REQUEST_SUB_DOMAIN === null) ){
						define('REQUEST_SUB_DOMAIN_IS_ROOT', true);
					}
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
			}

		}catch(Exception $e){
			// fail gracefully; let Concrete5 route as default
		}
		
	}
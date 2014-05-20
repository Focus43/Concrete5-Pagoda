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
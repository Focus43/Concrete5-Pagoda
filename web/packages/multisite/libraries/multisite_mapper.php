<?php

	class MultisiteMapper {
		
		const FLAT_CACHE_KEY = 'multisite_domains';
		
		public static function compile(){
			$md5 = md5(__CLASS__ . self::FLAT_CACHE_KEY)
			Cache::delete(__CLASS__, self::FLAT_CACHE_KEY);
			Cache::set(__CLASS__, self::FLAT_CACHE_KEY, self::getList());
		}
		
	}

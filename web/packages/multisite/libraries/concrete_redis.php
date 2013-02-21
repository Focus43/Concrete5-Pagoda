<?php

	class ConcreteRedis {
		
		
		private static $instance = null;
		private static $redisDB;
		
		private function __construct(){
			Loader::library('predis-0.8.3/autoload', 'multisite');
			Predis\Autoloader::register();
			self::$redisDB = new Predis\Client( REDIS_CONNECTION_HANDLE );
		}
		
		
		/**
		 * @return Predis\Client
		 */
		public static function db(){
			if( self::$instance === null ){
				self::$instance = new self;
			}
			return self::$redisDB;
		}
		
	}

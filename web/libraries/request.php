<?php defined('C5_EXECUTE') or die("Access Denied.");

	class Request extends Concrete5_Library_Request {
		
		
		protected $_parsedSubdomain;
		
		
		/**
		 * Override the constructor
		 */
		public function __construct( $path ){
			$request = $this->pathToPageOrSystem( $path );
			parent::__construct( $request );
		}
		
		
		protected function pathToPageOrSystem( $path ){
			$exploded = explode('/', $path);
			if( $exploded[0] == 'tools' || $exploded[0] == 'login' ){
				return $path;
			}
			
			$subdomain = $this->parseSubDomain();
			return ($subdomain === null) ? $path : "{$subdomain}/{$path}";
		}
		
		
		/**
		 * Parse the domain request, and get just the subdomain
		 * @return string
		 */
		protected function parseSubDomain(){
			if( $this->_parsedSubdomain == null ){
				$domain = parse_url($_SERVER['HTTP_HOST'], PHP_URL_PATH);
				if( substr_count($domain, '.') > 1 ){
					$url = explode('.', $domain, 2);
					$this->_parsedSubdomain = $url[0];
				}else{
					$this->_parsedSubdomain = null;
				}
			}
			return $this->_parsedSubdomain;
		}
		
	}

<?php defined('C5_EXECUTE') or die("Access Denied.");

	class Request extends Concrete5_Library_Request {
		
		
		protected $parsedSubdomain;
		
		
		/**
		 * Override the constructor
		 */
		public function __construct( $path ){
			$subdomain = $this->parseSubDomain();
			$path = ($subdomain === null) ? $path : "{$subdomain}/{$path}";
			parent::__construct($path);
		}
		
		
		/**
		 * Parse the domain request, and get just the subdomain
		 * @return string
		 */
		protected function parseSubDomain(){
			if( $this->parsedSubdomain == null ){
				$domain = parse_url($_SERVER['HTTP_HOST'], PHP_URL_PATH);
				if( substr_count($domain, '.') > 1 ){
					$url = explode('.', $domain, 2);
					$this->parsedSubdomain = $url[0];
				}else{
					$this->parsedSubdomain = null;
				}
			}
			return $this->parsedSubdomain;
		}
		
	}

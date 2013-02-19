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
			return ($subdomain === false) ? $path : "{$subdomain}/{$path}";
		}
		
		
		/**
		 * Parse the domain request, and get just the subdomain
		 * @return string || bool
		 */
		protected function parseSubDomain(){
			if( $this->_parsedSubdomain === null ){
				// default to false (eg. "no subdomain")
				$this->_parsedSubdomain = false;
				// try parsing
				$domain = parse_url($_SERVER['HTTP_HOST'], PHP_URL_PATH);
				if( substr_count($domain, '.') > 1 ){
					$url 		= explode('.', $domain, 2);
					$subdomain 	= $url[0];
					define('CURRENT_SUBDOMAIN', $subdomain);
					if( !isset($_GET['cID']) ){
						$this->_parsedSubdomain = $url[0];
					}
				}
			}
			return $this->_parsedSubdomain;
		}
		
	}

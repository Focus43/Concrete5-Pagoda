<?php defined('C5_EXECUTE') or die("Access Denied.");


	/**
	 * Determine whether to route the page response from a subdomain.
	 * The CURRENT_SUBDOMAIN constant is parsed in config/site_post_autoload.php
	 * higher up in the load order.
	 */
	class Request extends Concrete5_Library_Request {
		
		
		protected $_parsedSubdomain;
		
		
		/**
		 * Override the constructor, and run the $path through
		 * pathToPageOrSystem first.
		 */
		public function __construct( $path ){
			$request = $this->pathToPageOrSystem( $path );
			parent::__construct( $request );
		}
		
		
		/**
		 * Determine if the call is requesting a publicly accessible page, and
		 * not a "system" tool (eg. tools file, js/css parser, etc.)
		 * @param string path
		 * @return string
		 */
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
				
				if( defined('CURRENT_SUBDOMAIN') && !isset($_GET['cID']) ){
					$this->_parsedSubdomain = CURRENT_SUBDOMAIN;
				}
			}
			
			return $this->_parsedSubdomain;
		}
		
	}

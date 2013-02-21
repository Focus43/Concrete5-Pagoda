<?php defined('C5_EXECUTE') or die("Access Denied.");


	/**
	 * Determine whether to route the page response from a subdomain.
	 * The CURRENT_SUBDOMAIN constant is parsed in config/site_post_autoload.php
	 * higher up in the load order.
	 */
	class Request extends Concrete5_Library_Request {
		
		
		protected $domainRoute;
		
		
		/**
		 * Override the constructor, and run the $path through
		 * pathToPageOrSystem first.
		 */
		public function __construct( $path ){
			$request = trim($this->pathToPageOrSystem( $path ), '/');
			parent::__construct($request);
		}
		
		
		/**
		 * Determine if the call is requesting a publicly accessible page, and
		 * not a "system" tool (eg. tools file, js/css parser, etc.)
		 * @param string path
		 * @return string
		 */
		protected function pathToPageOrSystem( $path ){
			$exploded = explode('/', $path);
			if( in_array($exploded[0], array('tools', 'login', 'dashboard')) ){
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
			if( $this->domainRoute === null ){
				// default to false (eg. resolve to absolute home)
				$this->domainRoute = false;
				
				if( defined('REQUEST_RESOLVE_ROOT_PATH') && !(REQUEST_RESOLVE_ROOT_PATH === null) ){
					$this->domainRoute = REQUEST_RESOLVE_ROOT_PATH;
				}
				
				if( defined('REQUEST_RESOLVE_WILDCARDS') && (REQUEST_RESOLVE_WILDCARDS === true) && !isset($_GET['cID']) ){
					if( !(REQUEST_SUB_DOMAIN === null) ){
						if( !(REQUEST_RESOLVE_WILDCARDS_PATH === null) ){
							$this->domainRoute = REQUEST_RESOLVE_WILDCARDS_PATH . '/' . REQUEST_SUB_DOMAIN;
						}else{
							$this->domainRoute .= '/' . REQUEST_SUB_DOMAIN;
						}
					}
				}
				
				if( !(REQUEST_RESOLVE_WILDCARDS === true) && !(REQUEST_SUB_DOMAIN === null) ){
					$this->domainRoute = 'page_not_found';
				}
				
				/*if( defined('REQUEST_SUB_DOMAIN') && !isset($_GET['cID']) ){
					$this->_parsedSubdomain = REQUEST_SUB_DOMAIN;
				}*/
				if( isset($_GET['cID']) ){
					$this->domainRoute = false;
				}
			}
			
			return $this->domainRoute;
		}
		
	}

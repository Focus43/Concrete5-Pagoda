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
			if( !defined('REQUEST_ROOT_DOMAIN') ){
				parent::__construct($path);
				return;
			}
			
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
			
			$subdomain = $this->parseDomainRoute();
			return ($subdomain === false) ? $path : "{$subdomain}/{$path}";
		}
		
		
		/**
		 * Parse the domain request, and get just the subdomain
		 * @todo Work on restricting the ability to load pages by passing cID (specifically, if
		 * the user *is* logged in still)
		 * @return string || bool
		 */
		protected function parseDomainRoute(){
			if( $this->domainRoute === null ){
				// default to false (eg. resolve to absolute home)
				$this->domainRoute = false;
				
				// this domain starts at a specific page root (could be home or anything else)
				if( !(REQUEST_RESOLVE_ROOT_PATH === null) ){
					$this->domainRoute = REQUEST_RESOLVE_ROOT_PATH;
				}
				
				// root domain *can* have wildcard subdomains
				if( (REQUEST_RESOLVE_WILDCARDS === true) && !isset($_GET['cID']) ){
					// a subdomain *is* being requested
					if( !(REQUEST_SUB_DOMAIN === null) ){
						// wildcard subdomains have a different root than directly underneath
						if( !(REQUEST_RESOLVE_WILDCARDS_PATH === null) ){
							$this->domainRoute = REQUEST_RESOLVE_WILDCARDS_PATH . '/' . REQUEST_SUB_DOMAIN;
						// wildcard subdomain should resolve to page directly underneath the root
						}else{
							$this->domainRoute .= '/' . REQUEST_SUB_DOMAIN;
						}
					}
				}
				
				// no wildcards, but a subdomain *is* being requested
				if( !(REQUEST_RESOLVE_WILDCARDS === true) && !(REQUEST_SUB_DOMAIN === null) ){
					// the request *has* a subdomain, but it doesn't point to a specific root
					if( !(REQUEST_SUB_DOMAIN_IS_ROOT === true) ){
						$this->domainRoute = 'page_not_found';
					}
				}
				
				// what if its a www? then we automatically assume it aliases to the root
				if( REQUEST_SUB_DOMAIN == 'www' ){
					$this->domainRoute = REQUEST_ROOT_DOMAIN;
				}
				
				// handle if cID is passed in the query string
				if( isset($_GET['cID']) ){
					$this->domainRoute = false;
					
					// if the user is NOT logged in, disallow rendering a specific page by passing
					// cID as a query param
					if( !($_SESSION['uID'] > 0) ){
						$this->domainRoute = 'page_not_found';
					}
				}
			}
			
			return $this->domainRoute;
		}
		
	}

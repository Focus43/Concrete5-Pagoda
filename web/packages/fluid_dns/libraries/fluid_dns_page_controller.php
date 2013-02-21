<?php

	/**
	 * FluidDNS dashboard controller. Implementing classes are the
	 * actual page controllers.
	 * @author Jonathan Hartman
	 * @package FluidDNS
	 */
	abstract class FluidDnsPageController extends Controller {
		
		const PACKAGE_HANDLE 	= 'fluid_dns',
			  FLASH_TYPE_OK	 	= 'success',
			  FLASH_TYPE_ERROR	= 'error';
		
		
		/**
		 * Ruby on Rails "flash" functionality ripoff
		 * @param string $msg Optional, set the flash message
		 * @param string $type Optional, set the class for the alert
		 * @return void
		 */
		public function flash( $msg = 'Success', $type = self::FLASH_TYPE_OK ){
			$_SESSION['flash_msg'] = array(
				'msg'  => $msg,
				'type' => $type
			);
		}
		
		
		/**
		 * Override the parent Controller class's render() method so we can render
		 * view templates that match the name of the called controller action.
		 * @return void
		 */
		public function render(){
			$this->theme = 'dashboard';
			parent::render("{$this->c->cPath}/{$this->getTask()}");
		}
		
		
		/**
		 * Add js/css + tools URL meta tag; clear the flash.
		 * @return void
		 */
		public function on_start(){
			$this->addHeaderItem( '<meta id="fluid_dns_tools" value="'.FLUID_DNS_TOOLS_URL.'" />' );
			$this->addHeaderItem( $this->getHelper('html')->css('fdns.dashboard.css', self::PACKAGE_HANDLE) );
			$this->addFooterItem( $this->getHelper('html')->javascript('fdns.dashboard.js', self::PACKAGE_HANDLE) );

			// message flash
			if( isset($_SESSION['flash_msg']) ){
				$this->set('flash', $_SESSION['flash_msg']);
				unset($_SESSION['flash_msg']);
			}
		}
		
		
		/**
		 * "Memoize" helpers so they're only loaded once.
		 * @param string $handle Handle of the helper to load
		 * @param string $pkg Package to get the helper from
		 * @return ...Helper class of some sort
		 */
		public function getHelper( $handle, $pkg = false ){
			$helper = '_helper_' . preg_replace("/[^a-zA-Z0-9]+/", "", $handle);
			if( $this->{$helper} === null ){
				$this->{$helper} = Loader::helper($handle, $pkg);
			}
			return $this->{$helper};
		}
		
	}

<?php


	class MultisitePageController extends Controller {
		
		const PACKAGE_HANDLE 	= 'multisite',
			  FLASH_TYPE_OK	 	= 'success',
			  FLASH_TYPE_ERROR	= 'error';
		
		
		public function flash( $msg = 'Success', $type = self::FLASH_TYPE_OK ){
			$_SESSION['flash_msg'] = array(
				'msg'  => $msg,
				'type' => $type
			);
		}
		
		
		public function render(){
			$this->theme = 'dashboard';
			parent::render("{$this->c->cPath}/{$this->getTask()}");
		}
		
		
		public function on_start(){
			$this->addHeaderItem( $this->getHelper('html')->javascript('ms.dashboard.js', self::PACKAGE_HANDLE) );
			$this->addHeaderItem( $this->getHelper('html')->css('ms.dashboard.css', self::PACKAGE_HANDLE) );
			
			//
			// message flash
			if( isset($_SESSION['flash_msg']) ){
				$this->set('flash', $_SESSION['flash_msg']);
				unset($_SESSION['flash_msg']);
			}
		}
		
		
		/**
		 * @param string Handle of the helper to load
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

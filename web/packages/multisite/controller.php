<?php defined('C5_EXECUTE') or die(_("Access Denied."));
	
	class MultisitePackage extends Package {
	
	    protected $pkgHandle 			= 'multisite';
	    protected $appVersionRequired 	= '5.6.1';
	    protected $pkgVersion 			= '0.1.07';
	
	    
	    public function getPackageDescription() {
	        return t('Multisite Manager');
	    }
	
	
	    public function getPackageName(){
	        return t('Multisite');
	    }
	
	
	    public function on_start(){
	        define('MULTISITE_TOOLS_URL', BASE_URL . REL_DIR_FILES_TOOLS_PACKAGES . '/' . $this->pkgHandle . '/');
			
			Loader::registerAutoload(array(
				'MultisitePageController' => array('library', 'multisite_page_controller', $this->pkgHandle),
				'MultisiteDomain' => array('model', 'multisite_domain', $this->pkgHandle)
			));
	    }
		
	
	    public function uninstall() {
	        parent::uninstall();
	    }
		
		
		/**
		 * Run before install or upgrade to ensure dependencies are present
		 * @dependency concrete_redis package
		 */
		private function checkDependencies(){
			// test for the redis package
			$redisPackage 		= Package::getByHandle('concrete_redis');
			$redisPackageAvail 	= false;
			if( $redisPackage instanceof Package ){
				if( (bool) $redisPackage->isPackageInstalled() ){
					$redisPackageAvail = true;
				}
			}
			
			if( !$redisPackageAvail ){
				throw new Exception('Multisite depends on the Concrete Redis package.');
			}
		}
	    
	
	    public function upgrade(){
	    	$this->checkDependencies();
			parent::upgrade();
			$this->installAndUpdate();
	    }
		
		
		public function install() {
			$this->checkDependencies();
	    	$this->_packageObj = parent::install(); 
			$this->installAndUpdate();
	    }
		
		
		private function installAndUpdate(){
			$this->setupSinglePages();
		}
		
		
		/**
		 * @return MultisitePackage
		 */
		private function setupSinglePages(){
			SinglePage::add('/dashboard/multisite', $this->packageObject());
			SinglePage::add('/dashboard/multisite/manage', $this->packageObject());
			
			return $this;
		}


		/**
		 * Get the package object; if it hasn't been instantiated yet, load it.
		 * @return Package
		 */
		private function packageObject(){
			if( $this->_packageObj == null ){
				$this->_packageObj = Package::getByHandle( $this->pkgHandle );
			}
			return $this->_packageObj;
		}
	    
	}

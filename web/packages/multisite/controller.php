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
			$this->registerAutoloadClasses();
			
			// hook into on_page_update event, so we can recache domain route if path changes
			/*if( User::isLoggedIn() ){
				Events::extend('on_page_update', 'RedisDomainCache', 'update', 'packages/'.$this->pkgHandle.'/models/redis_domain_cache.php');
			}*/
	    } 
		
		
		private function registerAutoloadClasses(){
			Loader::registerAutoload(array(
				'MultisitePageController' => array('library', 'multisite_page_controller', $this->pkgHandle),
				'MultisiteDomain' => array('model', 'multisite_domain', $this->pkgHandle),
				'ConcreteRedis'	=> array('library', 'concrete_redis', $this->pkgHandle)
			));
		}
		
	
	    public function uninstall() {
	        parent::uninstall();
	    }
	    
	
	    public function upgrade(){
			parent::upgrade();
			$this->installAndUpdate();
	    }
		
		
		public function install() {
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

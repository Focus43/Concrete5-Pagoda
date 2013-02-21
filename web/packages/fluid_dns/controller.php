<?php defined('C5_EXECUTE') or die(_("Access Denied."));
	
	class FluidDnsPackage extends Package {
	
	    protected $pkgHandle 			= 'fluid_dns';
	    protected $appVersionRequired 	= '5.6.1';
	    protected $pkgVersion 			= '0.1.07';
	
	
	    public function getPackageName(){
	        return t('FluidDNS');
	    }
		
		
	    public function getPackageDescription() {
	        return t('Dynamic domain routing for Concrete5');
	    }
	
	
	    public function on_start(){
	        define('FLUID_DNS_TOOLS_URL', BASE_URL . REL_DIR_FILES_TOOLS_PACKAGES . '/' . $this->pkgHandle . '/');
			
			Loader::registerAutoload(array(
				'FluidDnsPageController' => array('library', 'fluid_dns_page_controller', $this->pkgHandle),
				'FluidDnsRoute' => array('model', 'fluid_dns_route', $this->pkgHandle)
			));
	    }
		
	
	    public function uninstall() {
	        parent::uninstall();
			
			try {
				// delete mysql tables
				$db = Loader::db();
				$db->Execute("DROP TABLE FluidDnsRoute");
				
				// clear redis cache
				ConcreteRedis::db()->del('domain_paths');
			}catch(Exception $e){
				// fail gracefully
			}
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
				throw new Exception('FluidDNS depends on the ConcreteRedis package.');
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
		 * @return FluidDnsPackage
		 */
		private function setupSinglePages(){
			SinglePage::add('/dashboard/fluid_dns', $this->packageObject());
			$manage = SinglePage::add('/dashboard/fluid_dns/manage_domain_routes', $this->packageObject());
			$manage->setAttribute('icon_dashboard', 'icon-globe');
			
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

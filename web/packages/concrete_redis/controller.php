<?php defined('C5_EXECUTE') or die(_("Access Denied."));
	
	class ConcreteRedisPackage extends Package {
	
	    protected $pkgHandle 			= 'concrete_redis';
	    protected $appVersionRequired 	= '5.6.1';
	    protected $pkgVersion 			= '0.01';
	
	    
	    public function getPackageDescription() {
	        return t('Redis Server Interface.');
	    }
	
	
	    public function getPackageName(){
	        return t('ConcreteRedis');
	    }
	
	
	    public function on_start(){
			Loader::registerAutoload(array(
				'ConcreteRedis' => array('library', 'concrete_redis', $this->pkgHandle)
			));
	    }
		
	
	    public function uninstall() {
	        parent::uninstall();
	    }
	    
	
	    public function upgrade(){
	    	$this->checkPhpVersion();
			parent::upgrade();
			$this->installAndUpdate();
	    }
		
		
		public function install() {
			$this->checkPhpVersion();
	    	$this->_packageObj = parent::install(); 
			$this->installAndUpdate();
	    }
		
		
		private function installAndUpdate(){
			
		}
		
		
		// make sure its PHP >= 5.3 to support the Predis library
		private function checkPhpVersion(){
			if( !( (float) phpversion() >= 5.3 ) ){
				throw new Exception('This package only runs on PHP 5.3 or greater.');
			}
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

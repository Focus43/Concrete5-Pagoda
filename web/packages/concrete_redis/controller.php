<?php defined('C5_EXECUTE') or die(_("Access Denied."));
	
	class ConcreteRedisPackage extends Package {
	
	    protected $pkgHandle 			= 'concrete_redis';
	    protected $appVersionRequired 	= '5.6.1';
	    protected $pkgVersion 			= '0.02';
	
	    
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
	    	$this->dependencyCheck();
			parent::upgrade();
			$this->installAndUpdate();
	    }
		
		
		public function install() {
			$this->dependencyCheck();
	    	$this->_packageObj = parent::install(); 
			$this->installAndUpdate();
	    }
		
		
		private function installAndUpdate(){
			$this->setupSinglePages();
		}
		
		
		/**
		 * Make sure the PHP version is 5.3 or greater. If its not, throw an exception and
		 * stop the install.
		 * @return void
		 */
		private function dependencyCheck(){
		    // make sure ConcreteRedis class is loaded and available
		    if( !class_exists('ConcreteRedis') ){
		        $this->on_start();
		    }
            
            // test php version is adequate
			if( !( (float) phpversion() >= 5.3 ) ){
				throw new Exception('This package only runs on PHP 5.3 or greater.');
			}

            // test redis connection; if fails, abort install
            try {
                ConcreteRedis::db()->ping();
            }catch(Exception $e){
                throw new Exception('Unable to connect to Redis. Check connection settings.');
            }
		}


		/**
		 * @return ConcreteRedisPackage
		 */
		private function setupSinglePages(){
			SinglePage::add('/dashboard/system/optimization/redis_cache', $this->packageObject());
			
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

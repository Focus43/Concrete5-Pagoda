<?php defined('C5_EXECUTE') or die(_("Access Denied."));
	
	class FlexryPackage extends Package {
	
	    protected $pkgHandle 			= 'flexry';
	    protected $appVersionRequired 	= '5.6.1';
	    protected $pkgVersion 			= '0.01';
	
	
	    public function getPackageName(){
	        return t('Flexry');
	    }
		
		
	    public function getPackageDescription() {
	        return t('Flexry Image Gallery');
	    }
	
	
	    public function on_start(){
	        define('FLEXRY_TOOLS_URL', REL_DIR_FILES_TOOLS_PACKAGES . '/' . $this->pkgHandle . '/');
            define('FLEXRY_IMAGE_PATH', DIR_REL . '/packages/' . $this->pkgHandle . '/images/');
            define('FLEXRY_JS_URL', DIR_REL . '/packages/' . $this->pkgHandle . '/js/');
			
			Loader::registerAutoload(array(
                'FlexryFile'     => array('model', 'flexry_file', $this->pkgHandle),
                'FlexryFileList' => array('model', 'flexry_file_list', $this->pkgHandle),
                'FlexryBlockTemplateOptions' => array('library', 'block_template', $this->pkgHandle)
			));
	    }
		
	
	    public function uninstall() {
	        parent::uninstall();
			
			try {
				// delete mysql tables
				$db = Loader::db();
				//$db->Execute("DROP TABLE FluidDnsRoute");
			}catch(Exception $e){
				// fail gracefully
			}
	    }
		
		
		/**
		 * Run before install/upgrade to ensure dependencies are present.
		 */
		private function checkDependencies(){
			// none required
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
			$this->installBlocks();
		}


        /**
         * Install blocks w/ this package.
         * @return FlexryPackage
         */
        private function installBlocks(){
            // Flexry
            if( ! is_object(BlockType::getByHandle('flexry_gallery')) ) {
                BlockType::installBlockTypeFromPackage('flexry_gallery', $this->packageObject());
            }

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

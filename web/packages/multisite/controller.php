<?php defined('C5_EXECUTE') or die(_("Access Denied."));
	
	class MultisitePackage extends Package {
	
	    protected $pkgHandle 			= 'multisite';
	    protected $appVersionRequired 	= '5.6.1';
	    protected $pkgVersion 			= '0.1.05';
	
	    
	    public function getPackageDescription() {
	        return t('Multisite Manager');
	    }
	
	
	    public function getPackageName(){
	        return t('Multisite');
	    }
	
	
	    public function on_start(){
	        $uh = Loader::helper('concrete/urls');
	        define('MULTISITE_TOOLS_URL', BASE_URL . $uh->getToolsURL('', $this->pkgHandle) );
			
			$this->registerAutoloadClasses();
	    } 
		
		
		private function registerAutoloadClasses(){
			$classes = array(
				'MultisitePageController' => array('library', 'multisite_page_controller', $this->pkgHandle),
				'MultisiteDomain' => array('model', 'multisite_domain', $this->pkgHandle)//,
				//'PageNoteList' => array('model', 'page_note_list', $this->pkgHandle)
			);
			Loader::registerAutoload($classes);
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
			SinglePage::add('/dashboard/multisite/settings', $this->packageObject());
			
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

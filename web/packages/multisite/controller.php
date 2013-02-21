<?php defined('C5_EXECUTE') or die(_("Access Denied."));
	
	class MultisitePackage extends Package {
	
	    protected $pkgHandle 			= 'multisite';
	    protected $appVersionRequired 	= '5.6.1';
	    protected $pkgVersion 			= '0.1.07';
	
	
	    public function getPackageName(){
	        return t('Multisite');
	    }
		
		
	    public function getPackageDescription() {
	        return t('Multisite');
	    }
		
	
	    public function uninstall() {
	        parent::uninstall();
			
			try {
				// delete mysql tables
				$db = Loader::db();
				$db->Execute("DROP TABLE MultisiteDomain");
			}catch(Exception $e){
				// fail gracefully
			}
	    }
	    
	
	    public function upgrade(){}
		
		
		public function install() {}
	    
	}

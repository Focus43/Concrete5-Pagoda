<? defined('C5_EXECUTE') or die("Access Denied.");

	class AutonavBlockController extends Concrete5_Controller_Block_Autonav { }
	
	class AutonavBlockItem extends Concrete5_Controller_Block_AutonavItem {
		
		function getURL(){
			if( defined('CURRENT_SUBDOMAIN') ){
				$this->cPath = str_replace('/' . CURRENT_SUBDOMAIN, '', $this->cPath);
			}
			
			return parent::getURL();
		}
		
	}
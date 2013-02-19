<? defined('C5_EXECUTE') or die("Access Denied.");

	class AutonavBlockController extends Concrete5_Controller_Block_Autonav { }
	
	class AutonavBlockItem extends Concrete5_Controller_Block_AutonavItem {
		
		function getURL(){
			if( defined('REQUEST_SUB_DOMAIN') ){
				$this->cPath = str_replace('/' . REQUEST_SUB_DOMAIN, '', $this->cPath);
			}
			
			return parent::getURL();
		}
		
	}
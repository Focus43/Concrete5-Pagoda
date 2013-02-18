<?php defined('C5_EXECUTE') or die("Access Denied.");

	class InstallController extends Concrete5_Controller_Install {
	
		public function on_start(){
			if (isset($_POST['locale']) && $_POST['locale']) {
				define("ACTIVE_LOCALE", $_POST['locale']);
				$this->set('locale', $_POST['locale']);
			}
			require(DIR_BASE_CORE . '/config/file_types.php');
			Cache::disableCache();
			Cache::disableLocalCache();
			//$this->setRequiredItems();
			//$this->setOptionalItems();
			Loader::model('package/starting_point');
		}
	
	}


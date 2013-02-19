<?php

	class DashboardMultisiteManageController extends MultisitePageController {
		
		public $helpers = array('form', 'form/page_selector');
		
		public function view(){
			$domainsList = MultisiteDomain::getList();
			$this->set('domainsList', $domainsList);
			$this->render();
		}
		
		public function add(){
			$this->render();
		}
		
		public function create(){
			$domain = $this->validateRootDomain($_POST['root_domain']);
			$path	= Page::getByID( (int) $_POST['pageID'] )->getCollectionPath();
			$model	= new MultisiteDomain(array(
				'domain' => $domain,
				'path'	 => $path
			));
			$model->save();
		}
		
		
		/**
		 * Crude root domain validation function. Ensures it is not prefixed
		 * with http://, www., a pre "."
		 */
		protected function validateRootDomain( $rootDomain ){
			return preg_replace(array('/(http:\/\/)/', '/(https:\/\/)/', '/www./', '/^\./'), '', $rootDomain);
		}
		
	}

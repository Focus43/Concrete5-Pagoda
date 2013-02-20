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
			$model	= new MultisiteDomain(array(
				'domain' 			=> $this->validateRootDomain($_POST['root_domain']),
				'path'	 			=> Page::getByID( (int) $_POST['pageID'] )->getCollectionPath(),
				'pageID' 			=> (int) $_POST['pageID'],
				'resolveWildcards'	=> (int) $_POST['resolveWildcards'],
				'wildcardRootPath'	=> Page::getByID( (int) $_POST['wildcardParentID'] )->getCollectionPath(),
				'wildcardParentID'	=> (int) $_POST['wildcardParentID']
			));
			$model->save();
			
			$this->flash('Root Domain Created Successfully.');
			$this->redirect('dashboard/multisite/manage');
		}
		
		
		/**
		 * Crude root domain validation function. Ensures it is not prefixed
		 * with http://, www., a pre "."
		 */
		protected function validateRootDomain( $rootDomain ){
			return preg_replace(array('/(http:\/\/)/', '/(https:\/\/)/', '/www./', '/^\./'), '', $rootDomain);
		}
		
	}

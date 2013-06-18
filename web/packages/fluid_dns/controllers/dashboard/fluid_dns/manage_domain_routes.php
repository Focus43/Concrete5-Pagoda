<?php

	class DashboardFluidDnsManageDomainRoutesController extends FluidDnsPageController {
		
		public $helpers = array('form', 'form/page_selector');
		
		public function view(){
			$domainsList = $this->getDomainsList();
			$this->set('domainsList', $domainsList);
			$this->render();
		}
		
		public function add(){
			$this->set('domainObj', new FluidDnsRoute);
			$this->render();
		}
		
		public function edit( $id ){
			$this->set('domainObj', FluidDnsRoute::getByID($id));
			$this->render();
		}
		
		public function create(){
			try {
				$model	= new FluidDnsRoute(array(
					'domain' 			=> $this->validateRootDomain($_POST['root_domain']),
					'path'	 			=> Page::getByID( (int) $_POST['pageID'] )->getCollectionPath(),
					'pageID' 			=> (int) $_POST['pageID'],
					'resolveWildcards'	=> (int) $_POST['resolveWildcards'],
					'wildcardRootPath'	=> (!((bool) $_POST['resolveWildcards'])) ? null : Page::getByID( (int) $_POST['wildcardParentID'] )->getCollectionPath(),
					'wildcardParentID'	=> (!((bool) $_POST['resolveWildcards'])) ? null : (int) $_POST['wildcardParentID']
				));
				$model->save();
				
				$this->flash('Root Domain Created Successfully.');
				$this->redirect('dashboard/fluid_dns/manage_domain_routes');
			}catch(Exception $e){
				$this->flash($e->getMessage(), self::FLASH_TYPE_ERROR);
				$this->redirect('/dashboard/fluid_dns/manage_domain_routes/add');
			}
		}
		
		
		public function update( $id ){
			try {
				$model = FluidDnsRoute::getByID($id);
				$model->setProperties(array(
					'domain' 			=> $this->validateRootDomain($_POST['root_domain']),
					'path'	 			=> Page::getByID( (int) $_POST['pageID'] )->getCollectionPath(),
					'pageID' 			=> (int) $_POST['pageID'],
					'resolveWildcards'	=> (int) $_POST['resolveWildcards'],
					'wildcardRootPath'	=> (!((bool) $_POST['resolveWildcards'])) ? null : Page::getByID( (int) $_POST['wildcardParentID'] )->getCollectionPath(),
					'wildcardParentID'	=> (!((bool) $_POST['resolveWildcards'])) ? null : (int) $_POST['wildcardParentID']
				));
				$model->save();
				
				$this->flash('Root Domain Updated Successfully.');
				$this->redirect('dashboard/fluid_dns/manage_domain_routes');
			}catch(Exception $e){
				$this->flash($e->getMessage(), self::FLASH_TYPE_ERROR);
				$this->redirect('/dashboard/fluid_dns/manage_domain_routes/edit', $id);
			}
		}
		
		
		protected function getDomainsList(){
			$records = Loader::db()->GetArray("SELECT * FROM FluidDnsRoute");
			$domains = array();
			if(!empty($records)){
				foreach($records AS $row){
					$domains[ $row['domain'] ] = new FluidDnsRoute( $row );
				}
			}
			return $domains;
		}
		
		
		/**
		 * Crude root domain validation function. Ensures it is not prefixed
		 * with http://, www., a pre "."
		 */
		protected function validateRootDomain( $rootDomain ){
			return preg_replace(array('/(http:\/\/)/', '/(https:\/\/)/', '/www./', '/^\./'), '', $rootDomain);
		}
		
	}

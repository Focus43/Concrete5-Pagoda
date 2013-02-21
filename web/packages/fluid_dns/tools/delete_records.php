<?php defined('C5_EXECUTE') or die("Access Denied.");

	$permissions = new Permissions( Page::getByPath('/dashboard/fluid_dns/manage_domain_routes') );
	
	// does caller of this URL have access?
	if( $permissions->canView() ){
		if(!empty($_POST['domainID'])){
			foreach($_POST['domainID'] AS $domainID){
				FluidDnsRoute::getByID($domainID)->delete();
			}
		}
		
		echo Loader::helper('json')->encode( (object) array(
			'code'	=> 1,
			'msg'	=> 'Success'
		));
	}
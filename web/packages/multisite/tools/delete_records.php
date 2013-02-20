<?php defined('C5_EXECUTE') or die("Access Denied.");

	$permissions = new Permissions( Page::getByPath('/dashboard/multisite/manage') );
	
	// does caller of this URL have access?
	if( $permissions->canView() ){
		if(!empty($_POST['domainID'])){
			foreach($_POST['domainID'] AS $domainID){
				MultisiteDomain::getByID($domainID)->delete();
			}
		}
		
		echo Loader::helper('json')->encode( (object) array(
			'code'	=> 1,
			'msg'	=> 'Success'
		));
	}
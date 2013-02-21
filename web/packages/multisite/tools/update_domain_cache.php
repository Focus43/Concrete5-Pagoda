<?php defined('C5_EXECUTE') or die("Access Denied.");

	$permissions = new Permissions( Page::getByPath('/dashboard/multisite/manage') );
	
	// does caller of this URL have access?
	if( $permissions->canView() ){
		try {
			// delete the domain_paths hash key from Redis
			ConcreteRedis::db()->del('domain_paths');
			
			// reload *EVERY. SINGLE. DOMAIN. RECORD.*
			$records = Loader::db()->GetCol("SELECT id FROM MultisiteDomain");
			if(!empty($records)){
				foreach($records AS $msID){
					$msDomain = MultisiteDomain::getByID( $msID );
					$rootPage = Page::getByID( $msDomain->getPageID(), 'ACTIVE' );
					
					if( !($rootPage instanceof Page) || !$rootPage->isActive() ){
						throw new Exception("Root page for domain <strong>{$msDomain->getDomain()}</strong> is either in trash or deleted!");
					}
					
					// update the $msDomain object Root Page stuff
					$msDomain->setProperties(array(
						'path' => $rootPage->getCollectionPath()
					));
					
					// if wildcards are enabled, update the page path too
					if( (bool) $msDomain->getResolveWildcards() ){
						if( $msDomain->getWildcardParentID() >= 1 ){
							$wildcardRootPage = Page::getByID( $msDomain->getWildcardParentID() );
						
							if( !($wildcardRootPage instanceof Page) || !$wildcardRootPage->isActive() ){
								throw new Exception("Wildcard root page for domain <strong>{$msDomain->getDomain()}</strong> is either in trash or deleted!");
							}
							
							$msDomain->setProperties(array(
								'wildcardRootPath' => $wildcardRootPage->getCollectionPath()
							));
						}
					}
					 
					// by re-saving the domain object, it'll automatically populate the Redis cache
					$msDomain->save();
				}
			}
			
			// return success
			echo Loader::helper('json')->encode( (object) array(
				'code'	=> 1,
				'msg'	=> 'Success'
			));
		}catch(Exception $e){
			// return success
			echo Loader::helper('json')->encode( (object) array(
				'code'	=> 0,
				'msg'	=> $e->getMessage()
			));
		}

	}
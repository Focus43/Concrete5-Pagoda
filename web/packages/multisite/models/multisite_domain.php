<?php

	class MultisiteDomain {
		
		protected $id, 
				  $domain, 
				  $path, 
				  $pageID, 
				  $resolveWildcards, 
				  $wildcardRootPath, 
				  $wildcardParentID;
		
		public function getID(){ return $this->id; }
		public function getDomain(){ return $this->domain; }
		public function getPath(){ return $this->path; }
		public function getPageID(){ return $this->pageID; }
		public function getResolveWildcards(){ return $this->resolveWildcards; }
		public function getWildcardRootPath(){ return $this->wildcardRootPath; }
		public function getWildcardParentID(){ return $this->wildcardParentID; }
		
		
		public function __construct( array $params = array() ){
			foreach($params AS $prop => $val){
				$this->{$prop} = $val;
			}
		}
		
		
		public static function getByID( $id ){
			$row = Loader::db()->GetRow("SELECT * FROM MultisiteDomain WHERE id = ?", array( (int)$id ));
			return new self( $row );
		}
		
		
		public function save(){
			Loader::db()->Execute("INSERT INTO MultisiteDomain (domain, path, pageID, resolveWildcards, wildcardRootPath, wildcardParentID) VALUES (?, ?, ?, ?, ?, ?)", array(
				$this->domain, $this->path, $this->pageID, $this->resolveWildcards, $this->wildcardRootPath, $this->wildcardParentID
			));
			ConcreteRedis::db()->hset( 'domain_paths', $this->domain, $this->serializeJson() );
		}
		
		
		public function delete(){
			Loader::db()->Execute("DELETE FROM MultisiteDomain WHERE id = ?", array( $this->id ));
			ConcreteRedis::db()->hdel( 'domain_paths', $this->domain );
		}
		
		
		protected function serializeJson(){
			return json_encode(array(
				'path'   			=> $this->path,
				'resolveWildcards' 	=> $this->resolveWildcards,
				'wildcardRootPath' 	=> $this->wildcardRootPath
			));
		}
		
	}

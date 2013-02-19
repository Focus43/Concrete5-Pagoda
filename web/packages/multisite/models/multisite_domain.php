<?php

	class MultisiteDomain {
		
		protected $id, $domain, $path;
		
		public function getID(){ return $this->id; }
		public function getDomain(){ return $this->domain; }
		public function getPath(){ return $this->path; }
		
		public function __construct( array $params = array() ){
			foreach($params AS $prop => $val){
				$this->{$prop} = $val;
			}
		}
		
		
		public static function getList(){
			$records = Loader::db()->GetArray("SELECT * FROM MultisiteDomain");
			$domains = array();
			if(!empty($records)){
				foreach($records AS $row){
					$domains[] = new self( $row );
				}
			}
			return $domains;
		}
		
		
		public function save(){
			Loader::db()->Execute("INSERT INTO MultisiteDomain (domain, path) VALUES (?, ?)", array(
				$this->domain, $this->path
			));
		}
		
	}

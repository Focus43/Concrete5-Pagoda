<?php defined('C5_EXECUTE') or die("Access Denied.");

	class DashboardSystemOptimizationRedisCacheController extends Controller {
		
		public $helpers = array('form');
		
		
		public function view(){
			$redisDB = ConcreteRedis::db();
			$keys 	 = $redisDB->keys('*');
			$this->set('cacheKeys', $this->getOverview());
		}
		
		
		public function explore_hash( $redisKey ){
			$redisDB = ConcreteRedis::db();
			if( $redisDB->type( $redisKey ) == 'hash' ){
				$hashKeys = $redisDB->hkeys( $redisKey );
				$this->set('hashName', $redisKey);
				$this->set('hashKeys', $hashKeys);
			}
		}
		
		
		// explore the data in a specific hash key
		public function explore_key( $key, $hashName = null ){
			$this->set('redisKey', $key);
			
			// we're exploring a hash key
			if( !($hashName === null) ){
				$this->set('hashName', $hashName);
				$keyData = ConcreteRedis::db()->hget( $hashName, $key );
			
			// otherwise, we're just exploring a string key
			}else{
				$keyData = ConcreteRedis::db()->get( $key );
			}
			
			// pass stuff to the view
			$this->set('keyData', unserialize($keyData) );
		}
		
		
		protected function getOverview(){
			$redisDB = ConcreteRedis::db();
			$keys 	 = $redisDB->keys('*');
			
			$data = array();
			if(!empty($keys)){
				foreach($keys AS $cKey){
					$keyType 	= $redisDB->type($cKey);
					
					switch($keyType){
						case 'hash':
							$hashLength = $redisDB->hlen($cKey) . ' Hash Key(s)';
							break;
						case 'string':
							$hashLength = $redisDB->strlen($cKey) . ' characters';
							break;
					}
					
					$data[$cKey] = (object) array(
						'type' => $keyType,
						'len'  => $hashLength
					);
				}
			}
			
			return $data;
		}
		
	}

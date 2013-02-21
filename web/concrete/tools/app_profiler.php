<?php defined('C5_EXECUTE') or die("Access Denied."); // @app_profiler

	try {
		$profileDataPath = ApplicationProfiler::getFileStore( $_REQUEST['id'] );
		$profileData 	 = unserialize( Loader::helper('file')->getContents( $profileDataPath ) );
		$profile 		 = new ApplicationProfilerReport($profileData);
		Loader::element('application_profiler', array('profile' => $profile));
		
	}catch(Exception $e){  }
	
	
	class ApplicationProfilerReport {
		
		public $profilerStartTime,
			   $profilerEndTime;
		
		protected $logCollection;
		
		
		public function __construct( array $logCollection ){
			$this->logCollection = $logCollection;
			$this->profileQueries();
			$this->profilerStartTime = $this->getLogObject('Memory', 'profiler_start')->occurred;
			$this->profilerEndTime	 = $this->getLogObject('Memory', 'profiler_end')->occurred;
		}
		
		
		public function countLogsByType( $type ){
			return count( $this->getLogsByType($type) );
		}
		
		
		public function getLogsByType( $type ){
			return $this->logCollection[ "log{$type}" ];
		}
		
		
		public function calcRenderTime(){
			return $this->elapsedTime($this->profilerEndTime);
		}
		
		
		public function getMemorySnapshot( $handle ){
			return $this->getReadableFileSize( $this->getLogObject('Memory', $handle)->data );
		}
		
		
		public function getLogObject( $type, $handle ){
			return $this->logCollection[ "log{$type}" ][ $handle ];
		}
		
		
		/**
		 * Calc the time different between two time marks
		 * @param int $end
		 * @param int $start Optional, if not passed, defaults to start time of the profiler
		 * @return float
		 */
		public function elapsedTime( $end, $start = null ){
			if( is_null($start) ){
				$start = $this->profilerStartTime;
			}
			return number_format($end - $start, 4);
		}
		
		
		/**
		 * Pass in a number and output legibly
		 * @param float
		 * @return string
		 */
		public function getReadableFileSize($size, $retstring = null) {
        	// adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
	       $sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

		   if ($retstring === null) { $retstring = '%01.2f %s'; }
	
			$lastsizestring = end($sizes);
	
			foreach ($sizes as $sizestring) {
	       		if ($size < 1024) { break; }
	           	if ($sizestring != $lastsizestring) { $size /= 1024; }
	       	}
		    
		    if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
		    
		    return sprintf($retstring, $size, $sizestring);
		}
		
		
		/**
		 * Profile the query data. This takes the queries that were logged,
		 * actually runs them if they're selects (to benchmark the time), and
		 * changes the data in the logCollection accordingly.
		 * @return void
		 */
		public function profileQueries(){			
			$queries = $this->getLogsByType('Database');
			
			if( !empty($queries) ){
				$db = Loader::db();
				
				foreach($queries AS $key => $obj){
					
					$data = $obj->data;
					
					// what type of query is it? (select, insert, update, delete)
					$queryType = strtolower(array_shift(explode(' ', trim($data['sql']))));
					
					// if its a select query, then run it again with Explain
					if( $queryType == 'select' ){
						// set start time before query is run
						$startTime = microtime(true);
						
						// execute the query
						$executed = $db->Execute("EXPLAIN {$data['sql']}", $data['input']);
						
						// log total execution time
						$executionTime = number_format(microtime(true) - $startTime, 6);
					}else{
						$executed = new stdClass();
						$executed->sql = $data['sql'];
						$executed->fields = null;
						$executionTime = null;
					}
					
					$this->logCollection['logDatabase'][$key]->data = (object) array(
						'type'		=> $queryType,
						'sql'		=> str_replace('EXPLAIN ', '', $executed->sql),
						'time'		=> $executionTime,
						'explain'	=> $executed->fields
					);
				}
			}
		}
	}

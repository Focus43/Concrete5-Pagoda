<?php defined('C5_EXECUTE') or die("Access Denied."); // @app_profiler


	/**
	 * Hijack ADODB's query logger function; proxy to the ApplicationProfiler.
	 * This has to be *global* because of how its' called by ADODB.
	 */
	function ApplicationProfilerQueryLogger(&$connx, $sql, $inputarr){
		$connx->fnExecute = false;
		ApplicationProfiler::console()->logDatabase(array(
			'sql' 	=> $sql,
			'input' => $inputarr
		));
		$connx->fnExecute = "ApplicationProfilerQueryLogger";
	}
	
	
	final class ApplicationProfilerCatchall {
		public function __call($a, $b){ /* DO NOTHING */ }
		public static function __callStatic($a, $b){ /* DO NOTHING */ }
	}
	

	
	/**
	 * Application profiler class (implemented as ApplicationProfiler). Use this
	 * to benchmark and profile your C5 Application. Generally intended for
	 * developers and advanced nerds only.
	 * 
	 * @package Core
	 * @author Jonathan Hartman <jon@focus-43.com>
	 * @category Concrete
	 * @copyright  Copyright (c) 2013 Concrete5. (http://www.concrete5.org)
	 * @license    http://www.concrete5.org/license/     MIT License
	 */
	abstract class Concrete5_Library_ApplicationProfiler {
		
		/** @var ApplicationProfiler || ApplicationProfilerCatchall $instance */
		protected static $instance;
		
		/** @var array $logCollection */
		protected $logCollection = array();
		
		/** @var int $timerCounter Count time marker log calls */
		protected $timerCounter = 1;
		
		/** @var int $includesCounter Count include log calls */
		protected $includesCounter = 1;
		
		/** @var int $exceptionsCounter Count exception log calls */
		protected $exceptionsCounter = 1;
		
		/** @var int $memoryCounter Count memory log calls */
		protected $memoryCounter = 1;
		
		/** @var int $consoleCounter Count console log calls */
		protected $consoleCounter = 1;
		
		/** @var int $queryCounter Count database query log calls */
		protected $queryCounter = 1;
		
		
		
		private function __construct(){
			// setup $logCollection (create subarrays for each method name
			// beginning with "log...")
			$capturable = get_class_methods(ApplicationProfiler);
			foreach( $capturable AS $method ){
				if( strpos($method, 'log') === 0 ){
					$this->logCollection[$method] = array();
				}
			}
			
			// create the first logs
			$this->logMemory('Profiler Start', 'profiler_start');
			$this->logFileIncludeSnapshot('Includes when app profile starts', 'profiler_start');
		}
		
		
		/**
		 * @return ApplicationProfiler
		 */
		final public static function console(){
			return self::$instance;
		}
		
		
		
		/**
		 * Start the Application Profiler?
		 * @param bool Enable it?
		 * @return void
		 */
		final public static function start( $enabled = false ){
			if( $enabled === true ){
				self::$instance = new ApplicationProfiler;
				return;
			}
			self::$instance = new ApplicationProfilerCatchall;
		}
		
		
		/**
		 * Enable MySQL query logging.
		 * @param ADODB object
		 * @return void
		 */
		final public static function enableQueryLogging( $adodb ){
			if( !(self::$instance instanceof ApplicationProfiler) ){ return; }
			
			$adodb->LogSQL(true);
			$adodb->fnExecute = "ApplicationProfilerQueryLogger";
		}
		
		
		/**
		 * Wrap up profiling. Argument accepts either an instance of
		 * 'Controller' or 'PageCacheRecord', and will set accordingly. If the page
		 * has been cached in FullPageCache, we search the compiled html and update
		 * the string with regexes!
		 * @param Controller || PageCacheRecord $controllerOrCache
		 * @return void
		 */
		final public static function finish( $controllerOrCache ){
			if( !(self::$instance instanceof ApplicationProfiler) ){ return; }
			
			// set the md5 hash
			$md5 = md5( microtime(true) . mt_rand(0,9999999) . gettype($controllerOrCache) );
			
			// setup the meta tag to add to page, and the javascript file
			$metaTagString 	= '<meta id="c5App-Profiler" value="'.$md5.'" data-url="'.self::getToolsFileURL().'" />';
			$jsFileString	= Loader::helper('html')->javascript('app_profiler.js');
			
			// is this a page cache record? then modify the content string (actual html file)
			if( $controllerOrCache instanceof PageCacheRecord ){
				$pageHTML = $controllerOrCache->content;
				// does the meta tag already exist on the page? then modify it...
				if(preg_match('/<meta id="c5App-Profiler"(.*?)\/>/', $pageHTML)){
					$controllerOrCache->content = preg_replace('/<meta id="c5App-Profiler"(.*?)\/>/', $metaTagString, $controllerOrCache->content);
				
				// doesn't exist yet (for some reason), so add it here
				}else{
					$controllerOrCache->content = str_replace('</body>', $metaTagString . $jsFileString . '</body>', $pageHTML);
				}
				
				// add to log to indicate this was a cached page
				self::$instance->logMixed('Yes', 'Full Page Cached?', 'page_cache_status');
				self::$instance->logMixed( PAGE_CACHE_LIBRARY, 'Page Cache Library' );
				
			// if the page hasn't been cached yet, just use the controller's addFooterItem
			}else{
				$controllerOrCache->addFooterItem( $metaTagString );
				$controllerOrCache->addFooterItem( $jsFileString );
				
				// add to log to indicate this was a cached page
				self::$instance->logMixed('No', 'Full Page Cached?', 'page_cache_status');
			}
			
			// mark end points
			self::$instance->logMemory('Profiler Stop, Rendering Page', 'profiler_end');
			self::$instance->logFileIncludeSnapshot('All file includes at page render', 'profiler_end');
			
			// if Redis is available, store it in Redis instead of a flat file
			if( class_exists('ConcreteRedis') ){
				ConcreteRedis::db()->hset( 'c5_app_profiler', $md5, serialize(self::$instance->logCollection) );
				return;
			}
			
			// if we get here, we're storing in a flat file
			$store = self::getFileStore( $md5 );
			Loader::helper('file')->append( $store, serialize( self::$instance->logCollection ) );
		}


		/**
		 * If full page is cached, constants aren't loaded in the URLs helper.
		 * @return string
		 */
		protected static function getToolsFileURL(){
			if (URL_REWRITING_ALL === true) {
				return DIR_REL . '/tools/required/app_profiler';
			}
			return DIR_REL . '/' . DISPATCHER_FILENAME . '/tools/required/app_profiler';
		}


		/**
		 * Get (and/or create the necessary directory) the path to the profiler data store.
		 * We use the /files directory, and create a /profiler directory, where serialized
		 * representations of the data are stored.
		 * @param strin $fileName optionally, pass in a file name to auto-append
		 * @return string
		 */
		public static function getFileStore( $fileName = '' ){
			if( !is_dir(DIR_BASE . '/files/cache/profiler') ){
				@mkdir(DIR_BASE . '/files/cache/profiler', DIRECTORY_PERMISSIONS_MODE);
				@chmod(DIR_BASE . '/files/cache/profiler', DIRECTORY_PERMISSIONS_MODE);
				@touch(DIR_BASE . '/files/cache/profiler/index.html');
			}
			
			if( is_dir(DIR_BASE . '/files/cache/profiler') && is_writable(DIR_BASE . '/files/cache/profiler') ){
				return $fileName != '' ? DIR_BASE . "/files/cache/profiler/$fileName" : DIR_BASE . '/files/cache/profiler';
			}
		}
		
		/******************************* LOGGING METHODS **************************/
		
		/**
		 * Log anything to the console.
		 * @param mixed  $thing Pass in anything and it'll be logged
		 * @param string $label Human readable
		 * @param string $handle underscored_identifier
		 * @return void
		 */
		public function logMixed( $thing, $label, $handle = null ){
			$handle = $handle ? $handle : 'console_log_' . $this->consoleCounter++;
			$this->logCollection[ __FUNCTION__ ][ $handle ] = (object) array(
				'data'		=> print_r($thing, true),
				'label'		=> $label,
				'occurred'	=> microtime(true),
				'type'		=> gettype($thing)
			);
		}
		
		
		/**
		 * Log memory in use at the point in time this is called.
		 * @param string $label Human readable
		 * @param string $handle underscored_identifier
		 * @return void
		 */
		public function logMemory( $label = 'Memory snapshot', $handle = null ){
			$handle = $handle ? $handle : 'memory_snapshot_' . $this->memoryCounter++;
			$this->logCollection[ __FUNCTION__ ][ $handle ] = (object) array(
				'data'		=> memory_get_usage(),
				'label'		=> $label,
				'occurred'	=> microtime(true)
			);
		}
		
		
		/**
		 * Log an exception that occurred within the application.
		 * @param Exception $e
		 * @param string $handle underscored_identifier
		 * @return void
		 */
		public function logException( Exception $e, $handle = null ){
			$handle = $handle ? $handle : 'exception_' . $this->exceptionsCounter++;
			$this->logCollection[ __FUNCTION__ ][ $handle ] = (object) array(
				'data'		 => (object) array(
					'object' => print_r($e, true),
					'file'	 => $e->getFile(),
					'line'	 => $e->getLine()
				),
				'label'		=> "Message: {$e->getMessage()}",
				'occurred'	=> microtime(true)
			);
		}
		
		
		/**
		 * Log a mysql query. This *just* logs the query and the data passed into it ($queryData),
		 * but doesn't actually rerun the query. To benchmark the queries, we actually rerun
		 * any *selects* (only, no insert, update, or deletes) upon displaying profiler results.
		 * ADODB uses the global ApplicationProfilerQueryLogger (declared above) to proxy query
		 * logs to this method.
		 * @param array $queryData Results from ADODB
		 * @param string $label Human readable
		 * @param string $handle underscored_identifier
		 * @return void
		 */
		public function logDatabase( array $queryData = array(), $label = 'Query', $handle = null ){
			$handle = $handle ? $handle : 'db_query_' . $this->queryCounter++;
			$this->logCollection[ __FUNCTION__ ][ $handle ] = (object) array(
				'data'		=> $queryData,
				'label'		=> $label,
				'occurred'	=> microtime(true)
			);
		}
		
		
		/**
		 * Log the files included by the application, up to point in time when
		 * this method gets called.
		 * @param string $label Human readable
		 * @param string $handle underscored_identifier
		 * @return void
		 */
		public function logFileIncludeSnapshot( $label = 'Included files snapshot', $handle = null ){
			$handle = $handle ? $handle : 'includes_snapshot_' . $this->includesCounter++;
			$this->logCollection[ __FUNCTION__ ][ $handle ] = (object) array(
				'data'		=> get_included_files(),
				'label'		=> $label,
				'occurred'	=> microtime(true)
			);
		}
		
	}

<?php defined('C5_EXECUTE') or die("Access Denied.");
	
	
	/**
	 * Schedulizer block controller.
	 */
	class SchedulizerCalendarBlockController extends BlockController {

		protected $btTable 									= 'btSchedulizerCalendar';
		protected $btInterfaceWidth 						= '585';
		protected $btInterfaceHeight						= '440';
		protected $btCacheBlockRecord 						= false;
		protected $btCacheBlockOutput 						= false;
		protected $btCacheBlockOutputOnPost 				= false;
		protected $btCacheBlockOutputForRegisteredUsers 	= false;
		protected $btCacheBlockOutputLifetime 				= CACHE_LIFETIME;

        protected $blockData;

        /**
         * @return string
         */
        public function getBlockTypeDescription(){
			return t("Display Schedulizer Calendars");
		}


        /**
         * @return string
         */
        public function getBlockTypeName(){
			return t("Schedulizer Calendar");
		}


        public function on_page_view(){
            // output function to execute deferreds
            $this->addFooterItem('<script type="text/javascript">'.Loader::helper('file')->getContents(DIR_PACKAGES . '/schedulizer/' . DIRNAME_BLOCKS . '/schedulizer_calendar/inline_script.js.txt').'</script>');
        }


        /**
         * View action for the block.
         */
        public function view(){
            // right now everything is handled via js / ajax
		}


        /**
         * Edit action.
         */
        public function add(){
            $this->edit();
        }

        public function edit(){
            $this->set('calendarList', $this->calendarListObj()->get());
            $this->set('blockData', $this->blockData());
        }


        /**
         * @return stdClass
         */
        public function blockData(){
            if( $this->_parsedBlockData === null ){
                $this->_parsedBlockData = (object) Loader::helper('json')->decode( $this->blockData );
            }
            return $this->_parsedBlockData;
        }


        /**
         * @return SchedulizerCalendarList
         */
        protected function calendarListObj(){
            if( $this->_calendarListObj === null ){
                $this->_calendarListObj = new SchedulizerCalendarList;
            }
            return $this->_calendarListObj;
        }
		
		
		public function save( $data ){
            $args['blockData'] = Loader::helper('json')->encode( (object)$_REQUEST['btcal'] );
			parent::save( $args );
		}
		
	}
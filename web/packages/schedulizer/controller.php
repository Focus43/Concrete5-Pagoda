<?php defined('C5_EXECUTE') or die(_("Access Denied."));
	
	class SchedulizerPackage extends Package {

        const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';
        // config keys
        const DEFAULT_TIMEZONE = 'DEFAULT_TIMEZONE';

	    protected $pkgHandle 			= 'schedulizer';
	    protected $appVersionRequired 	= '5.6.1';
	    protected $pkgVersion 			= '0.22';


        /**
         * @return string
         */
        public function getPackageName(){
	        return t('Schedulizer');
	    }


        /**
         * @return string
         */
        public function getPackageDescription() {
	        return t('Schedulizer');
	    }


        /**
         * On_start hook; provide constants for run + autoload classes.
         */
        public function on_start(){
	        define('SCHEDULIZER_TOOLS_URL', BASE_URL . REL_DIR_FILES_TOOLS_PACKAGES . '/' . $this->pkgHandle . '/');
			
			Loader::registerAutoload(array(
                'SchedulizerController'     => array('library', 'schedulizer_controller', $this->pkgHandle),
				'SchedulizerBaseModel'      => array('library', 'base_model', $this->pkgHandle),
                'SchedulizerCalendar'       => array('model', 'calendar/calendar', $this->pkgHandle),
                'SchedulizerCalendarAttributeKey,SchedulizerCalendarAttributeValue' => array('model', 'attribute/categories/schedulizer_calendar', $this->pkgHandle),
                'SchedulizerCalendarList'   => array('model', 'calendar/calendar_list', $this->pkgHandle),
                'SchedulizerCalendarColumnSet' => array('model', 'calendar/column_set', $this->pkgHandle),
                'SchedulizerCalendarDefaultColumnSet' => array('model', 'calendar/default_column_set', $this->pkgHandle),
                'SchedulizerEvent'          => array('model', 'event/event', $this->pkgHandle),
                'SchedulizerEventRepeat'    => array('model', 'event/event_repeat', $this->pkgHandle),
                'SchedulizerEventList'      => array('model', 'event/event_list', $this->pkgHandle),
                'SchedulizerEventColors'    => array('library', 'event_colors', $this->pkgHandle),
                'SchedulizerSaveEventProcessor' => array('library', 'save_event_processor', $this->pkgHandle),
                'SchedulizerUtilitiesEventJsonFormatter' => array('library', 'utilities/event_json_formatter', $this->pkgHandle)
			));

            // hooks
            if( User::isLoggedIn() ){
                Events::extend('schedulizer_calendar_save', 'SchedulizerOnCalendarSave', 'updateEventTimezones', "packages/{$this->pkgHandle}/libraries/system_event_hooks/on_calendar_save.php");
            }
	    }


        /**
         * Uninstall the Schedulizer package.
         * @return void
         */
        public function uninstall() {
	        parent::uninstall();
			
			try {
				// delete mysql tables
				$db = Loader::db();
				$db->Execute("DROP TABLE SchedulizerCalendar");
                $db->Execute("DROP TABLE SchedulizerCalendarAttributeValues");
                $db->Execute("DROP TABLE SchedulizerEvent");
                $db->Execute("DROP TABLE SchedulizerEventRepeat");
                $db->Execute("DROP TABLE SchedulizerEventRepeatNullify");
                $db->Execute("DROP TABLE SchedulizerCalendarSearchIndexAttributes");
			}catch(Exception $e){
				// fail gracefully
			}
	    }


        /**
         * Run before install or upgrade to ensure dependencies and version minimums are ok.
         * @note: tests for PHP version minimum, and for MySQL timezone tables.
         * @return true
         */

        private function checkDependencies(){
            // ensure minimium php version
            if( !( (float) phpversion() >= 5.3 ) ){
                throw new Exception(t("Schedulizer requires PHP version >= 5.3; you're running %s", phpversion()));
            }

            // timezone tables presence test (if present, MySQL will convert correctly).
            $testConversion = Loader::db()->GetOne("SELECT CONVERT_TZ('2001-01-17 12:00:00', 'UTC', 'America/New_York')");
            if( is_null($testConversion) || $testConversion !== '2001-01-17 07:00:00' ){
                throw new Exception('Schedulizer requires that MySQL have timezone tables installed, which they appear not to be. Please contact your hosting provider or an administrator.');
            }

            // take MySQL conversion results and instantiate Datetime, then test its conversion
            $dateTimeObject = new DateTime($testConversion, new DateTimeZone('America/New_York'));
            $dateTimeObject->setTimezone(new DateTimeZone('America/Denver'));
            if( $dateTimeObject->format('Y-m-d H:i:s') !== '2001-01-17 05:00:00' ){
                throw new Exception('The DateTime class in PHP is not making correct conversions. Please ensure your version is >= 5.3.');
            }

            // now test for relative word support (ordinals) for DateTime classes
            $dateTimeObject->modify('first day of this month');
            if( $dateTimeObject->format('Y-m-d') !== '2001-01-01' ){
                throw new Exception('Your PHP version/installation does not support DateTime ordinals (relative) words. Please ensure your version is >= 5.3.');
            }

            return true;
        }


        /**
         * @return void
         */
        public function upgrade(){
            if( $this->checkDependencies() ){
                parent::upgrade();
                $this->installAndUpdate();
            }
	    }


        /**
         * @return void
         */
        public function install() {
            if( $this->checkDependencies() ){
                $this->_packageObj = parent::install();
                $this->installAndUpdate();
            }
	    }


        /**
         * @return void
         */
        private function installAndUpdate(){
			$this->defaultSettings()
                 ->setupBlocks()
                 ->setupSinglePages()
                 ->registerEntityCategories();
		}


        /**
         * @return SchedulizerPackage
         */
        private function defaultSettings(){
            $this->saveConfig( self::DEFAULT_TIMEZONE, 'America/New_York' );

            return $this;
        }


        /**
         * @return SchedulizerPackage
         */
        private function setupBlocks(){
            if(!is_object(BlockType::getByHandle('schedulizer_calendar'))) {
                BlockType::installBlockTypeFromPackage('schedulizer_calendar', $this->packageObject());
            }

            return $this;
        }
		
		
		/**
		 * Install necessary single pages.
         * @return SchedulizerPackage
		 */
		private function setupSinglePages(){
			SinglePage::add('/dashboard/schedulizer', $this->packageObject());

            // calendars
			$calendar = SinglePage::add('/dashboard/schedulizer/calendars', $this->packageObject());
            if( $calendar instanceof Page ){
                $calendar->setAttribute('icon_dashboard', 'icon-calendar');
            }

            // calendars/search (no icon for subpage)
            SinglePage::add('/dashboard/schedulizer/calendars/search', $this->packageObject());

            // attributes
            $attributes = SinglePage::add('/dashboard/schedulizer/attributes', $this->packageObject());
            if( $attributes instanceof Page ){
                $attributes->setAttribute('icon_dashboard', 'icon-cog');
            }

            // settings
            $attributes = SinglePage::add('/dashboard/schedulizer/settings', $this->packageObject());
            if( $attributes instanceof Page ){
                $attributes->setAttribute('icon_dashboard', 'icon-wrench');
            }
			
			return $this;
		}


        /**
         * @return SchedulizerPackage
         */
        private function registerEntityCategories(){
            if( !($this->attributeKeyCategory('schedulizer_calendar') instanceof AttributeKeyCategory) ){
                $calendarAkc = AttributeKeyCategory::add('schedulizer_calendar', AttributeKeyCategory::ASET_ALLOW_MULTIPLE, $this->packageObject());
                $calendarAkc->associateAttributeKeyType( $this->attributeType('text') );
                $calendarAkc->associateAttributeKeyType( $this->attributeType('boolean') );
                $calendarAkc->associateAttributeKeyType( $this->attributeType('number') );
                $calendarAkc->associateAttributeKeyType( $this->attributeType('textarea') );
                $calendarAkc->associateAttributeKeyType( $this->attributeType('select') );
                $calendarAkc->associateAttributeKeyType( $this->attributeType('date_time') );
                $calendarAkc->associateAttributeKeyType( $this->attributeType('image_file') );
            }

            return $this;
        }


        /**
		 * Get the package object; if it hasn't been instantiated yet, load it.
		 * @return SchedulizerPackage
		 */
		private function packageObject(){
			if( $this->_packageObj == null ){
				$this->_packageObj = Package::getByHandle( $this->pkgHandle );
			}
			return $this->_packageObj;
		}


        /**
         * Get or create an attribute set, for a certain attribute key category (if passed).
         * Will automatically convert the $attrSetHandle from handle_form_name to Handle Form Name
         * @param string $attrSetHandle
         * @param string $attrKeyCategory
         * @return AttributeSet
         */
        private function getOrCreateAttributeSet( $attrSetHandle, $attrKeyCategory = null ){
            if( $this->{ 'attr_set_' . $attrSetHandle } === null ){
                // try to load an existing Attribute Set
                $attrSetObj = AttributeSet::getByHandle( $attrSetHandle );

                // doesn't exist? create it, if an attributeKeyCategory is passed
                if( !is_object($attrSetObj) && !is_null($attrKeyCategory) ){
                    // ensure the attr key category can allow multiple sets
                    $akc = AttributeKeyCategory::getByHandle( $attrKeyCategory );
                    $akc->setAllowAttributeSets( AttributeKeyCategory::ASET_ALLOW_MULTIPLE );

                    // *now* add the attribute set
                    $attrSetObj = $akc->addSet( $attrSetHandle, t( $this->getHelper('text')->unhandle($attrSetHandle) ), $this->packageObject() );
                }

                // assign the $attrSetObj
                $this->{ 'attr_set_' . $attrSetHandle } = $attrSetObj;
            }

            return $this->{ 'attr_set_' . $attrSetHandle };
        }


        /**
         * Get an attribute key category object (eg: an entity category) by its handle
         * @return AttributeKeyCategory
         */
        private function attributeKeyCategory( $handle ){
            if( !($this->{ "akc_{$handle}" } instanceof AttributeKeyCategory) ){
                $this->{ "akc_{$handle}" } = AttributeKeyCategory::getByHandle( $handle );
            }
            return $this->{ "akc_{$handle}" };
        }


        /**
         * @return AttributeType
         */
        private function attributeType( $atHandle ){
            if( $this->{ "at_{$atHandle}" } === null ){
                $this->{ "at_{$atHandle}" } = AttributeType::getByHandle( $atHandle );
            }
            return $this->{ "at_{$atHandle}" };
        }
	    
	}

<?php

    class SchedulizerEvent extends SchedulizerBaseModel {

        const   // timezone overrides
                USE_CALENDAR_TIMEZONE_TRUE      = 1,
                USE_CALENDAR_TIMEZONE_FALSE     = 0,
                // all day booleans
                ALL_DAY_TRUE                    = 1,
                ALL_DAY_FALSE                   = 0,
                // is recurring booleans
                IS_REPEATING_TRUE               = 1,
                IS_REPEATING_FALSE              = 0,
                // indefinite?
                REPEAT_INDEFINITE_TRUE          = 1,
                REPEAT_INDEFINITE_FALSE         = 0,
                // repeat monthly (specific day or "3rd {monday}"
                REPEAT_MONTHLY_SPECIFIC_DATE    = 1,
                REPEAT_MONTHLY_WEEK_AND_DAY     = 0,
                // frequency handle
                REPEAT_TYPE_HANDLE_DAILY        = 'daily',
                REPEAT_TYPE_HANDLE_WEEKLY       = 'weekly',
                REPEAT_TYPE_HANDLE_MONTHLY      = 'monthly',
                REPEAT_TYPE_HANDLE_YEARLY       = 'yearly',
                // alias? (only used when editing recurring events that are not the original)
                IS_ALIAS_TRUE                   = 1,
                IS_ALIAS_FALSE                  = 0;


        // defaults
        protected $attrCategoryHandle  = 'schedulizer_event',
                  $useCalendarTimezone = self::USE_CALENDAR_TIMEZONE_TRUE,
                  $isAllDay            = self::ALL_DAY_FALSE,
                  $isRepeating         = self::IS_REPEATING_FALSE,
                  $repeatTypeHandle    = self::REPEAT_TYPE_HANDLE_DAILY,
                  $repeatIndefinite    = self::REPEAT_INDEFINITE_TRUE,
                  $repeatMonthlyMethod = self::REPEAT_MONTHLY_SPECIFIC_DATE,
                  $colorHex            = SchedulizerEventColors::GREEN, // see EventColorsHelper for available values
                  $isAlias             = self::IS_ALIAS_FALSE;


        /**
         * Construct a new object; and optionally pass in a key => value array of parameters
         * to set properties during instantiation.
         * @param array $properties
         * @return SchedulizerEvent
         */
        public function __construct( array $properties = array() ){
            parent::__construct($properties);
            $this->tableName = __CLASS__;
        }


        /**
         * Magic method to print the title when 'echo' is run on Event object.
         * @return string
         */
        public function __toString(){
            return ucwords( $this->getTitle() );
        }


        /**
         * Get the event ID.
         * @return int || null
         */
        public function getEventID(){
            return $this->id;
        }


        /**
         * Get the event's calendar ID.
         * @return int || null
         */
        public function getCalendarID(){
            return $this->calendarID;
        }


        /**
         * Get the event title.
         * @return string
         */
        public function getTitle(){
            if( empty($this->title) && $this->id >= 1 ){
                return "Event {$this->id}";
            }
            return $this->title;
        }


        /**
         * Get the event description text.
         * @return string
         */
        public function getDescription(){
            return $this->description;
        }


        /**
         * Get the event start time as UTC timestamp.
         * @return string
         */
        public function getStartUTC(){
            return $this->startUTC;
        }


        /**
         * Get the event end time as UTC timestamp.
         * @return string
         */
        public function getEndUTC(){
            return $this->endUTC;
        }


        /**
         * Is this an all day event?
         * @return int
         */
        public function getIsAllDay(){
            return $this->isAllDay;
        }


        /**
         * Get useCalendarTimezone boolean.
         * @return int
         */
        public function getUseCalendarTimezone(){
            return $this->useCalendarTimezone;
        }


        /**
         * Get the event timezone name.
         * @return string
         */
        public function getTimezoneName(){
            return $this->timezoneName;
        }


        /**
         * Get the event color hex code.
         * @return string
         */
        public function getColorHex(){
            return $this->colorHex;
        }


        /**
         * Get "is it a recurring event?"
         * @return int
         */
        public function getIsRepeating(){
            return $this->isRepeating;
        }


        /**
         * Get the recurring frequency handle.
         * @return string
         */
        public function getRepeatTypeHandle(){
            return $this->repeatTypeHandle;
        }


        /**
         * How often does the event repeat?
         * @return int
         */
        public function getRepeatEvery(){
            return $this->repeatEvery;
        }


        /**
         * Does the event repeat forever?
         * @return int
         */
        public function getRepeatIndefinite(){
            return $this->repeatIndefinite;
        }


        /**
         * Get the recurring end time as UTC timestamp.
         * @return string
         */
        public function getRepeatEndUTC(){
            return $this->repeatEndUTC;
        }


        /**
         * @return int
         */
        public function getRepeatMonthlyMethod(){
            return $this->repeatMonthlyMethod;
        }


        /**
         * Get the User ID of the event owner (usually creator).
         * @return int
         */
        public function getOwnerID(){
            return $this->ownerID;
        }


        /**
         * The isAlias property is set dynamically when querying for recurring events!
         * @return bool
         */
        public function getIsAlias(){
            return $this->isAlias;
        }


        /**
         * The $aliasDate object passed in is a DateTime object w/ just the date set;
         * since this is an alias, its used to adjust the instance's startUTC property
         * to the aliasDate. Then, add the difference between the original startUTC and
         * endUTC.
         * @param bool $isAlias
         * @param string $aliasDate Only the date portion, eg: "2013-08-01"
         */
        public function setIsAlias( DateTime $startDTO ){
            $this->isAlias = true;
            $interval = $this->getStartDateTimeObj(false)->diff( $this->getEndDateTimeObj(false) );
            $this->startUTC = $this->getStartDateTimeObj(false)->setDate( $startDTO->format('Y'), $startDTO->format('m'), $startDTO->format('d') )->format(SchedulizerPackage::TIMESTAMP_FORMAT);
            $this->endUTC   = $this->getStartDateTimeObj(false)->add($interval)->format(SchedulizerPackage::TIMESTAMP_FORMAT);
        }


        /**
         * Return the start DateTime object. If startUTC property doesn't have a value
         * (= null), returns current time.
         * @param $localized bool Convert to local timezone for the event?
         * @return DateTime
         */
        public function getStartDateTimeObj( $localized = true ){
            $startDTO = new DateTime( $this->getStartUTC(), new DateTimeZone('UTC') );
            return $localized ? $startDTO->setTimezone( $this->getEventTimezoneObj() ) : $startDTO;
        }


        /**
         * Get the end DateTime object. If not set, defaults to the value from
         * getStartDateTimeObj().
         * @param $localized bool Convert to local timezone for the event?
         * @return DateTime
         */
        public function getEndDateTimeObj( $localized = true ){
            // if endUTC isn't set, return the startDateTimeObj
            if( $this->endUTC === null ){
                return $this->getStartDateTimeObj( $localized );
            }

            $endDTO = new DateTime( $this->getEndUTC(), new DateTimeZone('UTC') );
            return $localized ? $endDTO->setTimezone( $this->getEventTimezoneObj() ) : $endDTO;
        }


        /**
         * Get the recurring end DateTime object. If not set, defaults to
         * the value from getEndDateTimeObj().
         * @param $localized bool Convert to local timezone for the event?
         * @return DateTime
         */
        public function getRepeatEndDateTimeObj( $localized = true ){
            // if recurring end is unset, return startDateTimeObj
            if( $this->repeatEndUTC === null ){
                return $this->getEndDateTimeObj( $localized );
            }

            $recurringDTO = new DateTime( $this->getRepeatEndUTC(), new DateTimeZone('UTC') );
            return $localized ? $recurringDTO->setTimezone( $this->getEventTimezoneObj() ) : $recurringDTO;
        }


        /**
         * Get the event's timezone as a DateTimeZone object. Inheritance of the Calendar's default time
         * zone is *not* done in the database or programmatically; instead, every time the default
         * timezone gets updated, every event in the calendar *that uses the default timezone* also
         * gets updated.
         * @return DateTimeZone
         */
        public function getEventTimezoneObj(){
            if( $this->_eventTimezoneObj === null ){
                $this->_eventTimezoneObj = new DateTimeZone( $this->getTimezoneName() );
            }
            return $this->_eventTimezoneObj;
        }


        /**
         * Get the calendar object this event belongs to (memoized so its only loaded
         * once).
         * @return SchedulizerCalendar
         */
        public function calendarObject(){
            if( $this->_calendarObj === null ){
                $this->_calendarObj = SchedulizerCalendar::getByID( $this->calendarID );
            }
            return $this->_calendarObj;
        }


        /**
         * @return array || null
         */
        protected function _repeatSettings(){
            if( $this->_repeatData === null ){
                $this->_repeatData = Loader::db()->GetArray("SELECT * FROM SchedulizerEventRepeat WHERE eventID = ?", array($this->getEventID()));
            }
            return $this->_repeatData;
        }


        /**
         * @param int $dayIndex
         * @return bool
         */
        public function weeklyRepeatIsChecked( $dayIndex ){
            foreach($this->_repeatSettings() AS $row){
                if( (int)$row['repeatWeekday'] === $dayIndex ){
                    return true;
                }
            }
            return false;
        }


        /**
         * @return int
         */
        public function getMonthlyRepeatWeek(){
            $repeatSettings = $this->_repeatSettings();
            return $repeatSettings[0]['repeatWeek'];
        }


        /**
         * @return int
         */
        public function getMonthlyRepeatWeekday(){
            $repeatSettings = $this->_repeatSettings();
            return $repeatSettings[0]['repeatWeekday'];
        }


        /**
         * @return int
         */
        public function getMonthlyRepeatSpecificDay(){
            $repeatSettings = $this->_repeatSettings();
            return $repeatSettings[0]['repeatDay'];
        }


        /**
         * Get the list of white-labeled properties which can be persisted to the db.
         * @return array
         */
        protected function persistable(){
            return array('calendarID', 'title', 'description', 'startUTC', 'endUTC', 'isAllDay', 'useCalendarTimezone',
            'timezoneName', 'colorHex', 'isRepeating', 'repeatTypeHandle', 'repeatEvery',
            'repeatIndefinite', 'repeatEndUTC', 'repeatMonthlyMethod', 'ownerID');
        }


        /**
         * Save an event model in its current state.
         * @return SchedulizerEvent
         */
        public function save(){
            $this->persistToDatabase();
            // @todo: save attributes
            $self = self::getByID( $this->id );
            Events::fire('schedulizer_event_save', $self);
            return $self;
        }


        /**
         * Get an event object by its' ID.
         * @return SchedulizerEvent
         */
        public static function getByID( $id ){
            $self = new self();
            $row  = Loader::db()->GetRow("SELECT * FROM {$self->tableName} WHERE id = ?", array((int)$id));
            $self->setPropertiesFromArray($row);
            return $self;
        }


        /**
         * Delete the record and any associated attribute values.
         * @return void
         */
        public function delete(){
            $db = Loader::db();
            $db->Execute("DELETE FROM SchedulizerEventRepeat WHERE eventID = ?", array($this->id));
            $db->Execute("DELETE FROM SchedulizerEventRepeatNullify WHERE eventID = ?", array($this->id));
            $db->Execute("DELETE FROM {$this->tableName} WHERE id = ?", array($this->id));
        }


        /* Attribute association stuff
        ----------------------------------------------------------------------*/
        public function clearAttribute($ak){
            parent::clearAttribute($ak);
        }


        public function setAttribute($ak, $value) {
            parent::setAttribute($ak, $value);
        }


        public function getAttribute($ak, $displayMode = false) {
            return parent::getAttribute( $ak, $displayMode );
        }


        public function getAttributeField($ak){
            parent::getAttributeField( $ak );
        }


        public function getAttributeValueObject($ak, $createIfNotFound = false) {
            return parent::getAttributeValueObjectGeneric( $ak, $createIfNotFound, array(
                'table'			=> 'SchedulizerEventAttributeValues',
                'idColumn'		=> 'eventID',
                'attrValClass'	=> 'SchedulizerEventAttributeValue',
                'setObjMethod'	=> 'setEvent'
            ));
        }


        public function reindex() {
            parent::reindexGeneric(array(
                'table'		=> 'SchedulizerEventSearchIndexAttributes',
                'idColumn'	=> 'eventID'
            ));
        }

    }
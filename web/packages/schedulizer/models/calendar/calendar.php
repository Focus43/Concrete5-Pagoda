<?php

    class SchedulizerCalendar extends SchedulizerBaseModel {

        protected $attrCategoryHandle = 'schedulizer_calendar',
                  // this is a double back up; gets overridden by saved config settings
                  $defaultTimezone    = 'America/New_York';


        /**
         * Construct a new object; and optionally pass in a key => value array of parameters
         * to set properties during instantiation.
         * @param array $properties
         * @return SchedulizerCalendar
         */
        public function __construct( array $properties = array() ){
            parent::__construct($properties);
            $this->tableName = __CLASS__;
        }


        /**
         * Magic method to print the title when 'echo' is run on Calendar object.
         * @return string
         */
        public function __toString(){
            return ucwords( $this->getTitle() );
        }


        /**
         * Get the calendar ID.
         * @return int || null
         */
        public function getCalendarID(){
            return $this->id;
        }


        /**
         * Get the calendar title.
         * @return string
         */
        public function getTitle(){
            if( empty($this->title) ){
                return "Calendar {$this->id}";
            }
            return $this->title;
        }


        /**
         * Get the default timezone for the calendar (defaults to America/New_York).
         * @return string
         */
        public function getDefaultTimezone(){
            return $this->defaultTimezone;
        }


        /**
         * Get the calendar timezone as an object.
         * @return DateTimeZone
         */
        public function getCalendarTimezoneObj(){
            if( $this->_calendarTimezoneObj === null ){
                $this->_calendarTimezoneObj = new DateTimeZone( $this->getDefaultTimezone() );
            }
            return $this->_calendarTimezoneObj;
        }


        /**
         * Get the User ID of the calendar owner (usually creator).
         * @return int
         */
        public function getOwnerID(){
            return $this->ownerID;
        }


        /**
         * Get the list of white-labeled properties which can be persisted to the db.
         * @return array
         */
        protected function persistable(){
            return array('title', 'defaultTimezone', 'ownerID');
        }


        /**
         * Save a calendar model in its current state.
         *
         * @internal The schedulizer_calendar_save hook is used (amongst other things) to
         * update events that use the default calendar timezone.
         *
         * @return SchedulizerCalendar
         */
        public function save(){
            $this->persistToDatabase();

            // save attributes
            $attrKeys = SchedulizerCalendarAttributeKey::getList();
            foreach($attrKeys AS $akObj){
                $akObj->saveAttributeForm( $this );
            }

            $self = self::getByID( $this->id );
            Events::fire('schedulizer_calendar_save', $self, $this->previousTimezone);
            return $self;
        }


        /**
         * Get a calendar object by its' ID.
         * @return SchedulizerCalendar
         */
        public static function getByID( $id ){
            $self = new self();
            $row  = Loader::db()->GetRow("SELECT * FROM {$self->tableName} WHERE id = ?", array((int)$id));
            $self->setPropertiesFromArray($row);
            return $self;
        }


        /**
         * Delete the record and any associated attribute values.
         * @todo Delete all associated events (and therefore repeating records, and nullifiers)
         * @return void
         */
        public function delete(){
            $db = Loader::db();

            // get all associated eventIDs, load the eventObj, and run delete method
            $eventIDs = $db->GetCol("SELECT id FROM SchedulizerEvent WHERE calendarID = ?", array($this->id));
            foreach($eventIDs AS $eventID){
                SchedulizerEvent::getByID($eventID)->delete();
            }

            // then delete the calendar record itself and associated values
            $db->Execute("DELETE FROM SchedulizerCalendarAttributeValues WHERE calendarID = ?", array($this->id));
            $db->Execute("DELETE FROM SchedulizerCalendarSearchIndexAttributes WHERE calendarID = ?", array($this->id));
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
                'table'			=> 'SchedulizerCalendarAttributeValues',
                'idColumn'		=> 'calendarID',
                'attrValClass'	=> 'SchedulizerCalendarAttributeValue',
                'setObjMethod'	=> 'setCalendar'
            ));
        }


        public function reindex() {
            parent::reindexGeneric(array(
                'table'		=> 'SchedulizerCalendarSearchIndexAttributes',
                'idColumn'	=> 'calendarID'
            ));
        }

    }
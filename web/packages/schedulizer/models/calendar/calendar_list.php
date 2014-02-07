<?php

    class SchedulizerCalendarList extends DatabaseItemList {

        protected $autoSortColumns  = array('createdUTC', 'modifiedUTC', 'title'),
                  $itemsPerPage     = 10,
                  $attributeClass   = 'SchedulizerCalendarAttributeKey',
                  $attributeFilters = array();


        /**
         * Magic method for filtering by attribute keys.
         * @param string $nm Filter method name to parse
         * @param mixed $a Value
         * @return void
         */
        public function __call($nm, $a) {
            if (substr($nm, 0, 8) == 'filterBy') {
                $txt = Loader::helper('text');
                $attrib = $txt->uncamelcase(substr($nm, 8));
                if (count($a) == 2) {
                    $this->filterByAttribute($attrib, $a[0], $a[1]);
                } else {
                    $this->filterByAttribute($attrib, $a[0]);
                }
            }
        }


        /**
         * Apply a plain-text keyword search to specific column values.
         * @param string $keywords Plain text keywords.
         * @return void
         */
        public function filterByKeywords($keywords) {
            $db = Loader::db();
            $this->searchKeywords = $db->quote($keywords);
            $qkeywords = $db->quote('%' . $keywords . '%');
            $keys = SchedulizerCalendarAttributeKey::getSearchableIndexedList();
            $attribsStr = '';
            foreach ($keys as $ak) {
                $cnt = $ak->getController();
                $attribsStr.=' OR ' . $cnt->searchKeywords($keywords);
            }
            $this->filter(false, "(sc.title LIKE $qkeywords OR $qkeywords {$attribsStr})");
        }


        /**
         * Run the built up query.
         * @param int $itemsToGet
         * @param int $offset
         * @return array Of calendar objects
         */
        public function get( $itemsToGet = 100, $offset = 0 ){
            $calendars = array();
            $this->createQuery();
            $r = parent::get($itemsToGet, $offset);
            foreach($r AS $row){
                $calendars[] = SchedulizerCalendar::getByID( $row['id'] );
            }
            return $calendars;
        }


        /**
         * Run get() and turn it into a list with id: name key-value pairs.
         */
        public function getAsKeyValueList( $itemsToGet = 100, $offset = 0 ){
            $calendars = $this->get($itemsToGet = 100, $offset = 0);

            $keyValueList = array();
            /** @var $calendarObj SchedulizerCalendar */
            if(!empty($calendars)): foreach($calendars AS $calendarObj){
                $keyValueList[ $calendarObj->getCalendarID() ] = $calendarObj->getTitle();
            } endif;

            return $keyValueList;
        }


        /**
         * Get the total number of results.
         * @return int
         */
        public function getTotal(){
            $this->createQuery();
            return parent::getTotal();
        }


        /**
         * Setup the query string internally for the database class.
         * @return void
         */
        protected function createQuery(){
            if( !$this->queryCreated ){
                $this->setBaseQuery();
                //$this->setupAttributeFilters("LEFT JOIN LithPropertySearchIndexAttributes lpattrsearch ON (lpattrsearch.propertyID = lp.id)");
                $this->queryCreated = true;
            }
        }


        /**
         * Set the base query string up.
         * @return void
         */
        public function setBaseQuery(){
            $this->setQuery("SELECT sc.id FROM SchedulizerCalendar sc");
        }

    }
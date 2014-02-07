<?php

    /**
     * Class SchedulizerEventList
     *
     * @todo Attribute inclusion
     */
    class SchedulizerEventList extends DatabaseItemList {

              // number of days into the future to scan (implicit maximum of 1000 enforced by the query)
        const FORMAT_YEAR_MONTH_DAY  = 'Y-m-d',
              DEFAULT_DAYS_IN_FUTURE = 45;

        protected $autoSortColumns  = array('createdUTC', 'modifiedUTC', 'title', 'startLocalized', 'endLocalized'),
                  $itemsPerPage     = 10,
                  $attributeClass   = 'SchedulizerEventAttributeKey',
                  $attributeFilters = array(),

                  // non-standard class properties
                  $_queryDaySpan    = self::DEFAULT_DAYS_IN_FUTURE,
                  $_calendarIDs     = array(),
                  $_queryStartDTO   = null,
                  $_queryEndDTO     = null;


        /**
         * Insantiate new list; optionally pass in time to query forward from.
         *
         * @internal If you don't pass in a startDate, the query will look from now
         * forward.
         *
         * @param DateTime $startDate Pass a DateTime object with the time the query
         * should look forward from.
         */
        public function __construct( DateTime $startDate = null ){
            if( $startDate instanceof DateTime ){
                $this->_queryStartDTO = $startDate;
            }

            // set default sorting to chrono
            $this->sortBy('_eventList.eventDate', 'asc');
        }


        /**
         * Magic method for filtering by attribute keys.
         *
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
         *
         * @param string $keywords Plain text keywords.
         * @return void
         */
        public function filterByKeywords($keywords) {
            $db = Loader::db();
            $this->searchKeywords = $db->quote($keywords);
            $qkeywords = $db->quote('%' . $keywords . '%');
            $keys = SchedulizerEventAttributeKey::getSearchableIndexedList();
            $attribsStr = '';
            foreach ($keys as $ak) {
                $cnt = $ak->getController();
                $attribsStr.=' OR ' . $cnt->searchKeywords($keywords);
            }
            $this->filter(false, "(event.title LIKE $qkeywords OR $qkeywords {$attribsStr})");
        }


        /**
         * How many days into the future should the query look? Defaults to 45 days.
         *
         * @param int $number
         * @return SchedulizerEventList
         */
        public function setDaysIntoFuture( $number = self::DEFAULT_DAYS_IN_FUTURE ){
            $this->_queryDaySpan = (int) $number;
            return $this;
        }


        /**
         * Restrict the query to return results ending on/before date. Setting the
         * end date will implicitly adjust the _queryDateSpan number.
         *
         * @param DateTime $endDTO
         * @return SchedulizerEventList
         */
        public function filterByEndDate( DateTime $endDTO ){
            $this->_queryEndDTO = $endDTO;
            return $this;
        }


        /**
         * Filter results by one or more calendarID(s). Pass in either a specific calendarID
         * as an integer, or an array of calendarIDs.
         *
         * @internal: instead of applying the filter using the parent class's ->filter() method,
         * we store it in a class property so we can filter the events in a subquery first,
         * making it more efficient.
         *
         * @param mixed $calendarID
         * @return SchedulizerEventList
         */
        public function filterByCalendarIDs( $calendarIDs ){
            if( is_array($calendarIDs) ){
                $this->_calendarIDs = $calendarIDs;
                return;
            }
            $this->_calendarIDs = array( $calendarIDs );
            return $this;
        }


        /**
         * Filter results to show only original events (not repeating/aliased events).
         *
         * @return SchedulizerEventList
         */
        public function filterByIsOriginalEvent(){
            $this->filter('_eventList.isAlias', '0', '=');
            return $this;
        }


        /**
         * Filter to show only repeating/aliased events, not the original.
         *
         * @return SchedulizerEventList
         */
        public function filterByIsAlias(){
            $this->filter('_eventList.isAlias', '1', '=');
            return $this;
        }


        /**
         * Run the built up query.
         * @param int $itemsToGet
         * @param int $offset
         * @return array SchedulizerEvent
         */
        public function get( $itemsToGet = 100, $offset = 0 ){
            $events = array();
            $this->createQuery();
            $r = parent::get($itemsToGet, $offset);
            return $r;
            foreach($r AS $row){
                $events[] = SchedulizerEvent::getByID( $row['id'] );
            }
            return $events;
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
         * Setup the query string internally for the database class. Run validations in here to make
         * sure minimum parameters are set.
         * @return void
         */
        protected function createQuery(){
            if( !$this->queryCreated ){
                // make sure at least 1 calendarID is present
                if( empty($this->_calendarIDs) ){
                    throw new Exception('At least 1 calendar ID must be included in the Event List query.');
                }

                $this->setBaseQuery();
                //$this->setupAttributeFilters("LEFT JOIN LithPropertySearchIndexAttributes lpattrsearch ON (lpattrsearch.propertyID = lp.id)");
                $this->queryCreated = true;
            }
        }


        /**
         * Setup the start and end date filters. Instead of doing >= or <= comparisons with SQL,
         * all we do is set the number of days for the subquery to generate (look into the future),
         * and *that* is the mechanism for limiting the results.
         */
        private function _setupRestrictions(){
            // if _queryStartDTO hasn't been defined, set it to Now()
            if( !($this->_queryStartDTO instanceof DateTime) ){
                $this->_queryStartDTO = new DateTime('now', new DateTimeZone('UTC'));
            }

            // conversely, if the _queryEndDTO *HAS* been set, automatically adjust
            // the _queryDaySpan property to be the difference between start and end
            if( $this->_queryEndDTO instanceof DateTime ){
                $this->_queryDaySpan = $this->_queryEndDTO->diff($this->_queryStartDTO, true)->days + 1;
            }
        }


        /**
         * Set the base query string up.
         * @todo: repeat yearly (just once per year)
         * @todo: last (> 4th) "Tuesday" or whatever day of the month.
         * @todo: PERFORMANCE - include date restrictions on the JOIN of the events table,
         * so that it only joins events where the repeatEndUTC < $endDate AND startUTC < $endDate
         * and excludes *single day, historical events*
         *
         * @return void
         */
        public function setBaseQuery(){
            // this sets up the date restrictions, see comments
            $this->_setupRestrictions();

            // put data for the query in local variables
            $startDate      = $this->_queryStartDTO->format('Y-m-d');
            $inCalendarIDs  = join(',', $this->_calendarIDs);

            // for singular day events, make sure to restrict by number of days (daySpan) being queried
            $endDate = clone $this->_queryStartDTO;
            $endDate->modify("+{$this->_queryDaySpan} days");
            $endDate = $endDate->format('Y-m-d');

            $this->setQuery("SELECT _eventList.*, TIMESTAMP(_eventList.eventDate, TIME(CONVERT_TZ(_eventList.startUTC, 'UTC', _eventList.timezoneName))) AS startLocalized, TIMESTAMPADD(MINUTE,TIMESTAMPDIFF(MINUTE, _eventList.startUTC, _eventList.endUTC),TIMESTAMP(_eventList.eventDate, TIME(CONVERT_TZ(_eventList.startUTC, 'UTC', _eventList.timezoneName)))) AS endLocalized FROM (
                select _unionized.eventDate, _events.*, (CASE WHEN (_unionized.eventDate != DATE(_events.startUTC)) IS TRUE THEN 1 ELSE 0 END) AS isAlias FROM (
                    select '{$startDate}' + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as eventDate
                      from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
                      cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
                      cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
                      LIMIT {$this->_queryDaySpan}
                ) AS _unionized
                JOIN (
                    SELECT ev.*, evr.repeatWeek, evr.repeatDay, evr.repeatWeekday FROM SchedulizerEvent ev
                    LEFT JOIN SchedulizerEventRepeat evr ON evr.eventID = ev.id
                    WHERE ev.calendarID IN ({$inCalendarIDs})
                    AND ev.isRepeating = 1
                ) AS _events
                WHERE (_events.repeatIndefinite = 1 OR (_unionized.eventDate <= _events.repeatEndUTC AND _events.repeatIndefinite = 0))
                AND DATE(_events.startUTC) <= DATE(_unionized.eventDate)
                AND _events.id NOT IN (SELECT evnullify.eventID FROM SchedulizerEventRepeatNullify evnullify WHERE DATE(_unionized.eventDate) = DATE(evnullify.hideOnDate))
                AND (
                    ((_events.repeatTypeHandle = 'daily') AND ( DATEDIFF(_unionized.eventDate, CONVERT_TZ(_events.startUTC, 'UTC', _events.timezoneName)) % _events.repeatEvery = 0 ))
                    OR
                    ( (_events.repeatTypeHandle = 'weekly') AND (_events.repeatWeek IS NULL) AND (_events.repeatWeekday = DAYOFWEEK(_unionized.eventDate)) AND (CEIL(DATEDIFF(CONVERT_TZ(_events.startUTC, 'UTC', _events.timezoneName), _unionized.eventDate)/7) % _events.repeatEvery = 0) )
                    OR
                    ( (_events.repeatTypeHandle = 'monthly') AND (_events.repeatDay = DAYOFMONTH(_unionized.eventDate)) AND ((MONTH(_unionized.eventDate) - MONTH(CONVERT_TZ(_events.startUTC, 'UTC', _events.timezoneName))) % _events.repeatEvery = 0) )
                    OR
                    ( (_events.repeatTypeHandle = 'monthly') AND ((DATE_ADD(DATE_SUB(LAST_DAY(_unionized.eventDate), INTERVAL DAY(LAST_DAY(_unionized.eventDate)) -1 DAY), INTERVAL (((_events.repeatWeekday + 7) - DAYOFWEEK(DATE_SUB(LAST_DAY(_unionized.eventDate), INTERVAL DAY(LAST_DAY(_unionized.eventDate)) -1 DAY))) % 7) + ((_events.repeatWeek * 7) -7) DAY)) = _unionized.eventDate) AND ((MONTH(_unionized.eventDate) - MONTH(CONVERT_TZ(_events.startUTC, 'UTC', _events.timezoneName))) % _events.repeatEvery = 0))
                    OR
                    ( (_events.repeatTypeHandle = 'yearly') AND ((YEAR(_unionized.eventDate) - YEAR(CONVERT_TZ(_events.startUTC, 'UTC', _events.timezoneName))) % _events.repeatEvery = 0) )
                )
                UNION (SELECT DATE(CONVERT_TZ(ev2.startUTC, 'UTC', ev2.timezoneName)) AS eventDate, ev2.*, NULL as repeatWeek, NULL AS repeatDay, NULL AS repeatWeekday, 0 AS isAlias
                FROM SchedulizerEvent ev2 WHERE (ev2.isRepeating = 0) AND ev2.calendarID IN ({$inCalendarIDs})
                AND CONVERT_TZ(ev2.startUTC, 'UTC', ev2.timezoneName) >= '{$startDate}' AND CONVERT_TZ(ev2.startUTC, 'UTC', ev2.timezoneName) < '{$endDate}')
                ) AS _eventList");
        }

    }
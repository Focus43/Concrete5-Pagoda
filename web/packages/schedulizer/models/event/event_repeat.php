<?php

    /**
     * Class SchedulizerEventRepeat. Mostly used for saving repeating events.
     * @property SchedulizerEvent eventObj
     * @property array settings
     */
    class SchedulizerEventRepeat {

                // weekday indices
        const   WEEKDAY_INDEX_SUN   = 1,
                WEEKDAY_INDEX_MON   = 2,
                WEEKDAY_INDEX_TUE   = 3,
                WEEKDAY_INDEX_WED   = 4,
                WEEKDAY_INDEX_THU   = 5,
                WEEKDAY_INDEX_FRI   = 6,
                WEEKDAY_INDEX_SAT   = 7,
                // how to handle saving an event when its an alias
                UPDATE_ONLY_THIS_EVENT  = 'this_event_only',
                UPDATE_FOLLOWING_EVENTS = 'following_events',
                UPDATE_ALL_EVENTS       = 'update_all';

        protected $eventObj, $settings;


        /**
         * Instantiate (can only be done internally to the class from a static method)
         * @param SchedulizerEvent $eventObj
         * @param array $settings
         */
        protected function __construct(SchedulizerEvent $eventObj, array $settings){
            $this->eventObj = $eventObj;
            $this->settings = $settings;
        }


        /**
         * Clear any existing records from the EventRepeat table.
         * @return void
         */
        public static function purgeExisting( $eventID ){
            Loader::db()->Execute("DELETE FROM SchedulizerEventRepeat WHERE eventID = ?", array($eventID));
        }


        /**
         * @param $eventID
         * @param $date
         * @return void
         */
        public static function nullifyOnDate( DateTime $date, $eventID ){
            Loader::db()->Execute("INSERT INTO SchedulizerEventRepeatNullify (eventID, hideOnDate) VALUES (?,?)", array(
                (int) $eventID, $date->format('Y-m-d')
            ));
        }


        /**
         * Create records for events repeating daily.
         * return void
         */
        private function saveRepeatDaily(){
            Loader::db()->Execute("INSERT INTO SchedulizerEventRepeat (eventID) VALUES(?)", array(
                $this->eventObj->getEventID()
            ));
        }


        /**
         * Create records for events repeating weekly.
         * @return void
         */
        private function saveRepeatWeekly(){
            if( isset($this->settings['weekday_index']) && !empty($this->settings['weekday_index']) ){
                foreach( $this->settings['weekday_index'] AS $dayIndex ){
                    Loader::db()->Execute("INSERT INTO SchedulizerEventRepeat (eventID, repeatWeekday) VALUES (?,?)", array(
                        $this->eventObj->getEventID(), $dayIndex
                    ));
                }
            }
        }


        /**
         * Create records for events repeating monthly.
         * @return void
         */
        private function saveRepeatMonthly(){
            if( isset($this->settings['monthly']) && !empty($this->settings['monthly']) ){
                // if its repeating only on a specific date (eg: "21" of every month)
                if( (int)$this->settings['monthly']['method'] === SchedulizerEvent::REPEAT_MONTHLY_SPECIFIC_DATE ){
                    Loader::db()->Execute("INSERT INTO SchedulizerEventRepeat (eventID, repeatDay) VALUES(?,?)", array(
                        $this->eventObj->getEventID(), $this->settings['monthly']['specific_day']
                    ));
                // if its repeating on an abstract (eg: "Second Thursday" of every month)
                }else{
                    Loader::db()->Execute("INSERT INTO SchedulizerEventRepeat (eventID, repeatWeek, repeatWeekday) VALUES(?,?,?)", array(
                        $this->eventObj->getEventID(), $this->settings['monthly']['week'], $this->settings['monthly']['weekday']
                    ));
                }
            }
        }


        /**
         * Create records for events repeating yearly.
         * @return void
         */
        private function saveRepeatYearly(){
            Loader::db()->Execute("INSERT INTO SchedulizerEventRepeat (eventID) VALUES(?)", array(
                $this->eventObj->getEventID()
            ));
        }


        /**
         * Save the repeat settings for a given Event object.
         * @param SchedulizerEvent $eventObj
         * @param array $settings
         */
        public static function save( SchedulizerEvent $eventObj, array $settings ){
            $self = new self($eventObj, $settings);

            switch( $self->eventObj->getRepeatTypeHandle() ){
                // handle repeating daily
                case SchedulizerEvent::REPEAT_TYPE_HANDLE_DAILY:
                    $self->saveRepeatDaily();
                    break;
                // handle repeating weekly
                case SchedulizerEvent::REPEAT_TYPE_HANDLE_WEEKLY:
                    $self->saveRepeatWeekly();
                    break;
                // handle repeating monthly
                case SchedulizerEvent::REPEAT_TYPE_HANDLE_MONTHLY:
                    $self->saveRepeatMonthly();
                    break;
                // handle repeating yearly
                case SchedulizerEvent::REPEAT_TYPE_HANDLE_YEARLY:
                    $self->saveRepeatYearly();
                    break;
            }
        }

    }
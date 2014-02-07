<?php

    class SchedulizerSaveEventProcessor {

        protected $eventObj,
                  $formData,
                  $prepared = array();


        /**
         * @param SchedulizerEvent $eventObj
         * @param array $formData
         */
        public function __construct( SchedulizerEvent $eventObj, array $formData ){
            $this->eventObj = $eventObj;
            $this->formData = $formData;
        }


        /**
         * @return DateTimeZone
         */
        protected function tzObjUTC(){
            if( $this->_tzObjUTC === null ){
                $this->_tzObjUTC = new DateTimeZone('UTC');
            }
            return $this->_tzObjUTC;
        }


        /**
         * @return DateTimeZone
         */
        private function eventTimezoneObj(){
            return new DateTimeZone( $this->prepared['timezoneName'] );
        }


        /**
         * @param bool $asUTC Adjust the start time to UTC (defaults to yes)
         * @return DateTime
         */
        private function startDTO( $asUTC = true ){
            $dateTimeObj = new DateTime("{$this->formData['event_start_date']} {$this->formData['event_start_time']}", $this->eventTimezoneObj());
            if($asUTC){ $dateTimeObj->setTimezone( $this->tzObjUTC() ); }
            return $dateTimeObj;
        }


        /**
         * @param bool $asUTC Adjust the end time to UTC (defaults to yes)
         * @return DateTime
         */
        private function endDTO( $asUTC = true ){
            $dateTimeObj = new DateTime("{$this->formData['event_end_date']} {$this->formData['event_end_time']}", $this->eventTimezoneObj());
            if($asUTC){ $dateTimeObj->setTimezone( $this->tzObjUTC() ); }
            return $dateTimeObj;
        }


        /**
         * @return DateTime
         */
        private function repeatEndDTO(){
            $dateTimeObj = new DateTime("{$this->formData['event_repeat_end']}", $this->eventTimezoneObj());
            $dateTimeObj->setTimezone( $this->tzObjUTC() )->setTime(0,0,0);
            return $dateTimeObj;
        }


        /**
         * @return SchedulizerSaveEventProcessor
         */
        public function transform(){
            // if its a new event, first set calendarID *in the event obj*
            if( isset($this->formData['event']['calendarID']) ){
                $this->eventObj->setPropertiesFromArray(array(
                    'calendarID' => (int) $this->formData['event']['calendarID']
                ));
            }

            // standard properties (ensure booleans are always explicitly set)
            $this->prepared['title']                = $this->formData['event']['title'];
            $this->prepared['colorHex']             = $this->formData['event']['colorHex'];
            $this->prepared['description']          = $this->formData['event']['description'];
            $this->prepared['repeatTypeHandle']     = $this->formData['event']['repeatTypeHandle'];
            $this->prepared['useCalendarTimezone']  = isset($this->formData['event']['useCalendarTimezone']) ? SchedulizerEvent::USE_CALENDAR_TIMEZONE_TRUE : SchedulizerEvent::USE_CALENDAR_TIMEZONE_FALSE;
            $this->prepared['timezoneName']         = isset($this->formData['event']['useCalendarTimezone']) ? $this->eventObj->calendarObject()->getDefaultTimezone() : $this->formData['timezone_name'];
            $this->prepared['isRepeating']          = isset($this->formData['event']['isRepeating']) ? SchedulizerEvent::IS_REPEATING_TRUE : SchedulizerEvent::IS_REPEATING_FALSE;
            $this->prepared['isAllDay']             = isset($this->formData['event']['isAllDay']) ? SchedulizerEvent::ALL_DAY_TRUE : SchedulizerEvent::ALL_DAY_FALSE;
            $this->prepared['repeatMonthlyMethod']  = isset($this->formData['repeat']['monthly']['method']) ? (int) $this->formData['repeat']['monthly']['method'] : SchedulizerEvent::REPEAT_MONTHLY_SPECIFIC_DATE;
            $this->prepared['repeatIndefinite']     = (int) $this->formData['event']['repeatIndefinite'];
            $this->prepared['repeatEvery']          = (int) $this->formData['event']['repeatEvery'];

            // set start, end, and repeatEnd times as UTC
            $this->prepared['startUTC']     = $this->startDTO()->format(SchedulizerPackage::TIMESTAMP_FORMAT);
            $this->prepared['endUTC']       = $this->endDTO()->format(SchedulizerPackage::TIMESTAMP_FORMAT);
            $this->prepared['repeatEndUTC'] = $this->repeatEndDTO()->format(SchedulizerPackage::TIMESTAMP_FORMAT);

            // automatically adjust start and end UTC times if certain repeat settings
            // are applied
            if( isset($this->formData['event']['isRepeating']) ){
                $startEndInterval = $this->startDTO()->diff( $this->endDTO() );

                // weekly?
                if($this->formData['event']['repeatTypeHandle'] === SchedulizerEvent::REPEAT_TYPE_HANDLE_WEEKLY){
                    $this->adjustStartEndTimesForWeeklyRepeats( $startEndInterval );
                }

                // monthly
                if($_REQUEST['event']['repeatTypeHandle'] === SchedulizerEvent::REPEAT_TYPE_HANDLE_MONTHLY){
                    $this->adjustStartEndTimesForMonthlyRepeats( $startEndInterval );
                }
            }

            return $this;
        }


        /**
         * If the user clicked on a Wednesday, but the event should repeat every monday and friday,
         * make the start date monday.
         * @param DateInterval $startEndInterval
         * @return void
         */
        private function adjustStartEndTimesForWeeklyRepeats( DateInterval $startEndInterval ){
            $repeatableDays = (array) $this->formData['repeat']['weekday_index'];

            if( !empty($repeatableDays) ){
                $startDate          = $this->startDTO(false);
                $startDateDayIndex  = $startDate->format('w') + 1;
                $lowestDayIndex     = min( $repeatableDays );

                if( !in_array($startDateDayIndex, $repeatableDays) ){
                    $weekdayIndicesList = $this->timingHelper()->weekdayIndicesList();
                    $startDate->modify('next ' . $weekdayIndicesList[$lowestDayIndex]);
                    $startTime = explode(':', $this->formData['event_start_time']);
                    $startDate->setTime($startTime[0], $startTime[1]);
                    $startDate->setTimezone(new DateTimeZone('UTC'));

                    // reset the prepared data again from the modified start and end times
                    $this->prepared['startUTC'] = $startDate->format(SchedulizerPackage::TIMESTAMP_FORMAT);
                    $this->prepared['endUTC']   = $startDate->add($startEndInterval)->format(SchedulizerPackage::TIMESTAMP_FORMAT);
                }
            }
        }


        /**
         * Handle adjusting the start date when an event repeats monthly. The date would need to
         * be adjusted if, for example, the user clicked the 15th, but wants an event to repeat
         * on the 12th of every month (in which case the start date is moved to the 12th); or
         * if "second tuesday" is chosen - set the start date to the second tuesday.
         * @param DateInterval $startEndInterval
         * @return void
         */
        private function adjustStartEndTimesForMonthlyRepeats( DateInterval $startEndInterval ){
            $startDate = $this->startDTO(false);

            // ie. "repeat every month on the 11th"
            if( (int) $this->formData['repeat']['monthly']['method'] === SchedulizerEvent::REPEAT_MONTHLY_SPECIFIC_DATE ){
                if( (int)$startDate->format('j') !== (int) $this->formData['repeat']['monthly']['specific_day'] ){
                    $startDate->setDate($startDate->format('Y'), $startDate->format('m'), ((int) $this->formData['repeat']['monthly']['specific_day']));
                    $startDate->setTimezone(new DateTimezone('UTC'));

                    // reset the prepared data again from the modified start and end times
                    $this->prepared['startUTC'] = $startDate->format(SchedulizerPackage::TIMESTAMP_FORMAT);
                    $this->prepared['endUTC']   = $startDate->add($startEndInterval)->format(SchedulizerPackage::TIMESTAMP_FORMAT);
                }
            }

            // ie. "repeat every 2nd tuesday"
            if( (int) $this->formData['repeat']['monthly']['method'] === SchedulizerEvent::REPEAT_MONTHLY_WEEK_AND_DAY ){
                // 1 -> "first", 2 -> "second", etc
                $intToOrdinalPairs = $this->timingHelper()->monthlyRepeatableWeekOptions();
                $ordinal = $intToOrdinalPairs[ (int) $this->formData['repeat']['monthly']['week'] ];

                // weekday string (1 -> "sunday", 2 -> "monday", etc)
                $intToWeekdayPairs = $this->timingHelper()->weekdayIndicesList();
                $weekday = $intToWeekdayPairs[ (int) $this->formData['repeat']['monthly']['weekday'] ];

                // create date time object from relative string
                $monthName = $startDate->format('F');
                $dateTimeFromStr = new DateTime("{$ordinal} {$weekday} of {$monthName}");

                $startDate->setDate($dateTimeFromStr->format('Y'), $dateTimeFromStr->format('m'), $dateTimeFromStr->format('d'));
                $startDate->setTimezone(new DateTimezone('UTC'));

                // reset the prepared data again from the modified start and end times
                $this->prepared['startUTC'] = $startDate->format(SchedulizerPackage::TIMESTAMP_FORMAT);
                $this->prepared['endUTC']   = $startDate->add($startEndInterval)->format(SchedulizerPackage::TIMESTAMP_FORMAT);
            }
        }


        /**
         * @return TimingHelper
         */
        private function timingHelper(){
            if( $this->_timingHelper === null ){
                $this->_timingHelper = Loader::helper('timing', 'schedulizer');
            }
            return $this->_timingHelper;
        }


        /**
         * @todo Validate event properties on saving
         * @return SchedulizerSaveEventProcessor
         */
        public function validate(){

            return $this;
        }


        /**
         * @return User
         */
        protected function currentUser(){
            if( $this->_currentUserObj === null ){
                $this->_currentUserObj = new User;
            }
            return $this->_currentUserObj;
        }


        /**
         * @return SchedulizerSaveEventProcessor
         */
        public function persist(){
            // set ownerID if its a new object (eg. event obj has no ownerID assigned)
            if( ! ($this->eventObj->getOwnerID() >= 1) ){
                $this->prepared['ownerID'] = $this->currentUser()->getUserID();
            }

            // set all main properties of the event
            $this->eventObj->setPropertiesFromArray( $this->prepared );

            // persist
            $this->eventObj->save();

            // save repeat settings (always clear first)
            SchedulizerEventRepeat::purgeExisting( $this->eventObj->getEventID() );
            if( $this->prepared['isRepeating'] === SchedulizerEvent::IS_REPEATING_TRUE ){
                SchedulizerEventRepeat::save($this->eventObj, (array) $this->formData['repeat']);
            }

            return $this;
        }


        /**
         * @return SchedulizerEvent
         */
        public function getEventObj(){
            return $this->eventObj;
        }

    }
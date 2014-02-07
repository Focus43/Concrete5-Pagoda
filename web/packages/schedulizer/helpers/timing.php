<?php

    class TimingHelper {

        protected static $_selectableTimeValues;


        /**
         * Get a list of available time selections.
         * @return array
         */
        public static function selectableTimeValues(){
            // if cached, use that
            if( is_array(self::$_selectableTimeValues) ){
                return self::$_selectableTimeValues;
            }

            // otherwise generate and cache
            self::$_selectableTimeValues = array();
            foreach(range(0,23) AS $h){
                foreach(range(0,55,15) AS $m){
                    $minute = str_pad($m,2,'0',STR_PAD_LEFT);
                    $ampm   = ($h < 12) ? 'am' : 'pm';
                    $hValue = str_pad($h,2,'0',STR_PAD_LEFT);
                    self::$_selectableTimeValues["$hValue:$minute"] = t('%s:%s %s', ($h === 0 ? 12 : ($h > 12 ? ($h - 12) : $h)), $minute, $ampm);
                }
            }
            return self::$_selectableTimeValues;
        }


        /**
         * @return array
         */
        public function getRepeatTypeHandles(){
            return array(
                SchedulizerEvent::REPEAT_TYPE_HANDLE_DAILY      => 'Daily',
                SchedulizerEvent::REPEAT_TYPE_HANDLE_WEEKLY     => 'Weekly',
                SchedulizerEvent::REPEAT_TYPE_HANDLE_MONTHLY    => 'Monthly',
                SchedulizerEvent::REPEAT_TYPE_HANDLE_YEARLY     => 'Yearly'
            );
        }


        /**
         * @return array
         */
        public function repeatEveryOptions(){
            return array_combine(range(1,30), range(1,30));
        }


        /**
         * @return array
         */
        public function monthlyRepeatableWeekOptions(){
            return array(
                '1' => 'First',
                '2' => 'Second',
                '3' => 'Third',
                '4' => 'Fourth',
                '5' => 'Last'
            );
        }


        public function weekdayIndicesList(){
            return array(
                SchedulizerEventRepeat::WEEKDAY_INDEX_SUN => 'Sunday',
                SchedulizerEventRepeat::WEEKDAY_INDEX_MON => 'Monday',
                SchedulizerEventRepeat::WEEKDAY_INDEX_TUE => 'Tuesday',
                SchedulizerEventRepeat::WEEKDAY_INDEX_WED => 'Wednesday',
                SchedulizerEventRepeat::WEEKDAY_INDEX_THU => 'Thursday',
                SchedulizerEventRepeat::WEEKDAY_INDEX_FRI => 'Friday',
                SchedulizerEventRepeat::WEEKDAY_INDEX_SAT => 'Saturday'
            );
        }

    }
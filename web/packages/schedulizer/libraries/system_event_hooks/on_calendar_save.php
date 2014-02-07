<?php

    class SchedulizerOnCalendarSave {

        /**
         * Update any of the calendar's events that use the default timezone with the new one.
         * Means the startUTC and endUTC times have to be updated!
         *
         * @param SchedulizerCalendar $calendarObj
         * @return void
         */
        public function updateEventTimezones( SchedulizerCalendar $calendarObj, $previousTimezone = null ){
            // if the timezones have changed, update 'em
            if( $previousTimezone !== $calendarObj->getDefaultTimezone() ){
                $newTZ  = $calendarObj->getDefaultTimezone();

                // update query
                Loader::db()->Execute("UPDATE SchedulizerEvent SET timezoneName = ?,
                    startUTC = CONVERT_TZ(CONVERT_TZ(startUTC, 'UTC', ?), ?, 'UTC'),
                    endUTC = CONVERT_TZ(CONVERT_TZ(endUTC, 'UTC', ?), ?, 'UTC')
                    WHERE calendarID = ? AND useCalendarTimezone = 1",
                array(
                    $newTZ, $previousTimezone, $newTZ, $previousTimezone, $newTZ, $calendarObj->getCalendarID()
                ));
            }
        }

    }
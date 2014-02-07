<?php

    class SchedulizerCalendarColumnSet extends DatabaseItemListColumnSet {

        protected $attributeClass = 'SchedulizerCalendarAttributeKey';


        /**
         * Get the Schedulizer column set.
         * @return DatabaseItemListColumnSet|SchedulizerCalendarDefaultColumnSet
         */
        public function getCurrent(){
            $userObj = new User();
            $columns = $userObj->config('SCHEDULIZER_CALENDAR_COLUMNS');
            if( $columns != '' ){
                $columns = @unserialize($columns);
            }
            if( !($columns instanceof DatabaseItemListColumnSet) ){
                $columns = new SchedulizerCalendarDefaultColumnSet;
            }

            // return the column set class
            return $columns;
        }

    }
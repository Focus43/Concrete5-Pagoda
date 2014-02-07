<?php

    class SchedulizerCalendarDefaultColumnSet extends DatabaseItemListColumnSet {

        protected $attributeClass = 'SchedulizerCalendarAttributeKey';

        public function __construct(){
            $this->addColumn(new DatabaseItemListColumn('title', t('Title'), array('SchedulizerCalendarDefaultColumnSet', 'title')));
            $this->addColumn(new DatabaseItemListColumn('createdUTC', t('Created'), array('SchedulizerCalendarDefaultColumnSet', 'dateCreated')));
            $this->addColumn(new DatabaseItemListColumn('modifiedUTC', t('Last Modified'), array('SchedulizerCalendarDefaultColumnSet', 'dateModified')));
            $this->setDefaultSortColumn($this->getColumnByKey('createdUTC'), 'desc');
        }


        public function title(SchedulizerCalendar $calendarObj){
            $url   = View::url('/dashboard/schedulizer/calendars/edit', $calendarObj->getCalendarID());
            return t('<a href="%s">%s</a>', $url, $calendarObj);
        }


        public function dateCreated(SchedulizerCalendar $calendarObj){
            return date('M d, Y', strtotime($calendarObj->getDateCreated()));
        }


        public function dateModified(SchedulizerCalendar $calendarObj){
            return date('M d, Y', strtotime($calendarObj->getDateModified()));
        }
    }
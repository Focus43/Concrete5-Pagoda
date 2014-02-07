<?php

    class DashboardSchedulizerCalendarsSearchController extends SchedulizerController {

        /**
         * C5 view hook.
         * @return void
         */
        public function view(){
            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->css('dashboard/app.css', self::PACKAGE_HANDLE));
            $this->addFooterItem($this->getHelper('html')->javascript('dashboard/app.js', self::PACKAGE_HANDLE));
            $this->set('listObject', $this->calendarListObj());
            $this->set('listResults', $this->calendarListObj()->getPage());
            $this->setupSearchInstance();
        }


        /**
         * @return SchedulizerCalendarList
         */
        protected function calendarListObj(){
            if( $this->_calendarListObj === null ){
                $this->_calendarListObj = new SchedulizerCalendarList;
                //$this->_calendarListObj->applySearchFilters();
            }
            return $this->_calendarListObj;
        }


        /**
         * Pass $searchInstance stuff to the view
         * @return void
         */
        protected function setupSearchInstance(){
            $key = 'calendars_list_' . time();
            $this->set('searchInstance', $key);
            $this->addFooterItem('<script type="text/javascript">$(function() { ccm_setupAdvancedSearch(\''.$key.'\'); });</script>');
        }

    }
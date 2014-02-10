<?php

    class DashboardSchedulizerCalendarsController extends SchedulizerController {

        public function on_start(){
            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->css('app-dashboard.css', self::PACKAGE_HANDLE));
            $this->addFooterItem($this->getHelper('html')->javascript('app-dashboard.js', self::PACKAGE_HANDLE));
        }

        /**
         * C5 view action.
         * @return void
         */
        public function view(){
            $this->redirect('/dashboard/schedulizer/calendars/search');
        }


        /**
         * Create a new calendar record and redirect to /edit/$id.
         * @return void
         */
        public function add(){
            $calendarObj = new SchedulizerCalendar(array(
                'ownerID'           => $this->userObj()->getUserID(),
                'defaultTimezone'   => $this->packageConfig()->get( SchedulizerPackage::DEFAULT_TIMEZONE )
            ));
            $calendarObj->save();
            $this->redirect('/dashboard/schedulizer/calendars/edit', $calendarObj->getCalendarID());
        }


        /**
         * Passes an existing (*if* it exists) calendar object to the view.
         * @param int $id
         * @param string $activeTab The tab to activate on page render
         * @return void
         */
        public function edit( $id, $activeTab = 'calendar' ){
            $calendarObj = SchedulizerCalendar::getByID($id);

            // if it exists, render the edit form
            if( $calendarObj->getCalendarID() >= 1 ){
                $this->set('calendarObj', $calendarObj);
                $this->set('activeTab', $activeTab);
                $this->set('calendarAttrs', SchedulizerCalendarAttributeKey::getList());
                return;
            }

            // otherwise, redirect to /search and flash error message
            $this->flash('Calendar Not Found', self::FLASH_TYPE_ERROR);
            $this->redirect('/dashboard/schedulizer/calendars/search');
        }


        /**
         * Save a calendar. This does not *create* a calendar if it doesn't exist, that
         * happends in the add() action of this controller. This should only save existing
         * calendars.
         * @return void
         */
        public function save_calendar( $id = null ){
            try {
                $calendarObj = SchedulizerCalendar::getByID($id);

                // cache this so the save call can use it when updating event default timezones
                $calendarObj->previousTimezone = $calendarObj->getDefaultTimezone();

                // manually add to $_POST, b/c jquery Chosen can't handle arrays
                $_POST['calendar']['defaultTimezone'] = $_POST['calendar_timezone'];

                $calendarObj->setPropertiesFromArray($_POST['calendar']);
                $calendarObj->save();

                $this->flash('Calendar Saved!', self::FLASH_TYPE_OK);
                $this->redirect('/dashboard/schedulizer/calendars/edit', $calendarObj->getCalendarID());
            }catch(Exception $e){
                $flashMsg = ($e instanceof ADODB_Exception) ? $e->msg : $e->getMessage();
                $this->flash("Unable to save: {$flashMsg}", self::FLASH_TYPE_ERROR);
                $this->redirect('/dashboard/schedulizer/calendars/edit', $calendarObj->getCalendarID());
            }
        }


        /**
         * @todo Save repeatMonthlyMethod
         * @todo Save repeat yearly
         * @todo Validations
         * @param null $id
         */
        public function save_event( $id = null ){
            try {
                $saveEventProcessor = new SchedulizerSaveEventProcessor( SchedulizerEvent::getByID($id), $_POST );

                $saveEventProcessor->transform()
                                   ->validate()
                                   ->persist();

                $this->formResponder(true, 'Event saved.', array(
                    'eventID'   => $saveEventProcessor->getEventObj()->getEventID(),
                    'title'     => $saveEventProcessor->getEventObj()->getTitle()
                ));

            }catch(Exception $e){
                $this->formResponder(false, $e->getMessage());
            }
        }


        /**
         * Delete an event.
         * @todo test all recurring methods and database stuff
         * gets cleaned up ok.
         * @todo output the name of the event that was just
         * deleted.
         * @param int|null $id
         */
        public function delete_event( $id = null ){
            try {
                if( ! ((int)$id >= 1) ){
                    throw new Exception('Event ID empty; delete failed.');
                }

                // proceed
                $eventObj   = SchedulizerEvent::getByID($id);
                $eventTitle = $eventObj->getTitle();
                $eventObj->delete();

                $this->formResponder(true, t('Deleted event %s', $eventTitle));
            }catch(Exception $e){
                $this->formResponder(false, $e->getMessage());
            }
        }
    }
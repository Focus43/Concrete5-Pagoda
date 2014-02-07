<?php

    /**
     * Class SchedulizerUtilitiesEventJsonFormatter
     *
     * @package Schedulizer
     */
    class SchedulizerUtilitiesEventJsonFormatter {

        /**
         * @access private
         * @var SchedulizerEventList
         */
        private $eventListObj;


        /**
         * $ echo new SchedulizerUtilitiesEventJsonFormatter($eventListObj);
         *
         * @return string JSON
         */
        public function __toString(){
            return Loader::helper('json')->encode( $this->getFormatted() );
        }


        /**
         * Pass in a prepared EventListObject and this'll handle the rest.
         *
         * @param SchedulizerEventList $eventListObj
         */
        public function __construct( SchedulizerEventList $eventListObj ){
            $this->eventListObj = $eventListObj;
        }


        /**
         * Get results from the EventListObject and make them into JSON-serializable
         * array of objects.
         *
         * @access public
         * @return array
         */
        public function getFormatted(){
            $results = $this->results();

            $eventsList = array();
            foreach($results AS $eventData){
                $eventsList[] = (object) array(
                    'id'            => $eventData['id'],
                    'title'         => $eventData['title'],
                    'start'         => $eventData['startLocalized'],
                    'end'           => $eventData['endLocalized'],
                    'allDay'        => (bool) $eventData['isAllDay'] ? true : false,
                    'color'         => $eventData['colorHex'],
                    'textColor'     => SchedulizerEventColors::textColor( $eventData['colorHex'] ),
                    'isAlias'       => (int) $eventData['isAlias'],
                    'isRepeating'   => (int) $eventData['isRepeating'],
                    'repeatMethod'  => $eventData['repeatTypeHandle']
                );
            }

            return $eventsList;
        }


        /**
         * Memoize the results from eventListObj->get() so query is never exec'd repetitively.
         *
         * @access protected
         * @return array
         */
        protected function results(){
            if( $this->_results === null ){
                $this->_results = $this->eventListObj->get();
            }
            return $this->_results;
        }

    }
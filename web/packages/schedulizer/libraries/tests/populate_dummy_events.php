<?php

    class SchedulizerTestPopulateDummyEvents {


        private $repeatTypeMethods = array(
            'daily', 'weekly', 'monthly', 'yearly'
        );


        private $timezones = array(
            'America/New_York', 'America/Denver', 'America/Los_Angeles'
        );


        public function __construct( $number = 25 ){
            $this->_numberOfEvents = $number;
        }


        /**
         * Create x number of dummy events
         */
        public function run(){

            for( $i = 0; $i <= $this->_numberOfEvents; $i++ ){
                $isRepeating         = mt_rand(0,1);
                $repeatTypeHandle    = $this->repeatTypeMethods[ mt_rand(0,3) ];
                $repeatMonthlyMethod = mt_rand(0,1);

                $startUTC = new DateTime(mt_rand(2013,2014) . '-' . sprintf("%02d", mt_rand(8,12)) . '-' . sprintf("%02d", mt_rand(15,29)) . ' ' . sprintf("%02d", mt_rand(1,23)) .  ':30:00');

                $endUTC = clone $startUTC;
                $endUTC->modify('+'.mt_rand(1,8).' hours 30 minutes');

                $repeatEndUTC = clone $endUTC;
                $repeatEndUTC->modify('+1 year');
                $repeatEndUTC->modify('+' . mt_rand(1,12) . ' month');
                $repeatEndUTC->modify('+' . mt_rand(1,29) . ' day');

                $newEventObj = new SchedulizerEvent(array(
                    'title'                 => 'Test Generated ' . mt_rand(1,1999),
                    'calendarID'            => mt_rand(1,2),
                    'startUTC'              => $startUTC->format('Y-m-d H:i:s'),
                    'endUTC'                => $endUTC->format('Y-m-d H:i:s'),
                    'useCalendarTimezone'   => 0,
                    'timezoneName'          => $this->timezones[mt_rand(0,2)],
                    'repeatTypeHandle'      => $repeatTypeHandle,
                    'repeatEvery'           => mt_rand(1,31),
                    'isRepeating'           => $isRepeating,
                    'isAllDay'              => mt_rand(0,1),
                    'repeatIndefinite'      => mt_rand(0,1),
                    'repeatMonthlyMethod'   => $repeatMonthlyMethod,
                    'repeatEndUTC'          => $repeatEndUTC->format('Y-m-d H:i:s'),
                    'colorHex'              => '#' . mt_rand(1,9) . mt_rand(1,9) . mt_rand(1,9) . mt_rand(1,9) . mt_rand(1,9) . mt_rand(1,9),
                    'ownerID'               => mt_rand(1,2340938)
                ));

                // persist the event
                $newEventObj->save();

                if( (bool) $isRepeating ){
                    // default for daily/yearly
                    $settings = array();

                    if( $repeatTypeHandle == 'weekly' ){
                        $settings['weekday_index'] = (array) array_rand( range(1,7), mt_rand(1,7) );
                    }

                    if( $repeatTypeHandle == 'monthly' ){
                        $settings['monthly']['method'] = $repeatMonthlyMethod;
                        if( $repeatMonthlyMethod == SchedulizerEvent::REPEAT_MONTHLY_SPECIFIC_DATE ){
                            $settings['monthly']['specific_day'] = mt_rand(1,29);
                        }
                        if( $repeatMonthlyMethod == SchedulizerEvent::REPEAT_MONTHLY_WEEK_AND_DAY ){
                            $settings['monthly']['week'] = mt_rand(1,4);
                            $settings['monthly']['weekday'] = mt_rand(1,7);
                        }
                    }

                    SchedulizerEventRepeat::save($newEventObj, $settings);
                }
            }

        }

    }
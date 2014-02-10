<?php

    class DashboardSchedulizerSettingsController extends SchedulizerController {

        public function on_start(){
            parent::on_start();
            $this->addHeaderItem($this->getHelper('html')->css('app-dashboard.css', self::PACKAGE_HANDLE));
            $this->addFooterItem($this->getHelper('html')->javascript('app-dashboard.js', self::PACKAGE_HANDLE));

            $this->set('formHelper', Loader::helper('form'));
            $this->set('dateHelper', Loader::helper('date'));
            $this->set('configDefaultTimezone', $this->packageConfig()->get( SchedulizerPackage::DEFAULT_TIMEZONE ));
        }


        /**
         * Default timezone
         */
        public function save_default_timezone(){
            if( ! empty($_POST['config']) ){
                foreach( $_POST['config'] AS $configKey => $val ){
                    $this->packageConfig()->save( $configKey, $val );
                }
            }

            $this->formResponder(true, 'Default timezone saved.');
        }

    }
<?php

    class SchedulizerController extends Controller {

        const PACKAGE_HANDLE	= 'schedulizer',
              FLASH_TYPE_OK		= 'success',
              FLASH_TYPE_ERROR	= 'error';


        /**
         * Proxies to the flashNow() method through on_start() (see notes on that below).
         * @param string $msg
         * @param string $type
         * @return void
         */
        protected function flash( $msg = 'Success', $type = self::FLASH_TYPE_OK ){
            $_SESSION['flash_msg'] = array(
                'msg'  => $msg,
                'type' => $type
            );
        }


        /**
         * Ruby on Rails "flash" functionality ripoff.
         * @param string $msg Optional, set the flash message
         * @param string $type Optional, set the class for the alert
         * @return void
         */
        protected function flashNow( $msg = 'Success', $type = self::FLASH_TYPE_OK ){
            $this->set('flash', array(
                'msg'  => $msg,
                'type' => $type
            ));
        }


        /**
         * Execute thing in C5's on_start hook.
         * @return void
         */
        public function on_start(){
            $this->addHeaderItem('<meta name="schedulizer-tools" content="'.SCHEDULIZER_TOOLS_URL.'" />');

            // handle flash message
            if( isset($_SESSION['flash_msg']) ){
                $this->flashNow($_SESSION['flash_msg']['msg'], $_SESSION['flash_msg']['type']);
                unset($_SESSION['flash_msg']);
            }
        }


        /**
         * @return UserInfo
         */
        protected function userInfoObj(){
            if( $this->_userInfoObj === null ){
                $this->_userInfoObj = UserInfo::getByID( $this->userObj()->getUserID() );
            }
            return $this->_userInfoObj;
        }


        /**
         * @return User
         */
        protected function userObj(){
            if( $this->_userObj === null ){
                $this->_userObj = new User;
            }
            return $this->_userObj;
        }


        /**
         * Send back an ajax response if request headers accept json, or handle
         * redirect if just doing regular http
         * @param bool $okOrFail
         * @param mixed String || Array $message
         * @param array $extraData Array of extra properties to encode in response
         * @return void
         */
        protected function formResponder( $okOrFail, $message, $extraData = array() ){
            // determine content type header
            $contentType = 'text/plain';
            if( isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) || $_SERVER['X_REQUESTED_WITH'] == 'XMLHttpRequest' ){
                $contentType = 'application/json';
            }
            // set the header
            header("Content-Type: {$contentType}");

            // echo out json response
            echo $this->getHelper('json')->encode( (object) (array(
                'code'		=> (int) $okOrFail,
                'messages'	=> is_array($message) ? $message : array($message)
            ) + $extraData));

            exit;
        }


        /**
         * Get Config object, restricted to the SchedulizerPackage settings.
         * @return Config
         */
        public function packageConfig(){
            if( $this->_packageConfigObj === null ){
                $this->_packageConfigObj = new Config();
                $this->_packageConfigObj->setPackageObject( $this->packageObject() );
            }
            return $this->_packageConfigObj;
        }


        /**
         * Get the package object; if it hasn't been instantiated yet, load it.
         * @return SchedulizerPackage
         */
        private function packageObject(){
            if( $this->_packageObj == null ){
                $this->_packageObj = Package::getByHandle( self::PACKAGE_HANDLE );
            }
            return $this->_packageObj;
        }


        /**
         * "Memoize" helpers so they're only loaded once.
         * @param string $handle Handle of the helper to load
         * @param string $pkg Package to get the helper from
         * @return ...Helper class of some sort
         */
        public function getHelper( $handle, $pkg = false ){
            $helper = '_helper_' . preg_replace("/[^a-zA-Z0-9]+/", "", $handle);
            if( $this->{$helper} === null ){
                $this->{$helper} = Loader::helper($handle, $pkg);
            }
            return $this->{$helper};
        }

    }
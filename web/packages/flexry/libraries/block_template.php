<?php

    /**
     * This class is basically a helper for creating/storing/recalling data for block templates.
     * Instead of requiring database tables be setup for every template that wants to store data,
     * you can use this class to output field names and retrieve values that are stored in a
     * serialized JSON document by the FlexryGalleryBlockController class. Any template that
     * contains a settings.php file can make use of this, and effectively create data fields on
     * the fly.
     *
     * @class FlexryBlockTemplateOptions
     * @property string $_handle
     * @property string $_path
     * @property stdObj $_data
     */
    class FlexryBlockTemplateOptions {

        protected $_handle, $_path, $_data;


        /**
         * Can only be instantiated from the setup() static method.
         * @param $handle
         * @param $templatePath
         */
        protected function __construct( $handle, $templatePath ){
            $this->_handle  = $handle;
            $this->_path    = $templatePath;
        }


        /**
         * Takes care of running the template helper setup, and return a NEW instance of
         * FlexryBlockTemplateOptions.
         * @param string $handle
         * @param string $templatePath
         * @param stdObj|null $templateData
         * @return FlexryBlockTemplateOptions
         */
        public static function setup( $handle, $templatePath, $templateData = null ){
            // create unique instance
            $instance = new self( $handle, $templatePath );

            // check arguments
            if( (is_string($handle) && is_string($templatePath)) && !(empty($handle) || empty($templatePath)) ){
                try {
                    if( is_object( $templateData ) && is_object( $templateData->{ $handle } ) ){
                        $instance->_data = $templateData->{ $handle };
                    }
                }catch(Exception $e){ /* fail gracefully */ }
            }

            return $instance;
        }


        /**
         * This feels fucking weird - but in order to limit the scope of variables visible
         * to the include() function, use this method from a public instance to run the
         * _renderSettingsForm, and pass an instance of the instantiated $this into it.
         * @return void
         */
        public function renderForm(){
            $this->_renderSettingsForm( $this );
        }


        /**
         * Return the prepared name for the field
         * @param $name
         * @return string
         */
        public function field( $name ){
            $strPosArrayMarker = strpos($name, '[');
            if( $strPosArrayMarker !== false ){
                return t("templateData[%s][%s]%s", $this->_handle, current(explode('[', $name)), substr($name, $strPosArrayMarker));
            }
            return t("templateData[%s][%s]", $this->_handle, $name);
        }


        /**
         * @parameters string : Pass in an unlimited number of parameters to get the stored
         * property.
         * @return mixed
         */
        public function value(){
            // return immediately if $_data property isn't an object
            if( ! is_object($this->_data) ){ return null; }
            // proceed
            try {
                if( func_num_args() >= 1 ){ // ensure 1 or > arguments were passed
                    $result = $this->_data;
                    foreach( func_get_args() AS $idx => $property ){
                        if( is_object($result) && is_string($property) ){
                            $result = $result->{$property};
                        }else{ break; /* finito - move on */ }
                    }
                    return $result;
                }
                return null;
            }catch(Exception $e){ return null; }
        }


        /**
         * To help with setting default options, use this method and pass in a value (usually
         * the result from the value() method above), and a default. If the passed $value
         * is empty, it'll return the default.
         * @param $value
         * @param $default
         * @return mixed
         */
        public static function valueOrDefault( $value, $default ){
            return (!empty($value) || (is_string($value) && $value === '0')) ? $value : $default;
        }


        /**
         * Run the include() function in an independent function to keep scope limited.
         * @param FlexryBlockTemplateOptions $templateHelper
         * @return void
         */
        protected function _renderSettingsForm( FlexryBlockTemplateOptions $templateHelper ){
            // simply including this in the scope makes it the only $var available to the include
            $templateHelper;
            try {
                if( file_exists( "{$this->_path}/settings.php" ) ){
                    include( "{$this->_path}/settings.php" );
                }
            }catch(Exception $e){ echo t("Unable to find settings.php file for template %s", $this->_handle); }
        }

    }
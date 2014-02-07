<?php

    class SchedulizerEventColors {

        const GREEN         = '#A3D900',
              BLUE          = '#3A87AD',
              RED           = '#DE4E56',
              PURPLE        = '#BFBFFF',
              YELLOW        = '#FFFF73',
              ORANGE        = '#FFA64D',
              GRAY          = '#CCCCCC',
              LIGHT_BLUE    = '#00B7FF',
              DARK_GRAY     = '#222222';

        protected static $_colorPairs = null;

        /**
         * Get a list of available colors.
         * @return object
         */
        public static function pairs(){
            if( self::$_colorPairs === null ){
                self::$_colorPairs = (object) array(
                    self::GREEN         => (object) array('hex' => self::GREEN, 'textColor' => '#111111'),
                    self::BLUE          => (object) array('hex' => self::BLUE, 'textColor' => '#ffffff'),
                    self::RED           => (object) array('hex' => self::RED, 'textColor' => '#ffffff'),
                    self::PURPLE        => (object) array('hex' => self::PURPLE, 'textColor' => '#111111'),
                    self::YELLOW        => (object) array('hex' => self::YELLOW, 'textColor' => '#111111'),
                    self::ORANGE        => (object) array('hex' => self::ORANGE, 'textColor' => '#111111'),
                    self::GRAY          => (object) array('hex' => self::GRAY, 'textColor' => '#111111'),
                    self::LIGHT_BLUE    => (object) array('hex' => self::LIGHT_BLUE, 'textColor' => '#111111'),
                    self::DARK_GRAY     => (object) array('hex' => self::DARK_GRAY, 'textColor' => '#FFFFFF')
                );
            }

            return self::$_colorPairs;
        }


        /**
         * Get the text color as hex value.
         * @param $hex
         * @return string
         */
        public static function textColor( $hex ) {
            return self::pairs()->{$hex}->textColor;
        }

    }
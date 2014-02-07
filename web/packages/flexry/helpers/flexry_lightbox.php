<?php

    /**
     * Helper to render the javascript output that initializes a FlexryLightbox.
     * Pass in selectors to bindTo and itemTargets, and the block takes care of
     * the rest by outputting the settings from the block record.
     */
    class FlexryLightboxHelper {

        protected $_controller, $_parentSelector, $_itemTargets;

        /**
         * @param string|null $selector
         * @return FlexryLightboxHelper
         */
        public function bindTo( $selector = null ){
            if( ! is_null($selector) ){
                $this->_parentSelector = $selector;
            }
            return $this;
        }


        /**
         * @param string|null $selector
         * @return FlexryLightboxHelper
         */
        public function itemTargets( $selector = null ){
            if( ! is_null($selector) ){
                $this->_itemTargets = $selector;
            }
            return $this;
        }


        /**
         * @param FlexryGalleryBlockController $controller
         * @return FlexryLightboxHelper
         */
        public function passController( FlexryGalleryBlockController $controller = null ){
            if( ! is_null($controller) ){
                $this->_controller = $controller;
            }
            return $this;
        }


        /**
         * The keys in this array match *exactly* the settings available via javascript;
         * this just takes care of making them a JSON-style object {} and making sure
         * true/falses aren't string, proper things are strings, numbers, etc.
         * @return string
         */
        protected function keyValuePairs(){
            $settings = array(
                'itemTargets'        => t("'%s'", $this->_itemTargets),
                'maskColor'          => t("'#%s'", $this->_controller->lbMaskColor),
                'maskOpacity'        => (float) $this->_controller->lbMaskOpacity,
                'maskFadeSpeed'      => (int) $this->_controller->lbMaskFadeSpeed,
                'closeOnClick'       => ((bool)$this->_controller->lbCloseOnClick) ? 'true' : 'false',
                'transitionEffect'   => t("'%s'", $this->_controller->lbTransitionEffect),
                'transitionDuration' => (int)$this->_controller->lbTransitionDuration,
                'captions'           => ((bool)$this->_controller->lbCaptions) ? 'true' : 'false',
                'galleryMarkers'     => ((bool)$this->_controller->lbGalleryMarkers) ? 'true' : 'false',
            );

            return implode(',', array_map(function($v, $k){
                return $k . ':' . $v;
            }, $settings, array_keys($settings)));
        }


        /**
         * Get the string to output to the page after everything is prepared. Built-in check
         * to make sure that the parentSelector and itemSelectors are valid strings.
         * @return string
         */
        public function initOutput(){
            if( $this->_controller->lightboxEnable && is_string($this->_parentSelector) && is_string($this->_itemTargets) ){
                return t("$('%s').flexryLightbox({%s});\n", $this->_parentSelector, $this->keyValuePairs());
            }
            return "\n";
        }

    }
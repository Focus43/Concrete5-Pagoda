<?php

    /**
     * Helper to render the javascript output that initializes a FlexryLightbox.
     * Pass in selectors to bindTo and itemTargets, and the block takes care of
     * the rest by outputting the settings from the block record.
     */
    class FlexryLightboxHelper {

        protected $_controller, $_parentSelector, $_itemTargets;
        protected $_delegateTarget = false;

        /**
         * @param string|null $selector
         * @return FlexryLightboxHelper
         */
        public function bindTo( $selector = false ){
            if( is_string($selector) ){
                $this->_parentSelector = $selector;
            }
            return $this;
        }


        /**
         * @param string|null $selector
         * @return FlexryLightboxHelper
         */
        public function itemTargets( $selector = false ){
            if( is_string($selector) ){
                $this->_itemTargets = $selector;
            }
            return $this;
        }


        /**
         * @param string|null $selector
         * @return FlexryLightboxHelper
         */
        public function setDelegateTarget( $selector = false ){
            if( is_string($selector) ){
                $this->_delegateTarget = $selector;
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
            return Loader::helper('json')->encode((object)array(
                'itemTargets'        => sprintf('%s', $this->_itemTargets),
                'delegateTarget'     => is_string($this->_delegateTarget) ? $this->_delegateTarget : (bool)$this->_delegateTarget,
                'maskColor'          => sprintf('#%s', $this->_controller->lbMaskColor),
                'maskOpacity'        => (float) $this->_controller->lbMaskOpacity,
                'maskFadeSpeed'      => (int) $this->_controller->lbMaskFadeSpeed,
                'closeOnClick'       => (bool) ((bool) $this->_controller->lbCloseOnClick),
                'transitionEffect'   => sprintf('%s', $this->_controller->lbTransitionEffect),
                'transitionDuration' => (int) $this->_controller->lbTransitionDuration,
                'captions'           => (bool) ((bool) $this->_controller->lbCaptions),
                'galleryMarkers'     => (bool) ((bool) $this->_controller->lbGalleryMarkers)
            ));
        }


        /**
         * Get the string to output to the page after everything is prepared. Built-in check
         * to make sure that the parentSelector and itemSelectors are valid strings.
         * @return string
         */
        public function initOutput(){
            if( $this->_controller->lightboxEnable && is_string($this->_parentSelector) && is_string($this->_itemTargets) ){
                return sprintf("$('#%s').flexryLightbox(%s);\n", $this->_parentSelector, $this->keyValuePairs());
            }
            return "\n";
        }

    }
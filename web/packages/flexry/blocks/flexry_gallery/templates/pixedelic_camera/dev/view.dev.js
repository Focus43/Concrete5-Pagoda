/**
 * Just a wrapper for the Camera plugin to work in the context of the Flexry
 * stuff.
 */
(function( $ ){

    function FlexryCamera( $selector, _settings ){

        var config = $.extend(true, {}, {
            cols : Math.floor(Math.random()*(8-3+1)+3), // randomize
            rows : Math.floor(Math.random()*(6-2+1)+2)  // randomize
        }, _settings);

        /**
         * Initialize the camera plugin; aaaaaand thats about it for meow.
         */
        $selector.camera(config);

        return {
            $element : $selector,
            settings : _settings,
            configs  : config
        }
    }


    /**
     * This is the actual function visible to jQuery. Below, we create a new instace
     * of FlexryCamera, bind it to the selector's data attribute, then return
     * for chaining.
     * @param {} _settings
     * @returns jQuery
     */
    $.fn.flexryCamera = function( _settings ){
        return this.each(function(idx, _element){
            var $selector = $(_element),
                _instance = new FlexryCamera($selector, _settings);
            $selector.data('flexryCamera', _instance);
        });
    }

})( jQuery );
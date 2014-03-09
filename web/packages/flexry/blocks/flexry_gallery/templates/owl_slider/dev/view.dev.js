/**
 * Just a wrapper for the OwlSlider plugin to work in the context of the Flexry
 * stuff.
 */
(function( $ ){

    function FlexryOwl( $selector, _settings ){

        var config = $.extend(true, {}, {
            items: 7,
            singleItem: false
        }, _settings);

        /**
         * Initialize the camera plugin; aaaaaand thats about it for meow.
         */
        $selector.owlCarousel(config);

        return {
            $element : $selector,
            settings : _settings,
            configs  : config
        }
    }


    /**
     * This is the actual function visible to jQuery. Below, we create a new instace
     * of FlexryOwl, bind it to the selector's data attribute, then return
     * for chaining.
     * @param {} _settings
     * @returns jQuery
     */
    $.fn.flexryOwl = function( _settings ){
        return this.each(function(idx, _element){
            var $selector = $(_element),
                _instance = new FlexryOwl($selector, _settings);
            $selector.data('flexryOwl', _instance);
        });
    }

})( jQuery );
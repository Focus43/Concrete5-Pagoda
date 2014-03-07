/**
 * Just a wrapper for the Camera plugin to work in the context of the Flexry
 * stuff.
 */
(function( $ ){

    function FlexryCamera( $selector, _settings ){

        var config = $.extend(true, {}, {

        }, _settings);


        $selector.camera({
            fx          : 'stampede',
            transPeriod : 550,
            thumbnails  : true,
            height      : '300px'
        });


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
/**
 * Just a wrapper for the Camera plugin to work in the context of the Flexry
 * stuff.
 */
(function( $ ){

    function FlexryCamera( $selector, _settings ){

        var config = $.extend(true, {}, {
            imagePath   : '/packages/flexry/images/camera/',
            pagination  : true,
            cols        : Math.floor(Math.random()*(8-3+1)+3), // randomize
            rows        : Math.floor(Math.random()*(6-2+1)+2)  // randomize
        }, _settings);


        $selector.camera(config);

        /*$selector.camera({
            alignment   : 'center',
            imagePath   : '/packages/flexry/images/camera/',
            //autoAdvance : false,
            loaderPadding: 0,
            loaderStroke : 10,
            // loader bar
            barPosition : 'top',
            //barDirection : 'TopToBottom',
            loader      : 'bar',
            fx          : 'random',
            transPeriod : 500,
            time : 1000,
            thumbnails  : true,
            //height      : '450px',
            cols        : 15,
            rows        : 2
        });*/


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
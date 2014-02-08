(function( $, gsTween, gsTimeline ){

    function FlexryDicer( $selector, _settings ){

        var _self  = this,
            config = $.extend(true, {}, {
                slices: 4
            }, _settings);


        /**
         * Batch preload a group of images.
         * @param _array of objects {} with property src_thumb
         * @returns {*|promise}
         */
        function batchLoadImages( $jqArray ){
            return $.Deferred(function( _batchTask ){
                var _length = $jqArray.length,
                    _loaded = 0;
                $jqArray.each(function(index, image){
                    var inMem = new Image();
                    inMem.onload = function(){
                        _loaded++;
                        if(_loaded === _length){
                            _batchTask.resolve();
                        }
                    }
                    inMem.src = image.getAttribute('src');
                });
            }).promise();
        }


        function trace(){
            var _current = $('.current img', $selector),
                _clientW = _current[0].clientWidth,
                _clientH = _current[0].clientHeight;
            console.log(_clientW, _clientH);
        }


        batchLoadImages( $('img', $selector) ).done(function(){
            trace();
        })


        // @public methods
        return {
            $element     : $selector,
            initSettings : _settings,
            configs      : config
        }
    }

    /**
     * This is the actual function visible to jQuery. Below, we create a new instace
     * of FlexryDicer, bind it to the selector's data attribute, then return
     * for chaining.
     * @param {} _settings
     * @returns jQuery
     */
    $.fn.flexryDicer = function( _settings ){
        return this.each(function(idx, _element){
            var $selector = $(_element),
                _instance = new FlexryDicer( $selector, _settings );
            $selector.data('flexryDicer', _instance);
        });
    }

})( jQuery, TweenLite, TimelineLite );
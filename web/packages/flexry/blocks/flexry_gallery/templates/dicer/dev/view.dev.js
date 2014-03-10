(function( $, gsTween, gsTimeline ){

    function FlexryDicer( $selector, _settings ){

        var $images     = $('img', $selector),
            _timeline   = new gsTimeline(),
            _tweens     = [],
            config = $.extend(true, {}, {
                bricks: 1
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


        batchLoadImages( $images ).done(function(){

            /*var $items = $('.flexry-dicer-item', $selector),
                length = $items.length;
            $items.each(function(_index, element){
                    _tweens.push( gsTween.to(element, .35, {
                        //z:       -((length-_index)/10),
                        y:       -((length-_index)*10),//length-_index,//-((length-_index)/10),
                        scale:   1.1 + ((length-_index)/100),
                        opacity: ((100/length)*_index)/100,
                        delay:   .35
                    }));
            });
            _timeline.add(_tweens, '+=0', 'normal',.1);*/
            console.log($images.length, Math.floor($images.length/2));
            $images.eq(Math.floor($images.length/2)).addClass('current');

            var _maxHeight = (function(images){
                var _max = 1;
                $.each(images, function(idx,el){
                    _max = (el.clientHeight > _max) ? el.clientHeight : _max;
                });
                return _max;
            })($images);
            console.log(_maxHeight)
        });


        // @public methods
        return {
            $element     : $selector,
            initSettings : _settings,
            configs      : config,
            timeline     : function(){
                return _timeline;
            }
        }
    }

    /**
     * This is the actual function visible to jQuery. Below, we create a new instance
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
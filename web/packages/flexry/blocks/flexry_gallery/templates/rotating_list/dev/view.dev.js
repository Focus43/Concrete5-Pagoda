(function( $ ){

    function FlexryRtl( $selector, _settings ){

        var $groups     = $('.flexry-rtl-group', $selector),
            _groupCount = $groups.length,
            _groupCache = {},
            _paused     = false,
            _timeout    = null,
            config = $.extend(true, {}, {
                itemPadding : 5,
                rotateTime  : 1500,
                animateTime : 750 // fixed for now
            }, _settings);


        /**
         * Cache the group jQuery selectors. Also, cache the currentIndex, childItems,
         * and length of children as properties on the elements. Easier for use when
         * iterating and determining next, and pausing/restarting.
         * @param int _index
         * @returns jQuery
         */
        function _group( _index ){
            if( ! _groupCache[_index] ){
                _groupCache[_index] = $( $groups[_index] );
                _groupCache[_index]._currentIndex = 0;
                _groupCache[_index]._childItems   = $('.flexry-rtl-item', _groupCache[_index]);
                _groupCache[_index]._childLength  = _groupCache[_index]._childItems.length;
            }
            return _groupCache[_index];
        }


        /**
         * Pause the iterator.
         * @return void
         */
        function _pause(){
            clearTimeout(_timeout);
            _paused = true;
        }


        /**
         * Continue the iterator
         * @return void
         */
        function _continue(){
            if( _paused ){ _paused = false; _iterator(); }
        }


        /**
         * Meat and potatoes : iterate through the elements. Calls itself over and over
         * with the specified config.rotateTime.
         * @return void
         */
        function _iterator(){
            (function loopsidasy( _current ){
                _timeout = setTimeout(function(){
                    var $group      = _group(_current),
                        _nextIndex  = ($group._currentIndex + 1) % $group._childLength,
                        $nextItem   = $group._childItems.eq(_nextIndex);
                    $group.height( $('img', $nextItem)[0].clientHeight + config.itemPadding*2 );
                    $group._childItems.removeClass('current');
                    $nextItem.addClass('current');
                    $group._currentIndex = _nextIndex;
                    loopsidasy( ((_current+1)%_groupCount) );
                }, config.rotateTime);
            })( 0 );
        }


        /**
         * Listen for open/close events emitted by the lightbox on the selector. Does
         * not matter if the Lightbox is in use or declared, its just waiting for events
         * to be emitted.
         */
        $selector.on('flexrylb.open', function(){
            _pause();
        }).on('flexrylb.close', function(){
            _continue();
        });


        // Finally, kick off the iterator on init.
        _iterator();

        // @public methods
        return {
            config  : config,
            pause   : _pause,
            loop    : _continue
        }
    }


    /**
     * Bind and initialize the flexryRtl class.
     * @param {} _settings
     * @returns {*|each|each|HTMLElement|Array|Object|each}
     */
    $.fn.flexryRtl = function( _settings ){
        return this.each(function(idx, _element){
            var $selector = $(_element);
            if( ! ($selector.data('flexryRtl')) ){
                $selector.data('flexryRtl', new FlexryRtl( $selector, _settings ));
            }
        });
    }

})( jQuery );
(function( $ ){

    function FlexryAccordion( $selector, _settings ){

        var $items           = $('.accordion-item', $selector),
            _transitionSpeed = 650,
            _autoPlayMin     = 750,
            _itemCount       = $items.length, // cache selector length
            _containerWidth  = null, // cache for container width
            _nodeMaxWidth    = null, // cache for calc'd max width of nodes
            _openIndex       = 0, // cache for currently opened node (autoplay)
            _loopInterval    = null,
            _eventDefault    = 'mouseenter',
            _sensitiveness   = null,
            config           = $.extend(true, {}, {
                trigger         : _eventDefault,
                transitionSpeed : _transitionSpeed,
                nodeMaxWidth    : .6,
                autoPlay        : false,
                autoPlaySpeed   : 3000,
                sensitivity     : 175,
                pauseMouseOver  : true
            }, _settings);


        /**
         * Check if the transition speeds need to be adjusted from the defaults.
         */
        if( +(config.transitionSpeed) !== _transitionSpeed ){
            var vendors = ['-webkit-', '-moz-', '-o-', '-ms-', ''],
                time1   = +(config.transitionSpeed)/1000,
                time2   = time1/ 2,
                $items  = $('.accordion-item', $selector),
                _style  = $items[0].getAttribute('style');
            $('.accordion-item', $selector).attr('style', _style + ';' + vendors.join('transition-duration: '+time1+'s;'));
            $('.meta', $selector).attr('style', vendors.join('transition-duration: '+time2+'s;'));
        }


        /**
         * Check if the trigger event is indeed a mouseenter,
         * and if anything but, set the sensitivity to 0!
         */
        if( config.trigger !== _eventDefault ){
            config.sensitivity = 0;
        }


        /**
         * Re-cache and set values on window resize.
         */
        $(window).on('resize', function(){
            _containerWidth = $selector.width();
            _nodeMaxWidth   = _containerWidth*config.nodeMaxWidth;
            $items.filter('.open').trigger(config.trigger);
        }).trigger('resize'); // trigger on init so cached values get set


        /**
         * The event handler for whichever trigger type (ie. click, or mouseenter)
         * opens a node.
         */
        $selector.on(config.trigger, '.accordion-item', function(_event, _auto){
            var $this = $(this);
            // clear any _timers (even if null!)
            clearTimeout(_sensitiveness);
            // reset the _timer
            _sensitiveness = setTimeout(function(){
                var _image = $('img', $this),
                    _width = _image[0].clientWidth;
                // enforce max-width as percentage of container
                _width = (_width >= _nodeMaxWidth) ? _nodeMaxWidth : _width;
                // change errthang; triggers CSS transitions
                $this.width(_width).addClass('open').siblings('.accordion-item').removeClass('open').width((_containerWidth-_width)/(_itemCount-1));
                // set the currently opened index cache
                _openIndex = $this.index('.accordion-item');
                // determine whether to pause the iterator, if applicable
                if( !_auto ){ _pause(); }
            }, +(config.sensitivity));
        });


        /**
         * By default, if the config.trigger is a mouseenter event, pausing
         * will be handled anyways. But if its a click event, then we create
         * the mouseenter event handler anyways.
         */
        if( config.trigger !== 'mouseenter' && config.pauseMouseOver ){
            $selector.on('mouseenter', function(){
                _pause();
            });
        }


        /**
         * On leaving; if auto play is enabled.
         */
        $selector.on('mouseleave', function(){
            _play();
        });


        /**
         * Auto play start
         * @private
         */
        function _play(){
            clearInterval(_loopInterval);
            if( config.autoPlay ){
                _loopInterval = setInterval(function(){
                    _openIndex = (_openIndex+1)%_itemCount;
                    $items.eq(_openIndex).trigger(config.trigger, true);
                }, (config.autoPlaySpeed > _autoPlayMin) ? config.autoPlaySpeed : _autoPlayMin);
            }
        }


        /**
         * Auto play stop... er, pause more accurately.
         * @private
         */
        function _pause(){
            clearInterval(_loopInterval);
        }


        /**
         * If autoPlay is enabled...
         */
        if( config.autoPlay ){
            // start by opening node @ index 0
            $items.eq(_openIndex).trigger(config.trigger, true);
            // then kick off the iterator
            _play();
        }


        // @public methods
        return {
            play  : _play,
            pause : _pause
        }
    }


    /**
     * Bind and initialize the FlexryAccordion class.
     * @param {} _settings
     * @returns {*|each|each|HTMLElement|Array|Object|each}
     */
    $.fn.flexryAccordion = function( _settings ){
        return this.each(function(idx, _element){
            var $element  = $(_element),
                _instance = new FlexryAccordion( $element, _settings );
            $element.data('flexryAccordion', _instance);
        });
    }

})( jQuery );
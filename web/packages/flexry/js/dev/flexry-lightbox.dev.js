/**
 * FlexryLightbox : fully responsive, mobile-first Lightbox gallery with CSS transitions
 * and graceful fallback to sh*tty browsers.
 * @author Jonathan Hartman | Focus43, LLC.
 * @param $selector
 * @param _settings
 * @returns {{}}
 * @constructor
 */
(function( $ ){

    function FlexryLightbox( $selector, _settings ){

        var _self               = this,
            _supported          = $('html').hasClass('flexry-csstransforms'),
            status_loaded_true  = true,
            status_loaded_false = false,
            _transitionDuration = 200,
            _imagePromiseCache  = {},
            _currentIndexCache  = null,
            _listDataLength     = 0,
            _listDataCache      = false,
            _fxRandomized       = false, // determined in the build method (set internally)
            _fxList             = ['', 'fx-spin', 'fx-fall', 'fx-zoom', 'fx-flip-vertical', 'fx-flip-horizontal', 'fx-slide-in-right', 'fx-slide-in-left', 'fx-side-fall', 'fx-slit'],
            // merge passed options w/ the defaults
            config = $.extend(true, {}, {
                maskColor           : '#2a2a2a',
                maskOpacity         : .85,
                maskFadeSpeed       : 250,
                closeOnClick        : true,
                itemTargets         : '.lightbox-item',
                delegateTarget      : false,
                transitionEffect    : '',
                transitionDuration  : _transitionDuration,
                captions            : true,
                gallery             : true,
                galleryMarkers      : true,
                dataSourceMap       : _dataSourceMap
            }, _settings);

        /**
         * Create <div class="flexry-lightbox" /> container, and bind helper methods
         * open / close/ setStatus.
         */
        var $container = (function(){
            var buildPromise,
                $element = $('<div />', {
                    'class' : ['flexry-lightbox', (config.captions ? 'captions' : ''), (config.gallery ? 'arrows' : ''), (config.galleryMarkers ? 'markers' : '')].join(' '),
                    'html'  : '<div data-transition class="'+config.transitionEffect+'"><div class="masker"></div><div class="modal-container"><div class="content"><a class="gallery-arrows prev"></a><a class="gallery-arrows next"></a><div class="caption-container"><div class="caption title"><span></span></div><div class="caption descr"><span></span></div></div><img class="primary-img" /></div></div><div class="loader-container"><div class="inner"></div></div><a class="closer"><span>Close</span></a><div class="gallery-markers"><div class="m-inner"></div></div></div>'
                });

            // determine whether to enable randomizing the effects
            _fxRandomized = (config.transitionEffect === 'randomize');

            // Set transition durations, if not the default 200
            function setCssTransitionDuration(){
                if( +(config.transitionDuration) !== _transitionDuration ){
                    var vendors = ['-webkit-', '-moz-', '-o-', '-ms-', ''],
                        time1   = (config.transitionDuration/1000),
                        time2   = time1/2;
                    $('.modal-container, .content', $element).attr('style', vendors.join('transition-duration: '+time1+'s;'));
                    $('.loader-container', $element).attr('style', vendors.join('transition-duration: '+time2+'s;'));
                }
            }

            // append to the DOM and setup event bindings
            function _build(){
                if( ! buildPromise ){
                    buildPromise = $.Deferred(function( _task ){
                        // set ID here so timestamp is always at time of first launch
                        $element.attr('id', 'flexryLightbox-' + ((new Date()).getTime()) );
                        // add to body
                        $element.appendTo('body');
                        // update masker settings
                        $('.masker', $element).css({background:config.maskColor,opacity:config.maskOpacity});
                        // bind close target (add check so if img-primary, don't close)
                        var $closeTarget = config.closeOnClick ? $element : $('.closer', $element);
                        $closeTarget.on('click', function(_ev){
                            var $targ = $(_ev.target);
                            if($targ.hasClass('primary-img') || $targ.hasClass('caption-container')){return;}
                            $element.close();
                        });
                        // set transition duration, if not default 200
                        setCssTransitionDuration();
                        // build gallery stuff, or if only one image in listData(), hide
                        createGalleryElements();
                        // resolve the _build() task done
                        _task.resolve();
                    }).promise();
                }
                return buildPromise;
            }

            // add an open() method to the jQuery $container
            $element.open = function(){
                return $.Deferred(function( _openTask ){
                    _build().done(function(){
                        $('html').addClass('flexry-lb-active');
                        $element.setStatus(status_loaded_false).fadeIn(config.maskFadeSpeed, function(){
                            _openTask.resolve();
                            // emit an event on the $selector indicating open
                            $selector.trigger('flexrylb.open');
                        });
                    });
                }).promise();
            }

            // add a close() method
            $element.close = function(){
                $('html').removeClass('flexry-lb-active');
                return $element.setStatus(status_loaded_false).fadeOut(config.maskFadeSpeed, function(){
                    // emit an event on the $selector indicating close
                    $selector.trigger('flexrylb.close');
                });
            }

            // convenience "set status" message (swap classes)
            $element.setStatus = function( _to ){
                $element.toggleClass('loaded', _to === status_loaded_true);
                return $element;
            }

            // cache commonly used selectors
            $element.$_transition       = $('[data-transition]', $element);
            $element.$_content          = $('.content', $element);
            $element.$_captionContainer = $('.caption-container', $element);
            $element.$_caption1         = $('.title span', $element);
            $element.$_caption2         = $('.descr span', $element);
            $element.$_markersInner     = $('.m-inner', $element);

            return $element;
        })();


        /**
         * Build gallery navigation and bind events.
         */
        function createGalleryElements(){
            // is the gallery either: disabled, or is there not an object at _listCache() index 1?
            if( !(config.gallery) || !(_listCache()['1']) ){
                $container.removeClass('arrows markers'); return;
            }

            // bind previous/next actions
            $('.gallery-arrows', $container).on('click', function( _clickEv ){
                _clickEv.stopPropagation(); _clickEv.preventDefault();
                if( $(this).hasClass('next') ){
                    navigateNext(); return;
                }
                navigatePrev();
            });

            // create gallery markers?
            if( config.galleryMarkers ){
                // create html elements
                buildGalleryMarkers();
                // bind click event to the markerContainer (but not specific markers), so we can
                // cancel the event propagation and not close the window if user clicks off by an inch
                $container.$_markersInner.on('click', function( _clickEv ){
                    _clickEv.stopPropagation(); _clickEv.preventDefault();
                    if( $(_clickEv.target).hasClass('m') ){
                        navigateToIndex(_clickEv.target.getAttribute('data-cache'));
                    }
                });
            }
        }


        /**
         * Navigate to the next image in the gallery.
         * @return void
         */
        function navigateNext(){
            _displayImage( _listCache()[_currentIndexCache+1] || _listCache()[0] );
        }


        /**
         * Navigate to the previous image...
         * @return void
         */
        function navigatePrev(){
            _displayImage( _listCache()[_currentIndexCache-1] || _listCache()[_listDataLength] );
        }


        /**
         * Navigate to a specific (by index) image in the gallery.
         * @param int _to
         * @return void
         */
        function navigateToIndex( _to ){
            _displayImage( _listCache()[_to] );
        }


        /**
         * Loop through every item in the _listCache data, and compose the HTML for a marker.
         * @return void
         */
        function buildGalleryMarkers(){
            // test here again in case this is being called by the rescanItems public method
            if( config.galleryMarkers ){
                $container.$_markersInner.empty().append(function(){
                    var _html = '';
                    for(var _key in _listCache()){
                        _html += '<div class="m" data-cache="'+_listCache()[_key].index+'"><div class="t"><img src="'+_listCache()[_key].src_thumb+'" /></div></div>';
                    }
                    return _html;
                });
            }
        }


        /**
         * Image loader : create or get existing $.promise() objects representing
         * state of the image.
         * @param string _src
         * @returns $.promise()
         */
        function _getImage( _src ){
            if( ! _imagePromiseCache[_src] ){
                _imagePromiseCache[_src] = $.Deferred(function( _defer ){
                    _defer.notify();
                    var _image    = new Image();
                    _image.onload = function(){ _defer.resolve(_image); }
                    _image.src    = _src;
                }).promise();
            }
            return _imagePromiseCache[_src];
        }


        /**
         * Get a delayer : used (for example) when displaying an image - if the
         * image is already cached and resolves immediately, we want to make sure
         * the animation has time to complete (the CSS animation). Defaults to
         * use the config.transitionDuration.
         * @param int _time
         * @returns {*|promise}
         */
        function _delayer( _time ){
            return $.Deferred(function( _task ){
                setTimeout(function(){ _task.resolve(); }, +(_time || config.transitionDuration) );
            }).promise();
        }


        /**
         * Digest the data object passed in, and take care of kicking off switching
         * the currently displayed image. The _statusWorking variable prevents request
         * queueing if the user were to sit there and click faster than a mutha fucka.
         * @param dataObj : {title: string, description: string, srcThumb: string, srcFull: string}
         */
        var _displayImageWorking = false;
        function _displayImage( _obj ){
            if( ! _displayImageWorking ){
                // block super-fast clicks...
                _displayImageWorking = true;
                // cache the index of item being displayed, of all items
                _currentIndexCache = _obj['index'];
                // set container status to working
                $container.setStatus(status_loaded_false);
                // handle random transitions, if applicable
                if( _fxRandomized === true ){
                    $container.$_transition[0].className = _fxList[Math.floor(Math.random() * _fxList.length)];
                }
                // kickoff the getImage and the delayer simultaneously
                $.when( _getImage(_obj['src_full']), _delayer() ).done(function(){
                    // create a *new* image element here, as the _getImage cache sometimes
                    // returns nullified pointers
                    var imageElement  = new Image();
                    imageElement.src = _obj['src_full'];
                    imageElement.className = 'primary-img';
                    $('img.primary-img', $container.$_content).replaceWith( imageElement );
                    // if captions are enabled...
                    if( config.captions ){
                        $container.$_captionContainer.css({maxWidth:imageElement.clientWidth, height:imageElement.clientHeight});
                        $container.$_caption1.text(_obj.title);
                        $container.$_caption2.text(_obj.descr);
                        captionHack(imageElement); // aim to remove!
                    }
                    // update statuses
                    _displayImageWorking = false;
                    $container.setStatus(status_loaded_true);
                });
            }
        }


        /**
         * this is a hack *specifically* for Chrome on iOS, where *.clientWidth/Height aren't calc'd immediately.
         * @todo: see if this bullshit can be further debugged and removed!
         * @param imageElement
         */
        function captionHack(imageElement){
            if( imageElement.clientHeight === 0 ){
                (function captionFix(){
                    setTimeout(function(){
                        if( imageElement.clientHeight >= 1 ){
                            $container.$_captionContainer.css({maxWidth:imageElement.clientWidth, height:imageElement.clientHeight});
                            return;
                        }
                        captionFix();
                    }, 100);
                })();
            }
        }


        /**
         * On initialization (either by click or something else), this will
         * look at all available DOM elements (i.e. siblings of the item
         * clicked first) and cache the image data.
         * @returns {}
         */
        function _listCache( _rescan ){
            if( !_listDataCache || _rescan === true ){
                _listDataCache  = {};
                $(config.itemTargets, $selector).each(function(index, element){
                    element.setAttribute('data-flexrylb', index);
                    // pass the element to the (overridable!) dataSourceMap function
                    _listDataCache[index] = config.dataSourceMap(element);
                    // ensure the index property is present on the cached data
                    _listDataCache[index].index = index;
                    console.log(_listDataCache);
                    // update the list length cache
                    _listDataLength = index;
                });
            }
            return _listDataCache;
        }


        /**
         * This is the function that returns objects to the listCache; broken out into a
         * separate function so it can be overridden in the configs.
         * @param int index
         * @param HTML element element
         * @returns {{index: *, title: *, descr: *, src_thumb: (*|attr|attr|attr), src_full: string}}
         * @private
         */
        function _dataSourceMap(element){
            return {
                title     : $('.title', element).text() || '',
                descr     : $('.descr', element).text() || '',
                src_thumb : $('img', element).eq(0).attr('src'),
                src_full  : element.getAttribute('data-src-full')
            };
        }


        /**
         * Bind click event to launch the whole kit and kaboodle. A delegateTarget
         * can be passed in as an option, which will restrict launching unless
         * that element is the exact target that was clicked (first test).
         */
        $selector.on('click.lb', config.itemTargets, function(_ev){
            // are we restricting to a delegate target?
            if( config.delegateTarget && !($(_ev.target).is(config.delegateTarget)) ){
                return;
            }
            // browser support ok? proceed...
            if( _supported ){
                // if we're here, launch this bitch...
                var itemList     = _listCache(),
                    clickedIndex = this.getAttribute('data-flexrylb');
                $container.open().then(function(){
                    _displayImage( itemList[clickedIndex] );
                });
                return;
            }
            // if we get here, show unsupported!
            alert('Sorry, your browser does not support viewing this image in large format. Please consider upgrading!');
        });


        /**
         * @public instance methods
         * {}
         */
        return {
            listCache       : _listCache,
            instance        : _self,
            $container      : function(){ return $container; },
            settings        : function(){ return config; },
            currentIndex    : function(){ return _currentIndexCache; },
            listDataLength  : function(){ return _listDataLength; },
            config          : function(_prop, _value){
                // no _value? just return the current config value
                if( !_value ){ return config[_prop]; }
                // otherwise, set the value
                config[_prop] = _value;
                // and return this for chaining
                return this;
            },
            rescanItems     : function(){
                // bust the previous listCache by passing true
                var _updated = _listCache(true);
                // rebuild gallery markers, if applicable
                buildGalleryMarkers();
                // return the updated cache data
                return _updated;
            }
        }
    }


    /**
     * This is the actual function visible to jQuery. Below, we create a new instace
     * of FlexryLightbox, bind it to the selector's data attribute, then return
     * for chaining.
     * @param {} _settings
     * @returns jQuery
     */
    $.fn.flexryLightbox = function( _settings ){
        return this.each(function(idx, _element){
            var $selector = $(_element),
                _instance = new FlexryLightbox( $selector, _settings );
            $selector.data('flexryLightbox', _instance);
        });
    }

})( jQuery );
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
            status_loaded_false = false,
            status_loaded_true  = true,
            _transitionDuration = 200, // .2 seconds
            _imagePromiseCache  = {},
            _currentIndexCache  = null,
            _listDataLength     = 0,
            _listDataCache      = false,
            // merge passed options w/ the defaults
            config = $.extend(true, {}, {
                maskColor           : '#2a2a2a',
                maskOpacity         : .85,
                maskFadeSpeed       : 250,
                closeOnClick        : true,
                itemTargets         : '.lightbox-item',
                transitionEffect    : '',
                transitionDuration  : _transitionDuration,
                captions            : true,
                gallery             : true,
                galleryMarkers      : true,
                galleryMarkersThumb : {w: 160, h: 100}
            }, _settings);

        /**
         * Create <div class="flexry-lightbox" /> container, and bind helper methods
         * open / close/ setStatus.
         */
        var $container = (function(){
            var buildPromise,
                $element = $('<div />', {
                    class : ['flexry-lightbox', config.transitionEffect, (config.captions ? 'captions' : ''), (config.gallery ? 'arrows' : ''), (config.galleryMarkers ? 'markers' : '')].join(' '),
                    html  : '<div class="masker"></div><div class="modal-container"><div class="content"><a class="gallery-arrows prev"></a><a class="gallery-arrows next"></a><div class="caption-container"><div class="caption title"><span></span></div><div class="caption descr"><span></span></div></div><img class="primary-img" /></div></div><div class="loader-container"><div class="inner"></div></div><a class="closer"><span>Close</span></a><div class="gallery-markers"><div class="m-inner"></div></div>'
                });

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
                        // set ID here so timestamp is always at time of initialize
                        $element.attr('id', 'flexryLightbox-' + ((new Date()).getTime()) );
                        // add to body
                        $element.appendTo('body');
                        // update masker settings
                        $('.masker', $element).css({background:config.maskColor,opacity:config.maskOpacity});
                        // bind close target (add check so if img-primary, don't close)
                        var $closeTarget = config.closeOnClick ? $element : $('.closer', $element);
                        $closeTarget.on('click', function(_ev){
                            if($(_ev.target).hasClass('primary-img')){return;}
                            $element.close();
                        });
                        // set transition duration, if not default 200
                        setCssTransitionDuration();
                        // build gallery stuff, or if only one image in listData(), hide
                        buildGallery();
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
                            $selector.trigger('flexry_lightbox_open');
                        });
                    });
                }).promise();
            }

            // add a close() method
            $element.close = function(){
                $('html').removeClass('flexry-lb-active');
                return $element.setStatus(status_loaded_false).fadeOut(config.maskFadeSpeed, function(){
                    // emit an event on the $selector indicating close
                    $selector.trigger('flexry_lightbox_close');
                });
            }

            // convenience "set status" message (swap classes)
            $element.setStatus = function( _to ){
                $element.toggleClass('loaded', _to === status_loaded_true);
                return $element;
            }

            // cache commonly used selectors
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
        function buildGallery(){
            // is the gallery either: disabled, or is there not an object at _listCache() index 1?
            if( !(config.gallery) || !(_listCache()['1']) ){
                $container.removeClass('arrows markers');
                return;
            }

            // bind previous/next actions
            $('.gallery-arrows', $container).on('click', function( _clickEv ){
                _clickEv.stopPropagation(); _clickEv.preventDefault();
                var itemList = _listCache();
                if( $(this).hasClass('prev') ){
                    _displayImage( itemList[_currentIndexCache-1] || itemList[_listDataLength] );
                }else{
                    _displayImage( itemList[_currentIndexCache+1] || itemList[0] );
                }
            });

            // create gallery markers?
            if( config.galleryMarkers ){
                // loop through every item in the listCache and compose a marker
                $.each( _listCache(), function(_index, dataObj){
                    $.when( _getImage(dataObj['src_thumb']) ).done(function( _img ){
                        var _ratio  = _scaleRatio( _img, config.galleryMarkersThumb ),
                            $marker = $('<div class="m" data-cache="'+dataObj['index']+'"><span class="t"><span class="arrow"></span></span></div>');
                        $('span.t', $marker).css({left:-((_img.width*_ratio/2)+3)}).append(_img);
                        $container.$_markersInner.append($marker);
                    });
                });

                // bind click event to the markers
                $container.$_markersInner.on('click', function( _clickEv ){
                    _clickEv.stopPropagation(); _clickEv.preventDefault();
                    if( $(_clickEv.target).hasClass('m') ){
                        _displayImage( _listCache()[_clickEv.target.getAttribute('data-cache')] );
                    }
                });
            }
        }


        /**
         * Pass in an image object, and get the ratio by which the width/height are
         * scaled so that image doesn't exceed max % of window.
         * @param image _img : javascript image object
         * @param obj {w:int,h:int} : optionally, pass max width/height in object
         * @return {number}
         */
        function _scaleRatio( _img, max_w_h ){
            var _maxes   = max_w_h || config.galleryMarkersThumb,
                _scaledW = _img.width <= _maxes.w ? _img.width : _maxes.w,
                _scaledH = _img.height <= _maxes.h ? _img.height : _maxes.h,
                _ratios  = [(_scaledW / _img.width), (_scaledH / _img.height)];
            return Math.min.apply(Math, _ratios);
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
                setTimeout(function(){ _task.resolve(); }, _time || +(config.transitionDuration) );
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
                _displayImageWorking = true;
                // cache the index of item being displayed, of all items
                _currentIndexCache = _obj['index'];
                // set container status to working
                $container.setStatus(status_loaded_false);
                // kickoff the getImage and the delayer simultaneously
                $.when( _getImage(_obj['src_full']), _delayer(0) ).done(function(){
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
                        // @NOTE: this is a hack *specifically* for Chrome on iOS, where *.clientWidth/Height aren't calc'd immediately.
                        // Hopefully should be able to remove this bullshit someday :(
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
                    // update statuses
                    _displayImageWorking = false;
                    $container.setStatus(status_loaded_true);
                });
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
                    element.setAttribute('data-order', index);
                    _listDataCache[index] = {
                        index     : index,
                        title     : $('.title', element).text() || '',
                        descr     : $('.descr', element).text() || '',
                        src_thumb : $('img', element).attr('src'),
                        src_full  : element.getAttribute('data-src-full')
                    };
                    _listDataLength = index;
                });
            }
            return _listDataCache;
        }


        /**
         * Bind click event to launch the whole kit and kaboodle.
         */
        $selector.on('click', config.itemTargets, function(){
            var itemList     = _listCache(),
                clickedIndex = this.getAttribute('data-order');
            $container.open().then(function(){
                _displayImage( itemList[clickedIndex] );
            });
        });


        // public instance methods
        return {
            listCache       : _listCache,
            instance        : _self,
            $container      : function(){ return $container; },
            settings        : function(){ return config; },
            currentIndex    : function(){ return _currentIndexCache; },
            rescanItems     : function(){ return _listCache(true); },
            listDataLength  : function(){ return _listDataLength; }
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
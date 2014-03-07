/*!
 * FlexryGrid : Custom wrapper for Masonry library.
 */
(function( $ ){

    function FlexryGrid( $selector, _settings ){

        var $masonryContainer = $('.flexryGrid', $selector),
            $loaderButton     = $('.loader', $selector),
            $btnSpan          = $('span', $loaderButton),
            $window           = $(window),
            _pageOffset       = 2,
            _requestCache     = {},
            _didScroll        = false,
            _loopIterator     = null,
            config            = $.extend(true, {}, {
                itemSelector       : '.grid-item',
                columnWidth        : '.grid-sizer',
                transitionDuration : '.25s',
                paginationMethod   : 'click' // 'click' or 'scroll'
            }, _settings);


        /**
         * After more items have been loaded, appended, and layed out by Masonry.
         */
        function onLoadMoreSuccess(){
            var _msg = config.paginationMethod === 'click' ? 'Load More' : 'Scroll Or Click To Load More';
            $loaderButton.removeClass('working');
            $btnSpan.text(_msg);
            _pageOffset++;

            // if Lightbox is enabled, rescan items
            if( $selector.data('flexryLightbox') ){
                $selector.data('flexryLightbox').rescanItems();
            }

            // re-layout one more time (sometimes mobile devices can't keep up)
            $masonryContainer.masonry();
        }


        /**
         * Fail usually means "no more results to load", but could be server error.
         */
        function onLoadMoreFail(){
            clearInterval(_loopIterator);
            $loaderButton.removeClass('working').text('No More Images To Load');
            $window.off('scroll.flexry_grid');
        }


        /**
         * Create a master $.promise, which does a whole bunch of stuff inside it (namely an
         * ajax call then image preloading), and resolve it only once *everything* is prepared).
         * @param int _page
         * @returns $.promise
         */
        function loadMore(_page){
            if(!_requestCache[_page]){
                // create $.Deferred object
                var $promise = $.Deferred(function( _defer ){
                    // update button class and span text
                    $loaderButton.addClass('working');
                    $btnSpan.text('Loading');

                    // issue ajax request
                    $.ajax(config.flexryToolsPath + 'grid_paging_json', {
                        cache    : false,
                        data     : {offset: _pageOffset, blockID: config.blockID},
                        dataType : 'json',
                        /** @note: the defer gets resolved by the masonry onlayoutcomplete event! */
                        success  : function( _json ){
                            if( !_json.length ){ _defer.reject(); return; }
                            // start preloading (promise)
                            var $batchLoader = batchLoadImages(_json);
                            // watch for notifications of # images loaded on the promise
                            $batchLoader.progress(function(_status){
                                $btnSpan.text('Loading ('+_status+')');
                            });
                            // bind onLayoutComplete and resolve the deferred only from in here
                            $masonryContainer.masonry('on', 'layoutComplete', function(){
                                _defer.resolve();
                                // returning true unbinds the event (only run once)
                                return true;
                            });
                            // when all are loaded, *then* do DOM manipulation and relayout
                            $batchLoader.done(function(){
                                var fragment = document.createDocumentFragment(),
                                    elements = [];
                                $.each(_json, function(index, obj){
                                    var root = document.createElement('div'),
                                        html = '<div class="grid-item-inner"><img class="grid-image" src="'+obj.src_thumb+'" /><div class="meta"><div class="poz"><span class="title">'+obj.title+'</span><span class="descr">'+obj.descr+'</span></div></div></div>';
                                    root.className = 'grid-item';
                                    root.setAttribute('data-src-full', obj.src_full);
                                    root.innerHTML = html;
                                    fragment.appendChild(root);
                                    elements.push(root);
                                });
                                // append to masonry instance
                                $masonryContainer[0].appendChild(fragment);
                                // trigger layout!
                                $masonryContainer.masonry('appended', elements);
                            });
                        },
                        error    : function(){
                            _defer.reject();
                        }
                    });
                }).promise();

                // when marked as rejected (no more results)
                $promise.fail( onLoadMoreFail );

                // when marked as resolved (only by masonry onLayoutComplete)
                $promise.done( onLoadMoreSuccess );

                // cache it along with the current _page
                _requestCache[_page] = $promise;
            }
            return _requestCache[_page];
        }


        /**
         * Return the bottom coordinate of the masonry container.
         * @returns {number}
         */
        function scrollThreshold(){
            return ($masonryContainer.offset().top + $masonryContainer.outerHeight()) - $window.height();
        }


        /**
         * Using a quickly-iterating setInterval is more performant than running function on
         * *every* scroll.flexry_grid trigger.
         * http://ejohn.org/blog/learning-from-twitter/
         */
        function _iterate(){
            // if exists, clear it first
            if( _loopIterator ){ clearInterval(_loopIterator); }
            // then restart that bitch
            _loopIterator = setInterval(function(){
                if( _didScroll ){
                    if( $window.scrollTop() >= scrollThreshold() ){
                        loadMore(_pageOffset);
                    }
                    _didScroll = false;
                }
            }, 250);
        }


        /**
         * Batch preload a group of images.
         * @param _array of objects {} with property src_thumb
         * @returns {*|promise}
         */
        function batchLoadImages( _array ){
            return $.Deferred(function( _batchTask ){
                var _length = _array.length,
                    _loaded = 0;
                $.each(_array, function(index, obj){
                    var inMem = new Image();
                    inMem.onload = function(){
                        _loaded++;
                        _batchTask.notify(_loaded+'/'+_length);
                        if(_loaded === _length){
                            _batchTask.resolve();
                        }
                    }
                    inMem.src = obj['src_thumb'];
                });
            }).promise();
        }


        /**
         * Preload images already in the DOM, and cache as JSONified list.
         * @type {*|promise}
         */
        function preInitialize(){
            return $.Deferred(function( _initializeTask ){
                var $initialItems = $(config.itemSelector, $masonryContainer),
                    preloadList   = [];
                // push to preload list
                $initialItems.each(function(index, node){
                    preloadList.push({src_thumb : $('img', node).attr('src') });
                });
                // ... then, preload those images, and when done, resolve
                $.when( batchLoadImages(preloadList) ).done(function(){
                    _initializeTask.resolve()
                });
            }).promise();
        }


        /**
         * When preloading of all the images are done, *then* bind and initialize
         * masonry and other stuff.
         */
        $.when( preInitialize() ).done(function(){
            // Bind and initialize Masonry!
            $masonryContainer.masonry(config);

            // If paginating on scroll
            if( config.paginationMethod === 'scroll' ){
                // start the iterator
                _iterate();
                // Initialize scroll watcher
                $window.on('scroll.flexry_grid', function(){
                    _didScroll = true;
                });
            }

            // Always watch for on click to load more
            $loaderButton.on('click', function(){
                loadMore(_pageOffset);
            });
        });


        // @public methods
        return {
            settings : function(){
                return config;
            },
            $selector : $selector
        }
    }


    /**
     * Create the flexryGrid jQuery plugin.
     * @param {} _settings
     * @returns {jQuery|Element}
     */
    $.fn.flexryGrid = function( _settings ){
        return this.each(function(idx, _element){
            var $element  = $(_element),
                _instance = new FlexryGrid( $element, _settings );
            $element.data('flexryGrid', _instance);
        });
    }

})( jQuery );
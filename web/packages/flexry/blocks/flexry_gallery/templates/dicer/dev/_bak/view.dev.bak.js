(function( $, gsTween, gsTimeline ){

    function FlexryDicer( $selector, _settings ){

        var _self       = this,
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


        function cubifyNode( $node, _clientW, _clientH ){
            var $i1 = $('<div class="inner" />').appendTo($node),
                $i2 = $('<div class="inner2" />').appendTo($i1);

            var $tb = $('<div class="side top" /><div class="side bottom" />').appendTo($i2);
            gsTween.set($tb, {rotationX:90, z:(5)});
            //$tb.height(_clientH);

            //var $lr = $('<div class="side left" />').appendTo($i2);
            //$lr.width(_clientW);

            /*$('<div class="side top" />').appendTo($node);
            $('<div class="side bottom" />').appendTo($node);
            var $right = $('<div class="side right" />').appendTo($node);
            var $left = $('<div class="side left" />').appendTo($node);
            $right.add($left).width(_clientW);
            //gsTween.set([$right[0],$left[0]],)

            var $back = $('<div class="side back" />').appendTo($node);
            gsTween.set($back[0], {z:-_clientH});*/
        }


        function trace(){
            var $current = $('.current img', $selector).css({visibility: 'hidden'}),
                _offset  = $current.position(),
                _clientW = $current[0].clientWidth,
                _clientH = $current[0].clientHeight;
            var $container = $('<div class="containment" />').css({
                width: _clientW,
                height: _clientH,
                top: _offset.top,
                left: _offset.left
            });
            $current.parent('.flexry-dicer-item').append($container);

            for(i = 1; i <= config.bricks; i++ ){
                var $node = $('<div class="brick" data-index="'+i+'" />').css({
                    width: _clientW / config.bricks,
                    height: _clientH,
                    top: 0,
                    left: (_clientW / config.bricks) * (i-1),
                    backgroundImage: 'url('+$current.attr('src')+')',
                    backgroundPosition: -((_clientW / config.bricks) * (i-1)) + 'px 0',
                    backgroundSize: _clientW + 'px ' + _clientH + 'px'
                }).appendTo($container);

                cubifyNode($node, _clientW, _clientH);

                _tweens.push( gsTween.to($node[0], .35, {rotationY:170, delay:.35}) );
            }

            _timeline.add(_tweens, '+=0', 'normal', .1);
        }


        batchLoadImages( $('img', $selector) ).done(function(){
            trace();
        })


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
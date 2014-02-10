/**
 * Custom wrapper for schedulizer blocks (wrap FullCalendar API).
 */
(function( $ ){

    function Schedulizer( $selector, _settings ){

        var $calendar   = $('.schedulizerCalendar', $selector),
            $eventTable = $('.eventTable', $selector),
            config      = $.extend(true, {}, {

            }, _settings);


        /**
         * Initialize the calendar instance.
         */
        $calendar.fullCalendar({
            header: {
                left: 'title',
                right: 'prev,next today'
            },
            editable: false,
            defaultView: 'month',
            events: config.eventSrc,
            eventClick: function(event){
                $calendar.trigger('eventclick.schedulizer', [event]);
            },
            eventAfterAllRender: function(view){
                $eventTable.empty();
                $.each(view.calendar.clientEvents(), function(idx, event){
                    $eventTable.append('<tr><td>'+ $.fullCalendar.formatDate(event.start, 'MMM d, yyyy') +'</td><td>'+event.title+'</td></tr>');
                });
            }
        });


        // @public methods
        return {

        };
    }

    $.fn.schedulizer = function( _settings ){
        return this.each(function(idx, _element){
            var $element  = $(_element),
                _instance = new Schedulizer( $element, _settings );
            $element.data('schedulizer', _instance);
        });
    };

})( jQuery );
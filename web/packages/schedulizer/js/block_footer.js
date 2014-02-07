$(function(){
    function initCalendar( settings ){
        var $calendar   = $('.schedulizerCalendar.b-' + settings.bID),
            $eventTable = $('tbody', '#eventTable-' + settings.bID);

        $calendar.fullCalendar({
            header: {
                left: 'title',
                right: 'prev,next today'
            },
            editable: false,
            defaultView: 'month',
            // event feed
            events: settings.eventSrc,
            // click on an event
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
    }

    if( typeof(window.schedulizers) !== 'undefined' && window.schedulizers.length ){
        $.each(window.schedulizers, function(idx, settings){
            initCalendar(settings);
        });
    }
});
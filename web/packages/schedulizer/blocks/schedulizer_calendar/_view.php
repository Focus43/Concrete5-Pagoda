<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="schedulizerCalendar b-<?php echo $this->controller->bID; ?>">

</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Event</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($nextFiveEvents AS $eventObj): /** @var $eventObj SchedulizerEvent */ ?>
            <tr>
                <td><?php echo $eventObj->getStartDateTimeObj()->format('M d - h:i a'); ?></td>
                <td><?php echo $eventObj; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function(){
        var $calendar = $('.schedulizerCalendar.b-<?php echo $this->controller->bID; ?>');

        $calendar.fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            editable: true,
            defaultView: 'month',
            // event feed
            events: '<?php echo SCHEDULIZER_TOOLS_URL; ?>blocks/calendar?' + $.param({
                blockID: <?php echo $this->controller->bID; ?>
            }),
            // click on a day
            dayClick: function(date, allDay, jsEvent, view){
                /*var _data = $.param({
                    calendarID: _calendarID,
                    year: date.getUTCFullYear(),
                    month: date.getUTCMonth() + 1,
                    day: date.getUTCDate()
                });
                // OPEN EVENT DIALOG TO CREATE NEW
                $.fn.dialog.open({
                    width:650,
                    height:525,
                    title: 'New Event: ' + date.toLocaleDateString(),
                    href: _toolsURI + 'events/new?' + _data
                });*/
            },
            // click on an event
            eventClick: function(calEvent, jsEvent, view){
                /*
                // OPEN EVENT DIALOG TO CREATE EDIT
                $.fn.dialog.open({
                    width:650,
                    height:525,
                    title: 'Editing Event: ' + calEvent.title,
                    href: _toolsURI + 'events/edit?' + $.param({
                        eventID: calEvent.id,
                        isAlias: calEvent.isAlias,
                        date: $.fullCalendar.formatDate(calEvent.start, 'yyyy-MM-dd')
                    })
                });*/
            }
        });
    });
</script>
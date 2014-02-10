<?php defined('C5_EXECUTE') or die("Access Denied.");
    $containerID = t('schedulizerCalendar-%s', $this->controller->bID);
?>

<div id="<?php echo $containerID; ?>">
    <div class="schedulizerCalendar">
        <!-- fullcalendar instance -->
    </div>
    <table class="eventTable table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Event</th>
            </tr>
        </thead>
        <tbody>
            <!-- built via js -->
        </tbody>
    </table>
</div>

<script type="text/javascript">
    (function( _stack ){
        _stack.push(function(){
            $('#<?php echo $containerID; ?>').schedulizer({
                blockID  : <?php echo $this->controller->bID; ?>,
                eventSrc : '<?php echo SCHEDULIZER_TOOLS_URL; ?>blocks/calendar?blockID=<?php echo $this->controller->bID; ?>'
            });
        });
        window._schedulizers = _stack;
    }( window._schedulizers || [] ));
</script>
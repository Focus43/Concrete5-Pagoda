<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="schedulizerCalendar b-<?php echo $this->controller->bID; ?>">

</div>

<table id="eventTable-<?php echo $this->controller->bID; ?>" class="table table-bordered">
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

<script type="text/javascript">
    (function( _stack ){
        _stack.push({
            bID: <?php echo $this->controller->bID; ?>,
            eventSrc: '<?php echo SCHEDULIZER_TOOLS_URL; ?>blocks/calendar?blockID=<?php echo $this->controller->bID; ?>'
        });
        window.schedulizers = _stack;
    }( window.schedulizers || [] ));
</script>
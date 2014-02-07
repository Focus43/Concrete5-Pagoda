<?php defined('C5_EXECUTE') or die(_("Access Denied."));

    // @todo: permission check

    // get the calendar obj
    $calendarObj = SchedulizerCalendar::getByID($_REQUEST['calendarID']);

    // If the user is on the month view, the allDay parameter will equal true, meaning that no
    // start time was specified. If the user is on week/calendar list view, and clicks a certain
    // region (like 9:30 am), we want to use that time - in which case allDay will = false.
    if( $_REQUEST['allDay'] === 'true' ){
        $timeString       = "{$_REQUEST['year']}-{$_REQUEST['month']}-{$_REQUEST['day']} 09:00:00";
        $startDateTimeUTC = new DateTime($timeString, $calendarObj->getCalendarTimezoneObj());
        $startDateTimeUTC->setTimezone(new DateTimeZone('UTC'));
    }else{
        $timeString       = "{$_REQUEST['year']}-{$_REQUEST['month']}-{$_REQUEST['day']} {$_REQUEST['hour']}:{$_REQUEST['min']}:00";
        $startDateTimeUTC = new DateTime($timeString, new DateTimeZone('UTC'));
    }

    // Since this is a new record, automatically adjust the end time to one hour after the start
    $endDateTimeUTC = clone $startDateTimeUTC;
    $endDateTimeUTC->modify('+1 hour');

    // render the form, by passing in a newly instantiated (but not saved!) event object
    Loader::packageElement('dashboard/events/form_setup', 'schedulizer', array(
        'eventObj' => new SchedulizerEvent(array(
            'calendarID'    => $calendarObj->getCalendarID(),
            'startUTC'      => $startDateTimeUTC->format(SchedulizerPackage::TIMESTAMP_FORMAT),
            'endUTC'        => $endDateTimeUTC->format(SchedulizerPackage::TIMESTAMP_FORMAT),
            'timezoneName'  => $calendarObj->getDefaultTimezone()
        ))
    ));


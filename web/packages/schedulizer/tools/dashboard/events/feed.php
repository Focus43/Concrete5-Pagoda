<?php defined('C5_EXECUTE') or die(_("Access Denied."));

    $eventListObj = new SchedulizerEventList( new DateTime("@{$_REQUEST['start']}") );
    $eventListObj->filterByEndDate( new DateTime("@{$_REQUEST['end']}") );
    $eventListObj->filterByCalendarIDs( $_REQUEST['calendarID'] );

    // output
    echo (new SchedulizerUtilitiesEventJsonFormatter($eventListObj));
    exit;
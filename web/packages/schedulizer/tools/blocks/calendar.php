<?php defined('C5_EXECUTE') or die(_("Access Denied."));

    // get the block object and then get its data method
    $block = Block::getByID( (int)$_REQUEST['blockID'] )->getInstance();
    $blockData = $block->blockData();

    // get calendarIDs
    $calendarIDs = array_values( (array) $blockData->calendarIDs );

    // build the event query-er
    $eventListObj = new SchedulizerEventList( new DateTime("@{$_REQUEST['start']}") );
    $eventListObj->filterByEndDate( new DateTime("@{$_REQUEST['end']}") );
    $eventListObj->filterByCalendarIDs( $calendarIDs );

    echo (new SchedulizerUtilitiesEventJsonFormatter($eventListObj));
    exit;


<?php defined('C5_EXECUTE') or die(_("Access Denied."));

    // @todo: permission check

    $eventObj = SchedulizerEvent::getByID( $_REQUEST['eventID'] );

    // If the clicked event is *not* the original (its aliased), then use the passed in
    // start date to adjust the aliased view.
    if( (bool) ((int)$_REQUEST['isAlias']) === true ){
        $eventObj->setIsAlias( new DateTime($_REQUEST['eventCalendarStart']) );
    }

    Loader::packageElement('dashboard/events/form_setup', 'schedulizer', array(
        'eventObj' => $eventObj
    ));
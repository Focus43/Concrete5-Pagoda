<?php defined('C5_EXECUTE') or die(_("Access Denied."));

    // @todo: permissions

    $eventObj = SchedulizerEvent::getByID( $_REQUEST['id'] );

    // get the time distance between start and end
    $interval = $eventObj->getStartDateTimeObj(false)->diff( $eventObj->getEndDateTimeObj(false) );

    // if days are resized (ie. event spans multiple days), can be positive *or*
    // negative
    if( (int) $_REQUEST['dayDelta'] !== 0 ){
        // first update the start utc property
        $eventObj->setPropertiesFromArray(array(
            'startUTC' => $eventObj->getStartDateTimeObj(false)->modify( $_REQUEST['dayDelta'] . ' days' )->format( SchedulizerPackage::TIMESTAMP_FORMAT )
        ));
        // *then* update the end utc property
        $eventObj->setPropertiesFromArray(array(
            'endUTC'   => $eventObj->getStartDateTimeObj(false)->add($interval)->format( SchedulizerPackage::TIMESTAMP_FORMAT )
        ));
        $eventObj->save();
    }

    // if minutes change (ie. length of the event changes)
    if( (int) $_REQUEST['minuteDelta'] !== 0 ){
        $eventObj->setPropertiesFromArray(array(
            'startUTC' => $eventObj->getStartDateTimeObj(false)->modify( $_REQUEST['minuteDelta'] . ' minutes' )->format( SchedulizerPackage::TIMESTAMP_FORMAT )
        ));
        $eventObj->setPropertiesFromArray(array(
            'endUTC'   => $eventObj->getStartDateTimeObj(false)->add($interval)->format( SchedulizerPackage::TIMESTAMP_FORMAT )
        ));
        $eventObj->save();
    }

    echo Loader::helper('json')->encode( (object) array(
        'code'  => 1,
        'msg'   => 'Successfully adjusted event: "' . $eventObj->getTitle() . '"'
    ));

    exit;
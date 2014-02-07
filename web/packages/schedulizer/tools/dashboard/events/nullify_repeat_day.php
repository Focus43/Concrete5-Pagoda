<?php defined('C5_EXECUTE') or die(_("Access Denied."));

    $responseObj = (object) array(
        'code'   => 1,
        'msg'    => 'Ok'
    );

    try {
        SchedulizerEventRepeat::nullifyOnDate(new DateTime($_REQUEST['date']), $_REQUEST['eventID']);
    }catch(Exception $e){
        $responseObj->code = 0;
    }

    echo Loader::helper('json')->encode( $responseObj );

    exit;
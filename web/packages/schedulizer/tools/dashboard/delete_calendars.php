<?php defined('C5_EXECUTE') or die(_("Access Denied."));

    // @todo: permissions

    try {
        if( !empty($_REQUEST['calendarID']) ){
            foreach($_REQUEST['calendarID'] AS $calendarID){
                SchedulizerCalendar::getByID($calendarID)->delete();
            }
        }

        echo Loader::helper('json')->encode((object) array(
            'code' => 1,
            'msg'  => 'Calendar(s) successfully deleted'
        ));
    }catch(Exception $e){
        echo Loader::helper('json')->encode((object) array(
            'code' => 0,
            'msg'  => 'An error occurred deleting the calendars, please contact Focus43.'
        ));
    }

    exit;
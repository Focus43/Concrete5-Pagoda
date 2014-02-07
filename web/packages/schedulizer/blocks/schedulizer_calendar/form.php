<?php defined('C5_EXECUTE') or die("Access Denied.");
$formHelper	= Loader::helper('form');
/** @var $blockData stdObject */
?>

<style type="text/css">
    #blockFormSchedulizer h4 {padding-bottom:.6em;}
    #blockFormSchedulizer .well .inner-row.clearfix {padding-bottom:6px;}
    #blockFormSchedulizer .well .inner-row .fiddy {width:50%;float:left;}
</style>

<div id="blockFormSchedulizer" class="ccm-ui">
    <ul class="nav nav-tabs">
        <li class="active" data-target="#schedulizerCalendarView">
            <a>Settings</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="well">
            <h4>Include Events From Calendar(s):</h4>
            <?php
                $chunkd = array_chunk($calendarList, 2);
                foreach($chunkd AS $pairs): ?>
                    <div class="inner-row clearfix">
                    <?php foreach($pairs AS $calendarObj){ /** @var $calendarObj SchedulizerCalendar */ ?>
                        <div class="fiddy">
                        <?php echo $formHelper->checkbox('btcal[calendarIDs][]', $calendarObj->getCalendarID(), in_array($calendarObj->getCalendarID(), (array) $blockData->calendarIDs)) . ' ' . $calendarObj->getTitle(); ?>
                        </div>
                    <?php } ?>
                    </div>
                <?php endforeach; ?>
        </div>
    </div>
</div>
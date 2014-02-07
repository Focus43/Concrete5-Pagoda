<?php defined('C5_EXECUTE') or die("Access Denied.");
	$formHelper	= Loader::helper('form');
    /** @var $blockData stdObject */
?>

	<style type="text/css">
        #blockFormSchedulizer h4 {padding-bottom:.6em;}
        #blockFormSchedulizer .calendarConfigs {position:relative;border:1px solid #ccc;margin-bottom:.6em;padding:1em 1em .6em;}
            #blockFormSchedulizer .calendarConfigs .clearfix {padding:0;}
            #blockFormSchedulizer .calendarConfigs .removable {position:absolute;top:0;right:0;padding:2px 6px 7px;line-height:1em;display:block;text-transform:uppercase;color:#ccc;font-weight:bold;font-size:20px;}
            #blockFormSchedulizer .calendarConfigs .removable:hover {text-decoration:none;background:#f5f5f5;}
            #blockFormSchedulizer .calendarConfigs h4 {}
            #blockFormSchedulizer .calendarConfigs .calColorSwatch {width:30px;height:30px;border:2px solid #ccc;cursor:pointer;}
            #blockFormSchedulizer .calendarConfigs label {display:block;text-align:left;float:none;width:auto;}
            #blockFormSchedulizer .calendarConfigs label input[type="checkbox"] {display:inline;position:relative;top:-1px;}
            #blockFormSchedulizer .calendarConfigs table.table {display:none;margin:0 0 .4em;}
            #blockFormSchedulizer .calendarConfigs table.table td.colName {vertical-align:middle;font-weight:bold;white-space:nowrap;width:1%;}
        #blockFormSchedulizer .calendarConfigs.clonable {display:none;}
	</style>

	<div id="blockFormSchedulizer" class="ccm-ui">
		<ul class="nav nav-tabs">
			<li class="active" data-target="#schedulizerCalendarView">
                <a>Calendar Settings</a>
            </li>
			<li data-target="#schedulizerEventListView">
                <a>Event List</a>
            </li>
            <li class="pull-right">
                <?php echo $formHelper->select('calendarIDPicker', array('' => 'Select Calendar(s)') + $calendarList); ?>
            </li>
		</ul>
		<div class="tab-content">
			<div id="schedulizerCalendarView" class="tab-pane active">
                <div id="calendarDataSources">
                    <span class="muted" style="display:block;text-align:center;">Assign At Least One Calendar As A Data Source</span>
                    <?php foreach($blockData AS $calendarID => $data):
                        $calendarObj = SchedulizerCalendar::getByID($calendarID);
                        Loader::packageElement('partials/blocks/schedulizer_calendar_settings', 'schedulizer', array(
                            'wrapperClass'  => '',
                            'calendarID'    => $calendarObj->getCalendarID(),
                            'calendarName'  => $calendarObj->getTitle(),
                            'formHelper'    => $formHelper,
                            'overrideColors' => (bool)$data->overrideColors,
                            'disableForms'  => false
                        ) + (array)$data);
                    endforeach; ?>
                </div>

                <!-- clonable calendarConfigs -->
                <?php Loader::packageElement('partials/blocks/schedulizer_calendar_settings', 'schedulizer', array(
                    'wrapperClass'  => 'clonable',
                    'calendarID'    => '#ID',
                    'formHelper'    => $formHelper,
                    'disableForms'  => true
                )); ?>
			</div>

			<div id="schedulizerEventListView" class="tab-pane">

			</div>
		</div>
	</div>
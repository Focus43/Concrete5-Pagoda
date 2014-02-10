<?php /** @var $eventObj SchedulizerEvent */

    /**
     * @note: If the passed $eventObj is new (no ID assigned yet), in the tools file that loads
     * this element file we are setting the startUTC and endUTC properties to 9/10am respectively
     *
     * @todo: Randomized event colors
     */
    $formHelper     = Loader::helper('form');
    $dateHelper     = Loader::helper('date');
    $timingHelper   = Loader::helper('timing', 'schedulizer');
?>

    <style type="text/css">
        <?php if( !($eventObj->getIsRepeating()) ): ?>
        #eventRepeatSettings {display:none;}
        <?php endif; if( $eventObj->getUseCalendarTimezone() ): ?>
        #timezoneSettings {display:none;}
        <?php endif; ?>
    </style>

    <div id="eventSetupForm" class="ccm-ui">
        <?php if((bool)$eventObj->getIsAlias()): ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="alert alert-block" style="padding:8px 14px;">
                        <h4>Heads Up!</h4>
                        <p style="padding-bottom:8px;">The event you clicked is one in a repeating series; to change this event, you must update the original, which will update <i>all</i> events in the series.</p>
                        <button type="button" id="btnEditOriginal" class="btn btn-block" data-id="<?php echo $eventObj->getEventID(); ?>" data-title="<?php echo $eventObj->getTitle(); ?>">Edit Original Event</button>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <form id="frmNewEvent" data-method="ajax" action="<?php echo View::url('/dashboard/schedulizer/calendars', 'save_event', $eventObj->getEventID()); ?>">
                <!--<div class="row-fluid">
                    <div class="span12">
                        <div id="aliasEventAlert" class="alert alert-block">
                            <div class="alert-content">
                                <h4>Heads Up!</h4>
                                <p>This is a repeating event: choose how this event (and/or others in the series) should be updated.</p>
                                <br />
                                <div class="clearfix" style="padding:0;">
                                    <div class="pull-left">
                                        <div class="btn-group">
                                            <button class="btn">Only This Event<?php echo $formHelper->checkbox('aliasHandler', SchedulizerEventRepeat::UPDATE_ONLY_THIS_EVENT); ?></button>
                                            <button class="btn">Following Events<?php echo $formHelper->checkbox('aliasHandler', SchedulizerEventRepeat::UPDATE_FOLLOWING_EVENTS); ?></button>
                                            <button class="btn">All Events<?php echo $formHelper->checkbox('aliasHandler', SchedulizerEventRepeat::UPDATE_ALL_EVENTS); ?></button>
                                        </div>
                                    </div>
                                    <div class="pull-right">
                                        <button id="hideDayInEventSeries" class="btn error" data-eventid="<?php echo $eventObj->getEventID(); ?>" data-date="<?php echo $eventObj->getStartDateTimeObj()->format('Y-m-d'); ?>">
                                            Hide Day In Event Series
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->

                <?php if(! (bool)$eventObj->getUseCalendarTimezone()): ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="alert alert-block">
                                <p>This event is using timezone <strong><?php echo $eventObj->getTimezoneName(); ?></strong>, which is not the calendar default. In the default calendar timezone (<?php echo $eventObj->calendarObject()->getDefaultTimezone() ?>), this event starts at <strong><?php echo $eventObj->getStartDateTimeObj()->setTimezone(new DateTimeZone($eventObj->calendarObject()->getDefaultTimezone()))->format('n/j/Y h:i a'); ?></strong>.</strong></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row-fluid">
                    <div class="span12 form-horizontal well">
                        <div class="control-group">
                            <label class="control-label"><strong>Event Title</strong></label>
                            <div class="controls">
                                <?php echo $formHelper->text('event[title]', $eventObj->getTitle(), array('class' => 'input-block-level', 'placeholder' => 'Walk the dog')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><strong>Time</strong></label>
                            <div class="controls timeChoozers">
                                <div class="pull-left">
                                    <span class="from-to">From&nbsp;</span>
                                </div>
                                <div class="pull-left">
                                    <?php echo $formHelper->text('event_start_date', $eventObj->getStartDateTimeObj()->format(DATE_APP_GENERIC_MDY), array('class' => 'input-small dp-element', 'placeholder' => 'Start Date')); ?>
                                </div>
                                <div class="pull-left">
                                    <?php echo $formHelper->select('event_start_time', $timingHelper::selectableTimeValues(), $eventObj->getStartDateTimeObj()->format('H:i'), array('class' => 'chzn-element')); ?>
                                </div>
                                <div class="pull-left">
                                    <span class="from-to">&nbsp;to&nbsp;</span>
                                </div>
                                <div class="pull-left">
                                    <?php echo $formHelper->text('event_end_date', $eventObj->getEndDateTimeObj()->format(DATE_APP_GENERIC_MDY), array('class' => 'input-small dp-element', 'placeholder' => 'End Date')); ?>
                                </div>
                                <div class="pull-left">
                                    <?php echo $formHelper->select('event_end_time', $timingHelper::selectableTimeValues(), $eventObj->getEndDateTimeObj()->format('H:i'), array('class' => 'chzn-element')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"></label>
                            <div class="controls">
                                <label class="checkbox inline"><?php echo $formHelper->checkbox('event[isAllDay]', SchedulizerEvent::ALL_DAY_TRUE, $eventObj->getIsAllDay(), array('data-viz-unchecked' => '#event_start_time_chzn, #event_end_time_chzn')); ?> All Day Event</label>
                                <label class="checkbox inline"><?php echo $formHelper->checkbox('event[isRepeating]', SchedulizerEvent::IS_REPEATING_TRUE, $eventObj->getIsRepeating(), array('data-viz-checked' => '#eventRepeatSettings')); ?> Repeat Settings</label>
                                <label class="checkbox inline"><?php echo $formHelper->checkbox('event[useCalendarTimezone]', SchedulizerEvent::USE_CALENDAR_TIMEZONE_TRUE, $eventObj->getUseCalendarTimezone(), array('data-viz-unchecked' => '#timezoneSettings')); ?> Use Calendar Timezone <span class="label"><?php echo $eventObj->calendarObject()->getDefaultTimezone(); ?></span></label>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><strong>Event Color</strong></label>
                            <div class="controls">
                                <?php foreach(SchedulizerEventColors::pairs() AS $key => $colorObj): ?>
                                    <span class="colorThumbnail <?php echo ($colorObj->hex == $eventObj->getColorHex()) ? 'active' : ''; ?>" style="background:<?php echo $colorObj->hex; ?>" data-toggle-input="radio">
                                    <?php echo $formHelper->radio('event[colorHex]', $key, $eventObj->getColorHex()); ?>
                                </span>
                                <?php endforeach; ?>
                                <!--<span class="colorThumbnail" data-toggle-input="radio" style="background:#c3325f;width:auto;color:#fff;font-weight:bold;padding:2px 6px;font-size:11px;text-transform:uppercase;">
                                    Randomize<?php echo $formHelper->radio('event[colorHex]', $key, $eventObj->getColorHex()); ?>
                                </span>-->
                            </div>
                        </div>
                        <div id="timezoneSettings">
                            <div class="control-group">
                                <label class="control-label"><strong>Timezone</strong></label>
                                <div class="controls clearfix" style="padding-bottom:0;">
                                    <?php echo $formHelper->select('timezone_name', $dateHelper->getTimezones(), $eventObj->getTimezoneName(), array(
                                        'class' => 'input-block-level chzn-element',
                                        'style' => 'width:520px !important;'
                                    )); ?>
                                </div>
                            </div>
                        </div>
                        <div id="eventRepeatSettings">
                            <div class="control-group">
                                <label class="control-label"><strong>Repeats</strong></label>
                                <div class="controls">
                                    <?php echo $formHelper->select('event[repeatTypeHandle]', $timingHelper->getRepeatTypeHandles(), $eventObj->getRepeatTypeHandle(), array('class' => 'input-medium')); ?>
                                    &nbsp; every &nbsp;
                                    <?php echo $formHelper->select('event[repeatEvery]', $timingHelper->repeatEveryOptions(), $eventObj->getRepeatEvery(), array('class' => 'input-mini')); ?>
                                    <span id="recurTextLabel" class="help-inline"></span>
                                </div>
                            </div>
                            <!-- weekday repeats -->
                            <div class="control-group repeat-options" data-show-on="weekly">
                                <label class="control-label"><strong>Weekdays:</strong></label>
                                <div class="controls">
                                    <div class="btn-group" data-toggle="buttons-checkbox">
                                        <button type="button" class="btn <?php echo (bool) $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_SUN) ? 'active' : ''; ?>" data-toggle-input="checkbox">Sun<?php echo $formHelper->checkbox('repeat[weekday_index][]', SchedulizerEventRepeat::WEEKDAY_INDEX_SUN, $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_SUN)); ?></button>
                                        <button type="button" class="btn <?php echo (bool) $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_MON) ? 'active' : ''; ?>" data-toggle-input="checkbox">Mon<?php echo $formHelper->checkbox('repeat[weekday_index][]', SchedulizerEventRepeat::WEEKDAY_INDEX_MON, $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_MON)); ?></button>
                                        <button type="button" class="btn <?php echo (bool) $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_TUE) ? 'active' : ''; ?>" data-toggle-input="checkbox">Tue<?php echo $formHelper->checkbox('repeat[weekday_index][]', SchedulizerEventRepeat::WEEKDAY_INDEX_TUE, $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_TUE)); ?></button>
                                        <button type="button" class="btn <?php echo (bool) $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_WED) ? 'active' : ''; ?>" data-toggle-input="checkbox">Wed<?php echo $formHelper->checkbox('repeat[weekday_index][]', SchedulizerEventRepeat::WEEKDAY_INDEX_WED, $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_WED)); ?></button>
                                        <button type="button" class="btn <?php echo (bool) $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_THU) ? 'active' : ''; ?>" data-toggle-input="checkbox">Thu<?php echo $formHelper->checkbox('repeat[weekday_index][]', SchedulizerEventRepeat::WEEKDAY_INDEX_THU, $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_THU)); ?></button>
                                        <button type="button" class="btn <?php echo (bool) $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_FRI) ? 'active' : ''; ?>" data-toggle-input="checkbox">Fri<?php echo $formHelper->checkbox('repeat[weekday_index][]', SchedulizerEventRepeat::WEEKDAY_INDEX_FRI, $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_FRI)); ?></button>
                                        <button type="button" class="btn <?php echo (bool) $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_SAT) ? 'active' : ''; ?>" data-toggle-input="checkbox">Sat<?php echo $formHelper->checkbox('repeat[weekday_index][]', SchedulizerEventRepeat::WEEKDAY_INDEX_SAT, $eventObj->weeklyRepeatIsChecked(SchedulizerEventRepeat::WEEKDAY_INDEX_SAT)); ?></button>
                                    </div>
                                </div>
                            </div>
                            <!-- monthly repeats -->
                            <div class="control-group repeat-options" data-show-on="monthly" style="margin-bottom:10px;">
                                <label class="control-label"><strong>On:</strong></label>
                                <div class="controls" style="position:relative;top:-6px;">
                                    <div class="checkbox inline no-pad"><?php echo $formHelper->radio('repeat[monthly][method]', SchedulizerEvent::REPEAT_MONTHLY_SPECIFIC_DATE, $eventObj->getRepeatMonthlyMethod()); ?>&nbsp; Day &nbsp;<input id="repeatMonthlySpecificDay" type="number" value="<?php echo $eventObj->getMonthlyRepeatSpecificDay(); ?>" name="repeat[monthly][specific_day]" class="input-mini" value="" min="1" max="31" /></div>
                                    <div class="checkbox inline no-pad"><?php echo $formHelper->radio('repeat[monthly][method]', SchedulizerEvent::REPEAT_MONTHLY_WEEK_AND_DAY, $eventObj->getRepeatMonthlyMethod()); ?>&nbsp; <?php echo $formHelper->select('repeat[monthly][week]', $timingHelper->monthlyRepeatableWeekOptions(), $eventObj->getMonthlyRepeatWeek(), array('class' => 'input-small')); ?>
                                        <?php echo $formHelper->select('repeat[monthly][weekday]', $timingHelper->weekdayIndicesList(), $eventObj->getMonthlyRepeatWeekday(), array('class' => 'input-medium')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group" style="margin-bottom:8px;">
                                <label class="control-label"><strong>Ends</strong></label>
                                <div class="controls" style="position:relative;top:-7px;">
                                    <div class="checkbox inline no-pad"><?php echo $formHelper->radio('event[repeatIndefinite]', SchedulizerEvent::REPEAT_INDEFINITE_TRUE, $eventObj->getRepeatIndefinite()) ?>&nbsp; Never</div>
                                    <div class="checkbox inline no-pad"><?php echo $formHelper->radio('event[repeatIndefinite]', SchedulizerEvent::REPEAT_INDEFINITE_FALSE, $eventObj->getRepeatIndefinite()) ?>&nbsp; On Date: <?php echo $formHelper->text('event_repeat_end', $eventObj->getRepeatEndDateTimeObj(false)->format(DATE_APP_GENERIC_MDY), array('class' => 'input-small dp-element', 'placeholder' => 'End Date')) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="control-group" style="margin-bottom:0;">
                            <label class="control-label"><strong>Description</strong></label>
                            <div class="controls">
                                <?php Loader::packageElement('editor_config', 'schedulizer', array('theme' => PageTheme::getSiteTheme())); ?>
                                <?php Loader::element('editor_controls'); ?>
                                <?php echo $formHelper->textarea('event[description]', $eventObj->getDescription(), array('class' => 'ccm-advanced-editor')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <button type="submit" class="btn btn-large btn-block btn-success">Save</button>
                    </div>
                </div>
                <?php if( is_null($eventObj->getEventID()) ){ echo $formHelper->hidden('event[calendarID]', $eventObj->getCalendarID()); } ?>
                <?php echo $formHelper->hidden('event[isAlias]', $eventObj->getIsAlias()); ?>
            </form>

            <!-- DELETE THE EVENT? -->
            <?php if( $eventObj->getEventID() >= 1 ){ ?>
                <form id="frmDeleteEvent" action="<?php echo View::url('/dashboard/schedulizer/calendars', 'delete_event', $eventObj->getEventID()); ?>">
                    <div class="row-fluid" style="padding-top:8px;">
                        <div class="span12">
                            <button type="submit" class="btn btn-large btn-block btn-danger">Delete Event</button>
                        </div>
                    </div>
                </form>
            <?php } ?>
        <?php endif; ?>
    </div>

<script type="text/javascript">
    $(function(){
        SchedulizerDashboard.initEventWindow({
            isAllDay: <?php echo $eventObj->getIsAllDay() ? 'true' : 'false' ?>
        });

        <?php if( $eventObj->getIsAlias() ): ?>
        // custom action Hide Day In Event Series
        $('#hideDayInEventSeries').on('click', function(){
            var $this = $(this),
                _data = {eventID: $this.attr('data-eventid'), date: $this.attr('data-date')};

            // ajax call
            $.post(SchedulizerDashboard.toolsURI + 'dashboard/events/nullify_repeat_day', _data, function(resp){
                if(resp.code === 1){
                    // set message
                    $('#aliasEventAlert').html('<p><strong>Success!</strong> This date has been hidden from the event series. You can re-enable it in the Repeat Tab when editing other events in the series.</p>');
                    // close the window and refresh the calendar
                    SchedulizerDashboard.closeTopDialog(2400, function(){
                        $('#schedulizerCalendar').fullCalendar('refetchEvents');
                    });
                }
            }, 'json');
        });
        <?php endif; ?>
    });
</script>
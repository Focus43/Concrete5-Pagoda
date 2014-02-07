<?php /** @var $calendarObj SchedulizerCalendar */
    $formHelper = Loader::helper('form');
    $dateHelper = Loader::helper('date');
?>

<div id="schedulizerWrap">

    <ul class="nav nav-tabs">
        <li class="<?php if($activeTab == 'calendar'){ echo 'active'; } ?>"><a href="#tabGroup1" data-toggle="tab">Calendar</a></li>
        <li class="<?php if($activeTab == 'properties'){ echo 'active'; } ?>"><a href="#tabGroup2" data-toggle="tab" data-init="properties">Properties</a></li>
        <li class="<?php if($activeTab == 'event-attributes'){ echo 'active'; } ?>"><a href="#tabGroup3" data-toggle="tab">Event Attributes</a></li>
        <li class="<?php if($activeTab == 'permissions'){ echo 'active'; } ?>"><a href="#tabGroup4" data-toggle="tab">Permissions</a></li>
    </ul>

    <div class="tab-content" style="overflow:visible;">
        <!-- pane 1 -->
        <div id="tabGroup1" class="tab-pane<?php if($activeTab == 'calendar'){ echo ' active'; } ?>">
            <div id="schedulizerCalendar" style="width:100%;" data-calendar-id="<?php echo $calendarObj->getCalendarID(); ?>">
                <!-- rendered via js -->
            </div>
        </div>

        <!-- pane 2 -->
        <div id="tabGroup2" class="tab-pane<?php if($activeTab == 'properties'){ echo ' active'; } ?>">
            <form method="post" action="<?php echo $this->action('save_calendar', $calendarObj->getCalendarID()); ?>">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label>Calendar Title</label>
                            <div class="controls controls-row">
                                <?php echo $formHelper->text('calendar[title]', $calendarObj->getTitle(), array(
                                    'class' => 'input-block-level',
                                    'placeholder' => 'My Snazzy Calendar')) ;
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label>Default Timezone <span class="label">Note: Individual Events Can Override</span></label>
                            <div class="controls controls-row">
                                <?php echo $formHelper->select('calendar_timezone', $dateHelper->getTimezones(), $calendarObj->getDefaultTimezone(), array(
                                    'class' => 'input-block-level'
                                )); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <h4 class="span12">Custom Attributes</h4>
                </div>
                <div class="custom-attributes row-fluid">
                    <div class="span12">
                        <div class="well">
                            <?php $attrs = array_chunk($calendarAttrs, 3); foreach($attrs AS $chunkd): ?>
                                <div class="row-fluid">
                                    <?php foreach($chunkd AS $akObj){ ?>
                                        <div class="span4">
                                            <div class="control-group">
                                                <label><?php echo $akObj->getAttributeKeyName(); ?></label>
                                                <div class="controls controls-row">
                                                    <?php echo $akObj->render('form', $calendarObj->getAttributeValueObject($akObj), true); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php endforeach; ?>
                            <?php if( ! count($attrs) ): ?>
                                <p style="text-align:center;margin:0;padding:0;">No custom calendar attributes currently configured.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <button class="btn btn-success btn-block btn-large" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- pane 3 -->
        <div id="tabGroup3" class="tab-pane<?php if($activeTab == 'event-attributes'){ echo ' active'; } ?>">
            permissions
        </div>

        <!-- pane 4 -->
        <div id="tabGroup4" class="tab-pane<?php if($activeTab == 'permissions'){ echo ' active'; } ?>">
            permissions
        </div>
    </div>
</div>
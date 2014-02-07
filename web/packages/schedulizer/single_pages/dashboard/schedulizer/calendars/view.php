<?php /** @var $calendarObj SchedulizerCalendar */ ?>
<div class="ccm-ui">
    <div class="row">
        <?php echo Loader::packageElement('flash_message', 'schedulizer', array('flash' => $flash)); ?>
    </div>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Calendar: %s', $calendarObj->getTitle()), t('Calendar Details'), false, false ); ?>

    <div id="schedulizerWrap">
        <div class="ccm-pane-body">
            <?php Loader::packageElement('dashboard/calendars/form_setup', 'schedulizer', array(
                'calendarObj' 	=> $calendarObj,
                'activeTab'     => $activeTab,
                'calendarAttrs'	=> $calendarAttrs
            )); ?>
        </div>
        <div class="ccm-pane-footer"></div>
    </div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>
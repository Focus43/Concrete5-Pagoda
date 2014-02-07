<div class="ccm-ui">
    <div class="row">
        <?php echo Loader::packageElement('flash_message', 'schedulizer', array('flash' => $flash)); ?>
    </div>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Schedulizer Settings'), t('Default Settings.'), false, false ); ?>

<div id="schedulizerWrap">
    <div class="ccm-pane-options">
        <h1 class="lead" style="margin:0;padding:0;">Control Default Settings</h1>
    </div>

    <div class="ccm-pane-body ccm-pane-body-footer">
        <div class="row">
            <div class="span-pane-half">
                <form data-method="ajax" action="<?php echo $this->action('save_default_timezone'); ?>">
                    <h3>Default Timezone</h3>
                    <p>Configure the default timezone assigned when new calendars are created.</p>
                    <?php echo $formHelper->select('config['.SchedulizerPackage::DEFAULT_TIMEZONE.']', $dateHelper->getTimezones(), $configDefaultTimezone, array(
                        'class' => 'input-block-level'
                    )); ?>
                    <div class="clearfix btn-wrap">
                        <input type="submit" value="Save" class="btn btn-default primary pull-right" />
                    </div>
                </form>
            </div>
            <div class="span-pane-half">

            </div>
        </div>
    </div>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>

<script type="text/javascript">
    $(function(){
        $('form[data-method="ajax"]').ajaxifyForm();
    });
</script>
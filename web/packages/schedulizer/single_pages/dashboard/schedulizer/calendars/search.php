<div class="ccm-ui">
    <div class="row">
        <?php echo Loader::packageElement('flash_message', 'schedulizer', array('flash' => $flash)); ?>
    </div>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Calendars'), t('Schedulizer Calendars.'), false, false ); ?>

    <div id="schedulizerWrap">
        <div class="ccm-pane-options">
            <?php /*Loader::packageElement('dashboard/calendars/search_form_advanced', 'schedulizer', array(
                'columns' 			=> $columns,
                'searchInstance' 	=> $searchInstance,
                'searchRequest' 	=> $searchRequest
            ));*/ ?>
        </div>

        <?php Loader::packageElement('dashboard/calendars/search_results', 'schedulizer', array(
            'searchInstance'	=> $searchInstance,
            'listObject'		=> $listObject,
            'listResults'		=> $listResults,
            'pagination'		=> $pagination
        )); ?>
    </div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>
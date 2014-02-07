<?php $columns = SchedulizerCalendarColumnSet::getCurrent(); ?>

<div id="ccm-<?php echo $searchInstance; ?>-search-results">
    <div class="ccm-pane-body">
        <div class="clearfix">
            <div class="pull-left">
                <select id="actionMenu" class="span3" disabled="disabled" data-action-delete="<?php echo 'dashboard/properties/delete'; ?>">
                    <option value="">** With Selected</option>
                    <option value="delete">Delete Calendar(s)</option>
                </select>
            </div>
            <div class="pull-right">
                <a class="btn success" href="<?php echo View::url('dashboard/schedulizer/calendars/add'); ?>"><i class="icon-plus icon-white"></i> New Calendar</a>
            </div>
        </div>

        <table id="schedulizerSearchTable" border="0" cellspacing="0" cellpadding="0" class="ccm-results-list">
            <thead>
            <tr>
                <th><input id="checkAllBoxes" type="checkbox" /></th>
                <?php foreach($columns->getColumns() as $col) { ?>
                    <?php if ($col->isColumnSortable()) { ?>
                        <th class="<?php echo $listObject->getSearchResultsClass($col->getColumnKey())?>"><a href="<?php echo $listObject->getSortByURL($col->getColumnKey(), $col->getColumnDefaultSortDirection(), (SCHEDULIZER_TOOLS_URL . 'dashboard/properties/search_results'), array())?>"><?php echo $col->getColumnName()?></a></th>
                    <?php } else { ?>
                        <th><?php echo $col->getColumnName()?></th>
                    <?php } ?>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach($listResults AS $calendarObj): ?>
                <tr>
                    <td><input type="checkbox" name="calendarID[]" value="<?php echo $calendarObj->getCalendarID(); ?>" /></td>
                    <?php foreach($columns->getColumns() AS $colObj){ ?>
                        <td><?php echo $colObj->getColumnValue($calendarObj); ?></td>
                    <?php } ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- # of results -->
        <?php $listObject->displaySummary(); ?>
    </div>

    <!-- paging stuff -->
    <div class="ccm-pane-footer">
        <?php $listObject->displayPagingV2((SCHEDULIZER_TOOLS_URL . 'dashboard/properties/search_results'), array()); ?>
    </div>
</div>

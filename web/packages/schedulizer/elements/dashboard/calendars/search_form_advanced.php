<?php
Loader::helper('property_search', 'lith');
$formHelper 		= Loader::helper('form');
$dateHelper			= Loader::helper('form/date_time');
$listHelper			= Loader::helper('lists/states_provinces');
$searchFields		= array(
    '' 				=> '** ' . t('Fields'),
    'added_between'	=> t('Added Between'),
    'address'		=> t('Address Includes')
);
?>

<div id="ccm-<?php echo $searchInstance; ?>-search-field-base-elements" style="display:none;">
		<span class="ccm-search-option ccm-search-option-type-date_time"  search-field="added_between">
		<?php echo $formHelper->text('dateRangeStart', array('style' => 'width: 86px'))?>
        <?php echo t('to')?>
        <?php echo $formHelper->text('dateRangeEnd', array('style' => 'width: 86px'))?>
		</span>

		<span class="ccm-search-option" search-field="address">
			<?php echo $formHelper->text('address', '', array('class' => 'span2', 'placeholder' => 'Address')); ?>
            <?php echo $formHelper->text('city', '', array('class' => 'span2', 'placeholder' => 'City')); ?>
            <?php echo $formHelper->select('state', (array('' => 'State') + $listHelper->getStates()), '', array('class' => 'span2')); ?>
            <?php echo $formHelper->text('zip', '', array('class' => 'span1', 'placeholder' => 'Zip')); ?>
		</span>
</div>

<form method="get" id="ccm-<?php echo $searchInstance; ?>-advanced-search" action="<?php echo LITH_TOOLS_URL . 'dashboard/properties/search_results'; ?>">

    <!-- show more search options trigger -->
    <a href="javascript:void(0)" onclick="ccm_paneToggleOptions(this)" class="ccm-icon-option-closed"><?php echo t('Advanced Search')?></a>

    <!-- default search options -->
    <div class="ccm-pane-options-permanent-search">
        <div class="span2">
            <?php echo $formHelper->text('keywords', $_REQUEST['keywords'], array('class' => 'input-block-level helpTooltip', 'placeholder' => t('Keyword Search'), 'title' => 'Address, Zip')); ?>
        </div>
        <div class="span2">
            <?php echo $formHelper->select('typeHandle', PropertySearchHelper::typeHandles('Type'), $_REQUEST['typeHandle'], array('class' => 'input-block-level helpTooltip', 'title' => 'Filter by property type')); ?>
        </div>
        <div class="span2">
            <?php echo $formHelper->select('numResults', array('10' => 'Show 10 (Default)', '25' => 'Show 25', '50' => 'Show 50', '100' => 'Show 100', '500' => 'Show 500'), $_REQUEST['numResults'], array('class' => 'input-block-level helpTooltip', 'title' => '# of results to display')); ?>
        </div>
        <div class="span1">
            <button type="submit" class="btn info pull-right">Search</button>
            <img src="<?php echo ASSETS_URL_IMAGES?>/loader_intelligent_search.gif" width="43" height="11" class="ccm-search-loading" id="ccm-locales-search-loading" />
        </div>
    </div>

    <!-- extra search options -->
    <div class="clearfix ccm-pane-options-content">
        <table id="ccm-<?php echo $searchInstance; ?>-search-advanced-fields" class="table zebra-striped ccm-search-advanced-fields" style="margin-top:1em;">
            <thead>
            <tr>
                <th colspan="2" width="100%"><?php echo t('Additional Filters')?></th>
                <th style="text-align: right; white-space: nowrap"><a href="javascript:void(0)" id="ccm-<?php echo $searchInstance; ?>-search-add-option" class="ccm-advanced-search-add-field"><span class="ccm-menu-icon ccm-icon-view"></span><?php echo t('Add')?></a></th>
            </tr>
            </thead>
            <tbody>
            <tr id="ccm-search-field-base">
                <td><?php echo $formHelper->select('searchField', $searchFields);?></td>
                <td width="100%">
                    <input type="hidden" value="" class="ccm-<?php echo $searchInstance; ?>-selected-field" name="selectedSearchField[]" />
                    <div class="ccm-selected-field-content">
                        <?php echo t('Select Search Field.')?>
                    </div>
                </td>
                <td>
                    <a href="javascript:void(0)" class="ccm-search-remove-option"><img src="<?php echo ASSETS_URL_IMAGES?>/icons/remove_minus.png" width="16" height="16" /></a>
                </td>
            </tr>
            </tbody>
        </table>
        <div id="ccm-search-fields-submit">
            <a href="<?php echo LITH_TOOLS_URL; ?>dashboard/properties/customize_search_columns?searchInstance=<?php echo $searchInstance; ?>" id="ccm-list-view-customize"><span class="ccm-menu-icon ccm-icon-properties"></span><?php echo t('Customize Results')?></a>
        </div>
    </div>
</form>

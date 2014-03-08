<?php
$templateHelper; /** @var BlockTemplateHelper $templateHelper */
$formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */

// options
$columns       = array_combine(range(2,10), range(2,10));
$animationTime = array_combine(range(.25,2,.25), range(.25,2,.25));
$meta          = array('meta-hide' => 'Hidden', 'meta-below' => 'Show Under Photo', 'meta-hover' => 'Show On Hover');
$itemsPerPage  = array_combine(range(10,60,5), range(10,60,5));
$paginationMethod = array('click' => 'User Clicks Load More', 'scroll' => 'User Scrolls To Bottom');
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="4">Image Grid Settings</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Max Columns</td>
            <td>Spacing (px)</td>
            <td>Transition (seconds)</td>
            <td>Meta Info (Title/Description)</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('columns'), $columns, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('columns'), 5)); ?></td>
            <td><?php echo $formHelper->text($templateHelper->field('cellPadding'), FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('cellPadding'), 3), array('class' => 'span1', 'placeholder' => '3') ); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('animationTime'), $animationTime, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('animationTime'), '.5')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('meta'), $meta, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('meta'), 'meta-hover')); ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="2">Pagination Settings <span class="muted">Only load <i>__</i> images at a time (speeds up page load).</span></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Items Per Page</td>
            <td>Load More When</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('itemsPerPage'), $itemsPerPage, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('itemsPerPage'), '15')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('paginationMethod'), $paginationMethod, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('paginationMethod'), 'click')); ?></td>
        </tr>
    </tbody>
</table>
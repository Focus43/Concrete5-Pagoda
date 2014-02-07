<?php
$templateHelper; /** @var BlockTemplateHelper $templateHelper */
$formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="4">Image Grid Settings</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Max Columns</strong></td>
            <td><?php echo $formHelper->select( $templateHelper->field('columns'), array_combine(range(2,10), range(2,10)), $templateHelper->value('columns'), array('style' => 'width:70px;') ); ?></td>
            <td><strong>Spacing</strong></td>
            <td><?php echo $formHelper->text( $templateHelper->field('cellPadding'), $templateHelper->value('cellPadding'), array('class' => 'span1', 'placeholder' => '3') ); ?> px</td>
        </tr>
        <tr>
            <td><strong>Animation Time</strong></td>
            <td><?php echo $formHelper->text( $templateHelper->field('animationTime'), $templateHelper->value('animationTime'), array('class' => 'span1', 'placeholder' => '.25') ); ?> seconds</td>
            <td><strong>Title/Description</strong></td>
            <td colspan="3"><?php echo $formHelper->select( $templateHelper->field('meta'), array('meta-hide' => 'Hidden', 'meta-below' => 'Show Under Photo', 'meta-hover' => 'Show On Hover'), $templateHelper->value('meta'), array('style' => 'width:180px;') ); ?></td>
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
            <td><strong>Show</strong></td>
            <td><?php echo $formHelper->select( $templateHelper->field('itemsPerPage'), array_combine(range(20,60,5), range(20,60,5)), $templateHelper->value('itemsPerPage'), array('style' => 'width:70px;') ); ?> images at a time</td>
        </tr>
        <tr>
            <td><strong>Load More When</strong></td>
            <td><?php echo $formHelper->select( $templateHelper->field('paginationMethod'), array('click' => 'User Clicks Load More', 'scroll' => 'User Scrolls To Bottom'), $templateHelper->value('paginationMethod'), array('style' => 'width:180px;') ); ?></td>
        </tr>
    </tbody>
</table>
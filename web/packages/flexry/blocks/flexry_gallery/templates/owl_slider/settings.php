<?php
$templateHelper; /** @var BlockTemplateHelper $templateHelper */
$formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */

// for truthy values, display as "yes" or "no"
$trueFalseList  = array('false' => 'No', 'true' => 'Yes');

$singleItem     = $trueFalseList;
$items          = array_combine(range(2,12), range(2,12));
$navigation     = $trueFalseList;
$pagination     = $trueFalseList;
$scrollPerPage  = $trueFalseList;

$slideSpeed      = array_combine(range(.25,4,.25), range(.25,4,.25));
$paginationSpeed = array_combine(range(.25,4,.25), range(.25,4,.25));
$autoPlay        = $trueFalseList;
$stopOnHover     = $trueFalseList;
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="5">Display</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Single Item?</td>
            <td>Images Per Slide</td>
            <td>Navigation</td>
            <td>Pagination</td>
            <td>Scroll Per Page</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('singleItem'), $singleItem, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('singleItem'), 'false')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('items'), $items, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('items'), 3)); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('navigation'), $navigation, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('navigation'), 'false')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('pagination'), $pagination, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('pagination'), 'true')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('scrollPerPage'), $scrollPerPage, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('scrollPerPage'), 'false')); ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="3">Transitions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Slide Speed</td>
            <td>Pagination Speed</td>
            <td>Autoplay</td>
            <td>Stop On Hover?</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('slideSpeed'), $slideSpeed, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('slideSpeed'), '0.25')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('paginationSpeed'), $slideSpeed, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('paginationSpeed'), '0.75')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('autoPlay'), $autoPlay, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('autoPlay'), 'true')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('stopOnHover'), $stopOnHover, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('stopOnHover'), 'true')); ?></td>
        </tr>
    </tbody>
</table>
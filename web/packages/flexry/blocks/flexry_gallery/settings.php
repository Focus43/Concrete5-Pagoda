<?php
$templateHelper; /** @var BlockTemplateHelper $templateHelper */
$formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */

// options
$titleDisplay = array(
    'hidden'  => 'Hidden',
    'visible' => 'Visible'
);
$descriptionDisplay = array(
    'hidden'  => 'Hidden',
    'visible' => 'Visible'
);
$shadow = array(
    'none' => 'None',
    'yes' => 'Yes'
);
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="2">Captions</th>
            <th colspan="3">Styles</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Title</td>
            <td>Description</td>
            <td>Padding (px)</td>
            <td>Vertical Spacing (px)</td>
            <td>Shadow</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('titleDisplay'), $titleDisplay, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('titleDisplay'), 'hidden'), array('style' => 'width:100px;')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('descriptionDisplay'), $descriptionDisplay, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('descriptionDisplay'), 'hidden'), array('style' => 'width:100px;')); ?></td>
            <td><?php echo $formHelper->text($templateHelper->field('padding'), FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('padding'), 10), array('class' => 'span1', 'placeholder' => '5')); ?></td>
            <td><?php echo $formHelper->text($templateHelper->field('margin'), FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('margin'), 15), array('class' => 'span1', 'placeholder' => '5')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('shadow'), $shadow, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('shadow'), 'yes')); ?></td>
        </tr>
    </tbody>
</table>
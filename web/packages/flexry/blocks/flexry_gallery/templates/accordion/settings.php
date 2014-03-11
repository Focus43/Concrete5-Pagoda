<?php
$templateHelper; /** @var BlockTemplateHelper $templateHelper */
$formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */

// options
$trigger         = array('mouseenter' => 'Mouse Over', 'click' => 'Click');
$transitionSpeed = array_combine(range(.35,1.5,.1), range(.35,1.5,.1));
$autoPlay        = array('false' => 'No', 'true' => 'Yes');
$autoPlaySpeed   = array_combine(range(1,10,.5), range(1,10,.5));
$pauseMouseOver  = array('false' => 'No', 'true' => 'Yes');
?>

<?php Loader::packageElement('alert_crop_fit', 'flexry'); ?>

<p><strong>Note:</strong> Lightboxes are not supported by this template, even if enabled in the <i>Settings</i> tab.</p>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="5">Settings</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Open When</td>
            <td>Animation Speed</td>
            <td>AutoPlay</td>
            <td>AutoPlay Speed</td>
            <td>Pause On Hover</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('trigger'), $trigger, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('trigger'), 'mouseenter')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('transitionSpeed'), $transitionSpeed, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('transitionSpeed'), '0.65')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('autoPlay'), $autoPlay, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('autoPlay'), '3')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('autoPlaySpeed'), $autoPlaySpeed, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('autoPlaySpeed'), '3')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('pauseMouseOver'), $pauseMouseOver, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('pauseMouseOver'), 'true')); ?></td>
        </tr>
    </tbody>
</table>
<?php defined('C5_EXECUTE') or die("Access Denied.");
$templateHelper; /** @var BlockTemplateHelper $templateHelper */
$formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */

// options
$itemCount  = array_combine(range(1,6,1), range(1,6,1));
$rotateTime = array_combine(range(1,12,.5), range(1,12,.5));
?>

<?php Loader::packageElement('alert_crop_fit', 'flexry'); ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="2"><?php echo t('Settings'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo t('Items To Show'); ?></td>
            <td><?php echo t('Rotate Every (seconds)'); ?></td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('itemCount'), $itemCount, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('itemCount'), 3)); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('rotateTime'), $rotateTime, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('rotateTime'), '3')); ?></td>
        </tr>
    </tbody>
</table>
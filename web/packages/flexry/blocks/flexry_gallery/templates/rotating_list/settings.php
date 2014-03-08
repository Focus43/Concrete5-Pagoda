<?php
$templateHelper; /** @var BlockTemplateHelper $templateHelper */
$formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */

// options
$itemCount  = array_combine(range(1,6,1), range(1,6,1));
$rotateTime = array_combine(range(1,12,.5), range(1,12,.5));
?>

<div class="alert alert-danger ftl-alert-crop-fit" style="display:none;margin-bottom:10px;">
    <strong>Heads Up!</strong> It is highly recommended to check <i>Crop To Fit</i> on the thumbnail size in the settings tab to ensure
    consistent heights; not doing so could cause a jumpy page. <span class="rtl-check-crop-fit" style="text-decoration:underline;font-weight:bold;cursor:pointer;">Click here to enable.</span>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="2">Settings</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Items To Show</td>
            <td>Rotate Every (seconds)</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('itemCount'), $itemCount, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('itemCount'), 3)); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('rotateTime'), $rotateTime, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('rotateTime'), '3')); ?></td>
        </tr>
    </tbody>
</table>

<script type="text/javascript">
    $(function(){
        var $cropFitAlert       = $('.ftl-alert-crop-fit'),
            $thumbCropCheckbox  = $('#thumbCrop');

        // if clicked, automatically check the crop to fit checkbox
        $('.rtl-check-crop-fit').on('click', function(){
            $thumbCropCheckbox.prop('checked', true);
            $cropFitAlert.hide();
        });

        // if the crop to fit box is changed, and its *unchecked*, reshow the alert
        $thumbCropCheckbox.on('change.rtl', function(){
            $cropFitAlert.toggle( !this.checked );
        }).trigger('change.rtl');
    });
</script>
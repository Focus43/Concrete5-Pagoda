<?php
    $templateHelper; /** @var BlockTemplateHelper $templateHelper */
    $formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */
?>

    <div class="alert alert-danger ftl-alert-crop-fit" style="display:none;margin-bottom:10px;">
        <strong>Heads Up!</strong> It is highly recommended to check <i>Crop To Fit</i> on the thumbnail size in the settings tab to ensure
        consistent heights; not doing so could cause a jumpy page. <span class="rtl-check-crop-fit" style="text-decoration:underline;font-weight:bold;cursor:pointer;">Click here to enable.</span>
    </div>

    <table class="table table-bordered">
        <tr>
            <td><strong>Show</strong></td>
            <td><?php echo $formHelper->select( $templateHelper->field('itemCount'), array_combine(range(1,6,1), range(1,6,1)), $templateHelper->value('itemCount'), array('style' => 'width:80px') ); ?> at a time</td>
            <td><strong>Rotate Every</strong></td>
            <td><?php echo $formHelper->text( $templateHelper->field('rotateTime'), $templateHelper->value('rotateTime'), array('class' => 'span1', 'placeholder' => '2000') ); ?> ms</td>
            <td><strong>Randomize</strong></td>
            <td><?php echo $formHelper->checkbox( $templateHelper->field('randomize'), 1, (int) $templateHelper->value('randomize') ); ?></td>
        </tr>
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
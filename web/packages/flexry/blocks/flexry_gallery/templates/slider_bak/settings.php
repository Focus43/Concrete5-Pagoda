<?php
    $templateHelper; /** @var BlockTemplateHelper $templateHelper */
    $formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */
?>

    <table class="table table-bordered">
        <tr>
            <td><strong>Items Per Page</strong></td>
            <td><?php echo $formHelper->text($templateHelper->field('items'), $templateHelper->value('items'), array('class' => 'span1', 'placeholder' => 3)); ?></td>
        </tr>
        <tr>
            <td><strong>Autoplay</strong></td>
            <td><label class="checkbox"><?php echo $formHelper->checkbox($templateHelper->field('autoplay'), 1, (int) $templateHelper->value('autoplay') ); ?> Automatically scroll through elements</label></td>
        </tr>
        <tr>
            <td><strong>Navigation Settings</strong></td>
            <td><label class="checkbox"><?php echo $formHelper->checkbox($templateHelper->field('showNavigation'), 1, (int) $templateHelper->value('showNavigation') ); ?> Show Navigation</label>
                <label class="checkbox"><?php echo $formHelper->checkbox($templateHelper->field('showPagination'), 1, (int) $templateHelper->value('showPagination') ); ?> Show Page Markers</label>
            </td>
        </tr>
    </table>
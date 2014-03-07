<?php
    $templateHelper; /** @var BlockTemplateHelper $templateHelper */
    $formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="4">Caption Settings</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Title</strong></td>
            <td><?php echo $formHelper->select($templateHelper->field('titleDisplay'), array('hidden' => 'Hidden', 'visible' => 'Visible'), $templateHelper->value('titleDisplay'), array('style' => 'width:140px;') ); ?></td>
            <td><strong>Description</strong></td>
            <td><?php echo $formHelper->select($templateHelper->field('descriptionDisplay'), array('hidden' => 'Hidden', 'visible' => 'Visible'), $templateHelper->value('descriptionDisplay'), array('style' => 'width:140px;') ); ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="4">Spacing &amp; Style</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><strong>Padding</strong></td>
        <td><?php echo $formHelper->text($templateHelper->field('padding'), $templateHelper->value('padding'), array('class' => 'span1', 'placeholder' => '3') ); ?> px</td>
        <td><strong>Vertical Spacing</strong></td>
        <td><?php echo $formHelper->text($templateHelper->field('margin'), $templateHelper->value('margin'), array('class' => 'span1', 'placeholder' => '5') ); ?> px</td>
        <td><strong>Shadow</strong></td>
        <td><?php echo $formHelper->select($templateHelper->field('shadow'), array('none' => 'None', 'yes' => 'Yes'), $templateHelper->value('shadow'), array('style' => 'width:140px;') ); ?></td>
    </tr>
    </tbody>
</table>
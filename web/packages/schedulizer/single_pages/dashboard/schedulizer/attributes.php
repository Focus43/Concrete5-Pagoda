<?php // IF WE'RE EDITING AN EXISTING ATTRIBUTE
if( $editable && is_object($attrKey) ){ ?>

    <?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Edit Attribute'), false, false, false)?>

    <form method="post" action="<?php echo $this->action('update')?>" id="ccm-attribute-key-form">
        <?php
        $deleteAction = $this->url('/dashboard/schedulizer/attributes', 'delete', $attrKey->getAttributeKeyID(), $validation_token->generate('delete_attribute'));
        Loader::element("attribute/type_form_required", array('category' => $attrCategory, 'type' => $attrType, 'key' => $attrKey, 'deleteAction' => $deleteAction));
        ?>
    </form>

    <?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>



<?php }elseif($this->controller->getTask() === 'add'){ // IF WE'RE ADDING A NEW ATTRIBUTE ?>

    <?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Calendar Attributes'), false, false, false)?>

    <?php  if (isset($attrType)) { ?>
        <form method="post" action="<?php echo $this->action('create')?>" id="ccm-attribute-key-form">

            <?php Loader::element("attribute/type_form_required", array('category' => $attrCategory, 'type' => $attrType)); ?>

        </form>
    <?php  } ?>

    <?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>



<?php }else{ // ATTRIBUTE LIST DISPLAY ?>

    <?php
    echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Calendar Attributes'), false, false, false);

    $attribs = SchedulizerCalendarAttributeKey::getList();
    Loader::element('dashboard/attributes_table', array('category' => $attrCategory, 'attribs'=> $attribs, 'editURL' => '/dashboard/schedulizer/attributes'));
    ?>

    <div class="ccm-pane-body ccm-pane-body-footer" style="margin-top: -25px">
        <form method="get" class="form-stacked inline-form-fix" action="<?php echo $this->action('add')?>" id="ccm-attribute-type-form">
            <div class="clearfix">
                <?php echo $form->label('atID', t('Add Attribute'))?>
                <div class="input">

                    <?php echo $form->select('atID', $attrTypesList)?>
                    <?php echo $form->submit('submit', t('Go'))?>

                </div>
            </div>
        </form>
    </div>

    <?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>

<?php }
<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var BlockView $this */
/** @var FormHelper $formHelper */
/** @var TooltipsHelper $tooltips */
?>

	<style type="text/css">
        #flexryGallery .nav-tabs select {width:160px;}
        #flexryGallery .nav-tabs a {cursor:pointer;}
        #flexryGallery .tab-content {overflow:visible;}
        #flexryGallery .well {margin-bottom:15px;padding-top:13px;padding-bottom:11px;}
        #flexryGallery .well h3 {margin:0;padding-bottom:5px;line-height:1em;}
        #flexryGallery .well p:last-child {margin:0;}
        #flexryGallery .well p.muted {font-size:11px;}
        #flexryGallery label {display:inline-block;font-weight:inherit;margin-bottom:0;padding-top:0;min-height:0;}
        #flexryGallery table.table {vertical-align:middle;margin-bottom:8px;background:#fff;border-spacing:0;border-collapse:collapse;}
        #flexryGallery table.table:last-child {margin-bottom:0;}
        #flexryGallery table tbody {border:0;}
        #flexryGallery table.table th,
        #flexryGallery table.table td {white-space:nowrap;vertical-align:inherit;background:#fff;}
        /*#flexryGallery table.table tr td:last-child {width:98%;}*/
        #flexryGallery table.table.table-bordered thead tr th {white-space:nowrap;border-bottom-width:0 !important;}
        #flexryGallery input[type="text"] {padding:5px;width:100%;height:auto;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
        #flexryGallery input[type="checkbox"] {position:relative;top:-1px;}
        #flexryGallery select {padding:5px;width:100%;min-width:68px;height:27px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
        /* duplicates warning */
        #tabPaneImages .alert {display:none;}
        #tabPaneImages.dups .alert {display:block;}
        #tabPaneImages.dups .alert .btn {color:inherit;float:right;}
        #tabPaneImages.dups .alert .close {top:1px;}
        #tabPaneImages.dups #imageSelections {top:110px;}
        /* custom gallery builder */
        #imageSelections {background:#eee;position:absolute;top:58px;right:10px;bottom:10px;left:10px;border:1px dashed #bbb;overflow-y:scroll;overflow-x:hidden;}
        #imageSelections p {position:absolute;z-index:5;width:100%;text-align:center;font-size:11px;color:#777;margin:0;padding:8px 0;}
        #imageSelections p i {position:relative;top:-2px;}
            #flexryClearAll {position:relative;top:-1px;}
        #imageSelections .inner {padding:38px 0 0;width:100%;height:100%;position:relative;}
        #imageSelections .item {display:inline-block;width:60px;height:60px;margin:0 0 5px 6px;position:relative;border:1px solid #fff;cursor:pointer;background-size:cover;background-position:50% 50%; -webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box; -webkit-box-shadow:0 0 4px rgba(0,0,0,.25);-moz-box-shadow:0 0 4px rgba(0,0,0,.25);box-shadow:0 0 4px rgba(0,0,0,.25);-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;}
        #imageSelections .item i {display:none;position:absolute;}
        #imageSelections .item i.icon-minus-sign {bottom:-8px;right:-6px;cursor:not-allowed;}
        #imageSelections .item i.icon-move {top:-8px;left:50%;margin-left:-8px;cursor:move;}
        #imageSelections .item:hover i {display:block;}
        /* gallery type selection dropdown */
        #flexryGallery .fileSourceMethod {display:none;}
        #flexryGallery .fileSourceMethod.active {display:block;}
        /* template forms */
        #tabPaneTemplates .template-form {display:none;margin:10px 0 0;}
        #tabPaneTemplates .template-form.active {display:block;}
	</style>

	<div id="flexryGallery" class="ccm-ui">
        <ul class="nav nav-tabs">
            <li class="active"><a data-tab="#tabPaneImages">Gallery</a></li>
            <li><a data-tab="#tabPaneSettings">Settings</a></li>
            <li><a data-tab="#tabPaneTemplates">Template Options</a></li>
            <li id="flexryOptionsRight" class="pull-right">
                <?php echo $formHelper->select('fileSourceMethod', FlexryGalleryBlockController::$fileSourceMethods, $this->controller->fileSourceMethod); ?>
                <button id="chooseImg" type="button" class="btn" title="Select multiple with checkboxes." data-method="<?php echo FlexryGalleryBlockController::FILE_SOURCE_METHOD_CUSTOM; ?>">Add Images</button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- image selection tab -->
            <div id="tabPaneImages" class="tab-pane active">
                <!-- build gallery manually -->
                <div class="fileSourceMethod <?php if((int)$this->controller->fileSourceMethod === FlexryGalleryBlockController::FILE_SOURCE_METHOD_CUSTOM){ echo 'active'; } ?>" data-method="<?php echo FlexryGalleryBlockController::FILE_SOURCE_METHOD_CUSTOM; ?>">
                    <div class="dups-warning alert alert-warning">The same image was added more than once; duplicates have been removed.  <button type="button" class="close">&times;</button></div>
                    <div id="imageSelections">
                        <p>Hover images and: <i class="icon-hand-up"></i> <strong>click</strong> to edit; <i class="icon-move"></i> to reorder; <i class="icon-minus-sign"></i> to remove. <button id="flexryClearAll" class="btn btn-mini btn-warning" type="button">Clear All</button></p>
                        <div class="inner clearfix">
                            <?php foreach($imageList AS $fileObj){ /** @var FlexryFile $fileObj */ ?>
                                <div class="item" style="background-image:url('<?php echo $fileObj->getThumbnail(2, false); ?>');">
                                    <i class="icon-minus-sign"></i><i class="icon-move"></i>
                                    <input type="hidden" name="fileIDs[]" value="<?php echo $fileObj->getFileID(); ?>" />
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- compose gallery from sets -->
                <div class="fileSourceMethod <?php if((int)$this->controller->fileSourceMethod === FlexryGalleryBlockController::FILE_SOURCE_METHOD_SETS){ echo 'active'; } ?>" data-method="<?php echo FlexryGalleryBlockController::FILE_SOURCE_METHOD_SETS; ?>">
                    <div class="well">
                        <h3>Choose One Or More File Sets</h3>
                        <p>If more than one File Set is used, images will be ordered randomly.</p>
                        <select id="fileSetPicker" class="input-block-level" name="fileSetIDs[]" multiple data-placeholder="Choose one or more File Set">
                            <?php foreach($availableFileSets AS $fsObj): ?>
                                <option value="<?php echo $fsObj->getFileSetID(); ?>"<?php if(in_array($fsObj->getFileSetID(), $savedFileSets)){ echo ' selected="selected"'; } ?>><?php echo $fsObj->getFileSetName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="muted" style="padding-top:4px;">The advantage to using File Sets is that you can simply add one or more images to a set, or image sets, in the File Manager, and the gallery will automatically update with the images (instead of adding by hand using the custom gallery option).</p>
                    </div>
                </div>
            </div>

            <!-- settings tab -->
            <div id="tabPaneSettings" class="tab-pane">
                <div class="well">
                    <h3>Image Size Settings</h3>
                    <p>Thumbnails are generally used to display on the page, while the full size image may be shown after a user-triggered event (ie. click to view full size in a lightbox).</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Thumbnail Size</th>
                                <th><label class="checkbox"><?php echo $formHelper->checkbox('fullUseOriginal', FlexryGalleryBlockController::FULL_USE_ORIGINAL_TRUE, $this->controller->fullUseOriginal); ?> Use Original Image As Full Size?</label></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Max Width (px)</strong></td>
                                <td><?php echo $formHelper->text('thumbWidth', $this->controller->thumbWidth, array('placeholder' => '250')); ?></td>
                                <td><?php echo $formHelper->text('fullWidth', ($this->controller->fullWidth >= 1 ? $this->controller->fullWidth : ''), array('placeholder' => '900')); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Max Height (px)</strong></td>
                                <td><?php echo $formHelper->text('thumbHeight', $this->controller->thumbHeight, array('placeholder' => '250')); ?></td>
                                <td><?php echo $formHelper->text('fullHeight', ($this->controller->fullHeight >= 1 ? $this->controller->fullHeight : ''), array('placeholder' => '750')); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Crop To Fit?</strong></td>
                                <td><label class="checkbox"><?php echo $formHelper->checkbox('thumbCrop', FlexryGalleryBlockController::CROP_TRUE, $this->controller->thumbCrop); ?> Yes</label></td>
                                <td><label class="checkbox"><?php echo $formHelper->checkbox('fullCrop', FlexryGalleryBlockController::CROP_TRUE, $this->controller->fullCrop); ?> Yes</label></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="muted">Images will be scaled to the maximum width/height, while maintaining the aspect ratio so as not to appear blurry.</p>
                </div>

                <div class="well">
                    <h3>Gallery Settings</h3>
                    <p>Allow user to click an image and view full size image overlayed on the page.</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="5"><label class="checkbox" style="font-weight:normal;"><?php echo $formHelper->checkbox('lightbox[enable]', FlexryGalleryBlockController::LIGHTBOX_ENABLE_TRUE, (int) $this->controller->lightboxEnable, array('class' => 'enableLightboxCheckbox')); ?> Enable Lightbox Gallery (If supported by template)</label></th>
                            </tr>
                        </thead>
                        <tbody class="flexry-lightbox-settings">
                            <tr>
                                <td rowspan="2"><strong>Mask</strong></td>
                                <td>Color</td>
                                <td>Opacity (&#37;)</td>
                                <td>Fade Speed (sec)</td>
                                <td>Click __ To Close</td>
                            </tr>
                            <tr>
                                <td><?php echo $formHelper->text('lightbox[maskColor]', $this->controller->lbMaskColor, array('class' => 'span2 color-choose', 'placeholder' => '2d2d2d') ); ?></td>
                                <td><?php echo $formHelper->select('lightbox[maskOpacity]', FlexryGalleryBlockController::lightboxMaskOpacities(), $this->controller->lbMaskOpacity); ?></td>
                                <td><?php echo $formHelper->select('lightbox[maskFadeSpeed]', FlexryGalleryBlockController::lightboxAnimationsTiming(), $this->controller->lbMaskFadeSpeed); ?></td>
                                <td><?php echo $formHelper->select('lightbox[closeOnClick]', FlexryGalleryBlockController::$lightboxCloseMethods, $this->controller->lbCloseOnClick); ?></td>
                            </tr>
                            <tr>
                                <td rowspan="2"><strong>Animation</strong></td>
                                <td>Transition Effect</td>
                                <td colspan="3">Duration (sec)</td>
                            </tr>
                            <tr>
                                <td><?php echo $formHelper->select('lightbox[transitionEffect]', FlexryGalleryBlockController::$lightboxTransitions, $this->controller->lbTransitionEffect); ?></td>
                                <td colspan="3"><?php echo $formHelper->select('lightbox[transitionDuration]', FlexryGalleryBlockController::lightboxAnimationsTiming(), $this->controller->lbTransitionDuration); ?></td>
                            </tr>
                            <tr>
                                <td rowspan="2"><strong>Display</strong></td>
                                <td>Captions <?php echo $tooltips->generate('Title/Description', 'Responsive presets will trigger hiding on smaller devices.'); ?></td>
                                <td colspan="3">Thumbnail Markers <?php echo $tooltips->generate('Image Gallery', 'Displays circle icons (ie. pagination) to enable quick navigation of full gallery.') ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $formHelper->select('lightbox[captions]', FlexryGalleryBlockController::$lightboxCaptionsAndMarkers, $this->controller->lbCaptions); ?></td>
                                <td colspan="3"><?php echo $formHelper->select('lightbox[galleryMarkers]', FlexryGalleryBlockController::$lightboxCaptionsAndMarkers, $this->controller->lbGalleryMarkers); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="well">
                    <h3>Advanced</h3>
                    <p>Fine-tune the block settings (mainly for advanced users).</p>
                    <table class="table table-bordered">
                        <tr>
                            <td><label class="checkbox"><?php echo $formHelper->checkbox('autoIncludeJsInFooter', FlexryGalleryBlockController::JS_IN_FOOTER_TRUE, $this->controller->autoIncludeJsInFooter); ?> Output javascript includes in footer</label></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="tabPaneTemplates" class="tab-pane">
                <div class="well">
                    <h3>Template Settings</h3>
                    <p>Configure different settings for different block templates.</p>
                    <?php echo $formHelper->select('flexryTemplateHandle', $templateSelectList, $currentTemplateHandle, array('class' => 'input-block-level')); ?>
                    <div class="template-form <?php if( empty($currentTemplateHandle) ){ echo 'active'; } ?>" data-tmpl="">
                        <?php FlexryBlockTemplateOptions::setup(FlexryGalleryBlockController::TEMPLATE_DEFAULT_HANDLE, FlexryGalleryBlockController::templateDefaultPath(), $templateData)->renderForm(); ?>
                    </div>
                    <?php foreach($templateDirList AS $handle => $templatePath): ?>
                        <div class="template-form <?php if( $currentTemplateHandle == $handle ){ echo 'active'; } ?>" data-tmpl="<?php echo $handle; ?>">
                            <?php
                            if( is_dir($templatePath) && file_exists($templatePath . '/settings.php') ){
                                FlexryBlockTemplateOptions::setup( $handle, $templatePath, $templateData )->renderForm();
                            }else{
                                echo '<p>'.t('This template has no editable options').'.</p>';
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
	</div>
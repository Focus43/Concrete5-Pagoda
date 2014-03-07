<?php defined('C5_EXECUTE') or die("Access Denied.");
    /** @var BlockTemplateHelper $templateHelper */
    // templace-specific settings
    $selectorID = t('flexryCamera-%s', $this->controller->bID);
    //$itemCount  = ((int) $templateHelper->value('itemCount') >= 1) ? (int) $templateHelper->value('itemCount') : 3;
    /** @var FlexryFileList $fileListObj */
    $imageList = $fileListObj->get();
    ?>

    <div id="<?php echo $selectorID; ?>" class="flexryCamera camera_wrap camera_ash_skin">
        <?php foreach($imageList AS $flexryFile): /** @var FlexryFile $flexryFile */ ?>
            <div class="flexry-camera-item" data-src="<?php echo $flexryFile->fullImgSrc(); ?>" data-thumb="<?php echo $flexryFile->thumbnailImgSrc(); ?>">
                <div class="camera_caption"><?php echo $flexryFile->getDescription(); ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <script type="text/javascript">
        (function( _stack ){
            _stack.push(function(){
                $('#<?php echo $selectorID; ?>').flexryCamera();
                <?php echo $lightboxHelper->bindTo("#{$selectorID}")->itemTargets('.flexry-rtl-item')->initOutput(); ?>
            });
            window._flexry = _stack;
        }( window._flexry || [] ));
    </script>
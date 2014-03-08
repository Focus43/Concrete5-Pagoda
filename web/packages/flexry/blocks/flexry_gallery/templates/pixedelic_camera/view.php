<?php defined('C5_EXECUTE') or die("Access Denied.");
    /** @var BlockTemplateHelper $templateHelper */
    // templace-specific settings
    $selectorID = t('flexryCamera-%s', $this->controller->bID);
    /** @var FlexryFileList $fileListObj */
    $imageList = $fileListObj->get();
    ?>

    <div id="<?php echo $selectorID; ?>" class="flexryCamera camera_wrap">
        <?php foreach($imageList AS $flexryFile): /** @var FlexryFile $flexryFile */ ?>
            <div class="flexry-camera-item" data-src="<?php echo $flexryFile->fullImgSrc(); ?>" data-thumb="<?php echo $flexryFile->thumbnailImgSrc(); ?>">
                <div class="camera_caption"><?php echo $flexryFile->getDescription(); ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <script type="text/javascript">
        (function( _stack ){
            _stack.push(function(){
                $('#<?php echo $selectorID; ?>').flexryCamera({
                    fx              : '<?php echo $templateHelper->value('fx'); ?>',
                    easing          : '<?php echo $templateHelper->value('easing'); ?>',
                    time            : <?php echo (int)((float)$templateHelper->value('time')*1000); ?>,
                    transPeriod     : <?php echo (int)((float)$templateHelper->value('transPeriod')*1000); ?>,
                    alignment       : '<?php echo $templateHelper->value('alignment'); ?>',
                    portrait        : <?php echo $templateHelper->value('portrait'); ?>,
                    loader          : '<?php echo $templateHelper->value('loader'); ?>',
                    loaderColor     : '#<?php echo $templateHelper->value('loaderColor'); ?>',
                    loaderBgColor   : '#<?php echo $templateHelper->value('loaderBgColor'); ?>',
                    loaderOpacity   : <?php echo (float)$templateHelper->value('loaderOpacity'); ?>,
                    loaderPadding   : <?php echo (int) $templateHelper->value('loaderPadding'); ?>,
                    loaderStroke    : <?php echo (int) $templateHelper->value('loaderStroke'); ?>,
                    pieDiameter     : <?php echo (int) $templateHelper->value('pieDiameter'); ?>,
                    piePosition     : '<?php echo $templateHelper->value('piePosition'); ?>',
                    barDirection    : '<?php echo $templateHelper->value('barDirection'); ?>',
                    barPosition     : '<?php echo $templateHelper->value('barPosition'); ?>'
                });
                <?php echo $lightboxHelper->bindTo("#{$selectorID}")->itemTargets('.flexry-rtl-item')->initOutput(); ?>
            });
            window._flexry = _stack;
        }( window._flexry || [] ));
    </script>
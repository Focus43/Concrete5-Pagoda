<?php defined('C5_EXECUTE') or die("Access Denied.");
    /** @var BlockTemplateHelper $templateHelper */
    // templace-specific settings
    $selectorID = t('flexryRtl-%s', $this->controller->bID);
    /** @var FlexryFileList $fileListObj */
    $imageList = $fileListObj->get();
    $chunkd    = array_chunk($imageList, (int)$templateHelper->value('itemCount'));
?>

    <div id="<?php echo $selectorID; ?>" class="flexryRtl">
        <?php foreach($chunkd AS $group): ?>
            <div class="flexry-rtl-group">
                <?php foreach($group AS $index => $flexryFile){ /** @var FlexryFile $flexryFile */ ?>
                    <div class="flexry-rtl-item<?php echo $index === 0 ? ' current' : ''; ?>" data-src-full="<?php echo $flexryFile->fullImgSrc(); ?>">
                        <img class="rtl-image" src="<?php echo $flexryFile->thumbnailImgSrc(); ?>" alt="<?php echo $flexryFile->getTitle(); ?>" />
                        <!-- spans hidden; but present for lightbox data -->
                        <span class="title"><?php echo $flexryFile->getTitle(); ?></span>
                        <span class="descr"><?php echo $flexryFile->getDescription(); ?></span>
                    </div>
                <?php } ?>
            </div>
        <?php endforeach; ?>
    </div>

<script type="text/javascript">
    (function( _stack ){
        _stack.push(function(){
            $('#<?php echo $selectorID; ?>').flexryRtl({
                rotateTime  : <?php echo (int)((float)$templateHelper->value('rotateTime')*1000); ?>
            });
            <?php echo $lightboxHelper->bindTo($selectorID)->itemTargets('.flexry-rtl-item')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
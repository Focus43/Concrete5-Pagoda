<?php defined('C5_EXECUTE') or die("Access Denied.");
    /** @var BlockTemplateHelper $templateHelper */
    // templace-specific settings
    $selectorID = t('flexryRtl-%s', $this->controller->bID);
    $itemCount  = ((int) $templateHelper->value('itemCount') >= 1) ? (int) $templateHelper->value('itemCount') : 3;
    $rotateTime = ((int) $templateHelper->value('rotateTime') >= 850) ? (int) $templateHelper->value('rotateTime') : 1500;
    $randomize  = ((bool) $templateHelper->value('randomize')) ? 'true' : 'false';
    /** @var FlexryFileList $fileListObj */
    $imageList  = $fileListObj->get();
    $chunkd     = array_chunk($imageList, count($imageList)/$itemCount);
?>

    <div id="<?php echo $selectorID; ?>" class="flexryRtl">
        <?php foreach($chunkd AS $group): ?>
            <div class="flexry-rtl-group">
                <?php foreach($group AS $index => $flexryFile){ /** @var FlexryFile $flexryFile */ ?>
                    <div class="flexry-rtl-item<?php echo $index === 0 ? ' current' : ''; ?>" data-src-full="<?php echo $flexryFile->fullImgSrc(); ?>">
                        <img class="rtl-image" src="<?php echo $flexryFile->thumbnailImgSrc(); ?>" alt="<?php echo $flexryFile->getTitle(); ?>" />
                    </div>
                <?php } ?>
            </div>
        <?php endforeach; ?>
    </div>

<script type="text/javascript">
    (function( _stack ){
        _stack.push(function(){
            $('#<?php echo $selectorID; ?>').flexryRtl({
                rotateTime  : <?php echo $rotateTime; ?>,
                randomize   : <?php echo $randomize . "\n"; ?>
            });
            <?php echo $lightboxHelper->bindTo("#{$selectorID}")->itemTargets('.flexry-rtl-item')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
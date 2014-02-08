<?php defined('C5_EXECUTE') or die("Access Denied.");
    /** @var BlockTemplateHelper $templateHelper */
    /** @var FlexryFileList $fileListObj */
    $imageList  = $fileListObj->get();
    $selectorID = t('flexrySlicebox-%s', $this->controller->bID);
?>

<div id="<?php echo $selectorID; ?>" class="flexry-dicer">
    <?php foreach($imageList AS $index => $flexryFile): /** @var FlexryFile $flexryFile */ ?>
        <div class="flexry-dicer-item <?php if($index === 0){echo 'current';} ?>">
            <div class="inner">
                <img src="<?php echo $flexryFile->thumbnailImgSrc(); ?>" alt="<?php echo $flexryFile->getTitle(); ?>" />
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script type="text/javascript">
    (function( _stack ){
        _stack.push(function(){
            $('#<?php echo $selectorID; ?>').flexryDicer();
            <?php echo $lightboxHelper->bindTo("#{$selectorID}")->itemTargets('.slicebox-item')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
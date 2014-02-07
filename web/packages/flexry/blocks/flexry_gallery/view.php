<?php defined('C5_EXECUTE') or die("Access Denied.");
    /** @var BlockTemplateHelper $templateHelper */
    /** @var FlexryFileList $fileListObj */
    $imageList  = $fileListObj->get();
    $selectorID = t('flexryDefault-%s', $this->controller->bID);
?>

    <div id="<?php echo $selectorID; ?>" class="flexry-default">
    <?php foreach($imageList AS $flexryFile): /** @var FlexryFile $flexryFile */ ?>
        <div class="flexry-default-row">
            <div class="flexry-default-item" data-src-full="<?php echo $flexryFile->fullImgSrc(); ?>">
                <img src="<?php echo $flexryFile->thumbnailImgSrc(); ?>" alt="<?php echo $flexryFile->getTitle(); ?>" />
                <span class="title"><?php echo $flexryFile->getTitle(); ?></span>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

<script type="text/javascript">
    (function( _stack ){
        _stack.push(function(){
            <?php echo $lightboxHelper->bindTo("#{$selectorID}")->itemTargets('.flexry-default-item')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
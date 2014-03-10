<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var BlockTemplateHelper $templateHelper */
/** @var FlexryFileList $fileListObj */

$selectorID    = sprintf('flexryRtl-%s', $this->controller->bID);
$imageList     = $fileListObj->get();
$itemsPerChunk = count($imageList)/(int)$templateHelper->value('itemCount');
$chunkd        = array_chunk($imageList, (is_float($itemsPerChunk) ? $itemsPerChunk + 1 : $itemsPerChunk));

$settingsData  = (object) array(
    'rotateTime' => (int)((float)$templateHelper->value('rotateTime')*1000)
);
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
            $('#<?php echo $selectorID; ?>').flexryRtl(<?php echo Loader::helper('json')->encode($settingsData); ?>);
            <?php echo $lightboxHelper->bindTo($selectorID)->itemTargets('.flexry-rtl-item')->setDelegateTarget('.rtl-image')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
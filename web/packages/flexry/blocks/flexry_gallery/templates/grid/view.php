<?php defined('C5_EXECUTE') or die("Access Denied.");
    /** @var BlockTemplateHelper $templateHelper */
    // template-specific settings
    $selectorID       = t('flexryGrid-%s', $this->controller->bID);
    $columns          = ((int) $templateHelper->value('columns') >= 2) ? (int) $templateHelper->value('columns') : 2;
    $animationTime    = (is_numeric($templateHelper->value('animationTime'))) ? $templateHelper->value('animationTime') : '.25';
    $cellPadding      = ((int) $templateHelper->value('cellPadding') >= 1) ? (int) $templateHelper->value('cellPadding') : false;
    $meta             = ((string) $templateHelper->value('meta')) ? $templateHelper->value('meta') : '';
    $paginationMethod = ((string) $templateHelper->value('paginationMethod') != '') ? $templateHelper->value('paginationMethod') : 'click';
    /** @var FlexryFileList $fileListObj */
    $fileListObj->setItemsPerPage( ((int)$templateHelper->value('itemsPerPage')) ? (int)$templateHelper->value('itemsPerPage') : 10 );
    $imageList = $fileListObj->getPage();
?>

    <style type="text/css">
        <?php if($cellPadding): echo "#{$selectorID} .grid-item {padding:{$cellPadding}px;}"; endif; ?>
    </style>

    <div id="<?php echo $selectorID; ?>" class="flexryGridWrap">
        <div class="flexryGrid <?php echo "{$meta} columns-{$columns}"; ?>">
            <div class="grid-sizer"></div>
            <?php foreach($imageList AS $flexryFile): /** @var FlexryFile $flexryFile */ ?>
                <div class="grid-item" data-src-full="<?php echo $flexryFile->fullImgSrc(); ?>">
                    <div class="grid-item-inner">
                        <img class="grid-image" src="<?php echo $flexryFile->thumbnailImgSrc(); ?>" alt="<?php echo $flexryFile->getTitle(); ?>" />
                        <div class="meta">
                            <div class="poz">
                                <span class="title"><?php echo $flexryFile->getTitle(); ?></span>
                                <span class="descr"><?php echo $flexryFile->getDescription(); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="loader"><span><?php echo $paginationMethod == 'click' ? 'Load More' : 'Scroll Or Click To Load More'; ?></span></div>
    </div>

<script type="text/javascript">
    (function( _stack ){
        _stack.push(function(){
            $('#<?php echo $selectorID; ?>').flexryGrid({
                blockID            : '<?php echo $this->controller->bID; ?>',
                transitionDuration : '<?php echo $animationTime; ?>s',
                flexryToolsPath    : '<?php echo FLEXRY_TOOLS_URL; ?>',
                paginationMethod   : '<?php echo $paginationMethod; ?>'
            });
            <?php echo $lightboxHelper->bindTo("#{$selectorID}")->itemTargets('.grid-item')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
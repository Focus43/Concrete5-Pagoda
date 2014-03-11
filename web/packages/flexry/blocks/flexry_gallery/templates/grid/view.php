<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var BlockTemplateHelper $templateHelper */
/** @var FlexryFileList $fileListObj */

$selectorID       = sprintf('flexryGrid-%s', $this->controller->bID);
$cellPadding      = ((int) $templateHelper->value('cellPadding') >= 1) ? (int) $templateHelper->value('cellPadding') : false;
$itemsPerPage     = ((int)$templateHelper->value('itemsPerPage')) ? (int)$templateHelper->value('itemsPerPage') : 10;
$fileListObj->setItemsPerPage($itemsPerPage);
$imageList        = $fileListObj->getPage();

$settingsData = (object) array(
    'blockID'            => (int) $this->controller->bID,
    'transitionDuration' => sprintf('%ss', $templateHelper->value('animationTime')),
    'flexryToolsPath'    => (string) FLEXRY_TOOLS_URL,
    'paginationMethod'   => (string) $templateHelper->value('paginationMethod')
);
?>

    <style type="text/css">
        <?php if($cellPadding): echo "#{$selectorID} .grid-item {padding:{$cellPadding}px;}"; endif; ?>
    </style>

    <div id="<?php echo $selectorID; ?>" class="flexryGridWrap">
        <div class="<?php echo join(' ', array('flexryGrid', $templateHelper->value('meta'), "columns-{$templateHelper->value('columns')}")); ?>">
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
                        <input type="hidden" data-title="Something!" data-descr="else goes here!" value="this be it" />
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if( !(count($imageList) < $itemsPerPage) ): ?>
        <div class="loader"><span><?php echo $settingsData->paginationMethod === 'click' ? 'Load More' : 'Scroll Or Click To Load More'; ?></span></div>
        <?php endif; ?>
    </div>

<script type="text/javascript">
    (function( _stack ){
        _stack.push(function(){
            $('#<?php echo $selectorID; ?>').flexryGrid(<?php echo Loader::helper('json')->encode($settingsData); ?>);
            <?php echo $lightboxHelper->bindTo($selectorID)->itemTargets('.grid-item')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
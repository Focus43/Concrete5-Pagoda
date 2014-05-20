<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var BlockTemplateHelper $templateHelper */
/** @var FlexryFileList $fileListObj */

$selectorID = sprintf('flexryOwl-%s', $this->controller->bID);
$imageList  = $fileListObj->get();

$settingsData = (object) array(
    'singleItem'        => (bool)($templateHelper->value('singleItem') === 'true'),
    'items'             => (int)$templateHelper->value('items'),
    'navigation'        => (bool)($templateHelper->value('navigation') === 'true'),
    'pagination'        => (bool)($templateHelper->value('pagination') === 'true'),
    'scrollPerPage'     => (bool)($templateHelper->value('scrollPerPage') === 'true'),
    'slideSpeed'        => (int)((float)$templateHelper->value('slideSpeed')*1000),
    'paginationSpeed'   => (int)((float)$templateHelper->value('slideSpeed')*1000),
    'autoPlay'          => (bool)($templateHelper->value('autoPlay') === 'true'),
    'stopOnHover'       => (bool)($templateHelper->value('stopOnHover') === 'true')
);
?>

<div class="flexryOwl meta-hover">
    <div id="<?php echo $selectorID; ?>" class="owl-carousel">
        <?php foreach($imageList AS $flexryFile): /** @var FlexryFile $flexryFile */ ?>
            <div class="flexry-owl-item" data-src-full="<?php echo $flexryFile->fullImgSrc(); ?>">
                <div class="flexry-owl-item-inner">
                    <img class="slider-image" src="<?php echo $flexryFile->thumbnailImgSrc(); ?>" />
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
</div>

<script type="text/javascript">
    (function( _stack ){
        _stack.push(function(){
            $('#<?php echo $selectorID; ?>').flexryOwl(<?php echo Loader::helper('json')->encode($settingsData); ?>);
            <?php echo $lightboxHelper->bindTo($selectorID)->itemTargets('.flexry-owl-item')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
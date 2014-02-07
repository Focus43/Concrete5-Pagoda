<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var BlockTemplateHelper $templateHelper */
/** @var FlexryFileList $fileListObj */
$imageList = $fileListObj->get();
?>

<?php if( Page::getCurrentPage()->isEditMode() ): ?>

    <div style="padding:15px;background:#112233;color:#fff;text-align:center;">
        Content disabled in edit mode.
    </div>

<?php else: ?>

    <div id="flexrySlider-<?php echo $this->controller->bID; ?>" class="owl-carousel">
        <?php foreach($imageList AS $flexryFile): /** @var FlexryFile $flexryFile */ ?>
            <a class="flexryItem" title="<?php echo $flexryFile->getTitle(); ?>" href="<?php echo $flexryFile->fullImgSrc(); ?>">
                <span><?php echo $flexryFile->getTitle(); ?></span>
                <img src="<?php echo $flexryFile->thumbnailImgSrc(); ?>" alt="<?php echo $flexryFile->getTitle(); ?>" />
                <p data-descr><?php echo $flexryFile->getDescription(); ?></p>
            </a>
        <?php endforeach; ?>
    </div>

    <script type="text/javascript">
        $(function(){
            var $slider = $('#flexrySlider-<?php echo $this->controller->bID; ?>');

            // carousel init
            $slider.owlCarousel({
                theme: 'flexry-theme',
                autoPlay: <?php echo ((bool) $templateHelper->value('autoplay')) ? 'true' : 'false'; ?>,
                items: <?php echo ((int)$templateHelper->value('items') >= 1) ? (int) $templateHelper->value('items') : '3'; ?>,
                navigation: <?php echo ((bool) $templateHelper->value('showNavigation')) ? 'true' : 'false'; ?>,
                pagination: <?php echo ((bool) $templateHelper->value('showPagination')) ? 'true' : 'false'; ?>,
                lazyLoad: true
            });

            // lightboxes
            $('.flexryItem', $slider).swipebox({hideBarsDelay:0});
        });
    </script>

<?php endif; ?>

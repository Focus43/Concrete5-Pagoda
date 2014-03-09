<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var BlockTemplateHelper $templateHelper */
// templace-specific settings
$selectorID = t('flexryOwl-%s', $this->controller->bID);
/** @var FlexryFileList $fileListObj */
$imageList = $fileListObj->get();

$jsonSettings = array(
    /*'fx'            => (string)$templateHelper->value('fx'),
    'easing'        => (string)$templateHelper->value('easing'),
    'autoAdvance'   => (bool)($templateHelper->value('autoAdvance') === 'true'),
    'playPause'     => (bool)($templateHelper->value('playPause') === 'true'),
    'hover'         => (bool)($templateHelper->value('hover') === 'true'),
    'pauseOnClick'  => (bool)($templateHelper->value('pauseOnClick') === 'true'),
    'thumbnails'    => (bool)($templateHelper->value('thumbnails') === 'true'),
    'pagination'    => (bool)($templateHelper->value('pagination') === 'true'),
    'time'          => (int)((float)$templateHelper->value('time')*1000),
    'transPeriod'   => (int)((float)$templateHelper->value('transPeriod')*1000),
    'alignment'     => (string)$templateHelper->value('alignment'),
    'portrait'      => (bool)($templateHelper->value('portrait') === 'true'),
    'loader'        => (string)$templateHelper->value('loader'),
    'loaderColor'   => "#{$templateHelper->value('loaderColor')}",
    'loaderBgColor' => "#{$templateHelper->value('loaderBgColor')}",
    'loaderOpacity' => (float)$templateHelper->value('loaderOpacity'),
    'loaderPadding' => (int)$templateHelper->value('loaderPadding'),
    'loaderStroke'  => (int)$templateHelper->value('loaderStroke'),
    'pieDiameter'   => (int)$templateHelper->value('pieDiameter'),
    'piePosition'   => (string)$templateHelper->value('piePosition'),
    'barDirection'  => (string)$templateHelper->value('barDirection'),
    'barPosition'   => (string)$templateHelper->value('barPosition'),
    'imagePath'     => FLEXRY_IMAGE_PATH . 'camera/'*/
);

//if((int)$templateHelper->value('height') >= 1):
//    $jsonSettings['height'] = (int)$templateHelper->value('height') . 'px';
//endif;
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
            $('#<?php echo $selectorID; ?>').flexryOwl(<?php echo Loader::helper('json')->encode((object)$jsonSettings); ?>);
            <?php echo $lightboxHelper->bindTo($selectorID)->itemTargets('.flexry-owl-item')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
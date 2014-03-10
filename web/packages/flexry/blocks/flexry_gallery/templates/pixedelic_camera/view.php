<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var BlockTemplateHelper $templateHelper */
/** @var FlexryFileList $fileListObj */

$selectorID = sprintf('flexryCamera-%s', $this->controller->bID);
$imageList  = $fileListObj->get();

$settingsData = (object) array(
    'fx'            => (string)$templateHelper->value('fx'),
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
    'imagePath'     => FLEXRY_IMAGE_PATH . 'camera/'
);

if((int)$templateHelper->value('height') >= 1):
    $settingsData->height = (int)$templateHelper->value('height') . 'px';
endif;
?>

<div class="flexryCamera">
    <div id="<?php echo $selectorID; ?>" class="camera_wrap">
        <?php foreach($imageList AS $flexryFile): /** @var FlexryFile $flexryFile */ ?>
            <div class="flexry-camera-item" data-src="<?php echo $flexryFile->fullImgSrc(); ?>" data-thumb="<?php echo $flexryFile->thumbnailImgSrc(); ?>">
                <?php $descr = $flexryFile->getDescription(); if( !empty($descr) ): ?>
                    <div class="camera_caption"><?php echo $descr; ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">
    (function( _stack ){
        _stack.push(function(){
            $('#<?php echo $selectorID; ?>').flexryCamera(<?php echo Loader::helper('json')->encode($settingsData); ?>);
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
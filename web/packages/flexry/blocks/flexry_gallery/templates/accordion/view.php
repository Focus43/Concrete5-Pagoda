<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var BlockTemplateHelper $templateHelper */
/** @var FlexryFileList $fileListObj */

$selectorID  = sprintf('flexryAccordion-%s', $this->controller->bID);
$imageList   = $fileListObj->get();
$imageCount  = count($imageList);

$settingsData    = (object) array(
    'trigger'           => (string)$templateHelper->value('trigger'),
    'transitionSpeed'   => (int)((float)$templateHelper->value('transitionSpeed')*1000),
    'autoPlay'          => (bool)($templateHelper->value('autoPlay') === 'true'),
    'autoPlaySpeed'     => (int)((float)$templateHelper->value('autoPlaySpeed')*1000),
    'pauseMouseOver'    => (bool)($templateHelper->value('pauseMouseOver') === 'true')
);
?>

    <div id="<?php echo $selectorID; ?>" class="flexryAccordion">
        <div class="accordion-wrap">
            <?php foreach($imageList AS $flexryFile): /** @var FlexryFile $flexryFile */ ?>
                <div class="accordion-item" data-src-full="<?php echo $flexryFile->fullImgSrc(); ?>" style="width:<?php echo 100/$imageCount; ?>%">
                    <div class="accordion-item-inner">
                        <img class="accordion-image" src="<?php echo $flexryFile->thumbnailImgSrc(); ?>" alt="<?php echo $flexryFile->getTitle(); ?>" />
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
            $('#<?php echo $selectorID; ?>').flexryAccordion(<?php echo Loader::helper('json')->encode($settingsData); ?>);
            <?php echo $lightboxHelper->bindTo($selectorID)->itemTargets('.accordion-item')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
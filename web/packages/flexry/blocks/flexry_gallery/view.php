<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var BlockTemplateHelper $templateHelper */
/** @var FlexryFileList $fileListObj */

$selectorID = sprintf('flexryDefault-%s', $this->controller->bID);
$imageList  = $fileListObj->get();

$titleDisplay       = ((string)$templateHelper->value('titleDisplay') != '') ? $templateHelper->value('titleDisplay') : 'hidden';
$descriptionDisplay = ((string)$templateHelper->value('descriptionDisplay') != '') ? $templateHelper->value('descriptionDisplay') : 'hidden';
$padding            = (is_numeric($templateHelper->value('padding'))) ? (int)$templateHelper->value('padding') : 3;
$margin             = (is_numeric($templateHelper->value('margin'))) ? (int)$templateHelper->value('margin') : 5;
$shadow             = ((string)$templateHelper->value('shadow') != '') ? $templateHelper->value('shadow') : 'none';
?>

    <style type="text/css">
        #<?php echo $selectorID; ?> .flexry-default-row {margin:<?php echo (int)$margin; ?>px 0;}
        #<?php echo $selectorID; ?> .flexry-default-item {padding:<?php echo (int)$padding; ?>px;}
        #<?php echo $selectorID; ?> .flexry-default-item span.title {margin-bottom:<?php echo (int)$padding; ?>px;}
        #<?php echo $selectorID; ?> .flexry-default-item span.descr {margin-top:<?php echo (int)$padding; ?>px;}
    </style>

    <div id="<?php echo $selectorID; ?>" class="flexry-default<?php if($shadow === 'yes'){echo ' shadows';} ?>">
    <?php foreach($imageList AS $flexryFile): /** @var FlexryFile $flexryFile */ ?>
        <div class="flexry-default-row">
            <div class="flexry-default-item" data-src-full="<?php echo $flexryFile->fullImgSrc(); ?>" style="max-width:<?php echo ($flexryFile->flexryThumbnailObj()->width + ($padding*2)); ?>px;">
                <span class="title <?php echo $titleDisplay; ?>"><?php echo $flexryFile->getTitle(); ?></span>
                <img src="<?php echo $flexryFile->thumbnailImgSrc(); ?>" alt="<?php echo $flexryFile->getTitle(); ?>" />
                <span class="descr <?php echo $descriptionDisplay; ?>"><?php echo $flexryFile->getDescription(); ?></span>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

<script type="text/javascript">
    (function( _stack ){
        _stack.push(function(){
            <?php echo $lightboxHelper->bindTo($selectorID)->itemTargets('.flexry-default-item')->initOutput(); ?>
        });
        window._flexry = _stack;
    }( window._flexry || [] ));
</script>
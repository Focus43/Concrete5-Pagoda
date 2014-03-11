<?php
$templateHelper; /** @var BlockTemplateHelper $templateHelper */
$formHelper = Loader::helper('form'); /** @var FormHelper $formHelper */

/**
 * The following options match a subset of the available API as documented here:
 * http://www.pixedelic.com/plugins/camera/. Things excluded are mobile options
 * mostly, and stuff to make configuration not *too* complicated.
 */
$alignment = array(
    'topLeft'       => 'Top Left',
    'topCenter'     => 'Top Center',
    'topRight'      => 'Top Right',
    'centerLeft'    => 'Center Left',
    'center'        => 'Center',
    'centerRight'   => 'Center Right',
    'bottomLeft'    => 'Bottom Left',
    'bottomCenter'  => 'Bottom Center',
    'bottomRight'   => 'Bottom Right'
);
$portrait = array(
    'true'  => 'True',
    'false' => 'False'
);
$autoAdvance = array(
    'true'  => 'True',
    'false' => 'False'
);
$playPause = array(
    'true'  => 'True',
    'false' => 'False'
);
$hover = array(
    'true'  => 'True',
    'false' => 'False'
);
$pauseOnClick = array(
    'true'  => 'True',
    'false' => 'False'
);
$easing = array(
    'linear'            => 'Linear',
    'swing'             => 'Swing',
    'easeInQuad'        => 'Ease In Quad',
    'easeOutQuad'       => 'Ease Out Quad',
    'easeInOutQuad'     => 'Ease In/Out Quad',
    'easeInCubic'       => 'Ease In Cubic',
    'easeOutCubic'      => 'Ease Out Cubic',
    'easeInOutCubic'    => 'Ease In/Out Cubic',
    'easeInQuart'       => 'Ease In Quart',
    'easeOutQuart'      => 'Ease Out Quart',
    'easeInOutQuart'    => 'Ease In/Out Quart',
    'easeInQuint'       => 'Ease In Quint',
    'easeOutQuint'      => 'Ease Out Quint',
    'easeInOutQuint'    => 'Ease In/Out Quint',
    'easeInExpo'        => 'Ease In Expo',
    'easeOutExpo'       => 'Ease Out Expo',
    'easeInOutExpo'     => 'Ease In/Out Expo',
    'easeInSine'        => 'Ease In Sine',
    'easeOutSine'       => 'Ease Out Sine',
    'easeInOutSine'     => 'Ease In/Out Sine',
    'easeInCirc'        => 'Ease In Circ',
    'easeOutCirc'       => 'Ease Out Circ',
    'easeInOutCirc'     => 'Ease In/Out Circ',
    'easeInElastic'     => 'Ease In Elastic',
    'easeOutElastic'    => 'Ease Out Elastic',
    'easeInOutElastic'  => 'Ease In/Out Elastic',
    'easeInBack'        => 'Ease In Back',
    'easeOutBack'       => 'Ease Out Back',
    'easeInOutBack'     => 'Ease In/Out Back',
    'easeInBounce'      => 'Ease In Bounce',
    'easeOutBounce'     => 'Ease Out Bounce',
    'easeInOutBounce'   => 'Ease In/Out Bounce'
);
$fx = array(
    'random'                    => 'Random',
    'simpleFade'                => 'Simple Fade',
    'curtainTopLeft'            => 'Curtain Top Left',
    'curtainTopRight'           => 'Curtain Top Right',
    'curtainBottomLeft'         => 'Curtain Bottom Left',
    'curtainBottomRight'        => 'Curtain Bottom Right',
    'curtainSliceLeft'          => 'Curtain Slice Left',
    'curtainSliceRight'         => 'Curtain Slice Right',
    'blindCurtainTopLeft'       => 'Blind Curtain Top Left',
    'blindCurtainTopRight'      => 'Blind Curtain Top Right',
    'blindCurtainBottomLeft'    => 'Blind Curtain Bottom Left',
    'blindCurtainBottomRight'   => 'Blind Curtain Bottom Right',
    'blindCurtainSliceBottom'   => 'Blind Curtain Slice Bottom',
    'blindCurtainSliceTop'      => 'Blind Curtain Slice Top',
    'stampede'                  => 'Stampede',
    'mosaic'                    => 'Mosaic',
    'mosaicReverse'             => 'Mosaic Reverse',
    'mosaicRandom'              => 'Mosaic Random',
    'mosaicSpiral'              => 'Mosaic Spiral',
    'mosaicSpiralReverse'       => 'Mosaic Spiral Reverse',
    'topLeftBottomRight'        => 'Top Left Bottom Right',
    'bottomRightTopLeft'        => 'Bottom Right Top Left',
    'bottomLeftTopRight'        => 'Bottom Left Top Right',
    'topRightBottomLeft'        => 'Top Right Bottom Left',
    'scrollLeft'                => 'Scroll Left',
    'scrollRight'               => 'Scroll Right',
    'scrollTop'                 => 'Scroll Top',
    'scrollBottom'              => 'Scroll Bottom',
    'scrollHorz'                => 'Scroll Horizontal'
);
$loader = array(
    'pie'  => 'Pie',
    'bar'  => 'Bar',
    'none' => 'None'
);
$loaderColor = 'eeeeee';
$loaderBgColor = '222222';
$loaderOpacity = array_combine(range(.1,1,.1), range(.1,1,.1));
$loaderPadding = array_combine(range(0,8), range(0,8));
$loaderStroke  = array_combine(range(0,10), range(0,10));
$pieDiameter = 38;
$piePosition = array(
    'rightTop'      => 'Top Right',
    'leftTop'       => 'Top Left',
    'leftBottom'    => 'Bottom Left',
    'rightBottom'   => 'Bottom Right'
);
$barPosition = array(
    'top'    => 'Top',
    'right'  => 'Right',
    'bottom' => 'Bottom',
    'left'   => 'Left'
);
$barDirection = array(
    'leftToRight' => 'Left To Right',
    'rightToLeft' => 'Right To Left',
    'topToBottom' => 'Top To Bottom',
    'bottomToTop' => 'Bottom To Top'
);
$navigation = array(
    'true'  => 'True',
    'false' => 'False'
);
$navigationHover = array(
    'true'  => 'True',
    'false' => 'False'
);
$overlayer = array(
    'true'  => 'True',
    'false' => 'False'
);
$pagination = array(
    'true'  => 'True',
    'false' => 'False'
);
$thumbnails = array(
    'true'  => 'True',
    'false' => 'False'
);
$time = array_combine(range(1,12,.5), range(1,12,.5));
$transPeriod = array_combine(range(.25,2,.25), range(.25,2,.25));

?>

<?php Loader::packageElement('alert_crop_fit', 'flexry'); ?>

<p><strong>Note:</strong> Lightboxes are not supported by this template, even if enabled in the <i>Settings</i> tab.</p>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="4">Basic Configuration</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Auto-advance</td>
            <td>Show Play/Pause</td>
            <td>Pause On Hover</td>
            <td>Pause On Click</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('autoAdvance'), $autoAdvance, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('autoAdvance'), 'true')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('playPause'), $playPause, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('playPause'), 'true')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('hover'), $hover, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('hover'), 'true')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('pauseOnClick'), $pauseOnClick, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('pauseOnClick'), 'false')); ?></td>
        </tr>
        <tr>
            <td>Photo Alignment</td>
            <td>Navigation Buttons</td>
            <td>Navigation On Hover</td>
            <td>Allow Portrait</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('alignment'), $alignment, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('alignment'), 'center')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('navigation'), $navigation, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('navigation'), 'true')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('navigationHover'), $navigationHover, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('navigationHover'), 'true')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('portrait'), $portrait, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('portrait'), 'false')); ?></td>
        </tr>
        <tr>
            <td>Show Thumbnails</td>
            <td>Pagination</td>
            <td colspan="2">Force Height (px) <span class="muted">optional</span></td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('thumbnails'), $thumbnails, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('thumbnails'), 'true')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('pagination'), $pagination, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('pagination'), 'true')); ?></td>
            <td colspan="2"><?php echo $formHelper->text($templateHelper->field('height'), FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('height'), ''), array('placeholder' => 'in pixels')); ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="4">Timing &amp; Animation</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Time</td>
            <td>Transition</td>
            <td>Effect</td>
            <td>Easing</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('time'), $time, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('time'), '4')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('transPeriod'), $transPeriod, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('transPeriod'), '0.5')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('fx'), $fx, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('fx'), 'random'), array('style' => 'width:160px;')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('easing'), $easing, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('easing'), 'easeInOutExpo')); ?></td>
        </tr>
    </tbody>
</table>

<table id="tblPixedelicLoader" class="table table-bordered">
    <thead>
        <tr>
            <th colspan="6">Loader (ie. "Count down until next image is shown")</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Shape</td>
            <td>Color</td>
            <td>Background</td>
            <td>Opacity</td>
            <td>Padding (px)</td>
            <td>Stroke (px)</td>
        </tr>
        <tr>
            <td><?php echo $formHelper->select($templateHelper->field('loader'), $loader, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('loader'), 'pie'), array('class' => 'shape-select')); ?></td>
            <td><?php echo $formHelper->text($templateHelper->field('loaderColor'), FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('loaderColor'), $loaderColor), array('class' => 'color-choose', 'placeholder' => 'eeeeee')); ?></td>
            <td><?php echo $formHelper->text($templateHelper->field('loaderBgColor'), FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('loaderBgColor'), $loaderBgColor), array('class' => 'color-choose', 'placeholder' => '222222')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('loaderOpacity'), $loaderOpacity, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('loaderOpacity'), '0.8')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('loaderPadding'), $loaderPadding, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('loaderPadding'), '0')); ?></td>
            <td><?php echo $formHelper->select($templateHelper->field('loaderStroke'), $loaderStroke, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('loaderStroke'), '7')); ?></td>
        </tr>
    </tbody>
    <tbody data-shape="pie" style="display:none;">
        <tr>
            <td colspan="2">Position</td>
            <td colspan="4">Diameter (px)</td>
        </tr>
        <tr>
            <td colspan="2"><?php echo $formHelper->select($templateHelper->field('piePosition'), $piePosition, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('piePosition'), 'rightTop')); ?></td>
            <td colspan="4"><?php echo $formHelper->text($templateHelper->field('pieDiameter'), FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('pieDiameter'), $pieDiameter), array('placeholder' => $pieDiameter)); ?></td>
        </tr>
    </tbody>
    <tbody data-shape="bar" style="display:none;">
        <tr>
            <td colspan="2">Position</td>
            <td colspan="4">Direction</td>
        </tr>
        <tr>
            <td colspan="2"><?php echo $formHelper->select($templateHelper->field('barPosition'), $barPosition, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('barPosition'), 'top')); ?></td>
            <td colspan="4"><?php echo $formHelper->select($templateHelper->field('barDirection'), $barDirection, FlexryBlockTemplateOptions::valueOrDefault($templateHelper->value('barDirection'), 'leftToRight')); ?></td>
        </tr>
    </tbody>
</table>

<script type="text/javascript">
    $(function(){
        $('.shape-select', '#tblPixedelicLoader').on('change', function(){
            var val = $(this).val();
            $('[data-shape]').hide().filter('[data-shape="'+val+'"]').show();
        }).trigger('change');
    });
</script>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?php Loader::element('header_required'); // REQUIRED BY C5 // ?>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <style type="text/css">
        #padder{padding:30px 0;}
        #padder .row {margin-bottom:30px;}
        #padder .row:last-child {margin-bottom:0;}
    </style>
</head>

<body>

<div id="padder">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 style="text-align:center;text-transform:uppercase;"><?php echo SITE; ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?php $a = new Area('Header'); $a->display($c); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <?php $a = new Area('Main'); $a->display($c); ?>
            </div>
            <div class="col-sm-4">
                <div class="well well-sm">
                    <?php $a = new Area('Sidebar'); $a->display($c); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<?php Loader::element('footer_required'); // REQUIRED BY C5 // ?>
</body>
</html>
<?php defined('C5_EXECUTE') or die("Access Denied.");

    // turn off error reporting
    error_reporting(0);

    /** @var $blockObj FlexryGalleryBlockController */

    try {
        // Get the block obj
        $blockObj = Block::getByID( (int)$_REQUEST['blockID'] );

        if( !($blockObj instanceof Block) ){
            throw new Exception('');
        }

        // Get the instance
        $blockInstance = $blockObj->getInstance();

        // Get the template helper, and find the itemsPerPage value, or default to 10
        $templateHelper = $blockInstance->getTemplateHelper();
        $itemsPerPage   = ((int) $templateHelper->value('itemsPerPage')) ? (int) $templateHelper->value('itemsPerPage') : 10;

        // Set the items per page
        $blockInstance->fileListObj()->setItemsPerPage($itemsPerPage);

        // Get the results for this page offset
        $imageList = $blockInstance->fileListObj()->getPage( (int)$_REQUEST['offset'] );
    }catch(Exception $e){
        header('X-PHP-Response-Code: 406', true, 406);
        die;
    }

    echo Loader::helper('json_format', 'flexry')->output( $imageList );
    exit;
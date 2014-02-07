<?php

    class FlexryTemplateSubController {

        public static function run( FlexryGalleryBlockController $flexryBlockController ){

            if( (bool) $flexryBlockController->getTemplateHelper()->value('enableModals') ){
                $htmlHelper = Loader::helper('html');

                // css (always in header)
                $flexryBlockController->addHeaderItem( $htmlHelper->css('flexry-lightbox.min.css', 'flexry') );

                // javascript (honoring whether to output in header or footer
                if( $flexryBlockController->autoIncludeJsInFooter ){
                    $flexryBlockController->addFooterItem( $htmlHelper->javascript('flexry-lightbox.min.js', 'flexry') );
                }else{
                    $flexryBlockController->addHeaderItem( $htmlHelper->javascript('flexry-lightbox.min.js', 'flexry') );
                }
            }

        }

    }
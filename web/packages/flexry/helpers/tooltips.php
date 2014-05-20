<?php defined('C5_EXECUTE') or die("Access Denied.");

    class TooltipsHelper {

        public function generate( $title = '', $tip = '' ){
            return sprintf('<i class="icon-info-sign show-popover" title="%s" data-content="%s"></i>', $title, $tip);
        }

    }
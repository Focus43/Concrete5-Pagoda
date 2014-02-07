<?php defined('C5_EXECUTE') or die("Access Denied.");

    class JsonFormatHelper {

        public function output( array $fileRecords = array() ){
            return Loader::helper('json')->encode(array_map(function( $flexryFile ){ /** @var FlexryFile $flexryFile */
                return (object) array(
                    'title'     => $flexryFile->getTitle(),
                    'descr'     => $flexryFile->getDescription(),
                    'src_thumb' => $flexryFile->thumbnailImgSrc(),
                    'src_full'  => $flexryFile->fullImgSrc()
                );
            }, $fileRecords));
        }

    }
<?php defined('C5_EXECUTE') or die("Access Denied.");

    /**
     * Class FlexryFile : Extend the base File object so we can add more convenience methods
     * for generating thumbnails and such. Also, remember that the File parent class implements
     * a dynamic __call method, such that methods that don't exist get passed through to the
     * current FileVersion object.
     */
    class FlexryFile extends File {

        /** @var ImageHelper $_imageHelper */
        protected static $_imageHelper;

        /** @var BlockRecord $_blockRecord */
        protected $_blockRecord;

        /**
         * Override the parent getByID method so we can make sure to return a FlexryFile
         * object instead of the default File object.
         * @param int $fID
         * @return FlexryFile
         */
        public function getByID( $fID ){
            $db = Loader::db();
            $f = new FlexryFile; // @note: this is the only change! (see parent method)
            $row = $db->GetRow("SELECT Files.*, FileVersions.fvID
		    FROM Files LEFT JOIN FileVersions on Files.fID = FileVersions.fID and FileVersions.fvIsApproved = 1
		    WHERE Files.fID = ?", array($fID));
            if ($row['fID'] == $fID) {
                $f->setPropertiesFromArray($row);
            } else {
                $f->error = File::F_ERROR_INVALID_FILE;
            }
            return $f;
        }


        /**
         * Pass the block record in (required, and done by the FlexryFileList).
         * @param BlockRecord $record
         * @return void
         */
        public function setFlexryBlockInstance( BlockRecord &$record ){
            $this->_blockRecord = $record;
        }


        /**
         * Get the thumbnail url path (relative).
         * @return string
         */
        public function thumbnailImgSrc(){
            if( $this->_thumbnailImgSrc === null ){
                $this->_thumbnailImgSrc = $this->flexryThumbnailObj()->src;
            }
            return $this->_thumbnailImgSrc;
        }


        /**
         * Get the full image url path (relative). Checks for "use original image" being
         * checked, and if so, skips generating a resized thumbnail and just returns the
         * original image path.
         * @return string
         */
        public function fullImgSrc(){
            if( $this->_fullImgSrc === null ){
                if( $this->flexryFullImageObj() instanceof stdClass ){
                    $this->_fullImgSrc = $this->flexryFullImageObj()->src;
                }else{
                    $this->_fullImgSrc = $this->getRelativePath();
                }
            }
            return $this->_fullImgSrc;
        }


        /**
         * Get the resized image according to the thumbnail settings in the block data.
         * @return stdClass : properties ->src, ->width, ->height
         */
        public function flexryThumbnailObj(){
            if( $this->_flexryThumbnailObj === null ){
                $this->_flexryThumbnailObj = self::imageHelper()->getThumbnail($this, (int) $this->_blockRecord->thumbWidth, (int) $this->_blockRecord->thumbHeight, (bool) $this->_blockRecord->thumbCrop);
            }
            return $this->_flexryThumbnailObj;
        }


        /**
         * Get the resized image according to the full image settings in the block data;
         * OR, if using the original image, will return the path to the original.
         * @return stdClass : properties ->src, ->width, ->height
         */
        public function flexryFullImageObj(){
            if( $this->_flexryFullImageObj === null ){
                if( (bool) $this->_blockRecord->fullUseOriginal ){
                    $this->_flexryFullImageObj = $this;
                }else{
                    $this->_flexryFullImageObj = self::imageHelper()->getThumbnail($this, (int) $this->_blockRecord->fullWidth, (int) $this->_blockRecord->fullHeight, (bool) $this->_blockRecord->fullCrop);
                }
            }
            return $this->_flexryFullImageObj;
        }


        /**
         * Get and memoize the image helper, so only has to load the class once.
         * @return ImageHelper
         */
        protected static function imageHelper(){
            if( self::$_imageHelper === null ){
                self::$_imageHelper = Loader::helper('image');
            }
            return self::$_imageHelper;
        }

    }
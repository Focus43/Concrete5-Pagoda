<?php defined('C5_EXECUTE') or die("Access Denied.");
	
	
	/**
	 * Flexry Gallery block
	 */
	class FlexryGalleryBlockController extends BlockController {

        const TEMPLATE_DEFAULT_HANDLE           = '_default',
              CROP_FALSE                        = 0,
              CROP_TRUE                         = 1,
              FULL_USE_ORIGINAL_FALSE           = 0,
              FULL_USE_ORIGINAL_TRUE            = 1,
              FILE_SOURCE_METHOD_CUSTOM         = 0,
              FILE_SOURCE_METHOD_SETS           = 1,
              JS_IN_FOOTER_FALSE                = 0,
              JS_IN_FOOTER_TRUE                 = 1,
              // LIGHTBOX STUFF
              LIGHTBOX_ENABLE_FALSE             = 0,
              LIGHTBOX_ENABLE_TRUE              = 1,
              LIGHTBOX_CLOSE_CLICK_FALSE        = 0,
              LIGHTBOX_CLOSE_CLICK_TRUE         = 1,
              LIGHTBOX_CAPTIONS_MARKERS_FALSE   = 0,
              LIGHTBOX_CAPTIONS_MARKERS_TRUE    = 1;

        // file source selection method
        public static $fileSourceMethods = array(
            self::FILE_SOURCE_METHOD_CUSTOM => 'Custom Gallery',
            self::FILE_SOURCE_METHOD_SETS   => 'Pull From File Set(s)'
        );

        // lightbox presets / defaults
        public static $lightboxTransitions = array(
            ''                   => 'Fade (Default)',
            'fx-spin'            => 'Spin',
            'fx-fall'            => 'Fall',
            'fx-zoom'            => 'Zoom',
            'fx-flip-vertical'   => 'Flip Vertical',
            'fx-flip-horizontal' => 'Flip Horizontal',
            'fx-slide-in-right'  => 'Slide From Right',
            'fx-slide-in-left'   => 'Slide From Left',
            'fx-side-fall'       => 'Fall From Side',
            'fx-slit'            => 'Slit In',
            'randomize'          => 'Randomize'
        );

        public static $lightboxCloseMethods = array(
            self::LIGHTBOX_CLOSE_CLICK_TRUE  => 'Anywhere',
            self::LIGHTBOX_CLOSE_CLICK_FALSE => 'Close Button'
        );

        public static $lightboxCaptionsAndMarkers = array(
            self::LIGHTBOX_CAPTIONS_MARKERS_TRUE  => 'Show',
            self::LIGHTBOX_CAPTIONS_MARKERS_FALSE => 'Hide'
        );

        public static function lightboxMaskOpacities(){
            return array_combine(range(.05,1,.05), range(5,100,5));
        }

        public static function lightboxAnimationsTiming(){
            return array_combine(range(150, 1000, 50), range(.15, 1, .05));
        }

        public static function templateDefaultPath(){
            return DIR_PACKAGES . '/flexry/' . DIRNAME_BLOCKS . '/flexry_gallery/';
        }


        /**
         * @todo: RESET BLOCK CACHE SETTINGS TO TRUE!
         */
        protected $btTable 									= 'btFlexryGallery';
		protected $btInterfaceWidth 						= '580';
		protected $btInterfaceHeight						= '480';
		protected $btCacheBlockRecord 						= false;
		protected $btCacheBlockOutput 						= false;
		protected $btCacheBlockOutputOnPost 				= false;
		protected $btCacheBlockOutputForRegisteredUsers 	= false;
		protected $btCacheBlockOutputLifetime 				= CACHE_LIFETIME;

        // not persistable, just here for record setting
        protected $btTableGalleryFiles = 'btFlexryGalleryFiles';

        // defaults
        public  $fileSourceMethod   = self::FILE_SOURCE_METHOD_CUSTOM,
                $fileSetIDs         = null,
                $thumbWidth         = 250,
                $thumbHeight        = 250,
                $thumbCrop          = self::CROP_TRUE,
                $fullUseOriginal    = self::FULL_USE_ORIGINAL_TRUE,
                $fullWidth,  // no default
                $fullHeight, // no default
                $fullCrop           = self::CROP_FALSE,
                // lightbox settings (match javascript defaults!)
                $lightboxEnable         = self::LIGHTBOX_ENABLE_TRUE,
                $lbMaskColor            = '2a2a2a',
                $lbMaskOpacity          = .75,
                $lbMaskFadeSpeed        = 250,
                $lbCloseOnClick         = self::LIGHTBOX_CLOSE_CLICK_TRUE,
                $lbTransitionEffect     = 'fx-zoom',
                $lbTransitionDuration   = 200,
                $lbCaptions             = self::LIGHTBOX_CAPTIONS_MARKERS_TRUE,
                $lbGalleryMarkers       = self::LIGHTBOX_CAPTIONS_MARKERS_TRUE;

        // modify the auto-including of the view.js file (even in templates) to output in footer?
        public $autoIncludeJsInFooter = self::JS_IN_FOOTER_FALSE;

        // json'ified string (needs transforming before use, so dont make publicly accessible)
        protected $templateData = null;


        /**
         * @return string
         */
        public function getBlockTypeDescription(){
			return t("Flexible Image Gallery Management");
		}


        /**
         * @return string
         */
        public function getBlockTypeName(){
			return t("Flexry Gallery");
		}


        /**
         * Override the core BlockController method so we can allow outputting the view.js file
         * in the footer (for example, for projects that might include jquery in the footer only).
         * If its not enabled, the default inclusion method will be used.
         */
        public function outputAutoHeaderItems() {
            if( !( (bool) $this->autoIncludeJsInFooter ) ){
                parent::outputAutoHeaderItems();
                return;
            }

            // if we get here, we're outputting the view.js javascript file in the footer
            $bvt = new BlockViewTemplate( $this->getBlockObject() );
            $headers = $bvt->getTemplateHeaderItems();
            if (count($headers) > 0) {
                foreach($headers as $h) {
                    if( $h instanceof JavaScriptOutputObject ){
                        $this->addFooterItem($h);
                    }else{
                        $this->addHeaderItem($h);
                    }
                }
            }
        }


        /**
         * Controller method.
         * @return void
         */
        public function view(){
            $this->set('fileListObj', $this->fileListObj());
            $this->set('templateHelper', $this->getTemplateHelper());
            $this->set('lightboxHelper', $this->getLightboxHelper());
        }


        /**
         * Always output <meta> tag in the header with the flexry /js directory path.
         * @return void
         */
        public function on_page_view(){
            $this->runTemplateSubController();
            // lightbox enabled?
            if( (bool) $this->lightboxEnable ){
                $this->addHeaderItem( $this->getHelper('html')->css('flexry-lightbox.min.css', 'flexry') );
                if( (bool) $this->autoIncludeJsInFooter ){
                    $this->addFooterItem( $this->getHelper('html')->javascript('flexry-lightbox.js', 'flexry') );
                }else{
                    $this->addHeaderItem( $this->getHelper('html')->javascript('flexry-lightbox.js', 'flexry') );
                }
            }
            // output function to execute deferreds
            $this->addHeaderItem( $this->getHelper('html')->javascript('libs/modernizr.js', 'flexry') );
            $this->addFooterItem('<script type="text/javascript">'.$this->getHelper('file')->getContents(DIR_PACKAGES . '/flexry/' . DIRNAME_BLOCKS . '/flexry_gallery/inline_script.js.txt').'</script>');
        }


        /**
         * If the template is a) a directory, and b) has a file named sub_controller.php, then
         * load it and try to pass an instance of $this to the run() method.
         * @return void
         */
        protected function runTemplateSubController(){
            $templates = $this->templateAndDirectoryList();
            if( !empty($templates) ){
                $subControllerPath = t('%s/sub_controller.php', $templates[$this->currentTemplateHandle()]);
                try {
                    if( is_file($subControllerPath) && !class_exists('FlexryTemplateSubController') ){
                        include_once($subControllerPath);
                        if( class_exists('FlexryTemplateSubController') ){
                            FlexryTemplateSubController::run( $this );
                        }
                    }
                }catch(Exception $e){ /* FAIL GRACEFULLY */ }
            }
        }


        /**
         * Controller method (proxies to the edit() method).
         * @return void
         */
        public function add(){
            $this->edit();
        }


        /**
         * Controller method.
         * @return void
         */
        public function edit(){
            $this->set('tooltips', $this->getHelper('tooltips', 'flexry'));
            $this->set('formHelper', $this->getHelper('form'));
            $this->set('imageList', $this->fileListObj()->forceCustomResults()->get());
            $this->set('availableFileSets', $this->availableFileSets());
            $this->set('savedFileSets', $this->savedFileSets());
            $this->set('currentTemplateHandle', $this->currentTemplateHandle());
            $this->set('templateSelectList', $this->templatesSelectList());
            $this->set('templateDirList', $this->templateAndDirectoryList());
            $this->set('templateData', $this->parsedTemplateData());
        }


        protected function getLightboxHelper(){
            return $this->getHelper('flexry_lightbox', 'flexry')->passController( $this );
        }


        /**
         * Prepare the templateHelper for the view.
         * @return FlexryBlockTemplateOptions
         */
        public function getTemplateHelper(){
            if( $this->_flexryTemplateHelper === null ){
                $templateHandle = $this->currentTemplateHandle();
                if( empty($templateHandle) ){
                    $this->_flexryTemplateHelper = FlexryBlockTemplateOptions::setup(self::TEMPLATE_DEFAULT_HANDLE, self::templateDefaultPath(), $this->parsedTemplateData() );
                }else{
                    $templatesList = $this->templateAndDirectoryList();
                    $this->_flexryTemplateHelper = FlexryBlockTemplateOptions::setup($templateHandle, $templatesList[$templateHandle], $this->parsedTemplateData() );
                }
            }
            return $this->_flexryTemplateHelper;
        }


        /**
         * Get the currently selected template for the block. If its a *new* block, it'll
         * just return null.
         * @return string|null
         */
        protected function currentTemplateHandle(){
            if( $this->_currentTemplateHandle === null ){
                $blockObj = $this->getBlockObject();
                $this->_currentTemplateHandle = ( is_object($blockObj) ) ? (string) $blockObj->getBlockFilename() : null;
            }
            return $this->_currentTemplateHandle;
        }


        /**
         * Parse the template data from an abstract JSON structure to something workable data.
         * @return mixed
         */
        protected function parsedTemplateData(){
            if( $this->_parsedTemplateData === null ){
                $this->_parsedTemplateData = $this->getHelper('json')->decode( $this->templateData );
            }
            return $this->_parsedTemplateData;
        }


        /**
         * Get the FileList object (before a query ->get() is executed!), passing the block
         * record to the FlexryFileList to automatically apply filters.
         * @return FlexryFileList
         */
        public function fileListObj(){
            if( $this->_fileListObj === null ){
                $this->_fileListObj = new FlexryFileList( $this->record );
            }
            return $this->_fileListObj;
        }


        /**
         * Get an array of file set IDs.
         * @return array
         */
        protected function savedFileSets(){
            if( $this->_savedFileSets === null ){
                $this->_savedFileSets = (array) $this->getHelper('json')->decode( $this->fileSetIDs );
            }
            return $this->_savedFileSets;
        }


        /**
         * Get an array of existing FileSet objects (all available in the
         * @return array
         */
        protected function availableFileSets(){
            if( $this->_availableFileSets === null ){
                $fileSetListObj = new FileSetList;
                $this->_availableFileSets = $fileSetListObj->get();
            }
            return $this->_availableFileSets;
        }


        /**
         * Get an array ready for passing to formHelper->select, with the key as the template
         * handle and the value as the prettified template name.
         * @return array
         */
        protected function templatesSelectList(){
            $selectList = array('' => 'Default (Vertical List)');
            foreach( $this->templateAndDirectoryList() AS $fileSystemHandle => $path ){
                if( strpos($fileSystemHandle, '.') !== false ){
                    $selectList[ $fileSystemHandle ] = substr($this->getHelper('text')->unhandle($fileSystemHandle), 0, strrpos($fileSystemHandle, '.'));
                }else{
                    $selectList[ $fileSystemHandle ] = $this->getHelper('text')->unhandle($fileSystemHandle);
                }
            }
            return $selectList;
        }


        /**
         * Get an array of the available templates (normally you'd access by clicking the block
         * in edit mode and choose 'Edit Template'). Array uses the file system handle as the key
         * and the path to the template as the value.
         * @see {root}/web/concrete/core/models/block_types.php
         * @see {root}/web/concrete/elements/block_custom_template.php
         * @return array
         */
        protected function templateAndDirectoryList(){
            if( $this->_blockTemplatesList === null ){
                $this->_blockTemplatesList = array();

                // top level templates (in {root}/blocks/flexry_gallery/templates)
                $topLevelBlocksPath = DIR_FILES_BLOCK_TYPES . "/{$this->btHandle}/" . DIRNAME_BLOCK_TEMPLATES;
                if( file_exists($topLevelBlocksPath) ){
                    $topLevelFileHandles = $this->getHelper('file')->getDirectoryContents($topLevelBlocksPath);
                    foreach($topLevelFileHandles AS $dirItem){
                        $this->_blockTemplatesList[ $dirItem ] = $topLevelBlocksPath . '/' . $dirItem;
                    }
                }

                // templates in packages (in {root}/packages/{package_name}/blocks/flexry_gallery/templates)
                $packageList = PackageList::get()->getPackages();
                foreach( $packageList AS $pkgObj ){
                    $pkgPath        = (is_dir(DIR_PACKAGES . '/' . $pkgObj->getPackageHandle())) ? DIR_PACKAGES . '/'. $pkgObj->getPackageHandle() : DIR_PACKAGES_CORE . '/'. $pkgObj->getPackageHandle();
                    $pkgBlocksPath  = $pkgPath . '/' . DIRNAME_BLOCKS . '/' . $this->btHandle . '/' . DIRNAME_BLOCK_TEMPLATES;
                    if( is_dir($pkgBlocksPath) ){
                        $pkgTemplateFileHandles = $this->getHelper('file')->getDirectoryContents($pkgBlocksPath);
                        foreach( $pkgTemplateFileHandles AS $dirItem ){
                            $this->_blockTemplatesList[ $dirItem ] = $pkgBlocksPath . '/' . $dirItem;
                        }
                    }
                }
            }
            return $this->_blockTemplatesList;
        }


        /**
         * Validate the submitted block data.
         * @param $data
         * @return ValidationErrorHelper
         */
        public function validate( $data ){
            // if picking files by hand, make sure at least one exists
            if( ((int)$data['fileSourceMethod'] === self::FILE_SOURCE_METHOD_CUSTOM) && (count( (array)$data['fileIDs'] ) === 0) ){
                $this->getHelper('validation/error')->add('At least one Image must be added.');
            }
            // if using file sets, make sure at least one is selected
            if( ((int)$data['fileSourceMethod'] === self::FILE_SOURCE_METHOD_SETS) && (count( (array)$data['fileSetIDs'] ) === 0) ){
                $this->getHelper('validation/error')->add('At least one File Set must be selected.');
            }
            // thumbnail width
            if( !( (int)$data['thumbWidth'] >= 1) ){ $this->getHelper('validation/error')->add('Thumbnail Width must be >= 1.'); }
            // thumbnail height
            if( !( (int)$data['thumbHeight'] >= 1) ){ $this->getHelper('validation/error')->add('Thumbnail Height must be >= 1.'); }
            // if the full size image is *not* set to use original, then validate the sizes
            if( !( (int)$data['fullUseOriginal'] === self::FULL_USE_ORIGINAL_TRUE ) ){
                // full width
                if( !( (int)$data['fullWidth'] >= 1) ){ $this->getHelper('validation/error')->add('Full Width must be greater than 1.'); }
                // full height
                if( !( (int)$data['fullHeight'] >= 1) ){ $this->getHelper('validation/error')->add('Full Height must be greater than 1.'); }
            }
            // return the validation error object
            return $this->getHelper('validation/error');
        }


        /**
         * Save block data.
         * @param array $data
         */
        public function save( $data ){
            // validate that shit first
            $this->validate( $data );
            // persist in the join table
            $this->persistFiles( (array) $data['fileIDs'] );
            // main block data
            $blockData                          = array();
            $blockData['fileSourceMethod']      = (int) $data['fileSourceMethod'];
            $blockData['fileSetIDs']            = $this->getHelper('json')->encode( (array) $data['fileSetIDs'] );
            $blockData['thumbWidth']            = (int) $data['thumbWidth'];
            $blockData['thumbHeight']           = (int) $data['thumbHeight'];
            $blockData['thumbCrop']             = (int) $data['thumbCrop'];
            $blockData['fullUseOriginal']       = (int) $data['fullUseOriginal'];
            $blockData['fullWidth']             = (int) $data['fullWidth'];
            $blockData['fullHeight']            = (int) $data['fullHeight'];
            $blockData['fullCrop']              = (int) $data['fullCrop'];
            $blockData['autoIncludeJsInFooter'] = (int) $data['autoIncludeJsInFooter'];
            // lightbox stuff
            $blockData['lightboxEnable']        = (int) $data['lightbox']['enable'];
            $blockData['lbMaskColor']           = $data['lightbox']['maskColor'];
            $blockData['lbMaskOpacity']         = $data['lightbox']['maskOpacity'];
            $blockData['lbMaskFadeSpeed']       = $data['lightbox']['maskFadeSpeed'];
            $blockData['lbCloseOnClick']        = $data['lightbox']['closeOnClick'];
            $blockData['lbTransitionEffect']    = $data['lightbox']['transitionEffect'];
            $blockData['lbTransitionDuration']  = $data['lightbox']['transitionDuration'];
            $blockData['lbCaptions']            = $data['lightbox']['captions'];
            $blockData['lbGalleryMarkers']      = $data['lightbox']['galleryMarkers'];
            // transform the template data to a json'ified document (avoid db table creation!)
            $blockData['templateData']          = $this->encodeTemplateData( (array) $data['templateData'] );

            // Now persist everything to the block record
			parent::save( $blockData );

            // Set block template *AFTER* save, in case the recordID changed
            Block::getByID( $this->record->bID )->updateBlockInformation(array(
                'bFilename' => $data['flexryTemplateHandle']
            ));
		}


        /**
         * @param array $data
         * @return string
         */
        protected function encodeTemplateData( array $data = array() ){
            $objectified = array_map(function( $templateDataArray ){
                return (object) $templateDataArray;
            }, $data);

            return $this->getHelper('json')->encode($objectified);
        }


        /**
         * When this gets run, it *always* first deletes any existing records (instead of going
         * through and updating).
         * @param array $fileIDs
         * @return void
         */
        protected function persistFiles( array $fileIDs = array() ){
            $db = Loader::db();
            $db->Execute("DELETE FROM btFlexryGalleryFiles WHERE bID = ?", array( $this->bID ));
            foreach( $fileIDs AS $orderIndex => $fileID ){
                $db->Execute("INSERT INTO btFlexryGalleryFiles (bID, fileID, displayOrder) VALUES(?,?,?)", array(
                    $this->bID, $fileID, ($orderIndex + 1)
                ));
            }
        }


        /**
         * If the delete method ever gets called; overload the parent class delete method
         * so we can clear out the associated tables first.
         * @return mixed
         */
        public function delete(){
            Loader::db()->Execute("DELETE FROM {$this->btTableGalleryFiles} WHERE bID = ?", array(
                $this->bID
            ));
            return parent::delete();
        }


        /**
         * When new page versions get issued, or other triggering events, this method will
         * make sure data in the supplementary btFlexryGalleryFiles table gets updated and
         * carried over for the new block version.
         * @param int $newBID
         * @return BlockRecord|void
         */
        public function duplicate( $newBID ){
            // Duplicate the block using parent method
            $newBlockObj = parent::duplicate($newBID);
            // Have the old and new blockIDs in variables
            $newBlockID  = $newBlockObj->bID;
            $oldBlockID  = $this->bID;
            // *NOW* clone all the records in the btFlexryGalleryFiles table
            Loader::db()->Execute("INSERT INTO {$this->btTableGalleryFiles} (SELECT {$newBlockID} AS bID, fileID, displayOrder FROM {$this->btTableGalleryFiles} WHERE bID = ?)", array(
                $oldBlockID
            ));
        }


        /**
         * "Memoize" helpers so they're only loaded once.
         * @param string $handle Handle of the helper to load
         * @param string $pkg Package to get the helper from
         * @return ...Helper class of some sort
         */
        public function getHelper( $handle, $pkg = false ){
            $helper = '_helper_' . preg_replace("/[^a-zA-Z0-9]+/", "", $handle);
            if( $this->{$helper} === null ){
                $this->{$helper} = Loader::helper($handle, $pkg);
            }
            return $this->{$helper};
        }
		
	}
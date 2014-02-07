<?php

    class DashboardSchedulizerAttributesController extends Controller {

        public $helpers = array('form', 'image', 'concrete/asset_library', 'concrete/interface', 'validation/token');


        public function on_start(){
            $this->set('disableThirdLevelNav', true);

            // PASS THE ATTR CATEGORY TO THE VIEW
            $this->set('attrCategory', AttributeKeyCategory::getByHandle('schedulizer_calendar'));

            // PASS THE LIST OF AVAILABLE ATTR TYPES
            $types = AttributeType::getList('schedulizer_calendar');
            $attrTypes = array();
            foreach($types AS $at){
                $attrTypes[ $at->getAttributeTypeID() ] = $at->getAttributeTypeName();
            }
            $this->set('attrTypesList', $attrTypes);
        }


        private function setKeyAndType( $akID ){
            $ak = SchedulizerCalendarAttributeKey::getByID( (int)$akID );
            if( $ak->getAttributeKeyID() >= 1 ){
                $this->set('attrKey', $ak );
                $this->set('attrType', $ak->getAttributeType() );
            }
        }


        public function edit( $akID ){
            $this->setKeyAndType( $akID );
            $this->set('editable', true);
        }


        /**
         * Display the *FORM* for adding a new attribute
         */
        public function add(){
            $this->set('attrType', AttributeType::getByID($this->request('atID')));
        }


        public function created_success(){
            $this->set('message', t('Attribute created.'));
        }

        /**
         * Actually create the new attribute in the system
         */
        public function create(){
            $type   = AttributeType::getByID( (int)$this->post('atID') );
            $cnt    = $type->getController();
            $e      = $cnt->validateKey( $this->post() );
            if( $e->has() ){
                $this->set('error', $e);

                // cause the form to be rerendered so any prefilled info isnt lost
                $this->set('attrType', AttributeType::getByID($this->request('atID')));
            }else{
                SchedulizerCalendarAttributeKey::add($type, $this->post());
                $this->redirect('/dashboard/schedulizer/attributes', 'created_success');
            }
        }

        public function update_success(){
            $this->set('message', t('Attribute updated.'));
        }

        public function update() {
            $akID = (int) $this->post('akID');

            $key = SchedulizerCalendarAttributeKey::getByID($akID);
            $type = $key->getAttributeType();
            $this->set('key', $key);
            $this->set('type', $type);

            if ($this->isPost()) {
                $cnt = $type->getController();
                $cnt->setAttributeKey($key);
                $e = $cnt->validateKey($this->post());

                if ($e->has()) {
                    $this->set('error', $e);
                } else {
                    $type = AttributeType::getByID($this->post('atID'));
                    $key->update($this->post());
                    $this->redirect('/dashboard/schedulizer/attributes', 'update_success');
                }
            }
        }


        public function deleted_success(){
            $this->set('message', t('Attribute deleted.'));
        }

        public function delete($akID, $token){
            try {
                $ak = SchedulizerCalendarAttributeKey::getByID( (int)$akID );
                if( is_object($ak) ){
                    if( !($ak instanceof SchedulizerCalendarAttributeKey) ){
                        throw new Exception( t('Invalid attribute ID') );
                    }

                    $valt = Loader::helper('validation/token');
                    if( !$valt->validate('delete_attribute', $token) ){
                        throw new Exception( t($valt->getErrorMessage()) );
                    }

                    $ak->delete();
                    $this->redirect('/dashboard/schedulizer/attributes', 'deleted_success');
                }
            } catch(Exception $e) {
                $this->set('error', $e);
            }
        }

    }
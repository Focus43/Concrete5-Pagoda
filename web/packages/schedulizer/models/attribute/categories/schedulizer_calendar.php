<?php

    class SchedulizerCalendarAttributeKey extends AttributeKey {

        public function getIndexedSearchTable() {
            return 'SchedulizerCalendarSearchIndexAttributes';
        }

        protected $searchIndexFieldDefinition = 'calendarID I(11) UNSIGNED NOTNULL DEFAULT 0 PRIMARY';


        public function getAttributes($calendarID, $method = 'getValue') {
            $db = Loader::db();
            $values = $db->GetAll("select avID, akID from SchedulizerCalendarAttributeValues where calendarID = ?", array($calendarID));
            $avl = new AttributeValueList();
            foreach($values as $val) {
                $ak = self::getByID($val['akID']);
                if (is_object($ak)) {
                    $value = $ak->getAttributeValue($val['avID'], $method);
                    $avl->addAttributeValue($ak, $value);
                }
            }
            return $avl;
        }


        public function getAttributeValue($avID, $method = 'getValue') {
            $av = SchedulizerCalendarAttributeValue::getByID($avID);
            if (is_object($av)) {
                $av->setAttributeKey($this);
                return $av->{$method}();
            }
        }


        public static function getByID($akID) {
            $ak = new self();
            $ak->load($akID);

            if ($ak->getAttributeKeyID() > 0) {
                return $ak;
            }
        }


        public static function getByHandle($akHandle) {
            $ak = CacheLocal::getEntry('schedulizer_calendar_key_by_handle', $akHandle);
            if( is_object($ak) ){
                return $ak;
            }elseif( $ak == -1 ){
                return false;
            }

            $ak = -1;
            $q = "SELECT ak.akID
					FROM AttributeKeys ak
					INNER JOIN AttributeKeyCategories akc ON ak.akCategoryID = akc.akCategoryID
					WHERE ak.akHandle = ?
					AND akc.akCategoryHandle = 'schedulizer_calendar'";
            $akID = Loader::db()->GetOne($q, array($akHandle));

            if($akID > 0){
                $ak = self::getByID( $akID );
            }

            CacheLocal::set('schedulizer_calendar_key_by_handle', $akHandle, $ak);
            return $ak;
        }


        public static function getColumnHeaderList() {
            return parent::getList('schedulizer_calendar', array('akIsColumnHeader' => 1));
        }


        public static function getList() {
            return parent::getList('schedulizer_calendar');
        }


        public static function getSearchableList() {
            return parent::getList('schedulizer_calendar', array('akIsSearchable' => 1));
        }


        public static function getSearchableIndexedList() {
            return parent::getList('schedulizer_calendar', array('akIsSearchableIndexed' => 1));
        }


        public static function getImporterList() {
            return parent::getList('schedulizer_calendar', array('akIsAutoCreated' => 1));
        }


        public static function getUserAddedList() {
            return parent::getList('schedulizer_calendar', array('akIsAutoCreated' => 0));
        }


        public function get($akID) {
            return self::getByID($akID);
        }


        protected function saveAttribute(SchedulizerCalendar $calendarObj, $value = false) {
            // We check a cID/cvID/akID combo, and if that particular combination has an attribute value ID that
            // is NOT in use anywhere else on the same cID, cvID, akID combo, we use it (so we reuse IDs)
            // otherwise generate new IDs
            $av = $calendarObj->getAttributeValueObject($this, true);
            parent::saveAttribute($av, $value);
            $db = Loader::db();
            $v = array($calendarObj->getCalendarID(), $this->getAttributeKeyID(), $av->getAttributeValueID());
            $db->Replace('SchedulizerCalendarAttributeValues', array(
                'calendarID' => $calendarObj->getCalendarID(),
                'akID' => $this->getAttributeKeyID(),
                'avID' => $av->getAttributeValueID()
            ), array('calendarID', 'akID'));

            $calendarObj->reindex();
            unset($av);
            unset($calendarObj);
        }


        public function add($at, $args, $pkg = false) {
            CacheLocal::delete('schedulizer_calendar_key_by_handle', $args['akHandle']);
            $ak = parent::add('schedulizer_calendar', $at, $args, $pkg);
            return $ak;
        }


        public function delete() {
            parent::delete();
            $db = Loader::db();
            $r = $db->Execute('select avID from SchedulizerCalendarAttributeValues where akID = ?', array($this->getAttributeKeyID()));
            while ($row = $r->FetchRow()) {
                $db->Execute('delete from AttributeValues where avID = ?', array($row['avID']));
            }
            $db->Execute('delete from SchedulizerCalendarAttributeValues where akID = ?', array($this->getAttributeKeyID()));
        }

    }


    class SchedulizerCalendarAttributeValue extends AttributeValue {

        public function setCalendar( SchedulizerCalendar $calendarObj ) {
            $this->calendarObj = $calendarObj;
        }

        public static function getByID($avID) {
            $lav = new self();
            $lav->load($avID);
            if ($lav->getAttributeValueID() == $avID) {
                return $lav;
            }
        }

        public function delete() {
            $db = Loader::db();
            $db->Execute('delete from SchedulizerCalendarAttributeValues where calendarID = ? and akID = ? and avID = ?', array(
                $this->calendarObj->getCalendarID(),
                $this->attributeKey->getAttributeKeyID(),
                $this->getAttributeValueID()
            ));
            // Before we run delete() on the parent object, we make sure that attribute value isn't being referenced in the table anywhere else
            $num = $db->GetOne('select count(avID) from SchedulizerCalendarAttributeValues where avID = ?', array($this->getAttributeValueID()));
            if ($num < 1) {
                parent::delete();
            }
        }

    }
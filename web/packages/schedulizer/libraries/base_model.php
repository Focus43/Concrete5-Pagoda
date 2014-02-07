<?php

interface IfaceBaseObject {

    const PKG_HANDLE 		= 'schedulizer';
    const STATUS_ACTIVE 	= 1;
    const STATUS_INACTIVE	= 0;
    public function __construct( array $properties = array() );
    public function getDateCreated();
    public function getDateModified();
    public function setPropertiesFromArray( array $properties = array() );
    public function save();
    public function delete();
    public static function getByID( $id );
}


interface IfaceAttributable {
    public function clearAttribute( $ak );
    public function setAttribute( $ak, $value );
    public function reindex();
    public function getAttribute( $ak, $displayMode = false );
    public function getAttributeField( $ak );
    public function getAttributeValueObject( $ak, $createIfNotFound = false );
    public function getAttributeCategoryHandle();
}


/**
 * Base object that should be extended by all models that will be persisted
 * to the database, and that should use Concrete5's attribute model.
 *
 * @property string $tableName  The name of the database table the object persists to
 * @property int	$id		    The id of the object
 * @property mixed	$createdUTC	Timestamp the object was created
 * @property mixed	$modifiedUTC Timestamp the object was last modified
 * @property int	$isActive	Status of the object (active or not active)
 */
abstract class SchedulizerBaseModel implements IfaceBaseObject, IfaceAttributable {

    protected $attrCategoryHandle,
        $tableName,
        $id,
        $createdUTC,
        $modifiedUTC;


    /**
     * @param array $properties Set object property values with key => value array
     */
    public function __construct( array $properties = array() ){
        $this->setPropertiesFromArray($properties);
    }


    /** @return string Date the object was first created */
    public function getDateCreated(){ return $this->createdUTC; }
    /** @return string Date the object was last modified */
    public function getDateModified(){ return $this->modifiedUTC; }


    /**
     * Set properties of the current instance
     * @param array $arr Pass in an array of key => values to set object properties
     * @return void
     */
    public function setPropertiesFromArray( array $properties = array() ) {
        foreach($properties as $key => $prop) {
            $this->{$key} = $prop;
        }
    }


    /**
     * Persist the object to the database (save its properties). This is the ghetto
     * "as close as we can get" version to a simple abstraction layer w/ a few lines
     * of code.
     * @return void
     */
    protected function persistToDatabase(){
        $db     = Loader::db();
        $fields = $this->persistable();

        // if record already exists, do an update
        if( (int) $this->id >= 1 ){
            $sqlString  = 'modifiedUTC = UTC_TIMESTAMP()';
            $values     = array();
            foreach($fields AS $property){
                $sqlString   .= ", {$property} = ?";
                $values[] = $this->{$property};
            }
            $db->Execute("UPDATE {$this->tableName} SET {$sqlString} WHERE id = {$this->id}", $values);

        // create a new record
        }else{
            $sqlString = "createdUTC, modifiedUTC, " . implode(', ', $fields);
            $replacers = implode(',', array_fill(0, count($fields), '?'));
            $values    = array();
            foreach($fields AS $property){
                $values[] = $this->{$property};
            }
            $db->Execute("INSERT INTO {$this->tableName} ({$sqlString}) VALUES (UTC_TIMESTAMP(), UTC_TIMESTAMP(), {$replacers})", $values);
            $this->isNew = true;
            $this->id    = $db->Insert_ID();
        }
    }


    /**
     * Get the entity type category handle (attribute category handle)
     * @return string
     */
    public function getAttributeCategoryHandle(){
        return $this->attrCategoryHandle;
    }


    /**
     * Get the attribute key category object
     * @return AttributeKeyCategory
     */
    public function getAttributeKeyCategoryObj(){
        if( $this->_attrKeyCategoryObj === null ){
            $this->_attrKeyCategoryObj = AttributeKeyCategory::getByHandle( $this->attrCategoryHandle );
        }
        return $this->_attrKeyCategoryObj;
    }


    /**
     * Get the entity type categoryID (attribute category handle)
     * @return int
     */
    public function getAttributeCategoryID(){
        if( $this->_attrCategoryID === null ){
            $this->_attrCategoryID = $this->getAttributeKeyCategoryObj()->getAttributeKeyCategoryID();
        }
        return $this->_attrCategoryID;
    }


    /**
     * @return string Name of the attribute key class
     */
    protected function attributeKeyClass(){
        return get_class( $this ) . "AttributeKey";
    }


    /**
     * @param mixed $ak Pass in either an AttributeKey object, or a string handle,
     * and this will return the AttributeKey object properly
     * @return mixed AttributeKey
     */
    protected function getAttributeKeyObjFromMixed( $ak ){
        if( !is_object($ak) ){
            $klass 	= $this->attributeKeyClass();
            return $klass::getByHandle( $ak );
        }
        return $ak;
    }


    /**
     * @param string $handle Handle of the helper to load
     * @param string $pkg	 Handle of the package containing the helper (optional)
     * @return mixed A helper class
     */
    protected function helper( $handle, $pkg = false ){
        $helper = '_helper_' . preg_replace("/[^a-zA-Z0-9]+/", "", $handle);
        if( $this->{$helper} === null ){
            $this->{$helper} = Loader::helper($handle, $pkg);
        }
        return $this->{$helper};
    }


    /**
     * Clear an attribute that is associated with this object.
     * @param mixed $ak Pass in an AttributeKey object, or a handle
     * @return void
     */
    public function clearAttribute( $ak ){
        $ak = $this->getAttributeKeyObjFromMixed($ak);
        $cav = $this->getAttributeValueObject($ak);
        if( is_object($cav) ){
            $cav->delete();
        }
        $this->reindex();
    }


    /**
     * Set an attribute on an object
     * @param mixed $ak 	Pass in an AttributeKey object, or a handle
     * @param mixed $value	What should be saved against the attribute
     * @return void
     */
    public function setAttribute( $ak, $value ){
        $ak = $this->getAttributeKeyObjFromMixed($ak);
        $ak->setAttribute($this, $value);
        $this->reindex();
    }


    /**
     * Get the value of an attribute by its handle
     * @param mixed $ak	Pass in an AttributeKey object, or a handle
     * @param mixed $displayMode Pass in the display settings
     * @return mixed|void
     */
    public function getAttribute( $ak, $displayMode = false ){
        $ak = $this->getAttributeKeyObjFromMixed($ak);
        if( is_object($ak) ){
            $av = $this->getAttributeValueObject($ak);
            if (is_object($av)) {
                $args = func_get_args();
                if(count($args) > 1){
                    array_shift($args);
                    return call_user_func_array(array($av, 'getValue'), $args);
                }else{
                    return $av->getValue($displayMode);
                }
            }
        }
    }


    /**
     * Pass in an attribute key to render a field
     * @param mixed $ak
     * @return void
     */
    public function getAttributeField( $ak ){
        $ak 	= $this->getAttributeKeyObjFromMixed($ak);
        $value 	= $this->getAttributeValueObject($ak);
        $ak->render('form', $value);
    }


    /**
     * In the implementing classes, we need to have the method getAttributeValueObject
     * setup. So we don't have to write all this shit out every time, we put it in the
     * abstract class here and call it ...Generic, so any class can call parent::...Generic.
     * We add an extra $params value to be passed in so we can dynamically call the required
     * methods and values.
     * @param AttributeKey $ak
     * @param bool $createIfNotFound
     * @param array $params Values to be passed in
     * @return mixed AttributeValue object
     */
    protected function getAttributeValueObjectGeneric( $ak, $createIfNotFound = false, array $params = array() ){
        $av 	= false;
        $avID	= Loader::db()->GetOne("SELECT avID FROM {$params['table']} WHERE {$params['idColumn']} = ? AND akID = ?", array(
            $this->id, $ak->getAttributeKeyID()
        ));

        if( $avID > 0 ){
            $klass 	= $params['attrValClass'];
            $av 	= $klass::getByID( $avID );
            if( is_object($av) ){
                call_user_func_array(array($av, $params['setObjMethod']), array($this));
                $av->setAttributeKey( $ak );
            }
        }

        if( $createIfNotFound ){
            $cnt = 0;
            // Is this avID in use?
            if( is_object($av) ){
                $cnt = Loader::db()->GetOne("SELECT count(avID) FROM {$params['table']} WHERE avID = ?", array(
                    $av->getAttributeValueID()
                ));
            }
            if ( (!is_object($av)) || ($cnt > 1) ) {
                $av = $ak->addAttributeValue();
            }
        }

        return $av;
    }


    /**
     * When we set an attribute, we need to reindex it for searchability. This gets called by
     * the implementing class' reindex() method.
     * @param array $params
     * @return void
     */
    public function reindexGeneric( array $params ){
        $klass 		= $this->attributeKeyClass();
        $attribs	= $klass::getAttributes( $this->id, 'getSearchIndexValue' );
        $db			= Loader::db();
        $db->Execute("DELETE FROM {$params['table']} WHERE {$params['idColumn']} = ?", array( $this->id ));
        $searchableAttrs = array($params['idColumn'] => $this->id);
        $rs			= $db->Execute("SELECT * FROM {$params['table']} WHERE {$params['idColumn']} = -1");
        AttributeKey::reindex($params['table'], $searchableAttrs, $attribs, $rs);
    }

}
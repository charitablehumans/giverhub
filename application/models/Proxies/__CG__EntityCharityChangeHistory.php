<?php

namespace DoctrineProxies\__CG__\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class CharityChangeHistory extends \Entity\CharityChangeHistory implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'id', 'user_id', 'field', 'allowedFields', 'datetime', 'charity_id', 'old_value', 'new_value');
        }

        return array('__isInitialized__', 'id', 'user_id', 'field', 'allowedFields', 'datetime', 'charity_id', 'old_value', 'new_value');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (CharityChangeHistory $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function setCharityId($charity_id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCharityId', array($charity_id));

        return parent::setCharityId($charity_id);
    }

    /**
     * {@inheritDoc}
     */
    public function getCharityId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCharityId', array());

        return parent::getCharityId();
    }

    /**
     * {@inheritDoc}
     */
    public function setCharity(\Entity\Charity $charity)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCharity', array($charity));

        return parent::setCharity($charity);
    }

    /**
     * {@inheritDoc}
     */
    public function getCharity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCharity', array());

        return parent::getCharity();
    }

    /**
     * {@inheritDoc}
     */
    public function setDatetime($datetime)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDatetime', array($datetime));

        return parent::setDatetime($datetime);
    }

    /**
     * {@inheritDoc}
     */
    public function getDatetime()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDatetime', array());

        return parent::getDatetime();
    }

    /**
     * {@inheritDoc}
     */
    public function setDatetimeDt(\DateTime $dt)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDatetimeDt', array($dt));

        return parent::setDatetimeDt($dt);
    }

    /**
     * {@inheritDoc}
     */
    public function getDatetimeDt()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDatetimeDt', array());

        return parent::getDatetimeDt();
    }

    /**
     * {@inheritDoc}
     */
    public function setField($field)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setField', array($field));

        return parent::setField($field);
    }

    /**
     * {@inheritDoc}
     */
    public function getField()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getField', array());

        return parent::getField();
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setNewValue($new_value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNewValue', array($new_value));

        return parent::setNewValue($new_value);
    }

    /**
     * {@inheritDoc}
     */
    public function getNewValue()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNewValue', array());

        return parent::getNewValue();
    }

    /**
     * {@inheritDoc}
     */
    public function setOldValue($old_value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOldValue', array($old_value));

        return parent::setOldValue($old_value);
    }

    /**
     * {@inheritDoc}
     */
    public function getOldValue()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOldValue', array());

        return parent::getOldValue();
    }

    /**
     * {@inheritDoc}
     */
    public function setUserId($user_id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUserId', array($user_id));

        return parent::setUserId($user_id);
    }

    /**
     * {@inheritDoc}
     */
    public function getUserId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUserId', array());

        return parent::getUserId();
    }

    /**
     * {@inheritDoc}
     */
    public function getUser()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUser', array());

        return parent::getUser();
    }

    /**
     * {@inheritDoc}
     */
    public function setUser(\Entity\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUser', array($user));

        return parent::setUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function get_class_without_namespace()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'get_class_without_namespace', array());

        return parent::get_class_without_namespace();
    }

}
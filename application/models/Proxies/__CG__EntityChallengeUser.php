<?php

namespace DoctrineProxies\__CG__\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class ChallengeUser extends \Entity\ChallengeUser implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', 'id', 'challenge', 'user', 'challengeUserVideo', 'status', 'email', 'emailSent', 'statuses');
        }

        return array('__isInitialized__', 'id', 'challenge', 'user', 'challengeUserVideo', 'status', 'email', 'emailSent', 'statuses');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (ChallengeUser $proxy) {
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
    public function setChallenge(\Entity\Challenge $challenge)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setChallenge', array($challenge));

        return parent::setChallenge($challenge);
    }

    /**
     * {@inheritDoc}
     */
    public function getChallenge()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getChallenge', array());

        return parent::getChallenge();
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
    public function getUser()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUser', array());

        return parent::getUser();
    }

    /**
     * {@inheritDoc}
     */
    public function getUserEntity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUserEntity', array());

        return parent::getUserEntity();
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
    public function getImageUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getImageUrl', array());

        return parent::getImageUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus($status)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', array($status));

        return parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', array());

        return parent::getStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function setChallengeUserVideo(\Entity\ChallengeUserVideo $challengeUserVideo = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setChallengeUserVideo', array($challengeUserVideo));

        return parent::setChallengeUserVideo($challengeUserVideo);
    }

    /**
     * {@inheritDoc}
     */
    public function getChallengeUserVideo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getChallengeUserVideo', array());

        return parent::getChallengeUserVideo();
    }

    /**
     * {@inheritDoc}
     */
    public function uploadChallengeUserVideoFromPost()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'uploadChallengeUserVideoFromPost', array());

        return parent::uploadChallengeUserVideoFromPost();
    }

    /**
     * {@inheritDoc}
     */
    public function replaceVideo(\Entity\ChallengeUserVideo $new)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'replaceVideo', array($new));

        return parent::replaceVideo($new);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', array());

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', array($email));

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmailSent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmailSent', array());

        return parent::getEmailSent();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmailSent($emailSent)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmailSent', array($emailSent));

        return parent::setEmailSent($emailSent);
    }

    /**
     * {@inheritDoc}
     */
    public function send()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'send', array());

        return parent::send();
    }

    /**
     * {@inheritDoc}
     */
    public function canBeResent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canBeResent', array());

        return parent::canBeResent();
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
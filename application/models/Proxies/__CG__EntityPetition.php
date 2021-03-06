<?php

namespace DoctrineProxies\__CG__\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Petition extends \Entity\Petition implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', 'id', 'targetText', 'whatText', 'whyText', 'imgUrl', 'videoId', 'status', 'goal', 'createdDate', 'end_at', 'updatedDate', 'urlSlug', 'photo', 'user', 'charity');
        }

        return array('__isInitialized__', 'id', 'targetText', 'whatText', 'whyText', 'imgUrl', 'videoId', 'status', 'goal', 'createdDate', 'end_at', 'updatedDate', 'urlSlug', 'photo', 'user', 'charity');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Petition $proxy) {
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
    public function onPrePersist()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'onPrePersist', array());

        return parent::onPrePersist();
    }

    /**
     * {@inheritDoc}
     */
    public function onPreUpdate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'onPreUpdate', array());

        return parent::onPreUpdate();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedDate($createdDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreatedDate', array($createdDate));

        return parent::setCreatedDate($createdDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedDate', array());

        return parent::getCreatedDate();
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
    public function setImgUrl($imgUrl)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setImgUrl', array($imgUrl));

        return parent::setImgUrl($imgUrl);
    }

    /**
     * {@inheritDoc}
     */
    public function getImgUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getImgUrl', array());

        return parent::getImgUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function setPhoto($photo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPhoto', array($photo));

        return parent::setPhoto($photo);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhoto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhoto', array());

        return parent::getPhoto();
    }

    /**
     * {@inheritDoc}
     */
    public function setTargetText($targetText)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTargetText', array($targetText));

        return parent::setTargetText($targetText);
    }

    /**
     * {@inheritDoc}
     */
    public function getTargetText()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTargetText', array());

        return parent::getTargetText();
    }

    /**
     * {@inheritDoc}
     */
    public function setUpdatedDate($updatedDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUpdatedDate', array($updatedDate));

        return parent::setUpdatedDate($updatedDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdatedDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUpdatedDate', array());

        return parent::getUpdatedDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setUrlSlug($urlSlug)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUrlSlug', array($urlSlug));

        return parent::setUrlSlug($urlSlug);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlSlug()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUrlSlug', array());

        return parent::getUrlSlug();
    }

    /**
     * {@inheritDoc}
     */
    public function setUser($user)
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
    public function setVideoId($videoId)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVideoId', array($videoId));

        return parent::setVideoId($videoId);
    }

    /**
     * {@inheritDoc}
     */
    public function getVideoId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVideoId', array());

        return parent::getVideoId();
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
    public function setGoal($goal)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setGoal', array($goal));

        return parent::setGoal($goal);
    }

    /**
     * {@inheritDoc}
     */
    public function getGoal()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGoal', array());

        return parent::getGoal();
    }

    /**
     * {@inheritDoc}
     */
    public function setWhatText($whatText)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setWhatText', array($whatText));

        return parent::setWhatText($whatText);
    }

    /**
     * {@inheritDoc}
     */
    public function getWhatText()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWhatText', array());

        return parent::getWhatText();
    }

    /**
     * {@inheritDoc}
     */
    public function setWhyText($whyText)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setWhyText', array($whyText));

        return parent::setWhyText($whyText);
    }

    /**
     * {@inheritDoc}
     */
    public function getWhyText()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWhyText', array());

        return parent::getWhyText();
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'jsonSerialize', array());

        return parent::jsonSerialize();
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTitle', array());

        return parent::getTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUrl', array());

        return parent::getUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function getFullUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFullUrl', array());

        return parent::getFullUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function getLink()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLink', array());

        return parent::getLink();
    }

    /**
     * {@inheritDoc}
     */
    public function hasImage()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasImage', array());

        return parent::hasImage();
    }

    /**
     * {@inheritDoc}
     */
    public function hasVideo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasVideo', array());

        return parent::hasVideo();
    }

    /**
     * {@inheritDoc}
     */
    public function getVideoHtml()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVideoHtml', array());

        return parent::getVideoHtml();
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
    public function getImageUrlPrependHttp()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getImageUrlPrependHttp', array());

        return parent::getImageUrlPrependHttp();
    }

    /**
     * {@inheritDoc}
     */
    public function getImageHtml()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getImageHtml', array());

        return parent::getImageHtml();
    }

    /**
     * {@inheritDoc}
     */
    public function setCharity($charity)
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
    public function setEndAt(\DateTime $date)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEndAt', array($date));

        return parent::setEndAt($date);
    }

    /**
     * {@inheritDoc}
     */
    public function getEndAt()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEndAt', array());

        return parent::getEndAt();
    }

    /**
     * {@inheritDoc}
     */
    public function hasEnded()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasEnded', array());

        return parent::hasEnded();
    }

    /**
     * {@inheritDoc}
     */
    public function sign(\Entity\User $user, $hidden = false, $reason = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'sign', array($user, $hidden, $reason));

        return parent::sign($user, $hidden, $reason);
    }

    /**
     * {@inheritDoc}
     */
    public function unsign(\Entity\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'unsign', array($user));

        return parent::unsign($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getGiverhubPetitionSignaturesCount()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGiverhubPetitionSignaturesCount', array());

        return parent::getGiverhubPetitionSignaturesCount();
    }

    /**
     * {@inheritDoc}
     */
    public function getGiverhubPetitionSignatures($page = 1, $limit = 20)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGiverhubPetitionSignatures', array($page, $limit));

        return parent::getGiverhubPetitionSignatures($page, $limit);
    }

    /**
     * {@inheritDoc}
     */
    public function getGiverhubPetitionReasonCount()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGiverhubPetitionReasonCount', array());

        return parent::getGiverhubPetitionReasonCount();
    }

    /**
     * {@inheritDoc}
     */
    public function getGiverhubPetitionReasons($page = 1, $limit = 20)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGiverhubPetitionReasons', array($page, $limit));

        return parent::getGiverhubPetitionReasons($page, $limit);
    }

    /**
     * {@inheritDoc}
     */
    public function getGiverhubPetitionNewsCount()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGiverhubPetitionNewsCount', array());

        return parent::getGiverhubPetitionNewsCount();
    }

    /**
     * {@inheritDoc}
     */
    public function getGiverhubPetitionNews($page = 1, $limit = 20)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGiverhubPetitionNews', array($page, $limit));

        return parent::getGiverhubPetitionNews($page, $limit);
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaType()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMediaType', array());

        return parent::getMediaType();
    }

    /**
     * {@inheritDoc}
     */
    public function getNews($order = array (
  'created_on' => 'desc',
), $limit = 3)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNews', array($order, $limit));

        return parent::getNews($order, $limit);
    }

    /**
     * {@inheritDoc}
     */
    public function hasMedia()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasMedia', array());

        return parent::hasMedia();
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaHtml()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMediaHtml', array());

        return parent::getMediaHtml();
    }

    /**
     * {@inheritDoc}
     */
    public function getOverview()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOverview', array());

        return parent::getOverview();
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

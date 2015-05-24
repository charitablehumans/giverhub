<?php
namespace Entity;

/**
 * ActivityFeedPostComment
 *
 * @Table(name="activity_feed_post_comment", indexes={@Index(name="fk_activity_feed_post_user_idx", columns={"user_id"}), @Index(name="fk_activity_feed_post_comment_post_idx", columns={"post_id"})})
 * @Entity @HasLifecycleCallbacks
 */
class ActivityFeedPostComment extends BaseEntity implements \JsonSerializable {

    static public $entities = [
        'ActivityFeedPost',
        'Challenge',
        'Donation',
        'CharityChangeHistory',
        'CharityFollower',
        'CharityReview',
        'FacebookLike',
        'UserFollower',
        'CharityVolunteeringOpportunity',
        'UserPetitionSignature',
        'ChangeOrgPetitionFacebookShare',
        'PetitionSignature',
        'PetitionFacebookShare',
    ];

    /** @PrePersist */
    public function onPrePersist()
    {
        if (!$this->date) {
            $this->date = new \DateTime();
        }
    }

    public function getHumanizedDateDifference() {
        return \Common::humanizeDateDifference(new \DateTime, $this->getDate());
    }
    public function jsonSerialize() {
        $arr = [
            'id' => $this->id,
            'text' => $this->text,
            'text_nl2br' => nl2br(htmlspecialchars($this->text)),
            'user' => $this->getUser(),
            'humanizedDateDifference' => $this->getHumanizedDateDifference(),
        ];
        return $arr;
    }

    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var boolean
     *
     * @Column(name="is_deleted", type="boolean", nullable=false)
     */
    private $isDeleted = '0';

    /**
     * @var integer
     *
     * @Column(name="entity_id", type="integer", nullable=false)
     */
    private $entity_id;

    /**
     * @var \Entity\User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var string
     *
     * @Column(name="entity_name", type="text", nullable=false)
     */
    private $entity_name;

    /**
     * @var ExternalUrl
     *
     * @ManyToOne(targetEntity="ExternalUrl")
     * @JoinColumns({
     *   @JoinColumn(name="external_url_id", referencedColumnName="id")
     * })
     */
    private $external_url;


    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param \Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $entity_name
     *
     * @throws \Exception
     */
    public function setEntityName($entity_name)
    {
        if (!in_array($entity_name, self::$entities)) {
            throw new \Exception('Invalid entity: ' . $entity_name . ' needs to be one of: ' . join(', ', self::$entities));
        }
        $this->entity_name = $entity_name;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entity_name;
    }

    /**
     * @param BaseEntity $entity
     *
     * @return ActivityFeedPostComment[]
     */
    static public function getComments(BaseEntity $entity) {
        return self::findBy([
                'entity_name' => $entity->get_class_without_namespace(),
                'entity_id' => $entity->getId(),
                'isDeleted' => 0],
            ['id' => 'asc']);
    }

    /**
     * @param BaseEntity $entity
     */
    public function setEntity(BaseEntity $entity) {
        $this->setEntityName($entity->get_class_without_namespace());
        $this->setEntityId($entity->getId());
    }

    /**
     * @return BaseEntity
     * @throws \Exception
     */
    public function getEntity() {
        $class = '\\Entity\\'.$this->entity_name;
        $entity = $class::find($this->entity_id);
        if (!$entity) {
            throw new \Exception('Could not load entity. name: ' . $this->entity_name . ' entity-id: ' . $this->entity_id);
        }
        return $entity;
    }

    /**
     * @return ActivityFeedPostYoutubeVideo[]
     */
    public function getYoutubeVideos() {
        /** @var ActivityFeedPostYoutubeVideo[] $youtube_videos */
        $youtube_videos = ActivityFeedPostYoutubeVideo::findBy(['activity_feed_post_comment_id' => $this->id]);
        return $youtube_videos;
    }


    /**
     * @param int $entity_id
     */
    public function setEntityId($entity_id)
    {
        $this->entity_id = $entity_id;
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->entity_id;
    }

    /**
     * @return ExternalUrl
     */
    public function getExternalUrl()
    {
        return $this->external_url;
    }

    /**
     * @param ExternalUrl $external_url
     */
    public function setExternalUrl(ExternalUrl $external_url = null)
    {
        $this->external_url = $external_url;
    }

}
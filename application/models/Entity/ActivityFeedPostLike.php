<?php
namespace Entity;

/**
 * ActivityFeedPostLike
 *
 * @Table(name="activity_feed_post_like", indexes={@Index(name="fk_activity_feed_post_user_idxx", columns={"user_id"}), @Index(name="idx_comment_entityx", columns={"entity_name", "entity_id"})})
 * @Entity @HasLifecycleCallbacks
 */
class ActivityFeedPostLike extends BaseEntity {

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

    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @Column(name="entity_id", type="integer", nullable=false)
     */
    private $entityId;

    /**
     * @var string
     *
     * @Column(name="entity_name", type="string", nullable=false)
     */
    private $entityName;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


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
        $this->entityName = $entity_name;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
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
        $class = '\\Entity\\'.$this->entityName;
        $entity = $class::find($this->entityId);
        if (!$entity) {
            throw new \Exception('Could not load entity. name: ' . $this->entityName . ' entity-id: ' . $this->entityId);
        }
        return $entity;
    }

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
     * @param int $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @param BaseEntity $entity
     *
     * @return integer
     */
    static public function getLikes(BaseEntity $entity) {
        $em = \Base_Controller::$em;
        $query = $em->createQuery('SELECT COUNT(l.id) FROM Entity\ActivityFeedPostLike l WHERE l.entityName = :entityName AND l.entityId = :entityId');
        $query->setParameter('entityName', $entity->get_class_without_namespace());
        $query->setParameter('entityId', $entity->getId());
        $count = $query->getSingleScalarResult();
        return $count;
    }

    /**
     * @param User       $user
     * @param BaseEntity $entity
     *
     * @return bool
     */
    static public function didUserLikeIt(User $user, BaseEntity $entity) {
        if (!$user) {
            return false;
        }
        $em = \Base_Controller::$em;
        $query = $em->createQuery('SELECT COUNT(l.id) FROM Entity\ActivityFeedPostLike l WHERE l.user = :user AND l.entityName = :entityName AND l.entityId = :entityId');
        $query->setParameter('entityName', $entity->get_class_without_namespace());
        $query->setParameter('entityId', $entity->getId());
        $query->setParameter('user', $user);

        $count = $query->getSingleScalarResult();
        return (bool)$count;
    }
}

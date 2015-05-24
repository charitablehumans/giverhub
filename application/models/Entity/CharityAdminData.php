<?php
namespace Entity;

/**
 * CharityAdminData
 *
 * @Table(name="charity_admin_data", uniqueConstraints={@UniqueConstraint(name="uq_admin_data_charity_field", columns={"charity_id", "field"})}, indexes={@Index(name="fk_charity_admin_data_user_idx", columns={"user_id"}), @Index(name="fk_charity_admin_data_charity_idx", columns={"charity_id"})})
 * @Entity @HasLifecycleCallbacks
 */
class CharityAdminData extends BaseEntity {
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
     * @Column(name="field", type="string", nullable=false)
     */
    private $field;

    /**
     * @var string
     *
     * @Column(name="value", type="text", nullable=false)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @Column(name="date_created", type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @Column(name="date_updated", type="datetime", nullable=false)
     */
    private $dateUpdated;

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
     * @var Charity
     *
     * @ManyToOne(targetEntity="Charity")
     * @JoinColumns({
     *   @JoinColumn(name="charity_id", referencedColumnName="id")
     * })
     */
    private $charity;

    static public $fields = ['tagline', 'mission', 'facebook_page'];

    /** @PrePersist */
    public function onPrePersist()
    {
        if (!$this->dateCreated) {
            $this->dateCreated = new \DateTime();
        }
        if (!$this->dateUpdated) {
            $this->dateUpdated = new \DateTime();
        }
    }

    /** @PreUpdate */
    public function onPreUpdate()
    {
        $this->dateUpdated = new \DateTime();
    }

    /**
     * @param \Entity\Charity $charity
     */
    public function setCharity($charity)
    {
        $this->charity = $charity;
    }

    /**
     * @return \Entity\Charity
     */
    public function getCharity()
    {
        return $this->charity;
    }

    /**
     * @param \DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateUpdated
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @param string $field
     */
    public function setField($field)
    {
        if (!in_array($field, self::$fields)) {
            throw new \Exception('Invalid field: ' . $field);
        }
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
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
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}

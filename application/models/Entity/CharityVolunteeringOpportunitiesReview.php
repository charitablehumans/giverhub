<?php
namespace Entity;

/**
 * CharityVolunteeringOpportunitiesReview
 *
 * @Table(name="charity_volunteering_opportunities_review", indexes={@Index(name="fk_charity_volunteering_opportunities_review_user_idx", columns={"user_id"}), @Index(name="fk_charity_volunteering_opportunities_review_charity_idx", columns={"charity_id"})})
 * @Entity @HasLifecycleCallbacks
 */
class CharityVolunteeringOpportunitiesReview extends BaseEntity {

    /** @PrePersist */
    public function onPrePersist()
    {
        $this->created = new \DateTime();
    }


    /** @PreUpdate */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime();
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
     * @var integer
     *
     * @Column(name="rating", type="integer", nullable=false)
     */
    private $rating;

    static public $valid_ratings = [1,2,3,4,5];

    /**
     * @var string
     *
     * @Column(name="review", type="text", nullable=false)
     */
    private $review;

    /**
     * @var \DateTime
     *
     * @Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

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

    /**
     * @return Charity
     */
    public function getCharity()
    {
        return $this->charity;
    }

    /**
     * @param Charity $charity
     */
    public function setCharity($charity)
    {
        $this->charity = $charity;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param $rating
     *
     * @throws \Exception
     */
    public function setRating($rating)
    {
        if (!in_array($rating, self::$valid_ratings)) {
            throw new \Exception('rating invalid: ' . $rating);
        }
        $this->rating = $rating;
    }

    /**
     * @return string
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param string $review
     */
    public function setReview($review)
    {
        $this->review = $review;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}

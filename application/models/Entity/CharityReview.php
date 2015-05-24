<?php
namespace Entity;

/**
 * CharityReview
 *
 * @Table(name="charity_review")
 * @Entity
 */
class CharityReview extends BaseEntity
{
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
     * @Column(name="charity_id", type="integer", nullable=false)
     */
    private $charity_id;

    /**
     * @var integer
     *
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @var integer
     *
     * @Column(name="rating", type="integer", nullable=false)
     */
    private $rating;

    /**
     * @var string
     *
     * @Column(name="time_created", type="string", nullable=false)
     */
    private $time_created;

    /**
     * @var string
     *
     * @Column(name="review_desc", type="string", nullable=false)
     */
    private $review_desc;

    /**
     * @var integer
     *
     * @Column(name="status", type="integer", nullable=false)
     */
    private $status = 0;

    /**
     * @var integer
     *
     * @Column(name="is_show_review", type="integer", nullable=false)
     */
    private $is_show_review = 0;

	public function getId() {
		return $this->id;
	}

	public function getCharityId() {
		return $this->charity_id;
	}

    public function getUserId() {
        return $this->user_id;
    }

    public function getTimeCreated() {
        return $this->time_created;
    }

    public function getDate() {
        return $this->time_created;
    }

    /**
     * @return User
     */
    public function getUserEntity() {
        $em = \Base_Controller::$em;
        $userRepo = $em->getRepository('\Entity\User');
        /** @var \Entity\User $user */
        $user = $userRepo->find($this->user_id);
        if (!$user) {
            $user = new User;
            $user->setLName('Deleted');
        }
        return $user;
    }

    public function getUser() {
        return $this->getUserEntity();
    }

    public function getCharity() {
        $em = \Base_Controller::$em;
        $charityRepo = $em->getRepository('\Entity\Charity');
        /** @var \Entity\Charity $charity */
        $charity = $charityRepo->find($this->charity_id);
        return $charity;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime() {
        return new \DateTime($this->time_created);
    }

    public function getRating() {
        return $this->rating;
    }

    public function getText() {
        return $this->review_desc;
    }

    public function getReviewDesc() {
        return $this->review_desc;
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return CharityReview[]
     */
    static public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        return \Base_Controller::$em->getRepository('\Entity\CharityReview')->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return CharityReview|null
     */
    static public function findOneBy(array $criteria, array $orderBy = null) {
        return \Base_Controller::$em->getRepository('\Entity\CharityReview')->findOneBy($criteria, $orderBy);
    }

    /**
     * @param int $charity_id
     */
    public function setCharityId($charity_id)
    {
        $this->charity_id = $charity_id;
    }

    /**
     * @param \Entity\Charity $charity
     */
    public function setCharity(Charity $charity)
    {
        $this->charity_id = $charity->getId();
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @param string $review_desc
     */
    public function setReviewDesc($review_desc)
    {
        $this->review_desc = $review_desc;
    }

    /**
     * @param string $time_created
     */
    public function setTimeCreated($time_created)
    {
        $this->time_created = $time_created;
    }

    /**
     * @param \DateTime $time_created
     */
    public function setTimeCreatedDt(\DateTime $time_created)
    {
        $this->time_created = $time_created->format('Y-m-d H:i:s');
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param \Entity\User $user
     */
    public function setUser($user)
    {
        $this->user_id = $user->getId();
    }

    /**
     * @param int $is_show_review
     */
    public function setIsShowReview($is_show_review)
    {
        $this->is_show_review = $is_show_review;
    }

    /**
     * @return int
     */
    public function getIsShowReview()
    {
        return $this->is_show_review;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

}

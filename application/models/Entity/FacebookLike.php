<?php
namespace Entity;

/**
 * FacebookLike
 *
 * @Table(name="facebook_like")
 * @Entity
 */
class FacebookLike extends BaseEntity
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
     * @var string
     *
     * @Column(name="liked_at", type="string", nullable=false)
     */
    private $liked_at;

	public function getId() {
		return $this->id;
	}

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user_id = $user->getId();
    }

    /**
     * @return User
     */
    public function getUser() {
        return User::find($this->user_id);
    }

    /**
     * @param int $charity_id
     */
    public function setCharityId($charity_id)
    {
        $this->charity_id = $charity_id;
    }

    /**
     * @return int
     */
    public function getCharityId()
    {
        return $this->charity_id;
    }

    /**
     * @param Charity $charity
     */
    public function setCharity(Charity $charity) {
        $this->charity_id = $charity->getId();
    }

    /**
     * @return Charity
     */
    public function getCharity() {
        return Charity::find($this->charity_id);
    }

    /**
     * @param string $liked_at
     */
    public function setLikedAt($liked_at)
    {
        $this->liked_at = $liked_at;
    }

    /**
     * @return string
     */
    public function getLikedAt()
    {
        return $this->liked_at;
    }

    /**
     * @param \DateTime $liked_at
     */
    public function setLikedAtDt(\DateTime $liked_at)
    {
        $this->liked_at = $liked_at->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getLikedAtDt()
    {
        return new \DateTime($this->liked_at);
    }
}

<?php
namespace Entity;

/**
 * UserPetitionSignature
 *
 * @Table(name="user_petition_signature")
 * @Entity
 */
class UserPetitionSignature extends BaseEntity {

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
     * @Column(name="petition_id", type="integer", nullable=false)
     */
    private $petition_id;

    /**
     * @var integer
     *
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @var string
     *
     * @Column(name="signed_at", type="string", nullable=false)
     */
    private $signed_at;

    /**
     * @var integer
     *
     * @Column(name="hidden", type="integer", nullable=false)
     */
    private $hidden = 1;


    public function __construct() {
        parent::__construct();

        $dt = new \DateTime();
        $this->signed_at = $dt->format('Y-m-d H:i:s');
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $petition_id
     */
    public function setPetitionId($petition_id)
    {
        $this->petition_id = $petition_id;
    }

    /**
     * @return int
     */
    public function getPetitionId()
    {
        return $this->petition_id;
    }

    /**
     * @param ChangeOrgPetition $petition
     */
    public function setPetition(ChangeOrgPetition $petition)
    {
        $this->petition_id = $petition->getId();
    }

    /**
     * @return ChangeOrgPetition|null
     */
    public function getPetition()
    {
        return ChangeOrgPetition::find($this->petition_id);
    }

    /**
     * @param string $signed_at
     */
    public function setSignedAt($signed_at)
    {
        $this->signed_at = $signed_at;
    }

    /**
     * @return string
     */
    public function getSignedAt()
    {
        return $this->signed_at;
    }

    /**
     * @param \DateTime $signed_at
     */
    public function setSignedAtDt(\DateTime $signed_at)
    {
        $this->signed_at = $signed_at->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getSignedAtDt()
    {
        return new \DateTime($this->signed_at);
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user_id = $user->getId();
    }


    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return User::find($this->user_id);
    }

    /**
     * @return int
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param int $hidden
     */
    public function setHidden( $hidden )
    {
        $this->hidden = $hidden;
    }

    /**
     * @return bool
     */
    public function isHidden() {
        return (bool)$this->hidden;
    }

    public function getFullUrl() {
        return $this->getPetition()->getFullUrl();
    }
}

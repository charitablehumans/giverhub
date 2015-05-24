<?php
namespace Entity;


/**
 * CharityFollower
 *
 * @Table(name="charity_follower")
 * @Entity
 */
class CharityFollower extends BaseEntity
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
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @var integer
     *
     * @Column(name="charity_id", type="integer", nullable=false)
     */
    private $charity_id;

    /**
     * @var string
     *
     * @Column(name="date", type="string", nullable=false)
     */
    private $date;

	public function getId() {
		return $this->id;
	}

	public function getUserId() {
		return $this->user_id;
	}

    public function setUserId($userId) {
        $this->user_id = $userId;
    }

    public function getCharityId() {
        return $this->charity_id;
    }

    public function setCharityId($charityId) {
        $this->charity_id = $charityId;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
    
    /**
     * @return User
     */
    public function getUserEntity() {
        $em = \Base_Controller::$em;
        $userRepo = $em->getRepository('\Entity\User');
        /** @var \Entity\User $user */
        $user = $userRepo->find($this->user_id);
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
        return new \DateTime($this->date);
    }


}

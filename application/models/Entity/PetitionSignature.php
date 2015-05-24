<?php

namespace Entity;


/**
 * PetitionSignature
 *
 * @Table(name="petition_signature")
 * @Entity
 */
class PetitionSignature extends BaseEntity {

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
     * @var string
     *
     * @Column(name="signed_on", type="string", nullable=false)
     */
    private $signed_on;    

	/**
     * @var string
     *
     * @Column(name="reason", type="string", nullable=true)
     */
    private $reason;

	/**
     * @var integer
     *
     * @Column(name="petition_id", type="integer", nullable=false)
     */
    private $petition_id;

	/**
     * @var integer
     * @Column(name="is_hide", type="integer")
     *
     */
    private $is_hide = 0;
    

    /**
     * @return int
     */
    public function getId()
    {
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

	public function setReason($reason)	
	{
		$this->reason = $reason;
	}

	public function getReason()
	{
		return $this->reason;
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
     * @param Petition $petition
     */
    public function setPetition(Petition $petition)
    {
        $this->petition_id = $petition->getId();
    }

    /**
     * @return Petition|null
     */
    public function getPetition()
    {
        return Petition::find($this->petition_id);
    }

	/**
     * @param string $signed_on
     */
    public function setSignedOn($signed_on)
    {
        $this->signed_on = $signed_on;
    }

    /**
     * @param \DateTime $signed_on
     */
    public function setSignedOnDt(\DateTime $signed_on)
    {
        $this->signed_on = $signed_on->format('Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public function getSignedOn()
    {
        return $this->signed_on;
    }

    /**
     * @return \DateTime
     */
    public function getSignedOnDt()
    {
        return new \DateTime($this->signed_on);
    }

	public function setIsHide($hidden)
	{
		$this->is_hide = $hidden;
	}

	public function getIsHide()
	{
		return $this->is_hide;
	}

	/**
     * @return string
     */
    public function getIntervalFromNow() {
        $now = new \DateTime();
        $interval = $now->diff($this->getSignedOnDt());
        $mappings = array(
            'y' => 'years',
            'm' => 'months',
            'd' => 'days',
            'h' => 'hrs',
            'i' => 'mins',
            's' => 'secs',
        );
        foreach($mappings as $col => $str) {
            if ($interval->$col) {
                return $interval->$col . ' ' . $str;
            }
        }
        return 'now';
    }
    

    public function isHidden() {
        return $this->is_hide;
    }

    public function getSignedAtDt() {
        return $this->getSignedOnDt();
    }
}

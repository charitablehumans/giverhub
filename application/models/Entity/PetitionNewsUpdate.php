<?php

namespace Entity;


/**
 * PetitionNewsUpdate
 *
 * @Table(name="petition_news_update")
 * @Entity
 */
class PetitionNewsUpdate extends BaseEntity {

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
     * @Column(name="content", type="string", nullable=true)
     */
    private $content;

	/**
     * @var string
     *
     * @Column(name="created_on", type="string", nullable=false)
     */
    private $created_on;	
    

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

	public function setContent($content)	
	{
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;
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
     * @param GiverHubPetition $petition
     */
    public function setPetition(Petition $petition)
    {
        $this->petition_id = $petition->getId();
    }

    /**
     * @return ChangeOrgPetition|null
     */
    public function getPetition()
    {
        return Petition::find($this->petition_id);
    }

	/**
     * @param string $created_on
     */
    public function setCreatedOn($created_on)
    {
        $this->created_on = $created_on;
    }

    /**
     * @param \DateTime $signed_on
     */
    public function setCreatedOnDt(\DateTime $created_on)
    {
        $this->created_on = $created_on->format('Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }


}

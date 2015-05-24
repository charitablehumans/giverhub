<?php
namespace Entity;

/**
 * Mission
 *
 * @Table(name="mission")
 * @Entity @HasLifecycleCallbacks
 */
class Mission extends BaseEntity {

    /** @PrePersist */
    public function onPrePersist()
    {
        $this->updated_time = $this->created_time = date('Y-m-d H:i:s');
    }

    /** @PreUpdate */
    public function onPreUpdate()
    {
        $this->updated_time = date('Y-m-d H:i:s');
    }

    /** @PreRemove */
    public function onPreRemove() {
        $votes = $this->getVotes();
        foreach($votes as $vote) {
            \Base_Controller::$em->remove($vote);
            \Base_Controller::$em->flush();
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
     * @var string
     *
     * @Column(name="mission", type="string", nullable=false)
     */
    private $mission;

    /**
     * @var string
     *
     * @Column(name="source", type="string", nullable=false)
     */
    private $source;

    /**
     * @var string
     *
     * @Column(name="created_time", type="string", nullable=false)
     */
    private $created_time;

    /**
     * @var string
     *
     * @Column(name="updated_time", type="string", nullable=false)
     */
    private $updated_time;

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
    public function setCharity(Charity $charity)
    {
        $this->charity_id = $charity->getId();
    }

    /**
     * @return Charity
     */
    public function getCharity()
    {
        return Charity::find($this->charity_id);
    }

    /**
     * @param string $created_time
     */
    public function setCreatedTime($created_time)
    {
        $this->created_time = $created_time;
    }

    /**
     * @return string
     */
    public function getCreatedTime()
    {
        return $this->created_time;
    }

    /**
     * @param \DateTime $created_time
     */
    public function setCreatedTimeDt(\DateTime $created_time)
    {
        $this->created_time = $created_time->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getCreatedTimeDt()
    {
        return new \DateTime($this->created_time);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $mission
     */
    public function setMission($mission)
    {
        $this->mission = $mission;
    }

    /**
     * @return string
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $updated_time
     */
    public function setUpdatedTime($updated_time)
    {
        $this->updated_time = $updated_time;
    }

    /**
     * @return string
     */
    public function getUpdatedTime()
    {
        return $this->updated_time;
    }

    /**
     * @param \DateTime $updated_time
     */
    public function setUpdatedTimeDt(\DateTime $updated_time)
    {
        $this->updated_time = $updated_time->format(('Y-m-d H:i:s'));
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedTimeDt()
    {
        return new \DateTime($this->updated_time);
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
    public function setUser(User $user)
    {
        $this->user_id = $user->getId();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return User::find($this->user_id);
    }

    /** @var \Entity\MissionVote[]|null */
    private $_votes = null;

    /**
     * @return MissionVote[]
     */
    public function getVotes() {
        if ($this->_votes === null) {
            $this->_votes = MissionVote::findBy(['mission_id' => $this->id]);
        }
        return $this->_votes;
    }

    /** @var null|int */
    private $_vote_sum = null;

    /**
     * @return int
     */
    public function getVoteSum() {
        if ($this->_vote_sum !== null) {
            return $this->_vote_sum;
        }

        $this->_vote_sum = 0;
        foreach($this->getVotes() as $mission_vote) {
            $this->_vote_sum += $mission_vote->getVote();
        }

        return $this->_vote_sum;
    }

    /**
     * @param User $user
     *
     * @return MissionVote|null
     */
    public function getMyVote(User $user) {
        foreach($this->getVotes() as $vote) {
            if ($vote->getUserId() == $user->getId()) {
                return $vote;
            }
        }
        return null;
    }

    /**
     * Returns the source with url's turned into links.
     * urls are turned into links, other words are escaped with htmlspecialchars.
     *
     * @return string
     */
    public function getSourceFormatted() {
        return \Common::formatUrlsIntoLinks($this->source);
    }
}

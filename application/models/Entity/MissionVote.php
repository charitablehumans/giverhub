<?php
namespace Entity;

/**
 * MissionVote
 *
 * @Table(name="mission_vote")
 * @Entity
 */
class MissionVote extends BaseEntity {

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
     * @Column(name="mission_id", type="integer", nullable=false)
     */
    private $mission_id;

    /**
     * @var integer
     *
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @var integer
     *
     * @Column(name="vote", type="integer", nullable=false)
     */
    private $vote;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Mission $mission
     */
    public function setMission(Mission $mission)
    {
        $this->mission_id = $mission->getId();
    }

    /**
     * @return Mission
     */
    public function getMission()
    {
        return Mission::find($this->mission_id);
    }

    /**
     * @param int $mission_id
     */
    public function setMissionId($mission_id)
    {
        $this->mission_id = $mission_id;
    }

    /**
     * @return int
     */
    public function getMissionId()
    {
        return $this->mission_id;
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

    /**
     * @param int $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    }

    /**
     * @return int
     */
    public function getVote()
    {
        return $this->vote;
    }


}

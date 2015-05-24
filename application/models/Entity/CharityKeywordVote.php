<?php
namespace Entity;

/**
 * CharityKeywordVote
 *
 * @Table(name="charity_keyword_vote")
 * @Entity
 */
class CharityKeywordVote extends BaseEntity
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
     * @Column(name="keyword_id", type="integer", nullable=false)
     */
    private $keyword_id;

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
     * @param int $keyword_id
     */
    public function setKeywordId($keyword_id)
    {
        $this->keyword_id = $keyword_id;
    }

    /**
     * @return int
     */
    public function getKeywordId()
    {
        return $this->keyword_id;
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
        return \Base_Controller::$em->getRepository('\Entity\User')->find($this->user_id);
    }

    /**
     * @param CharityKeyword $keyword
     */
    public function setKeyword(CharityKeyword $keyword) {
        $this->keyword_id = $keyword->getId();
    }

    /**
     * @return CharityKeyword
     */
    public function getKeyword() {
        return \Base_Controller::$em->getRepository('\Entity\CharityKeyword')->find($this->keyword_id);
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

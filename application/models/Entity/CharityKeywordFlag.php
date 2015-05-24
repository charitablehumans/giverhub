<?php
namespace Entity;

/**
 * CharityKeywordFlag
 *
 * @Table(name="charity_keyword_flag")
 * @Entity
 */
class CharityKeywordFlag extends BaseEntity
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
     * @var string
     *
     * @Column(name="flagged_time", type="string", nullable=false)
     */
    private $flagged_time;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $flagged_time
     */
    public function setFlaggedTime($flagged_time)
    {
        $this->flagged_time = $flagged_time;
    }

    /**
     * @return string
     */
    public function getFlaggedTime()
    {
        return $this->flagged_time;
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
     * @param \DateTime $dt
     */
    public function setFlaggedTimeDt(\DateTime $dt) {
        $this->flagged_time = $dt->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getFlaggedTimeDt() {
        return new \DateTime($this->flagged_time);
    }
}

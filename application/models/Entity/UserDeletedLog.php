<?php
namespace Entity;

/**
 * UserDeletedLog
 *
 * @Table(name="user_deleted_log")
 * @Entity
 */
class UserDeletedLog extends BaseEntity
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
     * @var string
     *
     * @Column(name="user_data", type="text", nullable=false)
     */
    private $userData;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * @param string $userData
     */
    public function setUserData( $userData )
    {
        $this->userData = $userData;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate( $date )
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId( $userId )
    {
        $this->userId = $userId;
    }
}

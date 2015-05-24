<?php
namespace Entity;

use Doctrine\ORM\EntityManager;

/**
 * UserFollower
 *
 * @Table(name="followers")
 * @Entity
 */
class UserFollower extends BaseEntity
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
     * @Column(name="follower_user_id", type="integer", nullable=false)
     */
    private $follower_user_id;

    /**
     * @var integer
     *
     * @Column(name="followed_user_id", type="integer", nullable=false)
     */
    private $followed_user_id;

    /**
     * @var string
     *
     * @Column(name="date", type="string", nullable=false)
     */
    private $date;


    public function getId() {
        return $this->id;
    }

    public function getFollowerUserId() {
        return $this->follower_user_id;
    }

    public function setFollowerUserId($followerUserId) {
        $this->follower_user_id = $followerUserId;
    }

    public function getFollowedUserId() {
        return $this->followed_user_id;
    }

    public function setFollowedUserId($followedUserId) {
        $this->followed_user_id = $followedUserId;
    }

    /**
     * @return \Entity\User
     */
    public function getFollowerUser() {
        return \Base_Controller::$em->getRepository('Entity\User')->find($this->follower_user_id);
    }

    /**
     * @param User $follower
     */
    public function setFollowerUser(\Entity\User $follower) {
        $this->setFollowerUserId($follower->getId());
    }

    /**
     * @return \Entity\User
     */
    public function getFollowedUser() {
        return \Base_Controller::$em->getRepository('Entity\User')->find($this->followed_user_id);
    }

    /**
     * @param User $followed
     */
    public function setFollowedUser(\Entity\User $followed) {
        $this->setFollowedUserId($followed->getId());
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime() {
        return new \DateTime($this->getDate());
    }

    /**
     * @param \DateTime $date
     */
    public function setDateTime(\DateTime $date) {
        $this->setDate($date->format('Y-m-d H:i:s'));
    }
}

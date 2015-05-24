<?php
namespace Entity;

/**
 * Bet
 *
 * @Table(name="activity_feed_post_delete")
 * @Entity @HasLifecycleCallbacks
 */
class ActivityFeedPostDelete extends BaseEntity {

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
     * @Column(name="`activity_feed_post_id`", type="integer", nullable=false)
     */
    private $activity_feed_post_id;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $activity_feed_post_id
     */
    public function setActivityFeedPostId($activity_feed_post_id)
    {
        $this->activity_feed_post_id = $activity_feed_post_id;
    }

    /**
     * @return int
     */
    public function getActivityFeedPostId()
    {
        return $this->activity_feed_post_id;
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
     * @param ActivityFeedPost $activity_feed_post
     */
    public function setActivityFeedPost(ActivityFeedPost $activity_feed_post)
    {
        $this->activity_feed_post_id = $activity_feed_post->getId();
    }

    /**
     * @return ActivityFeedPost
     */
    public function getActivityFeedPost()
    {
        return ActivityFeedPost::find($this->activity_feed_post_id);
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
}

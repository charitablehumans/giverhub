<?php
namespace Entity;

/**
 * ActivityFeedPostImage
 *
 * @Table(name="activity_feed_post_image")
 * @Entity
 */
class ActivityFeedPostImage extends BaseEntity
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
     * @Column(name="temp_id", type="string", nullable=true)
     */
    private $temp_id;

    /**
     * @var string
     *
     * @Column(name="image_name", type="string", length=255, nullable=false)
     */
    private $image_name;

    /**
     * @var string
     *
     * @Column(name="image_thumb", type="string", length=255, nullable=false)
     */
    private $image_thumb;

    /**
     * @var integer
     *
     * @Column(name="activity_feed_post_id", type="integer", nullable=true)
     */
    private $activity_feed_post_id;

    /**
     * @var integer
     *
     * @Column(name="upload_date", type="string", nullable=false)
     */
    private $upload_date;

    /**
     * @var integer
     *
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getImageName() {
        return $this->image_name;
    }

    /**
     * @return string
     */
    public function getImageThumb() {
        return $this->image_thumb;
    }

    /**
     * @param $imageName
     */
    public function setImageName($imageName) {
        $this->image_name = $imageName;
    }

    /**
     * @param $imageThumb
     */
    public function setImageThumb($imageThumb) {
        $this->image_thumb = $imageThumb;
    }

    /**
     * @return User
     */
    public function getUser() {
        return User::find($this->user_id);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user_id = $user->getId();
    }

    /**
     * @param $uploadDate
     */
    public function setUploadDate($uploadDate) {
        $this->upload_date = $uploadDate;
    }

    /**
     * @return int
     */
    public function getUploadDate() {
        return $this->upload_date;
    }

    /**
     * @return \DateTime
     */
    public function getUploadDateTime() {
        return new \DateTime($this->upload_date);
    }

    /**
     * @param \DateTime $uploadDate
     */
    public function setUploadDateTime(\DateTime $uploadDate) {
        $this->upload_date = $uploadDate->format('Y-m-d H:i:s');
    }

    public function getUrl($include_hostname = false) {
        $path = '/images/activity_post_images/'.$this->getImageName();
        if ($include_hostname) {
            return base_url($path);
        }
        return $path;
    }

    public function getThumbUrl() {
        return '/images/activity_post_images/'.$this->getImageThumb();
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
     * @param ActivityFeedPost $activity_feed_post
     */
    public function setActivityFeedPost(ActivityFeedPost $activity_feed_post)
    {
        if ($activity_feed_post === null) {
            $this->activity_feed_post_id = null;
        } else {
            $this->activity_feed_post_id = $activity_feed_post->getId();
        }
    }

    /**
     * @return ActivityFeedPost
     */
    public function getActivityFeedPost()
    {
        if ($this->activity_feed_post_id === null) {
            return null;
        }
        return ActivityFeedPost::find($this->activity_feed_post_id);
    }

    /**
     * @param int $temp_id
     */
    public function setTempId($temp_id)
    {
        $this->temp_id = $temp_id;
    }

    /**
     * @return int
     */
    public function getTempId()
    {
        return $this->temp_id;
    }

}
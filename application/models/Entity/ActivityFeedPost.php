<?php
namespace Entity;

/**
 * Bet
 *
 * @Table(name="activity_feed_post")
 * @Entity @HasLifecycleCallbacks
 */
class ActivityFeedPost extends BaseEntity {

    /** @PrePersist */
    public function onPrePersist()
    {
        if (!$this->date) {
            $this->date = date('Y-m-d H:i:s');
        }
        if (!$this->scrambled_id) {
            do {
                $this->scrambled_id = bin2hex(openssl_random_pseudo_bytes(10));
                $existing = ActivityFeedPost::findOneBy(['scrambled_id' => $this->scrambled_id]);
            } while($existing);
        }
    }

    /**
     * @PreRemove
     */
    public function preRemove() {
        $em = \Base_Controller::$em;
        foreach(ActivityFeedPostDelete::findBy(['activity_feed_post_id' => $this->id]) as $delete) {
            $em->remove($delete);
            $em->flush();
        }
        foreach(ActivityFeedPostImage::findBy(['activity_feed_post_id' => $this->id]) as $delete) {
            $em->remove($delete);
            $em->flush();
        }
        foreach(ActivityFeedPostYoutubeVideo::findBy(['activity_feed_post_id' => $this->id]) as $delete) {
            $em->remove($delete);
            $em->flush();
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
     * @Column(name="`text`", type="string", nullable=false)
     */
    private $text;

    /**
     * @var string
     *
     * @Column(name="`date`", type="string", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @Column(name="from_user_id", type="integer", nullable=false)
     */
    private $from_user_id;

    /**
     * @var integer
     *
     * @Column(name="to_user_id", type="integer", nullable=false)
     */
    private $to_user_id;

    /**
     * @var integer
     *
     * @Column(name="charity_id", type="integer", nullable=true)
     */
    private $charity_id;

    /**
     * @var string
     *
     * @Column(name="`scrambled_id`", type="string", nullable=false)
     */
    private $scrambled_id;

    /**
     * @var integer
     *
     * @Column(name="`is_deleted`", type="integer", nullable=false)
     */
    private $is_deleted = 0;

    /**
     * @var ExternalUrl
     *
     * @ManyToOne(targetEntity="ExternalUrl")
     * @JoinColumns({
     *   @JoinColumn(name="external_url_id", referencedColumnName="id")
     * })
     */
    private $external_url;


    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDateDt(\DateTime $date)
    {
        $this->date = $date->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getDateDt()
    {
        return new \DateTime($this->date);
    }

    /**
     * @param int $from_user_id
     */
    public function setFromUserId($from_user_id)
    {
        $this->from_user_id = $from_user_id;
    }

    /**
     * @return int
     */
    public function getFromUserId()
    {
        return $this->from_user_id;
    }

    /**
     * @param User $from_user
     */
    public function setFromUser(User $from_user)
    {
        $this->from_user_id = $from_user->getId();
    }

    /**
     * @return User
     */
    public function getFromUser()
    {
        return User::find($this->from_user_id);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function getText($options = []) {
        $text = $this->text;

        if (isset($options['remove_ending_dot']) && $options['remove_ending_dot']) {
            $text = rtrim($this->text, '. ');
        }


        if (isset($options['link_external_urls'])) {
            $external_url = $this->getExternalUrl();

            $text = htmlspecialchars($text);

            if ($external_url) {
                $escaped_url = htmlspecialchars($external_url->getUrl());

                $pattern = '#' . preg_quote($escaped_url) . '#i';
                $replacement = '<a target="_blank" title="' . htmlspecialchars(
                        $external_url->getTitle()
                    ) . '" href="' . $escaped_url . '">' . $escaped_url . '</a>';
                $text = preg_replace($pattern, $replacement, $text);
            }
        }

        return $text;
    }

    /**
     * @param int $to_user_id
     */
    public function setToUserId($to_user_id)
    {
        $this->to_user_id = $to_user_id;
    }

    /**
     * @return int
     */
    public function getToUserId()
    {
        return $this->to_user_id;
    }

    /**
     * @param User $to_user
     */
    public function setToUser($to_user)
    {
        $this->to_user_id = $to_user->getId();
    }

    /**
     * @return User
     */
    public function getToUser()
    {
        return User::find($this->to_user_id);
    }

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
    public function setCharity(Charity $charity = null)
    {
        if ($charity) {
            $this->charity_id = $charity->getId();
        } else {
            $this->charity_id = null;
        }
    }

    /**
     * @return Charity
     */
    public function getCharity()
    {
        if ($this->charity_id) {
            return Charity::find($this->charity_id);
        }
        return $this->charity_id;
    }

    public function hasCharity() {
        return (bool)$this->charity_id;
    }

    /**
     * @return ActivityFeedPostImage[]
     */
    public function getImages() {
        /** @var ActivityFeedPostImage[] $images */
        $images = ActivityFeedPostImage::findBy(['activity_feed_post_id' => $this->id]);
        return $images;
    }

    /**
     * @return ActivityFeedPostYoutubeVideo[]
     */
    public function getYoutubeVideos() {
        /** @var ActivityFeedPostYoutubeVideo[] $youtube_videos */
        $youtube_videos = ActivityFeedPostYoutubeVideo::findBy(['activity_feed_post_id' => $this->id]);
        return $youtube_videos;
    }

    /**
     * @param string $scrambled_id
     */
    public function setScrambledId($scrambled_id)
    {
        $this->scrambled_id = $scrambled_id;
    }

    /**
     * @return string
     */
    public function getScrambledId()
    {
        return $this->scrambled_id;
    }

    public function getFullUrl() {
        return $this->getUrl();
    }

    public function getUrl() {
        return base_url('post/'.$this->getScrambledId());
    }

    public function hideFromUser(User $user) {
        $activity_feed_post_delete = ActivityFeedPostDelete::findOneBy(['user_id' => $user->getId(), 'activity_feed_post_id' => $this->id]);
        if (!$activity_feed_post_delete) {
            $activity_feed_post_delete = new ActivityFeedPostDelete();
            $activity_feed_post_delete->setUser($user);
            $activity_feed_post_delete->setActivityFeedPost($this);
            \Base_Controller::$em->persist($activity_feed_post_delete);
            \Base_Controller::$em->flush($activity_feed_post_delete);
        }
    }

    public function undoHideFromUser(User $user) {
        $activity_feed_post_delete = ActivityFeedPostDelete::findOneBy(['user_id' => $user->getId(), 'activity_feed_post_id' => $this->id]);
        if ($activity_feed_post_delete) {
            \Base_Controller::$em->remove($activity_feed_post_delete);
            \Base_Controller::$em->flush();
        }
    }

    public function deleteFromUser(User $user) {
        if ($this->from_user_id == $user->getId()) {
            $this->is_deleted = 1;
            \Base_Controller::$em->persist($this);
            \Base_Controller::$em->flush($this);
        } else {
            $this->hideFromUser($user);
        }
    }

    public function undoDeleteFromUser(User $user) {
        if ($this->from_user_id == $user->getId()) {
            $this->is_deleted = 0;
            \Base_Controller::$em->persist($this);
            \Base_Controller::$em->flush($this);
        } else {
            $this->undoHideFromUser($user);
        }
    }

    /**
     * @param int $is_deleted
     */
    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;
    }

    /**
     * @return int
     */
    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    /**
     * @return bool
     */
    public function isDeleted() {
        return (bool)$this->is_deleted;
    }

    /**
     * @param \Entity\ExternalUrl $external_url
     */
    public function setExternalUrl($external_url)
    {
        $this->external_url = $external_url;
    }

    /**
     * @return \Entity\ExternalUrl
     */
    public function getExternalUrl()
    {
        return $this->external_url;
    }

}

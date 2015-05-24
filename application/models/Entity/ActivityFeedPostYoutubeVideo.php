<?php
namespace Entity;

/**
 * ActivityFeedPostYoutubeVideo
 *
 * @Table(name="activity_feed_post_youtube_video")
 * @Entity
 */
class ActivityFeedPostYoutubeVideo extends BaseEntity
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
     * @Column(name="video_id", type="string", nullable=false)
     */
    private $video_id;

    /**
     * @var string
     *
     * @Column(name="activity_feed_post_id", type="integer", nullable=true)
     */
    private $activity_feed_post_id;

    /**
     * @var string
     *
     * @Column(name="activity_feed_post_comment_id", type="integer", nullable=true)
     */
    private $activity_feed_post_comment_id;

    /**
     * @var string
     *
     * @Column(name="title", type="string", nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @Column(name="description", type="string", nullable=true)
     */
    private $description;


    /**
     * @return int
     */
    public function getId() {
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
     * @param int $activity_feed_post_comment_id
     */
    public function setActivityFeedPostCommentId($activity_feed_post_comment_id)
    {
        $this->activity_feed_post_comment_id = $activity_feed_post_comment_id;
    }

    /**
     * @return int
     */
    public function getActivityFeedPostCommentId()
    {
        return $this->activity_feed_post_comment_id;
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
     * @param ActivityFeedPostComment $activity_feed_post_comment
     */
    public function setActivityFeedPostComment(ActivityFeedPostComment $activity_feed_post_comment)
    {
        if ($activity_feed_post_comment === null) {
            $this->activity_feed_post_comment_id = null;
        } else {
            $this->activity_feed_post_comment_id = $activity_feed_post_comment->getId();
        }
    }

    /**
     * @return ActivityFeedPostComment
     */
    public function getActivityFeedPostComment()
    {
        if ($this->activity_feed_post_comment_id === null) {
            return null;
        }
        return ActivityFeedPostComment::find($this->activity_feed_post_comment_id);
    }

    /**
     * @param string $video_id
     */
    public function setVideoId($video_id)
    {
        $this->video_id = $video_id;
    }

    /**
     * @return string
     */
    public function getVideoId()
    {
        return $this->video_id;
    }

    public function getThumbnailUrl() {
        return 'https://img.youtube.com/vi/'.htmlspecialchars($this->video_id).'/0.jpg';
    }

    public function setTitleAndDescription() {
        require_once(__DIR__.'/../../libraries/google-api-php-client-2/src/Google/Client.php');
        require_once(__DIR__.'/../../libraries/google-api-php-client-2/src/Google/Service/YouTube.php');

        $CI =& get_instance();

        $client = new \Google_Client();
        $client->setDeveloperKey($CI->config->item('google_developer_key'));

        // Define an object that will be used to make all API requests.
        $youtube = new \Google_Service_YouTube($client);

        try {
            # Call the videos.list method to retrieve location details for each video.
            $videosResponse = $youtube->videos->listVideos('snippet', array(
                    'id' => $this->video_id,
                ));


            // Display the list of matching videos.
            foreach ($videosResponse['items'] as $videoResult) {
                $this->title = $videoResult['snippet']['title'];
                $this->description = $videoResult['snippet']['description'];
            }

        } catch (\Google_Exception $e) {
        }
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function getDescription($options = [])
    {
        if (isset($options['remove_ending_dot']) && $options['remove_ending_dot']) {
            return rtrim($this->description, '. ');
        } else {
            return $this->description;
        }
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

}
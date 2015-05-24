<?php
namespace Entity;

/**
 * ChallengeUserVideo
 *
 * @Table(name="challenge_user_video", uniqueConstraints={@UniqueConstraint(name="filename_UNIQUE", columns={"filename"})})
 * @Entity @HasLifecycleCallbacks
 */
class ChallengeUserVideo extends BaseEntity {

    /** @PreRemove */
    public function onPreRemove() {
        if ($this->filename) {
            $filename = __DIR__.'/../../../videos/challenge/'.$this->filename;
            if (!unlink($filename)) {
                throw new \Exception('Could not unlink: '.$filename);
            }
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
     * @Column(name="youtube_video_id", type="string", length=45, nullable=true)
     */
    private $youtubeVideoId;

    /**
     * @var string
     *
     * @Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var string
     *
     * @Column(name="filetype", type="string", length=45, nullable=true)
     */
    private $filetype;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $youtubeVideoId
     */
    public function setYoutubeVideoId($youtubeVideoId)
    {
        $this->youtubeVideoId = $youtubeVideoId;
    }

    /**
     * @return string
     */
    public function getYoutubeVideoId()
    {
        return $this->youtubeVideoId;
    }

    /**
     * @param string $filetype
     */
    public function setFiletype($filetype)
    {
        $this->filetype = $filetype;
    }

    /**
     * @return string
     */
    public function getFiletype()
    {
        return $this->filetype;
    }

    public function getUrl() {
        return '/videos/challenge/'.rawurlencode($this->filename);
    }

    public function getLink() {
        return '<a href="'.$this->getUrl().'" title="'.htmlspecialchars($this->getFilename()).'">'.htmlspecialchars($this->getFilename()).'</a>';
    }
}

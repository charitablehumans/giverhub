<?php
namespace Entity;

class UrlSlugExistsException extends \Exception {}

/**
 * Petition
 *
 * @Table(name="petition", uniqueConstraints={@UniqueConstraint(name="url_slug_UNIQUE", columns={"url_slug"})}, indexes={@Index(name="fk_petition_charity_idx", columns={"charity_id"}), @Index(name="fk_petition_user_idx", columns={"user_id"}), @Index(name="fk_petition_photo_idx", columns={"photo_id"})})
 * @Entity @HasLifecycleCallbacks
 */
class Petition extends BaseEntity implements \JsonSerializable {

    /** @PrePersist */
    public function onPrePersist() {
        if (!$this->createdDate) {
            $this->createdDate = new \DateTime();
        }

        if (!$this->urlSlug) {
            $x = '';
            $n = 1;
            while(1) {
                try {
                    $this->setUrlSlug($this->getTitle() . $x);
                    break;
                } catch(UrlSlugExistsException $e) { $x = '-' . $n++; }
            }
        }
    }

    /** @PreUpdate */
    public function onPreUpdate() {
        $this->updatedDate = new \DateTime();
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
     * @Column(name="target_text", type="text", nullable=false)
     */
    private $targetText;

    /**
     * @var string
     *
     * @Column(name="what_text", type="text", nullable=false)
     */
    private $whatText;

    /**
     * @var string
     *
     * @Column(name="why_text", type="text", nullable=false)
     */
    private $whyText;

    /**
     * @var string
     *
     * @Column(name="img_url", type="string", length=255, nullable=true)
     */
    private $imgUrl;

    /**
     * @var string
     *
     * @Column(name="video_id", type="string", length=45, nullable=true)
     */
    private $videoId;

	/**
     * @var string
     *
     * @Column(name="status", type="string", nullable=false)
     */
    private $status;

	/**
     * @var integer
     *
     * @Column(name="goal", type="integer", nullable=true)
     */
    private $goal;

    /**
     * @var \DateTime
     *
     * @Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate;

	/**
     * @var \DateTime
     *
     * @Column(name="end_at", type="datetime", nullable=true)
     */
    private $end_at;

    /**
     * @var \DateTime
     *
     * @Column(name="updated_date", type="datetime", nullable=true)
     */
    private $updatedDate;

    /**
     * @var string
     *
     * @Column(name="url_slug", type="string", length=255, nullable=false)
     */
    private $urlSlug;

    /**
     * @var PetitionPhoto
     *
     * @ManyToOne(targetEntity="PetitionPhoto")
     * @JoinColumns({
     *   @JoinColumn(name="photo_id", referencedColumnName="id")
     * })
     */
    private $photo;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var Charity
     *
     * @ManyToOne(targetEntity="Charity")
     * @JoinColumns({
     *   @JoinColumn(name="charity_id", referencedColumnName="id")
     * })
     */
    private $charity;

    /**
     * @param \DateTime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $imgUrl
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;
    }

    /**
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * @param \Entity\PetitionPhoto $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return \Entity\PetitionPhoto
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $targetText
     */
    public function setTargetText($targetText)
    {
        $this->targetText = $targetText;
    }

    /**
     * @return string
     */
    public function getTargetText()
    {
        return $this->targetText;
    }

    /**
     * @param \DateTime $updatedDate
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @param $urlSlug
     *
     * @throws UrlSlugExistsException
     */
    public function setUrlSlug($urlSlug) {
        $urlSlug = \Common::slug($urlSlug, '-');

        $petition = Petition::findBy(['urlSlug' => $urlSlug]);
        $history = PetitionUrlSlug::findOneBy(['urlSlug' => $urlSlug]);

        if ($petition || $history) {
            throw new UrlSlugExistsException('new urlSlug already exists.');
        }

        if ($this->urlSlug) {
            $history = new PetitionUrlSlug();
            $history->setUrlSlug($this->urlSlug);
            $history->setPetition($this);
            \Base_Controller::$em->persist($history);
            \Base_Controller::$em->flush($history);
        }

        $this->urlSlug = $urlSlug;
    }

    /**
     * @return string
     */
    public function getUrlSlug()
    {
        return $this->urlSlug;
    }

    /**
     * @param \Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $videoId
     */
    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
    }

    /**
     * @return string
     */
    public function getVideoId()
    {
        return $this->videoId;
    }

	/**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

	public function setGoal($goal)
	{
		$this->goal = $goal;
	}

	public function getGoal()
	{
		return $this->goal;
	}

    /**
     * @param string $whatText
     */
    public function setWhatText($whatText)
    {
        $this->whatText = $whatText;
    }

    /**
     * @return string
     */
    public function getWhatText()
    {
        return $this->whatText;
    }

    /**
     * @param string $whyText
     */
    public function setWhyText($whyText)
    {
        $this->whyText = $whyText;
    }

    /**
     * @return string
     */
    public function getWhyText()
    {
        return $this->whyText;
    }

    public function jsonSerialize() {
        $ret = [
            'id' => $this->id,
            'target' => $this->targetText,
            'what' => $this->whatText,
            'why' => $this->whyText,
            'url' => $this->getUrl(),
            'full_url' => $this->getFullUrl(),
        ];

        if ($this->photo) {
            $ret['photo_id'] = $this->photo->getId();
            $ret['photo_thumb_src'] = $this->photo->getThumbSrc();
            $ret['photo_full_src'] = $this->photo->getFullSrc();
        }

        if ($this->videoId) {
            $ret['video_id'] = $this->videoId;
        }

        if ($this->imgUrl) {
            $ret['img_url'] = $this->imgUrl;
        }

        return $ret;
    }

    public function getTitle() {
        return $this->targetText .' - '. $this->whatText;
    }

    public function getUrl() {
        return '/g-petitions/'.$this->urlSlug;
    }

    public function getFullUrl() {
        return base_url($this->getUrl());
    }

    public function getLink() {
        return '<a href="'.$this->getUrl().'" title="'.htmlspecialchars($this->getTitle()).'">'.htmlspecialchars($this->getTitle()).'</a>';
    }

    /**
     * @return bool
     */
    public function hasImage() {
        return $this->imgUrl || $this->photo;
    }

    /**
     * @return bool
     */
    public function hasVideo() {
        return (bool)$this->videoId;
    }

    public function getVideoHtml() {
        if (!$this->hasVideo()) {
            throw new \Exception('Trying to get video html when video id is not set.');
        }

        return '<iframe class="g-petition-video youtube-player youtube-preview-iframe"
                        type="text/html"
                        width="100%"
                        height=""
                        src="https://www.youtube.com/embed/'.$this->videoId.'?controls=2"
                        allowfullscreen
                        frameborder="0"></iframe>';
    }

    public function getImageUrl() {
        if (!$this->hasImage()) {
            throw new \Exception('Trying to get image url when image is not set.');
        }
        if ($this->imgUrl) {
            $imgUrl = $this->imgUrl;
        } else {
            $imgUrl = $this->photo->getFullSrc();
        }
        return $imgUrl;
    }

    public function getImageUrlPrependHttp() {
        $imgUrl = $this->getImageUrl();

        if (preg_match('#^http://#', $imgUrl)) {
            return $imgUrl;
        } else {
            return base_url($imgUrl);
        }
    }

    public function getImageHtml() {
        if (!$this->hasImage()) {
            throw new \Exception('Trying to get image html when image is not set.');
        }

        $imgUrl = $this->getImageUrl();

        return '<img class="petition-image hide-on-medium-resolution"
                     src="'.$imgUrl.'"
                     alt="'.htmlspecialchars($this->getTitle()).'">';
    }

    /**
     * @param \Entity\Charity $charity
     */
    public function setCharity($charity)
    {
        $this->charity = $charity;
    }

    /**
     * @return \Entity\Charity
     */
    public function getCharity()
    {
        return $this->charity;
    }

    /**
     * @param \DateTime $date
     */
    public function setEndAt(\DateTime $date)
    {
        $this->end_at = $date;
    }

    /**
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->end_at;
    }

	public function hasEnded() {
        if ($this->end_at === null) {
            return false;
        }

        $end = new \DateTime($this->end_at);
        $now = new \DateTime();
        if ($end < $now) {
            return true;
        }

        return false;
    }

	public function sign(User $user, $hidden = false, $reason = null) {

        /** @var PetitionSignature $existingSignature */
        $existingSignature = PetitionSignature::findOneBy(['user_id' => $user->getId(), 'petition_id' => $this->getId()]);
        if ($existingSignature) {
            return 'You already signed this petition ' . $existingSignature->getSignedOn();
        }

		if ($hidden === 'true') {
			$isHide = 1;
		} else {
			$isHide = 0;
		}

        $signature = new PetitionSignature();
        $signature->setUser($user);
        $signature->setPetition($this);
		$signature->setReason($reason);
		$signature->setSignedOn(date('Y-m-d H:i:s'));
		$signature->setIsHide($isHide);

        \Base_Controller::$em->persist($signature);
        \Base_Controller::$em->flush($signature);

        return true;
    }

    public function unsign(User $user) {
        /** @var PetitionSignature $existingSignature */
        $existingSignature = PetitionSignature::findOneBy(['user_id' => $user->getId(), 'petition_id' => $this->getId()]);
        if ($existingSignature) {
            \Base_Controller::$em->remove($existingSignature);
            \Base_Controller::$em->flush();
        }
    }

	/**
     * @return integer
     */
    public function getGiverhubPetitionSignaturesCount() {
        $CI =& get_instance();
        $CI->db->select('COUNT(*) AS cnt');
        $CI->db->from('petition_signature');
        $CI->db->where('petition_id', $this->id);
        $res = $CI->db->get();
        $res = $res->row_array();
        $cnt = $res['cnt'];
        return (int)$cnt;
    }

	/**
     * @param int $page
     * @param int $limit
     *
     * @return PetitionSignature[]
     */
    public function getGiverhubPetitionSignatures($page = 1, $limit = 20) {
        $offset = ($page * $limit) - $limit;

        /** @var PetitionSignature[] $signatures */
        $signatures = PetitionSignature::findBy(array('petition_id' => $this->id), array('signed_on' => 'desc'), $limit, $offset);

        return $signatures;
    }

	/**
     * @return integer
     */
    public function getGiverhubPetitionReasonCount() {
        $CI =& get_instance();
        $CI->db->select('COUNT(*) AS cnt');
        $CI->db->from('petition_signature');
        $CI->db->where('petition_id', $this->id);
		$CI->db->where('reason !=', '');
        $res = $CI->db->get();
        $res = $res->row_array();
        $cnt = $res['cnt'];
        return (int)$cnt;
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return PetitionSignature[]
     */
    public function getGiverhubPetitionReasons($page = 1, $limit = 20) {
        $offset = ($page * $limit) - $limit;

        /** @var PetitionSignature[] $reasons */
        $reasons = PetitionSignature::findBy(array('petition_id' => $this->id), array('signed_on' => 'desc'), $limit, $offset);

        return $reasons;
    }

	/**
     * @return integer
     */
    public function getGiverhubPetitionNewsCount() {
        $CI =& get_instance();
        $CI->db->select('COUNT(*) AS cnt');
        $CI->db->from('petition_news_update');
        $CI->db->where('petition_id', $this->id);
        $res = $CI->db->get();
        $res = $res->row_array();
        $cnt = $res['cnt'];
        return (int)$cnt;
    }

	/**
     * @param int $page
     * @param int $limit
     *
     * @return PetitionNewsUpdate[]
     */
    public function getGiverhubPetitionNews($page = 1, $limit = 20) {
        $offset = ($page * $limit) - $limit;

        /** @var PetitionSignature[] $signatures */
        $news = PetitionNewsUpdate::findBy(array('petition_id' => $this->id), array('created_on' => 'desc'), $limit, $offset);

        return $news;
    }

	public function getMediaType() {
        if ($this->imgUrl) {
            $mediaType = "img_url";
        } else {
            $mediaType = "photo";
        }
        return $mediaType;
	}

    /**
     * @param array $order
     * @param int   $limit
     *
     * @return PetitionNewsUpdate[]
     */
    public function getNews($order = ['created_on' => 'desc'], $limit = 3) {
        return PetitionNewsUpdate::findBy(['petition_id' => $this->getId()], $order, $limit);
    }

    public function hasMedia() {
        return $this->hasImage() || $this->hasVideo();
    }

    public function getMediaHtml() {
        if ($this->hasVideo()) {
            return $this->getVideoHtml();
        } else {
            return $this->getImageHtml();
        }
    }

    public function getOverview() {
        return $this->whatText;
    }
}

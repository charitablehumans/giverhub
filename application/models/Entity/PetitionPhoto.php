<?php
namespace Entity;

/**
 * PetitionPhoto
 *
 * @Table(name="petition_photo", uniqueConstraints={@UniqueConstraint(name="temp_id_UNIQUE", columns={"temp_id"})}, indexes={@Index(name="fk_petition_photo_user_idx", columns={"user_id"})})
 * @Entity @HasLifecycleCallbacks
 */
class PetitionPhoto extends BaseEntity {

    /** @PreRemove */
    public function onPreRemove() {
        $full = __DIR__.'/../../../images/giverhub_petition_images/'.$this->full;
        $thumb = __DIR__.'/../../../images/giverhub_petition_images/thumbs/'.$this->thumb;

        if (!unlink($full)) {
            throw new \Exception('Could not unlink: '.$full);
        }
        if (!unlink($thumb)) {
            throw new \Exception('Could not unlink: '.$thumb);
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
     * @Column(name="temp_id", type="string", length=255, nullable=false)
     */
    private $tempId;

    /**
     * @var string
     *
     * @Column(name="full", type="string", length=255, nullable=false)
     */
    private $full;

    /**
     * @var string
     *
     * @Column(name="thumb", type="string", length=255, nullable=false)
     */
    private $thumb;

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
     * @param string $full
     */
    public function setFull($full)
    {
        $this->full = $full;
    }

    /**
     * @return string
     */
    public function getFull()
    {
        return $this->full;
    }

    public function getFullSrc() {
        return '/images/giverhub_petition_images/'.rawurlencode($this->full);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $tempId
     */
    public function setTempId($tempId)
    {
        $this->tempId = $tempId;
    }

    /**
     * @return string
     */
    public function getTempId()
    {
        return $this->tempId;
    }

    /**
     * @param string $thumb
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
    }

    /**
     * @return string
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    public function getThumbSrc() {
        return '/images/giverhub_petition_images/thumb/'.rawurlencode($this->thumb);
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
}

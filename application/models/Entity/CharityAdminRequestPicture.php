<?php
namespace Entity;

/**
 * CharityAdminRequestPicture
 *
 * @Table(name="charity_admin_request_picture", indexes={@Index(name="fk_charity_admin_request_picture_request_idx", columns={"charity_admin_request_id"})})
 * @Entity
 */
class CharityAdminRequestPicture extends BaseEntity
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
     * @Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var string
     *
     * @Column(name="thumb_filename", type="string", length=255, nullable=false)
     */
    private $thumbFilename;

    /**
     * @var string
     *
     * @Column(name="temp_id", type="string", length=45, nullable=true)
     */
    private $tempId;

    /**
     * @var CharityAdminRequest
     *
     * @ManyToOne(targetEntity="CharityAdminRequest")
     * @JoinColumns({
     *   @JoinColumn(name="charity_admin_request_id", referencedColumnName="id")
     * })
     */
    private $charityAdminRequest;

    /**
     * @param \Entity\CharityAdminRequest $charityAdminRequest
     */
    public function setCharityAdminRequest(CharityAdminRequest $charityAdminRequest)
    {
        $this->charityAdminRequest = $charityAdminRequest;
    }

    /**
     * @return \Entity\CharityAdminRequest
     */
    public function getCharityAdminRequest()
    {
        return $this->charityAdminRequest;
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
     * @param string $thumbFilename
     */
    public function setThumbFilename($thumbFilename)
    {
        $this->thumbFilename = $thumbFilename;
    }

    /**
     * @return string
     */
    public function getThumbFilename()
    {
        return $this->thumbFilename;
    }

    public function getUrl() {
        return base_url('/images/request_charity_admin/'.rawurlencode($this->getFilename()));
    }

    public function getThumbUrl() {
        return base_url('/images/request_charity_admin/thumbs/'.rawurlencode($this->getThumbFilename()));
    }
}

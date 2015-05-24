<?php
namespace Entity;

/**
 * CharityCity
 *
 * @Table(name="charity_images")
 * @Entity
 */
class CharityImage extends BaseEntity
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
     * @Column(name="charity_id", type="integer", nullable=false)
     */
    private $charity_id;


    /**
     * @var integer
     *
     * @Column(name="show_image", type="integer", nullable=false)
     */
    private $show_image;

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
     * @param $charityId
     */
    public function setCharityId($charityId) {
        $this->charity_id = $charityId;
    }

    /**
     * @return int
     */
    public function getCharityId() {
        return $this->charity_id;
    }

    /**
     * @return Charity
     */
    public function getCharity() {
        return Charity::find($this->charity_id);
    }

    /**
     * @param Charity $charity
     */
    public function setCharity(Charity $charity) {
        $this->charity_id = $charity->getId();
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

    /**
     * @param $showImage
     */
    public function setShowImage($showImage) {
        $this->show_image = $showImage;
    }

    /**
     * @return int
     */
    public function getShowImage() {
        return $this->show_image;
    }

    public function getUrl() {
        return '/images/charity/'.$this->getImageName();
    }

    public function getThumbUrl() {
        return '/images/charity/thumbs/'.$this->getImageThumb();
    }
}
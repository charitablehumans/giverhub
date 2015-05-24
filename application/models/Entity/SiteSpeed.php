<?php
namespace Entity;

/**
 * SiteSpeed
 *
 * @Table(name="site_speed")
 * @Entity
 */
class SiteSpeed extends BaseEntity
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
     * @var float
     *
     * @Column(name="speed", type="float", precision=10, scale=0, nullable=false)
     */
    private $speed;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var string
     *
     * @Column(name="extra", type="text", nullable=true)
     */
    private $extra;

    /**
     * @var string
     *
     * @Column(name="url", type="text", nullable=false)
     */
    private $url;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param float $speed
     */
    public function setSpeed( $speed )
    {
        $this->speed = $speed;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate( $date )
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId( $userId )
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param string $extra
     */
    public function setExtra( $extra )
    {
        $this->extra = $extra;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl( $url )
    {
        $this->url = $url;
    }
}

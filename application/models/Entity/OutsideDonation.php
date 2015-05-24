<?php
namespace Entity;

/**
 * OutsideDonation
 *
 * @Table(name="outside_donation", indexes={@Index(name="fk_outside_donation_user_idx", columns={"user_id"})})
 * @Entity
 */
class OutsideDonation extends BaseEntity {
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
     * @Column(name="nonprofit_name", type="text", nullable=false)
     */
    private $nonprofitName;

    /**
     * @var integer
     *
     * @Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @Column(name="time", type="time", nullable=true)
     */
    private $time;

    /**
     * @var string
     *
     * @Column(name="cause", type="text", nullable=true)
     */
    private $cause;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNonprofitName()
    {
        return $this->nonprofitName;
    }

    /**
     * @param string $nonprofitName
     */
    public function setNonprofitName( $nonprofitName )
    {
        $this->nonprofitName = $nonprofitName;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount( $amount )
    {
        $this->amount = $amount;
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
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime( $time )
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getCause()
    {
        return $this->cause;
    }

    /**
     * @param string $cause
     */
    public function setCause( $cause )
    {
        $this->cause = $cause;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser( $user )
    {
        $this->user = $user;
    }

    public function getDateTimeString() {
        return $this->date->format('Y-m-d') . ($this->time ? ' ' . $this->time->format('H:i') : '00:00');
    }
}

<?php

namespace Entity;

/**
 * EmailHistory
 *
 * @Table(name="email_history", indexes={@Index(name="fk_email_history_user_idx", columns={"user_id"})})
 * @Entity
 */
class EmailHistory extends BaseEntity
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
     * @Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @Column(name="extra", type="text", nullable=true)
     */
    private $extra;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    const TYPE_PETITION_TREND_START = 'petition_trend_start';

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType( $type )
    {
        $this->type = $type;
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


}

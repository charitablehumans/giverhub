<?php
namespace Entity;

/**
 * CharityAdmin
 *
 * @Table(name="charity_admin", indexes={@Index(name="fk_charity_admin_charity_idx", columns={"charity_id"}), @Index(name="fk_charity_admin_user_idx", columns={"user_id"}), @Index(name="fk_charity_admin_approved_by_user_idx", columns={"approved_by"})})
 * @Entity
 */
class CharityAdmin extends BaseEntity
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
     * @var \DateTime
     *
     * @Column(name="approved_at", type="datetime", nullable=false)
     */
    private $approvedAt;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="approved_by", referencedColumnName="id")
     * })
     */
    private $approvedBy;

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
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @param \DateTime $approvedAt
     */
    public function setApprovedAt($approvedAt)
    {
        $this->approvedAt = $approvedAt;
    }

    /**
     * @return \DateTime
     */
    public function getApprovedAt()
    {
        return $this->approvedAt;
    }

    /**
     * @param \Entity\User $approvedBy
     */
    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;
    }

    /**
     * @return \Entity\User
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

<?php
namespace Entity;

/**
 * ChangeOrgPetitionFacebookShare
 *
 * @Table(name="change_org_petition_facebook_share", indexes={@Index(name="fk_change_org_petition_facebook_share_user_idx", columns={"user_id"}), @Index(name="fk_change_org_petition_facebook_share_petition_idx", columns={"petition_id"})})
 * @Entity
 */
class ChangeOrgPetitionFacebookShare extends BaseEntity {
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

    /**
     * @var ChangeOrgPetition
     *
     * @ManyToOne(targetEntity="ChangeOrgPetition")
     * @JoinColumns({
     *   @JoinColumn(name="petition_id", referencedColumnName="id")
     * })
     */
    private $petition;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function setDate(\DateTime $date )
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
    public function setUser(User $user )
    {
        $this->user = $user;
    }

    /**
     * @return ChangeOrgPetition
     */
    public function getPetition()
    {
        return $this->petition;
    }

    /**
     * @param ChangeOrgPetition $petition
     */
    public function setPetition(ChangeOrgPetition $petition )
    {
        $this->petition = $petition;
    }
}

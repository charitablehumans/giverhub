<?php
namespace Entity;

/**
 * PetitionFacebookShare
 *
 * @Table(name="petition_facebook_share", indexes={@Index(name="fk_petition_facebook_share_user_idx", columns={"user_id"}), @Index(name="fk_petition_facebook_share_petition_idx", columns={"petition_id"})})
 * @Entity
 */
class PetitionFacebookShare extends BaseEntity
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
     * @Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var Petition
     *
     * @ManyToOne(targetEntity="Petition")
     * @JoinColumns({
     *   @JoinColumn(name="petition_id", referencedColumnName="id")
     * })
     */
    private $petition;

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
     * @param int $id
     */
    public function setId( $id )
    {
        $this->id = $id;
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
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return Petition
     */
    public function getPetition()
    {
        return $this->petition;
    }

    /**
     * @param Petition $petition
     */
    public function setPetition(Petition $petition)
    {
        $this->petition = $petition;
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
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getFullUrl() {
        return $this->getPetition()->getFullUrl();
    }
}

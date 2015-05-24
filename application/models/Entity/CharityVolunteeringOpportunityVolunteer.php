<?php
namespace Entity;

/**
 * CharityVolunteeringOpportunityVolunteer
 *
 * @Table(name="charity_volunteering_opportunity_volunteer", uniqueConstraints={@UniqueConstraint(name="uq_opp_user", columns={"charity_volunteering_opportunity_id", "user_id"})}, indexes={@Index(name="fk_charity_volunteering_opportunity_volunteer_opp_idx", columns={"charity_volunteering_opportunity_id"}), @Index(name="fk_charity_volunteering_opportunity_volunteer_user_idx", columns={"user_id"})})
 * @Entity
 */
class CharityVolunteeringOpportunityVolunteer extends BaseEntity implements \JsonSerializable
{

    public function jsonSerialize() {
        $arr = [
            'id' => $this->id,
            'event' => $this->getCharityVolunteeringOpportunity(),
            'user' => $this->getUser(),
            'status' => $this->getStatus(),
        ];
        return $arr;
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
     * @Column(name="status", type="string", nullable=false)
     */
    private $status = 'requested';

    /**
     * @var CharityVolunteeringOpportunity
     *
     * @ManyToOne(targetEntity="CharityVolunteeringOpportunity")
     * @JoinColumns({
     *   @JoinColumn(name="charity_volunteering_opportunity_id", referencedColumnName="id")
     * })
     */
    private $charityVolunteeringOpportunity;

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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus( $status )
    {
        $this->status = $status;
    }

    /**
     * @return CharityVolunteeringOpportunity
     */
    public function getCharityVolunteeringOpportunity()
    {
        return $this->charityVolunteeringOpportunity;
    }

    /**
     * @param CharityVolunteeringOpportunity $charityVolunteeringOpportunity
     */
    public function setCharityVolunteeringOpportunity(CharityVolunteeringOpportunity $charityVolunteeringOpportunity )
    {
        $this->charityVolunteeringOpportunity = $charityVolunteeringOpportunity;
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


}

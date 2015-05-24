<?php
namespace Entity;

/**
 * CharityAdminRequest
 *
 * @Table(name="charity_admin_request", indexes={@Index(name="fk_charity_admin_request_user_idx", columns={"user_id"}), @Index(name="fk_charity_admin_request_charity_idx", columns={"charity_id"})})
 * @Entity @HasLifecycleCallbacks
 */
class CharityAdminRequest extends BaseEntity {
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
     * @Column(name="message", type="text", nullable=false)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var \Entity\Charity
     *
     * @ManyToOne(targetEntity="\Entity\Charity")
     * @JoinColumns({
     *   @JoinColumn(name="charity_id", referencedColumnName="id")
     * })
     */
    private $charity;

    /**
     * @var \Entity\User
     *
     * @ManyToOne(targetEntity="\Entity\User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /** @PrePersist */
    public function onPrePersist()
    {
        if (!$this->date) {
            $this->date = new \DateTime();
        }
    }

    /** @PreRemove */
    public function onPreRemove() {
        $pics = $this->getPictures();
        foreach($pics as $pic) {
            \Base_Controller::$em->remove($pic);
            \Base_Controller::$em->flush();
        }
    }


    /**
     * @param Charity $charity
     */
    public function setCharity($charity)
    {
        $this->charity = $charity;
    }

    /**
     * @return Charity
     */
    public function getCharity()
    {
        return $this->charity;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
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

    /**
     * @return CharityAdminRequestPicture[]
     */
    public function getPictures() {
        return CharityAdminRequestPicture::findBy(['charityAdminRequest' => $this]);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function approve() {

        $charity_admin = CharityAdmin::findOneBy(['user' => $this->user, 'charity' => $this->charity]);

        $em = \Base_Controller::$em;

        if (!$charity_admin) {
            $charity_admin = new CharityAdmin();
            $charity_admin->setUser($this->user);
            $charity_admin->setCharity($this->charity);
            $charity_admin->setApprovedAt(new \DateTime());
            $charity_admin->setApprovedBy(\Base_Controller::$staticUser);

            $em->persist($charity_admin);
            $em->flush($charity_admin);
        }

        $em->remove($this);
        $em->flush();

        return true;
    }
}
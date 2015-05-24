<?php
namespace Entity;

/**
 * Chat
 *
 * @Table(name="chat", indexes={@Index(name="fk_chat_from_idx", columns={"from_user_id"}), @Index(name="fk_chat_to_idx", columns={"to_user_id"}), @Index(name="idx_chat_to_from", columns={"from_user_id", "to_user_id"})})
 * @Entity
 */
class Chat extends BaseEntity implements \JsonSerializable {

    public function jsonSerialize() {
        $arr = [
            'id' => $this->id,
            'from' => $this->getFromCharity() ? $this->getFromCharity() : $this->getFromUser(),
            'to' => $this->getToCharity() ? $this->getToCharity() : $this->getToUser(),
            'sent' => $this->getTimeSent()->format(DATE_ATOM),
            'message' => $this->getMessage(),
            'seen' => $this->getTimeSeen() ? $this->getTimeSeen()->format('Y-m-d H:i:s') : null,
            'unread_by_current_user' => $this->isUnreadByCurrentUser(),
            'tmp_id' => $this->tmp_id,
            'volunteer' => $this->getVolunteer(),
        ];
        return $arr;
    }

    public function isUnreadByCurrentUser() {
        $user = \Base_Controller::$staticUser;
        if (!$user) {
            return false;
        }
        return !$this->timeSeen && $this->toUser == $user && $this->toUser != $this->fromUser;
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
     * @var \DateTime
     *
     * @Column(name="time_sent", type="datetime", nullable=false)
     */
    private $timeSent;

    /**
     * @var string
     *
     * @Column(name="message", type="text", nullable=false)
     */
    private $message;

    /**
     * @var string
     *
     * @Column(name="tmp_id", type="text", nullable=false)
     */
    private $tmp_id;

    /**
     * @var \DateTime
     *
     * @Column(name="time_seen", type="datetime", nullable=true)
     */
    private $timeSeen;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="from_user_id", referencedColumnName="id")
     * })
     */
    private $fromUser;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="to_user_id", referencedColumnName="id")
     * })
     */
    private $toUser;

    /**
     * @var Charity
     *
     * @ManyToOne(targetEntity="Charity")
     * @JoinColumns({
     *   @JoinColumn(name="to_charity_id", referencedColumnName="id")
     * })
     */
    private $toCharity;

    /**
     * @var Charity
     *
     * @ManyToOne(targetEntity="Charity")
     * @JoinColumns({
     *   @JoinColumn(name="from_charity_id", referencedColumnName="id")
     * })
     */
    private $fromCharity;

    /**
     * @var CharityVolunteeringOpportunityVolunteer
     *
     * @ManyToOne(targetEntity="CharityVolunteeringOpportunityVolunteer")
     * @JoinColumns({
     *   @JoinColumn(name="volunteer_id", referencedColumnName="id")
     * })
     */
    private $volunteer;

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
    public function getTimeSent()
    {
        return $this->timeSent;
    }

    /**
     * @param \DateTime $timeSent
     */
    public function setTimeSent(\DateTime $timeSent)
    {
        $this->timeSent = $timeSent;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage( $message )
    {
        $this->message = $message;
    }

    /**
     * @return \DateTime
     */
    public function getTimeSeen()
    {
        return $this->timeSeen;
    }

    /**
     * @param \DateTime $timeSeen
     */
    public function setTimeSeen(\DateTime $timeSeen)
    {
        $this->timeSeen = $timeSeen;
    }

    /**
     * @return User
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * @param User $fromUser
     */
    public function setFromUser(User $fromUser)
    {
        $this->fromUser = $fromUser;
    }

    /**
     * @return User
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * @param User $toUser
     */
    public function setToUser(User $toUser)
    {
        $this->toUser = $toUser;
    }

    /**
     * @return string
     */
    public function getTmpId()
    {
        return $this->tmp_id;
    }

    /**
     * @param string $tmp_id
     */
    public function setTmpId( $tmp_id )
    {
        $this->tmp_id = $tmp_id;
    }

    /**
     * @return Charity
     */
    public function getToCharity()
    {
        return $this->toCharity;
    }

    /**
     * @param Charity $toCharity
     */
    public function setToCharity( $toCharity )
    {
        $this->toCharity = $toCharity;
    }

    /**
     * @return Charity
     */
    public function getFromCharity()
    {
        return $this->fromCharity;
    }

    /**
     * @param Charity $fromCharity
     */
    public function setFromCharity(Charity $fromCharity)
    {
        $this->fromCharity = $fromCharity;
    }

    /**
     * @return CharityVolunteeringOpportunityVolunteer
     */
    public function getVolunteer()
    {
        return $this->volunteer;
    }

    /**
     * @param CharityVolunteeringOpportunityVolunteer $volunteer
     */
    public function setVolunteer(CharityVolunteeringOpportunityVolunteer $volunteer)
    {
        $this->volunteer = $volunteer;
    }
}
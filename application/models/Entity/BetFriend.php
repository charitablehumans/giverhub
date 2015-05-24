<?php
namespace Entity;

require_once(__DIR__.'/../FakeUser.php');

/**
 * BetFriend
 *
 * @Table(name="bet_friend", indexes={@Index(name="fk_bet_friend_bet_idx", columns={"bet_id"}), @Index(name="fk_bet_friend_user_idx", columns={"user_id"}), @Index(name="fk_bet_friend_donation_idx", columns={"donation_id"}), @Index(name="fk_bet_friend_charity_idx", columns={"charity_id"})})
 * @Entity
 */
class BetFriend extends BaseEntity implements \JsonSerializable {

    public function jsonSerialize() {
        $a = [
            'id' => $this->id,
            'user' => $this->user,
            'charity' => $this->charity,
        ];

        return $a;
    }
    static public $statuses = ['pending','requested','incomplete','accepted','rejected','request_rejected'];
    static public $claims = ['win', 'loss'];

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
     * @Column(name="claim", type="string", nullable=true)
     */
    private $claim;

    /**
     * @var Bet
     *
     * @ManyToOne(targetEntity="Bet")
     * @JoinColumns({
     *   @JoinColumn(name="bet_id", referencedColumnName="id")
     * })
     */
    private $bet;

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
     * @var \Entity\FacebookFriend
     *
     * @ManyToOne(targetEntity="\Entity\FacebookFriend")
     * @JoinColumns({
     *   @JoinColumn(name="facebook_friend_id", referencedColumnName="id")
     * })
     */
    private $facebookFriend;

    /**
     * @var Donation
     *
     * @ManyToOne(targetEntity="Donation")
     * @JoinColumns({
     *   @JoinColumn(name="donation_id", referencedColumnName="id")
     * })
     */
    private $donation;

    /**
     * @var Donation
     *
     * @ManyToOne(targetEntity="Donation")
     * @JoinColumns({
     *   @JoinColumn(name="other_donation_id", referencedColumnName="id")
     * })
     */
    private $otherDonation;

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
     * @var string
     *
     * @Column(name="status", type="string", nullable=false)
     */
    private $status = 'pending';


    /**
     * @var string
     *
     * @Column(name="email", type="string", nullable=true)
     */
    private $email;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Bet
     */
    public function getBet()
    {
        return $this->bet;
    }

    /**
     * @param Bet $bet
     */
    public function setBet(Bet $bet)
    {
        $this->bet = $bet;
    }

    /**
     * @return Charity
     */
    public function getCharity()
    {
        return $this->charity;
    }

    /**
     * @param Charity $charity
     */
    public function setCharity(Charity $charity)
    {
        $this->charity = $charity;
    }

    /**
     * @return Donation
     */
    public function getDonation()
    {
        return $this->donation;
    }

    /**
     * @param Donation $donation
     */
    public function setDonation(Donation $donation)
    {
        $this->donation = $donation;
    }

    /**
     * @return Donation
     */
    public function getOtherDonation()
    {
        return $this->otherDonation;
    }

    /**
     * @param Donation $donation
     */
    public function setOtherDonation(Donation $donation)
    {
        $this->otherDonation = $donation;
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
    public function setUser(User $user = null)
    {
        $this->user = $user;
        if ($user) {
            $this->facebookFriend = null;
        }
    }

    /**
     * @return \Entity\FacebookFriend
     */
    public function getFacebookFriend()
    {
        return $this->facebookFriend;
    }

    /**
     * @param \Entity\FacebookFriend $facebookFriend
     */
    public function setFacebookFriend(\Entity\FacebookFriend $facebookFriend = null)
    {
        $this->facebookFriend = $facebookFriend;
        if ($facebookFriend) {
            $this->user = null;
        }
    }


    /**
     * @param BaseEntity|User|FacebookFriend $friend
     *
     * @throws \Exception
     */
    public function setFriend(BaseEntity $friend) {
        if ($friend instanceof \Entity\User) {
            $this->setUser($friend);
        } elseif ($friend instanceof \Entity\FacebookFriend) {
            $this->setFacebookFriend($friend);
        } else {
            throw new \Exception('Invalid friend type' . get_class($friend));
        }
    }

    /**
     * @return FacebookFriend|User|string
     * @throws \Exception
     */
    public function getFriend() {
        $friend = $this->user ? $this->user : $this->facebookFriend;
        if (!$friend) {

            $friend = new \FakeUser($this->email);
            if ($friend === null) {
                throw new \Exception( 'Bet friend is invalid.. does not have user, email or facebookFriend' );
            }
        }
        return $friend;
    }

    /**
     * @param $status
     *
     * @throws \Exception
     */
    public function setStatus($status)
    {
        if (!in_array($status, self::$statuses)) {
            throw new \Exception('Invalid status: ' . $status);
        }
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $claim
     *
     * @throws \Exception
     */
    public function setClaim($claim)
    {
        if ($claim !== null && !in_array($claim, self::$claims)) {
            throw new \Exception('Bad claim: ' . $claim . ' bet_id: ' . $this->id);
        }
        $this->claim = $claim;
    }

    /**
     * @return string
     */
    public function getClaim()
    {
        return $this->claim;
    }

    public function getNotMyClaim(User $user) {
        if ($this->user == $user) {
            return $this->bet->getClaim();
        }
        return $this->getClaim();
    }

    public function getMyDonation(User $user) {
        if ($this->user == $user) {
            return $this->donation;
        } else {
            return $this->otherDonation;
        }
    }

    public function getNotMyDonation(User $user) {
        if ($this->user == $user) {
            return $this->otherDonation;
        } else {
            return $this->donation;
        }
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail( $email )
    {
        $this->email = $email;
    }

}

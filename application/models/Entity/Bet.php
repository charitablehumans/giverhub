<?php
namespace Entity;

/**
 * Bet
 *
 * @Table(name="bet")
 * @Entity @HasLifecycleCallbacks
 */
class Bet extends BaseEntity implements \JsonSerializable {

    public function jsonSerialize() {
        $a = [
            'id' => $this->id,
            'name' => $this->name,
            'terms' => $this->getTerms(['remove_ending_dot' => true]),
            'amount' => $this->amount,
            'determinationDate' => $this->getDeterminationDate()->format('m/d/y'),
            'user' => $this->user,
            'charity' => $this->charity,
        ];

        return $a;
    }

    /** @PrePersist */
    public function onPrePersist()
    {
        $this->createdDate = new \DateTime();
    }

    static public $statuses = ['draft', 'sent'];
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
     * @Column(name="`name`", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="`terms`", type="string", nullable=false)
     */
    private $terms;

    /**
     * @var integer
     *
     * @Column(name="amount", type="integer", nullable=false)
     */
    private $amount = 10;

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
     * @var Charity
     *
     * @ManyToOne(targetEntity="Charity")
     * @JoinColumns({
     *   @JoinColumn(name="charity_id", referencedColumnName="id")
     * })
     */
    private $charity;

    /**
     * @var \DateTime
     *
     * @Column(name="determination_date", type="date", nullable=false)
     */
    private $determinationDate;

    /**
     * @var string
     *
     * @Column(name="status", type="string", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate;

    /**
     * @var string
     *
     * @Column(name="claim", type="string", nullable=true)
     */
    private $claim;

    /**
     * @var integer
     *
     * @Column(name="open", type="integer", nullable=true)
     */
    private $open;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * @param int $open
     */
    public function setOpen($open)
    {
        $this->open = $open;
    }

    public function isOpen() {
        return (bool)$this->open;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime $createdDate
     */
    public function setCreatedDate(\DateTime $createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return \DateTime
     */
    public function getDeterminationDate()
    {
        return $this->determinationDate;
    }

    /**
     * @param \DateTime $determinationDate
     */
    public function setDeterminationDate(\DateTime $determinationDate)
    {
        $this->determinationDate = $determinationDate;
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }


	public function getUrl($full = true) {
		$relative = '/bet/'.$this->id;
		if ($full) {
			return base_url($relative);
		} else {
			return $relative;
		}
	}

	public function getLink($full = true) {
		return '<a href="'.$this->getUrl($full).'" title="'.htmlspecialchars($this->getName()).'">'.htmlspecialchars($this->getName()).'</a>';
	}

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param string $terms
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function getTerms($options = [])
    {
        if (isset($options['remove_ending_dot']) && $options['remove_ending_dot']) {
            return rtrim($this->terms, '. ');
        } else {
            return $this->terms;
        }
    }

    /**
     * @param User $user
     * @param      $links
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getSummary(User $user, $links) {
        /** @var \Base_Controller $CI */
        $CI =& get_instance();
        $my_dashboard = $CI->user->getId() == $user->getId();
        $you_him = $my_dashboard ? 'you' : 'him/her';
        $your_his = $my_dashboard ? 'your' : 'his/hers';

        $my_bet = $this->getUser() == $user;

        if ($this->hasFriend()) {
            $other_username = $links ? $this->getOther()->getLink() : htmlspecialchars($this->getOther()->getName());
        }
        $first_username = $links ? $this->getFirstUser()->getLink() : htmlspecialchars($this->getFirstUser()->getName());

        switch($this->status) {
            case 'draft':
                return 'Betting ' . $other_username . ' $'.$this->amount . ' that ' . htmlspecialchars($this->terms);
            case 'pending':
                if ($my_bet) {
                    return 'Betting ' . $other_username  . ' $'.$this->amount . ' that ' . htmlspecialchars($this->terms);
                } else {
                    return $first_username . ' is betting '.$other_username.' $'.$this->amount . ' that ' . htmlspecialchars($this->terms);
                }
            case 'rejected':
                if ($my_bet) {
                    return $other_username . ' rejected '.$your_his.' bet for $'.$this->amount . ' that ' . htmlspecialchars($this->terms);
                } else {
                    return ($my_dashboard ? 'You' : 'He/She') . ' rejected ' . $first_username . '\' bet for $'.$this->amount . ' that ' . htmlspecialchars($this->terms);
                }
            case 'incomplete':
                return 'Betting ' . ($my_dashboard ? $other_username : $first_username). ' $'.$this->amount . ' that ' . htmlspecialchars($this->terms);
            case 'confirmed':
                if ($my_bet) {
                    return 'Betting ' . $other_username . ' $'.$this->amount . ' that ' . htmlspecialchars($this->terms);
                } else {
                    return $first_username . ' is betting '.$you_him.' $'.$this->amount . ' that ' . htmlspecialchars($this->terms);
                }
            case 'over':
                if ($my_bet) {
                    return 'Bet ' . $other_username . ' $'.$this->amount . ' that ' . htmlspecialchars($this->terms);
                } else {
                    return $first_username . ' bet '.$you_him.' $'.$this->amount . ' that ' . htmlspecialchars($this->terms);
                }
            default:
                throw new \Exception('Unexpected status: ' . $this->status);
        }
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

    public function isTimeForDetermination() {
        return $this->getDeterminationDate() <= (new \DateTime());
    }

    /**
     * @return BetFriend[]
     */
    public function getFriends() {
        return BetFriend::findBy(['bet' => $this]);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isToUser(User $user) {
        foreach($this->getFriends() as $friend) {
            if ($friend->getUser() == $user) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param User $user
     *
     * @throws \Exception
     * @return BetFriend
     */
    public function getFriend(User $user, $throw = true) {
        foreach($this->getFriends() as $friend) {
            if ($friend->getUser() == $user) {
                return $friend;
            }
        }
        if ($throw) {
            throw new \Exception('User was not found in bet... bet_id: ' . $this->id . ' user-id: ' . $user->getId());
        } else {
            return false;
        }
    }

    public function getMyClaim(User $user) {
        if ($this->user == $user) {
            return $this->getClaim();
        }

        $friend = $this->getFriend($user, false);

        if (!$friend) {
            return null;
        }

        return $friend->getClaim();
    }

}

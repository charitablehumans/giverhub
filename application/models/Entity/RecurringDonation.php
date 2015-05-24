<?php
namespace Entity;

/**
 * RecurringDonation
 *
 * @Table(name="recurring_donation")
 * @Entity
 */
class RecurringDonation extends BaseEntity
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
     * @var integer
     *
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @var integer
     *
     * @Column(name="recurring_donation_id", type="integer", nullable=false)
     */
    private $recurring_donation_id;

    /**
     * @var float
     *
     * @Column(name="amount", type="float", nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @Column(name="npo_name", type="string", nullable=false)
     */
    private $npo_name;

    /**
     * @var string
     *
     * @Column(name="recur_type", type="string", nullable=false)
     */
    private $recur_type;

    /**
     * @var string
     *
     * @Column(name="status", type="string", length=45, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @Column(name="last_date", type="string", nullable=false)
     */
    private $last_date;

    /**
     * @var string
     *
     * @Column(name="next_date", type="string", nullable=true)
     */
    private $next_date;

    /**
     * @var integer
     *
     * @Column(name="notify", type="integer", nullable=false)
     */
    private $notify = 0;


    static public $recur_types = ['NotRecurring', 'Monthly', 'Quarterly', 'Annually'];

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $last_date
     */
    public function setLastDate($last_date)
    {
        $this->last_date = $last_date;
    }

    /**
     * @return string
     */
    public function getLastDate()
    {
        return $this->last_date;
    }

    /**
     * @param string $next_date
     */
    public function setNextDate($next_date)
    {
        $this->next_date = $next_date;
    }

    /**
     * @return string
     */
    public function getNextDate()
    {
        return $this->next_date;
    }

    /**
     * @param string $npo_name
     */
    public function setNpoName($npo_name)
    {
        $this->npo_name = $npo_name;
    }

    /**
     * @return string
     */
    public function getNpoName()
    {
        return $this->npo_name;
    }

    /**
     * @param string $recur_type
     */
    public function setRecurType($recur_type)
    {
        $this->recur_type = $recur_type;
    }

    /**
     * @return string
     */
    public function getRecurType()
    {
        return $this->recur_type;
    }

    /**
     * @param int $recurring_donation_id
     */
    public function setRecurringDonationId($recurring_donation_id)
    {
        $this->recurring_donation_id = $recurring_donation_id;
    }

    /**
     * @return int
     */
    public function getRecurringDonationId()
    {
        return $this->recurring_donation_id;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
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
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user_id = $user->getId();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return User::find($this->user_id);
    }

    /**
     * @param int $notify
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;
    }

    /**
     * @return int
     */
    public function getNotify()
    {
        return $this->notify;
    }
}

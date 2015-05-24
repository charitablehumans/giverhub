<?php

namespace Entity;

/**
 * Giverhub Cards
 *
 * @Table(name="givercard_transactions")
 * @Entity
 */
class GivercardTransactions extends BaseEntity {

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
     * @Column(name="from_user_id", type="integer", nullable=false)
     */
    private $from_user_id;

	/**
     * @var integer
     *
     * @Column(name="to_user_id", type="integer", nullable=true)
     */
    private $to_user_id;

	/**
     * @var integer
     *
     * @Column(name="to_user_fb_id", type="integer", nullable=true)
     */
    private $to_user_fb_id;

	/**
     * @var string
     *
     * @Column(name="to_email", type="string", nullable=true)
     */
    private $to_email;

	/**
     * @var integer
     *
     * @Column(name="cof_id", type="integer", nullable=false)
     */
    private $cof_id;

	/**
     * @var integer
     *
     * @Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

	/**
     * @var integer
     *
     * @Column(name="balance_amount", type="integer", nullable=false)
     */
    private $balance_amount;	

	/**
     * @var string
     *
     * @Column(name="message", type="string", nullable=false)
     */
    private $message;

	/**
     * @var string
     *
     * @Column(name="date_created", type="string", nullable=false)
     */
    private $date_created;

	/**
     * @var integer
     *
     * @Column(name="is_notified", type="integer")
     */
    private $is_notified = 0;

	/**
     * @var integer
     *
     * @Column(name="is_used", type="integer")
     */
    private $is_used = 0;

	public function getId() {
        return $this->id;
    }

	public function setFromUserId($from_user) {
        $this->from_user_id = $from_user->getId();
    }

	public function getFromUserId() {
        return $this->from_user_id;
    }

	/**
     * @return User
     */
    public function getFromUser()
    {
        return User::find($this->from_user_id);
    }

    public function isFrom(User $user) {
        return $this->from_user_id == $user->getId();
    }

 	public function isTo(User $user) {
        if ($this->isFacebookFriend()) {
            return false;
        }
        return $this->to_user_id == $user->getId();
    }

	/**
     * @return bool
     * @throws \Exception
     */
    public function isFacebookFriend() {
        if ($this->to_user_id) {
            return false;
        } elseif ($this->to_user_fb_id) {
            return true;
        }
        throw new \Exception('Could not determine if givercard is facebook-friend or not. givercard-id: ' . $this->id);
    }

	/**
     * @return FacebookFriend|User
     */
    public function getTo() {
        if ($this->isFacebookFriend()) {
            return $this->getToUserFb();
        } else {
            return $this->getToUser();
        }
    }
	
	/**
     * @return FacebookFriend
     */
    public function getToUserFb()     {
        return FacebookFriend::find($this->to_user_fb_id);
    }	

    public function setToUser(User $to_user)
    {
        $this->to_user_id = $to_user->getId();
    }

	public function setToUserId($to_user_id) {
        $this->to_user_id = $to_user_id;
    }

	public function getToUserId() {
        return $this->to_user_id;
    }

    /**
     * @return User
     */
    public function getToUser()
    {
        return User::find($this->to_user_id);
    }

    public function setToUserFbId($to_user_fb_id)
    {
        $this->to_user_fb_id = $to_user_fb_id;
    }

    public function getToUserFbId()
    {
        return $this->to_user_fb_id;
    }

    public function setToUserFb(FacebookFriend $to_user_fb)
    {
        $this->to_user_fb_id = $to_user_fb->getId();
    }

	public function setToEmail($to_email) {
        $this->to_email = $to_email;
    }

	public function getToEmail() {
        return $this->to_email;
    }

	public function setCofId($cof_id) {
        $this->cof_id = $cof_id;
    }

	public function getCofId() {
        return $this->cof_id;
    }

	public function setAmount($amount) {
        $this->amount = $amount;
    }

	public function getAmount() {
        return $this->amount;
    }

	public function setBalanceAmount($balance_amount) {
        $this->balance_amount = $balance_amount;
    }

	public function getBalanceAmount() {
        return $this->balance_amount;
    }

	public function setMessage($message) {
        $this->message = $message;
    }

	public function getMessage() {
        return $this->message;
    }

	public function setDateCreated($date_created) {
        $this->date_created = $date_created;
    }

	public function getDateCreated() {
        return $this->date_created;
    }

	public function setIsNotified($is_notified) {
        $this->is_notified = $is_notified;
    }

	public function getIsNotified() {
        return $this->is_notified;
    }

	public function setIsUsed($is_used) {
        $this->is_used = $is_used;
    }

	public function getIsUsed() {
        return $this->is_used;
    }

	static public function findOneBy(array $criteria, array $orderBy = null) {
        return \Base_Controller::$em->getRepository('\Entity\GivercardTransactions')->findOneBy($criteria, $orderBy);
    }

    public function updateGivercardAmount($donation_amount) {
        $currentAmount  = $this->getBalanceAmount();
        $donationAmount = $donation_amount;
        $balanceAmount = $currentAmount-$donationAmount;
        $this->setBalanceAmount($balanceAmount);
    }

}

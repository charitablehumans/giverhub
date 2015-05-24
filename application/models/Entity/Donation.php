<?php
namespace Entity;

/**
 * Donation
 *
 * @Table(name="donation")
 * @Entity
 */
class Donation extends BaseEntity
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
    private $userId;

    /**
     * @var integer
     *
     * @Column(name="charity_id", type="integer", nullable=false)
     */
    private $charityId;

    /**
     * @var integer
     *
     * @Column(name="charge_id", type="integer", nullable=false)
     */
    private $chargeId;

    /**
     * @var integer
     *
     * @Column(name="cof_id", type="integer", nullable=false)
     */
    private $cofId;

    /**
     * @var integer
     *
     * @Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @Column(name="date", type="string", length=50, nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @Column(name="instant", type="integer", nullable=false)
     */
    private $instant;

    /**
     * @var string
     *
     * @Column(name="recur_type", type="string", nullable=false)
     */
    private $recur_type;

	/**
     * @var integer
     *
     * @Column(name="givercard_transaction_id", type="integer", nullable=true)
     */
    private $givercardTransactionId = null;

    /**
     * @var integer
     *
     * @Column(name="paypal", type="integer", nullable=false)
     */
    private $paypal = 0;

    /**
     * @var string
     *
     * @Column(name="paypal_token", type="string", nullable=true)
     */
    private $paypal_token = null;

    /**
     * @var string
     *
     * @Column(name="dedication", type="string", nullable=false, length=128)
     */
    private $dedication = '';

    /**
     * @var integer
     *
     * @Column(name="hidden", type="integer", nullable=false)
     */
    private $hidden = 0;

    static public $recur_types = ['NotRecurring', 'Monthly', 'Quarterly', 'Annually'];

    public function getId() {
        return $this->id;
    }

    public function donate(User $user, $cofId, $amount, $charityId, $instant, $dedication, $recurType = 'NotRecurring', $giverCardTransaction = false, $giverCardTransactionId = null, $giverCardFromUser = null) {
        $em = \Base_Controller::$em;
        $charityRepo = $em->getRepository('Entity\Charity');
        /** @var Charity|null $charity */
        $charity = $charityRepo->find($charityId);

        //Check if donation is happening through givercard, if yes, compare current balanced givercard amount with donation amount
        if ( $giverCardTransaction && $giverCardTransactionId != "" && $giverCardFromUser) {

            $originalUser       = $giverCardFromUser;
            $givercardResultSet = GivercardTransactions::findOneBy(['id' => $giverCardTransactionId]);
            $balanceAmount      = $givercardResultSet->getBalanceAmount();
            $donationAmount     = $amount;

            if ( $balanceAmount < $donationAmount ) {
                throw new \Exception('GiverCard amount not sufficient for donation');
            }
        } else {
            $originalUser = $user;
        }

        $this->amount = $amount;
        $this->userId = $user->getId();
        $this->charityId = $charityId;
        $this->cofId = $cofId;
        $this->instant = $instant == 'instant-donation' ? 1 : 0;
        $this->recur_type = $recurType;
        $this->dedication = $dedication;


        if (!in_array($recurType, self::$recur_types)) {
            throw new \Exception('Bad recur_type: ' . $recurType);
        }

        $json = array('success' => false, 'msg' => 'Unexpected error!');
        try {
            $cof = \NetWorkForGood::getCOF($originalUser, $cofId);
            $r = \NetworkForGood::donate($originalUser, $charity, $cof, $amount, $dedication, $recurType);
            if (!$r instanceof \stdClass ||
                !isset($r->MakeCOFDonationResult) ||
                !$r->MakeCOFDonationResult instanceof \stdClass ||
                !isset($r->MakeCOFDonationResult->StatusCode)) {
                throw new \Exception('oops');
            }

            switch($r->MakeCOFDonationResult->StatusCode) {
                case 'Success':
                    $this->chargeId = $r->MakeCOFDonationResult->ChargeId;
                    $this->date = date('Y-m-d H:i:s');

                    //Code to update GiverCard amount from givercard_transaction table
					$json['givercardDonation'] = false;
                    if ( $giverCardTransaction && $giverCardTransactionId != "" && $giverCardFromUser) {

                        $this->givercardTransactionId = $giverCardTransactionId;
                        $result         = GivercardTransactions::findOneBy(['id' => $giverCardTransactionId]);
                        $currentAmount  = $result->getBalanceAmount();
                        $balanceAmount  = $currentAmount-$amount;
                        $result->setBalanceAmount($balanceAmount);
                        $result->setIsUsed(1);
                        $em->persist($result);
                        $em->flush($result);
						$json['givercardDonation'] = true;
					}

                    $em->persist($this);
                    $em->flush($this);

                    if ($recurType != 'NotRecurring' && isset($_POST['recurNotify']) && $_POST['recurNotify']) {
                        /** @var \Entity\RecurringDonation[] $recurring_donations */
                        $recurring_donations = \NetworkForGood::getRecurringDonations($user)['recurring_donations'];
                        foreach($recurring_donations as $recurring_donation) {
                            $recurring_donation->setNotify(1);
                            $em->persist($recurring_donation);
                            $em->flush($recurring_donation);
                        }
                    }

                    $user->addGiverHubScore('donation', $amount);
                    $user->addBadgePoints('donation', $charity, $amount*100);

                    $json['success'] = true;
                    $json['msg'] = 'Success';
                    break;
                default:
                    throw new \Exception($r->MakeCOFDonationResult->StatusCode);
            }

        } catch (\Exception $e) {
            $json['success'] = false;
            $json['msg'] = "We are sorry! We are unable to process your credit card transaction. Your donation may have failed if your billing information, such as your credit card number, address, expiration date or card security code, did not match exactly what is on file at your bank. If your credit card information was entered correctly and you are still unable to process your transaction, we recommend that you contact your bank or our partner Network for Good’s customer service team at donations@networkforgood.org.";
            $json['debug'] = $e->getMessage();
        }
        return $json;
    }

    public function initialize_paypal(User $user, Charity $charity, $amount, $dedication) {
        $this->amount = $amount;
        $this->userId = $user->getId();
        $this->charityId = $charity->getId();
        $this->cofId = 0;
        $this->chargeId = 0;
        $this->instant = 0;
        $this->recur_type = 'NotRecurring';
        $this->paypal = 1;
        $this->date = date('Y-m-d H:i:s');
        $this->dedication = $dedication;

        $em = \Base_Controller::$em;
        $em->persist($this);
        $em->flush($this);

        return \NetworkForGood::InitializePayPalCheckout($this);
    }

    public function getDate() {
        return $this->date;
    }

    private $charity;

    /**
     * @return \Entity\Charity
     */
    public function getCharity() {
        if (!$this->charity) {
            $this->charity = \Base_Controller::$em->getRepository('Entity\Charity')->find($this->charityId);
        }
        return $this->charity;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getUserId() {
        return $this->userId;
    }

    /**
     * @return User
     */
    public function getUserEntity() {
        $em = \Base_Controller::$em;
        $userRepo = $em->getRepository('\Entity\User');
        /** @var \Entity\User $user */
        $user = $userRepo->find($this->userId);
        return $user;
    }

    public function getUser() {
        return $this->getUserEntity();
    }

    public function getDateTime() {
        return new \DateTime($this->date);
    }

    /**
     * @return bool
     */
    public function getInstant() {
        return (bool)$this->instant;
    }

    /**
     * @param $instant
     */
    public function setInstant($instant) {
        $this->instant = $instant ? 1 : 0;
    }

    public function getRecurType() {
        return $this->recur_type;
    }

	public function getGivercardTransactionId() {
		return $this->givercardTransactionId;
	}

    /**
     * @param int $paypal
     */
    public function setPaypal($paypal)
    {
        $this->paypal = $paypal;
    }

    /**
     * @return int
     */
    public function getPaypal()
    {
        return $this->paypal;
    }

    /**
     * @param string $paypal_token
     */
    public function setPaypalToken($paypal_token)
    {
        $this->paypal_token = $paypal_token;
    }

    /**
     * @return string
     */
    public function getPaypalToken()
    {
        return $this->paypal_token;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @param int $chargeId
     */
    public function setChargeId($chargeId)
    {
        $this->chargeId = $chargeId;
    }

    /**
     * @return int
     */
    public function getChargeId()
    {
        return $this->chargeId;
    }

    public function isComplete() {
        return in_array($this->paypal, [0,2]);
    }

    /**
     * @return string
     */
    public function getDedication()
    {
        return $this->dedication;
    }

    /**
     * @param string $dedication
     */
    public function setDedication( $dedication )
    {
        $this->dedication = $dedication;
    }

    /**
     * @return int
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param int $hidden
     */
    public function setHidden( $hidden )
    {
        $this->hidden = $hidden;
    }

}
<?php

require_once(__DIR__ . '/Base_Controller.php');
require_once(__DIR__ . '/members.php');
require_once(__DIR__ . '/../helpers/NetworkForGood.php');

use \Entity\Charity;
use \Entity\User;
use \Entity\UserAddress;
use \Entity\GivercardTransactions;

class Donation extends Base_Controller {

    public function getCOFs() {
        ini_set('default_socket_timeout', 15);
        $json = array(
            'msg' => 'Unexpected problem, please try again later!',
            'success' => false,
            'cards' => array(),
        );
        if ($this->user) {
            try {
                $cofId = $this->user->getInstantDonationCofId();
                if (!$cofId) {
                    $cofId = $this->user->getPaymentCofId();
                }

                if ($this->session->userdata('cached-cofs')) {
                    $json = $this->session->userdata('cached-cofs');
                    if ($cofId) {
                        $json = json_decode($json, true);
                        foreach($json['cards'] as $k => $card) {
                            if ($card['COFId'] == $cofId) {
                                $json['cards'][$k]['selected'] = true;
                            } else {
                                $json['cards'][$k]['selected'] = false;
                            }
                        }
                        $json = json_encode($json);
                        $this->session->set_userdata('cached-cofs', $json);
                    }

                    echo $json;
                    return;
                }

                $r = NetworkForGood::getCOFs($this->user);

                if (empty($r)) {
                    $json['msg'] = 'empty response..';
                    $json['success'] = false;
                } else {
                    if (!$r instanceof stdClass || !isset($r->GetDonorCOFsResult) || !$r->GetDonorCOFsResult instanceof stdClass || !$r->GetDonorCOFsResult->Cards instanceof stdClass || !isset($r->GetDonorCOFsResult->StatusCode)) {
                        $json['success'] = false;
                        $json['msg'] = 'Unexpected problem, please try again later.';
                    } else {
                        switch ($r->GetDonorCOFsResult->StatusCode) {
                            case 'Success':
                                $json['success'] = true;
                                $json['msg'] = '';

                                $cards = $r->GetDonorCOFsResult->Cards;

                                $json['cards'] = isset($cards->COFRecord) ? $cards->COFRecord : array();
                                if ($json['cards'] instanceof stdClass) {
                                    // JESUS FUCKING CHRIST... it should be an array of stdClass instances, but if there's only one card, it's the instance returned.. we fix ...
                                    $json['cards'] = array($json['cards']); // see proof of this problem in debug2 below..
                                }

                                if ($cofId) {
                                    foreach($json['cards'] as $k => $card) {
                                        if ($card->COFId == $cofId) {
                                            $json['cards'][$k]->selected = true;
                                        } else {
                                            $json['cards'][$k]->selected = false;
                                        }
                                    }
                                }
                                $json['debug'] = GIVERHUB_DEBUG ? $r : null;
                                $json['debug2'] = GIVERHUB_DEBUG ? var_export(isset($cards->COFRecord) ? $cards->COFRecord : array(), true) : null;

                                $this->session->set_userdata('cached-cofs', json_encode($json));
                                break;
                            default:
                                $json['success'] = false;
                                $json['msg'] = 'Unexpected problem, please try again later.';
                                $json['r'] = $r;
                                break;
                        }
                    }
                }
            } catch (Exception $e) {
                $json = array(
                    'msg' => 'Unexpected problem, please try again later!',
                    'success' => false,
                    'cards' => array(),
                    'debug' => $e->getMessage(),
                );
            }
        } else {
            $json['msg'] = 'not logged in';
        }
        echo json_encode($json);
    }

    public function card_type($str) {
        if (!$str) {
            $this->form_validation->set_message('card_type', 'The card number is not a valid Visa, Mastercard or AmEx.');
            return false;
        }
        return true;
    }

    public function user_address_id($str) {
        if (!$str) {
            $this->form_validation->set_message('user_address_id', 'You need to select an address!');
            return false;
        }
        /** @var UserAddress $ua */
        $ua = UserAddress::find($str);
        if (!$ua) {
            $this->form_validation->set_message('user_address_id', 'The address has been deleted.');
            return false;
        }
        if ($this->user->getId() != $ua->getUserId()) {
            $this->form_validation->set_message('user_address_id', 'Address does not belong to you...?');
            return false;
        }
        return true;
    }

    public function addCard() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('newCardholderName', 'Card Holder Name', 'required');
        $this->form_validation->set_rules('newCardNumber', 'Card Number', 'required');
        $this->form_validation->set_rules('newExpiryMonth', 'Expiry Month', 'required');
        $this->form_validation->set_rules('newExpiryYear', 'Expiry Year', 'required');
        $this->form_validation->set_rules('newSecurityCode', 'Security Code', 'required');
        $this->form_validation->set_rules('newCardType', 'Card Type', 'callback_card_type');
        $this->form_validation->set_rules('userAddressId', 'Card Type', 'callback_user_address_id');
        $json = array(
            'success' => false,
            'msg' => 'Sorry, there was an unexpected problem, please try again later. Thank you for your patience.',
        );

        $debug = [];
        if ($this->user) {
            if ($this->form_validation->run()) {
                try {
                    /** @var UserAddress $ua */
                    $ua = UserAddress::findOneBy([
                            'user_id' => $this->user->getId(),
                            'id' => $this->input->post('userAddressId')
                        ]);
                    if (!$ua) {
                        throw new Exception('User Address could not be opened..uaid: ' . $this->input->post('userAddressId') . ' user_id: ' . $this->user->getId());
                    }

                    $r = NetworkForGood::addCard(
                        $this->user,
                        $this->input->post('newCardholderName'),
                        preg_replace('/[^0-9]/', '', $this->input->post('newCardNumber')),
                        $this->input->post('newExpiryMonth'),
                        $this->input->post('newExpiryYear'),
                        $this->input->post('newSecurityCode'),
                        $this->input->post('newCardType'),
                        $ua
                    );
                    if (empty($r)) {
                        $json['msg'] = 'empty response..';
                        $json['success'] = false;
                    } else {
                        if (!$r instanceof stdClass || !isset($r->CreateCOFResult) || !$r->CreateCOFResult instanceof stdClass || !isset($r->CreateCOFResult->StatusCode)) {
                            $json['success'] = false;
                            $json['msg'] = 'Unexpected problem, please try again at a later time. Thanks for showing patience';
                        } else {
                            switch ($r->CreateCOFResult->StatusCode) {
                                case 'Success':
                                    $json['success'] = true;
                                    $json['msg'] = '';
                                    $json['CofId'] = (int)$r->CreateCOFResult->CofId;
                                    if ($this->session->userdata('cached-cofs')) {
                                        $this->session->unset_userdata('cached-cofs');
                                    }
                                    break;
                                case 'ValidationFailed':
                                case 'ChargeFailed':
                                    $json['success'] = false;

                                    $messages = [];
                                    if (isset($r->CreateCOFResult->ErrorDetails) && isset($r->CreateCOFResult->ErrorDetails->ErrorInfo) && isset($r->CreateCOFResult->ErrorDetails->ErrorInfo->ErrData) && !empty($r->CreateCOFResult->ErrorDetails->ErrorInfo->ErrData)) {
                                        $messages[] = $r->CreateCOFResult->ErrorDetails->ErrorInfo->ErrData;
                                    }
                                    $messages[] = 'We are sorry! We are unable to process your credit card. It may have been declined if your billing information, such as your credit card number, address, expiration date or card security code, did not match exactly what is on file at your bank. If your credit card information was entered correctly and you are still unable to add your payment method, we recommend that you contact your bank or our partner Network for Good’s customer service team at donations@networkforgood.org.';
                                    $json['msg'] = join('. ', $messages);

                                    if (GIVERHUB_DEBUG) {
                                        $json['debug'] = $r;
                                    } else {
                                        $debug = $r;
                                    }
                                    break;
                                case 'AccessDenied':
                                default:
                                    $json['success'] = false;
                                    $json['msg'] = 'Unexpected problem, please try again later. Thank you for being patient.';
                                    if (GIVERHUB_DEBUG) {
                                        $json['debug'] = $r;
                                    } else {
                                        $debug = $r;
                                    }
                                    break;
                            }
                        }
                    }
                } catch (Exception $e) {
                    $json['success'] = false;
                    $json['message'] = 'Sorry, there was an unexpected problem while processing your request, please try again later. Thank you for showing patience.';
                    if (GIVERHUB_DEBUG) {
                        $json['e'] = $e->getMessage();
                        $json['trace'] = $e->getTraceAsString();
                    }
                }
            } else {
                $json['success'] = false;
                $json['msg'] = validation_errors();
            }
        } else {
            $json['msg'] = 'not logged in';
            $json['success'] = false;
        }

        if (GIVERHUB_DEBUG || (GIVERHUB_LIVE && in_array($this->user->getId(), [148,141,137]))) {
            $debugParams = print_r($_POST, true);
            $debugResponse = print_r($json, true);
            $debugDebug = print_r($debug, true);
            $CI =& get_instance();
            $CI->db->insert('debug', ['datablob' => date('Y-m-d H:i:s') . '|||' . 'from add card controller method|||'.$debugParams .'|||'. $debugResponse . '|||' . $debugDebug]);
        }

        echo json_encode($json);
    }

    public function removeCOF() {
        if (!$this->user) {
            throw new Exception('User not signed in.');
        }
        if (!isset($_POST['cofId'])) {
            throw new Exception('Invalid request, missing cofId.');
        }

        $found = false;
        /** @var \Entity\GivercardTransactions[] $givercards */
        $givercards = \Entity\GivercardTransactions::findBy(['cof_id' => $_POST['cofId']]);
        foreach($givercards as $givercard) {
            if ($givercard->getBalanceAmount()) {
                $found = true;
                break;
            }
        }

        if ($found) {
            echo json_encode(['success' => true, 'prevent_remove_msg' => 'The card that you are trying to remove is in use by givercards that you have created. Please contact admin@giverhub.com for support.']);
            return;
        }

        /** @var \Entity\CardOnFile $cardsOnFile */
        $cardsOnFile = \Entity\CardOnFile::findBy(['cofId' => $_POST['cofId']]);
        foreach($cardsOnFile as $cardOnFile) {
            /** @var \Entity\GivingPot[] $pots */
            $pots = \Entity\GivingPot::findBy(['cardOnFile' => $cardOnFile]);
            foreach($pots as $pot) {
                if ($pot->isPublished() && $pot->getAmountRemaining()) {
                    $found = true;
                    break;
                }
            }
        }

        if ($found) {
            echo json_encode(['success' => true, 'prevent_remove_msg' => 'The card that you are trying to remove is in use by a giving pot that you have created. Please contact admin@giverhub.com for support.']);
            return;
        }

        NetworkForGood::removeCard($this->user, $_POST['cofId']);

        if ($this->user->getPaymentCofId() == $_POST['cofId']) {
            $this->user->setPaymentCofId(null);
        }

        if ($this->user->getInstantDonationCofId() == $_POST['cofId']) {
            $this->user->setInstantDonationCofId(null);
        }

        \Base_Controller::$em->persist($this->user);
        \Base_Controller::$em->flush($this->user);

        echo json_encode(array('success' => true));
    }

    public function confirmDonation() {
        $json = array(
            'success' => false,
            'msg' => 'Unexpected problem processing your request. Please try again later. Thank you!',
        );
        if ($this->user) {
            $this->load->library('form_validation');

            $giverCardTransaction 	= false;
            $giverCardTransactionId = null;
            $giverCardFromUser 		= null;

            $this->form_validation->set_rules('amount', 'Amount', 'required|callback_validate_amount');
            $this->form_validation->set_rules('charityId', 'Charity ID', 'required|callback_validate_charity_id');

            if (@$_POST['givercard_transaction'] != "" && @$_POST['givercard_transaction_id'] != "" ) {

                $giverCardTransaction        = $_POST['givercard_transaction'];
                $giverCardTransactionId      = $_POST['givercard_transaction_id'];
                $givercardTransactionDetails = GivercardTransactions::findOneBy(array('id' => $giverCardTransactionId));
                $givercardFromUserId         = $givercardTransactionDetails->getFromUserId();
                $givercardBalanceAmount      = $givercardTransactionDetails->getBalanceAmount();
                $giverCardFromUser           = User::findOneBy(array('id' => $givercardFromUserId));

                $this->form_validation->set_rules('cofId', 'Card on file', 'required|callback_validate_cof_id_givercard['.$givercardFromUserId.']');

                //Check weather Givercard Amount is sufficient for current donation or not?
                $givercardResultSet = GivercardTransactions::findOneBy(['id' => $giverCardTransactionId]);
                $balanceAmount      = $givercardResultSet->getBalanceAmount();
                $donationAmount     = $this->input->post('amount');

                if ( $balanceAmount < $donationAmount ) {
                    echo json_encode([
                            'success' => false,
                            'msg' => 'This GiverCard is having only $'.$balanceAmount.', you can\'t donate more than it'
                        ]);
                    return;
                }

                //Check weather remaining amount after givercard donation is less than $10 or not
                $remainingAmountAfterDonation = $givercardBalanceAmount - $donationAmount;
                $totalAmount = $donationAmount + $remainingAmountAfterDonation;

                if ( $remainingAmountAfterDonation < 10  && $remainingAmountAfterDonation != 0 ) {
                    $totalUnsedAmount = 10 - $remainingAmountAfterDonation;
                    $newDonationAmount = $donationAmount - $totalUnsedAmount;

					if ($newDonationAmount < 10) {
						echo json_encode([
                            'success' => false,
                            'msg' => 'Donating $'.$donationAmount.' would leave $'.$remainingAmountAfterDonation.' unusable in your GiverCard. Please donate $'.$totalAmount.' to empty GiverCard']);
                    	return;

					} else {
						echo json_encode([
                            'success' => false,
                            'msg' => 'Donating $'.$donationAmount.' would leave $'.$remainingAmountAfterDonation.' unusable in your GiverCard. Please donate $'.$totalAmount.' to empty GiverCard or donate $'.$newDonationAmount.' so that at least $10 will left in you Givercard']);
                    	return;
					}
                    
                }
            } else {
                $this->form_validation->set_rules('cofId', 'Card on file', 'required|callback_validate_cof_id');
            }

            if (!$this->session->userdata('CSRFToken') || $this->input->post('CSRFToken') != $this->session->userdata('CSRFToken')) {
                $json['success'] = false;
                $json['msg'] = 'There was a problem checking the credentials of your request.';
                if (GIVERHUB_DEBUG) {
                    $json['debug'] = array('session-csrf-token' => @$this->session->userdata('CSRFToken'), 'post-csrf-token' => @$this->input->post('CSRFToken'));
                }
            } else {
                if ($this->form_validation->run()) {
                    $recurType = 'NotRecurring';
                    if (isset($_POST['recurType']) && in_array($_POST['recurType'], ['NotRecurring', 'Monthly', 'Quarterly', 'Annually'])) {
                        $recurType = $_POST['recurType'];
                    }

                    $bet = null;
                    $bet_friend = null;
                    if (is_numeric($_POST['bet_id']) && is_numeric($_POST['bet_friend_id'])) {
                        /** @var \Entity\Bet $bet */
                        $bet = \Entity\Bet::find($_POST['bet_id']);
                        if (!$bet) {
                            echo json_encode([
                                    'success' => false,
                                    'msg' => 'Something went wrong when trying to connect this donation to your bet! Bet could not be found'
                                ]);
                            return;
                        }

                        /** @var \Entity\BetFriend $bet_friend */
                        $bet_friend = \Entity\BetFriend::find($_POST['bet_friend_id']);
                        if (!$bet_friend) {
                            echo json_encode([
                                    'success' => false,
                                    'msg' => 'Something went wrong when trying to connect this donation to your bet! Bet Friend could not be found'
                                ]);
                            return;
                        }

                        if ($bet_friend->getBet() != $bet) {
                            echo json_encode([
                                    'success' => false,
                                    'msg' => 'Something went wrong when trying to connect this donation to your bet! Bet Friend did not match bet.',
                                ]);
                            return;
                        }

                        if ($bet_friend->getStatus() != 'accepted') {
                            echo json_encode([
                                    'success' => false,
                                    'msg' => 'Something went wrong when trying to connect this donation to your bet! Bet Friend has not accepted the bet.'
                                ]);
                            return;
                        }

                        if ($bet->getStatus() != 'sent') {
                            echo json_encode([
                                    'success' => false,
                                    'msg' => 'Something went wrong when trying to connect this donation to your bet! Bet had wrong status.'
                                ]);
                            return;
                        }
                        if ($bet->getMyClaim($this->user) != 'loss') {
                            echo json_encode([
                                    'success' => false,
                                    'msg' => 'Something went wrong when trying to connect this donation to your bet! You have not claimed to loose this bet.'
                                ]);
                            return;
                        }
                    }

                    if (!isset($_POST['dedication'])) {
                        $_POST['dedication'] = '';
                    }
                    if (strlen($_POST['dedication']) > 128) {
                        echo json_encode(['success' => false, 'msg' => 'Dedication is too long. Max 128 characters.']);
                        return;
                    }

                    $donation = new \Entity\Donation();
                    $cofId = $this->input->post('cofId') == 'instant-donation' ? $this->user->getInstantDonationCofId() : $this->input->post('cofId');

                    $json = $donation->donate(
                        $this->user,
                        $cofId,
                        $this->input->post('amount'),
                        $this->input->post('charityId'),
                        $this->input->post('cofId'),
                        $_POST['dedication'],
                        $recurType,
                        $giverCardTransaction,
                        $giverCardTransactionId,
                        $giverCardFromUser
                    );

                    $json['donation_id'] = $donation->getId();
                    $json['nonprofit_url'] = $donation->getCharity()->getUrl(true);

                    if ($json['success'] && $bet instanceof \Entity\Bet && $bet_friend instanceof \Entity\BetFriend) {
                        if ($this->user == $bet_friend->getUser()) {
                            $bet_friend->setDonation($donation);
                        } else {
                            $bet_friend->setOtherDonation($donation);
                        }
                        self::$em->persist($bet_friend);
                        self::$em->flush($bet_friend);
                        $json['bet_list'] = $this->load->view('bets/_bet-list', null, true);
                        $json['bet_info'] = $this->load->view('bets/_bet_info', ['bet' => $bet], true);
                    }
                } else {
                    $json['msg'] = 'Unexpected problem when processing the request. Please try again at a later time. Thanks!';
                    if (GIVERHUB_DEBUG) {
                        $json['debug'] = validation_errors();
                    }
                }
            }
        } else {
            $json['msg'] = 'You are not signed in';
        }

        echo json_encode($json);
    }

    function validate_cof_id($cofId) {
        try {
            $cofId = $cofId == 'instant-donation' ? $this->user->getInstantDonationCofId() : $cofId;
            $cof = NetworkForGood::getCOF($this->user, $cofId);
            if ($cof) {
                return true;
            }
            throw new Exception('could not get cof.');
        } catch (Exception $e) {
            $this->form_validation->set_message('validate_cof_id', 'cof-id: ' . $e->getMessage());
            return false;
        }
    }

	function validate_cof_id_givercard($cofId,$givercardFromUserId) {
		try {
            $cofId = $cofId == 'instant-donation' ? $this->user->getInstantDonationCofId() : $cofId;
			$user = User::findOneBy(array('id' => $givercardFromUserId));
            $cof = NetworkForGood::getCOF($user, $cofId);
            if ($cof) {
                return true;
            }
            throw new Exception('could not get cof.');
        } catch (Exception $e) {
            $this->form_validation->set_message('validate_cof_id_givercard', 'cof-id: ' . $e->getMessage());
            return false;
        }
	}

    function validate_amount($amount) {
        if (!is_numeric($amount)) {
            $this->form_validation->set_message('validate_amount', 'amount must be a number');
            return false;
        }
        if ($amount < 10) {
            $this->form_validation->set_message('validate_amount', 'amount must be atleast 10 dollars');
            return false;
        }
        return true;
    }

    function validate_charity_id($charityId) {
        $cRepo = self::$em->getRepository('Entity\Charity');
        /** @var Charity|null $c */
        $c = $cRepo->find($charityId);
        if ($c && $c->isReadyForDonation()) {
            return true;
        }
        $this->form_validation->set_message('validate_charity_id', 'charity id could not be found in database or the charity is missing neccessary information.');
        return false;
    }

    public function set_payment_cof() {
        if (!isset($_POST['cofId'])) {
            throw new Exception('Invalid request');
        }

        $cofId = $_POST['cofId'];

        if (!$this->user) {
            throw new Exception('Not signed in!');
        }

        $this->user->setPaymentCofId($cofId);

        if ($this->session->userdata('cached-cofs')) {
            $json = $this->session->userdata('cached-cofs');
            if ($cofId) {
                $json = json_decode($json, true);
                foreach($json['cards'] as $k => $card) {
                    if ($card['COFId'] == $cofId) {
                        $json['cards'][$k]['selected'] = true;
                    } else {
                        $json['cards'][$k]['selected'] = false;
                    }
                }
                $json = json_encode($json);
            }

            $this->session->set_userdata('cached-cofs', $json);
        }

        self::$em->persist($this->user);
        self::$em->flush($this->user);

        echo json_encode(array('success' => true));
    }

    public function set_instant_donation_cof() {
        if (!isset($_POST['cofId'])) {
            throw new Exception('Invalid request');
        }

        $cofId = $_POST['cofId'];

        if (!$this->user) {
            throw new Exception('Not signed in!');
        }

        $this->user->setInstantDonationCofId($cofId);

        self::$em->persist($this->user);
        self::$em->flush($this->user);
        echo json_encode(array('success' => true, 'showInstantDonationConfirmation' => !$this->user->getNoInstantDonationConfirmationMessage()));
    }

    public function dont_show_instant_donation_confirmation() {
        if (!isset($_POST['doit']) || $_POST['doit'] != 1) {
            throw new Exception('Invalid request.');
        }

        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        $this->user->setNoInstantDonationConfirmationMessage(1);
        self::$em->persist($this->user);
        self::$em->flush($this->user);

        echo json_encode(array('success' => true));
    }

    public function disable_instant_donations() {
        if (!isset($_POST['disable_instant_donations'])) {
            throw new Exception('Invalid request');
        }

        if (!$this->user) {
            throw new Exception('Not signed in!');
        }

        $this->user->setInstantDonationCofId(null);

        self::$em->persist($this->user);
        self::$em->flush($this->user);

        echo json_encode(array('success' => true));
    }

    public function payment_method() {
        if (!$this->user) {
            throw new Exception('not signed in.');
        }
        $cofId = $this->user->getInstantDonationCofId();
        if (!$cofId) {
            $cofId = $this->user->getPaymentCofId();
        }
        if (!$cofId) {
            echo json_encode(array('success' => true, 'cardData' => 'None.'));
        } else {
            $cof = NetworkForGood::getCOF($this->user, $cofId);
            if (!$cof) {
                echo json_encode(array('success' => true, 'cardData' => 'None.'));
            } else {
                $cof = $cof->_stdClass;
                echo json_encode(array('success' => true, 'cardData' => $cof));
            }
        }
    }

    public function initializePaypalDonation() {
        $json = array(
            'success' => false,
            'msg' => 'Unexpected problem processing your request. Please try again later. Thank you!',
        );
        if ($this->user) {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('amount', 'Amount', 'required|callback_validate_amount');
            $this->form_validation->set_rules('charityId', 'Charity ID', 'required|callback_validate_charity_id');

            if ($this->form_validation->run()) {


                $bet = null;
                $bet_friend = null;
                if (is_numeric($_POST['bet_id']) && is_numeric($_POST['bet_friend_id'])) {
                    /** @var \Entity\Bet $bet */
                    $bet = \Entity\Bet::find($_POST['bet_id']);
                    if (!$bet) {
                        echo json_encode([
                                'success' => false,
                                'msg' => 'Something went wrong when trying to connect this donation to your bet! Bet could not be found'
                            ]);
                        return;
                    }

                    /** @var \Entity\BetFriend $bet_friend */
                    $bet_friend = \Entity\BetFriend::find($_POST['bet_friend_id']);
                    if (!$bet_friend) {
                        echo json_encode([
                                'success' => false,
                                'msg' => 'Something went wrong when trying to connect this donation to your bet! Bet Friend could not be found'
                            ]);
                        return;
                    }

                    if ($bet_friend->getBet() != $bet) {
                        echo json_encode([
                                'success' => false,
                                'msg' => 'Something went wrong when trying to connect this donation to your bet! Bet Friend did not match bet.',
                            ]);
                        return;
                    }

                    if ($bet_friend->getStatus() != 'accepted') {
                        echo json_encode([
                                'success' => false,
                                'msg' => 'Something went wrong when trying to connect this donation to your bet! Bet Friend has not accepted the bet.'
                            ]);
                        return;
                    }

                    if ($bet->getStatus() != 'sent') {
                        echo json_encode([
                                'success' => false,
                                'msg' => 'Something went wrong when trying to connect this donation to your bet! Bet had wrong status.'
                            ]);
                        return;
                    }
                    if ($bet->getMyClaim($this->user) != 'loss') {
                        echo json_encode([
                                'success' => false,
                                'msg' => 'Something went wrong when trying to connect this donation to your bet! You have not claimed to loose this bet.'
                            ]);
                        return;
                    }
                }

                if (!isset($_POST['dedication'])) {
                    $_POST['dedication'] = '';
                }
                if (strlen($_POST['dedication']) > 128) {
                    echo json_encode(['success' => false, 'msg' => 'Dedication is too long. Max 128 characters.']);
                    return;
                }

                /** @var \Entity\Charity $charity */
                $charity = \Entity\Charity::find($this->input->post('charityId'));
                if (!$charity) {
                    throw new Exception('Invalid Charity: ' . $this->input->post('charityId'));
                }
                $donation = new \Entity\Donation();

                $json = $donation->initialize_paypal($this->user, $charity, $this->input->post('amount'), $_POST['dedication']);

                if ($json['success'] && $bet instanceof \Entity\Bet && $bet_friend instanceof \Entity\BetFriend) {
                    if ($this->user == $bet_friend->getUser()) {
                        $bet_friend->setDonation($donation);
                    } else {
                        $bet_friend->setOtherDonation($donation);
                    }
                    self::$em->persist($bet_friend);
                    self::$em->flush($bet_friend);
                    $json['bet_list'] = $this->load->view('bets/_bet-list', null, true);
                    $json['bet_info'] = $this->load->view('bets/_bet_info', ['bet' => $bet], true);
                }
            } else {
                $json['msg'] = 'Unexpected problem when processing the request. Please try again at a later time. Thanks!';
                if (GIVERHUB_DEBUG) {
                    $json['debug'] = validation_errors();
                }
            }

        } else {
            $json['msg'] = 'You are not signed in';
        }

        echo json_encode($json);
    }

    public function paypal_cancel() {
        // paypal_cancel?InitId=phMerp0Eh0E3kymaSemDMQ%3d%3d&token=EC-54F26882S8811832G
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }

        if (!isset($_GET['token'])) {
            throw new Exception('Token is missing');
        }

        /** @var \Entity\Donation $donation */
        $donation = \Entity\Donation::findOneBy(['paypal_token' => $_GET['token']]);

        if (!$donation) {
            throw new Exception('Could not load donation. token: ' . $_GET['token']);
        }

        if ($donation->getUser() != $this->user) {
            throw new Exception('Donation does not belong to user. token: ' . $_GET['token'] . ' donation-user: ' . $donation->getUserId() . ' signed-user: ' . $this->user->getId());
        }

        if ($donation->getPaypal() != 1) {
            throw new Exception('Donation has the wrong status: ' . $donation->getPaypal());
        }

        $donation->setPaypal(3);
        $donation->setDate(date('Y-m-d H:i:s'));
        self::$em->persist($donation);
        self::$em->flush($donation);


        $data['main_content'] = '/donation/paypal_cancel';

        $this->load->view('includes/user/template', $data);
    }

    public function paypal_confirm() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }

        if (!isset($_GET['token'])) {
            throw new Exception('Token is missing');
        }

        /** @var \Entity\Donation $donation */
        $donation = \Entity\Donation::findOneBy(['paypal_token' => $_GET['token']]);

        if (!$donation) {
            throw new Exception('Could not load donation. token: ' . $_GET['token']);
        }

        if ($donation->getUser() != $this->user) {
            throw new Exception('Donation does not belong to user. token: ' . $_GET['token'] . ' donation-user: ' . $donation->getUserId() . ' signed-user: ' . $this->user->getId());
        }

        if ($donation->getPaypal() != 1) {
            throw new Exception('Donation has the wrong status: ' . $donation->getPaypal());
        }

        $ret = \NetworkForGood::CompletePayPalCheckout($donation);

        $data['response'] = $ret;
        $data['donation'] = $donation;
        $data['main_content'] = '/donation/paypal_confirm';

        $this->load->view('includes/user/template', $data);
    }

    public function share() {
        if (!$this->user) {
            throw new Exception('user not signed in');
        }

        if (!isset($_POST['donation_id'])) {
            throw new Exception('missing donation_id');
        }

        /** @var \Entity\Donation $donation */
        $donation = \Entity\Donation::find($_POST['donation_id']);
        if (!$donation) {
            throw new Exception('Failed to open donation with id: ' . $_POST['donation_id']);
        }

        if ($donation->getUser()->getId() != $this->user->getId()) {
            throw new Exception('donation does not belong to user! signed-in: ' . $this->user->getId() . ' donation-user: ' . $donation->getUserId() . ' donation-id: ' . $donation->getId());
        }

        $donation->setHidden(0);

        self::$em->persist($donation);
        self::$em->flush($donation);

        echo json_encode(['success' => true]);
    }
}

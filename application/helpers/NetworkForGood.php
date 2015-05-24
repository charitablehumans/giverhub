<?php
require_once(__DIR__.'/../models/CardOnFile.php');

use \Entity\UserAddress;
use \Entity\User;
use \Entity\Charity;
use \Entity\Donation;
use \Entity\RecurringDonation;

class NetworkForGood {

	static public $cardTypes = array('Visa', 'Mastercard', 'Amex');

    static public $PartnerID;
    static public $PartnerPW;
    static public $PartnerSource;
    static public $PartnerCampaign;
    static public $url;
    static public $paypal_url;

    static public function init() {

        self::$PartnerID = 'G1V3RHU8';

        self::$PartnerSource = 'GHUB';
        self::$PartnerCampaign = 'DNT';

        if (isset($_SERVER) && is_array($_SERVER) && isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'giverhub.com') {
            self::$url = 'https://api.networkforgood.org/PartnerDonationService/DonationServices.asmx?wsdl';
            self::$paypal_url = 'https://api.networkforgood.org/PartnerDonationService/PayPal.asmx?wsdl';
            self::$PartnerPW = trim(file_get_contents('/var/nfgpwd.dontremove'));
            // stored outside source control for security purposes
            if (!self::$PartnerPW) {
                throw new Exception('Password not found!');
            }
        } else {
            self::$url = 'https://api-sandbox.networkforgood.org/PartnerDonationService/DonationServices.asmx?wsdl';
            self::$paypal_url = 'https://api-sandbox.networkforgood.org/PartnerDonationService/PayPal.asmx?wsdl';
            self::$PartnerPW = '5rEpuBr4';
        }

    }

    static public function getSoapClient() {
        static::init();
        ini_set('default_socket_timeout', 15);
        return @new SoapClient(self::$url, array('exceptions' => true, 'connection_timeout' => 10));
    }

    static public function getSoapClientPayPal() {
        static::init();
        ini_set('default_socket_timeout', 15);
        return @new SoapClient(self::$paypal_url, array('exceptions' => true, 'connection_timeout' => 10));
    }

	static public function donate(User $user, Charity $charity, CardOnFile $cof, $amount, $dedication, $recurType = 'NotRecurring') {
		$sc = static::getSoapClient();

        if (!in_array($recurType, Donation::$recur_types)) {
            throw new Exception('Invalid recurType: ' . $recurType);
        }

		$donationItem = array(
			'NpoEin' => $charity->getEin(),
			'Designation' => '',
			'Dedication' => $dedication,
			'donorVis' => 'ProvideAll',
			'ItemAmount' => $amount,
			'RecurType' => $recurType,
		);
		$donationItem['AddOrDeduct'] = 'Deduct';
		$donationItem['TransactionType'] = 'Donation';

		$totalChargeAmount = static::getTotalChargeAmount($cof, $donationItem, 'Deduct', 'Donation');

		$r = $sc->MakeCOFDonation(array(
			'PartnerID'                    => self::$PartnerID,
			'PartnerPW'                    => self::$PartnerPW,
			'PartnerSource'                => self::$PartnerSource,
			'PartnerCampaign'              => self::$PartnerCampaign,
			'DonationLineItems'            => array($donationItem),
			'TotalAmount'                  => $totalChargeAmount,
			'TipAmount'                    => '0',
			'PartnerTransactionIdentifier' => '',
			'DonorIpAddress'               => $_SERVER['REMOTE_ADDR'],
			'DonorToken'                   => $user->getId(),
			'COFId'                        => $cof->COFId,
		));

        $user->setRecurringDonationsCacheNeedsUpdate(1);
        \Base_Controller::$em->persist($user);
        \Base_Controller::$em->flush($user);

		return $r;
	}

	static public function getTotalChargeAmount(CardOnFile $cof, $donationItem, $addOrDeduct = 'Deduct', $transactionType = 'Donation') {
		$r = static::getFee($cof, $donationItem, $addOrDeduct, $transactionType);
		if (!$r instanceof stdClass ||
            !isset($r->GetFeeResult) ||
            !$r->GetFeeResult instanceof stdClass ||
            !isset($r->GetFeeResult->TotalChargeAmount)
        ) {
			throw new Exception('Problem retrieving charge amount');
		}
        if (isset($r->GetFeeResult->ErrorDetails) &&
            isset($r->GetFeeResult->ErrorDetails->ErrorInfo) &&
            isset($r->GetFeeResult->ErrorDetails->ErrorInfo->ErrCode) &&
            isset($r->GetFeeResult->ErrorDetails->ErrorInfo->ErrData) &&
            $r->GetFeeResult->ErrorDetails->ErrorInfo->ErrData) {
            throw new Exception('Problem getting fee: ' . $r->GetFeeResult->ErrorDetails->ErrorInfo->ErrData . ' ' . $r->GetFeeResult->ErrorDetails->ErrorInfo->ErrCode);
        }
		return $r->GetFeeResult->TotalChargeAmount;
	}

	static public function getFee(CardOnFile $cof, $donationItem, $addOrDeduct = 'Deduct', $transactionType = 'Donation') {
		$sc = static::getSoapClient();

		$donationItem['AddOrDeduct'] = $addOrDeduct;
		$donationItem['TransactionType'] = $transactionType;

		$r = $sc->GetFee(array(
			'TipAmount'                    => 0,
			'CardType'                     => $cof->CardType,
            'PartnerID'                    => self::$PartnerID,
            'PartnerPW'                    => self::$PartnerPW,
            'PartnerSource'                => self::$PartnerSource,
            'PartnerCampaign'              => self::$PartnerCampaign,
			'DonationLineItems'            => array($donationItem),
		));

		return $r;
	}

    /**
     * @param User $user
     * @param      $cofId
     *
     * @throws Exception
     * @returns CardOnFile
     */
	static public function getCOF(User $user, $cofId) {
		$r = static::getCOFs($user);
		if (!$r instanceof stdClass || !isset($r->GetDonorCOFsResult) || !$r->GetDonorCOFsResult instanceof stdClass || !$r->GetDonorCOFsResult->Cards instanceof stdClass || !isset($r->GetDonorCOFsResult->StatusCode)) {
			throw new Exception('Unexpected problem, please try again later.');
		} else {
			switch ($r->GetDonorCOFsResult->StatusCode) {
				case 'Success':

					$cards = $r->GetDonorCOFsResult->Cards;

					if (isset($cards->COFRecord)) {
                        if ($cards->COFRecord instanceof stdClass && $cards->COFRecord->COFId == $cofId) {
                            return new CardOnFile($cards->COFRecord);
                        } else {
                            foreach($cards->COFRecord as $cof) {
                                if (isset($cof->COFId) && $cof->COFId == $cofId) {
                                    return new CardOnFile($cof);
                                }
                            }
                        }
					}
					throw new Exception('could not find cofId: ' . $cofId);
				    break;
				default:
					throw new Exception('Unexpected problem, please try again later.');
					break;
			}
		}
	}
	
    static public function getCOFs(User $user) {
        $sc = static::getSoapClient();


        $params = [
            'PartnerID'                    => self::$PartnerID,
            'PartnerPW'                    => self::$PartnerPW,
            'PartnerSource'                => self::$PartnerSource,
            'PartnerCampaign'              => self::$PartnerCampaign,
            'DonorToken'					 => $user->getId(),
        ];
        $r = $sc->GetDonorCOFs($params);

        return $r;
	}


	static public function addCard(User $user, $newCardholderName, $newCardNumber, $newExpiryMonth, $newExpiryYear, $newSecurityCode, $newCardType, UserAddress $ua) {
		$sc = static::getSoapClient();

        $params = array(
            'PartnerID'                    => self::$PartnerID,
            'PartnerPW'                    => self::$PartnerPW,
            'PartnerSource'                => self::$PartnerSource,
            'PartnerCampaign'              => self::$PartnerCampaign,
            'DonorToken'                   => $user->getId(),
            'DonorFirstName'               => $user->getFname(),
            'DonorLastName'                => $user->getLname(),
            'DonorEmail'                   => $user->getEmail(),
            'DonorAddress1'                => $ua->getAddress1(),
            'DonorAddress2'                => $ua->getAddress2(),
            'DonorCity'                    => $ua->getCity()->getName(),
            'DonorState'                   => $ua->getState()->getName(),
            'DonorZip'                     => $ua->getZipCode(),
            'DonorCountry'                 => 'US',
            'DonorPhone'                   => $ua->getPhone(),
            'CardType'                     => $newCardType,
            'NameOnCard'                   => $newCardholderName,
            'CardNumber'                   => $newCardNumber,
            'ExpMonth'                     => $newExpiryMonth,
            'ExpYear'                      => $newExpiryYear,
            'CSC'                          => $newSecurityCode
        );

		$r = $sc->CreateCOF($params);

        if (GIVERHUB_DEBUG || (GIVERHUB_LIVE && in_array($user->getId(), [148,141,137]))) {
            $debugParams = print_r($params, true);
            $debugResponse = print_r($r, true);
            $CI =& get_instance();
            $CI->db->insert('debug', ['datablob' => date('Y-m-d H:i:s') . '|||' . $debugParams .'|||'. $debugResponse]);
        }

		return $r;
	}

    static public function addCardAndroid(User $user, $newCardholderName, $newCardNumber, $newExpiryMonth, $newExpiryYear, $newSecurityCode, $newCardType, $address1, $address2, $state, $city, $zip, $phone) {
        $sc = static::getSoapClient();

        $params = array(
            'PartnerID'                    => self::$PartnerID,
            'PartnerPW'                    => self::$PartnerPW,
            'PartnerSource'                => self::$PartnerSource,
            'PartnerCampaign'              => self::$PartnerCampaign,
            'DonorToken'                   => $user->getId(),
            'DonorFirstName'               => $user->getFname(),
            'DonorLastName'                => $user->getLname(),
            'DonorEmail'                   => $user->getEmail(),
            'DonorAddress1'                => $address1,
            'DonorAddress2'                => $address2,
            'DonorCity'                    => $city,
            'DonorState'                   => $state,
            'DonorZip'                     => $zip,
            'DonorCountry'                 => 'US',
            'DonorPhone'                   => $phone,
            'CardType'                     => $newCardType,
            'NameOnCard'                   => $newCardholderName,
            'CardNumber'                   => $newCardNumber,
            'ExpMonth'                     => $newExpiryMonth,
            'ExpYear'                      => $newExpiryYear,
            'CSC'                          => $newSecurityCode
        );

        $r = $sc->CreateCOF($params);

        if (GIVERHUB_DEBUG || (GIVERHUB_LIVE && in_array($user->getId(), [148,141,137]))) {
            $debugParams = print_r($params, true);
            $debugResponse = print_r($r, true);
            $CI =& get_instance();
            $CI->db->insert('debug', ['datablob' => date('Y-m-d H:i:s') . '|||' . $debugParams .'|||'. $debugResponse]);
        }

        return $r;
    }

    static public function removeCard(User $user, $cofId) {
        $sc = static::getSoapClient();

        $r = $sc->DeleteDonorCOF(array(
                                'PartnerID'                    => self::$PartnerID,
                                'PartnerPW'                    => self::$PartnerPW,
                                'PartnerSource'                => self::$PartnerSource,
                                'PartnerCampaign'              => self::$PartnerCampaign,
                                'DonorToken'                   => $user->getId(),
                                'COFId'                        => $cofId,
                            ));
        if (empty($r)) {
            throw new Exception('empty response');
        } else {
            if (!$r instanceof stdClass || !isset($r->DeleteDonorCOFResult) || !$r->DeleteDonorCOFResult instanceof stdClass || !isset($r->DeleteDonorCOFResult->StatusCode)) {
                throw new Exception('Unexpected response from NFG. dump: ' . print_r($r, true));
            } else {
                switch ($r->DeleteDonorCOFResult->StatusCode) {
                    case 'Success':
                        $CI =& get_instance();
                        if ($CI->session->userdata('cached-cofs')) {
                            $CI->session->unset_userdata('cached-cofs');
                        }
                        if ($user->getInstantDonationCofId() == $cofId) {
                            $user->setInstantDonationCofId(null);
                            \Base_Controller::$em->persist($user);
                            \Base_Controller::$em->flush($user);
                        }
                        break;
                    default:
                        throw new Exception('Bad response from NFG. ' . $r->DeleteDonorCOFResult->StatusCode . ' dump: ' . print_r($r, true));
                        break;
                }
            }
        }
        return $r;
    }

	static public function getDonationHistory(User $user) {
		$sc = static::getSoapClient();


		$r = $sc->GetDonorDonationHistory(array(
                                    'PartnerID'                    => self::$PartnerID,
                                    'PartnerPW'                    => self::$PartnerPW,
                                    'PartnerSource'                => self::$PartnerSource,
                                    'PartnerCampaign'              => self::$PartnerCampaign,
                                    'DonorToken'					 => $user->getId(),
							   ));

		return $r;
	}

    static public function getRecurringDonations(User $user) {

        /** @var RecurringDonation[] $recurring_donations */
        $recurring_donations = \Entity\RecurringDonation::findBy(['user_id' => $user->getId()]);
        $need_update = false;
        foreach($recurring_donations as $recurring_donation) {
            $next_date = $recurring_donation->getNextDate();
            if ($next_date !== null) {
                $next_date = new DateTime($next_date);
                $today = new DateTime(date('Y-m-d 23:59:59'));
                if ($next_date < $today) {
                    $need_update = true;
                    break;
                }
            }
        }
        
        if ($need_update || $user->getRecurringDonationsCacheNeedsUpdate()) {
            $sc = static::getSoapClient();

            $r = $sc->GetDonorRecurringDonations([
                    'PartnerID'       => self::$PartnerID,
                    'PartnerPW'       => self::$PartnerPW,
                    'PartnerSource'   => self::$PartnerSource,
                    'PartnerCampaign' => self::$PartnerCampaign,
                    'DonorToken'      => $user->getId(),
                ]);

            if (!$r instanceof stdClass) {
                return ['success' => false, 'msg' => 'Bad response from NetworkForGood...'];
            }
            if (!isset($r->GetDonorRecurringDonationsResult)) {
                return ['success' => false, 'msg' => 'Bad response from NetworkForGood..'];
            }
            if (!$r->GetDonorRecurringDonationsResult instanceof stdClass) {
                return ['success' => false, 'msg' => 'Bad response from NetworkForGood.'];
            }
            if (!isset($r->GetDonorRecurringDonationsResult->StatusCode)) {
                return ['success' => false, 'msg' => 'Bad response from NetworkForGood....'];
            }
            if ($r->GetDonorRecurringDonationsResult->StatusCode !== 'Success') {
                return ['success' => false, 'msg' => 'Bad response from NetworkForGood....StatusCode: ' . $r->GetDonorRecurringDonationsResult->StatusCode];
            }
            if (!isset($r->GetDonorRecurringDonationsResult->RecurringDonations)) {
                return ['success' => false, 'msg' => 'Bad response from NetworkForGood.....'];
            }
            if (!$r->GetDonorRecurringDonationsResult->RecurringDonations instanceof stdClass) {
                return ['success' => false, 'msg' => 'Bad response from NetworkForGood.....'];
            }

            $donations = null;

            if (!isset($r->GetDonorRecurringDonationsResult->RecurringDonations->RecurringDonationData)) {
                $donations = [];
            } else {
                if ($r->GetDonorRecurringDonationsResult->RecurringDonations->RecurringDonationData instanceof stdClass) {
                    $donations = [ $r->GetDonorRecurringDonationsResult->RecurringDonations->RecurringDonationData ];
                }
                if (is_array( $r->GetDonorRecurringDonationsResult->RecurringDonations->RecurringDonationData )) {
                    $donations = $r->GetDonorRecurringDonationsResult->RecurringDonations->RecurringDonationData;
                }
            }

            if (is_array($donations)) {
                /** @var stdClass[] $donations */
                /** @var \Entity\RecurringDonation[] $recurring_donations */
                $recurring_donations = [];
                foreach($donations as $donation) {

                    $recurring_donation = \Entity\RecurringDonation::findOneBy(['user_id' => $user->getId(), 'recurring_donation_id' => $donation->RecurringDonationId]);
                    if (!$recurring_donation) {
                        $recurring_donation = new \Entity\RecurringDonation();
                        $recurring_donation->setRecurringDonationId($donation->RecurringDonationId);
                        $recurring_donation->setUser($user);
                    }

                    $recurring_donation->setAmount($donation->Amount);
                    $recurring_donation->setStatus($donation->Status);
                    $recurring_donation->setLastDate((new \DateTime($donation->LastDonationDate))->format('Y-m-d'));
                    $recurring_donation->setNextDate($donation->Status == 'Live' ? (new \DateTime($donation->NextDonationDate))->format('Y-m-d') : null);
                    $recurring_donation->setNpoName($donation->NpoName);
                    $recurring_donation->setRecurType($donation->RecurType);

                    \Base_Controller::$em->persist($recurring_donation);
                    \Base_Controller::$em->flush($recurring_donation);
                    $recurring_donations[] = $recurring_donation;
                }

                $user->setRecurringDonationsCacheNeedsUpdate(0);
                \Base_Controller::$em->persist($user);
                \Base_Controller::$em->flush($user);
                return ['success' => true, 'recurring_donations' => $recurring_donations];
            }
            return ['success' => false, 'msg' => 'Unexpected Error When getting data from NetworkForGood.'];
        } else {

            return ['success' => true, 'recurring_donations' => $recurring_donations];
        }
    }

    static public function cancelRecurringDonation(User $user, \Entity\RecurringDonation $recurring_donation) {
        $sc = static::getSoapClient();

        $r = $sc->CancelRecurringDonation([
                'PartnerID'           => self::$PartnerID,
                'PartnerPW'           => self::$PartnerPW,
                'PartnerSource'       => self::$PartnerSource,
                'PartnerCampaign'     => self::$PartnerCampaign,
                'DonorToken'          => $user->getId(),
                'RecurringDonationId' => $recurring_donation->getRecurringDonationId(),
        ]);

        $user->setRecurringDonationsCacheNeedsUpdate(1);
        \Base_Controller::$em->persist($user);
        \Base_Controller::$em->flush($user);

        if (!$r instanceof stdClass) {
            return ['success' => false, 'msg' => 'Bad response from NetworkForGood...'];
        }
        if (!isset($r->CancelRecurringDonationResult)) {
            return ['success' => false, 'msg' => 'Bad response from NetworkForGood..'];
        }
        if (!$r->CancelRecurringDonationResult instanceof stdClass) {
            return ['success' => false, 'msg' => 'Bad response from NetworkForGood.'];
        }
        if (!isset($r->CancelRecurringDonationResult->StatusCode)) {
            return ['success' => false, 'msg' => 'Bad response from NetworkForGood....'];
        }
        if ($r->CancelRecurringDonationResult->StatusCode !== 'Success') {
            return ['success' => false, 'msg' => 'Bad response from NetworkForGood....StatusCode: ' . $r->CancelRecurringDonationResult->StatusCode];
        }

        return ['success' => true];
    }

    static public function InitializePayPalCheckout(Donation $donation) {
        if (!$donation->getId()) {
            throw new Exception('Donation must be saved before calling initialize paypal checkout. See PartnerTransactionIdentifier below.');
        }
        $sc = static::getSoapClientPayPal();

        $donationItem = array(
            'NpoEin' => $donation->getCharity()->getEin(),
            'Designation' => '',
            'Dedication' => $donation->getDedication(),
            'DonorNpoDisclosure' => 'ProvideAll',
            'DonationAmount' => $donation->getAmount(),
            'AddOrDeduct' => 'Deduct',
            'TransactionType' => 'Donation',
        );

        $r = $sc->InitializePayPalCheckout($req = array(
                'PartnerID'                     => self::$PartnerID,
                'PartnerPW'                     => self::$PartnerPW,
                'PartnerSource'                 => self::$PartnerSource,
                'PartnerCampaign'               => self::$PartnerCampaign,
                'DonorToken'                    => $donation->getUser()->getId(),
                'DonationLineItems'             => array($donationItem),
                'PartnerTransactionIdentifier'  => $donation->getId(),
                'TipAmount'                     => '0',
                'DonorIpAddress'                => $_SERVER['REMOTE_ADDR'],
                'DisplayName'                   => $donation->getCharity()->getName(),
                'ReturnURL'                     => base_url('/donation/paypal_confirm'),
                'CancelURL'                     => base_url('/donation/paypal_cancel'),
        ));

        if (
            $r instanceof stdClass &&
            isset($r->InitializePayPalCheckoutResult) &&
            $r->InitializePayPalCheckoutResult instanceof stdClass &&
            isset($r->InitializePayPalCheckoutResult->StatusCode) &&
            $r->InitializePayPalCheckoutResult->StatusCode === 'Success' &&
            isset($r->InitializePayPalCheckoutResult->RedirectURL) &&
            $r->InitializePayPalCheckoutResult->RedirectURL &&
            isset($r->InitializePayPalCheckoutResult->PPToken) &&
            $r->InitializePayPalCheckoutResult->PPToken
        ) {
            $donation->setPaypal(1);
            $donation->setPaypalToken($r->InitializePayPalCheckoutResult->PPToken);
            \Base_Controller::$em->persist($donation);
            \Base_Controller::$em->flush($donation);
            return [
                'success' => true,
                'RedirectURL' => $r->InitializePayPalCheckoutResult->RedirectURL,
                'PPToken' => $r->InitializePayPalCheckoutResult->PPToken
            ];
        }

        if ($donation->getUser()->getEmail() == 'robertanderssonwebdeveloper@gmail.com') {
            return ['success' => false, 'req' => $req, 'r' => $r];
        }
        return ['success' => false];
    }

    static public function CompletePayPalCheckout(Donation $donation) {
        $sc = static::getSoapClientPayPal();

        $r = $sc->CompletePayPalCheckout(array(
            'PartnerID'                     => self::$PartnerID,
            'PartnerPW'                     => self::$PartnerPW,
            'PartnerSource'                 => self::$PartnerSource,
            'PartnerCampaign'               => self::$PartnerCampaign,
            'PPToken'                       => $donation->getPaypalToken(),
        ));

        if (
            $r instanceof stdClass &&
            isset($r->CompletePayPalCheckoutResult) &&
            $r->CompletePayPalCheckoutResult instanceof stdClass &&
            isset($r->CompletePayPalCheckoutResult->StatusCode) &&
            $r->CompletePayPalCheckoutResult->StatusCode === 'Success' &&
            isset($r->CompletePayPalCheckoutResult->ChargeId ) &&
            $r->CompletePayPalCheckoutResult->ChargeId
        ) {
            $donation->setPaypal(2);
            $donation->setChargeId($r->CompletePayPalCheckoutResult->ChargeId);
            $donation->setDate(date('Y-m-d H:i:s'));
            \Base_Controller::$em->persist($donation);
            \Base_Controller::$em->flush($donation);
            return [
                'success' => true,
            ];
        }

        return ['success' => false];
    }
}
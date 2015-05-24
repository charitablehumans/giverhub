<?php

define('GOOGLE_API_VERSION', 3);

require_once(__DIR__ . '/Base_Controller.php');

use \Entity\User;

class Android extends Base_Controller {


    public function __construct() {
        parent::__construct();
    }

    public function auth($session) {
        if (!isset($_GET['auth_token'])) {
            echo json_encode(['success' => false, 'msg' => 'auth_token missing']);
            die;
        }

        $this->user = User::findOneBy(['auth_token' => $_GET['auth_token']]);

        if (!$this->user) {
            echo json_encode(['success' => false, 'msg' => 'auth_token invalid']);
            die;
        }
    }

    public function get_user() {
        $this->auth($this->session);

        echo json_encode(['success' => true, 'user' => $this->user]);
    }

    public function fb_access_token() {
        if (!isset($_POST['fb_access_token'])) {
            throw new Exception('missing fb_access_token');
        }

        define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__.'/../libraries/Facebook/');
        require_once(__DIR__.'/../libraries/Facebook/autoload.php');


        \Facebook\FacebookSession::setDefaultApplication($this->config->item('fb_app_id'), $this->config->item('fb_secret'));
        $session = new \Facebook\FacebookSession($_POST['fb_access_token']);

        try {
            $session->validate();

            $request = new \Facebook\FacebookRequest($session, 'GET', '/me');
            $response = $request->execute();
            $graphObject = $response->getGraphObject();
            /** @var \Facebook\GraphUser $user */
            $user = $graphObject->cast(\Facebook\GraphUser::className());

            $data['id'] = $user->getId();
            $data['email'] = $user->getProperty('email');
            $data['name'] = $user->getName();

            $data['data'] = $graphObject->asArray();

            $request = new \Facebook\FacebookRequest($session, 'GET', '/me/permissions');
            $response = $request->execute();
            $graphObject = $response->getGraphObject();
            $data['permissions'] = $graphObject->asArray();

            /** @var \Entity\User $user */
            $user = Common::create_member_fb($data, self::$em);
            $user->login($this->session);
            if ($user instanceof \Entity\User) {
                echo json_encode( [ 'success' => true, 'user' => $user ] );
            } else {
                echo json_encode( [ 'success' => false, 'msg' => $user ] );
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
            return;
        }
    }

    public function google_access_token() {
        if (!isset($_POST['google_access_token'])) {
            throw new Exception('missing google_access_token');
        }


        $client = new \Google_Client();
        $client->setApplicationName("GiverHub");

        $client->setClientId($this->config->item('google_client_id'));
        $client->setClientSecret($this->config->item('google_client_secret'));

        $client->setDeveloperKey($this->config->item('google_developer_key'));
        $client->setRedirectUri($this->config->item('google_redirect_uri'));

        $client->setScopes([
            'https://www.googleapis.com/auth/plus.login',
            'https://www.googleapis.com/auth/plus.me',
            'email',
        ]);

        try {
            $reqUrl = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token='.$_POST['google_access_token'];
            $req = new \Google_Http_Request($reqUrl);

            $io = $client->getIo();
            $response  = $io->executeRequest($req);


            $response = $response[0];

            $response = json_decode($response, true);
            if ($response === null) {
                throw new Exception('Failed to check token. response null');
            }

            if ($response['issued_to'] !== '466530377541-s7cfm34jpf818gbr0547pndpq9songkg.apps.googleusercontent.com') {
                throw new Exception('Invalid access token. issued to wrong client id: '. print_r($response, true));
            }

            if (!isset($response['user_id'])) {
                throw new Exception('Missing user_id');
            }

            if (!isset($response['email'])) {
                throw new Exception('Missing email');
            }

            $client->setAccessToken(json_encode([
                'access_token' => $_POST['google_access_token'],
                'created' => time(),
                'expires_in' => time()+60
            ]));
            $oauth2 = new \Google_Service_Oauth2($client);
            $user_info = $oauth2->userinfo->get();


            /** @var \Entity\User $user */
            $user = Common::create_member_google([
                'id' => $response['user_id'],
                'email' => $response['email'],
                'given_name' => $user_info['given_name'],
                'family_name' => $user_info['family_name'],
            ]);


            $user->login($this->session);
            if ($user instanceof \Entity\User) {
                echo json_encode( [ 'success' => true, 'user' => $user ] );
            } else {
                echo json_encode( [ 'success' => false, 'msg' => $user ] );
            }
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function nonprofit_feed($limit = 4) {
        $this->auth($this->session);

        if (isset($_GET['searchTerms']) && $_GET['searchTerms']) {
            $nonprofits = \Entity\Charity::findSphinx($_GET['searchTerms'], '', 'relevance',0,$limit)['content_relevance'];
            $nonprofits = array_values($nonprofits); // remove keys
        } else {
            $nonprofits = $this->user->getRecommendedCharities( $limit );
        }
        echo json_encode(['success' => true, 'nonprofits' => $nonprofits]);
    }

    public function donate() {
        $this->auth($this->session);

        if (!isset($_POST['nonprofitId'])) {
            throw new Exception('missing nonprofitId');
        }
        if (!isset($_POST['amount'])) {
            throw new Exception('missing amount');
        }
        if (!isset($_POST['cofId'])) {
            throw new Exception('missing cofId');
        }
        if (!isset($_POST['dedication'])) {
            $_POST['dedication'] = '';
        }

        if ($_POST['amount'] < 10) {
            throw new Exception('invalid amount: ' . $_POST['amount']);
        }

        /** @var \Entity\Charity $charity */
        $charity = \Entity\Charity::find($_POST['nonprofitId']);

        if (!$charity) {
            throw new Exception('could not load nonprofit: ' . $_POST['nonprofitId']);
        }

        $donation = new \Entity\Donation();
        $r = $donation->donate(
            $this->user,
            $_POST['cofId'],
            $_POST['amount'],
            $_POST['nonprofitId'],
            $_POST['cofId'],
            $_POST['dedication']
        );

        echo json_encode($r);
    }

    public function get_cofs() {
        $this->auth($this->session);

        $json = array(
            'msg' => 'Unexpected problem, please try again later!',
            'success' => false,
            'cards' => array(),
        );



        try {
            $cofId = $this->user->getPaymentCofId();

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

        echo json_encode($json);
    }

    public function add_payment_method() {
        $this->auth($this->session);

        if (!isset($_POST['address1'])) {
            throw new Exception('missing address1');
        }

        if (!isset($_POST['address2'])) {
            $_POST['address2'] = '';
        }

        if (!isset($_POST['city'])) {
            throw new Exception('missing city');
        }

        if (!isset($_POST['state'])) {
            throw new Exception('missing state');
        }

        if (!isset($_POST['phone'])) {
            throw new Exception('missing phone');
        }

        if (!isset($_POST['cardNumber'])) {
            throw new Exception('missing cardNumber');
        }

        if (!isset($_POST['cardSecurityCode'])) {
            throw new Exception('missing cardSecurityCode');
        }

        if (!isset($_POST['cardExpDate'])) {
            throw new Exception('missing cardExpDate');
        }

        if (!isset($_POST['cardType'])) {
            throw new Exception('missing cardType');
        }

        if (!isset($_POST['zip'])) {
            throw new Exception('missing zip');
        }

        if (!isset($_POST['nameOnCard'])) {
            throw new Exception('missing nameOnCard');
        }

        $expMonth = substr($_POST['cardExpDate'],0,2);
        $expYear = "20" . substr($_POST['cardExpDate'],3);


        $json = ['success' => false, 'msg' => 'Unexpected Error. Please try again.'];


        try {
            $r = NetworkForGood::addCardAndroid(
                $this->user,
                $_POST['nameOnCard'],
                $_POST['cardNumber'],
                $expMonth,
                $expYear,
                $_POST['cardSecurityCode'],
                $_POST['cardType'],
                $_POST['address1'],
                $_POST['address2'],
                $_POST['state'],
                $_POST['city'],
                $_POST['zip'],
                $_POST['phone']
            );
            if (empty($r)) {
                $json['msg'] = 'Unexpected problem, please try again at a later time. Thanks for showing patience.';
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

        echo json_encode($json);
    }

    public function get_states_and_cities() {
        $this->auth($this->session);

        $return = [];

        /** @var \Entity\CharityState[] $states */
        $states = \Entity\CharityState::findBy([],['name' => 'asc']);

        foreach($states as $state) {
            $arr = ['state' => $state->getName(), 'cities' => []];
            /** @var \Entity\CharityCity[] $cities */
            $cities = \Entity\CharityCity::findBy(['stateId' => $state->getId()], ['name' => 'asc']);
            foreach ($cities as $city) {
                $arr['cities'][] = $city->getName();
            }
            $return[] = $arr;
        }

        echo json_encode(['success' => true, 'states' => $return]);
    }
}

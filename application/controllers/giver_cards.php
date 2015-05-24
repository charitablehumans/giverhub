<?php

require_once(__DIR__ . '/Base_Controller.php');
require_once(__DIR__ . '/../helpers/NetworkForGood.php');
use \Entity\User;
use \Entity\GivercardTransactions;
use \Entity\Donation;

class Giver_cards extends \Base_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->config('general_website_conf');
    }

    public function index() {
		if (!$this->user) {
            header('location: /?redirect=/giver-cards');
            return;
        }
        $this->dashboard_dropdown = $this->user;
        $this->htmlTitle = 'Giver Cards';
		$order = array('id' => 'desc');
		$data['sent_givercards'] = GivercardTransactions::findBy(['from_user_id' => $this->user->getId()], $order);
		$givercardsById 	= GivercardTransactions::findBy(array('to_user_id' => $this->user->getId()), $order);
        $givercardsByEmail 	= GivercardTransactions::findBy(array('to_email' => $this->user->getEmail()), $order);
		$givercards 		= array_merge_recursive($givercardsById, $givercardsByEmail);
		$data['received_givercards'] = $givercards;

        $data['main_content'] = 'givercards/index';
        $this->load->view('includes/user/template', $data);
    }

	public function create() {
		if (!$this->user) {
            header('location: /');
            return;
        }
		$this->htmlTitle = 'Giver Cards';
        $data['main_content'] = 'givercards/create';
        $this->load->view('includes/user/template', $data);
	}

	public function view_givercard($giverCardId) {
		if (!$this->user) {
            redirect(base_url('?redirect=/giver-cards/view_givercard/'.$giverCardId));
            return;
        }

        /** @var \Entity\GivercardTransactions $givercard */
		$givercard 	= GivercardTransactions::find($giverCardId);
		if (!$givercard) {
			header('location: /giver-cards');
		}
		
		$data['givercard'] = $givercard;
		$data['main_content'] = 'givercards/view_givercard';
        $this->load->view('includes/user/template', $data);
	}

	public function confirmGiverCardDonation() {
		if (!$this->user) {
            header('location: /');
            return;
        }
        $CI =& get_instance();
        $this->load->config('mailsvariation');

        $json = array(
            'success' => false,
            'msg' => 'Unexpected problem processing your request. Please try again later. Thank you!',
        );
        if ($this->user) {            

            if (!$this->session->userdata('CSRFToken') || $this->input->post('CSRFToken') != $this->session->userdata('CSRFToken')) {
                $json['success'] = false;
                $json['msg'] = 'There was a problem checking the credentials of your request.';
                if (GIVERHUB_DEBUG) {
                    $json['debug'] = array('session-csrf-token' => @$this->session->userdata('CSRFToken'), 'post-csrf-token' => @$this->input->post('CSRFToken'));
                }
            } else {
                
				//store GiverCard entry in Givercard Transactions table
				
				$giverCardAmount 	= $_POST['amount'];
				$giverCardMessage 	= $_POST['message'];
				$cofId				= $_POST['cofId'];
				$giverCard 			= new \Entity\GivercardTransactions();
				$giverCard->setFromUserId($this->user);

				$fromUser = User::findOneBy(array('id' => $this->user));
				$fromUserFname = $fromUser->getFname();
				$fromUserLname = $fromUser->getLname();
				$numOfNonProfits = "2 millions";
				$facebookFriend = false;

				
				if ( isset($_POST['postUserId']) && $_POST['postUserId'] != "") {

					$fb_friend = \Entity\FacebookFriend::find($_POST['postUserId']);
		            if (!$fb_friend) {
						$json['success'] = false;
               			$json['msg'] = 'Could not find facebook_friend provided in user_id: ' . $_POST['postUserId'];
		            }
					$facebookFriend = true;
					$giverCard->setToUserFb($fb_friend);
            		$giverCard->setToUserId(null);

				} else if ( $_POST['existingUserEmail'] != "" && $_POST['existingUserId'] != "" ) {
				
					$giverCard->setToUserId($_POST['existingUserId']);
					$getDetails = User::findOneBy(array('id' => $_POST['existingUserId']));
					$to = $getDetails->getEmail();
					$userY = $getDetails->getFname();

				} else if ( $_POST['newUserEmail'] != "" ) {
					$giverCard->setToEmail($_POST['newUserEmail']);
					$to = $_POST['newUserEmail'];
					if ( $_POST['newUserName'] != "" ) {
						$userY = $_POST['newUserName'];
					} else {
						$userY = "User";
					}					
				}

				$giverCard->setCofId($cofId);
				$giverCard->setAmount($giverCardAmount);
				$giverCard->setBalanceAmount($giverCardAmount);
				$giverCard->setMessage($giverCardMessage);
				$giverCard->setDateCreated(date('Y-m-d H:i:s'));
				self::$em->persist($giverCard);
				self::$em->flush($giverCard);
				$insertedGiverCardId = $giverCard->getId();

				if (!$_POST['postUserId']) {
					$from = $CI->config->item('from');
					$companyname = $CI->config->item('companyname');
					$fromUserFullName = $fromUserFname.' '.$fromUserLname;
					$subject = str_replace("[user_x]",$fromUserFullName,$CI->config->item('givercard_receiver_subject'));
		            $baseUrl = $CI->config->item('base_url');
		            $mailHeader = "<img src='".$baseUrl."/images/logo_plus_givercard.png' width='100%' height='120px'>";
					$viewYourGiverCard = '<a href="'.$baseUrl.'giver-cards/view_givercard/'.$insertedGiverCardId.'">Your GiverCard</a>';
			
					$temp1 = array("[givercard_mail_header]",
		                            "[user_y]",
									"[user_x]",
									"[givercard_amount]",
									"[num_of_nonprofits]",
									"[user_x_fname]",
									"[givercard_message]",
									"[view_givercard_link]"
									);

					$temp2 = array($mailHeader,
		                            $userY,
									$fromUserFname.' '.$fromUserLname,
									$giverCardAmount,
									$numOfNonProfits,
									$fromUserFname,
									$giverCardMessage,
									$viewYourGiverCard);

					$body = str_replace($temp1,$temp2,$CI->config->item('givercard_receiver_email_body'));

					emailsending($from, $to, $subject, $body, $companyname, 1);
				}

				$json['success'] = true;
                $json['msg'] = 'GiverCard sent successfully.';
				$json['fb_friend'] = $facebookFriend;
				$json['givercard_id'] = $insertedGiverCardId;

            }
        } else {
            $json['msg'] = 'You are not signed in';
        }

        echo json_encode($json);
    }

	public function setGivercardPaymentCof() {
		if (!$this->user) {
            header('location: /');
            return;
        }
		$json = array(
            'success' => false,
            'msg' => 'Unexpected problem, please try again! Thank you for your patience.',
        );
		
		if ($this->user) {       
			
			$givercardTransactionId = $_POST['givercardId'];

			$cofResultRow = GivercardTransactions::findOneBy(array('id' => $givercardTransactionId));
			$json['success'] = true;
			$json['cof_id'] = $cofResultRow->getCofId();
			$json['balance_amount'] = $cofResultRow->getBalanceAmount();
			$json['msg'] = "You can proceed to donation";
            
        } else {
			$json['success'] = false;
            $json['msg'] = 'You are not signed in';
        }

		echo json_encode($json);
	}

	public function friendonfb($id) {

        $giverCardTransaction = \Entity\GivercardTransactions::find($id);
		if (!$giverCardTransaction) {
		    $this->giverhub_404('nonprofits/charity-404', 'Bet Not Found');
		    return;
		}

		$data['givercard'] = $giverCardTransaction;
		$data['main_content'] = 'givercards/facebook_og_page';

		$this->headerPrefix = 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# giverhub: http://ogp.me/ns/fb/giverhub#';
		$this->ogTitle = 'GiverCard';
		$this->ogType = 'giverhub:givercard';
		$this->ogDesc = $giverCardTransaction->getMessage(). ".\n\nGiverHub: Donate instantly, itemize automatically.";
		$this->ogImage = [(@$_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . @$_SERVER['SERVER_NAME'] . '/images/giverhub_givercard.jpg'];

		$this->extra_headers[] = '<meta property="og:amount" content="$20">';

		$this->load->view('includes/user/template', $data);
    }

}

<?php
require_once(__DIR__.'/Base_Controller.php');

use \Entity\User;

class Register extends \Base_Controller {
	/** @var Doctrine\ORM\EntityManager */
	public static $em;

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');

		$this->load->config('general_website_conf');

		$this->load->library('doctrine');

		self::$em = $this->doctrine->em;
	}

	function _remap($method)
	{
		if (method_exists($this, $method))
		{
			$this->$method();
		}
		else {
			$this->index($method);
		}
	}


	public function signup() {

		$this->form_validation->set_message('is_unique', 'The %s that you supplied is already registered!');


        if (isset($_POST['type']) && $_POST['type'] == 'no-name') {
            if (preg_match('/(.*)\@/', $_POST['email'], $matches)) {
                $username = $matches[1];

                $x = '';
                do {
                    $test = $username . $x;
                    $existing = \Entity\User::findBy(['username' => $test]);
                    if ($x !== 1) {
                        $x = 1;
                    } else {
                        $x++;
                    }
                } while($existing);
                $_POST['username'] = $test;
                $this->form_validation->set_rules('username', 'Username', 'required|callback_validate_username');
            }
        } else {
            $this->form_validation->set_rules( 'name', 'Name', 'required' );
            $this->form_validation->set_rules('username', 'Username', 'required|callback_validate_username');
        }


        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|matches[password2]');
        $this->form_validation->set_rules('password2', 'Repeat password', 'required');


        $response = array(
			'success' => false,
			'msg' => 'There was an unknown problem processing your request to join the site. Please try again later. Thank you for your patience.',
		);

        if ($this->form_validation->run()) {
            $query = Common::create_member();
            if ($query) {
                $response['success'] = true;
            }
        } else {
            $response['msg'] = strip_tags($this->load->view('/partials/_validation_errors_god_i_hate_codeigniter', null, true));
        }


		echo json_encode($response);
	}

    public function validate_username($username) {
        if (\Common::slug($username) != $username) {
            $this->form_validation->set_message('validate_username', 'Username is invalid. only lowercase letters, digits and - are allowed. no spaces. Must begin and end with letter or digit.');
            return false;
        }

        if (User::findOneBy(['username' => $username])) {
            $this->form_validation->set_message('validate_username', 'Username is already taken.');
            return false;
        }

        return true;
    }
        
	public function index() {
		redirect(base_url(), 'location', 301);
	}

	public function zipcode() {
		$zipcode = $this->input->get('zipcode');
		$zcRepo = self::$em->getRepository('Entity\ZipCode');

		/** @var Entity\ZipCode $zipcode  */
		$zipcode = $zcRepo->findOneBy(array('zip' => $zipcode));

		$return = array(
			'state' => '',
			'city' => '',
		);
		if ($zipcode) {
			$return['state'] = $zipcode->getStateId();
			$return['city'] = $zipcode->getCityId();
		}

		echo json_encode($return);
	}

	public function validate_zipcode($zipcode) {
		if (!is_numeric($zipcode) || strlen($zipcode) != 5) {
			$this->form_validation->set_message('validate_zipcode', 'The %s has to be a 5 digit number...');
			return false;
		}
		return true;
	}
	
	public function validate_state($stateId) {
		/** @var Doctrine\ORM\EntityRepository $stateRepo  */
		$stateRepo = self::$em->getRepository('Entity\CharityState');
		$state = $stateRepo->find($stateId);
		
		if (!$state) {
			$this->form_validation->set_message('validate_state', 'The %s is not valid.. please contact us if this issue remains.');
			return false;
		}
		return true;
	}

	public function validate_city($cityId) {
		/** @var Doctrine\ORM\EntityRepository $cityRepo  */
		$cityRepo = self::$em->getRepository('Entity\CharityCity');
		$city = $cityRepo->find($cityId);

		if (!$city) {
			$this->form_validation->set_message('validate_city', 'The %s is not valid.. please contact us if this issue remains.');
			return false;
		}
		return true;
	}

	public function activate_account(){
		if($this->user){
			redirect(base_url().'members/');
		}

        $activation_key = $this->uri->segment(3);
		$user = \Entity\User::validate_activation_key($activation_key);

        if ($user) {
	        $user->login($this->session);
        }
		redirect(base_url());
	}
	
	public function google_login() {
		$this->load->library('openid');

		try {
			# Change 'localhost' to your domain name.
			$openid = new LightOpenID($_SERVER['SERVER_NAME']);
			if(!$openid->mode) {
				$openid->identity = 'https://www.google.com/accounts/o8/id';
				redirect($openid->authUrl());
			} elseif($openid->mode == 'cancel') {
				echo 'User has canceled authentication!';
			} else {
				echo 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
			}
		} catch(ErrorException $e) {
			echo $e->getMessage();
		}
	}

}
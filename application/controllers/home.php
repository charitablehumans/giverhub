<?php

require_once(__DIR__ . '/Base_Controller.php');
require_once(__DIR__ . '/members.php');
require_once(__DIR__ . '/../helpers/NetworkForGood.php');

use \Entity\Charity;
use \Entity\User;
use \Entity\ChangeOrgPetition;
use \Entity\ClosedBetaSignup;
use \Entity\CharityState;
use \Entity\FacebookLike;


class Home extends Base_Controller {
    public $causes;
    
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if($this->user) {
            Members::index();
        } else {
            $this->landing_page();
        }
    }

    static $mixedPicks = array(
        'relevance' => array(
            'nonProfit' => 'relevance',
            'petition' => 'relevance',
            'items' => array()
        ),
        'name' => array(
            'nonProfit' => 'charity_name',
            'petition' => 'petition_title',
            'items' => array()
        ),
        'rating' => array(
            'nonProfit' => 'score',
            'petition' => 'signature_count',
            'items' => array()
        ),
    );

    public function landing_page() {

        if ($this->input->post('signed_request'))
        {
            throw new Exception('i didnt think this would happen!'); // robert
            $ret = $this->facebookSignedRequest();
            if (!$ret instanceof User) {
                $this->session->set_flashdata('flashError', $ret);
                redirect('/');
                return;
            } else {
                $ret->login($this->session);
                $data['triggerFbLogin'] = '';
                //print_r($this->session->all_userdata());
                $url = $this->session->userdata('sign_up_redir_url');
                redirect($url);
                return;
            }
        }

        $this->htmlTitle = 'Home';

        $data['main_content'] = 'home/dual_landing_page';
        $this->body_class = "dual-landing-page";

        $this->load->view('includes/user/template', $data);
    }

    public function search() {
        /** @var \Base_Controller $CI */
        $CI =& get_instance();

        if (isset($_POST['search_text'])) {
            $search_text = $_POST['search_text'];
        } else {
            $search_text = '';
        }

        set_time_limit(0);

        define('INDEX_CACHE_FILE', __DIR__.'/../cache/cached_index.html');
        define('INDEX_CACHE_TIME', 60*60); // 1 hour


        // The following variables are being used again way down..
        // look at end of this function.... all the way down.
        $use_cache_file = false;
        $cache_file_exists = false;
        $cache_file_is_old = false;
        if (!$CI->user && $_SERVER['REQUEST_METHOD'] == 'GET') {
            $use_cache_file = true;

            if (file_exists(INDEX_CACHE_FILE)) {
                $cache_file_exists = true;
                $cache_modified = filemtime(INDEX_CACHE_FILE);
                $cache_expires = $cache_modified + INDEX_CACHE_TIME;
                $time_now = time();
                if ($cache_expires > $time_now) {
                    echo preg_replace('#XX_FLASH_ERROR_YY#', $CI->session->flashdata('flashError'), preg_replace('#XX_CSRF_TOKEN_CSRF_TOKEN_YY#', $CI->session->userdata('CSRFToken'), file_get_contents(INDEX_CACHE_FILE)));
                    die;
                } else {
                    $cache_file_is_old = true;
                }
            } else {
                $cache_file_exists = false;
            }
        }

        // cache sphinx query
        if (function_exists('apc_fetch') && !$search_text && !$CI->input->post('search_zip')) { // if the search is empty.. default front page
            $data['views'] = apc_fetch('initial_query', $success);
            if (!$success) {
                unset($data['views']);  // unset views if it does not exist in cache
            }
        }

        if (!isset($data['views'])) {  // if views is not set from cache above.. then we need to do the query etc..
            $nonProfitSearchResults = Charity::findSphinx($search_text, $CI->input->post('search_zip'), 'relevance');
            $nonProfitCount = $nonProfitSearchResults['result_count'];

            $petitionSearchResults = ChangeOrgPetition::findSphinx($search_text, 'relevance');
            $petitionCount = $petitionSearchResults['result_count'];

            $mixedPicks = self::$mixedPicks;

            foreach($mixedPicks as $key => $mixedPick) {
                if ($key != 'relevance') continue;
                $nonProfit = $nonProfitSearchResults['content_'.$mixedPick['nonProfit']];
                $petition = $petitionSearchResults['content_'.$mixedPick['petition']];

                $mixed = array();
                do {

                    if ($nonProfit && count($mixed) < 40) {
                        $a = array_shift($nonProfit);
                        if ($a !== null) {
                            $mixed[] = $a;
                        }
                    }

                    if ($petition && count($mixed) < 40) {
                        $a = array_shift($petition);
                        if ($a !== null) {
                            $mixed[] = $a;
                        }
                    }

                } while(($petition || $nonProfit) && count($mixed) < 40);

                $mixedPicks[$key]['items'] = $mixed;
            }

            $data['views'] = array(
                'mixed' => array(
                    'id' => 'mixed',
                    'name' => 'Mixed',
                    'tabs' => array(
                        'relevance' => array(
                            'id' => 'relevance',
                            'name' => 'Relevance',
                            'items' => $mixedPicks['relevance']['items'],
                        ),
                        'name' => array(
                            'id' => 'name',
                            'name' => 'Name',
                            'items' => $mixedPicks['name']['items'],
                        ),
                        'rating' => array(
                            'id' => 'rating',
                            'name' => 'Rating',
                            'items' => $mixedPicks['rating']['items'],
                        ),
                    ),
                    'customTabs' => array(),
                    'result_count' => $nonProfitCount + $petitionCount,
                ),
                'non-profits' => array(
                    'id' => 'non-profits',
                    'name' => 'Non Profits',
                    'tabs' => array(
                        array(
                            'name' => 'Relevance',
                            'id' => 'relevance',
                            'items' => $nonProfitSearchResults['content_relevance'],
                        ),
                        array(
                            'name' => 'Name',
                            'id' => 'charity_name',
                            'items' => [], //$nonProfitSearchResults['content_charity_name'],
                        ),
                        array(
                            'name' => 'Rating',
                            'id' => 'score',
                            'items' => [], //$nonProfitSearchResults['content_popular'],
                        ),

                    ),
                    'customTabs' => array(
                        array(
                            'name' => 'Reviews',
                            'id' => 'reviews',
                            'items' => [], //$nonProfitSearchResults['content_reviews'],
                        ),
                        array(
                            'name' => 'Score',
                            'id' => 'score',
                            'items' => [], //$nonProfitSearchResults['content_score'],
                        ),
                        /*array(
                            'name' => 'Overall Efficiency',
                            'id' => 'overall_efficiency',
                            'items' => [], //$nonProfitSearchResults['content_overall_efficiency'],
                        ),
                        array(
                            'name' => 'Program Services',
                            'id' => 'program_services',
                            'items' => [], //$nonProfitSearchResults['content_program_services'],
                        ),
                        array(
                            'name' => 'Executive Productivity',
                            'id' => 'executive_productivity',
                            'items' => [], //$nonProfitSearchResults['content_executive_productivity'],
                        ),
                        array(
                            'name' => 'Highest Paid Officer',
                            'id' => 'highest_paid_officer',
                            'items' => [], //$nonProfitSearchResults['content_highest_paid_officer'],
                        ),*/
                    ),
                    'result_count' => $nonProfitCount,
                ),
                'petitions' => array(
                    'id' => 'petitions',
                    'name' => 'Petitions',
                    'tabs' => array(
                        array(
                            'name' => 'Relevance',
                            'id' => 'relevance',
                            'items' => $petitionSearchResults['content_relevance'],
                        ),
                        array(
                            'name' => 'Petition Title',
                            'id' => 'petition_title',
                            'items' => [], //$petitionSearchResults['content_petition_title'],
                        ),
                        array(
                            'name' => 'Signatures',
                            'id' => 'signature_count',
                            'items' => [], //$petitionSearchResults['content_signature_count'],
                        ),
                    ),
                    'customTabs' => array(),
                    'result_count' => $petitionCount,
                )
            );

            if (function_exists('apc_store') && !$search_text && !$CI->input->post('search_zip')) { // cache only when the request is empty / frontpage-default
                apc_store('initial_query', $data['views'], 60*60); // 1 hour
            }
        }

        $data['current_zip'] = $CI->input->post('search_zip');
        $data['current_text'] = $search_text;


        $CI->htmlTitle = 'Search';
        $data['main_content'] = 'home/search';

        $data['selected_view'] = "";
        if (isset($_POST['non-profit-tab']) && $_POST['non-profit-tab']!="") {
            $data['selected_view'] = "non-profits";
        }
        if (isset($_POST['petitions-tab']) && $_POST['petitions-tab']!="") {
            $data['selected_view'] = "petitions";
        }


        if ($use_cache_file && (!$cache_file_exists || $cache_file_is_old)) {
            $html = $CI->load->view('includes/user/template', $data, true);

            file_put_contents(INDEX_CACHE_FILE, preg_replace('#data-flash-error=".*"#', 'data-flash-error="XX_FLASH_ERROR_YY"', preg_replace('#data-csrf-token=".*"#', 'data-csrf-token="XX_CSRF_TOKEN_CSRF_TOKEN_YY"', $html)));

            echo $html;
        } else {
            $CI->load->view('includes/user/template', $data);
        }
    }

    public function more() {
        $mixedPicks = self::$mixedPicks;

        switch($_POST['search_type']) {
            case 'mixed':
                $mixedPick = $mixedPicks[$this->input->post('tab')];

                $nonProfitSearchResults = Charity::findSphinx($this->input->post('search_text'), $this->input->post('search_zip'), $mixedPick['nonProfit'], $this->input->post('offset'));
                $petitionSearchResults = ChangeOrgPetition::findSphinx($this->input->post('search_text'), $mixedPick['petition'], $this->input->post('offset'));


                $nonProfit = $nonProfitSearchResults['content_'.$mixedPick['nonProfit']];
                $petition = $petitionSearchResults['content_'.$mixedPick['petition']];

                $mixed = array();
                do {

                    if ($nonProfit && count($mixed) < 40) {
                        $a = array_shift($nonProfit);
                        if ($a !== null) {
                            $mixed[] = $a;
                        }
                    }

                    if ($petition && count($mixed) < 40) {
                        $a = array_shift($petition);
                        if ($a !== null) {
                            $mixed[] = $a;
                        }
                    }

                } while(($petition || $nonProfit) && count($mixed) < 40);

                $data['items'] = $mixed;

                break;
            case 'non-profits':
                $searchResults = Charity::findSphinx($this->input->post('search_text'), $this->input->post('search_zip'), $this->input->post('tab'), $this->input->post('offset'));
                $data['items'] = $searchResults['content_' . $this->input->post('tab')];
                break;
            case 'petitions':
                $searchResults = ChangeOrgPetition::findSphinx($this->input->post('search_text'), $this->input->post('tab'), $this->input->post('offset'));
                $data['items'] = $searchResults['content_' . $this->input->post('tab')];
                break;
            default:
                throw new Exception('bad search_type: ' . $_POST['search_type']);
                break;
        }

        if ($data['items']) {
            $this->load->view('/home/search-items', $data);
        } else {
            echo 'no-more';
        }
    }

    public function closed_beta_signup() {
        if (!CLOSED_BETA) {
            throw new Exception('Not closed beta.');
        }
        if (!isset($_POST['email']) || !$_POST['email']) {
            throw new Exception('invalid request, missing email.');
        }

        $this->load->helper('email');

        if (!valid_email($_POST['email'])) {
            echo json_encode(array('success' => false, 'msg' => 'Email is invalid.'));
            return;
        }

        $existing = ClosedBetaSignup::findOneBy(array('email' => $_POST['email']));
        if ($existing) {
            echo json_encode(array('success' => false, 'msg' => 'Email already signed up!'));
            return;
        }
        $signup = new ClosedBetaSignup();
        $signup->setEmail($_POST['email']);
        $signup->setApproved(0);
        $signup->setSignupDate(date('Y-m-d H:i:s'));
        \Base_Controller::$em->persist($signup);
        \Base_Controller::$em->flush($signup);
        echo json_encode(array('success' => true));
    }

    public function feedback() {
        if (!isset($_POST['text'])) {
            throw new Exception('Invalid request. no Text.');
        }
        if (strlen($_POST['text']) < 5) {
            throw new Exception('Invalid request. Text too short. len: ' . strlen($_POST['text'] . ' text: \''.$_POST['text'].'\''));
        }
        if (!isset($_POST['url'])) {
            throw new Exception('Invalid request. no url.');
        }

        $body = nl2br(print_r(array(
                             'text' => $_POST['text'],
                             'url' => $_POST['url'],
                             'email' => $this->user ? $this->user->getEmail() : null,
                             'user-id' => $this->user ? $this->user->getId() : null,
                             'f33db4ck' => 'f33db4ck',
                        ),true));

        $ret = emailsending('admin@giverhub.com', 'admin@giverhub.com', 'Feedback', $body, 'GiverHub, Inc.');

        if ($ret !== true) {
            throw new Exception('emailsending returned: ' . $ret);
        }
        echo json_encode(array('success' => true));
    }

    function friends() {
        if (!$this->user) {
            header('location: /');
            return;
        }

        $data['fbFriends'] = array();

        $data['main_content'] = 'home/friends';
        $this->htmlTitle = 'Find Friends';

        if ($this->session->userdata('fbAccessToken')) {
            define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__.'/../libraries/Facebook/');
            require_once(__DIR__.'/../libraries/Facebook/autoload.php');
            \Facebook\FacebookSession::setDefaultApplication($this->config->item('fb_app_id'), $this->config->item('fb_secret'));
            $session = new \Facebook\FacebookSession($this->session->userdata('fbAccessToken'));
            $request = new \Facebook\FacebookRequest(
                $session,
                'GET',
                '/me/friends'
            );
            $response = $request->execute();
            $graphObject = $response->getGraphObject();
            $request = new \Facebook\FacebookRequest(
                $session,
                'GET',
                '/me/permissions'
            );
            $response = $request->execute();
            $graphObject = $response->getGraphObject();

            $fbRequest = @file_get_contents('https://graph.facebook.com/me/friends?access_token=' . $this->session->userdata('fbAccessToken'));
            if ($fbRequest === false) {
                $data['fbError'] = true;
            } else {
                $json = json_decode($fbRequest, true);
                while (isset($json['data']) && is_array($json['data']) && !empty($json['data'])) {
                    $data['fbFriends'] = array_merge($data['fbFriends'], $json['data']);
                    if (isset($json['paging']['next'])) {
                        $fbRequest = @file_get_contents($json['paging']['next']);
                        if ($fbRequest === false) {
                            $data['fbError'] = true;
                            break;
                        } else {
                            $json = json_decode($fbRequest, true);
                        }
                    } else {
                        break;
                    }
                }
            }
        }
        if (!empty($data['fbFriends'])) {
            $data['fbAlready'] = array();
            foreach ($data['fbFriends'] as $k => $fbFriend) {
                $user = User::findByFbUserId($fbFriend['id'], self::$em);
                if ($user) {
                    unset($data['fbFriends'][$k]);
                    $fbFriend['userId'] = $user->getId();
                    $this->db->select('count(id) as cnt');
                    $q = $this->db->get_where('followers', array(
                                                           'follower_user_id' => $this->user->getId(),
                                                           'followed_user_id' => $user->getId(),
                                                      ));
                    $res = $q->result_array();
                    $fbFriend['following'] = (bool)$res[0]['cnt'];
                    $data['fbAlready'][] = $fbFriend;
                }
            }
        }
        $data['em'] = self::$em;

        if ($this->input->get('fbReloginAttempt')) {
            $data['attemptedFbRelogin'] = true;
        }

        if (isset($data['fbError']) && !isset($data['attemptedFbRelogin'])) {
            $data['triggerFbLogin'] = '/home/friends/?fbReloginAttempt=1';
        }
        $data['google'] = false;
        $this->load->view('includes/user/template', $data);
    }

    function fb_profile_image(){
        $file = "images/fb_profile_images/".$this->input->post('fbId').'.jpg';
        $image_url = file_get_contents($this->input->post('pro_image_url')); // Read the file's contents
        file_put_contents($file, $image_url);
        if(file_exists($file))
            echo $file;
    }

    function fb_profile_image_delete(){
        if (!$this->user) {
            throw new Exception('need to be signed in.');
        }
         $file = "images/fb_profile_images/".$this->input->post('fbId').'.jpg';
         if(unlink($file))
             echo "success";

        if (@$_POST['success']) {
            $this->user->addGiverHubScore('invite');
        }
    }


    function display_gmail_photo() {
        if (!isset($_GET['href'])) {
            throw new Exception('bad request. no href.' . print_r($_REQUEST, true));
        }
        if (!$this->session->userdata('google-access-token')) {
            throw new Exception('access token has expired.');
        } else {
            $accesstoken = $this->session->userdata('google-access-token');
        }

        //get the last-modified-date of this very file
        $lastModified=filemtime(__FILE__);
        //get a unique hash of this file (etag)
        $etagFile = md5($_GET['href']);
        //get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
        $etagHeader=(isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

        //set last-modified header
        header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
        //set etag-header
        header("Etag: $etagFile");
        //make sure caching is turned on
        header('Cache-Control: public');

        //check if page has changed. If not, send 304 and exit
        if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])==$lastModified || $etagHeader == $etagFile)
        {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }


        $res = @file_get_contents($_GET['href'].'&oauth_token=' . $accesstoken);
        if (!$res) {

            $res = file_get_contents(__DIR__.'/../../assets/images/contact_header.png');
        }
        header('Content-Type: image/png');
        echo $res;
    }

    function grab_gmail_contact() {
        $max_results = 500;

        if (false && $this->session->userdata('google-access-token')) {
            $accesstoken = $this->session->userdata('google-access-token');
        } else {
            $client_id = $this->config->item('google_client_id');
            $client_secret = $this->config->item('google_client_secret');
            $redirect_uri = $this->config->item('google_redirect_uri');


            $auth_code = $_GET["code"];
            $fields = array(
                'code' => urlencode($auth_code),
                'client_id' => urlencode($client_id),
                'client_secret' => urlencode($client_secret),
                'redirect_uri' => urlencode($redirect_uri),
                'grant_type' => urlencode('authorization_code')
            );
            $post = '';
            foreach ($fields as $key => $value) {
                $post .= $key . '=' . $value . '&';
            }
            $post = rtrim($post, '&');

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
            curl_setopt($curl, CURLOPT_POST, 5);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            $result = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($result);
            $accesstoken = $response->access_token;
            $this->session->set_userdata('google-access-token', $accesstoken);
        }


        $client = new \Google_Client();
        $client->setApplicationName("GiverHub");

        $client->setClientId($this->config->item('google_client_id'));
        $client->setClientSecret($this->config->item('google_client_secret'));

        $client->setDeveloperKey($this->config->item('google_developer_key'));
        $client->setRedirectUri($this->config->item('google_redirect_uri'));

        $reqUrl = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token='.$_POST['google_access_token'];
        $req = new \Google_Http_Request($reqUrl);

        $io = $client->getIo();
        $response  = $io->executeRequest($req);


        $url = 'https://www.google.com/m8/feeds/contacts/default/full?alt=json&v=3.0&max-results=' . $max_results . '&oauth_token=' . $accesstoken;
        $xmlresponse = file_get_contents($url);


        $repose_object = json_decode($xmlresponse);

        if ($xmlresponse === false || ((strlen(stristr($xmlresponse, 'Authorization required')) > 0) && (strlen(stristr($xmlresponse, 'Error ')) > 0))) {
            echo "<h2>OOPS !! Something went wrong. Please try reloading the page.</h2>";
            exit();
        }

        $entry = $repose_object->feed->entry;
        $result = array();
        $i = 0;
        foreach ($entry as $each_entry) {
            if (is_object($each_entry) && isset($each_entry->{'gd$email'}) && isset($each_entry->{'gd$email'}[0]) && isset($each_entry->{'gd$email'}[0]->address)) {
                if (!isset($result[$i])) {
                    $result[$i] = new stdClass;
                }
                $result[$i]->address = $each_entry->{'gd$email'}[0]->address;
            }
            if (is_object($each_entry) && isset($each_entry->title) && is_object($each_entry->title) && isset($each_entry->title->{'$t'}) && $each_entry->title->{'$t'} != "") {
                if (!isset($result[$i])) {
                    $result[$i] = new stdClass;
                }
                $result[$i]->username = $each_entry->title->{'$t'};
            } else {
                if (!isset($result[$i])) {
                    $result[$i] = new stdClass;
                }
                $result[$i]->username = "Unknown Name";
            }
            if (is_array($each_entry->link) && $each_entry->link) {
                foreach($each_entry->link as $link) {
                    if ($link->type == 'image/*') {
                        $result[$i]->photo_href = $link->href;
                    }
                }
            }
            $i++;
        }

        $data['gmail_contacts'] = $result;

        $data['main_content'] = 'home/friends';
        $this->htmlTitle = 'Find Friends';
        $data['google'] = true;
        $this->load->view('includes/user/template', $data);
    }


    function send_invitation_gmail()
    {
        if (!$this->user) {
            throw new Exception('Need to be signed in');
        }
        $email_address = array();
        if (strpos($_POST['email'], ",") !== false) {
            $email_address = explode(",", $_POST['email']);
            array_pop($email_address);
        } else {
            array_push($email_address, trim($_POST['email']));
        }

        foreach ($email_address as $each_address) {
            $body = "";
            $body = $body . "Hi, <br/>";
            $body = $body . $this->user->getName() . " has invited you to join giverhub.<br/>";
            $body = $body . "Go to this link to have an account : <a href='" . $_SERVER['SERVER_NAME'] . "/register'>Register</a>.<br/>";
            $body = $body . "<br/>";
            $body = $body . "Thanks <br/>";
            $body = $body . "Giverhub Team <br/>";

            emailsending($this->config->item('no_reply_email'), trim($each_address), "Invitation", $body, "Giverhub", 1);
        }

        $this->user->addGiverHubScore('invite');
        echo "success";
    }

    public function validate_credentials() {
        $user = User::checkCredentials($this->input->post('username'), $this->input->post('password'));

        $json = [];
        $level = false;

        if ($user) {
            $level = $user->getLevel();

            if ($level > 1) {
                $user->login($this->session);

                $json['auth_token'] = $user->getAuthToken();
                /** @var \Entity\CharityAdmin $charity_admin */
                $charity_admin = \Entity\CharityAdmin::findOneBy(['user' => $user]);
                if ($charity_admin) {
                    $json['charity_admin_url'] = $charity_admin->getCharity()->getUrl();
                }
            }
        }

        $json['level'] = $level;
        echo json_encode($json);
    }

    public function sign_in_using_auth_token() {
        if ($this->user) {
            echo json_encode(['success' => true]);
            return;
        }

        if (!isset($_POST['auth_token'])) {
            echo json_encode(['success' => false]);
            return;
        }

        $user = User::findOneBy(['auth_token' => $_POST['auth_token']]);
        if ($user) {
            $user->login($this->session);
            echo json_encode(['success' => true]);
            return;
        } else {
            echo json_encode(['success' => false]);
            return;
        }
    }

    public function contact() {
        $emails = 'admin@giverhub.com, robertanderssonwebdeveloper@gmail.com, levineam@gmail.com';

        if ($this->user)
        {
            $CI = & get_instance();
            $orig = $data['ContactForm'] = array('name' => $this->user->getName(), 'email' => $this->user->getEmail());
        }

        if (isset($_POST['ContactForm'])) {
            $data['ContactForm'] = $_POST['ContactForm'];

            if (!$_POST['ContactForm']['name']) {
                $data['contact_error'] = 'Please fill out your name';
            } elseif (!$_POST['ContactForm']['email']) {
                $data['contact_error'] = 'Please fill out your email';
            } elseif (!$_POST['ContactForm']['subject']) {
                $data['contact_error'] = 'Please type a subject';
            } elseif (!$_POST['ContactForm']['message']) {
                $data['contact_error'] = 'Please type a message';
            } else {
                $ret = emailsending('admin@giverhub.com', $emails, '[GiverHub Contact Form]', nl2br(print_r($_POST['ContactForm'], true)), 'GiverHub');
                if ($ret !== true) {
                    $data['contact_error'] = 'Problems when sending email, sorry, please try again at a later time, or contact us directly at ' . $emails;
                    $data['contact_error2'] = $ret;
                } else {
                    $data['contact_success'] = '<strong>Great!</strong> We will get back to you as soon as possible! Thank you for your input!';
                    if (isset($orig)) {
                        $data['ContactForm'] = $orig;
                    } else {
                        unset($data['ContactForm']);
                    }
                }
            }
        }

        $this->htmlTitle = 'Contact';
        $this->metaDesc = 'Contact GiverHub about making donations to charities or anything else for that matter.';
        $data['main_content'] = 'home/contact';

        $this->load->view('includes/user/template', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url());
    }

    /**
     * @return User
     */
    private function facebookSignedRequest() {
        define('FACEBOOK_APP_ID', $this->config->item("fb_app_id"));
        define('FACEBOOK_SECRET', $this->config->item("fb_secret"));

        $signed_request = $this->input->post('signed_request');
        $secret = FACEBOOK_SECRET;

        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return 'Unexpected problem when receiving data from from facebook.com. Unknown algorithm. Expected HMAC-SHA256';
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return 'Unexpected problem when receiving data from from facebook.com. Bad Signed JSON signature!';
        }

        return Common::create_member_fb($data, self::$em);
    }

    private function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function fbLogin() {
        $json = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $this->input->post('fbAccessToken')), true);

        if (!is_array($json) || !isset($json['id'])) {
            echo json_encode(['msg' => 'failed', 'json' => $json, 'stage' => 1]);
            return;
        }

        if (!isset($json['email']) || !$json['email']) {
            if ($json['username']) {
                $json['email'] = $json['username'].'@facebook.com';
            } else {
                mail('admin@giverhub.com', 'no fb email or username', print_r($json, true));
                echo json_encode(['msg' => 'no-email-username']);
                return;
            }
        }

        $extended_access_token_raw = @file_get_contents($extend_access_token_url = 'https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id='.$this->config->item("fb_app_id").'&client_secret='.$this->config->item("fb_secret").'&fb_exchange_token='.$this->input->post('fbAccessToken'));
        if (!$extended_access_token_raw) {
            echo json_encode(['msg' => 'failed', 'json' => $json, 'stage' => 2, 'extend' => $extended_access_token_raw]);
            return;
        }
        // access_token=CAAEk8Gwb1ZCoBACWYZAUojlz3nrLaZBhPnCVFZBD47xoUvVUdxYI0lNhj1lEzY23Vt4N8tnoy2Tyd3LIZAZCddjtWYkB6jqDUjknTlZA3TFuO352opZCAnZBHNOo5uxKcx6wIOMDWE741jwqxjmBZCC4qNzYVRPhGkiOPM5jnwgCnECRDXlIiX5KS5&expires=5179804
        preg_match('#access_token=(.*)&#U', $extended_access_token_raw, $matches);

        if (!$matches || !$matches[1]) {
            echo json_encode(['msg' => 'failed', 'json' => $json, 'stage' => 3, 'extend' => $extended_access_token_raw, 'matches' => $matches]);
            return;
        }

        $extended_access_token = $matches[1];

        if ($this->user && $this->session->userdata('fbAccessToken') == $extended_access_token) {
            echo json_encode(['msg' => 'already']);
            return;
        }

        $this->session->set_userdata('fbAccessToken', $extended_access_token);


        $em = self::$em;
        /** @var Doctrine\ORM\EntityRepository $userRepo */
        $userRepo = $em->getRepository('Entity\User');

        /** @var User $user|null */
        $user = $userRepo->findOneBy(array('fb_user_id' => $json['id']));

        if (!$user) {
            $user = Common::create_member_fb($json, self::$em);
        }

        if ($user) {

            if (!$user instanceof \Entity\User) {
                throw new Exception('user is not instanceof user: ' . $user);
            }
            $user->login($this->session);

            $json = [];

            /** @var \Entity\CharityAdmin $charity_admin */
            $charity_admin = \Entity\CharityAdmin::findOneBy(['user' => $user]);

            if($charity_admin) {
                $json['charity_admin_url'] = $charity_admin->getCharity()->getUrl();
            }
            if ($user->getLevel() >= 3) {
                $json['msg'] = 'success-admin';
            } else {
                $json['msg'] = 'success';
            }
            echo json_encode($json);
            return;
        } else {
            echo json_encode(['msg' => 'not-found', 'data' => $json]);
            return;
        }
    }

    public function page_not_found() {
        $this->giverhub_404('nonprofits/charity-404', 'Page Not Found');
    }

    public function completeProfile() {
        $this->load->library('form_validation');

        $this->form_validation->set_message('is_unique', '%s is already registered!');

        $this->form_validation->set_rules('completeFname', 'First Name', 'required');
        $this->form_validation->set_rules('completeLname', 'Last Name', 'required');
        //$this->form_validation->set_rules('completeEmail', 'Email', 'required|valid_email|callback_validate_email');
        $this->form_validation->set_rules('completeAddress', 'Address', 'required');
        $this->form_validation->set_rules('completeState', 'State', 'required|callback_validate_state');
        $this->form_validation->set_rules('completeCity', 'City', 'required|callback_validate_city');
        $this->form_validation->set_rules('completeZipCode', 'Zip Code', 'required|callback_validate_zipcode');
        $this->form_validation->set_rules('completePhone', 'Phone', 'required');

        $json = array(
            'success' => false,
            'msg' => 'Unexpected problem, please try again! Thank you for your patience.',
        );

        if (!$this->user) {
            $json['msg'] = '<strong>Oops.</strong> You have been signed out, please sign in again.';
        } else {
            if ($this->form_validation->run()) {
                $u = $this->user;

                $u->setFname(set_value('completeFname'));
                $u->setLname(set_value('completeLname'));
                $u->setAddress1(set_value('completeAddress'));
                if (set_value('completeAddress2')) {
                    $u->setAddress2(set_value('completeAddress2'));
                }
                $u->setZipCode(set_value('completeZipCode'));
                $u->setStateId(set_value('completeState'));
                $u->setCityId(set_value('completeCity'));
                $u->setPhone(set_value('completePhone'));
                self::$em->persist($u);
                self::$em->flush($u);
                $json['success'] = true;
            } else {
                $json['success'] = false;
                $errors = validation_errors();
                if ($errors) { // wtf code ignhituurh, no errors when no form is submitted???? then why the fuck does validation fail????? fucking cunt framework..
                    $json['msg'] = $errors;
                }
            }
        }
        echo json_encode($json);
    }

    public function validate_email($email) {
        $qb = self::$em->createQueryBuilder();
        $qb->select('u')
                ->from('Entity\User', 'u')
                ->where('u.id != ?1')
                ->andWhere('u.email != ?2')
                ->setParameter(1, $this->user->getId())
                ->setParameter(2, $email);
        $q = $qb->getQuery();
        $u = $q->getResult();
        if ($u) {
            $this->form_validation->set_message('validate_email', 'The email address is already in use by another member');
            return false;
        }
        return true;
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

    public function getCities() {
        $stateId = $this->input->get('stateId');
        if (!$stateId) {
            throw new Exception('getCities called with incorrect stateId');
        }
        /** @var CharityState $state */
        $state = CharityState::find($stateId);
        if (!$state) {
            throw new Exception('state not found (' . $stateId . ')');
        }
        $cities = $state->getCities();
        $this->load->view('home/_citiesDropDown', array('cities' => $cities));
    }

    private function follow($userId) {

        $this->db->select('count(id) as cnt');
        $q = $this->db->get_where('users', array('id' => $userId));
        $res = $q->result_array();
        if (!$res[0]['cnt']) {
            throw new Exception('user does not exist. id: ' . $userId);
        }

        $this->db->select('count(id) as cnt');
        $q = $this->db->get_where('followers', array(
                                               'follower_user_id' => $this->user->getId(),
                                               'followed_user_id' => $userId,
                                          ));
        $res = $q->result_array();
        if (!$res[0]['cnt']) { //  not friends already.. add
            return $this->db->insert('followers', array(
                                                'follower_user_id' => $this->user->getId(),
                                                'followed_user_id' => $userId,
                                                'date' => date('Y-m-d H:i:s'),
                                           ));
        }
    }

    public function followSelected() {
        if (!isset($_POST['userIds'])) {
            throw new Exception('Invalid request.');
        }

        $userIds = explode(',',$_POST['userIds']);

        foreach($userIds as $userId) {
            if (!$this->follow($userId)) {
                throw new Exception('Could not save userid: ' . $userId);
            }
        }

        echo json_encode(array('success' => true));
    }

    public function forgot_password() {
        if ($this->user) {
            $user = $this->user;
        } else {
            if (!isset($_POST['username']) && !isset($_POST['email'])) {
                throw new Exception('Invalid request, you need to send email or username');
            }

            if (isset($_POST['username']) && $_POST['username']) {
                $user = User::findOneBy(array('username' => $_POST['username']));
            } elseif (isset($_POST['email']) && $_POST['email']) {
                $user = User::findOneBy(array('email' => $_POST['email']));
            }

            if (!isset($user) || !$user) {
                echo json_encode(array('success' => false, 'msg' => 'Could not find any user with that username/email.'));
                return;
            }
        }
        if (!$user->resetPasswordStep1()) {
            echo json_encode(array('success' => false, 'msg' => 'Something unexpected went wrong when resetting your password. Please check your inbox and trash folder and if nothing has arrived, Try again. If it still does not work after trying again, please contact admin@giverhub.com'));
            return;
        }

        echo json_encode(array('success' => true, 'msg' => $user->getEmail()));
    }

    public function reset_password() {
        if (!@$_GET['t']) {
            $this->session->set_flashdata('flashError', 'Token missing, try copy paste the url from email.');
            redirect('/');
        }
        $t = $_GET['t'];
        if ($this->user) {
            $this->session->sess_destroy();
            redirect(base_url('home/reset_password?t='.$t));
        }

        $user = User::findOneBy(array('password_token' => $t));
        if (!$user) {
            $this->session->set_flashdata('flashError', 'Invalid token, sorry try again.');
            redirect('/');
        }

        $timeout = new \DateTime($user->getRetrievePasswordTime());
        $timeout->modify('+3 day');
        $now = new \DateTime();

        if ($now > $timeout) {
            $this->session->set_flashdata('flashError', 'Token has timed out. Get a new one.');
            redirect('/');
        }

        $data['PageTitle'] = 'Reset Password';
        $data['main_content'] = 'home/reset-password';
        $data['token'] = $t;
        $data['disable_beta_modal'] = true;
        $this->load->view('includes/user/template', $data);
    }

    public function reset_password_submit() {
        if (!isset($_POST['token'])) {
            throw new Exception('Invalid request. Token missing.');
        }
        if (!isset($_POST['password'])) {
            throw new Exception('Invalid request. Password missing.');
        }

        $user = User::findOneBy(array('password_token' => $_POST['token']));
        if (!$user) {
            throw new Exception('Token not found.');
        }

        $timeout = new \DateTime($user->getRetrievePasswordTime());
        $timeout->modify('+3 day');
        $now = new \DateTime();

        if ($now > $timeout) {
            throw new Exception('Token too old.');
        }
        $user->changePassword($_POST['password']);
        $user->login($this->session);
        echo json_encode(array('success' => true));
    }

    public function privacy() {
        $data['PageTitle'] = 'Privacy Policy';
        $data['main_content'] = 'home/privacy-policy';

        $this->htmlTitle = 'Privacy Policy';

        $this->load->view('includes/user/template', $data);
    }

    public function save_facebook_like() {
        if (!$this->user) {
            throw new Exception('not signed in');
        }
        if (!isset($_POST['charityId'])) {
            throw new Exception('Invalid request. Missing charityId');
        }
        $charity = Charity::find($_POST['charityId']);
        if (!$charity) {
            throw new Exception('Could not load charity. ' . $_POST['charityId']);
        }
        if (!isset($_POST['like'])) {
            throw new Exception('Invalid request. Missing like parameter.');
        }
        if (!in_array($_POST['like'], array('true', 'false'))) {
            throw new Exception('Invalid request. Like parameter is not in list. ' . $_POST['like']);
        }

        /** @var FacebookLike $facebookLike */
        $facebookLike = FacebookLike::findOneBy(array('user_id' => $this->user->getId(), 'charity_id' => $charity->getId()));
        if ($_POST['like'] == 'true') {
            if (!$facebookLike) {
                $facebookLike = new FacebookLike();
                $facebookLike->setUser($this->user);
                $facebookLike->setCharity($charity);

                $this->user->addGiverHubScore('facebook-like');
            }

            $facebookLike->setLikedAtDt(new \DateTime());
            \Base_Controller::$em->persist($facebookLike);
            \Base_Controller::$em->flush($facebookLike);
        } else {
            if ($facebookLike) {
                \Base_Controller::$em->remove($facebookLike);
                \Base_Controller::$em->flush();
                $this->user->addGiverHubScore('facebook-like', null, true);
            }
        }

        echo json_encode(array('success' => true));
    }

    public function check_logged_in() {
        echo json_encode(['success' => true, 'signed_in' => (bool)$this->user]);
    }

    public function save_missing_name() {
        if (!$this->user) {
            throw new Exception('not signed in');
        }
        if (!isset($_POST['l_name']) || !isset($_POST['f_name'])) {
            throw new Exception('Invalid request, missing l_name or f_name');
        }
        if (!$_POST['l_name']) {
            throw new Exception('Empty l_name: ' . $_POST['l_name']);
        }
        if (!$_POST['f_name']) {
            throw new Exception('Empty f_name: ' . $_POST['f_name']);
        }

        $this->user->setLname($_POST['l_name']);
        $this->user->setFname($_POST['f_name']);
        \Base_Controller::$em->persist($this->user);
        \Base_Controller::$em->flush($this->user);

        echo json_encode(['success' => true]);
    }

    public function save_new_username() {

        //Check username
        $this->load->library('form_validation');

        $this->form_validation->set_message('is_unique', 'The %s that you supplied is already registered!');

        $this->form_validation->set_rules('username', 'Old Username', 'required|callback_validate_old_username');
        $this->form_validation->set_rules('new_username', 'New Username', 'required|callback_validate_username_new');

        $response = array(
            'success' => false,
            'msg' => 'There was an unknown problem processing your request to join the site. Please try again later. Thank you for your patience.',
        );


        if ($this->form_validation->run()) {

            $this->user->setUsername($_POST['new_username']);
            $this->user->setPromptPickUsername(0);

            try {
                \Base_Controller::$em->persist($this->user);
                \Base_Controller::$em->flush($this->user);
                $response['success'] = true;
            } catch (Exception $e) {
                return false;
            }

        } else {
            $response['msg'] = validation_errors();
        }

        echo json_encode($response);
    }


    public function validate_username_new($username) {
        if (\Common::slug($username) != $username) {
            $this->form_validation->set_message('validate_username_new', 'Username is invalid. only lowercase letters, digits and - are allowed. no spaces. Must begin and end with letter or digit.');
            return false;
        } else if ($existing = User::findOneBy(['username' => $username])) {
            if ($existing->getId() != $this->user->getId()) {
                $this->form_validation->set_message('validate_username_new', 'Username is already taken.');
                return false;
            }
        }
        return true;
    }

    public function validate_old_username($oldUsername) {
        if($oldUsername!=$this->user->getUsername()) {
            $this->form_validation->set_message('validate_old_username', 'Current username is not matching.');
            return false;
        } else {
            return true;
        }
    }

	public function dashboard_tour() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

		if (isset($_POST['tour_taken'])) {
            $this->user->setIsDashboardTourTaken(1);
            \Base_Controller::$em->persist($this->user);
            \Base_Controller::$em->flush($this->user);
        } else {
            throw new Exception('Invalid request.');
        }
	}

    public function check_dashboard_image() {

        if ($this->user && $this->user->getDashboardImage()) {
            $response = array(
                'success' => true,
                'msg' => $this->user->getDashboardImage(),
            );
        } else {
            $response = array(
                'success' => false,
                'msg' => 'User dashboard image is not available',
            );
        }
        echo json_encode($response);

    }

    public function remove_dashboard_cover() {
        if (!$this->user) {
            throw new Exception('not signed in');
        }

        $this->user->setDashboardImage(null);
        $this->user->setDashboardImageUploadDate(null);
        \Base_Controller::$em->persist($this->user);
        \Base_Controller::$em->flush($this->user);
        $response = array(
            'success' => true,
            'msg' => 'Cover removed successfully',
        );

        echo json_encode($response);
    }

    public function trending_petitions_search() {
        $search_text = $this->input->get('search_text');

        $searchResults = ChangeOrgPetition::findSphinx($search_text, 'relevance', 0, 5);

        if (!$searchResults['result_count']) {
            $html = '<div class="nothing">Nothing found.</div>';
        } else {
            $html = $this->load->view('/partials/trending-petitions-search-results', ['petitions' => $searchResults['content_relevance']], true);
        }

        echo json_encode(['success' => true, 'search_text' => $search_text, 'html' => $html]);
    }

    public function auto_complete() {
        $search_text = $this->input->get('search_text');

        $results = [
            'success' => true,
            'search_text' => $search_text,
            'petitions' => [],
            'nonprofits' => [],
            'users' => [],
            'pages' => [],
        ];

        $searchResults[] = Charity::findSphinx($search_text, "", 'relevance', 0, 3);
        $searchResults[] = ChangeOrgPetition::findSphinx($search_text, 'relevance', 0, 3);


        foreach($searchResults as $searchResult) {
            if ($searchResult) {
                foreach($searchResult['content_relevance'] as $entity) {
                    if ($entity instanceof Charity) {
                        /** @var Charity $entity */
                        $results['nonprofits'][] = [
                            'name' => $entity->getName(),
                            'url' => $entity->getUrl(),
                            'id' => $entity->getId(),
                            'image_url' => $entity->getImageUrl(),
                            'desc' => $entity->getSearchDesc(),
                            'nonprofit' => true,
                        ];
                    } else {
                        /** @var ChangeOrgPetition $entity */
                        $results['petitions'][] = [
                            'name' => $entity->getTitle(),
                            'id' => $entity->getId(),
                            'url' => $entity->getGiverhubUrl('/'),
                            'image_url' => $entity->getImageUrl(),
                            'petition' => true,
                        ];
                    }
                }
            }
        }

        if ($this->user) {
            $searchResults = \Entity\User::findSphinx( '*' . $search_text . '*' );
            $count         = 0;
            foreach ($searchResults['users'] as $user) {
                if ($count == 3) {
                    break;
                }
                /** @var \Entity\User $user */
                $results['users'][] = [
                    'id'        => $user->getId(),
                    'name'      => $user->getName(),
                    'image_url' => $user->getImageUrl(),
                    'user'      => true,
                    'url'       => $user->getUrl(),
                ];
                $count ++;
            }
        }

        $searchResults = \Entity\Page::findSphinx('*'.$search_text.'*');
        $count = 0;
        foreach($searchResults['pages'] as $page) {
            if ($count == 3) {
                break;
            }
            /** @var \Entity\Page $page */
            $results['pages'][] = [
                'id' => $page->getId(),
                'name' => $page->getName(),
                'image_url' => $page->getLogoUrl(),
                'page' => true,
                'url' => $page->getUrl(),
            ];
            $count++;
        }

        echo json_encode($results);
    }

    public function get_google_url() {
        echo json_encode([
                'success' => true,
                'url' => $this->getGoogleUrl($_GET['redirect'])
            ]);
    }

    public function js_exception() {
        $server = print_r($_SERVER, true);
        $post = print_r($_POST, true);
        $get = print_r($_GET, true);
        $request = print_r($_REQUEST, true);
        $user = $this->user ? $this->user->getId() . ':' . $this->user->getName() : 'none';
        $msg =
"e_msg: {$_POST['e_msg']}
e_stack: {$_POST['e_stack']}
user: {$user}
_POST: {$post}
_GET: {$get}
_REQUEST: {$request}
_SERVER: {$server}";

        $send_email = true;
        if (isset($_SERVER['HTTP_USER_AGENT']) &&
            isset($_POST['msg']) &&
            preg_match('#getComputedStyle#', $_POST['msg'])) {
            $send_email = false;
        }

        if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('#Baiduspider#i', $_SERVER['HTTP_USER_AGENT'])) {
            $send_email = false; // ignore everything from that fucking cunt baiduspider
        }

        if (isset($_POST['url']) && in_array($_POST['url'],
                [
                    'https://platform.twitter.com/widgets.js',
                    'https://www.youtube.com/iframe_api',
                    'https://connect.facebook.net/en_US/sdk.js',
                    'https://ssl.google-analytics.com/ga.js',
                ]
        )) {
            $send_email = false;
        }

        if ($send_email) {
            mail('admin@giverhub.com', 'js-exception @' . @$_SERVER['SERVER_NAME'], $msg);
        }
    }

    public function help() {
        redirect('/faqs', 'location', 301);
    }


    public function gcal() {
        require_once(__DIR__.'/../libraries/google-api-php-client-2/src/Google/Client.php');
        require_once(__DIR__.'/../libraries/google-api-php-client-2/src/Google/Service/Calendar.php');
        $client = new Google_Client();
        // Replace this with your application name.
        $client->setApplicationName("GiverHub");
        // Replace this with the service you are using.
        $service = new Google_Service_Calendar($client);

        // This file location should point to the private key file.
        $key = file_get_contents(__DIR__.'/../../../conf/google-api/something.p12');
        $cred = new Google_Auth_AssertionCredentials(
            '466530377541-8tkbg5p17u6aeo9hak8udta7hm5i6816@developer.gserviceaccount.com',
            array('https://www.googleapis.com/auth/calendar'),
            $key
        );
        $client->setAssertionCredentials($cred);

        $calendarList = $service->calendarList->listCalendarList();

        while(true) {
            foreach ($calendarList->getItems() as $calendarListEntry) {
                /** @var Google_Service_Calendar_Calendar $calendarListEntry */
                echo $calendarListEntry->getSummary() .'<br/>';
                echo $calendarListEntry->getId() . '<br/>';
                echo $calendarListEntry->getDescription() . '<br/>';
                echo $calendarListEntry->getLocation() . '<br/>';
            }
            $pageToken = $calendarList->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $calendarList = $service->calendarList->listCalendarList($optParams);
            } else {
                break;
            }
        }

        $calendar = $service->calendars->get('primary');

        echo $calendar->getSummary();


        $event = new Google_Service_Calendar_Event();
        $event->setSummary('Appointment');
        $event->setLocation('Somewhere');
        $event->setId(123);


        $start = new Google_Service_Calendar_EventDateTime();
        $start->setDateTime('2011-06-03T10:00:00.000-07:00');
        $event->setStart($start);

        $end = new Google_Service_Calendar_EventDateTime();
        $end->setDateTime('2011-06-03T10:25:00.000-07:00');
        $event->setEnd($end);

        $createdEvent = $service->events->insert('primary', $event);

        echo $createdEvent->getId();
    }

    public function gcal2() {
        require_once(__DIR__.'/../libraries/google-api-php-client-2/src/Google/Client.php');
        require_once(__DIR__.'/../libraries/google-api-php-client-2/src/Google/Service/Calendar.php');

        $client = new Google_Client();
        $client->setApplicationName("GiverHub");
        $client->setClientId($this->config->item('google_client_id'));
        $client->setClientSecret($this->config->item('google_client_secret'));
        $client->setRedirectUri($this->config->item('google_redirect_uri'));
        $client->setDeveloperKey($this->config->item('google_developer_key'));
        $service = new Google_Service_Calendar($client);

        $client->setScopes(array(
                'https://www.googleapis.com/auth/calendar',
                'email'
            ));

        if ($this->session->userdata('google_access_token')) {
            $client->setAccessToken($this->session->userdata('google_access_token'));
        }

        if ($client->getAccessToken()) {
            $this->session->set_userdata('google_access_token', $client->getAccessToken());

            $calendar = $service->calendars->get('primary');

            echo $calendar->getSummary();

            $events = $service->events->listEvents('primary');

            while(true) {
                foreach ($events->getItems() as $event) {
                    /** @var Google_Service_Calendar_Event $event */
                    echo nl2br(print_r($event->getRecurrence(), true));
                }
                $pageToken = $events->getNextPageToken();
                if ($pageToken) {
                    $optParams = array('pageToken' => $pageToken);
                    $events = $service->events->listEvents('primary', $optParams);
                } else {
                    break;
                }
            }
        } else {
            $this->googleAuthUrl = $client->createAuthUrl();
            echo '<a href="'.$this->googleAuthUrl.'">ok</a>';
        }
    }

    public function buttons_demo() {
        $data['PageTitle'] = 'Buttons Demo';
        $data['main_content'] = 'home/buttons-demo';

        $this->htmlTitle = 'Buttons Demo';

        $this->load->view('includes/user/template', $data);
    }

    public function bet_a_friend() {
        $this->htmlTitle = 'Bet-a-Friend';
        $data['main_content'] = '/bets/bet-a-friend';

        $this->load->view('includes/user/template', $data);
    }
}

<?php

require_once(__DIR__ . '/Base_Controller.php');
require_once __DIR__.'/../libraries/htmlpurifier/HTMLPurifier.auto.php';

use \Entity\CharityReview;

class Charity extends Base_Controller 
{

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');

        $this->load->config('general_website_conf');
    }

    public function index($id) {
        try {
            $charity = $this->loadCharity($id);
            $data['charity'] = $charity;
        } catch(Exception $e) {
            $this->giverhub_404('nonprofits/charity-404', 'Nonprofit not found');
            return;
        }

        redirect($charity->getUrl(), 'location', 301);
    }

    function submit_update() {
        if (!$this->user) {
            throw new Exception('Not signed in.');
        }
        if (!isset($_POST['charityId'])) {
            throw new Exception('Invalid request. No charity id');
        }
        $charity = $this->loadCharity($_POST['charityId']);
        if (!isset($_POST['updateText'])) {
            throw new Exception('No update text');
        }
        if (strlen($_POST['updateText']) < 2) {
            throw new Exception('Update Text Too short');
        }

        $charityUpdate = new \Entity\CharityUpdate();
        $charityUpdate->setUser($this->user);
        $charityUpdate->setCharity($charity);
        $charityUpdate->setDateTime(new \DateTime());
        $charityUpdate->setUpdate($_POST['updateText']);
        \Base_Controller::$em->persist($charityUpdate);
        \Base_Controller::$em->flush($charityUpdate);
        $this->user->addGiverHubScore('leave-charity-update');
        echo json_encode(array(
                              'success' => true,
                              'updates' => $this->load->view('nonprofits/_charity_updates_table',array('charity' => $charity),true),
                         ));
    }
    
    public function follow() {
        if (!$this->user) {
            throw new Exception('Must be signed in');
        }
        if (!isset($_POST['id'])) {
            throw new Exception('invalid request. missing id.');
        }
        if (!isset($_POST['action'])) {
            throw new Exception('invalid request. missing action.');
        }
        $charity = $this->loadEntity($_POST['id']);

        switch($_POST['action']) {
            case 'follow':
                $this->user->followCharity($charity);
                break;
            case 'unfollow':
                $this->user->unfollowCharity($charity);
                break;
            default:
                throw new Exception('Bad action ' . $_POST['action'] . ' should be follow or unfollow');
        }
        echo json_encode(array('success' => true, 'action' => $_POST['action']));
    }

    /**
     * @param $id
     *
     * @return \Entity\Charity
     */
    private function loadEntity($id) {
        return $this->loadCharity($id);
    }

    /**
     * @param $id
     *
     * @return \Entity\Charity
     * @throws Exception
     */
    public function loadCharity($id) {
        $em = self::$em;
        $cRepo = $em->getRepository('\Entity\Charity');
        /** @var \Entity\Charity $charity */
        $charity = $cRepo->find($id);
        if (!$charity) {
            throw new Exception('Could not load charity with id: '. $id);
        }
        return $charity;
    }

    function add_keyword() {
        if (!$this->user) {
            throw new Exception('Need to be signed in');
        }
        $user = $this->user;

        if (!isset($_POST['keyword'])) {
            throw new Exception('Invalid request. keyword is missing.');
        }
        $keywords = $_POST['keyword'];
        $keywords = array_unique(explode(',',$keywords));
        

        if (!isset($_POST['charityId'])) {
            throw new Exception('Invalid request. charityId is missing.');
        }
        $charityId = $_POST['charityId'];

        $em = self::$em;
        $anyKeywordExists = false;
        

        foreach($keywords as $keyword){
            
            $keyword = preg_replace('/^(\s)*|(\s)*$/', '', $keyword);
            if(!$keyword) continue;


            $ckRepo = $em->getRepository('\Entity\CharityKeyword');
            $existingKeyword = $ckRepo->findOneBy(array(
                                                       'keyword_name' => $keyword,
                                                       'charity_id' => $charityId
                                                  ));
           if ($existingKeyword) {
               $anyKeywordExists = true;
           }
        }
        
        if ($anyKeywordExists) {
            echo json_encode(array(
                              'success' => false,
                              'message' => 'One or more entered keywords already exists.',
                         ));
            die;
        }
        
        $charity = $this->loadCharity($charityId);

        foreach($keywords as $keyword){
            
            $keyword = preg_replace('/^(\s)*|(\s)*$/', '', $keyword);
            if(!$keyword) continue;
            
            $charityKeyword = new \Entity\CharityKeyword();
            $charityKeyword->setCharity($charity);
            $charityKeyword->setUser($user);
            $charityKeyword->setKeywordName( strtolower($keyword) ); //make keywords lowercase
            $charityKeyword->setDate(date('Y-m-d H:i:s'));
            $em->persist($charityKeyword);
            $em->flush();

            $user->addGiverHubScore('keyword');
            $user->addBadgePoints('keyword', $charity);
        }

        echo json_encode(array(
                              'success' => true,
                              'keywordsHtml' => $this->load->view('nonprofits/_charity_header_keywords', array('charity' => $charity), true),
                         ));
    }

    public function keyword_vote() {
        if (!$this->user) {
            throw new Exception('Need to be signed in first.');
        }

        if (!isset($_POST['keyword_id'])) {
            throw new Exception('invalid request, missing keyword id.');
        }

        if (!isset($_POST['vote'])) {
            throw new Exception('invalid request, missing vote parameter.');
        }

        if (!in_array($_POST['vote'], array(1, -1))) {
            throw new Exception('invalid vote, needs to be 1 or -1 .. ' . $_POST['vote']);
        }

        /** @var \Entity\CharityKeyword $keyword */
        $keyword = \Entity\CharityKeyword::find($_POST['keyword_id']);

        if (!$keyword) {
            throw new Exception('keyword could not be loaded. id: ' . $_POST['keyword_id']);
        }

        $keyword->vote($_POST['vote']);

        $charity = $keyword->getCharity();

        echo json_encode(array(
                              'success' => true,
                              'keywordsHtml' => $this->load->view('nonprofits/_charity_header_keywords', array('charity' => $charity), true),
                         ));
    }

    public function keyword_flag() {
        if (!$this->user) {
            throw new Exception('Need to be signed in first.');
        }

        if (!isset($_POST['keyword_id'])) {
            throw new Exception('invalid request, missing keyword id.');
        }

        /** @var \Entity\CharityKeyword $keyword */
        $keyword = \Entity\CharityKeyword::find($_POST['keyword_id']);

        if (!$keyword) {
            throw new Exception('keyword could not be loaded. id: ' . $_POST['keyword_id']);
        }

        $keyword->flag();

        $charity = $keyword->getCharity();

        echo json_encode(array(
                              'success' => true,
                              'keywordsHtml' => $this->load->view('nonprofits/_charity_header_keywords', array('charity' => $charity), true),
                         ));
    }


    /*
     *
     * 00365 - review validation routine
     * This function also checks if the user is signed or not.
     */
    function IsCharityReviewUnique(){
    
        $user_id = $this->user->getId();
        if (!$user_id) {

            $this->form_validation->set_message('IsCharityReviewUnique', 'You need to login to post a review.');
            return false;

        }

        return true;
    }

    //00365 - Insert Reivew
    //for inserting review of a charity
    public function review_charity()
    {
        $this->form_validation->set_rules('review_desc', 'Review', 'required');
        $this->form_validation->set_rules('rating', 'Rating', 'greater_than[0]|required');
        $this->form_validation->set_rules('charity_id', 'Charity', 'callback_IsCharityReviewUnique');

        if ($this->form_validation->run() == TRUE){

            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            $review_desc = $purifier->purify($this->input->post('review_desc'));

            $user_id = $this->user->getId();
            $show_name = $this->input->post('show_name');
            $rating = $this->input->post('rating');
            $charity_id = $this->input->post('charity_id');

            try {
                $charity = $this->loadCharity($charity_id);

                if ($this->user->hasReviewedCharity($charity)) {
                    $review = $this->user->getCharityReview($charity);
                } else {
                    $review = new CharityReview();
                    $review->setCharity($charity);
                    $review->setUser($this->user);
                    $new_review = true;
                }

                $review->setRating($rating);
                $review->setReviewDesc($review_desc);
                $review->setTimeCreated( date('Y-m-d H:i:s') );
                $review->setIsShowReview($show_name);

                self::$em->persist($review);
                self::$em->flush($review);

                $message = 'Review saved';
                $success = 1;

                $reviewCount = count($charity->getReviews());
                $review = \Entity\CharityReview::findOneBy(array('user_id' => $user_id, 'charity_id' => $charity_id));
                if (isset($new_review)) {
                    $this->user->addGiverHubScore('review');
                    $this->user->addBadgePoints('review', $charity);
                }
            } catch(Exception $e) {
                $message = form_error('charity_id') ? form_error('charity_id') : form_error('review_desc').form_error('rating');
                $success = 0;
            }
        }else{
           $message = form_error('charity_id') ? form_error('charity_id') : form_error('review_desc').form_error('rating');
           $success = 0;
        }
        $result = array(
           'success' => $success,
           'message' => $message,
           'count' => isset($reviewCount) ? $reviewCount : null,
           'review' => isset($review) ? $this->load->view('nonprofits/_charity_review', array('review' => $review), true) : null,
        );

       
       echo json_encode($result); 
    }

    public function remove_review() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('missing charity_id');
        }

        $charity = $this->loadCharity($_POST['charity_id']);

        if (!$this->user->hasReviewedCharity($charity)) {
            echo json_encode(['success' => true]); // the review was already deleted ... :)
            return;
        }

        $review = $this->user->getCharityReview($charity);

        self::$em->remove($review);
        self::$em->flush();

        $this->user->addGiverHubScore('review', null, true);
        $this->user->addBadgePoints('review', $charity, null, true);

        echo json_encode(['success' => true]);
    }

    public function delete_keywords() {
        if (!$this->user || $this->user->getLevel() < 4) {
            throw new Exception('bad level');
        }

        if (!isset($_POST['keywordIds'])) {
            throw new Exception('Invalid request, missing keywordIds');
        }

        if (!is_array($_POST['keywordIds'])) {
            throw new Exception('Invalid request, keywordIds is not array');
        }

        if (empty($_POST['keywordIds'])) {
            throw new Exception('Invalid request, keywordIds is empty');
        }

        $qb = \Base_Controller::$em->createQueryBuilder();
        $qb->select('ck')
           ->from('\Entity\CharityKeyword', 'ck')
           ->add('where', $qb->expr()->in('ck.id', $_POST['keywordIds']));

        $query = $qb->getQuery();
        $keywords = $query->getResult();

        foreach($keywords as $keyword) {
            \Base_Controller::$em->remove($keyword);
        }
        \Base_Controller::$em->flush();

        echo json_encode(array('success' => true));
    }
    
    public function feature() {
        if (!$this->user || $this->user->getLevel() < 4) {
            throw new Exception('Request denied');
        }
        if (!isset($_POST) || !isset($_POST['charityId']) || !isset($_POST['featuredText']) || !isset($_POST['isFeatured'])) {
            throw new Exception('Invalid request. ' . print_r($_POST, true));
        }

        $charity = $this->loadCharity($_POST['charityId']);

        $charity->setIsFeatured($_POST['isFeatured']);
        $charity->setFeaturedText($_POST['featuredText']);

        self::$em->persist($charity);
        self::$em->flush($charity);
        echo json_encode(array('success' => true));
    }

    /**
     *  @param POST - charity_id, email
     *
     *  @return JSON - status = Success or Fail
     */
    public function send_invitation(){
        
        if(!empty($_POST)) {

            $this->load->config('mailsvariation');
            $charity_id = $this->input->post('charity_id');
            $emails = $this->input->post('email');

            $emails = preg_replace('/\s+/', '', $emails);
            $emails = explode(',', $emails);

            $from = $this->config->item('from');
            $companyname = $this->config->item('companyname');
            $to = $emails;

            $charity = $this->loadCharity($charity_id);
            $link = '<a href="' . base_url() . 'charity/' . $charity_id . '">' . base_url() . 'charity/' . $charity_id . '</a>';
            $subject = 'Giverhub - Invitation for Charity';

            $body = "I just donated to ".$charity->getName()." and thought you might be interested in learning more about it. Here's the url: ".$link;
            

            $sent = emailsending($from, $to, $subject, $body, $companyname, 1);
            
            $response = array('success' => (bool)$sent);

            echo json_encode($response);
            exit;
        } else {
            throw new Exception('Invalid Data Sent');
        }
    }

    public function set_mission_summary() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['text'])) {
            throw new Exception('text field missing');
        }

        if (!isset($_POST['field'])) {
            throw new Exception('field field is missing');
        }

        if ($_POST['field'] != 'mission-summary') {
            throw new Exception('invalid field ' . $_POST['field']);
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('missing charity_id field.');
        }

        $charity = $this->loadCharity($_POST['charity_id']);

        if (!$charity instanceof \Entity\Charity) {
            throw new Exception('Could not load charity. charity-id: '. $_POST['charity_id']);
        }

        $new_text = trim($_POST['text']);

        $change_history = new \Entity\CharityChangeHistory();
        $change_history->setNewValue($new_text);
        $change_history->setUser($this->user);
        $change_history->setCharity($charity);
        $change_history->setDatetime(date('Y-m-d H:i:s'));


        switch($_POST['field']) {
            case 'mission-summary':
                if ($charity->getTagLine()) {
                    throw new Exception('Cannot update mission summary when tagline is present.');
                }
                if (strlen($new_text) > 140) {
                    throw new Exception('Text is too long. ' . strlen($new_text));
                }
                $old_text = $charity->getMissionSummary();
                $charity->setMissionSummary($new_text);
                $charity->setMissionSummaryUser($this->user);
                $change_history->setField('tagline');
                break;
            default:
                throw new Exception('Invalid field: ' . $_POST['field']);
        }

        self::$em->persist($charity);
        self::$em->flush($charity);

        $change_history->setOldValue($old_text);
        self::$em->persist($change_history);
        self::$em->flush($change_history);

        mail(
            'admin@giverhub.com',
            'nonprofit ' . $_POST['field'] . ' changed by user',
            print_r([
                    'User ID' => $this->user->getId(),
                    'User Name' => $this->user->getName(),
                    'Nonprofit ID' => $charity->getId(),
                    'Nonprofit Name' => $charity->getName(),
                    'Nonprofit Link' => base_url($charity->getUrl()),
                    'Field' => $_POST['field'],
                    'Old Text' => $old_text,
                    'New Text' => $new_text
            ], true)
        );

        $this->user->addGiverHubScore('mission-summary');

        echo json_encode(['success' => true]);
    }

    public function citizen_admin() {
        if (!isset($_GET['charity_id'])) {
            throw new Exception('invalid request, missing charity_id param.');
        }

        $charity = \Entity\Charity::find($_GET['charity_id']);
        if (!$charity) {
            throw new Exception('Could not load charity. ' . $_GET['charity_id']);
        }

        echo json_encode(['success' => true, 'html' => $this->load->view('/nonprofits/_citizen_admin', ['charity' => $charity], true)]);
    }

    public function request_admin() {
        if (!isset($this->user)) {
            throw new Exception('User not signed in.');
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('missing charity_id');
        }

        if (!isset($_POST['temp_id'])) {
            throw new Exception('missing temp_id');
        }

        /** @var \Entity\Charity $charity */
        $charity = \Entity\Charity::find($_POST['charity_id']);
        if (!$charity) {
            throw new Exception('could not load charity_id: ' . $_POST['charity_id']);
        }

        $request = new \Entity\CharityAdminRequest();
        $request->setCharity($charity);
        $request->setMessage(isset($_POST['message']) ? $_POST['message'] : '');
        $request->setUser($this->user);

        self::$em->persist($request);
        self::$em->flush($request);

        /** @var \Entity\CharityAdminRequestPicture[] $images */
        $images = \Entity\CharityAdminRequestPicture::findBy(['tempId' => $_POST['temp_id']]);

        $image_urls = [];
        foreach($images as $image) {
            $image->setTempId(null);
            $image->setCharityAdminRequest($request);
            self::$em->persist($image);
            self::$em->flush($image);
            $image_urls = $image->getUrl();
        }

        mail(
            'admin@giverhub.com',
            'charity admin request',
            print_r([
                    'User ID' => $this->user->getId(),
                    'User Name' => $this->user->getName(),
                    'Nonprofit ID' => $charity->getId(),
                    'Nonprofit Name' => $charity->getName(),
                    'Nonprofit Link' => base_url($charity->getUrl()),
                    'Message' => $request->getMessage(),
                    'images' => $image_urls,
                ], true)
        );

        echo json_encode(['success' => true]);
    }

    public function vol_cal($id) {
        /** @var \Entity\Charity $charity */
        $charity = \Entity\Charity::find($id);

        if (!$charity) {
            throw new Exception('failed to load charity: ' . $id);
        }

        $events = [];

        foreach($charity->getVolunteeringOpportunities() as $opps) {
            $events[] = $opps->getCalendarEvent(\Entity\CharityVolunteeringOpportunity::$php_time_zones_reverse[$_GET['timezone']]);
        }

        echo json_encode(['success' => true, 'events' => $events]);
    }
}
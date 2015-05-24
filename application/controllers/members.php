<?php
use \Entity\User;
use \Entity\Charity;
use \Entity\ChangeOrgPetition;
use \Entity\RecurringDonation;

require_once(__DIR__ . '/Base_Controller.php');


class Members extends Base_Controller 
{
    public $user_id;

    public function __construct() {
        parent::__construct();
    }

    static public function index($username = null) {
        /** @var \Base_Controller $CI */
        $CI =& get_instance();
        if (!$CI->user) {
            redirect(base_url($username ? '?redirect=/member/'.$username : ''));
            die();
        }

        if ($username !== null) {
            $user = User::findOneBy(['username' => $username]);
            if (!$user) {
                $CI->giverhub_404('members/member-404', 'Member not found!');
                return;
            }
        } else {
            $user = $CI->user;

            $pages = $user->getPages();
            if ($pages) {
                redirect('/page/'.$pages[0]->getUrlSlug());
                return;
            }
        }


        $data['my_dashboard'] = $my_dashboard = $CI->user->getId() == $user->getId();

        if ($my_dashboard) {
            $CI->htmlTitle = 'My Dashboard';
            $CI->my_dashboard = true;
            $CI->dashboard_dropdown = $user;
            \Entity\Stat::register('dashboard-views');
        } else {
            $CI->htmlTitle = $user->getName() ."'s Profile";
        }

        $data['main_content'] = 'members/index';
        $data['user'] = $user;
        $CI->load->view('includes/user/template', $data);
    }

    public function toggleFollowUser() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        if (!isset($_POST['userId'])) {
            throw new Exception('Invalid request.');
        }

        $em = self::$em;

        $userRepo = $em->getRepository('\Entity\User');

        /** @var \Entity\User $user */
        $user = $userRepo->find($_POST['userId']);

        if (!$user) {
            throw new Exception('user does not exist. id: ' . $_POST['userId']);
        }

        echo json_encode(array('success' => true, 'following' => $this->user->toggleFollowUser($user)));
    }

    /**
     * @param $id
     *
     * @return \Entity\User
     * @throws Exception
     */
    public function loadUser($id) {
        $em = self::$em;
        $cRepo = $em->getRepository('\Entity\User');
        /** @var \Entity\User $user */
        $user = $cRepo->find($id);
        if (!$user) {
            throw new Exception('Could not load user with id: '. $id);
        }
        return $user;
    }

    public function activity() {
        if (!$this->user) {
            redirect(base_url('?redirect=/members/activity'));
            return;
        }
        $this->dashboard_dropdown = $this->user;

        $this->htmlTitle = 'Recent Activity';
        $data['main_content'] = 'members/activity';
        $data['user'] = $this->user;
        $this->load->view('includes/user/template', $data);
    }

    public function save_categories_and_causes() {
        if (!$this->user) {
            throw new Exception('Need to be signed in!');
        }
        if (!isset($_POST['categories'])) {
            throw new Exception('Invalid request. categories parameter missing.');
        }
        if (!isset($_POST['causes'])) {
            throw new Exception('Invalid request. causes parameter missing.');
        }

        $em = self::$em;
        $userCategories = $this->user->getCategories();
        foreach($userCategories as $userCategory) {
            $em->remove($userCategory);
        }
        $em->flush();

        $userCauses = $this->user->getCauses();
        foreach($userCauses as $userCause) {
            $em->remove($userCause);
        }
        $em->flush();

        $userCategories = array();
        if ($_POST['categories']) {
            $selected = explode(',', $_POST['categories']);
            $ccRepo = $em->getRepository('\Entity\CharityCategory');
            foreach($selected as $charityCategoryId) {
                /** @var \Entity\CharityCategory $charityCategory */
                $charityCategory = $ccRepo->find($charityCategoryId);
                if (!$charityCategory) {
                    throw new Exception('Invalid Charity Category Id: ' . $charityCategoryId);
                }
                $userCategory = new \Entity\UserCategory();
                $userCategory->setUser($this->user);
                $userCategory->setCharityCategory($charityCategory);
                $em->persist($userCategory);
                $em->flush($userCategory);
                $userCategories[] = $userCategory;
            }
        }

        $userCauses = array();
        if ($_POST['causes']) {
            $selected = explode(',', $_POST['causes']);
            $ccRepo = $em->getRepository('\Entity\CharityCause');
            foreach($selected as $charityCauseId) {
                /** @var \Entity\CharityCause $charityCause */
                $charityCause = $ccRepo->find($charityCauseId);
                if (!$charityCause) {
                    throw new Exception('Invalid Charity Cause Id: ' . $charityCauseId);
                }
                $userCause = new \Entity\UserCause();
                $userCause->setUser($this->user);
                $userCause->setCharityCause($charityCause);
                $em->persist($userCause);
                $em->flush($userCause);
                $userCauses[] = $userCause;
            }
        }

        echo json_encode(array(
                              'success' => true,
                              'causesHtml' => $this->load->view('members/_causes', array('user' => $this->user, 'my_dashboard' => 1), true),
                         ));
    }

    public function remove_category() {
        if (!$this->user) {
            throw new Exception('Need to be signed in!');
        }
        if (!isset($_POST['userCategoryId'])) {
            throw new Exception('Invalid request. userCategoryId parameter missing.');
        }

        $em = self::$em;
        $ucRepo = $em->getRepository('\Entity\UserCategory');
        /** @var \Entity\UserCategory $userCategory */
        $userCategory = $ucRepo->findOneBy(array('id' => $_POST['userCategoryId'], 'user_id' => $this->user->getId()));
        if (!$userCategory) {
            throw new Exception('Could not find user category with id: ' . $_POST['userCategoryId'] . ' for user-id: ' . $this->user->getId());
        }
        $em->remove($userCategory);
        $em->flush();
        echo json_encode(array(
                            'success' => true,
                         ));
    }

    public function remove_cause() {
        if (!$this->user) {
            throw new Exception('Need to be signed in!');
        }
        if (!isset($_POST['userCauseId'])) {
            throw new Exception('Invalid request. userCauseId parameter missing.');
        }

        $em = self::$em;
        $ucRepo = $em->getRepository('\Entity\UserCause');
        /** @var \Entity\UserCause $userCause */
        $userCause = $ucRepo->findOneBy(array('id' => $_POST['userCauseId'], 'userid' => $this->user->getId()));
        if (!$userCause) {
            throw new Exception('Could not find user cause with id: ' . $_POST['userCauseId'] . ' for user-id: ' . $this->user->getId());
        }
        $em->remove($userCause);
        $em->flush();
        echo json_encode(array(
                              'success' => true,
                         ));
    }

    public function settings($op = '', $type = null) {
        if (!$this->user) {
            redirect(base_url('?redirect=/members/settings'));
            return;
        }

        $this->dashboard_dropdown = $this->user;

        /*
         * Save Settings
         */
        if ($op == 'save' && !empty($type)) {
            if ($type == 'info') {
                $message = array();
                $id = $this->user->getId();
                $key = $this->input->post('key');
                $value = $this->input->post('value');
                $user = $this->user;


                if (!$value) {
                    $message['status'] = 'failed';
                    $message['message'] = 'Value cannot be empty';
                } elseif($key == 'name') {
                
                    $pieces = explode(" ", $value);
                    $first_name = $pieces[0];
                    unset($pieces[0]);
                    $last_name = implode(" ", $pieces);

                    try {

                        $user->setFname($first_name);
                        $user->setLname($last_name);

                        $message['status'] = 'success';
                        $message['message'] = 'Saved';

                        self::$em->persist($user);
                        self::$em->flush($user);

                    } catch(Exception $e) {

                        $message['status'] = 'failed';
                        $message['message'] = 'Not Saved';

                    }

                    
                } elseif($key == 'username') {

                    $errorMessage = "";

                    if (\Common::slug($value) != $value) {
                        $errorMessage = "Username is invalid. only lowercase letters, digits and - are allowed. no spaces. Must begin and end with letter or digit.";
                    } else if ($existing = User::findOneBy(['username' => $value])) {
                        if ($existing->getId() != $user->getId()) {
                            $errorMessage = "Username is already taken.";
                        }
                    }

                    if ($errorMessage) {

                        $message['status'] = 'failed';
                        $message['message'] = $errorMessage;

                    } else {

                        try {

                            $user->setUsername($value);
							$user->setPromptPickUsername(0);

                            self::$em->persist($user);
                            self::$em->flush($user);

                            $message['status'] = 'success';
                            $message['message'] = 'Saved';

                        } catch(Exception $e) {

                            $message['status'] = 'failed';
                            $message['message'] = 'Not Saved';

                            $GLOBALS['debug']($e->getMessage());

                        }
                    }



                } elseif ($key == 'email') {

                    $existingUser = User::findOneBy(array('email' => $value));

                    if ($existingUser) {

                        $message['status'] = 'failed';
                        $message['message'] = 'Email Exists';

                    } else {

                        try {

                            $user->setEmail($value);
                            self::$em->persist($user);
                            self::$em->flush($user);

                            $message['status'] = 'success';
                            $message['message'] = 'Saved';

                        } catch (Exception $e) {

                            $message['status'] = 'failed';
                            $message['message'] = 'Not Saved';

                            $GLOBALS['debug']($e->getMessage());
                        }

                    }
                } elseif($key == 'password') {
                    try {
                        $this->user->changePassword($value);

                        $message['status'] = 'success';
                        $message['message'] = 'Saved';
                        $message['time_message'] = 'Modified on '.date('m/d/Y');
                    } catch(\Exception $e) {
                        $message['status'] = 'failed';
                        $message['message'] = 'Not Saved';
                    }
                }

                echo json_encode($message);
                exit;
                
            } elseif ($type == 'settings') {
                
                
                $message = array();
                $id = $this->user->getId();
                $key = $this->input->post('key');
                $value = $this->input->post('value') ? 1 : 0;
                $user = $this->user;

                if ($key == 'sign-petitions-anonymously') {

                    try {
                        $user->setSignPetitionsAnonymously($value);

                        $message['status'] = 'success';
                        $message['message'] = 'Saved';

                        $message['msg_subject'] = 'Your change has been saved!';
                        if ($value) {
                            $message['msg_msg'] = '
<div class="text-align-left"><div class="roboto-bold">Your petition signatures will be anonymous by default.</div>
If you would like to sign any petition publicly, simply click the button which says “Signature Hidden” on the petition before signing.</div>';
                        } else {
                            $message['msg_msg'] = '
<div class="text-align-left"><div class="roboto-bold">Your petition signatures will be public by default.</div>
If you would like to sign any petition anonymously, simply click the button which says “Hide Signature?” on the petition before signing.</div>';
                        }

                        self::$em->persist($user);
                        self::$em->flush($user);

                    } catch(Exception $e) {
                        $message['status'] = 'failed';
                        $message['message'] = 'Not Saved';
                    }
                    
                } elseif ($key == 'badges') {

                    try {
                        $user->setHideUnhideBadges($value);

                        $message['status'] = 'success';
                        $message['message'] = 'Saved';

                        self::$em->persist($user);
                        self::$em->flush($user);

                    } catch(Exception $e) {

                        $GLOBALS['debug']($e->getMessage());
                        $message['status'] = 'failed';
                        $message['message'] = 'Not Saved';

                    }

                }
                
                echo json_encode($message);
                exit;
            }
        }

        $this->htmlTitle = 'Settings';
        $data['main_content'] = 'members/settings';
        $data['user'] = $this->user;
        $this->load->view('includes/user/template', $data);
    }

    public function donations(){
        if (!$this->user) {
            redirect(base_url('?redirect=/members/donations'));
            return;
        }
        $this->dashboard_dropdown = $this->user;

        $data['user'] = $this->user;

        $this->htmlTitle = 'Donation History';
        $data['main_content'] = 'members/my_donations';

        $user               = $this->user;
        $data['simplifiedArray'] = $user->getDonationHistoryArray();
        $this->load->view('includes/user/template', $data);
    }

    public function petition_history(){
        if (!$this->user) {
            redirect(base_url('?redirect=/members/petition-history'));
            return;
        }
        $this->dashboard_dropdown = $this->user;

        $this->htmlTitle = 'Petition History';
        $data['main_content'] = 'members/petition-history';

        $data['signatures'] = $this->user->getMySignedChangeOrgPetitions();

        $this->load->view('includes/user/template', $data);
    }

    public function reviews() {
        if (!$this->user) {
            redirect(base_url('?redirect=/members/reviews'));
            return;
        }
        $this->dashboard_dropdown = $this->user;

        $data['user'] = $this->user;

        $this->htmlTitle = 'My Reviews';
        $data['main_content'] = 'members/my_reviews';


        $this->load->view('includes/user/template', $data);     
    }

    
    public function followers() {
        if (!$this->user) {
            redirect(base_url('?redirect=/members/followers'));
            return;
        }
        $this->dashboard_dropdown = $this->user;

        $data['user'] = $this->user;

        $this->htmlTitle = 'My Followers';
        $data['main_content'] = 'members/my_followers';

        $data['following'] = $this->user->getFollowing();
        $data['followers'] = $this->user->getFollowers();


        $this->load->view('includes/user/template', $data);
    }

    public function save_address() {
        if (!$this->user) {
            throw new Exception('not signed in.');
        }
        if (!isset($_POST['scope'])) {
            throw new Exception('Invalid request. Scope is not set.');
        }
        $scope = $_POST['scope'];
        if (!in_array($scope, ['add', 'edit'])) {
            throw new Exception('Bad scope: ' . $scope);
        }

        if (!isset($_POST['address1'])) {
            throw new Exception('Invalid request. Address1 is not set.');
        }
        if (!isset($_POST['zipcode'])) {
            throw new Exception('Invalid request. zipcode is not set.');
        }
        $zipcode = preg_replace("/[^0-9]/", "", $_POST['zipcode']);

        if (strlen($zipcode) != 5) {
            throw new Exception('Zipcode is ' . strlen($zipcode) . ' digits long expected 5 digits.');
        }


        if (!isset($_POST['state'])) {
            throw new Exception('Invalid request. state is not set.');
        }
        /** @var \Entity\CharityState $state */
        $state = \Entity\CharityState::find($_POST['state']);
        if (!$state) {
            throw new Exception('Invalid request. Bad state: ' . $_POST['state']);
        }

        if (!isset($_POST['city'])) {
            throw new Exception('Invalid request. state is not set.');
        }
        /** @var \Entity\CharityCity $city */
        $city = \Entity\CharityCity::find($_POST['city']);
        if (!$city) {
            throw new Exception('Invalid request. Bad city: ' . $_POST['city']);
        }

        if (!isset($_POST['phone'])) {
            throw new Exception('Invalid request. phone is not set.');
        }
        $phone = preg_replace("/[^0-9]/", "", $_POST['phone']);

        if (!in_array(strlen($phone), [10,11])) {
            throw new Exception('Phone number is ' . strlen($phone) . ' digits long expected 10 or 11 digits.');
        }

        if ($scope == 'add') {

            $ua = $this->user->addAddress(
                $city,
                $state,
                $phone,
                $zipcode,
                $_POST['address1'],
                isset($_POST['address2']) && $_POST['address2'] ? $_POST['address2'] : null
            );

        } elseif ($scope == 'edit') {

            if (!isset($_POST['user_address_id'])) {
                throw new Exception('Invalid request. Cant edit when no user_address_id is provided.');
            }

            /** @var \Entity\UserAddress $ua */
            $ua = \Entity\UserAddress::findOneBy(['user_id' => $this->user->getId(), 'id' => $_POST['user_address_id']]);
            if (!$ua) {
                throw new Exception('Could not find user address with id: ' . $_POST['user_address_id']);
            }

            $ua->setCity($city);
            $ua->setState($state);
            $ua->setPhone($phone);
            $ua->setZipcode($zipcode);
            $ua->setAddress1($_POST['address1']);
            $ua->setAddress2($_POST['address2']);
            self::$em->persist($ua);
            self::$em->flush($ua);

        } else {
            throw new Exception('Bad scope. ' . $scope);
        }

        $this->user->setDefaultAddress($ua);

        self::$em->persist($this->user);
        self::$em->flush($this->user);

        echo json_encode([
                'success' => true,
                'user_address_id' => $ua->getId(),
                'addressesHtml' => $this->load->view('partials/_addresses', [], true)
            ]);
    }

    public function set_default_address() {
        if (!$this->user) {
            throw new Exception('not signed in.');
        }

        if (!isset($_POST['user_address_id'])) {
            throw new Exception('Invalid request. Missing user_address_id.');
        }

        /** @var \Entity\UserAddress $ua */
        $ua = \Entity\UserAddress::findOneBy(['user_id' => $this->user->getId(), 'id' => $_POST['user_address_id']]);
        if (!$ua) {
            throw new Exception('Could not find user address with id: ' . $_POST['user_address_id']);
        }

        $this->user->setDefaultAddress($ua);

        self::$em->persist($this->user);
        self::$em->flush($this->user);

        echo json_encode(['success' => true]);
    }

    public function post_to_activity_feed() {
        if (!$this->user) {
            throw new Exception('not signed in.');
        }

        $required = [
            'to_user_id',
            'text',
            'context',
            'charity_id',
            'temp_id',
            'youtube_video_ids'
        ];

        foreach($required as $req) {
            if (!isset($_POST[$req])) {
                throw new Exception('Invalid request. Missing ' . $req . ' parameter.');
            }
        }



        /** @var User $to */
        $to = User::find($_POST['to_user_id']);
        if (!$to) {
            throw new Exception('Bad to_user_id: ' . $_POST['to_user_id']);
        }

        if ($_POST['charity_id']) {
            /** @var \Entity\Charity $charity */
            $charity = Charity::find($_POST['charity_id']);
            if (!$charity) {
                throw new Exception('Could not load charity. charity_id: ' . $_POST['charity_id']);
            }
        } else {
            $charity = null;
        }

        /** @var \Entity\ActivityFeedPostImage[] $images */
        $images = \Entity\ActivityFeedPostImage::findBy([
                'temp_id' => $_POST['temp_id'],
                'user_id' => $this->user->getId()
            ]);

        if ((!is_array($_POST['youtube_video_ids']) || !$_POST['youtube_video_ids']) && !$images && !$charity && !strlen($_POST['text'])) {
            echo json_encode(['success' => false, 'message' => 'Text cannot be empty.']);
            return;
        }

        if (strlen($_POST['text']) > 10000) {
            echo json_encode(['success' => false, 'message' => 'Text is too long. Maximum 10000 characters. You entered '.strlen($_POST['text']).'.']);
            return;
        }

        if (!in_array($_POST['context'], ['my', 'other'])) {
            throw new Exception('Invalid context: ' . $_POST['context'] . ' must be my/other');
        }

        $activity_feed_post = new \Entity\ActivityFeedPost();
        $activity_feed_post->setFromUser($this->user);
        $activity_feed_post->setToUser($to);
        $activity_feed_post->setText($_POST['text']);

        if (isset($_POST['external_url_id'])) {
            /** @var \Entity\ExternalUrl $external_url */
            $external_url = \Entity\ExternalUrl::find($_POST['external_url_id']);
        } else {
            $external_url = null;
        }
        $activity_feed_post->setExternalUrl($external_url);

        $activity_feed_post->setCharity($charity);

        self::$em->persist($activity_feed_post);
        self::$em->flush($activity_feed_post);

        foreach($images as $image) {
            $image->setTempId(null);
            $image->setActivityFeedPost($activity_feed_post);
            self::$em->persist($image);
            self::$em->flush($image);
        }

        if (is_array($_POST['youtube_video_ids'])) {
            foreach($_POST['youtube_video_ids'] as $youtube_video_id) {
                $youtube_video = new \Entity\ActivityFeedPostYoutubeVideo();
                $youtube_video->setVideoId($youtube_video_id);
                $youtube_video->setActivityFeedPost($activity_feed_post);
                $youtube_video->setTitleAndDescription();
                self::$em->persist($youtube_video);
                self::$em->flush($youtube_video);
            }
        }
        $html = $this->load->view('/members/_activity', ['activity' => $activity_feed_post, 'context' => $_POST['context']], true);

        do {
            $temp_id = crc32(rand());
            $temp_id_exists = \Entity\ActivityFeedPostImage::findOneBy(['temp_id' => $temp_id]);
        } while($temp_id_exists);

        echo json_encode(['success' => true, 'html' => $html, 'temp_id' => $temp_id, 'post_id' => $activity_feed_post->getScrambledId()]);
    }

    public function activity_feed_post_comment() {
        if (!$this->user) {
            throw new Exception('User is not signed in.');
        }
        if (!isset($_POST['text'])) {
            throw new Exception('Missing text.');
        }
        if (!isset($_POST['entity_id'])) {
            throw new Exception('Missing post_id');
        }

        if (!isset($_POST['context'])) {
            throw new Exception('Context missing.');
        }

        if (!isset($_POST['entity'])) {
            throw new Exception('Entity is missing');
        }

        if (!in_array($_POST['entity'], \Entity\ActivityFeedPostComment::$entities)) {
            throw new Exception('Invalid entity: ' . $_POST['entity'] . ' should be one of: ' . join(', ', \Entity\ActivityFeedPostComment::$entities));
        }

        $class = '\\Entity\\'.$_POST['entity'];
        /** @var \Entity\BaseEntity $post */
        $entity = $class::find($_POST['entity_id']);

        if (!$entity) {
            throw new Exception('Could not load entity. entity_id: ' . $_POST['entity_id'] . ' entity: ' . $_POST['entity']);
        }

        if (isset($_POST['external_url_id'])) {
            /** @var \Entity\ExternalUrl $external_url */
            $external_url = \Entity\ExternalUrl::find($_POST['external_url_id']);
        } else {
            $external_url = null;
        }

        $comment = new \Entity\ActivityFeedPostComment();
        $comment->setUser($this->user);
        $comment->setEntity($entity);
        $comment->setText($_POST['text']);
        $comment->setExternalUrl($external_url);
        self::$em->persist($comment);
        self::$em->flush($comment);

        if (is_array($_POST['youtube_video_ids'])) {
            foreach($_POST['youtube_video_ids'] as $youtube_video_id) {
                $youtube_video = new \Entity\ActivityFeedPostYoutubeVideo();
                $youtube_video->setVideoId($youtube_video_id);
                $youtube_video->setActivityFeedPostComment($comment);
                $youtube_video->setTitleAndDescription();
                self::$em->persist($youtube_video);
                self::$em->flush($youtube_video);
            }
        }

        $comments = \Entity\ActivityFeedPostComment::getComments($entity);

        $html = $this->load->view('/members/_activity_feed_post_comments', ['entity' => $entity, 'comments' => $comments], true);

        echo json_encode([
            'success' => true,
            'html' => $html,
            'comment_share_like_indicators' => $this->load->view('/members/_activity_feed_post_comment_share_like_indicators', ['entity' => $entity], true)
        ]);
    }

    public function delete_activity_feed_post_comment() {
        if (!$this->user) {
            throw new Exception('User is not signed in.');
        }

        if (!isset($_POST['comment_id'])) {
            throw new Exception('missing comment_id');
        }

        /** @var \Entity\ActivityFeedPostComment $comment */
        $comment = \Entity\ActivityFeedPostComment::find($_POST['comment_id']);

        if (!$comment) {
            throw new Exception('failed loading comment: '. $_POST['comment_id']);
        }

        $entity = $comment->getEntity();
        if ($comment->getUser() != $this->user) {
            throw new Exception('Comment does not belong to user. signed-in:' . $this->user->getId() . ' comment-user-id: ' . $comment->getUser()->getId() . ' comment-id: ' . $comment->getId());
        }

        $comment->setIsDeleted(1);

        self::$em->persist($comment);
        self::$em->flush($comment);

        echo json_encode([
            'success' => true,
            'comment_share_like_indicators' => $this->load->view('/members/_activity_feed_post_comment_share_like_indicators', ['entity' => $entity], true)
        ]);
    }

    public function activity_like() {
        if (!$this->user) {
            throw new Exception('User is not signed in.');
        }
        if (!isset($_POST['like'])) {
            throw new Exception('like is missing.');
        }
        if (!isset($_POST['entity_id'])) {
            throw new Exception('Missing post_id');
        }
        if (!isset($_POST['entity'])) {
            throw new Exception('Entity is missing');
        }

        if (!in_array($_POST['entity'], \Entity\ActivityFeedPostLike::$entities)) {
            throw new Exception('Invalid entity: ' . $_POST['entity'] . ' should be one of: ' . join(', ', \Entity\ActivityFeedPostLike::$entities));
        }

        $class = '\\Entity\\'.$_POST['entity'];
        /** @var \Entity\BaseEntity $post */
        $entity = $class::find($_POST['entity_id']);

        if (!$entity) {
            throw new Exception('Could not load entity. entity_id: ' . $_POST['entity_id'] . ' entity: ' . $_POST['entity']);
        }

        $existing_like = \Entity\ActivityFeedPostLike::findOneBy([
                'user' => $this->user,
                'entityName' => $_POST['entity'],
                'entityId' => $_POST['entity_id']
            ]);

        if ($_POST['like'] && !$existing_like) {
            $new_like = new \Entity\ActivityFeedPostLike();
            $new_like->setEntity($entity);
            $new_like->setUser($this->user);
            self::$em->persist($new_like);
            self::$em->flush($new_like);
        } elseif (!$_POST['like'] && $existing_like) {
            self::$em->remove($existing_like);
            self::$em->flush();
        }

        echo json_encode([
            'success' => true,
            'comment_share_like_indicators' => $this->load->view('/members/_activity_feed_post_comment_share_like_indicators', ['entity' => $entity], true)
        ]);
    }

    public function get_recurring_donations() {
        if (!$this->user) {
            throw new Exception('User not signed in!');
        }
        $ret = NetworkForGood::getRecurringDonations($this->user);

        $json = ['success' => false];

        if (!$ret['success']) {
            $json['success'] = false;
            $json['msg'] = $ret['msg'];
        } else {
            $json['success'] = true;
            $json['html'] = $this->load->view('members/_recurring_donations', ['recurring_donations' => $ret['recurring_donations']], true);
        }

        echo json_encode($json);
    }

    public function cancel_recurring_donation() {
        if (!$this->user) {
            throw new Exception('User not signed in!');
        }
        if (!isset($_POST['recurringDonationId'])) {
            throw new Exception('Recurring donation id is missing!');
        }

        /** @var RecurringDonation $recurring_donation */
        $recurring_donation = RecurringDonation::findOneBy(['user_id' => $this->user->getId(), 'id' => $_POST['recurringDonationId']]);
        if (!$recurring_donation) {
            throw new Exception('Could not find recurring donation. user_id: ' . $this->user->getId() . ' id: ' . $_POST['recurringDonationId']);
        }

        $ret = NetworkForGood::cancelRecurringDonation($this->user, $recurring_donation);

        $json = ['success' => false];

        if (!$ret['success']) {
            $json['success'] = false;
            $json['msg'] = $ret['msg'];
        } else {
            $json['success'] = true;
        }

        echo json_encode($json);
    }

    public function recurring_donation_notify() {
        if (!$this->user) {
            throw new Exception('User is not signed in!');
        }
        if (!isset($_POST['notify'])) {
            throw new Exception('Invalid request. missing notify.');
        }
        if (!isset($_POST['recurring_donation_id'])) {
            throw new Exception('Invalid request. missing recurring_donation_id.');
        }
        /** @var RecurringDonation $recurring_donation */
        $recurring_donation = RecurringDonation::findOneBy(['user_id' => $this->user->getId(), 'id' => $_POST['recurring_donation_id']]);
        if (!$recurring_donation) {
            throw new Exception('Could not find recurring donation. user_id: ' . $this->user->getId() . ' id: ' . $_POST['recurring_donation_id']);
        }

        $recurring_donation->setNotify($_POST['notify']);
        \Base_Controller::$em->persist($recurring_donation);
        \Base_Controller::$em->flush($recurring_donation);

        echo json_encode(['success' => true]);
    }

    public function bet_friends_friend() {
        if (!isset($_GET['search'])) {
            throw new Exception('search is missing.');
        }

        if (!$this->user) {
            throw new Exception('user is not signed in');
        }

        $fb_friends_apc_key = 'fb_friends_'.$this->user->getId();

        $fb_friends = apc_fetch($fb_friends_apc_key, $success);

        if (!$success || !$fb_friends) {
            $fb_friends = [];

            if ($this->session->userdata('fbAccessToken')) {
                $fbRequest = @file_get_contents($url = 'https://graph.facebook.com/me/friends?access_token=' . $this->session->userdata('fbAccessToken'));
                if ($fbRequest === false) {
                    $data['fbError'] = true;
                } else {
                    $json = json_decode($fbRequest, true);
                    while (isset($json['data']) && is_array($json['data']) && !empty($json['data'])) {
                        $fb_friends = array_merge($fb_friends, $json['data']);
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

            if ($fb_friends) {
                foreach ($fb_friends as $k => $fb_friend) {
                    \Entity\FacebookFriend::createOrUpdate($fb_friend);
                    $user = User::findByFbUserId($fb_friend['id'], self::$em);
                    if ($user) {
                        unset($fb_friends[$k]);
                    }
                }
            }
            apc_store($fb_friends_apc_key, $fb_friends, 60*60);
        }

        $filtered_fb_friends = [];
        foreach($fb_friends as $fb_friend) {
            if (preg_match('#^'.addslashes($_GET['search']).'#i', $fb_friend['name'])) {
                $filtered_fb_friends[] = \Entity\FacebookFriend::findOneBy(['fb_id' => $fb_friend['id']]);
            }
        }

        $results = User::findSphinx('^'.$_GET['search'].'*');

        $results = $this->load->view('bets/_bet_friends_friends', ['users' => $results['users'], 'fb_friends' => $filtered_fb_friends], true);
        echo json_encode(['success' => true, 'search' => $_GET['search'], 'results' => $results]);
    }

    public function bet_friends_charity() {
        if (!isset($_GET['search'])) {
            throw new Exception('search is missing.');
        }

        if (!$this->user) {
            throw new Exception('user is not signed in');
        }

        $search_string = \Common::fixWordForms($_GET['search']);

        if (preg_match('#\bals\b#i', $search_string)) {
            $results = Charity::findSphinxQuery('@fieldName '.preg_replace('#\bals\b#i', '(als | amyotrophic lateral sclerosis)', $search_string));
        } else {
            $results = Charity::findSphinxQuery('@fieldName *'.$search_string.'*');
        }

        $results = $this->load->view('bets/_bet_friends_charities', ['charities' => $results['charities']], true);
        echo json_encode(['success' => true, 'search' => $_GET['search'], 'results' => $results]);
    }

	public function search_charities() {
        if (!isset($_GET['search'])) {
            throw new Exception('search is missing.');
        }


        $results = Charity::findSphinx($_GET['search'], '', 'relevance',0,4);

        $results = $this->load->view(
                              'members/_member_nonprofits.php',
                              [
                                'nonprofits' => $results['content_relevance'],
                                'total_results' => $results['result_count'],
                                'search_text' => $_GET['search'],
                              ],
                              true
        );
        echo json_encode(['success' => true, 'search' => $_GET['search'], 'results' => $results]);

    }

    public function search_petitions() {
        if (!isset($_GET['search'])) {
            throw new Exception('search is missing.');
        }


        $resultPetitions = ChangeOrgPetition::findSphinx($_GET['search'], 'relevance', 0, 4);

        $results = $this->load->view(
                              'members/_member_petitions',
                              [
                                  'petitions' => $resultPetitions['content_relevance'],
                                  'total_results' => $resultPetitions['result_count'],
                                  'search_text' => $_GET['search'],
                              ],
                              true
        );

        echo json_encode(['success' => true, 'search' => $_GET['search'], 'results' => $results]);
    }

    public function bets() {
        if (!$this->user) {
            redirect(base_url('?redirect=/members/bets'));
            return;
        }
        $this->dashboard_dropdown = $this->user;
        $this->htmlTitle = 'Bets';
        $data['main_content'] = 'members/bets';

        $this->load->view('includes/user/template', $data);
    }

    public function count_givercards_view() {
        if (!$this->user) {
            throw new Exception('user is not signed in');
        }

        $giverCardsView = new \Entity\GiverCardsView();
        $checkUserClicked = \Entity\GiverCardsView::findOneBy(array('user_id' => $this->user->getId()));

        //Show number of times users click on Giver Cards link to Admin person
        $countmsg = "";
        if($this->user->getLevel() >= 4) {

            $this->db->select('count(id) as cnt');
            $query = $this->db->get('giver_cards_view');
            $res = $query->result_array();
            $count = $res[0]['cnt'];
            $countmsg = "Number of Users click on this link = ".$count;
        }

        if(!$checkUserClicked && $this->user->getLevel() < 4) {

            $giverCardsView->setUser($this->user);
            $giverCardsView->setTimeChecked(date('Y-m-d H:i:s'));
            \Base_Controller::$em->persist($giverCardsView);
            \Base_Controller::$em->flush($giverCardsView);
            $response = array(
                'success' => true,
                'msg' => 'Count added successfully',
                'countmsg' => $countmsg,
            );
        } else {

            $response = array(
                'success' => false,
                'msg' => 'You have already clicked the link or you are admin',
                'countmsg' => $countmsg,
            );
        }
        echo json_encode($response);
    }

    public function hide_activity_feed_post() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['post_id'])) {
            throw new Exception('Invalid request.');
        }

        /** @var \Entity\ActivityFeedPost $post */
        $post = \Entity\ActivityFeedPost::find($_POST['post_id']);

        $post->hideFromUser($this->user);

        echo json_encode(['success' => true]);
    }

    public function undo_hide_activity_feed_post() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['post_id'])) {
            throw new Exception('Invalid request.');
        }

        /** @var \Entity\ActivityFeedPost $post */
        $post = \Entity\ActivityFeedPost::find($_POST['post_id']);

        $post->undoHideFromUser($this->user);

        echo json_encode([
            'success' => true,
            'html' => $this->load->view('/members/_activity_feed_post', ['activity_feed_post' => $post, 'context' => 'my'], true)
        ]);
    }

    public function delete_activity_feed_post() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['post_id'])) {
            throw new Exception('Invalid request.');
        }

        /** @var \Entity\ActivityFeedPost $post */
        $post = \Entity\ActivityFeedPost::find($_POST['post_id']);

        $post->deleteFromUser($this->user);

        echo json_encode(['success' => true]);
    }

    public function undo_delete_activity_feed_post() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['post_id'])) {
            throw new Exception('Invalid request.');
        }

        /** @var \Entity\ActivityFeedPost $post */
        $post = \Entity\ActivityFeedPost::find($_POST['post_id']);

        $post->undoDeleteFromUser($this->user);

        echo json_encode([
            'success' => true,
            'html' => $this->load->view('/members/_activity_feed_post', ['activity_feed_post' => $post, 'context' => 'my'], true)
        ]);
    }

	public function giver_cards() {
		if (!$this->user) {
            throw new Exception('user is not signed in!');
        }

        if ( !isset($_POST['existing_email']) && !isset($_POST['new_email']) && !isset($_POST['new_name']) ) {
            throw new Exception('Please select existing user or enter new user\'s name and email address');
        }

		$giverCardArray = array();

		if ( isset($_POST['existing_email']) ) {
			if (isset($_POST['user_id'])) {
		        if (!$_POST['facebook_friend']) {
		            $user = User::find($_POST['user_id']);
		            if (!$user) {
		                throw new Exception('Could not find user provided in user_id: ' . $_POST['user_id']);
		            }
					$giverCardArray['givercard_recipient_fname'] = $user->getFname();
					$giverCardArray['existing_user_id'] = $_POST['user_id'];
					$giverCardArray['existing_user_email'] = $user->getEmail();

		        } else {
		            $fb_friend = \Entity\FacebookFriend::find($_POST['user_id']);
		            if (!$fb_friend) {
		                throw new Exception('Could not find facebook_friend provided in user_id: ' . $_POST['user_id']);
		            }

					$giverCardArray['givercard_recipient_fname'] = $fb_friend->getName();
					$giverCardArray['existing_user_id'] = "";
					$giverCardArray['existing_user_email'] = "";
					$giverCardArray['fb_user'] = $_POST['user_id'];
					$giverCardArray['fb_user_id'] = $fb_friend->getFbId();
		        }

		    } else {
		        throw new Exception('user_id is not set.');
		    }
		} else {
		
			if ( !isset($_POST['new_name']) ) {
				throw new Exception('Please enter name for new user.');
			}
			if ( !isset($_POST['new_email']) ) {
				throw new Exception('Please enter email address for new user.');
			}

			if ( isset($_POST['new_email']) && $_POST['new_email'] != "" ) {

				if ( filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL) ) {
					// Valid
				} else {
					throw new Exception('Please enter a valid email address');
				}
				$giverCardArray['givercard_recipient_fname'] = $_POST['new_name'];
			}
		}

        if ( !isset($_POST['donation_amount']) ) {
            throw new Exception('Please enter donation amount');
        }
		if ( isset($_POST['donation_amount']) > 10 ) {
            throw new Exception('Please enter donation amount greater than $10');
		}

        if (!isset($_POST['givercard_message'])) {
            throw new Exception('Please enter GiverCard message');
        }

		if ( isset($_POST['new_email']) && $_POST['new_email'] != "" ) {
			$giverCardArray['new_email'] = $_POST['new_email'];
		}
		if ( isset($_POST['new_name']) && $_POST['new_name'] != "" ) {
			$giverCardArray['new_name'] = $_POST['new_name'];
		}

		$giverCardArray['donation_amount'] = $_POST['donation_amount'];
		$giverCardArray['givercard_message'] = $_POST['givercard_message'];

		echo json_encode(['success' => true, 'giverCardArray' => $giverCardArray ]);
	}

	public function validate_donation_amount($donation_amount) {

		if ($donation_amount < 10) {
			$this->form_validation->set_message('validate_donation_amount', 'Please enter donation amount greater than $10');
			return false;
		} else {
			return true;
		}
	}
		
	public function required_inputs() {
		if ( ! $this->input->post('existing_email') AND ! $this->input->post('new_email') ) {
            $this->form_validation->set_message('required_inputs', 'Please select existing user or enter new user\'s email');
            return false;
        }
        return true;
	}

    public function my_petitions() {
        if (!$this->user) {
            redirect('/');
        }

        $data = ['main_content' => '/members/my_petitions'];
        $this->load->view('includes/user/template', $data);
    }

    public function my_challenges() {
        if (!$this->user) {
            redirect('/');
        }
        $this->dashboard_dropdown = $this->user;

        $data = [
            'main_content' => '/members/my_challenges',
            'challenges' => $this->user->getMyChallenges(),
        ];
        $this->load->view('includes/user/template', $data);
    }

    public function save_setting() {
        if (!$this->user) {
            throw new Exception('need signed in');
        }

        if (!isset($_POST['name'])) {
            throw new Exception('missing name');
        }

        if (!isset($_POST['value'])) {
            throw new Exception('missing value');
        }

        $this->user->setSetting($_POST['name'], $_POST['value']);

        echo json_encode(['success' => true]);
    }

    public function load_nonprofit_feed() {
        echo json_encode([
            'success' => true,
            'feed_html' => $this->load->view('/members/_nonprofit_feed', ['user' => $this->user], true)
        ]);
    }

    public function load_petition_feed() {
        echo json_encode([
            'success' => true,
            'feed_html' => $this->load->view('/members/_petition_feed', ['user' => $this->user], true)
        ]);
    }

    public function get_givercoin() {
        if (!isset($_GET['user_id'])) {
            throw new Exception('invalid request. missing user_id');
        }

        /** @var \Entity\User $user */
        $user = \Entity\User::find($_GET['user_id']);

        if (!$user) {
            throw new Exception('invalid user_id: ' . $_GET['user_id']);
        }

        echo round($user->getScore(),2);
    }

    public function hide_activity() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }
        if (!isset($_POST['id'])) {
            throw new Exception('missing id.');
        }
        if (!isset($_POST['type'])) {
            throw new Exception('missing type.');
        }

        $existing = \Entity\ActivityHide::findOneBy(['user' => $this->user, 'activityId' => $_POST['id'], 'activityType' => $_POST['type']]);
        if ($existing) {
            echo json_encode(['success' => true]);
            return;
        }

        $hide = new \Entity\ActivityHide;
        $hide->setUser($this->user);
        $hide->setActivityId($_POST['id']);
        $hide->setActivityType($_POST['type']);

        self::$em->persist($hide);
        self::$em->flush($hide);

        echo json_encode(['success' => true]);
    }

    public function undo_hide_activity() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }
        if (!isset($_POST['id'])) {
            throw new Exception('missing id.');
        }
        if (!isset($_POST['type'])) {
            throw new Exception('missing type.');
        }

        $existing = \Entity\ActivityHide::findOneBy(['user' => $this->user, 'activityId' => $_POST['id'], 'activityType' => $_POST['type']]);
        if ($existing) {
            self::$em->remove($existing);
            self::$em->flush();
        }

        echo json_encode(['success' => true, 'html' => \Entity\ActivityHide::getHtml($_POST['id'], $_POST['type'])]);
    }

    public function refresh_feed() {
        if (!isset($_GET['latest_activity_id'])) {
            throw new Exception('missing latest_activity_id');
        }
        if (!isset($_GET['user_id'])) {
            throw new Exception('missing user_id');
        }
        if (!isset($_GET['context'])) {
            throw new Exception('missing context');
        }

        /** @var \Entity\User $user */
        $user = \Entity\User::find($_GET['user_id']);
        $activities = UserActivityFeed::get($user, 0, 50, $_GET['context']);


        $new = [];

        foreach($activities as $activity) {
            $id = get_class($activity).$activity->getId();
            if ($id == $_GET['latest_activity_id']) {
                break;
            }
            $new[] = $activity;
        }

        $html = '';

        foreach($new as $n) {
            $html .= $this->load->view('/members/_activity', ['context' => $_GET['context'], 'activity' => $n], true);
        }

        echo json_encode(['success' => true, 'html' => $html]);
    }

    public function toggle_hide_donation_or_petition() {
        if (!$this->user) {
            throw new Exception('user not signed in');
        }

        if (!isset($_POST['type'])) {
            throw new Exception('missing type');
        }

        if (!in_array($_POST['type'], ['donation', 'petition'])) {
            throw new Exception('invalid type: ' . $_POST['type']);
        }

        if (!isset($_POST['id'])) {
            throw new Exception('missing id');
        }

        if (!isset($_POST['pub'])) {
            throw new Exception('missing pub');
        }

        if (!in_array($_POST['pub'], ['1', '0'])) {
            throw new Exception('invalid pub: ' . $_POST['pub']);
        }

        if ($_POST['type'] === 'donation') {
            /** @var \Entity\Donation $donation */
            $donation = \Entity\Donation::find($_POST['id']);

            if ($donation->getUserId() != $this->user->getId()) {
                throw new Exception('donation does not belong to user. user-id: ' . $this->user->getId() . ' donation-id: ' . $donation->getId());
            }
            $donation->setHidden($_POST['pub'] ? 0 : 1);
            self::$em->persist($donation);
            self::$em->flush($donation);
        } elseif ($_POST['type'] == 'petition') {
            /** @var \Entity\UserPetitionSignature $signature */
            $signature = \Entity\UserPetitionSignature::find($_POST['id']);
            if ($signature->getUserId() != $this->user->getId()) {
                throw new Exception('signature does not belong to user. user-id: ' . $this->user->getId() . ' signature-id: ' . $signature->getId());
            }
            $signature->setHidden($_POST['pub'] ? 0 : 1);
            self::$em->persist($signature);
            self::$em->flush($signature);
        }

        echo json_encode(['success' => true]);
    }

    public function delete_account() {
        if (!$this->user) {
            throw new Exception('not signed in');
        }

        try {
            self::$em->remove($this->user);
            self::$em->flush();
            $this->session->sess_destroy();
            echo json_encode(['success' => true]);
        } catch(Exception $e) {
            mail('admin@giverhub.com', 'failed to delete user', 'user-id: ' . $this->user->getId() . 'email: ' . $this->user->getEmail() . ' e: ' . $e->getMessage());
            echo json_encode(['success' => true, 'failed' => true]);
        }
    }

    public function messages() {
        if (!$this->user) {
            redirect(base_url('?redirect=/members/messages'));
            return;
        }
        $this->dashboard_dropdown = $this->user;
        $this->htmlTitle = 'Messages';
        $data['main_content'] = 'members/messages';
        $this->body_class .= ' minw800';
        $this->load->view('includes/user/template', $data);
    }

    public function send_message() {
        if (!$this->user) {
            throw new Exception('not signed in.');
        }

        if (isset($GLOBALS['HTTP_RAW_POST_DATA']) && $GLOBALS['HTTP_RAW_POST_DATA']) {
            $post = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
        } else {
            throw new Exception('missing post.');
        }

        if (!isset($post['entity_id'])) {
            throw new Exception('missing entity_id');
        }

        if (!isset($post['entity_type'])) {
            throw new Exception('missing entity_type');
        }

        if (!in_array($post['entity_type'], ['user', 'charity'])) {
            throw new Exception('entity_type invalid: ' . $post['entity_type']);
        }

        if (!isset($post['msg'])) {
            throw new Exception('missing msg');
        }

        if (!isset($post['tmp_id'])) {
            throw new Exception('missing tmp_id');
        }

        if ($post['entity_type'] == 'user') {
            /** @var \Entity\User $to */
            $to = \Entity\User::find($post['entity_id']);
        } else {
            /** @var \Entity\Charity $to */
            $to = \Entity\Charity::find($post['entity_id']);
        }

        if (!$to) {
            throw new Exception('could not load to: ' . $post['entity_id'] . ' type: ' . $post['entity_type']);
        }

        if (!$post['msg']) {
            throw new Exception('invalid msg');
        }

        if (!$post['tmp_id']) {
            throw new Exception('invalid tmp_id');
        }



        $chat = new \Entity\Chat;

        if ($to instanceof \Entity\User) {
            $chat->setToUser( $to );
        } elseif ($to instanceof \Entity\Charity) {
            $chat->setToCharity($to);
        }

        if (isset($post['from_charity_id'])) {
            /** @var \Entity\Charity $charity */
            $charity = \Entity\Charity::find($post['from_charity_id']);
            if (!$charity) {
                throw new Exception('failed to load from_charity_id: ' . $post['from_charity_id']);
            }

            if (!$this->user->isCharityAdmin($charity)) {
                throw new Exception('user is not charity admin. user-id: ' . $this->user->getId() . ' charity: ' . $charity->getId());
            }

            $chat->setFromCharity($charity);

            if (isset($post['volunteer_id'])) {
                /** @var \Entity\CharityVolunteeringOpportunityVolunteer $volunteer */
                $volunteer = \Entity\CharityVolunteeringOpportunityVolunteer::find($post['volunteer_id']);
                $volunteer->setStatus('accepted');
                self::$em->persist($volunteer);
                self::$em->flush($volunteer);
                $chat->setVolunteer($volunteer);
            }
        }

        $chat->setMessage($post['msg']);
        $chat->setFromUser($this->user);
        $chat->setTimeSent(new \DateTime());
        $chat->setTmpId($post['tmp_id']);

        self::$em->persist($chat);
        self::$em->flush($chat);

        echo json_encode(['success' => true,'message' => $chat]);
    }

    public function new_messages($last_message_id = null) {
        if (!$this->user) {
            echo json_encode(['success' => true, 'messages' => []]);
            return;
        }

        $charity = null;
        if (isset($_GET['charity_id'])) {
            /** @var \Entity\Charity $charity */
            $charity = \Entity\Charity::find($_GET['charity_id']);
            if (!$charity) {
                throw new Exception('charity could not be loaded: ' . $_GET['charity_id']);
            }
            if (!$this->user->isCharityAdmin($charity)) {
                throw new Exception('not admin: ' . $charity->getId() . ' user-id: ' . $this->user->getId());
            }
        }

        echo json_encode(['success' => true, 'messages' => $this->user->getNewMessages($last_message_id, $charity)]);
    }

    public function seen_messages() {
        if (!$this->user) {
            throw new Exception('not signed in.');
        }

        if (isset($GLOBALS['HTTP_RAW_POST_DATA']) && $GLOBALS['HTTP_RAW_POST_DATA']) {
            $post = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
        } else {
            throw new Exception('missing post.');
        }

        if (!isset($post['seen_ids'])) {
            throw new Exception('missing seen_ids');
        }

        if (!isset($post['seen_ids'])) {
            throw new Exception('missing seen_ids');
        }

        if (!is_array($post['seen_ids']) || !$post['seen_ids']) {
            throw new Exception('seen_ids invalid.');
        }

        if (isset($post['charity_id'])) {
            /** @var \Entity\Charity $charity */
            $charity = \Entity\Charity::find($post['charity_id']);
            if (!$charity) {
                throw new Exception('could not load charity: ' . $post['charity_id']);
            }
            if (!$this->user->isCharityAdmin($charity)) {
                throw new Exception('denied. charity_id: ' . $post['charity_id'] . ' user_id: ' . $this->user->getId());
            }
        }

        foreach($post['seen_ids'] as $chat_id) {
            /** @var \Entity\Chat $chat */
            $chat = \Entity\Chat::find($chat_id);

            if ($chat->getToUser() == $this->user || (isset($charity) && $chat->getToCharity() == $charity)) {
                $chat->setTimeSeen(new \DateTime());
                self::$em->persist($chat);
                self::$em->flush($chat);
            }
        }

        echo json_encode(['success' => true]);
    }

    public function send_vol_request() {
        if (!$this->user) {
            throw new Exception('user not signed in');
        }

        if (!isset($_POST['event_id'])) {
            throw new Exception('event_id missing');
        }

        /** @var \Entity\CharityVolunteeringOpportunity $event */
        $event = \Entity\CharityVolunteeringOpportunity::find($_POST['event_id']);

        if (!$event) {
            throw new Exception('event could not be loaded. event_id: ' . $_POST['event_id']);
        }

        if (!isset($_POST['msg'])) {
            throw new Exception('msg is missing');
        }

        $volunteer = \Entity\CharityVolunteeringOpportunityVolunteer::findOneBy(['user' => $this->user, 'charityVolunteeringOpportunity' => $event]);
        if (!$volunteer) {
            $volunteer = new \Entity\CharityVolunteeringOpportunityVolunteer();
            $volunteer->setUser($this->user);
            $volunteer->setCharityVolunteeringOpportunity($event);
            self::$em->persist($volunteer);
            self::$em->flush($volunteer);
        }

        $chat = new \Entity\Chat;
        $chat->setFromUser($this->user);
        $chat->setToCharity($event->getCharity());
        $chat->setTimeSent(new \DateTime());
        $chat->setMessage(trim($_POST['msg']));
        $chat->setTmpId(uniqid());
        $chat->setVolunteer($volunteer);

        self::$em->persist($chat);
        self::$em->flush($chat);

        echo json_encode(['success' => true]);
    }
}
<?php
namespace Entity;

require_once(__DIR__ . '/../../libraries/sphinxapi.php');
require_once(__DIR__ . '/../../helpers/NetworkForGood.php');
require_once(__DIR__ . '/../UserActivityFeed.php');

use \UserActivityFeed;

/**
 * User
 *
 * @Table(name="users")
 * @Entity @HasLifecycleCallbacks
 */
class User extends BaseEntity implements \JsonSerializable {

    private $deletedId;

    /**
     * @PreRemove
     */
    public function preRemove() {
        $em = \Base_Controller::$em;
        foreach(BetFriend::findBy(['user' => $this]) as $bet_friend) {
            $em->remove($bet_friend);
            $em->flush();
        }
        foreach(Bet::findBy(['user' => $this]) as $bet) {
            $em->remove($bet);
            $em->flush();
        }
        foreach(ChallengeUser::findBy(['user' => $this]) as $challenge_user) {
            $em->remove($challenge_user);
            $em->flush();
        }
        foreach(Challenge::findBy(['fromUser' => $this]) as $challenge_user) {
            $em->remove($challenge_user);
            $em->flush();
        }
        foreach(ActivityFeedPost::findBy(['from_user_id' => $this->id]) as $post) {
            $em->remove($post);
            $em->flush();
        }
        foreach(ActivityFeedPost::findBy(['to_user_id' => $this->id]) as $post) {
            $em->remove($post);
            $em->flush();
        }
        foreach(ActivityHide::findBy(['user' => $this]) as $post) {
            $em->remove($post);
            $em->flush();
        }
        foreach(Badge::findBy(['user_id' => $this->id]) as $post) {
            $em->remove($post);
            $em->flush();
        }
        foreach(UserFollower::findBy(['followed_user_id' => $this->id]) as $post) {
            $em->remove($post);
            $em->flush();
        }
        foreach(UserFollower::findBy(['follower_user_id' => $this->id]) as $post) {
            $em->remove($post);
            $em->flush();
        }
        foreach(UserPetitionSignature::findBy(['user_id' => $this->id]) as $post) {
            $em->remove($post);
            $em->flush();
        }
        foreach(Donation::findBy(['userId' => $this->id]) as $donation) {
            $em->remove($donation);
            $em->flush();
        }
        foreach(FacebookLike::findBy(['user_id' => $this->id]) as $donation) {
            $em->remove($donation);
            $em->flush();
        }

        foreach(Chat::findBy(['fromUser' => $this]) as $chat) {
            $em->remove($chat);
            $em->flush();
        }
        foreach(Chat::findBy(['toUser' => $this]) as $chat) {
            $em->remove($chat);
            $em->flush();
        }

        /** @var User $removed */
        $removed = self::findOneBy(['email' => 'removed@giverhub.com']);
        foreach(PetitionSignatureRemovalRequest::findBy(['user'=>$this]) as $req) {
            /** @var PetitionSignatureRemovalRequest $req */
            $req->setUser($removed);
            $em->persist($req);
            $em->flush($req);
        }

        $this->default_user_address_id = null;
        $em->persist($this);
        $em->flush($this);
        foreach(UserAddress::findBy(['user_id'=>$this->id]) as $req) {
            $em->remove($req);
            $em->flush();
        }
        foreach(UserSetting::findBy(['user'=>$this]) as $req) {
            $em->remove($req);
            $em->flush();
        }

        $this->deletedId = $this->id;
    }

    /**
     * @PostRemove
     */
    public function postRemove() {
        $log = new UserDeletedLog();
        $log->setDate(new \DateTime());
        $log->setUserData(json_encode($this));
        $log->setUserId($this->deletedId);

        $em = \Base_Controller::$em;
        $em->persist($log);
        $em->flush($log);
    }

    const ACTIVITIES_PER_PAGE = 15;

    public function jsonSerialize() {
        $arr = [
            'id' => $this->id,
            'type' => 'user',
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'url' => $this->getUrl(),
            'imageUrl' => $this->getImageUrl(),
            'link' => $this->getLink(),
            'fnameLink' => $this->getFnameLink(),
            'giverCoin' => $this->getGiverCoin(),
        ];
        return $arr;
    }

    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="username", type="string", length=50, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @Column(name="fname", type="string", length=50, nullable=false)
     */
    private $fname;

    /**
     * @var string
     *
     * @Column(name="lname", type="string", length=50, nullable=false)
     */
    private $lname;

    /**
     * @var string
     *
     * @Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @Column(name="password", type="string", length=50, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @Column(name="image", type="string", length=50, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @Column(name="joined", type="string", length=50, nullable=false)
     */
    private $joined;

    /**
     * @var string
     *
     * @Column(name="capabilities", type="string", length=50, nullable=false)
     */
    private $capabilities;

    /**
     * @var string
     *
     * @Column(name="activation_key", type="string", length=50, nullable=false)
     */
    private $activation_key;

    /**
     * @var string
     *
     * @Column(name="fb_user_id", type="string", length=50, nullable=true)
     */
    private $fb_user_id;

    /**
     * @var string
     *
     * @Column(name="google_user_id", type="string", length=50, nullable=true)
     */
    private $google_user_id;

    /**
     * @var string
     * @Column(name="checkedNotifications", type="string")
     *
     */
    private $checkedNotifications;

    /**
     * @var integer
     * @Column(name="instant_donation_cof_id", type="integer")
     *
     */
    private $instant_donation_cof_id;

    /**
     * @var integer
     * @Column(name="no_instant_donation_confirmation_message", type="integer")
     *
     */
    private $no_instant_donation_confirmation_message;

    /**
     * @var integer
     * @Column(name="hide_unhide_donation", type="integer")
     *
     */
    private $hide_unhide_donation;

    /**
     * @var integer
     * @Column(name="hide_unhide_badges", type="integer")
     *
     */
    private $hide_unhide_badges;

    /**
     * @var string
     * @Column(name="password_changed", type="string")
     *
     */
    private $password_changed;

    /**
     * @var float
     * @Column(name="score", type="float")
     *
     */
    private $score = 0;

    /**
     * @var string
     * @Column(name="password_token", type="string")
     *
     */
    private $password_token;

    /**
     * @var string
     * @Column(name="retrieve_password_time", type="string")
     *
     */
    private $retrieve_password_time;

    /**
     * @var string
     * @Column(name="last_online", type="string")
     *
     */
    private $last_online;

    /**
     * @var integer
     * @Column(name="payment_cof_id", type="integer")
     *
     */
    private $payment_cof_id;

    /**
     * @var integer
     * @Column(name="default_user_address_id", type="integer")
     *
     */
    private $default_user_address_id;

    /**
     * @var integer
     * @Column(name="auto_follow", type="integer")
     *
     */
    private $auto_follow = 0;

    /**
     * @var integer
     * @Column(name="recurring_donations_cache_needs_update", type="integer")
     *
     */
    private $recurring_donations_cache_needs_update = 1;

    /**
     * @var string
     *
     * @Column(name="updated_at", type="string", nullable=true)
     */
    private $updated_at;

	/**
     * @var integer
     * @Column(name="is_dashboard_tour_taken", type="integer")
     *
     */
    private $is_dashboard_tour_taken = 0;

	/**
     * @var integer
     * @Column(name="prompt_pick_username", type="integer")
     *
     */
    private $prompt_pick_username = 1;

    /**
     * @var string
     * @Column(name="dashboard_image", type="string")
     *
     */
    private $dashboard_image;

    /**
     * @var string
     * @Column(name="dashboard_image_upload_date", type="string")
     *
     */
    private $dashboard_image_upload_date;

    /**
     * @var string
     * @Column(name="auth_token", type="string")
     *
     */
    private $auth_token;

    /**
     * @var string
     * @Column(name="url_before_signup", type="string")
     *
     */
    private $url_before_signup;

    /**
     * @var integer
     * @Column(name="sign_petitions_anonymously", type="integer")
     */
    private $sign_petitions_anonymously = 0;

    public function __construct() {
        parent::__construct();
        $this->updated_at = date('Y-m-d H:i:s');
    }	

    /** @PrePersist */
    public function onPrePersist()
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function getRecurringDonationsCacheNeedsUpdate() {
        return $this->recurring_donations_cache_needs_update;
    }

    public function setRecurringDonationsCacheNeedsUpdate($val) {
        $this->recurring_donations_cache_needs_update = $val;
    }

    public function login($session) {

        if (!$this->auth_token) {
            $this->generateAuthToken();
        }
        $session->set_userdata(['id' => $this->id]);

        $this->last_online = date('Y-m-d H:i:s');
        \Base_Controller::$em->persist($this);
        \Base_Controller::$em->flush($this);
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @param string $activation_key
     */
    public function setActivationKey($activation_key) {
        $this->activation_key = $activation_key;
    }

    /**
     * @return string
     */
    public function getActivationKey() {
        return $this->activation_key;
    }

    /**
     * @param string $capabilities
     */
    public function setCapabilities($capabilities) {
        $this->capabilities = $capabilities;
    }

    /**
     * @return string
     */
    public function getCapabilities() {
        return $this->capabilities;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $fb_user_id
     */
    public function setFbUserId($fb_user_id) {
        $this->fb_user_id = $fb_user_id;
    }

    /**
     * @return string
     */
    public function getFbUserId() {
        return $this->fb_user_id;
    }

    /**
     * @param string $google_user_id
     */
    public function setGoogleUserId($google_user_id) {
        $this->google_user_id = $google_user_id;
    }

    /**
     * @return string
     */
    public function getGoogleUserId() {
        return $this->google_user_id;
    }

    /**
     * @param string $fname
     */
    public function setFname($fname) {
        $this->fname = $fname;
    }

    /**
     * @return string
     */
    public function getFname() {
        return $this->fname;
    }

    /**
     * @param string $image
     */
    public function setImage($image) {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * @param string $joined
     */
    public function setJoined($joined) {
        $this->joined = $joined;
    }

    /**
     * @return string
     */
    public function getJoined() {
        return $this->joined;
    }

    /**
     * @param string $lname
     */
    public function setLname($lname) {
        $this->lname = $lname;
    }

    /**
     * @return string
     */
    public function getLname() {
        return $this->lname;
    }

    /**
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param                             $fbUserId
     * @param \Doctrine\ORM\EntityManager $em
     *
     * @return \Entity\User|null
     */
    static public function findByFbUserId($fbUserId, \Doctrine\ORM\EntityManager $em) {
        /** @var \Doctrine\ORM\EntityRepository $ur  */
        $ur = $em->getRepository('Entity\User');

        return $ur->findOneBy(array('fb_user_id' => $fbUserId));
    }

    public function getName() {
        $name =  $this->fname . ($this->lname ? ' ' . $this->lname : '');
        if (!$name) {
            return $this->username;
        }

        return $name;
    }

    public function getImageUrl() {
        if ($this->image) {
            return '/images/profiles/' . $this->id . '-' . $this->image;
        }
        if ($this->fb_user_id) {
            return 'https://graph.facebook.com/' . $this->fb_user_id . '/picture';
        }
        return '/images/user-avatar-default.png';
    }

    public function setCheckedNotifications($new) {
        $this->checkedNotifications = $new;
    }

    public function saveDonation(\stdClass $MakeCOFDonationResponse, Charity $charity) {
        $donation = new Donation($MakeCOFDonationResponse, $charity, $this);
        $em = \Base_Controller::$em;
        $em->persist($donation);
        $em->flush($donation);
    }

    public function isInstantDonationsEnabled() {
        return (bool)$this->instant_donation_cof_id;
    }

    public function setInstantDonationCofId($cofId) {
        $this->instant_donation_cof_id = $cofId;
    }

    public function getInstantDonationCofId() {
        return $this->instant_donation_cof_id;
    }

    public function setPaymentCofId($cofId) {
        $this->payment_cof_id = $cofId;
    }

    public function getPaymentCofId() {
        return $this->payment_cof_id;
    }

    public function getNoInstantDonationConfirmationMessage() {
        return $this->no_instant_donation_confirmation_message;
    }

    public function setNoInstantDonationConfirmationMessage($val) {
        $this->no_instant_donation_confirmation_message = $val;
    }

    public function isFollowingCharity(Charity $charity) {
        $em = \Base_Controller::$em;
        $cfRepo = $em->getRepository('\Entity\CharityFollower');
        $cfs = $cfRepo->findBy(array(
                                    'user_id' => $this->getId(),
                                    'charity_id' => $charity->getId(),
                               ));
        return (bool)$cfs;
    }

    public function followCharity(Charity $charity) {
        if ($this->isFollowingCharity($charity)) {
            return true;
        }
        $em = \Base_Controller::$em;

        $cf = new CharityFollower();
        $cf->setUserId($this->getId());
        $cf->setCharityId($charity->getId());
        $cf->setDate(date('Y-m-d H:i:s'));
        $em->persist($cf);
        $em->flush($cf);

        $charity->setUpdatedAtDt(new \DateTime());
        $em->persist($charity);
        $em->flush($charity);

        return true;
    }

    public function unfollowCharity(Charity $charity) {
        if (!$this->isFollowingCharity($charity)) {
            return true;
        }

        $em = \Base_Controller::$em;
        $cfRepo = $em->getRepository('\Entity\CharityFollower');
        $cfs = $cfRepo->findBy(array(
                             'user_id' => $this->getId(),
                             'charity_id' => $charity->getId(),
                        ));

        foreach($cfs as $cf) {
            $em->remove($cf);
        }

        $em->flush();

        $charity->setUpdatedAtDt(new \DateTime());
        $em->persist($charity);
        $em->flush($charity);

        return true;
    }

    /**
     * @return UserCause[]
     */
    public function getCauses() {
        $em = \Base_Controller::$em;
        $ucRepo = $em->getRepository('\Entity\UserCause');
        /** @var \Entity\UserCause[] $causes */
        $causes = $ucRepo->findBy(array(
                                     'userid' => $this->id,
                                  ));
        return $causes;
    }

    /**
     * @return array with the key "cause" for the \Entity\CharityCause instance and "selected" bool if it is selected or not.
     */
    public function getCausesForSelecting(CharityCategory $category) {
        $em = \Base_Controller::$em;
        $ccRepo = $em->getRepository('\Entity\CharityCause');
        /** @var \Entity\CharityCause[] $causes */
        $causes = $ccRepo->findBy(array('categoryId' => $category->getId()));

        $selectedCauses = $this->getCauses();
        /** @var \Entity\UserCause[] $tmpSelectedCauses */
        $tmpSelectedCauses = array();
        foreach($selectedCauses as $cause) {
            $tmpSelectedCauses[$cause->getCauseId()] = $cause;
        }
        $selectedCauses = $tmpSelectedCauses;

        $return = array();
        foreach($causes as $cause) {
            $return[$cause->getName()] = array(
                'cause' => $cause,
                'selected' => isset($selectedCauses[$cause->getId()]),
            );
        }
        ksort($return);
        return $return;
    }

    /**
     * @return UserCategory[]
     */
    public function getCategories() {
        $em = \Base_Controller::$em;
        $ucRepo = $em->getRepository('\Entity\UserCategory');
        /** @var \Entity\UserCategory[] $categories */
        $categories = $ucRepo->findBy(array(
                                       'user_id' => $this->id,
                                  ));
        return $categories;
    }


    /**
     * @return array with the key "category" for the \Entity\CharityCategory instance and "selected" bool if it is selected or not.
     */
    public function getCategoriesForSelecting() {
        $em = \Base_Controller::$em;
        $ccRepo = $em->getRepository('\Entity\CharityCategory');
        /** @var \Entity\CharityCategory[] $categories */
        $categories = $ccRepo->findAll();

        $selectedCategories = $this->getCategories();
        /** @var \Entity\UserCategory[] $tmpSelectedCategories */
        $tmpSelectedCategories = array();
        foreach($selectedCategories as $category) {
            $tmpSelectedCategories[$category->getCategoryId()] = $category;
        }
        $selectedCategories = $tmpSelectedCategories;

        $return = array();
        foreach($categories as $category) {
            $return[$category->getName()] = array(
                'category' => $category,
                'selected' => isset($selectedCategories[$category->getId()]),
            );
        }
        ksort($return);
        return $return;
    }

    /**
     * @param User $user
     *
     * @return bool true if the user is now followed, false if the user is no longer followed.
     */
    public function toggleFollowUser(User $user) {
        $em = \Base_Controller::$em;

        $ufRepo = $em->getRepository('\Entity\UserFollower');
        /** @var \Entity\UserFollower $follower */
        $follower = $ufRepo->findOneBy(array(
                                'follower_user_id' => $this->getId(),
                                'followed_user_id' => $user->getId(),
                           ));

        if ($follower) {
            $em->remove($follower);
            $em->flush();
            return false;
        }

        $follower = new UserFollower();
        $follower->setDateTime(new \DateTime());
        $follower->setFollowedUser($user);
        $follower->setFollowerUser($this);

        $em->persist($follower);
        $em->flush($follower);

        return true;
    }


    public function getHideUnhideDonation() {
        return $this->hide_unhide_donation;
    }

    public function setHideUnhideDonation($val) {
        $this->hide_unhide_donation = $val;
    }

    public function getHideUnhideBadges() {
        return $this->hide_unhide_badges;
    }

    public function setHideUnhideBadges($val) {
        $this->hide_unhide_badges = $val;
    }

    /**
     * @return bool
     */
    public function hasPasswordChanged() {
        if ($this->password_changed == '0000-00-00 00:00:00' || !$this->password_changed) {
            return false;
        }
        return true;
    }

    /**
     * @return \DateTime|null
     */
    public function getPasswordChangedDateTime() {
        if ($this->hasPasswordChanged()) {
            $dt = new \DateTime($this->password_changed);
            return $dt;
        }
        return null;
    }

    private $friends = array();

    /**
     * @return bool
     */
    public function hasFriends() {
        return !empty($this->getFriends());
    }

    /**
     * @return User[]
     */
    public function getFriends() {
        if ($this->friends) {
            return $this->friends;
        }
        /** @var UserFollower[] $ufs */
        $ufs = \Base_Controller::$em->getRepository('\Entity\UserFollower')->findBy(array(
                                                                                                   'follower_user_id' => $this->id,
                                                                                              ));

        foreach($ufs as $uf) {
            $this->friends[] = $uf->getFollowedUser();
        }

        return $this->friends;
    }

    /** @var Donation[] */
    private $donations = array();

    /** @var Donation[] */
    private $donations_with_pending_paypal = array();


    /**
     * @param null $limit
     * @param bool $include_pending_paypal_donations default false ... set to true if you want to include pending/incomplete/cancelled paypal-donations.
     *
     * @return Donation[]
     */
    public function getDonations($limit = null, $include_pending_paypal_donations = false) {
        if ($include_pending_paypal_donations) {
            if ($this->donations_with_pending_paypal) {
                return $this->donations_with_pending_paypal;
            }
            return $this->donations = Donation::findBy(
                [
                    'userId' => $this->id,
                ],
                [
                    'date' => 'desc'
                ],
                $limit
            );
        } else {
            if ($this->donations) {
                return $this->donations;
            }
            $qb = \Base_Controller::$em->createQueryBuilder();
            $qb->select('d')
               ->from('\Entity\Donation', 'd')
               ->where('d.userId = ?1')
               ->andWhere('d.paypal = 0 OR d.paypal = 2')
               ->orderBy('d.date', 'DESC')
               ->setParameter(1, $this->id)
               ->setMaxResults($limit);
            $query = $qb->getQuery();
            $this->donations = $query->getResult();
            return $this->donations;
        }
    }

    public function hasDonations() {
        return !empty($this->getDonations());
    }

    /**
     * @return Charity[]
     */
    public function getFollowedCharities() {
        /** @var \Entity\CharityFollower[] $charityFollowers */
        $charityFollowers = \Base_Controller::$em->getRepository('\Entity\CharityFollower')->findBy(array(
                                                                                     'user_id' => $this->id
                                                                                ));

        /** @var \Entity\Charity[] $charities */
        $charities = array();
        foreach($charityFollowers as $cf) {
            $charities[] = $cf->getCharity();
        }

        return $charities;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isFollowingUser(User $user) {
        $follows = \Base_Controller::$em->getRepository('\Entity\UserFollower')->findBy(array(
                                                                          'follower_user_id' => $this->id,
                                                                          'followed_user_id' => $user->getId(),
                                                                     ));

        return (bool)$follows;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    public function setGiverCoin($coin) {
        $this->score = $coin;
    }

    public function addGiverHubScore($event, $amount = null, $reverse = false) {

        /** @var GiverCoin[] $tmp_scores */
        $tmp_scores = GiverCoin::findAll();
        $scores = [];
        foreach($tmp_scores as $tmp_score) {
            $scores[$tmp_score->getEvent()] = $tmp_score->getAmount();
        }

        /*
        $scores = array(
            'donation' => 10,
            'review' => 2,
            'keyword' => 1,
            'invite' => 1,
            'upload-charity-photo' => 1,
            'leave-charity-update' => 1,
            'facebook-like' => 1,
            'facebook-unlike' => -1,
            'mission-statement' => 1,
            'mission-summary' => 1,
            'publish-petition' => 10,
        );
        */

        if (!isset($scores[$event])) {
            throw new \Exception('Bad event: ' . $event);
        }

        if ($event === 'donation') {
            $score = $amount * $scores[$event];
        } elseif ($amount) {
            $score = $amount;
        } else {
            $score = $scores[$event];
        }

        if ($reverse) {
            $query = \Base_Controller::$em->createQuery('UPDATE \Entity\User u SET u.score = u.score - ?1 WHERE u.id = ?2');
        } else {
            $query = \Base_Controller::$em->createQuery('UPDATE \Entity\User u SET u.score = u.score + ?1 WHERE u.id = ?2');
        }
        $query->setParameter(1, $score);
        $query->setParameter(2, $this->id);

        $query->execute();
    }

    public function getScore() {
        return $this->score;
    }

    public function getGiverCoin() {
        return $this->score;
    }

    /**
     * @param Charity $charity
     *
     * @return bool
     */
    public function hasReviewedCharity(Charity $charity) {
        return (bool)CharityReview::findOneBy(array('user_id' => $this->id, 'charity_id' => $charity->getId()));
    }

    /**
     * @param Charity $charity
     *
     * @return CharityReview
     */
    public function getCharityReview(Charity $charity) {
        return CharityReview::findOneBy(array('user_id' => $this->id, 'charity_id' => $charity->getId()));
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return User
     */
    static public function findOneBy(array $criteria, array $orderBy = null) {
        return \Base_Controller::$em->getRepository('\Entity\User')->findOneBy($criteria, $orderBy);
    }

    public function resetPasswordStep1() {
        $this->password_token = bin2hex(openssl_random_pseudo_bytes(10));
        $this->retrieve_password_time = date('Y-m-d H:i:s');

        \Base_Controller::$em->persist($this);
        \Base_Controller::$em->flush($this);

        $body =
            "You have requested to reset your password on GiverHub.com

            Following the link to complete the password reset.
            <a href=\"".base_url('home/reset_password?t='.$this->password_token)."\">".base_url('home/reset_password?t='.$this->password_token)."</a>

            ".base_url('home/reset_password?t='.$this->password_token);
        $body = nl2br($body);
        return \emailsending('admin@giverhub.com', $this->getEmail(), 'Reset Password | GiverHub.com', $body, 'GiverHub, Inc.');
    }

    public function checkPassword($clearTextPassword) {
        return ($this->password == crypt($clearTextPassword, $this->password));
    }

    static public function encryptPassword($clearTextPassword) {
        $salt = bin2hex(openssl_random_pseudo_bytes(22));

        return crypt($clearTextPassword, '$2a$12$' . $salt);
    }

    static public function checkCredentials($usernameOrEmail, $clearTextPassword) {
        /** @var User[] $users */
        $users = array();

        // check email first
        $user = User::findOneBy(array('email' => $usernameOrEmail));
        if ($user) {
            $users[] = $user;
        }

        // but also check username
        $user = User::findOneBy(array('username' => $usernameOrEmail));
        if ($user) {
            $users[] = $user;
        }


        if (!$users) {
            return false;
        }

        foreach($users as $user) {
            if ($user->checkPassword($clearTextPassword)) {
                return $user;
            }
        }
        return false;
    }

    public function changePassword($newClearTextPassword) {
        $this->password = $this->encryptPassword($newClearTextPassword);
        $this->password_changed = date('Y-m-d H:i:s');
        \Base_Controller::$em->persist($this);
        \Base_Controller::$em->flush($this);
        return true;
    }

    public function getLevel() {
        switch ($this->capabilities) {
            case 'registered':
                $level = 1;
                break;
            case 'confirmed':
                $level = 2;
                break;
            case 'charity_admin':
                $level = 3;
                break;
            case 'admin':
                $level = 4;
                break;
            case 'super_admin':
                $level = 5;
                break;
            default:
                $level = 0;
        }
        return $level;
    }

    /**
     * @return User[]
     */
    public function getFollowers() {
        /** @var UserFollower[] $followersEntities */
        $followersEntities = \Base_Controller::$em->getRepository('Entity\UserFollower')->findBy(array('followed_user_id' => $this->id));

        /** @var User[] $followers */
        $followers = array();

        foreach($followersEntities as $followerEntity) {
            $user = $followerEntity->getFollowerUser();
            if(!$user) continue;
            $followers[] = $user;
        }

        return $followers;
    }


    /**
     * @return User[]
     */
    public function getFollowing() {
        /** @var UserFollower[] $ufs */
        $ufs = \Base_Controller::$em->getRepository('Entity\UserFollower')->findBy(array('follower_user_id' => $this->id));

        /** @var User[] $following */
        $following = array();

        foreach($ufs as $uf) {
            $user = $uf->getFollowedUser();
            if(!$user) continue;
            $following[] = $user;
        }

        return $following;
    }

    /**
     * @param string $retrieve_password_time
     */
    public function setRetrievePasswordTime($retrieve_password_time)
    {
        $this->retrieve_password_time = $retrieve_password_time;
    }

    /**
     * @return string
     */
    public function getRetrievePasswordTime()
    {
        return $this->retrieve_password_time;
    }

    /**
     * @param string $password_changed
     */
    public function setPasswordChanged($password_changed)
    {
        $this->password_changed = $password_changed;
    }

    /**
     * @return string
     */
    public function getPasswordChanged()
    {
        return $this->password_changed;
    }

    /**
     * @param string $password_token
     */
    public function setPasswordToken($password_token)
    {
        $this->password_token = $password_token;
    }

    /**
     * @return string
     */
    public function getPasswordToken()
    {
        return $this->password_token;
    }

    /**
     * @return Badge[]
     */
    public function getBadges() {
        $badges = Badge::findBy(array('user_id' => $this->id), array('points' => 'desc'));

        return $badges;
    }

    /**
     * @param string $last_online
     */
    public function setLastOnline($last_online)
    {
        $this->last_online = $last_online;
    }

    /**
     * @return string
     */
    public function getLastOnline()
    {
        return $this->last_online;
    }

    /**
     * @return bool
     */
    public function isOnline() {
        if (!$this->last_online) {
            return false;
        }
        $offline = new \DateTime('-2 minute');
        $lastOnline = new \DateTime($this->last_online);
        $ret = $lastOnline > $offline;
        return $ret;
    }

    public function addBadgePoints($event, Charity $charity, $amount = null, $reverse = false) {
        $points = array(
            'donation' => 0,
            'review' => 100, //0.1,
            'keyword' => 1, //0.001,
        );
        if (!isset($points[$event])) {
            throw new \Exception('Bad event: ' . $event);
        }
        if ($amount) {
            $points = $amount;
        } else {
            $points = $points[$event];
        }

        $CI =& get_instance();
        foreach($charity->getCategories() as $category) {
            if ($reverse) {
                $sql = "
                INSERT INTO badge (user_id,category_id,points) VALUES (?,?,?)
                ON DUPLICATE KEY UPDATE points=points-?
                ";
            } else {
                $sql = "
                INSERT INTO badge (user_id,category_id,points) VALUES (?,?,?)
                ON DUPLICATE KEY UPDATE points=points+?
                ";
            }
            $CI->db->query($sql, array($this->id, $category->getId(), $points, $points));
        }
        if ($reverse) {
            $sql = "delete from badge where user_id = ? and points <= 0";
            $CI->db->query($sql, [$this->id]);
        }
    }

    /**
     * @param int $limit
     *
     * @return Charity[]
     */
    public function getRecommendedCharities($limit = 3) {
        $GLOBALS['super_timers']['grc1'] = microtime(true) - $GLOBALS['super_start'];
        $cl = new \SphinxClient();

        $cl->SetServer($_SERVER['SERVER_NAME'] == 'giverhub.com' ? 'sphinx' : '127.0.0.1');
        $cl->SetConnectTimeout(5);
        $cl->SetArrayResult(true);

        $cl->SetLimits(0, 1000, 1000);
        //$cl->SetSortMode(SPH_SORT_EXTENDED, 'overallScore DESC');
        $cl->SetSortMode(SPH_SORT_EXPR, "(@weight + (overallScore / 2)) * (orgId + 0.1)");

        $GLOBALS['super_timers']['grc2'] = microtime(true) - $GLOBALS['super_start'];
        /**
         * @param \SphinxClient $cl
         *
         * @return array
         */
        $getCharityIds = function(\SphinxClient $cl) {
            $GLOBALS['super_timers']['sqsq1'] = microtime(true) - $GLOBALS['super_start'];
            $res = $cl->Query('', 'charity:charity_delta');
            $GLOBALS['super_timers']['sqsq2'] = microtime(true) - $GLOBALS['super_start'];
            if ($res === false) {
                $GLOBALS['debug']('similar: last error: '.$cl->GetLastError() .'::last warning:'. $cl->GetLastWarning());
                return array();
            }
            $GLOBALS['debug']('recommended:'.print_r($res,true));

            if (@$res['matches']) {
                $ids = array();
                foreach ($res['matches'] as $match){
                    $ids[] = $match['id'];
                }
                return $ids;
            }

            return array();
        };

        $GLOBALS['super_timers']['grc3'] = microtime(true) - $GLOBALS['super_start'];
        $charityIds = array();
        $userAddress = $this->getDefaultAddress();
        if ($userAddress) {
            $city = $userAddress->getCity();
        }
        $GLOBALS['super_timers']['grc4'] = microtime(true) - $GLOBALS['super_start'];
        foreach($this->getCauses() as $x => $cause) {
            $GLOBALS['super_timers']['grc4_1'.$x] = microtime(true) - $GLOBALS['super_start'];
            $cl->ResetFilters();
            if (isset($city)) {
                $cl->SetFilter('cityId', array($city->getId()));
            }
            $cl->SetFilter('causes', array($cause->getCauseId()));
            $GLOBALS['super_timers']['grc4_2'.$x] = microtime(true) - $GLOBALS['super_start'];
            foreach($getCharityIds($cl) as $charityId) {
                if (isset($charityIds[$charityId])) {
                    $charityIds[$charityId]++;
                } else {
                    $charityIds[$charityId] = 1;
                }
            }
            $GLOBALS['super_timers']['grc4_3'.$x] = microtime(true) - $GLOBALS['super_start'];
        }
        $GLOBALS['super_timers']['grc5'] = microtime(true) - $GLOBALS['super_start'];
        foreach($this->getCategories() as $category) {
            $cl->ResetFilters();
            if (isset($city)) {
                $cl->SetFilter('cityId', array($city->getId()));
            }
            $cl->SetFilter('categories', array($category->getCategoryId()));
            foreach($getCharityIds($cl) as $charityId) {
                if (isset($charityIds[$charityId])) {
                    $charityIds[$charityId]++;
                } else {
                    $charityIds[$charityId] = 1;
                }
            }
        }
        $GLOBALS['super_timers']['grc6'] = microtime(true) - $GLOBALS['super_start'];
        if (count($charityIds) < $limit) {
            foreach($this->getCauses() as $cause) {
                $cl->ResetFilters();
                $cl->SetFilter('causes', array($cause->getCauseId()));
                foreach($getCharityIds($cl) as $charityId) {
                    if (isset($charityIds[$charityId])) {
                        $charityIds[$charityId]++;
                    } else {
                        $charityIds[$charityId] = 1;
                    }
                }
            }
            foreach($this->getCategories() as $category) {
                $cl->ResetFilters();
                $cl->SetFilter('categories', array($category->getCategoryId()));
                foreach($getCharityIds($cl) as $charityId) {
                    if (isset($charityIds[$charityId])) {
                        $charityIds[$charityId]++;
                    } else {
                        $charityIds[$charityId] = 1;
                    }
                }
            }
        }
        $GLOBALS['super_timers']['grc7'] = microtime(true) - $GLOBALS['super_start'];
        if (count($charityIds) < $limit) {
            $cl->ResetFilters();

            foreach($getCharityIds($cl) as $charityId) {
                if (isset($charityIds[$charityId])) {
                    $charityIds[$charityId]++;
                } else {
                    $charityIds[$charityId] = 1;
                }
            }
        }
        $GLOBALS['super_timers']['grc8'] = microtime(true) - $GLOBALS['super_start'];
        arsort($charityIds);
        arsort($charityIds); // run twice to preserve ordering lol / Robert
        $charityIds = array_keys($charityIds);

        if (count($charityIds) > $limit+12) { // take the best 15 and shuffle them
            $charityIds = array_slice($charityIds, 0, $limit+12);
        }

        shuffle($charityIds);

        $n = 0;
        $charities = array();
        $GLOBALS['super_timers']['sqsq3'] = microtime(true) - $GLOBALS['super_start'];
        foreach($charityIds as $charityId) {
            $charity = Charity::find($charityId);

            if(!$charity->getTagLine()) {
                continue;
            }

            $charities[] = $charity;
            $n++;
            if ($n == $limit) {
                break;
            }
        }
        $GLOBALS['super_timers']['sqsq4'] = microtime(true) - $GLOBALS['super_start'];
        return $charities;
    }

    /**
     * @param int $limit
     *
     * @return ChangeOrgPetition[]
     */
    public function getRecommendedPetitions($limit = 3) {
        $cl = new \SphinxClient();

        $cl->SetServer($_SERVER['SERVER_NAME'] == 'giverhub.com' ? 'sphinx' : '127.0.0.1');
        $cl->SetConnectTimeout(5);
        $cl->SetArrayResult(true);

        $cl->SetLimits(0, 1000, 1000);
        $cl->SetSortMode(SPH_SORT_EXTENDED, 'can_be_signed DESC, signature_count DESC');

        /**
         * @param \SphinxClient $cl
         *
         * @return array
         */
        $getCharityIds = function(\SphinxClient $cl) {
            $res = $cl->Query('', 'petition');
            if ($res === false) {
                //echo "----------------------->1";
                $GLOBALS['debug']('similar: last error: '.$cl->GetLastError() .'::last warning:'. $cl->GetLastWarning());
                return array();
            }
            //echo "----------------------->2";
            $GLOBALS['debug']('recommended:'.print_r($res,true));

            if (@$res['matches']) {
                $ids = array();
                foreach ($res['matches'] as $match){
                    $ids[] = $match['id'];
                }
                return $ids;
            }

            return array();
        };
        //echo "----------------------->3";

        $charityIds = array();
        $userAddress = $this->getDefaultAddress();
        if ($userAddress) {
            $city = $userAddress->getCity();
        }

        foreach($this->getCauses() as $cause) {
            $cl->ResetFilters();

            $cl->SetFilter('causes', array($cause->getCauseId()));
            foreach($getCharityIds($cl) as $charityId) {
                if (isset($charityIds[$charityId])) {
                    $charityIds[$charityId]++;
                } else {
                    $charityIds[$charityId] = 1;
                }
            }
        }
        foreach($this->getCategories() as $category) {
            $cl->ResetFilters();

            $cl->SetFilter('categories', array($category->getCategoryId()));
            foreach($getCharityIds($cl) as $charityId) {
                if (isset($charityIds[$charityId])) {
                    $charityIds[$charityId]++;
                } else {
                    $charityIds[$charityId] = 1;
                }
            }
        }
        if (count($charityIds) < $limit) {
            foreach($this->getCauses() as $cause) {
                $cl->ResetFilters();
                $cl->SetFilter('causes', array($cause->getCauseId()));
                foreach($getCharityIds($cl) as $charityId) {
                    if (isset($charityIds[$charityId])) {
                        $charityIds[$charityId]++;
                    } else {
                        $charityIds[$charityId] = 1;
                    }
                }
            }
            foreach($this->getCategories() as $category) {
                $cl->ResetFilters();
                $cl->SetFilter('categories', array($category->getCategoryId()));
                foreach($getCharityIds($cl) as $charityId) {
                    if (isset($charityIds[$charityId])) {
                        $charityIds[$charityId]++;
                    } else {
                        $charityIds[$charityId] = 1;
                    }
                }
            }
        }
        if (count($charityIds) < $limit) {
            $cl->ResetFilters();
            foreach($getCharityIds($cl) as $charityId) {
                if (isset($charityIds[$charityId])) {
                    $charityIds[$charityId]++;
                } else {
                    $charityIds[$charityId] = 1;
                }
            }
        }

        arsort($charityIds);
        arsort($charityIds);
        $charityIds = array_keys($charityIds);

        if (count($charityIds) > 15) { // take the best 15 and shuffle them
            $charityIds = array_slice($charityIds, 0, 15);
        }

        shuffle($charityIds);

        $n = 0;
        $charities = array();
        foreach($charityIds as $charityId) {
            $charities[] = ChangeOrgPetition::find($charityId);
            $n++;
            if ($n == $limit) {
                break;
            }
        }
        return $charities;
    }


    /**
     * @param ChangeOrgPetition $petition
     *
     * @return bool
     */
    public function hasSignedPetition(ChangeOrgPetition $petition) {
        return (bool)UserPetitionSignature::findOneBy(['user_id' => $this->id, 'petition_id' => $petition->getId()]);
    }

	/**
     * @param GiverHubPetition $petition
     *
     * @return bool
     */
    public function hasSignedGiverhubPetition(Petition $petition) {
        return (bool)PetitionSignature::findOneBy(['user_id' => $this->id, 'petition_id' => $petition->getId()]);
    }

    /**
     * @param $key
     *
     * @return bool|User
     */
    static public function validate_activation_key($key) {
        $user = self::findOneBy(array('activation_key' => $key));
        if (!$user) {
            return false;
        }

        $user->setCapabilities('confirmed');
        $user->setActivationKey('');
        \Base_Controller::$em->persist($user);
        \Base_Controller::$em->flush($user);

        return $user;
    }

    /** @var CharityReview[] */
    private $_charity_reviews;

    /**
     * @return CharityReview[]
     */
    public function getCharityReviews() {
        if ($this->_charity_reviews === null) {
            $this->_charity_reviews = CharityReview::findBy(['user_id' => $this->id]);
        }
        return $this->_charity_reviews;
    }

    /**
     * @param null $limit
     * @param      $context
     *
     * @return BaseEntity[]
     */
    public function getActivityFeed($offset = 0, $limit = null, $context) {
        return UserActivityFeed::get($this, $offset, $limit, $context);
    }

    public function generateActivationKey() {
        return bin2hex(openssl_random_pseudo_bytes(10));
    }

    /**
     * @return bool
     */
    public function hasAddress() {
        return (bool)$this->default_user_address_id;
    }

    public function getDefaultAddressId() {
        return $this->default_user_address_id;
    }

    public function setDefaultAddressId($user_address_id) {
        $this->default_user_address_id = $user_address_id;
    }

    /**
     * @return UserAddress|null
     */
    public function getDefaultAddress() {
        if (!$this->hasAddress()) {
            return null;
        }
        /** @var UserAddress|null $a */
        $a = UserAddress::find($this->default_user_address_id);
        return $a;
    }

    /**
     * @param UserAddress $a
     */
    public function setDefaultAddress(UserAddress $a = null) {
        if (is_null($a)) {
            $this->default_user_address_id = null;
        } else {
            $this->default_user_address_id = $a->getId();
        }
    }

    /**
     * @return UserAddress[]
     */
    public function getAddresses() {
        /** @var UserAddress[] $a */
        $a = UserAddress::findBy(['user_id' => $this->id]);
        return $a;
    }

    /**
     * @param CharityCity  $city
     * @param CharityState $state
     * @param              $phone
     * @param              $zipcode
     * @param              $address1
     * @param null         $address2
     *
     * @return UserAddress
     */
    public function addAddress(CharityCity $city, CharityState $state, $phone, $zipcode, $address1, $address2 = null) {
        $a = new UserAddress();
        $a->setUser($this);
        $a->setCity($city);
        $a->setState($state);
        $a->setPhone($phone);
        $a->setZipcode($zipcode);
        $a->setAddress1($address1);
        $a->setAddress2($address2);
        \Base_Controller::$em->persist($a);
        \Base_Controller::$em->flush($a);

        if (!$this->default_user_address_id) {
            $this->default_user_address_id = $a->getId();
            \Base_Controller::$em->persist($this);
            \Base_Controller::$em->flush($this);
        }

        return $a;
    }

    public function isMissingName() {
        return !$this->lname || !$this->fname;
    }

    public function getAutoFollow() {
        return $this->auto_follow;
    }

    public function setAutoFollow($val) {
        $this->auto_follow = $val;
    }

	public function getIsDashboardTourTaken() {
        return $this->is_dashboard_tour_taken;
    }

    public function setIsDashboardTourTaken($val) {
		$this->is_dashboard_tour_taken = $val;
    }

	public function getPromptPickUsername() {
        return $this->prompt_pick_username;
    }

    public function setPromptPickUsername($val) {
		$this->prompt_pick_username = $val;
    }

    public function getDashboardImage() {
        return $this->dashboard_image;
    }

    public function setDashboardImage($val) {
        $this->dashboard_image = $val;
    }

    public function getDashboardImageUploadDate() {
        return $this->dashboard_image_upload_date;
    }

    public function setDashboardImageUploadDate($val) {
        $this->dashboard_image_upload_date = $val;
    }

    /** @PostPersist */
    public function onPostPersist()
    {

        /** @var User[] $autoFollowedUsers */
        $autoFollowedUsers = User::findBy(['auto_follow' => 1]);

        foreach($autoFollowedUsers as $autoFollowedUser) {
            $autoFollowedUser->toggleFollowUser($this);
            $this->toggleFollowUser($autoFollowedUser);
        }

        $andrew = User::findOneBy(['email' => 'andrewlevine@giverhub.com']);
        if ($andrew) {
            $dt = new \DateTime();
            $dt->modify('+3 second'); // we want these to come after the follow below
            $welcome_post = new ActivityFeedPost();
            $welcome_post->setFromUser($andrew);
            $welcome_post->setToUser($this);
            $welcome_post->setDateDt($dt);
			$toUserName		= $this->getFname();
			if (!$toUserName) {
				$toUserName = $this->getUsername();
			}
			$youtubeVideoId = "gyZXGfkheYU";
			$welcome_post->setText(
                         "Hi ".$toUserName.", welcome to GiverHub and thanks for joining us on our mission to make giving back a fast, easy, and rewarding experience! Check out our introductory video below to learn about more GiverHub features and gain some insights into our larger mission."
            );
			
            \Base_Controller::$em->persist($welcome_post);
            \Base_Controller::$em->flush($welcome_post);

			//Giverhub introduction youtube video to appear below new welcome message
			$youtube_video = new \Entity\ActivityFeedPostYoutubeVideo();
		    $youtube_video->setVideoId($youtubeVideoId);
		    $youtube_video->setActivityFeedPost($welcome_post);
		    $youtube_video->setTitleAndDescription();
		    \Base_Controller::$em->persist($youtube_video);
		    \Base_Controller::$em->flush($youtube_video);
        }

    }

    public static function findSphinx($query) {
        $em = \Base_Controller::$em;

        $cl = new \SphinxClient();

        $cl->SetServer($_SERVER['SERVER_NAME'] == 'giverhub.com' ? 'sphinx' : '127.0.0.1');
        $cl->SetConnectTimeout(5);
        $cl->SetArrayResult(true);

        $cl->SetLimits(0, 10, 1000);

        $cl->SetSortMode(SPH_SORT_ATTR_ASC, 'name');

        $res = $cl->Query($query, 'user');
        if ($res === false) {
            return ['count' => 0, 'users' => []];
        }


        /** @var User[] $users */
        $users = array();

        if (@$res['matches']) {
            foreach ($res['matches'] as $match){
                $users[$match['id']] = $match['id'];
            }

            $qb = $em->createQueryBuilder();
            $qb->select('u');
            $qb->from('Entity\User', 'u');
            $qb->where('u.id IN ('.join(',',$users).')');


            /** @var \Entity\User[] $results */
            $results = $qb->getQuery()->getResult();
            foreach($results as $r) {
                $users[$r->getId()] = $r;
            }
        }

        return array(
            'users' => $users,
            'count' => $res['total']
        );
    }

    private $_bets;

    /**
     * @return Bet[]
     */
    public function getBets() {
        if ($this->_bets === null) {
            /** @var Bet[] $bets */
            $bets = Bet::findBy( [ 'user' => $this ] );

            /** @var BetFriend[] $bet_friends */
            $bet_friends = BetFriend::findBy( [ 'user' => $this ] );

            foreach ($bet_friends as $bet_friend) {
                $bet = $bet_friend->getBet();
                if ($bet->getStatus() != 'draft') {
                    $bets[] = $bet;
                }
            }
            $this->_bets = $bets;
        }
        return $this->_bets;
    }

    /**
     * @param Bet $success_bet
     * @param Bet $save_for_later
     *
     * @return array
     */
    public function getBetsIndexedByUserAndStatus(Bet $success_bet = null, Bet $save_for_later = null) {
        /** @var Bet[] $my_bets */
        $my_bets = Bet::findBy(['first_user_id' => $this->id]);
        /** @var Bet[] $other_bets */
        $other_bets = Bet::findBy(['other_user_id' => $this->id]);

        $ret = [
            'determination_time' => [],
            'my_incomplete' => [],
            'other_incomplete' => [],
            'my_pending' => [],
            'other_pending' => [],
            'success' => [],
        ];

        foreach(Bet::$statuses as $status) {
            if ($status == 'pending' || $status == 'incomplete') {
                continue;
            }
            $ret[$status] = [];
        }

        if ($save_for_later) {
            $ret['draft'] = [$save_for_later];
        }

        foreach($other_bets as $bet) {
            $status = $bet->getStatus();

            // dont show other users drafts,, even when they target me..
            if ($status == 'draft') {
                continue;
            }

            if ($status == 'confirmed') {
                if ($bet->getClaim($this) !== null && !$bet->isClaimConflict()) {
                    continue;
                }
                if ($success_bet && $bet->getId() == $success_bet->getId()) {
                    $ret['success'][] = $bet;
                    continue;
                }
            }
            if ($status == 'pending') {
                $ret['other_pending'][] = $bet;
            } elseif ($status == 'incomplete') {
                $ret['other_incomplete'][] = $bet;
            } elseif ($bet->isTimeForDetermination() && $bet->getStatus() == 'confirmed') {
                $ret['determination_time'][] = $bet;
            } else {
                $ret[$status][] = $bet;
            }
        }

        foreach($my_bets as $bet) {
            $status = $bet->getStatus();

            if ($status == 'draft' && $save_for_later && $save_for_later->getId() == $bet->getId()) {
                continue;
            }

            if ($status == 'confirmed') {
                if ($bet->getClaim($this) !== null && !$bet->isClaimConflict()) {
                    continue;
                }
                if ($success_bet && $bet->getId() == $success_bet->getId()) {
                    $ret['success'][] = $bet;
                    continue;
                }
            }

            if ($status == 'pending') {
                $ret['my_pending'][] = $bet;
            } elseif ($status == 'incomplete') {
                $ret['my_incomplete'][] = $bet;
            } elseif ($bet->isTimeForDetermination() && $bet->getStatus() == 'confirmed') {
                $ret['determination_time'][] = $bet;
            } else {
                $ret[$status][] = $bet;
            }
        }

        return $ret;
    }

    private $bets_in_need_of_determination_cache;

	/**
     * @return Bet[]
     */
    public function getBetsInNeedOfDetermination($clear_cache = false) {
        if ($this->bets_in_need_of_determination_cache !== null && !$clear_cache) {
            return $this->bets_in_need_of_determination_cache;
        }

        /** @var Bet[] $my_bets */
        $my_bets = Bet::findBy([
                'first_user_id' => $this->id,
                'status' => 'confirmed',
            ]);

        /** @var Bet[] $other_bets */
        $other_bets = Bet::findBy([
                'other_user_id' => $this->id,
                'status' => 'confirmed',
            ]);

        /** @var Bet[] $bets */
        $bets = [];
        foreach(array_merge($my_bets, $other_bets) as $bet) {
            /** @var Bet $bet */
            if ($bet->isTimeForDetermination() && (is_null($bet->getClaim($this)) || $bet->isClaimConflict())) {
                $bets[] = $bet;
            }
        }

        $this->bets_in_need_of_determination_cache = $bets;
        return $this->bets_in_need_of_determination_cache;
    }

    /**
     * @return Bet[]
     */
    public function getBetsInNeedOfDeterminationJSON() {
        $bets = $this->getBetsInNeedOfDetermination();

        $json = [];

        foreach($bets as $bet) {
            $friend_name = $bet->getFriend($this)->getName();
            $friend_link = $bet->getFriend($this)->getLink();

            /** @var \Entity\Charity $my_nonprofit */
            $my_nonprofit = $bet->getFirstUserId() == $this->id ? $bet->getFirstCharity() : $bet->getOtherCharity();
            /** @var \Entity\Charity $not_my_nonprofit */
            $not_my_nonprofit = $bet->getFirstUserId() == $this->id ? $bet->getOtherCharity() : $bet->getFirstCharity();

            $json[] = [
                'id' => $bet->getId(),
                'bet_id' => $bet->getId(),
                'name' => $bet->getName(),
                'terms' => $bet->getTerms(),
                'friend_name' => $friend_name,
                'friend_link' => $friend_link,
                'my_nonprofit_name' => $my_nonprofit->getName(),
                'my_nonprofit_id' => $my_nonprofit->getId(),
                'my_nonprofit_link' => $my_nonprofit->getLink(),
                'not_my_nonprofit_name' => $not_my_nonprofit->getName(),
                'not_my_nonprofit_id' => $not_my_nonprofit->getId(),
                'not_my_nonprofit_link' => $not_my_nonprofit->getLink(),
                'amount' => $bet->getAmount(),
                'claim_conflict' => $bet->isClaimConflict(),
            ];
        }

        return json_encode($json);
    }

    /**
     * @return string
     */
    public function getUrl() {
        if (\Base_Controller::$staticUser && \Base_Controller::$staticUser->getId() == $this->getId()) {
            return '/';
        }
        if ($this->getPages()) {
            return $this->getPages()[0]->getUrl();
        }
        return '/member/'.$this->username;
    }

    /**
     * @return string
     */
    public function getLink() {
        $name = $this->getName();
        if (\Base_Controller::$staticUser && \Base_Controller::$staticUser->getId() == $this->getId()) {
            $name .= ' (You)';
        }
        return '<a title="'.htmlspecialchars($name).'" href="'.$this->getUrl().'">'.htmlspecialchars($name).'</a>';
    }

    /**
     * @return string
     */
    public function getFnameLink() {
        return '<a title="'.htmlspecialchars($this->getName()).'" href="'.$this->getUrl().'">'.htmlspecialchars($this->getFname()).'</a>';
    }

    public function fixBets() {
        if ($this->fb_user_id) {
            /** @var FacebookFriend $fb_friend */
            $fb_friend = FacebookFriend::findOneBy(['fb_id' => $this->fb_user_id]);

            if ($fb_friend) {
                $bet_friends = $fb_friend->getBetFriends();

                foreach($bet_friends as $bet_friend) {
                    $bet_friend->setUser($this);
                    \Base_Controller::$em->persist($bet_friend);
                    \Base_Controller::$em->flush($bet_friend);
                }
            }
        }

        /** @var \Entity\BetFriend[] $bet_friends */
        $bet_friends = BetFriend::findBy(['email' => $this->email]);
        foreach($bet_friends as $bet_friend) {
            $bet_friend->setUser($this);
            \Base_Controller::$em->persist($bet_friend);
            \Base_Controller::$em->flush($bet_friend);
        }
    }

    public function fixChallenges() {
        /** @var ChallengeUser[] $challenge_users */
        $challenge_users = ChallengeUser::findBy(['email' => $this->getEmail()]);

        foreach($challenge_users as $challenge_user) {
            $challenge_user->setUser($this);

            \Base_Controller::$em->persist($challenge_user);
            \Base_Controller::$em->flush($challenge_user);
            $challenge_user->getChallenge()->setUpdatedDate(new \DateTime());
            \Base_Controller::$em->persist($challenge_user->getChallenge());
            \Base_Controller::$em->flush($challenge_user->getChallenge());
        }

    }

	public function fixFacebookGivercards() {
		if (!$this->fb_user_id) {
            return;
        }

        /** @var FacebookFriend $fb_friend */
        $fb_friend = FacebookFriend::findOneBy(['fb_id' => $this->fb_user_id]);

        if ($fb_friend) {
            $givercards = $fb_friend->getGivercardTransactions();

            foreach($givercards as $givercard) {
                $givercard->setToUser($this);
                $givercard->setToUserFbId(null);
                \Base_Controller::$em->persist($givercard);
                \Base_Controller::$em->flush($givercard);
            }
        }
	}

    /**
     * @return User[]
     */
    public function getAllFollowingFollowers() {
        $ret = [];
        $users = $this->getFollowing();
        foreach($users as $user) {
            $ret[$user->getId()] = $user;
        }
        $users = $this->getFollowers();
        foreach($users as $user) {
            $ret[$user->getId()] = $user;
        }
        return $ret;
    }

    /**
     * @return bool
     */
    public function isAdmin() {
        return $this->getLevel() >= 4;
    }

    /**
     * @param Charity $charity
     *
     * @return CharityAdminRequest
     */
    public function getCharityAdminRequest(Charity $charity) {
        /** @var CharityAdminRequest $request */
        $request = CharityAdminRequest::findOneBy(['charity' => $charity, 'user' => $this]);
        return $request;
    }

    /** @var \Entity\CharityAdmin|bool */
    private $charity_admin = false;
    /**
     * @param Charity $charity
     *
     * @return bool
     */
    public function isCharityAdmin(Charity $charity) {
        if ($this->isAdmin()) {
            return true;
        }
        if ($this->charity_admin === false) {
            /** @var \Entity\CharityAdmin $charity_admin */
            $this->charity_admin = CharityAdmin::findOneBy(['charity' => $charity, 'user' => $this]);
        }

        return (bool)$this->charity_admin;
    }

    static public function getJoinedGraphData() {
        $CI =& get_instance();
        $q = $CI->db->query('select joined from users order by joined asc');
        $users = $q->result_array();

        $first_user = $users[0];
        $last_user = end($users);
        $first_date_dt = new \DateTime($first_user['joined']);
        $last_date_dt = new \DateTime($last_user['joined']);

        $dates = [];
        while ($first_date_dt <= $last_date_dt) {
            $dates[$first_date_dt->format('y-m-d')] = 0;
            $first_date_dt->modify('+1 day');
        }

        foreach($users as $user) {
            $joined_dt = new \DateTime($user['joined']);
            $joined_date = $joined_dt->format('y-m-d');
            foreach($dates as $date => $nr) {
               if ($joined_date <= $date) {
                   $dates[$date]++;
               }
            }
        }

        return $dates;
    }

    static public function getGrowthRateGraphData() {
        $graph = self::getJoinedGraphData();

        $now = new \DateTime;
        $minus_one_week = clone $now;
        $minus_one_week->modify('-7 day');

        $first = true;
        $value_has_been_found = false;
        $data = [];
        while(1) {
            $end_date = $now->format('y-m-d');
            $start_date = $minus_one_week->format('y-m-d');

            if ($first) {
                $end_value = end($graph);
                if (isset($graph[$start_date])) {
                    $start_value = $graph[$start_date];
                    $value_has_been_found = true;
                } else {
                    $start_value = $end_value;
                }
                $first = false;
            } else {
                if ($value_has_been_found && (!isset($graph[$start_date]) || !isset($graph[$end_date]))) {
                    break;
                } else {
                    if (isset($graph[$start_date])) {
                        $start_value = $graph[$start_date];
                        $value_has_been_found = true;
                    } else {
                        $start_value = end($graph);
                    }

                    if (isset($graph[$end_date])) {
                        $end_value = $graph[$end_date];
                        $value_has_been_found = true;
                    } else {
                        $end_value = end($graph);
                    }
                }
            }

            $data[$minus_one_week->format('m/d').'-'.$now->format('m/d')] = round((($end_value-$start_value) / $start_value) * 100);

            $now->modify('-7 day');
            $minus_one_week->modify('-7 day');
        }

        $data = array_reverse($data, true);
        array_shift($data);
        return $data;
    }

    static public function getMonthlyGrowthRateData() {
        $data = self::getGrowthRateGraphData();

        $ret = [];

        $data = array_reverse($data, true);
        $chunks = array_chunk($data, 4, true);
        foreach($chunks as $chunk) {
            reset($chunk);
            $first = key($chunk);
            end($chunk);
            $end = key($chunk);
            reset($chunk);

            $end = substr($end, 0, 5);
            $first = substr($first, 6, 5);
            $ret[$end.'-'.$first] = round(array_sum($chunk) / count($chunk), 1);
        }

        return array_reverse($ret,true);
    }

    static public function getGrowthRateData() {
        $data = apc_fetch('growth_rate_data', $success);
        if ($success) {
            return $data;
        } else {
            unset($data);
        }


        $graph = self::getJoinedGraphData();

        $today = new \DateTime();
        $value_now = end($graph);
        reset($graph);

        $one_week_ago = $today->modify('-7 day');

        if (isset($graph[$one_week_ago->format('y-m-d')])) {
            $value_week_ago = $graph[$one_week_ago->format('y-m-d')];
        } else {
            $value_week_ago = $value_now;
        }

        $data['value_week_ago'] = $value_week_ago;
        $data['value_now'] = $value_now;
        $data['growth_rate'] = round((($value_now-$value_week_ago) / $value_week_ago) * 100);


        $CI =& get_instance();
        $db = $CI->db;
        $date_of_first_removal_request = $db->query('SELECT date(date_added) as date_added FROM petition_signature_removal_request order by date_added asc limit 1')->row()->date_added;
        $user_ids_that_joined_since_first_removal_request = $db->query('select id from users where joined >= \'' . $date_of_first_removal_request .'\'')->result_array();

        $joined = 0;
        $requested = 0;
        foreach($user_ids_that_joined_since_first_removal_request as $row) {
            $user = \Entity\User::find($row['id']);
            if (\Entity\PetitionSignatureRemovalRequest::findOneBy(['user' => $user])) {
                $requested++;
            }
            $joined++;
        }

        $data['joined_since_first_removal_request'] = $joined;
        $data['users_that_joined_and_requested_removal'] = $requested;
        $data['percentage_of_users_that_joined_and_requested_removal'] = $joined ? round(($requested / $joined) * 100,2) : 0;

        apc_store('growth_rate_data', $data, 60*60*24);

        return $data;
    }

    /**
     * @return GivercardTransactions[]
     */
    public function getUserGiverCards() {
        $order      		= array('date_created' => 'desc');
        $givercardsById 	= GivercardTransactions::findBy(array('to_user_id' => $this->id), $order);
        $givercardsByEmail 	= GivercardTransactions::findBy(array('to_email' => $this->getEmail()), $order);
        $givercards 		= array_merge_recursive($givercardsById, $givercardsByEmail);
        return $givercards;
    }

    public function hasGiverCardsOrGivingPots() {
        if (GivingPot::findOneBy(['user' => $this])) {
            return true;
        }

        if (GivercardTransactions::findOneBy(array('to_user_id' => $this->id))) {
            return true;
        }

        if (GivercardTransactions::findOneBy(array('to_email' => $this->email))) {
            return true;
        }

        return false;
    }

    /** @var Petition[] */
    private $_my_petitions;

    /**
     * @return Petition[]
     */
    public function getMyPetitions() {
        if ($this->_my_petitions === null) {
            $this->_my_petitions = Petition::findBy(['user' => $this]);
        }
        return $this->_my_petitions;
    }

    /**
     * @return PetitionSignature[]
     */
	public function getMySignedGiverhubPetitions() {
		return PetitionSignature::findBy(['user_id' => $this->id]);
	}

    private $_my_signed_change_org_petitions = null;
    /**
     * @return UserPetitionSignature[]
     */
	public function getMySignedChangeOrgPetitions($refresh_cache = false) {
        if ($this->_my_signed_change_org_petitions === null || $refresh_cache) {
            $this->_my_signed_change_org_petitions = UserPetitionSignature::findBy( [ 'user_id' => $this->id ], ['signed_at' => 'DESC'] );
        }
        return $this->_my_signed_change_org_petitions;
	}

    /** @var Challenge[] */
    private $_my_challenges;

    /**
     * @return Challenge[]
     */
    public function getMyChallenges() {
        if ($this->_my_challenges === null) {
            /** @var Challenge[] $challenges */
            $challenges = [];
            foreach(Challenge::findBy(['fromUser' => $this]) as $challenge) {
                /** @var Challenge $challenge */
                $challenges[$challenge->getId()] = $challenge;
            }

            foreach(ChallengeUser::findBy(['user' => $this]) as $challenge_user) {
                /** @var ChallengeUser $challenge_user */
                $challenge = $challenge_user->getChallenge();
                $challenges[$challenge_user->getChallenge()->getId()] = $challenge;
            }

            $this->_my_challenges = $challenges;
        }

        return $this->_my_challenges;
    }


    static public function getCapabilitiesPercentages() {
        $CI =& get_instance();

        $db = $CI->db;
        $q = $db->query('select capabilities,count(*) as cnt, (count(*)/(select count(*) from users))*100 as pcnt from users group by capabilities');
        $rows = $q->result_array();
        return $rows;
    }

    static public function getSocialPercentages() {
        $CI =& get_instance();

        $types = [
            'no social' => [
                'query' => 'fb_user_id is null AND google_user_id is null',
            ],
            'google and facebook' => [
                'query' => 'fb_user_id is not null AND google_user_id is not null',
            ],
            'google or facebook' => [
                'query' => '(fb_user_id is not null OR google_user_id is not null)'
            ],
            'facebook' => [
                'query' => 'fb_user_id is not null',
            ],
            'google' => [
                'query' => 'google_user_id is not null'
            ],
            'only google' => [
                'query' => 'google_user_id is not null and fb_user_id is null',
            ],
            'only facebook' => [
                'query' => 'google_user_id is null and fb_user_id is not null',
            ],
        ];

        $ret = [];
        $db = $CI->db;

        foreach($types as $type_name => $type) {
            $sql = '
                select
                    \''.$type_name.'\' as type,
                    count(*) as cnt,
                    (
                        count(*) / (select count(*) from users where capabilities = \'confirmed\')
                    )*100 as pcnt
                from
                    users
                where
                    capabilities = \'confirmed\' AND '.$type['query'];

            $q = $db->query($sql);
            $rows = $q->result_array();
            $ret[] = $rows[0];
        }

        return $ret;
    }

    public function getSetting($name) {
        /** @var \Entity\Setting $setting */
        $setting = Setting::findOneBy(['name' => $name]);
        if (!$setting) {
            throw new \Exception('Invalid setting: ' . $name);
        }

        /** @var \Entity\UserSetting $user_setting */
        $user_setting = UserSetting::findOneBy(['user' => $this, 'setting' => $setting]);
        if ($user_setting) {
            return $user_setting->getValue();
        } else {
            return $setting->getDefault();
        }
    }

    public function setSetting($name, $value) {
        /** @var \Entity\Setting $setting */
        $setting = Setting::findOneBy(['name' => $name]);
        if (!$setting) {
            throw new \Exception('Invalid setting: ' . $name);
        }

        /** @var \Entity\UserSetting $user_setting */
        $user_setting = UserSetting::findOneBy(['user' => $this, 'setting' => $setting]);
        if (!$user_setting) {
            $user_setting = new UserSetting();
            $user_setting->setUser($this);
            $user_setting->setSetting($setting);
        }

        $user_setting->setValue($value);

        \Base_Controller::$em->persist($user_setting);
        \Base_Controller::$em->flush($user_setting);
    }

    public function setAuthToken($auth_token) {
        $this->auth_token = $auth_token;
    }

    public function getAuthToken() {
        if (!$this->auth_token) {
            $this->generateAuthToken();
        }
        return $this->auth_token;
    }

    public function generateAuthToken() {
        $auth_token_max_length = 16;
        $id_length = strlen($this->id);
        $remaining_length = $auth_token_max_length - $id_length;
        $this->auth_token = $this->id . bin2hex(openssl_random_pseudo_bytes(floor($remaining_length / 2)));
        return $this->auth_token;
    }

    /** @var Page[] */
    private $_pages;

    /**
     * @return Page[]
     */
    public function getPages() {
        if ($this->_pages === null) {

            $this->_pages = [];

            /** @var \Entity\PageUser[] $page_users */
            $page_users = PageUser::findBy(['user' => $this]);

            foreach($page_users as $page_user) {
                $this->_pages[] = $page_user->getPage();
            }
        }

        return $this->_pages;
    }

    /** @var GivingPot[] */
    private $_giving_pots;

    /**
     * @return GivingPot[]
     */
    public function getGivingPots() {
        if ($this->_giving_pots === null) {
            $this->_giving_pots = GivingPot::findBy(['user' => $this]);
        }

        return $this->_giving_pots;
    }

    public function getDonationHistoryArray() {
        $donationListing    = $this->getDonations();
        /** @var OutsideDonation[] $outside_donations */
        $outside_donations = OutsideDonation::findBy(['user' => $this], ['date' => 'desc', 'time' => 'desc']);

        $donations = [];
        foreach($donationListing as $donation) {
            $donations[$donation->getDate()][] = [
                'name' => $donation->getCharity()->getLink(),
                'date' => $donation->getDate(),
                'amount' => $donation->getAmount(),
                'cause' => $donation->getCharity()->getCauseNames(),
                'has-time' => true
            ];
        }
        foreach($outside_donations as $outside_donation) {
            $donations[$outside_donation->getDateTimeString()][] = [
                'name' => htmlspecialchars($outside_donation->getNonprofitName()),
                'date' => $outside_donation->getDateTimeString(),
                'amount' => $outside_donation->getAmount(),
                'cause' => $outside_donation->getCause(),
                'has-time' => (bool)$outside_donation->getTime()
            ];
        }

        krsort($donations);


        $simplifiedArray    = array();
        $checkMonthYear     = array();
        $checkDate			= array();
        $yearsArray         = array();
        $monthArray         = array();

        foreach($donations as $rows) {
            foreach($rows as $row) {
                $year = date('Y', strtotime($row['date']));
                $month = date('m', strtotime($row['date']));
                $day = date('d', strtotime($row['date']));
                $monthYear    = $month . "/" . $year;

                if ($row['has-time']) {
                    $donationTime = date('H:i', strtotime($row['date']));
                } else {
                    $donationTime = '00:00';
                }

                if (!in_array($row['date'], $checkDate)) {
                    if ( ! in_array( $monthYear, $checkMonthYear )) {
                        $monthTotal = 0;
                    }

                    if ( ! in_array( $year, $yearsArray )) {
                        $simplifiedArray[$year]                                = array();
                        $simplifiedArray[$year]['total']                       = $row['amount'];
                        $simplifiedArray[$year]['details']                     = array();
                        $simplifiedArray[$year]['details'][$month]['total']   = array();
                        $simplifiedArray[$year]['details'][$month]            = array();
                        $simplifiedArray[$year]['details'][$month]['details'] = array();
                    } else {
                        $simplifiedArray[$year]['total'] = $simplifiedArray[$year]['total'] + $row['amount'];
                    }
                }

                $monthTotal += $row['amount'];
                $simplifiedArray[$year]['details'][$month]['total']           = $monthTotal;
                $simplifiedArray[$year]['details'][$month]['details'][$day][] = [
                    'charity_name' => $row['name'],
                    'amount'       => $row['amount'],
                    'date'         => date( 'm/d/y', strtotime( $row['date'] ) ),
                    'time'         => $row['has-time'] ? date( 'h:i A', strtotime( $row['date'] ) ) : '',
                    'causes'       => $row['cause'],
                ];


                $checkDate[]      = $row['date'];
                $checkMonthYear[] = $monthYear;
                $yearsArray[]     = $year;
                $monthArray[]     = $month;
            }
        }

        return $simplifiedArray;
    }

    /**
     * @return string
     */
    public function getUrlBeforeSignup()
    {
        return $this->url_before_signup;
    }

    /**
     * @param string $url_before_signup
     */
    public function setUrlBeforeSignup( $url_before_signup )
    {
        $this->url_before_signup = $url_before_signup;
    }

    /**
     * @return int
     */
    public function getSignPetitionsAnonymously()
    {
        return $this->sign_petitions_anonymously;
    }

    /**
     * @param int $sign_petitions_anonymously
     */
    public function setSignPetitionsAnonymously( $sign_petitions_anonymously )
    {
        $this->sign_petitions_anonymously = $sign_petitions_anonymously;
    }

    /**
     * @return bool
     */
    public function hasUnreadMessages() {
        return (bool)$this->getUnreadMessages();
    }

    /** @var Chat[] */
    private $_unread_messages;

    /**
     * @return Chat[]
     */
    public function getUnreadMessages() {
        if ($this->_unread_messages === null) {
            $query = \Base_Controller::$em->createQuery('
              SELECT
                c
              FROM
                \Entity\Chat c
              WHERE
                c.toUser = :me AND
                c.timeSeen is null
            ');
            $query->setParameters([
                'me' => $this,
            ]);
            /** @var Chat[] $unread_messages */
            $unread_messages = $query->getResult();
            $this->_unread_messages = [];
            foreach($unread_messages as $unread_message) {
                if ($unread_message->getFromUser() == $unread_message->getToUser()) {
                    continue;
                }
                $this->_unread_messages[] = $unread_message;
            }
        }

        return $this->_unread_messages;
    }

    private $_message_data;

    public function getMessageData(Charity $charity = null) {
        if ($this->_message_data === null) {
            $this->_message_data = [];


            $this->_message_data['users'] = [];

            if (!$charity) {
                /** @var User[] $users */
                $users = User::findAll();

                foreach ($users as $user) {
                    $this->_message_data['users'][$user->getId()]['entity']   = $user;
                    $this->_message_data['users'][$user->getId()]['messages'] = [ ];
                }

                $query = \Base_Controller::$em->createQuery('
                  SELECT
                    c
                  FROM
                    \Entity\Chat c
                  WHERE
                    (c.fromUser = :user1 OR c.toUser = :user2)
                  ORDER BY
                    c.id ASC
                ');
                $query->setParameters( [
                    'user1' => $this,
                    'user2' => $this,
                ] );

                /** @var Chat[] $messages */
                $messages = $query->getResult();
            } else {
                $query = \Base_Controller::$em->createQuery('
                  SELECT
                    c
                  FROM
                    \Entity\Chat c
                  WHERE
                    (c.fromCharity = :charity1 OR c.toCharity = :charity2)
                  ORDER BY
                    c.id ASC
                ');
                $query->setParameters( [
                    'charity1' => $charity,
                    'charity2' => $charity,
                ] );

                /** @var Chat[] $messages */
                $messages = $query->getResult();
            }

            $this->_message_data['nonprofits'] = [];

            foreach($messages as $message) {
                if ($charity) {
                    if ($message->getFromCharity() == $charity) {
                        $user = $message->getToUser();
                    } else {
                        $user = $message->getFromUser();
                    }
                    $this->_message_data['users'][$user->getId()]['entity'] = $user;
                    $this->_message_data['users'][$user->getId()]['messages'][] = $message;
                } else {
                    if ($message->getFromCharity() || $message->getToCharity()) {
                        if ($message->getFromCharity()) {
                            $_charity = $message->getFromCharity();
                        } else {
                            $_charity = $message->getToCharity();
                        }
                        $this->_message_data['nonprofits'][$_charity->getId()]['entity']     = $_charity;
                        $this->_message_data['nonprofits'][$_charity->getId()]['messages'][] = $message;
                    } else {
                        if ($message->getFromUser() == $message->getToUser()) {
                            $user_id = $message->getFromUser()->getId();
                        } else {
                            if ($message->getFromUser() == $this) {
                                $user_id = $message->getToUser()->getId();
                            } else {
                                $user_id = $message->getFromUser()->getId();
                            }
                        }
                        $this->_message_data['users'][$user_id]['messages'][] = $message;
                    }
                }
                $this->_message_data['last_message_id'] = $message->getId();
            }

            foreach($this->_message_data['nonprofits'] as $id => $nonprofit) {
                $this->_message_data['nonprofits'][$id]['last_message'] = end($nonprofit['messages']);
                reset($nonprofit['messages']);
            }

            $tmp_users = [];
            foreach($this->_message_data['users'] as $k => $user) {
                if (!$user['messages']) {
                    $user['last_message'] = null;
                    $tmp_users[0][$user['entity']->getName().$user['entity']->getId()] = $user;
                } else {
                    $user['last_message'] = end( $user['messages'] );
                    reset( $user['messages'] ); // just in case lol
                    $tmp_users[$user['last_message']->getId()] = $user;
                }
            }

            krsort($tmp_users);

            $tmp_users2 = [];

            foreach($tmp_users as $k => $users) {
                if ($k == 0) {
                    ksort($users);
                    foreach($users as $user) {
                        $tmp_users2[] = $user;
                    }
                } else {
                    $tmp_users2[] = $users;
                }
            }

            usort($tmp_users2, function($a,$b) {
                if ($a['last_message'] && !$b['last_message']) {
                    return -1;
                }
                if (!$a['last_message'] && $b['last_message']) {
                    return 1;
                }

                if ($a['last_message'] && $b['last_message']) {
                    if ($a['last_message']->isUnreadByCurrentUser() && !$b['last_message']->isUnreadByCurrentUser()) {
                        return -1;
                    }
                    if (!$a['last_message']->isUnreadByCurrentUser() && $b['last_message']->isUnreadByCurrentUser()) {
                        return 1;
                    }
                    return $a['last_message']->getId() > $b['last_message']->getId() ? -1 : 1;
                }

                if ($a['entity']->getName() === $b['entity']->getName()) {
                    return 0;
                } else {
                    return strtoupper($a['entity']->getName()) > strtoupper($b['entity']->getName()) ? 1 : - 1;
                }
            });

            $this->_message_data['users'] = $tmp_users2;
        }

        return $this->_message_data;
    }

    /**
     * @param $last_message_id
     *
     * @return Chat[]
     */
    public function getNewMessages($last_message_id, Charity $charity = null) {
        if ($charity) {
            if ($last_message_id > 0) {
                $query = \Base_Controller::$em->createQuery( '
              SELECT
                c
              FROM
                \Entity\Chat c
              WHERE
                (c.fromCharity = :char1 OR c.toCharity = :char2) AND
                c.id > :last_message_id
              ORDER BY
                c.id ASC
            ' );
                $query->setParameters( [
                    'char1'           => $charity,
                    'char2'           => $charity,
                    'last_message_id' => $last_message_id
                ] );
            } else {
                $query = \Base_Controller::$em->createQuery( '
              SELECT
                c
              FROM
                \Entity\Chat c
              WHERE
                (c.fromCharity = :char1 OR c.toCharity = :char2)
              ORDER BY
                c.id ASC
            ' );
                $query->setParameters( [
                    'char1' => $this,
                    'char2' => $this
                ] );
            }
        } else {
            if ($last_message_id > 0) {
                $query = \Base_Controller::$em->createQuery( '
              SELECT
                c
              FROM
                \Entity\Chat c
              WHERE
                (c.fromUser = :user1 OR c.toUser = :user2) AND
                c.id > :last_message_id
              ORDER BY
                c.id ASC
            ' );
                $query->setParameters( [
                    'user1'           => $this,
                    'user2'           => $this,
                    'last_message_id' => $last_message_id
                ] );
            } else {
                $query = \Base_Controller::$em->createQuery( '
              SELECT
                c
              FROM
                \Entity\Chat c
              WHERE
                (c.fromUser = :user1 OR c.toUser = :user2)
              ORDER BY
                c.id ASC
            ' );
                $query->setParameters( [
                    'user1' => $this,
                    'user2' => $this
                ] );
            }
        }

        /** @var Chat[] $messages */
        $messages = $query->getResult();

        return $messages;
    }
}

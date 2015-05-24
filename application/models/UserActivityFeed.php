<?php

use \Entity\User;

class UserActivityFeed {
    static $user;
    static $userId;
    /** @var \Entity\BaseEntity[] */
    static $items;
    static $friends;

    static public $context = 'my';

    static public $hides = [];
    /**
     * @param User $user
     * @param int  $offset
     * @param null $limit
     * @param      $context
     *
     * @return array
     */
    static public function get(User $user, $offset = 0, $limit = null, $context) {
        self::$context = $context;
        self::$userId = $user->getId();
        self::$user = $user;
        self::$items = array();

        $GLOBALS['super_timers']['afaf1'] = microtime(true) - $GLOBALS['super_start'];
        self::setFriends();
        $GLOBALS['super_timers']['afaf2'] = microtime(true) - $GLOBALS['super_start'];

        if ($context === 'my') {
            $GLOBALS['super_timers']['afaf2_1'] = microtime(true) - $GLOBALS['super_start'];
            self::loadHides();
            $GLOBALS['super_timers']['afaf2_2'] = microtime(true) - $GLOBALS['super_start'];
        }

        $GLOBALS['super_timers']['afaf3'] = microtime(true) - $GLOBALS['super_start'];
        self::donations();
        $GLOBALS['super_timers']['afaf4'] = microtime(true) - $GLOBALS['super_start'];
        self::followingCharity();
        $GLOBALS['super_timers']['afaf5'] = microtime(true) - $GLOBALS['super_start'];
        self::followingUser();
        $GLOBALS['super_timers']['afaf6'] = microtime(true) - $GLOBALS['super_start'];
        self::reviews();
        $GLOBALS['super_timers']['afaf7'] = microtime(true) - $GLOBALS['super_start'];
        self::facebookLikes();
        $GLOBALS['super_timers']['afaf8'] = microtime(true) - $GLOBALS['super_start'];
        self::activityFeedPosts();
        $GLOBALS['super_timers']['afaf9'] = microtime(true) - $GLOBALS['super_start'];
        self::charityChanges();
        $GLOBALS['super_timers']['afaf10'] = microtime(true) - $GLOBALS['super_start'];
        self::challenges();
        $GLOBALS['super_timers']['afaf11'] = microtime(true) - $GLOBALS['super_start'];
        self::changeOrgPetitionSignatures();
        $GLOBALS['super_timers']['afaf12'] = microtime(true) - $GLOBALS['super_start'];
        self::giverhubPetitionSignatures();
        $GLOBALS['super_timers']['afaf13'] = microtime(true) - $GLOBALS['super_start'];
        self::changeOrgPetitionFacebookShares();
        $GLOBALS['super_timers']['afaf14'] = microtime(true) - $GLOBALS['super_start'];
        self::giverhubPetitionFacebookShares();
        $GLOBALS['super_timers']['afaf15'] = microtime(true) - $GLOBALS['super_start'];
        self::sort();
        $GLOBALS['super_timers']['afaf16'] = microtime(true) - $GLOBALS['super_start'];
        self::$items = array_slice(self::$items, $offset, $limit);
        $GLOBALS['super_timers']['afaf17'] = microtime(true) - $GLOBALS['super_start'];
        return self::$items;
    }

    static public function loadHides() {
        /** @var \Entity\ActivityHide[] $hides */
        $hides = \Entity\ActivityHide::findBy(['user' => self::$user]);
        foreach($hides as $hide) {
            self::$hides[$hide->getActivityType()][$hide->getActivityId()] = true;
        }
    }

    static public function sort() {
        krsort(self::$items);
        $items = array();
        foreach(self::$items as $itemArray) {
            foreach($itemArray as $item) {
                $items[] = $item;
            }
        }
        self::$items = $items;
    }

    static public function setFriends() {
        $em = Base_Controller::$em;
        $fuRepo = $em->getRepository('\Entity\UserFollower');
        /** @var \Entity\UserFollower[] $ufs */
        $ufs = $fuRepo->findBy(array(
                                    'follower_user_id' => self::$userId
                               ));
        self::$friends = array();
        foreach($ufs as $uf) {
            self::$friends[] = $uf->getFollowedUserId();
        }
    }

    static public function donations() {
        if (self::$context != 'my') {
            return;
        }

        $qb = \Base_Controller::$em->createQueryBuilder();
        $qb->select('d')
           ->from('\Entity\Donation', 'd')
           ->where('d.paypal = 0 OR d.paypal = 2');
        $query = $qb->getQuery();
        /** @var \Entity\Donation[] $donations */
        $donations = $query->getResult();

        foreach($donations as $donation) {
            if (self::$context === "other" && $donation->getUser()->getId() === \Base_Controller::$staticUser->getId()) {
                continue;
            }

            if (isset(self::$hides['donation'][$donation->getId()])) {
                continue;
            }

            $date = $donation->getDate();
            if (isset(self::$items[$date])) {
                self::$items[$date][] = $donation;
            } else {
                self::$items[$date] = array($donation);
            }
        }

    }

    static public function followingCharity() {
        $em = Base_Controller::$em;

        $cfRepo = $em->getRepository('\Entity\CharityFollower');

        if (self::$context == 'my') {
            $users = array_merge(self::$friends, array(self::$userId));
        } else {
            $users = array(self::$userId);
        }

        foreach($users as $userId) {
            /** @var \Entity\CharityFollower[] $charityFollowers */
            $charityFollowers = $cfRepo->findBy(array(
                                             'user_id' => $userId
                                        ));
            foreach($charityFollowers as $charityFollower) {
                if (isset(self::$hides['charity-follower'][$charityFollower->getId()])) {
                    continue;
                }
                $date = $charityFollower->getDate();
                if (isset(self::$items[$date])) {
                    self::$items[$date][] = $charityFollower;
                } else {
                    self::$items[$date] = array($charityFollower);
                }
            }
        }
    }

    static public function followingUser() {
        //if (self::$context != 'my') return;

        $em = Base_Controller::$em;

        $cfRepo = $em->getRepository('\Entity\UserFollower');

        foreach(array('follower_user_id', 'followed_user_id') as $col) {
            /** @var \Entity\UserFollower[] $userFollowers */
            $userFollowers = $cfRepo->findBy(array(
                                                     $col => self::$userId
                                                ));
            foreach($userFollowers as $userFollower) {
                if (isset(self::$hides['user-follower'][$userFollower->getId()])) {
                    continue;
                }
                $date = $userFollower->getDate();
                if (isset(self::$items[$date])) {
                    self::$items[$date][] = $userFollower;
                } else {
                    self::$items[$date] = array($userFollower);
                }
            }
        }
    }

    static public function reviews() {
        if (self::$context != 'my') {
            return;
        }

        $em = Base_Controller::$em;

        $cfRepo = $em->getRepository('\Entity\CharityReview');

        foreach(array_merge(self::$friends, array(self::$userId)) as $userId) {
            /** @var \Entity\CharityReview[] $charityReviews */
            $charityReviews = $cfRepo->findBy(array(
                                                     'user_id' => $userId
                                                ));
            foreach($charityReviews as $charityReview) {
                if (isset(self::$hides['charity-review'][$charityReview->getId()])) {
                    continue;
                }
                $date = $charityReview->getDate();
                if (isset(self::$items[$date])) {
                    self::$items[$date][] = $charityReview;
                } else {
                    self::$items[$date] = array($charityReview);
                }
            }
        }
    }

    static public function facebookLikes() {
        if (self::$context != 'my') {
            return;
        }

        foreach(array_merge(self::$friends, array(self::$userId)) as $userId) {
            /** @var \Entity\FacebookLike[] $facebookLikes */
            $facebookLikes = \Entity\FacebookLike::findBy(array('user_id' => $userId));
            foreach($facebookLikes as $facebookLike) {
                if (isset(self::$hides['facebook-like'][$facebookLike->getId()])) {
                    continue;
                }
                $date = $facebookLike->getLikedAt();
                if (isset(self::$items[$date])) {
                    self::$items[$date][] = $facebookLike;
                } else {
                    self::$items[$date] = array($facebookLike);
                }
            }
        }
    }

    static public function activityFeedPosts() {

        $deleted_ids = [];
        if (\Base_Controller::$staticUser) {
            /** @var \Entity\ActivityFeedPostDelete[] $my_deleted */
            $my_deleted = \Entity\ActivityFeedPostDelete::findBy( [ 'user_id' => \Base_Controller::$staticUser->getId() ] );

            foreach ($my_deleted as $deleted) {
                $deleted_ids[$deleted->getActivityFeedPostId()] = true;
            }
        }

        /** @var \Entity\ActivityFeedPostDelete[] $my_deleted */
        $my_deleted = \Entity\ActivityFeedPostDelete::findBy([ 'user_id' => self::$userId]);

        foreach ($my_deleted as $deleted) {
            $deleted_ids[$deleted->getActivityFeedPostId()] = true;
        }


        /** @var \Entity\ActivityFeedPost[] $activity_feed_posts */
        $activity_feed_posts = [];

        $query = \Base_Controller::$em->createQuery(
          'SELECT afp FROM Entity\ActivityFeedPost afp WHERE afp.is_deleted = 0 AND (afp.to_user_id = :to_user_id OR afp.from_user_id = :from_user_id)'
        );
        $query->setParameter('to_user_id', self::$userId);
        $query->setParameter('from_user_id', self::$userId);

        /** @var \Entity\ActivityFeedPost[] $tmp_posts */
        $tmp_posts = $query->getResult();

        foreach($tmp_posts as $post) {
            if (!isset($deleted_ids[$post->getId()])) {
                $activity_feed_posts[$post->getId()] = $post;
            }
        }

        if (self::$context == 'my') {
            foreach(self::$friends as $userId) {
                $query = \Base_Controller::$em->createQuery(
                  'SELECT afp FROM Entity\ActivityFeedPost afp WHERE
                  afp.is_deleted = 0 AND (
                      (
                       (afp.to_user_id = :my_id_1 OR afp.from_user_id = :my_id_2) AND
                       (afp.to_user_id = :friend_id_1 OR afp.from_user_id = :friend_id_2)
                      ) OR (
                        afp.to_user_id = :friend_id_3 AND afp.from_user_id = :friend_id_4
                      )
                  )'
                );
                $query->setParameter('my_id_1', self::$userId);
                $query->setParameter('my_id_2', self::$userId);
                $query->setParameter('friend_id_1', $userId);
                $query->setParameter('friend_id_2', $userId);
                $query->setParameter('friend_id_3', $userId);
                $query->setParameter('friend_id_4', $userId);


                /** @var \Entity\ActivityFeedPost[] $tmp_posts */
                $tmp_posts = $query->getResult();

                foreach($tmp_posts as $post) {
                    if (!isset($deleted_ids[$post->getId()])) {
                        $activity_feed_posts[$post->getId()] = $post;
                    }
                }
            }
        }

        foreach($activity_feed_posts as $activity_feed_post) {
            $date = $activity_feed_post->getDate();
            if (isset(self::$items[$date])) {
                self::$items[$date][] = $activity_feed_post;
            } else {
                self::$items[$date] = [$activity_feed_post];
            }
        }
    }

    static public function charityChanges() {
        if (self::$context != 'my') {
            return;
        }

        foreach(array_merge(self::$friends, array(self::$userId)) as $userId) {
            /** @var \Entity\CharityChangeHistory[] $items */
            $items = \Entity\CharityChangeHistory::findBy(array('user_id' => $userId));
            foreach($items as $item) {
                if (isset(self::$hides['charity-change'][$item->getId()])) {
                    continue;
                }
                $date = $item->getDateTime();
                if (isset(self::$items[$date])) {
                    self::$items[$date][] = $item;
                } else {
                    self::$items[$date] = array($item);
                }
            }
        }
    }

    static public function challenges() {
        /** @var \Entity\Challenge[] $challenges */
        $challenges = [];

        if (self::$context == 'other') {
            $users = [self::$userId];
        } else {
            $users = array_merge(self::$friends, array(self::$userId));
        }

        foreach($users as $userId) {
            $user = \Entity\User::find($userId);

            /** @var \Entity\Challenge[] $tmp_challenges */
            $tmp_challenges = \Entity\Challenge::findBy(['fromUser' => $user]);

            foreach($tmp_challenges as $challenge) {
                $challenges[$challenge->getId()] = $challenge;
            }

            /** @var \Entity\ChallengeUser[] $challenge_users */
            $challenge_users = \Entity\ChallengeUser::findBy(['user' => $user]);
            foreach($challenge_users as $challenge_user) {
                $challenge = $challenge_user->getChallenge();
                $challenges[$challenge->getId()] = $challenge;
            }
        }

        foreach($challenges as $challenge) {
            if ($challenge->isDraft()) {
                continue;
            }
            if (isset(self::$hides['challenge'][$challenge->getId()])) {
                continue;
            }
            $date = $challenge->getUpdatedDate() ? $challenge->getUpdatedDate() : $challenge->getCreatedDate();
            $date = $date->format('Y-m-d H:i:s');
            if (isset(self::$items[$date])) {
                self::$items[$date][] = $challenge;
            } else {
                self::$items[$date] = [$challenge];
            }
        }
    }

    static public function changeOrgPetitionSignatures() {
        /** @var \Entity\UserPetitionSignature[] $signatures */
        $signatures = [];

        /** @var \Entity\UserPetitionSignature[] $tmp_signatures */
        $tmp_signatures = \Entity\UserPetitionSignature::findAll();
        $GLOBALS['super_timers']['afaf11_1'] = microtime(true) - $GLOBALS['super_start'];

        foreach($tmp_signatures as $signature) {
            if (self::$context === "other" && $signature->getUserId() === \Base_Controller::$staticUser->getId()) {
                continue;
            }
            $signatures[$signature->getId()] = $signature;
        }
        $GLOBALS['super_timers']['afaf11_2'] = microtime(true) - $GLOBALS['super_start'];

        foreach($signatures as $signature) {
            if (isset(self::$hides['co-pet-signature'][$signature->getId()])) {
                continue;
            }
            $date = $signature->getSignedAt();
            if (isset(self::$items[$date])) {
                self::$items[$date][] = $signature;
            } else {
                self::$items[$date] = [$signature];
            }
        }
        $GLOBALS['super_timers']['afaf11_3'] = microtime(true) - $GLOBALS['super_start'];
    }

    static public function giverhubPetitionSignatures() {
        /** @var \Entity\PetitionSignature[] $signatures */
        $signatures = [];

        /** @var \Entity\PetitionSignature[] $tmp_signatures */
        $tmp_signatures = \Entity\PetitionSignature::findAll();

        foreach($tmp_signatures as $signature) {
            if (self::$context === "other" && $signature->getUser()->getId() === \Base_Controller::$staticUser->getId()) {
                continue;
            }
            $signatures[$signature->getId()] = $signature;
        }

        foreach($signatures as $signature) {
            if (isset(self::$hides['gh-pet-signature'][$signature->getId()])) {
                continue;
            }
            $date = $signature->getSignedOn();
            if (isset(self::$items[$date])) {
                self::$items[$date][] = $signature;
            } else {
                self::$items[$date] = [$signature];
            }
        }
    }

    static public function changeOrgPetitionFacebookShares() {
        /** @var \Entity\ChangeOrgPetitionFacebookShare[] $shares */
        $shares = [];

        /** @var \Entity\ChangeOrgPetitionFacebookShare[] $tmp_shares */
        $tmp_shares = \Entity\ChangeOrgPetitionFacebookShare::findAll();

        foreach($tmp_shares as $share) {
            if (self::$context === "other" && $share->getUser()->getId() === \Base_Controller::$staticUser->getId()) {
                continue;
            }
            $shares[$share->getId()] = $share;
        }

        foreach($shares as $share) {
            if (isset(self::$hides['co-pet-facebook-share'][$share->getId()])) {
                continue;
            }
            $date = $share->getDate()->format('Y-m-d H:i:s');
            if (isset(self::$items[$date])) {
                self::$items[$date][] = $share;
            } else {
                self::$items[$date] = [$share];
            }
        }
    }

    static public function giverhubPetitionFacebookShares() {
        /** @var \Entity\PetitionFacebookShare[] $shares */
        $shares = [];

        /** @var \Entity\PetitionFacebookShare[] $tmp_shares */
        $tmp_shares = \Entity\PetitionFacebookShare::findAll();

        foreach($tmp_shares as $share) {
            if (self::$context === "other" && $share->getUser()->getId() === \Base_Controller::$staticUser->getId()) {
                continue;
            }
            $shares[$share->getId()] = $share;
        }

        foreach($shares as $share) {
            if (isset(self::$hides['gh-pet-facebook-share'][$share->getId()])) {
                continue;
            }
            $date = $share->getDate()->format('Y-m-d H:i:s');
            if (isset(self::$items[$date])) {
                self::$items[$date][] = $share;
            } else {
                self::$items[$date] = [$share];
            }
        }
    }
}
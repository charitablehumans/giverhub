<?php
/** @var Object $activity */
/** @var string $context */

$class_name = get_class($activity);
switch($class_name) {
    case 'Entity\Donation':
        $this->load->view('/members/_activity_donation', array('donation' => $activity, 'context' => $context));
        break;
    case 'Entity\CharityFollower':
        $this->load->view('/members/_activity_charity_follower', array('charityFollower' => $activity, 'context' => $context));
        break;
    case 'Entity\UserFollower':
        $this->load->view('/members/_activity_user_follower', array('userFollower' => $activity, 'context' => $context));
        break;
    case 'Entity\CharityReview':
        $this->load->view('/members/_activity_charity_review', array('charityReview' => $activity, 'context' => $context));
        break;
    case 'Entity\FacebookLike':
        $this->load->view('/members/_activity_facebook_like', array('facebookLike' => $activity, 'context' => $context));
        break;
    case 'Entity\ActivityFeedPost':
        $this->load->view('/members/_activity_feed_post', array('activity_feed_post' => $activity, 'context' => $context));
        break;
    case 'Entity\CharityChangeHistory':
        $this->load->view('/members/_activity_charity_change', array('charity_change_history' => $activity, 'context' => $context));
        break;
    case 'Entity\Challenge':
    case 'DoctrineProxies\__CG__\Entity\Challenge':
        $this->load->view('/members/_activity_challenge', ['challenge' => $activity, 'context' => $context]);
        break;
    case 'Entity\PetitionSignature':
    case 'Entity\UserPetitionSignature':
        $this->load->view('/members/_activity_signature', ['signature' => $activity, 'context' => $context]);
        break;
    case 'Entity\PetitionFacebookShare':
    case 'Entity\ChangeOrgPetitionFacebookShare':
        $this->load->view('/members/_activity_petition_facebook_share', ['facebook_share' => $activity, 'context' => $context]);
        break;
}
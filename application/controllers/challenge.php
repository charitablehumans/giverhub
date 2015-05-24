<?php
use \Entity\User;
use \Entity\Charity;

require_once(__DIR__ . '/Base_Controller.php');


class Challenge extends Base_Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function index($id) {
        /** @var \Entity\Challenge $challenge */
        $challenge = \Entity\Challenge::find($id);
        if (!$challenge || $challenge->isDraft()) {
            $this->giverhub_404('nonprofits/charity-404', 'Challenge Not Found');
            return;
        }

        $data['challenge'] = $challenge;
        $data['main_content'] = '/challenges/index';

        array_unshift($this->ogImage, 'https://img.youtube.com/vi/'.htmlspecialchars($challenge->getYoutubeVideoId()).'/0.jpg');

        $this->headerPrefix = 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# giverhub: http://ogp.me/ns/fb/giverhub#';
        $this->ogType = 'giverhub:challenge';
        $this->ogTitle = $challenge->getNameWithChallenge();
        $this->ogDesc = $challenge->getDescription(['remove_ending_dot' => true]) . ".\n\nGiverHub: Donate instantly, itemize automatically.";

        $this->load->view('includes/user/template', $data);
    }

    public function create() {
        if (!$this->user) {
            redirect('/?redirect=/challenge/create');
        }
        $data['main_content'] = '/challenges/create';

        $this->htmlTitle = 'Create Challenge';

        $this->load->view('includes/user/template', $data);
    }

    public function edit($id) {
        if (!$this->user) {
            redirect('/?redirect=/challenge/edit/'.$id);
        }
        $data['main_content'] = '/challenges/edit';

        /** @var \Entity\Challenge $challenge */
        $challenge = \Entity\Challenge::find($id);
        if (!$challenge) {
            redirect('/');
        }
        if ($challenge->getFromUser() != $this->user) {
            redirect('/');
        }

        if (!$challenge->isDraft()) {
            redirect($challenge->getUrl());
        }
        $data['challenge'] = $challenge;
        $this->htmlTitle = 'Edit Challenge';

        $this->load->view('includes/user/template', $data);
    }

    public function reissue($id) {
        if (!$this->user) {
            redirect('/?redirect=/challenge/reissue/'.$id);
        }
        /** @var \Entity\Challenge $challenge */
        $challenge = \Entity\Challenge::find($id);

        if (!$challenge) {
            throw new Exception('could not load challenge: ' . $id);
        }

        $clone = new \Entity\Challenge;
        $clone->setFromUser($this->user);
        $clone->setCharity($challenge->getCharity());
        $clone->setName($challenge->getName());
        $clone->setDedication($challenge->getDedication());
        $clone->setDescription($challenge->getDescription());
        $clone->setYoutubeVideoId($challenge->getYoutubeVideoId());

        self::$em->persist($clone);
        self::$em->flush($clone);

        redirect('/challenge/edit/'.$clone->getId());
    }

    public function save() {
        if (!$this->user) {
            throw new Exception('user is not signed in!');
        }

        if (!isset($_POST['name'])) {
            throw new Exception('name is missing!');
        }
        if (!strlen($_POST['name'])) {
            throw new Exception('name is too short! should have been validated by client side js. name: ' . $_POST['name']);
        }

        if (!isset($_POST['description'])) {
            throw new Exception('description is missing!');
        }
        if (strlen($_POST['description']) < 5) {
            throw new Exception('description is too short. should have been validated by client side javascript. description: ' . $_POST['description']);
        }

        if (!isset($_POST['emails'])) {
            throw new Exception('emails are not set.');
        }
        if (!is_array($_POST['emails'])) {
            throw new Exception('emails is not an array');
        }
        if (empty($_POST['emails'])) {
            throw new Exception('emails array is empty');
        }
        if (count($_POST['emails']) > 3) {
            throw new Exception('emails is too large. count: ' . count($_POST['emails']));
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('charity_id is missing.');
        }
        $charity = Charity::find($_POST['charity_id']);
        if (!$charity) {
            throw new Exception('charity_id could not be found in db. charity_id: ' . $_POST['charity_id']);
        }

        if (!isset($_POST['video_id'])) {
            throw new Exception('video_id is not set.');
        }

        if (isset($_POST['challenge_id'])) {
            /** @var \Entity\Challenge $challenge */
            $challenge = \Entity\Challenge::find($_POST['challenge_id']);
            if (!$challenge) {
                throw new Exception('Could not load challenge. challenge_id: ' . $_POST['challenge_id']);
            }
            if ($challenge->getFromUser() != $this->user) {
                throw new Exception('challenge is not owned by current user. from_user_id: ' . $challenge->getFromUser()->getId() . ' current-user-id: ' . $this->user->getId());
            }

            if (!$challenge->isDraft()) {
                throw new Exception('Trying to edit challenge that has already been sent.');
            }
        } else {
            $challenge = new \Entity\Challenge();
        }

        if (isset($_POST['dedication'])) {
            $challenge->setDedication($_POST['dedication']);
        } else {
            $challenge->setDedication(null);
        }

        $challenge->setCharity($charity);
        $challenge->setFromUser($this->user);
        $challenge->setDescription($_POST['description']);
        $challenge->setName($_POST['name']);
        $challenge->setYoutubeVideoId($_POST['video_id']);

        self::$em->persist($challenge);
        self::$em->flush($challenge);

        $challenge->setFriendsFromPost($_POST['emails']);

        if (isset($_POST['publish']) && $_POST['publish']) {
            $challenge->send();
        }

        $json = ['success' => true, 'challenge_id' => $challenge->getId()];
        if (@$_POST['my_challenges_table']) {
            $json['my_challenges_table'] = $this->load->view('/challenges/_my_challenges_table', ['challenges' => $this->user->getMyChallenges()], true);
        }
        echo json_encode($json);
    }

    public function send() {
        if (!$this->user) {
            throw new Exception('user is not signed in!');
        }

        if (!isset($_POST['challenge_id'])) {
            throw new Exception('missing challenge_id');
        }

        /** @var \Entity\Challenge $challenge */
        $challenge = \Entity\Challenge::find($_POST['challenge_id']);
        if (!$challenge) {
            throw new Exception('Challenge could not be loaded. challenge_id: ' . $_POST['challenge_id']);
        }

        if ($challenge->getFromUser() != $this->user) {
            throw new Exception('challenge does not belong to user. challenge-user-id: ' . $challenge->getFromUser()->getId() . ' current-user-id: ' . $this->user->getId() . ' challenge-id: ' . $challenge->getId());
        }

        $challenge->send();

        $json = ['success' => true];
        if (@$_POST['my_challenges_table']) {
            $json['my_challenges_table'] = $this->load->view('/challenges/_my_challenges_table', ['challenges' => $this->user->getMyChallenges()], true);
        }
        echo json_encode($json);
    }

    public function accept_reject() {
        if (!$this->user) {
            throw new Exception('user is not signed in!');
        }

        if (!isset($_POST['challenge_id'])) {
            throw new Exception('missing challenge_id');
        }

        /** @var \Entity\Challenge $challenge */
        $challenge = \Entity\Challenge::find($_POST['challenge_id']);
        if (!$challenge) {
            throw new Exception('Challenge could not be loaded. challenge_id: ' . $_POST['challenge_id']);
        }

        if (!isset($_POST['accept'])) {
            throw new Exception('accept is missing');
        }

        if (!in_array($_POST['accept'], [0,1])) {
            throw new Exception('Invalid value for accept: ' . $_POST['accept'] . ' expecting 0 or 1');
        }

        if (!$challenge->isToUser($this->user)) {
            throw new Exception('current user is not listed as receiver for this challenge');
        }

        if ($_POST['accept']) {
            $challenge->accept($this->user);
        } else {
            $challenge->reject($this->user);
        }

        echo json_encode(['success' => true, 'challenge_info_html' => $this->load->view('/challenges/_info', ['challenge' => $challenge], true)]);
    }

    public function upload_video() {
        if (!$this->user) {
            throw new Exception('user is not signed in!');
        }

        if (!isset($_POST['challenge_id'])) {
            throw new Exception('missing challenge_id');
        }

        /** @var \Entity\Challenge $challenge */
        $challenge = \Entity\Challenge::find($_POST['challenge_id']);
        if (!$challenge) {
            throw new Exception('Challenge could not be loaded. challenge_id: ' . $_POST['challenge_id']);
        }

        if (!$challenge->isToUser($this->user)) {
            throw new Exception('current user is not listed as receiver for this challenge');
        }

        $challenge_user = $challenge->getToChallengeUser($this->user);

        $ret = $challenge_user->uploadChallengeUserVideoFromPost();

        if ($ret['success']) {
            $ret['challenge_info_html'] = $this->load->view('/challenges/_info', ['challenge' => $challenge], true);
        }

        echo json_encode($ret);
    }

    public function upload_youtube() {
        if (!$this->user) {
            throw new Exception('user is not signed in!');
        }

        if (!isset($_POST['challenge_id'])) {
            throw new Exception('missing challenge_id');
        }

        if (!isset($_POST['video_id'])) {
            throw new Exception('missing video_id');
        }

        /** @var \Entity\Challenge $challenge */
        $challenge = \Entity\Challenge::find($_POST['challenge_id']);
        if (!$challenge) {
            throw new Exception('Challenge could not be loaded. challenge_id: ' . $_POST['challenge_id']);
        }

        if (!$challenge->isToUser($this->user)) {
            throw new Exception('current user is not listed as receiver for this challenge');
        }

        $challenge_user = $challenge->getToChallengeUser($this->user);

        $video = new \Entity\ChallengeUserVideo();
        $video->setFilename(null);
        $video->setYoutubeVideoId($_POST['video_id']);
        $video->setFiletype(null);

        $challenge_user->replaceVideo($video);

        echo json_encode([
            'success' => true,
            'challenge_info_html' => $this->load->view('/challenges/_info', ['challenge' => $challenge], true)
        ]);
    }

    public function resend() {
        if (!$this->user) {
            throw new Exception('user not signed in');
        }

        if (!isset($_POST['challenge_user_id'])) {
            throw new Exception('missing challenge_user_id');
        }

        /** @var \Entity\ChallengeUser $challenge_user */
        $challenge_user = \Entity\ChallengeUser::find($_POST['challenge_user_id']);

        if ($challenge_user->getChallenge()->getFromUser() != $this->user) {
            throw new Exception('challenge does not belong to user: user-id: ' . $this->user->getId() . ' challenge-user-id: ' . $challenge_user->getId());
        }

        if (!$challenge_user->canBeResent()) {
            echo json_encode(['success' => true, 'msg' => 'You need to wait one day before you can resend again.']);
            return;
        }

        $challenge_user->send();

        echo json_encode(['success' => true]);
    }

}
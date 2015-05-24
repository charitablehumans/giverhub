<?php
require_once(__DIR__ . '/Base_Controller.php');

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use \Entity\Petition;
use \Entity\PetitionUrlSlug;
use \Entity\PetitionPhoto;

class Giverhub_Petition extends \Base_Controller {

    public function publish() {
        if (!$this->user) {
            throw new Exception('User is not signed');
        }

        foreach(['target', 'what', 'why'] as $required) {
            if (!isset($_POST[$required])) {
                throw new Exception($required . ' is missing');
            }
        }

        $petition = new Petition();

        $petition->setTargetText($_POST['target']);
        $petition->setWhatText($_POST['what']);
        $petition->setWhyText($_POST['why']);
        $petition->setUser($this->user);
        $petition->setStatus('open');		

        if (isset($_POST['photo_id'])) {
            /** @var \Entity\PetitionPhoto $photo */
            $photo = \Entity\PetitionPhoto::find($_POST['photo_id']);
            if (!$photo) {
                throw new Exception('photo cant be loaded. photo_id: ' . $_POST['photo_id']);
            }
            if ($photo->getUser() != $this->user) {
                throw new Exception('Photo not owned by current user. current_user_id: ' . $this->user->getId() . ' photo-user-id: ' . $photo->getUser()->getId() . ' photo_id: ' . $_POST['photo_id']);
            }
            $photo->setTempId(null);
            self::$em->persist($photo);
            self::$em->flush($photo);
            $petition->setPhoto($photo);
        } elseif (isset($_POST['video_id'])) {
            $petition->setVideoId($_POST['video_id']);
        } elseif (isset($_POST['img_url'])) {
            $petition->setImgUrl($_POST['img_url']);
        }

        self::$em->persist($petition);
        self::$em->flush($petition);

        $this->user->addGiverHubScore('publish-petition');

        echo json_encode([
                'success' => true,
                'petition' => $petition
            ]);
    }

    public function add_email() {
        if (!$this->user) {
            throw new Exception('user is not signed in');
        }

        if (!isset($_POST['email'])) {
            throw new Exception('email is missing');
        }

        if (!isset($_POST['petition_id'])) {
            throw new Exception('petition_id is missing');
        }

        /** @var \Entity\Petition $petition */
        $petition = \Entity\Petition::find($_POST['petition_id']);

        if (!$petition) {
            throw new Exception('Could not load petition. petition_id: ' . $_POST['petition_id']);
        }

        if ($petition->getUser() != $this->user) {
            throw new Exception('user does not own this petition. petition_id: ' . $_POST['petition_id'] . ' signed-in-user-id: ' . $this->user->getId() . ' petition-user-id: ' . $petition->getUser()->getId());
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'msg' => 'Email is invalid']);
            return;
        }

        $email = \Entity\PetitionEmail::findOneBy(['petition' => $petition, 'email' => $_POST['email']]);
        if (!$email) {
            $email = new \Entity\PetitionEmail();
            $email->setEmail($_POST['email']);
            $email->setPetition($petition);

            self::$em->persist($email);
            self::$em->flush($email);
        }

        echo json_encode(['success' => true]);
    }

    /**
     * @param      $slug
     * @param bool $redirect
     *
     * @return Petition
     * @throws GiverHub404Exception
     */
    private function loadPetition($slug, $redirect = true) {
        /** @var Petition $petition */
        $petition = Petition::findOneBy(array('urlSlug' => $slug));
        if (!$petition) {
            /** @var PetitionUrlSlug $history */
            $history = PetitionUrlSlug::findOneBy(['urlSlug' => $slug]);
            if ($history) {
                if ($redirect) {
                    redirect($history->getPetition()->getUrl(), 302);
                } else {
                    return $history->getPetition();
                }
            } else {
                throw new GiverHub404Exception('Failed to load petition.');
            }
        }
        return $petition;
    }

    /**
     * @param $id
     *
     * @return Petition
     * @throws Exception
     */
    private function loadPetitionById($id) {
        /** @var Petition $petition */
        $petition = Petition::find($id);
        if (!$petition) {
            throw new GiverHub404Exception('Failed to load petition.');
        }
        return $petition;
    }

    public function index($slug = null) {
        if (!$slug) {
            redirect('a-z/g-petitions', 'location', 301);
        }

        try {
            $petition = $this->loadPetition($slug);
            $data['petition'] = $petition;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('petitions/petition-404', 'Petition not found');
            return;
        }
        $this->htmlTitle = $petition->getTitle() . ' - Overview';
        $this->ogTitle = "I just signed the petition: ".$petition->getTitle()." through GiverHub.";
        $this->ogDesc = $this->ogTitle;
        if ($petition->hasImage()) {
            array_unshift($this->ogImage, $petition->getImageUrlPrependHttp());
        }

        $this->metaDesc = 'Sign the petition '.$petition->getTitle() . '.';

        $data['main_content'] = 'giverhub-petitions/index';

        $this->load->view('includes/user/template', $data);
    }

    public function link_nonprofit() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }

        if (!isset($_POST['petition_id'])) {
            throw new Exception('Invalid request. missing petition_id');
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('Invalid request. missing charity_id');
        }

        /** @var Petition $petition */
        $petition = Petition::find($_POST['petition_id']);

        if (!$petition) {
            throw new Exception('Petition could not be loaded. petition_id: ' . $_POST['petition_id']);
        }

        if ($petition->getUser() != $this->user) {
            throw new Exception('Petition does not belong to currently signed in user. signed-in-user-id: ' . $this->user->getId() . ' petition-user-id: '. $petition->getUser()->getId() . ' petition_id: ' . $petition->getId());
        }

        /** @var \Entity\Charity $charity */
        $charity = \Entity\Charity::find($_POST['charity_id']);

        if (!$charity) {
            throw new Exception('charity could not be loaded. charity_id: ' . $charity->getId());
        }

        $petition->setCharity($charity);

        self::$em->persist($petition);
        self::$em->flush($petition);

        echo json_encode(['success' => true]);
    }

    public function clear_nonprofit() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }

        if (!isset($_POST['petition_id'])) {
            throw new Exception('Invalid request. missing petition_id');
        }

        /** @var Petition $petition */
        $petition = Petition::find($_POST['petition_id']);

        if (!$petition) {
            throw new Exception('Petition could not be loaded. petition_id: ' . $_POST['petition_id']);
        }

        if ($petition->getUser() != $this->user) {
            throw new Exception('Petition does not belong to currently signed in user. signed-in-user-id: ' . $this->user->getId() . ' petition-user-id: '. $petition->getUser()->getId() . ' petition_id: ' . $petition->getId());
        }

        $petition->setCharity(null);

        self::$em->persist($petition);
        self::$em->flush($petition);

        echo json_encode(['success' => true]);
    }
	
    private function loadGiverhubPetitionById($id) {
        /** @var Petition $petition */
        $petition = Petition::find($id);
        if (!$petition) {
            throw new GiverHub404Exception('Failed to load petition.');
        }
        return $petition;
    }

	public function reasons($slug, $page = 1) {

        try {
            $petition = $this->loadPetition($slug);
            if ($petition->getUrlSlug() != $slug) {
                redirect($petition->getUrlSlug(base_url()). '/reasons/');
            }
            $data['petition'] = $petition;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('petitions/petition-404', 'Petition not found');
            return;
        }
        $this->htmlTitle = $petition->getTitle() . ' - Reasons';
        $this->ogTitle = "I just signed the petition: ".$petition->getTitle();
        $this->ogDesc = $this->ogTitle;
        $this->metaDesc = 'Sign the petition '.$petition->getTitle() . '.';

        $data['main_content'] = 'giverhub-petitions/reasons';
        $data['current_page'] = $page;
        $data['disable_beta_modal'] = true;
        $this->load->view('includes/user/template', $data);
    }

	public function signatures($slug, $page = 1) {

        try {
            $petition = $this->loadPetition($slug);
            if ($petition->getUrlSlug() != $slug) {
                redirect($petition->getUrlSlug(base_url()). '/signatures/');
            }
            $data['petition'] = $petition;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('petitions/petition-404', 'Petition not found');
            return;
        }
        $this->htmlTitle = $petition->getTitle() . ' - Signatures';
        $this->ogTitle = "I just signed the petition: ".$petition->getTitle();
        $this->ogDesc = $this->ogTitle;
        $this->metaDesc = 'Sign the petition '.$petition->getTitle() . '.';

        $data['main_content'] = 'giverhub-petitions/signatures';
        $data['current_page'] = $page;
        $data['disable_beta_modal'] = true;
        $this->load->view('includes/user/template', $data);
    }

	public function news($slug, $page = 1) {

        try {
            $petition = $this->loadPetition($slug);
            if ($petition->getUrlSlug() != $slug) {
                redirect($petition->getUrlSlug(base_url()). '/news/');
            }
            $data['petition'] = $petition;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('petitions/petition-404', 'Petition not found');
            return;
        }
        $this->htmlTitle = $petition->getTitle() . ' - News';
        $this->ogTitle = "I just signed the petition: ".$petition->getTitle();
        $this->ogDesc = $this->ogTitle;
        $this->metaDesc = 'Sign the petition '.$petition->getTitle() . '.';

        $data['main_content'] = 'giverhub-petitions/news';
        $data['current_page'] = $page;
        $data['disable_beta_modal'] = true;
        $this->load->view('includes/user/template', $data);
    }

	public function sign() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        if (!isset($_POST['petitionId'])) {
            throw new Exception('Invalid request. Missing petitionId.');
        }

        $petition = $this->loadGiverhubPetitionById($_POST['petitionId']);

        if (!isset($_POST['hidden'])) {
            throw new Exception('Invalid Request. Missing "hidden" paramater.');
        }
        if (!in_array($_POST['hidden'], array('true', 'false'))) {
            throw new Exception('Invalid Request. Bad Value \''.$_POST['hidden'].'\' for hidden.');
        }

        if (!isset($_POST['reason'])) {
            throw new Exception('Invalid Request. Missing "reason" paramater.');
        }

        $response = $petition->sign($this->user, $_POST['hidden'], $_POST['reason']);

        if ($response === true) {
            echo json_encode([
                'success' => true,
                'sign_block' => $this->load->view('/giverhub-petitions/_sign-block', ['petition' => $petition], true)
            ]);
        } else {
            echo json_encode(array('success' => false, 'msg' => $response));
        }
    }

    public function unsign() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        if (!isset($_POST['petitionId'])) {
            throw new Exception('Invalid request. Missing petitionId.');
        }

        $petition = $this->loadGiverhubPetitionById($_POST['petitionId']);

        $petition->unsign($this->user);

        echo json_encode([
            'success' => true,
            'sign_block' => $this->load->view('/giverhub-petitions/_sign-block', ['petition' => $petition], true)
        ]);
    }

	public function update() {
        if (!$this->user) {
            throw new Exception('User is not signed');
        }

        foreach(['target', 'what', 'why'] as $required) {
            if (!isset($_POST[$required])) {
                throw new Exception($required . ' is missing');
            }
        }
	
		//Store updated values in database
	
		$giverHubPetition = $this->loadGiverhubPetitionById($_POST['petitionId']);
        if (!$giverHubPetition) {
            throw new Exception('could not load petition: ' . $_POST['petitionId']);
        }
        if ($giverHubPetition->getUser() != $this->user) {
            throw new Exception('petition does not belong to user. signed in user: ' . $this->user->getId() . ' petition: ' . $giverHubPetition->getId());
        }
		$giverHubPetition->setTargetText($_POST['target']);
		$giverHubPetition->setWhatText($_POST['what']);
		$giverHubPetition->setWhyText($_POST['why']);
		$giverHubPetition->setUpdatedDate(date('Y-m-d H:i:s'));

        self::$em->persist($giverHubPetition);
        self::$em->flush($giverHubPetition);

        echo json_encode([
                'success' => true,
                'petition' => $giverHubPetition
            ]);
    }

	public function save_goal() {
		if (!$this->user) {
            throw new Exception('User is not signed');
        }

 		if (!isset($_POST['goal'])) {
			throw new Exception('Goal is not mentioned');
		}

		if (!isset($_POST['petitionId'])) {
			throw new Exception('Something went wrong');
		}

		$gPetitionForUpdate = $this->loadGiverhubPetitionById($_POST['petitionId']);

        if (!$gPetitionForUpdate) {
            throw new Exception('Failed to load petition: ' . $_POST['petitionId']);
        }

        if ($gPetitionForUpdate->getUser() != $this->user) {
            throw new Exception('petition does not belong to user. signed in user: ' . $this->user->getId() . ' petition: ' . $gPetitionForUpdate->getId());
        }

		$gPetitionForUpdate->setGoal($_POST['goal']);

		self::$em->persist($gPetitionForUpdate);
        self::$em->flush($gPetitionForUpdate);

        echo json_encode([
                'success' => true,
                'petition' => $gPetitionForUpdate,
                'goal_html' => $this->load->view('/giverhub-petitions/_goal', ['petition' => $gPetitionForUpdate], true),
            ]);
	}

	public function save_deadline() {
		if (!$this->user) {
            throw new Exception('User is not signed');
        }

 		if (!isset($_POST['deadline'])) {
			throw new Exception('Deadline is not mentioned');
		}
		if (!isset($_POST['petitionId'])) {
			throw new Exception('Something went wrong');
		}

		$deadline = date('Y-m-d h:i:s',strtotime($_POST['deadline']));
		$deadline = new \DateTime($deadline);


		$gPetitionForUpdate = $this->loadGiverhubPetitionById($_POST['petitionId']);

        if (!$gPetitionForUpdate) {
            throw new Exception('Failed to load petition: ' . $_POST['petitionId']);
        }

        if ($gPetitionForUpdate->getUser() != $this->user) {
            throw new Exception('petition does not belong to user. signed in user: ' . $this->user->getId() . ' petition: ' . $gPetitionForUpdate->getId());
        }

		$gPetitionForUpdate->setEndAt($deadline);

		self::$em->persist($gPetitionForUpdate);
        self::$em->flush($gPetitionForUpdate);

        echo json_encode([
                'success' => true,
                'petition' => $gPetitionForUpdate,
				'deadline' => $this->load->view('/giverhub-petitions/_deadline', ['petition' => $gPetitionForUpdate], true),
            ]);
	}

	public function save_news() {
		if (!$this->user) {
            throw new Exception('User is not signed');
        }

 		if (!isset($_POST['news'])) {
			throw new Exception('News is not entered');
		}
		if (!isset($_POST['petitionId'])) {
			throw new Exception('Something went wrong');
		}

        /** @var \Entity\Petition $giverHubPetition */
        $giverHubPetition = Petition::findOneBy(array('id' => $_POST['petitionId']));

        if (!$giverHubPetition) {
            throw new Exception('could not load petition: ' . $_POST['petitionId']);
        }

        if ($giverHubPetition->getUser() != $this->user) {
            throw new Exception('petition does not belong to user. user-id: ' . $this->user->getId() . ' petition-id: ' . $giverHubPetition->getId());
        }

		$gPetitionNewsUpdate = new \Entity\PetitionNewsUpdate();

		$gPetitionNewsUpdate->setPetitionId($_POST['petitionId']);
		$gPetitionNewsUpdate->setUser($this->user);
		$gPetitionNewsUpdate->setContent($_POST['news']);
		$gPetitionNewsUpdate->setCreatedOn(date('Y-m-d H:i:s'));

		self::$em->persist($gPetitionNewsUpdate);
        self::$em->flush($gPetitionNewsUpdate);

        echo json_encode([
                'success' => true,
            ]);
	}

	public function remove_media() {
		if (!$this->user) {
            throw new Exception('User is not signed');
        }

		if (!isset($_POST['petitionId'])) {
			throw new Exception('Something went wrong');
		}

        /** @var \Entity\Petition $giverHubPetition */
		$giverHubPetition = Petition::findOneBy(array('id' => $_POST['petitionId']));

        if (!$giverHubPetition) {
            throw new Exception('could not load petition: ' . $_POST['petitionId']);
        }
        if ($giverHubPetition->getUser() != $this->user) {
            throw new Exception('petition does not belong to user. user-id: ' . $this->user->getId() . ' petition-id: ' . $giverHubPetition->getId());
        }

        if ($_POST['mediaType'] == 'video') {
			$giverHubPetition->setVideoId(null);
		} else {
			$getMediaTypeToRemove = $giverHubPetition->getMediaType();
			if ($getMediaTypeToRemove == 'img_url') {
				$giverHubPetition->setImgUrl(null);
			} else {
				$giverHubPetition->setPhoto(null);
			}
		}
		
		$giverHubPetition->setUpdatedDate(date('Y-m-d H:i:s'));

        self::$em->persist($giverHubPetition);
        self::$em->flush($giverHubPetition);

        echo json_encode([
                'success' => true,
                'petition' => $giverHubPetition
            ]);
	}

	public function update_g_petition_media() {
        if (!$this->user) {
            throw new Exception('User is not signed');
        }

		if (!isset($_POST['petition_id'])) {
            throw new Exception('Something went wrong');
		}

        /** @var \Entity\Petition $petition */
		$petition = \Entity\Petition::findOneBy(['id' => $_POST['petition_id']]);

        if ($petition->getUser() != $this->user) {
            throw new Exception('petition does not belong to user. user-id: ' . $this->user->getId() . ' petition-id: ' . $petition->getId());
        }

        if (isset($_POST['photo_id'])) {
            /** @var \Entity\PetitionPhoto $photo */
            $photo = \Entity\PetitionPhoto::find($_POST['photo_id']);
            if (!$photo) {
                throw new Exception('photo cant be loaded. photo_id: ' . $_POST['photo_id']);
            }
            if ($photo->getUser() != $this->user) {
                throw new Exception('Photo not owned by current user. current_user_id: ' . $this->user->getId() . ' photo-user-id: ' . $photo->getUser()->getId() . ' photo_id: ' . $_POST['photo_id']);
            }
            $photo->setTempId(null);
            self::$em->persist($photo);
            self::$em->flush($photo);
            $petition->setPhoto($photo);
            $petition->setImgUrl(null);
            $petition->setVideoId(null);
        } elseif (isset($_POST['video_id'])) {
            $petition->setVideoId($_POST['video_id']);
            $petition->setImgUrl(null);
            $petition->setPhoto(null);
        } elseif (isset($_POST['img_url'])) {
            $petition->setImgUrl($_POST['img_url']);
            $petition->setPhoto(null);
            $petition->setVideoId(null);
        }

        self::$em->persist($petition);
        self::$em->flush($petition);

        echo json_encode([
                'success' => true,
                'petition' => $petition
            ]);
    }

    public function fb_share() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }

        if (!isset($_POST['petition_id'])) {
            throw new Exception('missing petition_id');
        }

        /** @var \Entity\Petition $petition */
        $petition = \Entity\Petition::find($_POST['petition_id']);

        if (!$petition) {
            throw new Exception('could not load petition: ' . $_POST['petition_id']);
        }

        $fb_share = new \Entity\PetitionFacebookShare();
        $fb_share->setUser($this->user);
        $fb_share->setPetition($petition);
        $fb_share->setDate(new \DateTime);
        self::$em->persist($fb_share);
        self::$em->flush($fb_share);
    }
}

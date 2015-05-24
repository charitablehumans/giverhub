<?php

require_once(__DIR__ . '/Base_Controller.php');

use \Entity\ChangeOrgPetition;
use \Entity\ChangeOrgPetitionUrlHistory;

class Petitions extends Base_Controller
{
    
    public function __construct() {
        parent::__construct();

        $this->load->config('general_website_conf');
    }

    /**
     * @param $slug
     *
     * @return ChangeOrgPetition
     * @throws Exception
     */
    private function loadPetition($slug) {
        /** @var ChangeOrgPetition $petition */
        $petition = ChangeOrgPetition::findOneBy(array('giverhub_url_slug' => $slug));
        if (!$petition) {
            /** @var ChangeOrgPetitionUrlHistory $history */
            $history = ChangeOrgPetitionUrlHistory::findOneBy(['giverhub_url_slug' => $slug]);
            if ($history) {
                return $history->getPetition();
            } else {
                throw new GiverHub404Exception('Failed to load petition.');
            }
        }
        return $petition;
    }

    /**
     * @param $id
     *
     * @return ChangeOrgPetition
     * @throws Exception
     */
    private function loadPetitionById($id) {
        /** @var ChangeOrgPetition $petition */
        $petition = ChangeOrgPetition::find($id);
        if (!$petition) {
            throw new GiverHub404Exception('Failed to load petition.');
        }
        return $petition;
    }

    public function feature() {
        if (!$this->user || $this->user->getLevel() < 4) {
            throw new Exception('Request denied');
        }
        if (!isset($_POST) || !isset($_POST['petitionId']) || !isset($_POST['featuredText']) || !isset($_POST['isFeatured'])) {
            throw new Exception('Invalid request. ' . print_r($_POST, true));
        }

        $petition = $this->loadPetitionById($_POST['petitionId']);

        $petition->setIsFeatured($_POST['isFeatured']);
        $petition->setFeaturedText($_POST['featuredText']);

        self::$em->persist($petition);
        self::$em->flush($petition);
        echo json_encode(array('success' => true));
    }

    private function closedBetaSpecialForChangeOrgUsers() {
        if (!preg_match('#facebookexternalhit#', $_SERVER['HTTP_USER_AGENT']) && \CLOSED_BETA && !$this->user && $_GET['from'] != 'change_org') {
            redirect('/');
            die;
        }
    }

    public function index($slug = null) {
        $this->closedBetaSpecialForChangeOrgUsers();

        if (!$slug) {
            redirect('a-z/petitions', 'location', 301);
        }

        try {
            $petition = $this->loadPetition($slug);
            if ($petition->getGiverhubUrlSlug() != $slug) {
                redirect($petition->getGiverhubUrl(base_url()), 'location', 301);
            }
            $data['petition'] = $petition;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('petitions/petition-404', 'Petition not found');
            return;
        }
        $this->htmlTitle = $petition->getTitle() . ' - Overview';
        $this->ogTitle = "I just signed the petition: ".$petition->getTitle()." through GiverHub.";
        $this->ogDesc = $this->ogTitle;
        array_unshift($this->ogImage, $petition->getImageUrlPrependHttp());


        $this->metaDesc = $petition->getTitle() . '. You can read more about and sign this petition on GiverHub.';
        if (strlen($this->metaDesc) > 160) {
            $this->metaDesc = 'Sign the petition ' . $petition->getTitle();
            if (strlen($this->metaDesc) > 160) {
                $this->metaDesc = $petition->getTitle();
            }
        }


        if ($petition->isRemoved()) {
            $data['main_content'] = 'petitions/removed';
        } else {
            $data['main_content'] = 'petitions/index';
        }
        $data['disable_beta_modal'] = true;
        $this->load->view('includes/user/template', $data);
        if (isset($_GET['timingzzz'])) {
            echo '<!-- after petition index ' . ( microtime( true ) - $GLOBALS['super_start'] ) . ' -->';
        }
    }

    public function news($slug, $page = 1) {
        $this->closedBetaSpecialForChangeOrgUsers();

        try {
            $petition = $this->loadPetition($slug);
            if ($petition->getGiverhubUrlSlug() != $slug) {
                redirect($petition->getGiverhubUrl(base_url()). '/news/' . $page, 'location', 301);
            }
            $data['petition'] = $petition;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('petitions/petition-404', 'Petition not found');
            return;
        }
        $this->htmlTitle = $petition->getTitle() . ' - News' . ($page > 1 ? ' - Page ' . $page : '');
        $this->ogTitle = "I just signed the petition: ".$petition->getTitle()." through GiverHub.";
        $this->ogDesc = $this->ogTitle;
        array_unshift($this->ogImage, $petition->getImageUrlPrependHttp());


        $this->metaDesc = 'Get the latest news for the petition '.$petition->getTitle() . '.' . ($page > 1 ? ' Page ' . $page : '');
        if (strlen($this->metaDesc) > 160) {
            $this->metaDesc = 'News for petition '.$petition->getTitle() . '.' . ($page > 1 ? ' Page ' . $page : '');
        }


        if ($petition->isRemoved()) {
            $data['main_content'] = 'petitions/removed';
        } else {
            $data['main_content'] = 'petitions/news';
        }

        $data['current_page'] = $page;
        $data['disable_beta_modal'] = true;
        $this->load->view('includes/user/template', $data);
    }

    public function reasons($slug, $page = 1) {
        $this->closedBetaSpecialForChangeOrgUsers();

        try {
            $petition = $this->loadPetition($slug);
            if ($petition->getGiverhubUrlSlug() != $slug) {
                redirect($petition->getGiverhubUrl(base_url()). '/reasons/' . $page, 'location', 301);
            }
            $data['petition'] = $petition;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('petitions/petition-404', 'Petition not found');
            return;
        }
        $this->htmlTitle = $petition->getTitle() . ' - Reasons' . ($page > 1 ? ' - Page ' . $page : '');
        $this->ogTitle = "I just signed the petition: ".$petition->getTitle()." through GiverHub.";
        $this->ogDesc = $this->ogTitle;
        array_unshift($this->ogImage, $petition->getImageUrlPrependHttp());


        $this->metaDesc = 'View all the reasons people have signed the petition '.$petition->getTitle() . '.' . ($page > 1 ? ' Page ' . $page : '');
        if (strlen($this->metaDesc) > 160) {
            $this->metaDesc = 'Reasons for signing the petition ' . $petition->getTitle() . '.' . ( $page > 1 ? ' Page ' . $page : '' );
        }


        if ($petition->isRemoved()) {
            $data['main_content'] = 'petitions/removed';
        } else {
            $data['main_content'] = 'petitions/reasons';
        }


        $data['current_page'] = $page;
        $data['disable_beta_modal'] = true;
        $this->load->view('includes/user/template', $data);
    }

    public function signatures($slug, $page = 1) {
        $this->closedBetaSpecialForChangeOrgUsers();

        try {
            $petition = $this->loadPetition($slug);
            if ($petition->getGiverhubUrlSlug() != $slug) {
                redirect($petition->getGiverhubUrl(base_url()). '/signatures/' . $page, 'location', 301);
            }
            $data['petition'] = $petition;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('petitions/petition-404', 'Petition not found');
            return;
        }
        $this->htmlTitle = $petition->getTitle() . ' - Signatures' . ($page > 1 ? ' - Page ' . $page : '');
        $this->ogTitle = "I just signed the petition: ".$petition->getTitle()." through GiverHub.";
        $this->ogDesc = $this->ogTitle;
        array_unshift($this->ogImage, $petition->getImageUrlPrependHttp());

        $this->metaDesc = 'See all the names of the people who have signed the petition '.$petition->getTitle() . '.' . ($page > 1 ? ' Page ' . $page : '');
        if (strlen($this->metaDesc) > 160) {
            $this->metaDesc = 'Signatures for the petition '.$petition->getTitle() . '.' . ($page > 1 ? ' Page ' . $page : '');
        }


        if ($petition->isRemoved()) {
            $data['main_content'] = 'petitions/removed';
        } else {
            $data['main_content'] = 'petitions/signatures';
        }

        $data['current_page'] = $page;
        $data['disable_beta_modal'] = true;
        $this->load->view('includes/user/template', $data);
    }

    public function sign() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        if (!$this->user->hasAddress()) {
            throw new Exception('User does not have a default address!');
        }

        if (!isset($_POST['petitionId'])) {
            throw new Exception('Invalid request. Missing petitionId.');
        }

        $petition = $this->loadPetitionById($_POST['petitionId']);

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

        if (is_array($response) && isset($response['success']) && $response['success'] === true) {
            echo json_encode($response);
        } else {
            echo json_encode(array('success' => false, 'msg' => $response));
        }
    }

    public function sign_modal_body() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        if (!isset($_GET['petition_id'])) {
            throw new Exception('Invalid request. Missing petition_id.');
        }

        $petition = $this->loadPetitionById($_GET['petition_id']);

        echo json_encode(['success' => true, 'html' => $this->load->view('/petitions/_sign-block', ['petition' => $petition], true)]);
    }

    public function removal_request() {
        if (!$this->user) {
            throw new Exception('need to be signed in');
        }

        if (!isset($_POST['reason'])) {
            throw new Exception('missing reason for removal request.');
        }

        if (!isset($_POST['id'])) {
            throw new Exception('missing id');
        }

        if (!isset($_POST['type'])) {
            throw new Exception('missing type');
        }

        $allowed_types = ['signature','reason'];
        if (!in_array($_POST['type'], $allowed_types)) {
            throw new Exception('invalid type: ' . $_POST['type'] . ' should be one of ' . join(', ', $allowed_types));
        }

        if (!strlen($_POST['reason'])) {
            throw new Exception('reason is too short.');
        }

        $existing_request = \Entity\PetitionSignatureRemovalRequest::findOneBy([
           'user' => $this->user,
           'type' => $_POST['type'],
           'signatureId' => $_POST['id'],
        ]);

        if ($existing_request) {
            echo json_encode(['success' => false, 'requested_already' => true]);
            return;
        }

        if ($_POST['type'] == 'reason') {
            $entity = \Entity\ChangeOrgPetitionReason::find($_POST['id']);
        } elseif ($_POST['type'] == 'signature') {
            $entity = \Entity\ChangeOrgPetitionSignature::find($_POST['id']);
        } else {
            throw new Exception('invalid type: ' . $_POST['type'] . ' must be reason or signature');
        }

        if (!$entity) {
            throw new Exception('Failed to load the signature.. type: ' . $_POST['type'] . ' id: ' . $_POST['id']);
        }

        $request = new \Entity\PetitionSignatureRemovalRequest;
        $request->setUser($this->user);
        $request->setType($_POST['type']);
        $request->setReason($_POST['reason']);
        $request->setSignatureId($_POST['id']);
        $request->setDateAdded(new \DateTime());

        self::$em->persist($request);
        self::$em->flush($request);

        mail('admin@giverhub.com', '[' . $_POST['type'] . ' removal request]', 'https://'.$_SERVER['SERVER_NAME'].'/admin/petition_signature_removal_request/'.$request->getId());
        echo json_encode(['success' => true]);
    }

    public function fb_share() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }

        if (!isset($_POST['petition_id'])) {
            throw new Exception('missing petition_id');
        }

        /** @var \Entity\ChangeOrgPetition $petition */
        $petition = \Entity\ChangeOrgPetition::find($_POST['petition_id']);

        if (!$petition) {
            throw new Exception('could not load petition: ' . $_POST['petition_id']);
        }

        $fb_share = new \Entity\ChangeOrgPetitionFacebookShare();
        $fb_share->setUser($this->user);
        $fb_share->setPetition($petition);
        $fb_share->setDate(new \DateTime);
        self::$em->persist($fb_share);
        self::$em->flush($fb_share);
    }

}
<?php
require_once(__DIR__ . '/Base_Controller.php');
require_once __DIR__.'/../libraries/htmlpurifier/HTMLPurifier.auto.php';

use \Entity\Charity;

class Mission extends Base_Controller
{
    public function submit() {
        if (!$this->user) {
            throw new Exception('User is not signed in.');
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('charity_id is missing.');
        }
        /** @var Charity $charity */
        $charity = Charity::find($_POST['charity_id']);
        if (!$charity) {
            throw new Exception('could not open charity: ' . $_POST['charity_id']);
        }

        if (!isset($_POST['source'])) {
            throw new Exception('source missing.');
        }

        if (!isset($_POST['mission'])) {
            throw new Exception('mission missing.');
        }

        if (strlen($_POST['source']) < 6) {
            throw new Exception('Source is too short: ' . $_POST['source']);
        }

        if (strlen($_POST['mission']) < 10) {
            throw new Exception('Mission is too short: ' . $_POST['mission']);
        }

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $clean_mission = $purifier->purify($_POST['mission']);

        /** @var \Entity\Mission $mission */
        $mission = \Entity\Mission::findOneBy(['user_id' => $this->user->getId(), 'charity_id' => $charity->getId()]);
        if (!$mission) {
            $mission = new \Entity\Mission();
            $mission->setCharity($charity);
            $mission->setUser($this->user);
            $old_text = null;
            $added_edited = 'added';
        } else {
            $old_text = $mission->getMission();
            $added_edited = 'edited';
        }

        $mission->setMission($clean_mission);
        $mission->setSource($_POST['source']);

        self::$em->persist($mission);
        self::$em->flush($mission);

        $change_history = new \Entity\CharityChangeHistory();
        $change_history->setNewValue($clean_mission);
        $change_history->setUser($this->user);
        $change_history->setCharity($charity);
        $change_history->setDatetime(date('Y-m-d H:i:s'));
        $change_history->setField('mission');
        $change_history->setOldValue($old_text);
        self::$em->persist($change_history);
        self::$em->flush($change_history);

        $missions = $charity->getMissions();

        mail(
            'admin@giverhub.com',
            'nonprofit mission '.$added_edited.' by user',
            print_r([
                    'User ID' => $this->user->getId(),
                    'User Name' => $this->user->getName(),
                    'Nonprofit ID' => $charity->getId(),
                    'Nonprofit Name' => $charity->getName(),
                    'Nonprofit Link' => base_url($charity->getUrl()),
                    'Field' => 'mission',
                    'Old Text' => $old_text,
                    'New Text' => $mission->getMission(),
                ], true)
        );

        if ($added_edited == 'added') {
            $this->user->addGiverHubScore('mission-statement');
        }

        echo json_encode([
                'success' => true,
                'missionsHtml' => $this->load->view('/nonprofits/_missions', ['missions' => $missions], true)
            ]);
    }

    public function vote() {
        if (!$this->user) {
            throw new Exception('User is not signed in.');
        }

        if (!isset($_POST['mission_id'])) {
            throw new Exception('mission_id is missing.');
        }
        /** @var \Entity\Mission $mission */
        $mission = \Entity\Mission::find($_POST['mission_id']);

        if (!$mission) {
            throw new Exception('Could not open mission: ' . $_POST['mission_id']);
        }

        if (!isset($_POST['vote'])) {
            throw new Exception('vote is missing.');
        }

        if (!in_array($_POST['vote'], [-1,0,1])) {
            throw new Exception('vote is invalid: ' . $_POST['vote']);
        }

        $mission_vote = \Entity\MissionVote::findOneBy(['user_id' => $this->user->getId(), 'mission_id' => $mission->getId()]);
        if (!$mission_vote) {
            $mission_vote = new \Entity\MissionVote();
            $mission_vote->setUser($this->user);
            $mission_vote->setMission($mission);
        }

        $mission_vote->setVote($_POST['vote']);

        self::$em->persist($mission_vote);
        self::$em->flush($mission_vote);

        echo json_encode(['success' => true]);
    }

    public function delete() {
        if (!$this->user) {
            throw new Exception('User is not signed in.');
        }

        if (!isset($_POST['mission_id'])) {
            throw new Exception('mission_id is missing.');
        }
        /** @var \Entity\Mission $mission */
        $mission = \Entity\Mission::find($_POST['mission_id']);

        if (!$mission) {
            throw new Exception('Could not open mission: ' . $_POST['mission_id']);
        }

        if ($mission->getUserId() != $this->user->getId()) {
            throw new Exception('User does not own mission when deleting. mission_id: ' . $mission->getId() . ' user_id: ' . $this->user->getId() . ' mission-user-id: ' . $mission->getUserId());
        }

        $charity = $mission->getCharity();

        self::$em->remove($mission);
        self::$em->flush();

        $missions = $charity->getMissions();

        echo json_encode([
                'success' => true,
                'missionsHtml' => $this->load->view('/nonprofits/_missions', ['missions' => $missions], true)
            ]);


    }
}
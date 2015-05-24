<?php
use \Entity\ActivityFeedPost;
use \UserActivityFeed;

require_once(__DIR__ . '/../models/UserActivityFeed.php');
require_once(__DIR__ . '/Base_Controller.php');


class Activity extends Base_Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function more() {

        if (!isset($_GET['offset'])) {
            throw new Exception('offset missing!');
        }

        if (!isset($_GET['user_id'])) {
            throw new Exception('missing user_id');
        }

        /** @var \Entity\User|null $user */
        $user = \Entity\User::find($_GET['user_id']);

        if (!$user) {
            throw new Exception('could not load user_id: '.$_GET['user_id']);
        }

        $context = $this->user && $this->user == $user ? 'my' : 'other';
        $entities = $user->getActivityFeed($_GET['offset'], \Entity\User::ACTIVITIES_PER_PAGE, $context);

        $activities = [];
        foreach($entities as $activity) {
            $activities[] = $this->load->view('/members/_activity', ['context' => $context, 'activity' => $activity], true);
        }

        echo json_encode([
            'success' => true,
            'activities' => $activities,
        ]);
    }
}

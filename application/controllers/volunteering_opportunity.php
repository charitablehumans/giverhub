<?php
require_once(__DIR__ . '/Base_Controller.php');

use \Entity\Charity;
use \Entity\CharityVolunteeringOpportunity;
use \Entity\CharityVolunteeringOpportunitiesReview;

class Volunteering_Opportunity extends Base_Controller {
    private $post_data;

    public function __construct() {
        parent::__construct();
        if (isset($GLOBALS['HTTP_RAW_POST_DATA']) && $GLOBALS['HTTP_RAW_POST_DATA']) {
            $this->post_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
        }
    }

    /**
     * @param $slug
     *
     * @return \Entity\Charity
     * @throws Exception
     */
    public function loadCharity($slug) {
        /** @var \Entity\Charity $charity */
        $charity = Charity::findOneBy(['url_slug' => $slug]);
        if (!$charity) {
            if (is_numeric($slug)) {
                $charity = Charity::find($slug);
            }
            if ($charity) {
                redirect($charity->getUrl(), 'location', 301);
            } else {
                throw new GiverHub404Exception('could not load charity with slug: ' . $slug);
            }
        }
        return $charity;
    }

    public function index($nonprofit_url_slug) {
        try {
            $charity = $this->loadCharity($nonprofit_url_slug);
            $data['charity'] = $charity;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('nonprofits/charity-404', 'Nonprofit not found');
            return;
        }

        $this->htmlTitle = $charity->getName() . ' - Volunteering Events';
        $this->ogTitle = "Find out about Volunteering Events for ".$charity->getName()." through GiverHub.";
        $this->metaDesc = 'Find out about Volunteering Events for nonprofit '.$charity->getName() . ($charity->getTagLine() ? ' ... '.$charity->getTagLine().'.' : '.');
        $data['main_content'] = 'volunteering/index';

        $this->load->view('includes/user/template', $data);
    }

    public function save() {
        if (!$this->user) {
            throw new Exception('user not signed in');
        }

        if (!$this->post_data || !$this->post_data['opportunity']) {
            throw new Exception('missing post_data | opportunity');
        }
        $post_opportunity = $this->post_data['opportunity'];


        if (isset($post_opportunity['id'])) {
            $new = false;
            /** @var CharityVolunteeringOpportunity $opportunity */
            $opportunity = CharityVolunteeringOpportunity::find($post_opportunity['id']);
            if (!$opportunity) {
                throw new Exception('could not load opportunity: ' . $post_opportunity['id']);
            }

            $charity = $opportunity->getCharity();
        } else {
            $new = true;
            $opportunity = new CharityVolunteeringOpportunity();
            $opportunity->setUser($this->user);

            /** @var Charity $charity */
            $charity = Charity::find($post_opportunity['charity_id']);
            if (!$charity) {
                throw new Exception('charity could not be loaded. charity-id: ' . $post_opportunity['charity_id']);
            }
            $opportunity->setCharity($charity);
        }

        if (!$this->user->isCharityAdmin($charity)) {
            throw new Exception('user is not admin for the charity. user-id: ' . $this->user->getId() . ' charity: ' . $charity->getId() . ' opp-id: ' . $opportunity->getId());
        }


        if (isset($post_opportunity['title'])) {
            $opportunity->setTitle($post_opportunity['title']);
        } elseif ($new) {
            throw new Exception('missing title for new opportunity.');
        }

        if (isset($post_opportunity['description'])) {
            $opportunity->setDescription($post_opportunity['description']);
        } elseif ($new) {
            throw new Exception('missing description for new opportunity.');
        }

        if (isset($post_opportunity['location'])) {
            $opportunity->setLocation($post_opportunity['location']);
        } elseif ($new) {
            throw new Exception('missing location for new opportunity.');
        }

        if (isset($post_opportunity['occurs'])) {
            $opportunity->setOccurs($post_opportunity['occurs']);
        } elseif ($new) {
            throw new Exception('missing occurs for new opportunity.');
        }

        if (isset($post_opportunity['start_date'])) {
            $opportunity->setStartDate(new \DateTime($post_opportunity['start_date']));
        } elseif ($new) {
            throw new Exception('missing start_date for new opportunity.');
        }

        if (array_key_exists('end_date', $post_opportunity)) {
            if ($post_opportunity['end_date'] === null) {
                $opportunity->setEndDate($post_opportunity['end_date']);
            } else {
                $opportunity->setEndDate(new \DateTime($post_opportunity['end_date']));
            }
        } elseif ($new) {
            throw new Exception('missing end_date for new opportunity.');
        }

        if (isset($post_opportunity['all_day']) && $post_opportunity['all_day']) {
            $opportunity->setStartTime(null);
            $opportunity->setEndTime(null);
        } else {
            if (isset($post_opportunity['start_time'])) {
                $opportunity->setStartTime(new \DateTime($post_opportunity['start_time']));
            } elseif ($new) {
                throw new Exception('missing start_time for new opportunity.');
            }

            if (isset($post_opportunity['end_time'])) {
                $opportunity->setEndTime(new \DateTime($post_opportunity['end_time']));
            } elseif ($new) {
                throw new Exception('missing end_date for new opportunity.');
            }
        }

        if ($post_opportunity['time_zone'] === null) {
            $opportunity->setTimeZone(null);
        } else {
            $opportunity->setTimeZone($post_opportunity['time_zone']['id']);
        }


        self::$em->persist($opportunity);
        self::$em->flush($opportunity);

        echo json_encode(['success' => true, 'opportunity' => $opportunity, 'new' => $new]);
    }

    public function reviews($nonprofit_url_slug) {
        try {
            $charity = $this->loadCharity($nonprofit_url_slug);
            $data['charity'] = $charity;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('nonprofits/charity-404', 'Nonprofit not found');
            return;
        }

        $this->htmlTitle = $charity->getName() .' - Reviews';
        $this->ogTitle = "Donate to ".$charity->getName()." through GiverHub.";
        $this->metaDesc = 'Reviews of charity '.$charity->getName().'.';
        $this->load->view('includes/user/template', array(
                'charity' => $charity,
                'reviews' => CharityVolunteeringOpportunitiesReview::findBy(['charity' => $charity]),
                'main_content' => 'volunteering/reviews',
            ));
    }

    public function review() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }

        if (!isset($this->post_data['charity-id'])) {
            throw new Exception('missing charity-id');
        }

        if (!isset($this->post_data['review'])) {
            throw new Exception('missing review');
        }

        if (!isset($this->post_data['rating'])) {
            throw new Exception('rating missing');
        }

        /** @var \Entity\Charity $charity */
        $charity = Charity::find($this->post_data['charity-id']);

        if (!$charity) {
            throw new Exception('could not load charity: ' . $this->post_data['charity-id']);
        }

        $review = $charity->getUserReviewedVolunteeringOpportunities($this->user);

        if (!$review) {
            $review = new CharityVolunteeringOpportunitiesReview();
            $review->setCharity($charity);
            $review->setUser($this->user);
        }

        $review->setRating($this->post_data['rating']);
        $review->setReview($this->post_data['review']);

        self::$em->persist($review);
        self::$em->flush($review);

        $reviewsCount = $charity->getVolunteeringOpportunitiesReviewsCount();

        $response = [
            'success' => true,
            'reviewsCount' => $reviewsCount
        ];

        if (isset($this->post_data['request-review-list-html']) && $this->post_data['request-review-list-html']) {
            $response['reviewsListHtml'] = $this->load->view('volunteering/_reviews_list', array('charity' => $charity, 'reviews' => CharityVolunteeringOpportunitiesReview::findBy(['charity' => $charity])), true);
        }
        echo json_encode($response);
    }

    public function delete() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }

        if (!isset($this->post_data['opportunity-id'])) {
            throw new Exception('missing opportunity-id');
        }

        /** @var CharityVolunteeringOpportunity $opportunity */
        $opportunity = CharityVolunteeringOpportunity::find($this->post_data['opportunity-id']);

        if (!$this->user->isCharityAdmin($opportunity->getCharity())) {
            throw new Exception('user is not admin for the charity. user-id: ' . $this->user->getId() . ' charity: ' . $opportunity->getCharity()->getId() . ' opp-id: ' . $opportunity->getId());
        }

        self::$em->remove($opportunity);
        self::$em->flush();

        echo json_encode(['success' => true]);
    }

    public function like() {
        if (!$this->user) {
            throw new Exception('User is not signed in.');
        }

        if (!isset($this->post_data['like'])) {
            throw new Exception('like is missing.');
        }
        if (!isset($this->post_data['opportunity_id'])) {
            throw new Exception('Missing opportunity_id');
        }


        /** @var \Entity\CharityVolunteeringOpportunity $opportunity */
        $opportunity = CharityVolunteeringOpportunity::find($this->post_data['opportunity_id']);

        if (!$opportunity) {
            throw new Exception('Could not load opportunity. opportunity_id: ' . $this->post_data['opportunity_id']);
        }

        $existing_like = \Entity\ActivityFeedPostLike::findOneBy([
                'user' => $this->user,
                'entityName' => 'CharityVolunteeringOpportunity',
                'entityId' => $this->post_data['opportunity_id'],
            ]);

        if ($this->post_data['like'] && !$existing_like) {
            $new_like = new \Entity\ActivityFeedPostLike();
            $new_like->setEntity($opportunity);
            $new_like->setUser($this->user);
            self::$em->persist($new_like);
            self::$em->flush($new_like);
        } elseif (!$this->post_data['like'] && $existing_like) {
            self::$em->remove($existing_like);
            self::$em->flush();
        }

        echo json_encode([
                'success' => true,
                'opportunity' => CharityVolunteeringOpportunity::find($this->post_data['opportunity_id']),
            ]);
    }

    public function add_comment() {
        if (!$this->user) {
            throw new Exception('User is not signed in.');
        }
        if (!isset($this->post_data['text'])) {
            throw new Exception('Missing text.');
        }
        if (!isset($this->post_data['opportunity-id'])) {
            throw new Exception('Missing opportunity-id');
        }

        /** @var CharityVolunteeringOpportunity $opportunity */
        $opportunity = CharityVolunteeringOpportunity::find($this->post_data['opportunity-id']);

        if (!$opportunity) {
            throw new Exception('Could not load opportunity. opportunity_id: ' . $this->post_data['opportunity-id']);
        }

        $comment = new \Entity\ActivityFeedPostComment();
        $comment->setUser($this->user);
        $comment->setEntity($opportunity);
        $comment->setText(trim($this->post_data['text']));
        self::$em->persist($comment);
        self::$em->flush($comment);

        echo json_encode([
                'success' => true,
                'opportunity' => CharityVolunteeringOpportunity::find($this->post_data['opportunity-id']),
            ]);
    }

    public function embed($id) {
        $charity = \Entity\Charity::find($id);

        $this->load->view('/volunteering/embed', ['charity' => $charity]);
    }
}

<?php
require_once(__DIR__ . '/Base_Controller.php');

use \Entity\Charity;

class Nonprofits extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');

        $this->load->config('general_website_conf');
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
                /** @var \Entity\CharityUrlHistory $history */
                $history = \Entity\CharityUrlHistory::findOneBy(['url_slug' => $slug]);
                if ($history) {
                    redirect($history->getCharity()->getUrl(), 'location', 301);
                } else {
                    throw new GiverHub404Exception('could not load charity with slug: ' . $slug);
                }
            }
        }
        return $charity;
    }

    public function index($slug = null) {
        if (!$slug) {
            redirect('a-z/nonprofits', 'location', 301);
        }
        try {
            $charity = $this->loadCharity($slug);
            $data['charity'] = $charity;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('nonprofits/charity-404', 'Nonprofit not found');
            return;
        }
        $this->htmlTitle = $charity->getName() . ' - Overview';
        $this->ogTitle = "Donate to ".$charity->getName()." through GiverHub.";
        $this->metaDesc = 'Make a donation to the nonprofit '.$charity->getName() . ($charity->getTagLine() ? ' ... '.$charity->getTagLine().'.' : '.');
        if (strlen($this->metaDesc) > 160) {
            $this->metaDesc = 'Donate to ' . $charity->getName() . ($charity->getTagLine() ? ' ... '.$charity->getTagLine().'.' : '.');
            if (strlen($this->metaDesc) > 160) {
                $this->metaDesc = 'Make a donation to ' . $charity->getName();
            }
        }
        $data['main_content'] = 'nonprofits/index';

        $this->load->view('includes/user/template', $data);
    }

    public function messages($slug) {
        try {
            $charity = $this->loadCharity($slug);
            $data['charity'] = $charity;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('nonprofits/charity-404', 'Nonprofit not found');
            return;
        }

        if (!$this->user) {
            redirect('/?redirect='.$_SERVER['REQUEST_URI']);
            return;
        }

        if (!$this->user->isCharityAdmin($charity)) {
            echo 'Access denied!';
            return;
        }

        $this->htmlTitle = $charity->getName() .' - Messahes';
        $this->ogTitle = "Donate to ".$charity->getName()." through GiverHub.";

        $this->load->view('includes/user/template', array(
            'charity' => $charity,
            'main_content' => 'nonprofits/messages',
        ));
    }

    public function followers($slug) {
        try {
            $charity = $this->loadCharity($slug);
            $data['charity'] = $charity;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('nonprofits/charity-404', 'Nonprofit not found');
            return;
        }

        $this->htmlTitle = $charity->getName() .' - Followers';
        $this->ogTitle = "Donate to ".$charity->getName()." through GiverHub.";
        $this->metaDesc = 'Followers of charity '.$charity->getName().'. Join GiverHub today and start following and donating to your favorite charities.';

        $this->load->view('includes/user/template', array(
                                                         'charity' => $charity,
                                                         'followers' => $charity->getFollowers(),
                                                         'main_content' => 'nonprofits/followers',
                                                    ));
    }

    public function reviews($slug) {
        try {
            $charity = $this->loadCharity($slug);
            $data['charity'] = $charity;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('nonprofits/charity-404', 'Nonprofit not found');
            return;
        }

        $this->htmlTitle = $charity->getName() .' - Reviews';
        $this->ogTitle = "Donate to ".$charity->getName()." through GiverHub.";
        $this->metaDesc = 'View all the reviews of the nonprofit '.$charity->getName().'.';
        $this->load->view('includes/user/template', array(
                                                         'charity' => $charity,
                                                         'main_content' => 'nonprofits/reviews',
                                                    ));
    }

    public function missions($slug) {
        try {
            $charity = $this->loadCharity($slug);
            $data['charity'] = $charity;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('nonprofits/charity-404', 'Nonprofit not found');
            return;
        }

        if ($this->user) {
            $my_mission = \Entity\Mission::findOneBy(['user_id' => $this->user->getId(), 'charity_id' => $charity->getId()]);
        }
        $this->htmlTitle = $charity->getName() .' - Mission Statements';
        $this->ogTitle = "Donate to ".$charity->getName()." through GiverHub.";
        $this->metaDesc = 'Reviews of charity '.$charity->getName().'.';
        $this->load->view('includes/user/template', array(
                'charity' => $charity,
                'my_mission' => isset($my_mission) ? $my_mission : null,
                'main_content' => 'nonprofits/missions',
            ));
    }

    public function manage_keywords($slug) {
        if (!$this->user || $this->user->getLevel() < 4) {
            redirect(base_url());
            die();
        }

        try {
            $charity = $this->loadCharity($slug);
            $data['charity'] = $charity;
        } catch(GiverHub404Exception $e) {
            $this->giverhub_404('nonprofits/charity-404', 'Nonprofit not found');
            return;
        }

        $this->htmlTitle = 'Manage Keywords';
        $this->ogTitle = "Donate to ".$charity->getName()." through GiverHub.";
        $data['main_content'] = 'nonprofits/manage_keywords';

        $this->load->view('includes/user/template', $data);
    }

    public function az($letter = null, $page = 1) {
        $data['letter'] = $letter;

        if ($letter === null) {
            $data['letters'] = array_merge(range(0,9), range('A', 'Z'));
            $data['main_content'] = 'nonprofits/az-index';
        } else {
            $data['main_content'] = 'nonprofits/az-letter';

            $limit = 80;
            $offset = ($page - 1) * $limit;
            $res = Charity::findSphinxQuery('^'.$letter.'*', $offset, $limit);
            $data['charities'] = $res['charities'];
            $data['total_found'] = $res['count'];
            $data['page'] = $page;
            $data['pages'] = ceil($data['total_found'] / $limit);
        }

        $this->load->view('includes/user/template', $data);
    }
}
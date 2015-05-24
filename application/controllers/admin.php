<?php

use \Entity\User;
use \Entity\CharityCategory;
use \Entity\FAQ;
use \Entity\CharityCause;
use \Entity\CharityCauseKeyword;
use \Entity\TaskQueue;

require_once(__DIR__ . '/Base_Controller.php');

class Admin extends \Base_Controller
{
    const users_per_page = 5;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');

        $this->load->config('general_website_conf');

        if (!$this->user || $this->user->getLevel() < 3) {
            redirect('?redirect='.$_SERVER['REQUEST_URI']);
            die();
        }
    }

    public function index()
    {
        $this->htmlTitle      = 'Members';
        $data['main_content'] = 'index';
        $this->load->view('includes/admin/admin_template', $data);
    }

    public function view_users()
    {
        $config             = array();
        $config["base_url"] = base_url() . "admin/view_users";

        $config["total_rows"]  = User::count();
        $config["per_page"]    = self::users_per_page;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page            = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['users'] = User::findBy(array(), null, $config['per_page'], $page);
        $data["links"]   = $this->pagination->create_links();

        $this->htmlTitle      = 'Users';
        $data['main_content'] = 'view_users';

        $this->load->view('includes/admin/admin_template', $data);
    }

    public function delete_user() {
        /** @var \Entity\User $user */
        $user = \Entity\User::find($_POST['user_id']);

        if ($user->isAdmin() && $user->getEmail() != 'levineam@gmail.com') {
            echo json_encode(['success' => false, 'error_msg' => 'Cannot delete admins!']);
            return;
        }

        try {
            self::$em->remove($user);
            self::$em->flush();
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'error_msg' => $e->getMessage()]);
            return;
        }

        echo json_encode(['success' => true]);
    }

    function search_user() {
        $search = trim($this->input->post('search'));
        if ($search) {
            $users = User::findBy(['email' => $search]);
        } else {
            $users = User::findBy(array(), null, self::users_per_page);
        }
        echo json_encode([
                'success' => true,
                'html' => $this->load->view('admin/_users', ['users' => $users], true)
            ]);
    }

    function change_status_of_users() {
        $user_ids = $this->input->post('user_ids');
        $action = $this->input->post('action');

        /** @var User[] $users */
        $users = [];
        foreach($user_ids as $user_id) {
            $user = User::find($user_id);
            $users[] = $user;
        }

        foreach($users as $user) {
            switch($action) {
                case 'delete_profile_picture':
                    $user->setImage(null);
                    break;
                case 'admin':
                case 'confirmed':
                    $user->setCapabilities($action);
                    break;
            }
            self::$em->persist($user);
            self::$em->flush($user);
        }

        echo json_encode(['success' => true, 'html' => $this->load->view('admin/_users', ['users' => $users], true)]);
    }

    public function auto_follow() {
        if (!isset($_POST['user_id'])) {
            throw new Exception('Invalid request missing user_id.');
        }
        if (!isset($_POST['auto_follow'])) {
            throw new Exception('invalid request missing auto_follow parameter.');
        }
        $user = User::find($_POST['user_id']);
        if (!$user) {
            throw new Exception('could not find user. user_id: ' . $_POST['user_id']);
        }
        if (!in_array($_POST['auto_follow'], [0, 1])) {
            throw new Exception('invalid request, auto_follow is invalid. auto_follow: ' . $_POST['auto_follow']);
        }

        $user->setAutoFollow($_POST['auto_follow']);

        self::$em->persist($user);
        self::$em->flush($user);

        echo json_encode(['success' => true]);
    }

    public function user_profile($user_id) {
        $user                  = User::find($user_id);
        $data['user']          = $user;

        $this->htmlTitle       = 'Users';
        $data['main_content']  = 'user_profile';
        $this->load->view('includes/admin/admin_template', $data);
    }

    public function faqs() {
        $this->htmlTitle       = 'FAQs';
        $data['main_content']  = 'faqs';

        $data['faqs']          = FAQ::findAll();
        $this->load->view('includes/admin/admin_template', $data);
    }

    function add_faq() {
        $data = [];
        if ($_POST) {
            $this->form_validation->set_rules('fq_ques', 'FAQ Question', 'required');
            $this->form_validation->set_rules('fq_ans', 'FAQ Answer', 'required');

            if ($this->form_validation->run() == false) {
                $data['message'] = 'You did not fill in everything correctly..';
            } else {

                $faq = new FAQ;
                $faq->setFqQues($this->input->post('fq_ques'));
                $faq->setFqAns($this->input->post('fq_ans'));
                $faq->setCreateDateDt(new \DateTime());
                $faq->setFqFilter(implode(',', $this->input->post('fq_filter')));
                $faq->setFqOrder($this->input->post('fq_order'));
                \Base_Controller::$em->persist($faq);
                \Base_Controller::$em->flush($faq);

                $this->session->set_flashdata('message', 'FAQ Added successfully.');
                redirect('/admin/faqs');
            }
        }

        $this->htmlTitle       = 'FAQs';
        $data['main_content']  = 'add_faq';
        $data['faq_count']     = FAQ::count()+1;

        $this->load->view('includes/admin/admin_template', $data);
    }

    function edit_faq($faq_id) {
        /** @var FAQ $faq */
        $faq = FAQ::find($faq_id);

        if ($_POST) {
            $this->form_validation->set_rules('fq_ques', 'FAQ Question', 'required');
            $this->form_validation->set_rules('fq_ans', 'FAQ Answere', 'required');

            if ($this->form_validation->run() == false) {
                $data['message'] = 'You did not fill in everything correctly..';
            } else {
                $faq->setFqQues($this->input->post('fq_ques'));
                $faq->setFqAns($this->input->post('fq_ans'));
                $faq->setFqFilter(implode(',', $this->input->post('fq_filter')));
                $faq->setFqOrder($this->input->post('fq_order'));
                \Base_Controller::$em->persist($faq);
                \Base_Controller::$em->flush($faq);

                $this->session->set_flashdata('message', 'FAQ Updated successfully.');
                $data['success_message'] = 'FAQ Updated successfully.';
            }
        }

        $this->htmlTitle       = 'FAQs';
        $data['main_content']  = 'edit_faq';

        $data['faq']           = $faq;
        $data['faq_count']     = FAQ::count();
        $this->load->view('includes/admin/admin_template', $data);

    }

    function delete_faq() {
        $faq_id     = $this->input->post('faq_id');
        $faq        = FAQ::find($faq_id);

        \Base_Controller::$em->remove($faq);
        \Base_Controller::$em->flush();

        echo json_encode(['success' => true]);
    }

    public function closed_beta($page = 1) {
        define('SIGNUPS_PER_PAGE', 20);

        $this->htmlTitle  = 'Closed Beta';
        $data['main_content'] = 'closed_beta';
        $data['signups'] = \Entity\ClosedBetaSignup::findBy([], null, SIGNUPS_PER_PAGE, ($page-1) * SIGNUPS_PER_PAGE);
        $data['pages'] = ceil(\Entity\ClosedBetaSignup::count() / SIGNUPS_PER_PAGE);
        $data['page'] = $page;
        $this->load->view('includes/admin/admin_template', $data);
    }

    public function closed_beta_approve() {
        $signup = \Entity\ClosedBetaSignup::find($_POST['signupId']);
        $signup->approve();

        echo json_encode(array('success' => true));
    }

    public function add_and_approve_multiple_emails() {
        if (!$_POST['emails']) {
            $this->session->set_flashdata('message', 'Need to enter emails.');
            redirect('/admin/closed_beta');
        }
        $emails = explode(',', $_POST['emails']);

        $this->load->helper('email');

        $messages = array();
        foreach($emails as $email) {
            $email = trim($email);
            if (!valid_email($email)) {
                $messages[] = $email . ' is invalid.';
                continue;
            }

            $signup = \Entity\ClosedBetaSignup::findOneBy(array('email' => $email));
            if (!$signup) {
                $signup = new \Entity\ClosedBetaSignup();
                $signup->setEmail($email);
                $signup->setSignupDateDt(new \DateTime());
                $signup->setApproved(0);
                \Base_Controller::$em->persist($signup);
                \Base_Controller::$em->flush($signup);
            }
            if (!$signup->approve()) {
                $messages[] = $email . ' was approved but sending email to it failed!';
            } else {
                $messages[] = $email . ' was approved and invited.';
            }
        }
        if ($messages) {
            $this->session->set_flashdata('message', join('<br>', $messages));
        }
        redirect('/admin/closed_beta');
    }

    public function profanities() {
        $this->htmlTitle  = 'Profanity';
        $data['main_content'] = 'profanity';
        $data['profanities'] = \Entity\Profanity::findAll();

        $qb = \Base_Controller::$em->createQueryBuilder('cop');
        $qb->select('cop');
        $qb->from('\Entity\ChangeOrgPetition', 'cop');
        $qb->where($qb->expr()->isNotNull('cop.profanity_filter'));
        $filteredPetitions = $qb->getQuery()->execute();

        $data['filteredPetitions'] = $filteredPetitions;
        $this->load->view('includes/admin/admin_template', $data);
    }

    public function delete_profanity() {
        if (!isset($_POST['profanityId'])) {
            throw new Exception('invalid request.');
        }
        $profanity = \Entity\Profanity::find($_POST['profanityId']);
        \Base_Controller::$em->remove($profanity);
        \Base_Controller::$em->flush();
        echo json_encode(array('success' => true));
    }

    public function add_profanities() {
        if (!$_POST['profanities']) {
            $this->session->set_flashdata('message', 'Need to enter profanities.');
            redirect('/admin/profanities');
        }
        $profanities = explode(',', $_POST['profanities']);
        $messages = array();
        foreach($profanities as $profanity) {
            $prof = trim($profanity);
            /** @var \Entity\Profanity $profanity */
            $profanity = \Entity\Profanity::findOneBy(array('profanity' => $prof));
            if ($profanity) {
                $messages[] = $prof . ' already exists. id: ' . $profanity->getId();
            } else {
                $profanity = new \Entity\Profanity();
                $profanity->setProfanity($prof);
                \Base_Controller::$em->persist($profanity);
                \Base_Controller::$em->flush($profanity);
                $messages[] = 'Added ' . $prof;
            }
        }
        $msg = $messages ? join('<br>',$messages) : 'You need to enter profanities..';
        $this->session->set_flashdata('message', $msg);

        redirect('/admin/profanities');
    }

    public function remove_petition_from_blacklist() {
        if (!isset($_POST['petitionId'])) {
            throw new Exception('invalid request.');
        }
        /** @var \Entity\ChangeOrgPetition $petition */
        $petition = \Entity\ChangeOrgPetition::find($_POST['petitionId']);
        if (!$petition) {
            throw new Exception('could not open petition with id: ' . $_POST['petitionId']);
        }
        $petition->setProfanityFilter(null);
        \Base_Controller::$em->persist($petition);
        \Base_Controller::$em->flush($petition);
        echo json_encode(array('success' => true));
    }

    public function run_petition_filter() {
        /** @var \Entity\Profanity[] $profanities */
        $profanities = \Entity\Profanity::findAll();
        $regex = [];
        foreach($profanities as $profanity) {
            $regex[] = trim($profanity->getProfanity());
        }
        $regex = join('|', $regex);
        $regex = '#\b('.$regex.')\b#i';

        /** @var \Entity\ChangeOrgPetition[] $petitions */
        $petitions = \Entity\ChangeOrgPetition::findAll();

        foreach($petitions as $petition) {
            $problems = [];

            $places = [
                'title' => $petition->getTitle(),
                'overview' => $petition->getOverview()
            ];
            foreach($places as $name => $value) {
                if (preg_match_all($regex, $petition->getTitle(), $matches)) {
                    foreach($matches[0] as $match) {
                        $problems[] = $match . ' in ' . $name . '.';
                    }
                }
            }

            if ($problems) {
                $petition->setProfanityFilter(join("\n", $problems));
            } else {
                $petition->setProfanityFilter(null);
            }
            \Base_Controller::$em->persist($petition);
            \Base_Controller::$em->flush($petition);
        }

        redirect('admin/profanities');
    }

    public function last_online() {
        $q = \Base_Controller::$em->createQuery('SELECT u FROM \Entity\User u WHERE u.last_online IS NOT NULL ORDER BY u.last_online DESC');
        $data['rows'] = $q->getResult();

        $this->htmlTitle  = 'Last Online';
        $data['main_content'] = 'last_online';

        $this->load->view('includes/admin/admin_template', $data);
    }

    public function causes_keywords() {
        $this->htmlTitle  = 'Causes & Keywords';
        $data['main_content'] = 'causes_keywords';

        $data['categories'] = CharityCategory::findAll();

        $this->load->view('includes/admin/admin_template', $data);
    }

    public function add_cause_keyword() {
        if (!isset($_POST['cause_id'])) {
            throw new Exception('Invalid request, missing cause_id');
        }
        if (!isset($_POST['keyword'])) {
            throw new Exception('Invalid request, missing keyword');
        }
        if (!$_POST['keyword']) {
            throw new Exception('Invalid request, keyword is too short. keyword: ' . $_POST['keyword']);
        }
        /** @var CharityCause $cause */
        $cause = CharityCause::find($_POST['cause_id']);
        if (!$cause) {
            throw new Exception('Could not load cause. cause_id: ' . $_POST['cause_id']);
        }

        $tmp_keywords = explode(',',$_POST['keyword']);
        $keywords = [];
        foreach($tmp_keywords as $keyword) {
            $keywords[] = trim($keyword);
        }

        foreach($keywords as $keyword) {
            $existing = CharityCauseKeyword::findBy(['charity_cause_id' => $cause->getId(), 'keyword' => $_POST['keyword']]);
            if (!$existing) {
                $key = new CharityCauseKeyword;
                $key->setKeyword($keyword);
                $key->setCharityCause($cause);
                self::$em->persist($key);
                self::$em->flush($key);
            }
        }

        echo json_encode([
                'success' => true,
                'html' => $this->load->view('admin/_cause_tbody', ['cause' =>  $cause], true)
            ]);
    }

    public function delete_cause_keyword() {
        if (!isset($_POST['keyword_id'])) {
            throw new Exception('Invalid request, missing keyword_id');
        }

        /** @var CharityCauseKeyword $keyword */
        $keyword = CharityCauseKeyword::find($_POST['keyword_id']);
        if (!$keyword) {
            throw new Exception('Could not load keyword. keyword_id: ' . $_POST['keyword_id']);
        }

        $cause = $keyword->getCharityCause();

        self::$em->remove($keyword);
        self::$em->flush();

        echo json_encode([
                'success' => true,
                'html' => $this->load->view('admin/_cause_tbody', ['cause' =>  $cause], true)
            ]);
    }

    public function set_cause_keyword_strength() {
        if (!isset($_POST['keyword_id'])) {
            throw new Exception('Invalid request, missing keyword_id');
        }

        /** @var CharityCauseKeyword $keyword */
        $keyword = CharityCauseKeyword::find($_POST['keyword_id']);
        if (!$keyword) {
            throw new Exception('Could not load keyword. keyword_id: ' . $_POST['keyword_id']);
        }

        if (!isset($_POST['strength'])) {
            throw new Exception('Invalid request, missing strength');
        }

        if (!in_array($_POST['strength'], ['1', '0'])) {
            throw new Exception('Invalid request, strength is bad, strength: ' . $_POST['strength']);
        }

        $keyword->setStrength($_POST['strength']);
        self::$em->persist($keyword);
        self::$em->flush($keyword);
        echo json_encode(['success' => true]);
    }

    public function task_queue() {
        $this->htmlTitle  = 'Task Queue';
        $data['main_content'] = 'task_queue';

        $this->db->select('id, command, created_at, started_at, stopped_at, status, pid');
        $this->db->order_by("created_at", "desc");
        $query = $this->db->get('task_queue');

        $data['tasks'] = $query->result_array();

        $this->load->view('includes/admin/admin_template', $data);
    }

    public function task_output($id) {
        $this->htmlTitle  = 'Task Output';
        $data['main_content'] = 'task_output';

        $this->db->select('output');
        $query = $this->db->get_where('task_queue', array('id' => $id));

        $data['output'] = $query->result_array()[0]['output'];

        $this->load->view('includes/admin/admin_template', $data);
    }

    public function enqueue_map_keywords_task() {
        $task = new TaskQueue();
        $task->setCommand('task map_keywords_to_causes');
        self::$em->persist($task);
        self::$em->flush($task);
        $this->session->set_flashdata('message', 'Created task with ID: ' . $task->getId() . '!!');
        redirect('/admin/task_queue');
    }

    public function kill_task() {

        $pids = preg_split('/\s+/', `ps -o pid --no-heading --ppid $ppid`);
        foreach($pids as $pid) {
            if(is_numeric($pid)) {
                echo "Killing $pid\n";
                posix_kill($pid, 9); //9 is the SIGKILL signal
            }
        }

    }

    public function charity_admins() {
        $this->htmlTitle      = 'Charity Admins';

        $data['main_content'] = 'charity_admins';

        $data['admins'] = \Entity\CharityAdmin::findAll();
        $this->load->view('includes/admin/admin_template', $data);
    }

    public function charity_admin_requests() {
        $this->htmlTitle      = 'Charity Admin Requests';

        $data['main_content'] = 'charity_admin_requests';

        $data['requests'] = \Entity\CharityAdminRequest::findAll();
        $this->load->view('includes/admin/admin_template', $data);
    }

    public function charity_admin_request_approve() {
        if (!isset($_POST['request_id'])) {
            throw new Exception('invalid request. request_id is missing.');
        }
        /** @var \Entity\CharityAdminRequest $request */
        $request = \Entity\CharityAdminRequest::find($_POST['request_id']);
        if (!$request) {
            throw new Exception('Could not load request. id: ' . $_POST['request_id']);
        }

        $request->approve();

        echo json_encode([
                'success' => true,
                'html' =>   $this->load->view(
                                 '/admin/_charity_admin_requests',
                                 [
                                     'requests' => \Entity\CharityAdminRequest::findAll()
                                 ],
                                 true
                            )
        ]);
    }

    public function charity_admin_data() {
        $this->htmlTitle      = 'Charity Admin Data';

        $data['main_content'] = 'charity_admin_data';

        $data['datas'] = \Entity\CharityAdminData::findAll();
        $this->load->view('includes/admin/admin_template', $data);
    }

    public function users_graph() {
        $this->htmlTitle = 'Users Graph';

        $data['main_content'] = 'users_graph';

        $data['growth_rate'] =  User::getGrowthRateData();



        $this->load->view('includes/admin/admin_template', $data);
    }

    public function users_graph_img() {
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph.php');
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph_line.php');
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph_date.php');
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph_utils.inc.php');

        $data = \Entity\User::getJoinedGraphData();

        // Setup the graph
        $graph = new Graph(1000,250);
        $graph->SetScale("datlin");

        $theme_class=new UniversalTheme;

        $graph->SetTheme($theme_class);
        $graph->img->SetAntiAliasing(false);
        $graph->title->Set('Users Graph');
        $graph->SetBox(false);

        $graph->img->SetAntiAliasing();

        $graph->yaxis->HideZeroLabel();
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
        $graph->xaxis->SetTickLabels(array_keys($data));
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->xaxis->SetLabelAngle(50);

        // Create the first line
        $p1 = new LinePlot(array_values($data));
        $graph->Add($p1);
        $p1->SetColor("#6495ED");
        $p1->SetLegend('Users');


        $graph->legend->SetFrameWeight(1);

        // Output line
        $graph->Stroke();
    }

    public function growth_rate_img() {
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph.php');
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph_line.php');
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph_date.php');
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph_utils.inc.php');

        $data = \Entity\User::getGrowthRateGraphData();

        // Setup the graph
        $graph = new Graph(1000,280);
        $graph->SetScale("datlin");

        $theme_class=new UniversalTheme;

        $graph->SetTheme($theme_class);
        $graph->img->SetAntiAliasing(false);
        $graph->title->Set('Growth Rate Graph');
        $graph->SetBox(false);

        $graph->img->SetAntiAliasing();

        $graph->yaxis->HideZeroLabel();
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
        $graph->xaxis->SetTickLabels(array_keys($data));
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->xaxis->SetLabelAngle(50);

        // Create the first line
        $p1 = new LinePlot(array_values($data));
        $graph->Add($p1);
        $p1->SetColor("#6495ED");
        $p1->SetLegend('Growth Rate %');
        $p1->value->SetFormat('%d');
        $p1->value->Show();


        $graph->legend->SetFrameWeight(1);

        // Output line
        $graph->Stroke();
    }

    public function monthly_growth_rate_img() {
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph.php');
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph_line.php');
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph_date.php');
        require_once (__DIR__.'/../libraries/jpgraph/src/jpgraph_utils.inc.php');

        $data = \Entity\User::getMonthlyGrowthRateData();

        // Setup the graph
        $graph = new Graph(1000,450);
        $graph->SetScale("datlin");

        $theme_class=new UniversalTheme;

        $graph->SetTheme($theme_class);
        $graph->img->SetAntiAliasing(false);
        $graph->title->Set('Growth Rate Graph');
        $graph->SetBox(false);

        $graph->img->SetAntiAliasing();

        $graph->yaxis->HideZeroLabel();
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
        $graph->xaxis->SetTickLabels(array_keys($data));
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->xaxis->SetLabelAngle(30);

        // Create the first line
        $p1 = new LinePlot(array_values($data));
        $graph->Add($p1);
        $p1->SetColor("#6495ED");
        $p1->SetLegend('Growth Rate %');
        //$p1->value->SetFormat();
        $p1->value->Show();


        $graph->legend->SetFrameWeight(1);

        // Output line
        $graph->Stroke();
    }

    public function petition_signature_removal_request($id) {
        $this->htmlTitle = 'Petition Signature Removal Request';

        $data['main_content'] = 'petition_signature_removal_request';

        $request = \Entity\PetitionSignatureRemovalRequest::find($id);

        if (!$request) {
            throw new Exception('request missing? id: ' . $id);
        }

        $data['request'] = $request;

        $this->load->view('includes/admin/admin_template', $data);
    }

    public function grant_petition_signature_removal_request() {
        if (!isset($_POST['id'])) {
            throw new Exception('missing id!');
        }

        /** @var \Entity\PetitionSignatureRemovalRequest $request */
        $request = \Entity\PetitionSignatureRemovalRequest::find($_POST['id']);

        if (!$request) {
            throw new Exception('request missing: ' . $_POST['id']);
        }

        if ($request->getRemovedByUser()) {
            throw new Exception('request already granted! id: ' . $_POST['id']);
        }

        $entity = $request->getEntity();
        if (!$entity) {
            throw new Exception('could not load entity.. id: ' . $_POST['id']);
        }

        self::$em->remove($entity);
        self::$em->flush($entity);

        $request->setRemovedByUser($this->user);
        $request->setDateRemoved(new \DateTime());

        self::$em->persist($request);
        self::$em->flush($request);

        $user = $request->getUser();

        $body = "Hello " . $user->getName() . "<br><br>" .
                "We have removed your signature from GiverHub.com<br><br>" .
                "Kind Regards / GiverHub, Inc.";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: GiverHub <admin@giverhub.com>" . "\r\n";

        mail(
            $user->getEmail(),
            'Your signature removal request',
            $body,
            $headers);
        echo json_encode(['success' => true]);
    }

    public function petition_signature_removal_requests() {
        $this->htmlTitle = 'Petition Signature Removal Requests';

        $data['main_content'] = 'petition_signature_removal_requests';

        $requests = \Entity\PetitionSignatureRemovalRequest::findAll();



        $data['requests'] = $requests;

        $this->load->view('includes/admin/admin_template', $data);
    }

    public function signed_up_from() {
        $this->htmlTitle = 'Signed up from url';

        $data['main_content'] = 'signed_up_from';

        $data['urls'] = $this->db->query('select url_before_signup as url from users where url_before_signup <> \'\' and url_before_signup is not null')->result_array();
        $this->load->view('includes/admin/admin_template', $data);
    }

    public function givercoin() {
        $this->htmlTitle = 'GiverCoin';

        $data['main_content'] = 'givercoin';


        if (isset($_POST['amount']) && isset($_POST['description'])) {
            $values = [];
            foreach($_POST['amount'] as $id => $amount) {
                $values[$id]['amount'] = round($amount,2);
            }
            foreach($_POST['description'] as $id => $description) {
                $values[$id]['description'] = $description;
            }

            foreach($values as $id => $value) {
                /** @var \Entity\GiverCoin $givercoin */
                $givercoin = \Entity\GiverCoin::find($id);
                $givercoin->setDescription($value['description']);
                $givercoin->setAmount($value['amount']);
                self::$em->persist($givercoin);
                self::$em->flush($givercoin);
            }
            redirect('/admin/givercoin');
            return;
        }

        $data['givercoin'] = \Entity\GiverCoin::findAll();

        $this->load->view('includes/admin/admin_template', $data);
    }

    public function stat_details($stat_name = null) {
        if ($stat_name === null) {
            $stats = [];
        } else {
            $stats = \Entity\Stat::findBy(['name' => $stat_name], ['id' => 'DESC']);
        }

        $q = $this->db->query('select distinct(name) from stat order by name asc');
        $rows = $q->result_array();

        $stat_names = [];
        foreach($rows as $row) {
            $stat_names[] = $row['name'];
        }


        $data['main_content'] = 'stat_details';
        $data['stats'] = $stats;
        $data['stat_name'] = $stat_name;
        $data['stat_names'] = $stat_names;

        $this->load->view('includes/admin/admin_template', $data);
    }

    public function add_nonprofit() {
        $data['main_content'] = 'add_nonprofit';

        $this->load->view('includes/admin/admin_template', $data);
    }

    public function add_nonprofit_save() {
        if (!isset($_POST['name'])) {
            throw new Exception('missing name');
        }

        if (!$_POST['name']) {
            throw new Exception('name empty');
        }

        if (!isset($_POST['stateId'])) {
            throw new Exception('missing stateId');
        }

        if (!$_POST['stateId']) {
            throw new Exception('stateId empty');
        }

        /** @var \Entity\CharityState $state */
        $state = \Entity\CharityState::find($_POST['stateId']);
        if (!$state) {
            throw new Exception('could not find state: ' . $_POST['stateId']);
        }

        $name = trim($_POST['name']);

        $orig_slug = $slug = \Common::slug($name);
        $x = 1;

        while(1) {
            $x++;
            $exists = \Entity\Charity::findOneBy(['url_slug' => $slug]);
            $history = \Entity\CharityUrlHistory::findOneBy(['url_slug' => $slug]);

            if ($exists || $history) {
                $slug = $orig_slug . $x;
            } else {
                break;
            }
        }

        $nonprofit = new \Entity\Charity;
        $nonprofit->setName($name);
        $nonprofit->setUrlSlug($slug);
        $nonprofit->setStateId($state->getId());
        $nonprofit->setCreatedAt(date('Y-m-d H:i:s'));
        $nonprofit->setUpdatedAtDt(new \DateTime());

        $x = 0;
        do {
            $x++;
            $ein = 'admin_'.$x;
            $exists = \Entity\Charity::findOneBy(['ein' => $ein]);
        } while($exists);

        $nonprofit->setEin($ein);

        self::$em->persist($nonprofit);
        self::$em->flush($nonprofit);

        echo json_encode(['url' => $nonprofit->getUrl()]);
    }

    public function site_speed() {
        $data['main_content'] = 'site_speed';

        ini_set('memory_limit', '2G');

        $this->load->view('includes/admin/admin_template', $data);
    }
}
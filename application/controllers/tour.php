<?php
require_once(__DIR__ . '/Base_Controller.php');



class Tour extends \Base_Controller
{
    const users_per_page = 5;

    public function __construct()
    {
        parent::__construct();
        $this->load->config('general_website_conf');
    }

    public function index() {
        $this->htmlTitle      = 'Tour';

        $this->metaDesc = "Take a tour of GiverHub. Learn how to donate and do other awesome giving.";

        $data['my_dashboard'] = true;
        $data['main_content'] = 'members/index';

        $user = \Entity\User::findOneBy(['email' => 'demo@giverhub.com']);

        $data['activities'] = [];
        $data['tour'] = true;

        $data['user'] = $user;

        $this->load->view('includes/user/template', $data);
    }
    
}
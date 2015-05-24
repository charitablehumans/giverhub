<?php

use \Entity\FAQ;

/*
 * Display all the faqs 
 * 
 */
require_once(__DIR__ . '/Base_Controller.php');

class Faqs extends Base_Controller {

    public function index() {
        $this->htmlTitle = 'FAQs';
        $this->metaDesc = 'Answers to frequently asked questions about how to donate to charities and related stuff.';
        $data['main_content'] = 'faqs/index';

        $data['faqs'] = FAQ::findBy([], ['fq_order' => 'asc']);
        $data['disable_beta_modal'] = true;

        $this->load->view('includes/user/template', $data);
    }
}

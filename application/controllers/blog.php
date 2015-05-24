<?php

use \Entity\Blogs;

/*
 * Display Blogs
 * 
 */
require_once(__DIR__ . '/Base_Controller.php');

class Blog extends Base_Controller {


    public function index() {
        $this->htmlTitle = 'Blog';

        $data['blogs'] = Blogs::findBy(['is_publish' => '1'], ['id' => 'desc']);
		$data['main_content'] = 'blog/index';
		$data['disable_beta_modal'] = true;
        $this->load->view('includes/user/template', $data);
    }

	public function save_blog() {

        if (!$this->user || $this->user->getLevel() < 4) {
            throw new Exception('No access!');
        }

        $addBlogs = new \Entity\Blogs();
        $addBlogs->setTitle($_POST['title']);
		$addBlogs->setDescription($_POST['description']);
		$addBlogs->setIsPublish($_POST['is_publish']);
		$addBlogs->setUserId($this->user);
		$addBlogs->setCreatedAt(date('Y-m-d H:i:s'));

        \Base_Controller::$em->persist($addBlogs);
        \Base_Controller::$em->flush($addBlogs);
        $response = array(
            'success' => true,
            'msg' => 'Blog added successfully',
        );
        echo json_encode($response);

	}
}

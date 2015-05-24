<?php
require_once(__DIR__ . '/Base_Controller.php');
require_once __DIR__.'/../libraries/htmlpurifier/HTMLPurifier.auto.php';

class Page extends Base_Controller {

    public function index($url_slug) {
        /** @var \Entity\Page $page */
        $page = \Entity\Page::findOneBy(['urlSlug' => $url_slug]);
        if (!$page) {
            $this->giverhub_404('page/404', 'Page not found!');
            return;
        }

        $this->htmlTitle = $page->getName();
        $this->metaDesc = $page->getDescription();

        $data['main_content'] = 'page/index';
        $data['page'] = $page;

        $this->load->view('includes/user/template', $data);
    }

}

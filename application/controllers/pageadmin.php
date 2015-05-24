<?php
require_once(__DIR__ . '/Base_Controller.php');
require_once __DIR__.'/../libraries/htmlpurifier/HTMLPurifier.auto.php';

class Pageadmin extends Base_Controller {

    public function desc() {
        if (!$this->user) {
            throw new Exception('user not signed in');
        }
        if (!isset($_POST['page_id'])) {
            throw new Exception('missing page_id');
        }

        /** @var \Entity\Page $page */
        $page = \Entity\Page::find($_POST['page_id']);

        if (!$page) {
            throw new Exception('could not load page. page_id: ' . $_POST['page_id']);
        }

        if (!$page->isAdmin()) {
            throw new Exception('current user is not page admin. page_id: ' . $_POST['page_id']);
        }

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $page->setDescription($purifier->purify(trim($_POST['desc'])));

        self::$em->persist($page);
        self::$em->flush($page);

        echo json_encode(['success' => true]);
    }
}

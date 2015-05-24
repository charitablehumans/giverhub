<?php
require_once(__DIR__ . '/Base_Controller.php');

class Stat extends Base_Controller {
    public function register() {
        if (!isset($_POST['name'])) {
            throw new Exception('missing name');
        }

        if (preg_match('#\,#', $_POST['name'])) {
            $statNames = explode(',',$_POST['name']);
            foreach($statNames as $name) {
                $name = trim($name);
                if ($name) {
                    \Entity\Stat::register($name);
                }
            }
        } else {
            \Entity\Stat::register( $_POST['name'] );
        }

        echo json_encode(['success' => true]);
    }
}
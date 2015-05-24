<?php
require_once(__DIR__ . '/Base_Controller.php');


if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class GuideStar
 * https://data.guidestar.org
 *
 * intended to be run from the cli
 * http://ellislab.com/codeigniter/user-guide/general/cli.html
 */
class GuideStar extends \Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_cli_request() && $this->router->method != 'callback') {
            sleep(rand(0,4));
            $msg = md5(rand(0,1000));
            echo json_encode(array('msg' => $msg, 'crc' => crc32($msg)));
            die;
        }
        set_time_limit(0);
        ini_set('memory_limit', '2G');
    }

    public function index() {

    }
}


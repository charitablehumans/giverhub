<?php
require_once(__DIR__ . '/Base_Controller.php');

use \Entity\ChangeOrgPetition;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Queue
 *
 * intended to be run from the cli
 * http://ellislab.com/codeigniter/user-guide/general/cli.html
 */
class Queue extends \Base_Controller
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

    private function startNext() {
        $query = $this->db->query('SELECT id,command FROM task_queue WHERE `status` = \'waiting\' ORDER BY created_at ASC LIMIT 1');
        $rows = $query->result_array();
        $waiting = count($rows);

        if (!$waiting) {
            //echo 'nothing waiting';
        } else {
            $row = $rows[0];
            $command = $row['command'];
            $id = $row['id'];
            $pid = getmypid();
            $webroot = __DIR__.'/../..';

            $this->db->query('UPDATE task_queue SET pid = '.$pid.', `status` = \'running\', started_at = NOW() WHERE id='.$id);
            $this->db->query('UNLOCK TABLES');
            exec("php $webroot/index.php $command", $output);
            $output = join("\n", $output);

            /** <prevent "server has gone away"> */
            $this->load->database();
            $this->db->reconnect();
            /** </prevent "server has gone away"> */

            $this->db->query('UPDATE task_queue SET `status` = \'completed\', stopped_at = NOW(), output = '.$this->db->escape($output).' WHERE id='.$id);
        }
    }

    public function index() {
        $this->db->query('LOCK TABLES task_queue WRITE');
        $query = $this->db->query('SELECT id,pid,command FROM task_queue WHERE `status` = \'running\' ORDER BY created_at ASC');
        $rows = $query->result_array();
        $running = count($rows);

        if ($running == 1) {
            $row = $rows[0];
            $id = $row['id'];
            $pid = $row['pid'];
            $command = $row['command'];
            if (!file_exists("/proc/$pid")) {
                echo 'command with pid: ' . $pid . ' is no longer running?! command: ' . $command;
                $this->db->query('UPDATE task_queue SET `status` = \'failed\', stopped_at = NOW() WHERE id = '.$id);

                $this->startNext();
            } else {
                //echo 'already running';
                $this->db->query('UNLOCK TABLES');
                return;
            }
        } elseif ($running == 0) {
            $this->startNext();
        } else {
            echo 'multiple running??!? ' . $running;
        }

        $this->db->query('UNLOCK TABLES');
    }

    /**
    $pids = preg_split('/\s+/', `ps -o pid --no-heading --ppid $ppid`);
    foreach($pids as $pid) {
    if(is_numeric($pid)) {
    echo "Killing $pid\n";
    posix_kill($pid, 9); //9 is the SIGKILL signal
    }
    }
     */

    public function test($sleep) {
        sleep($sleep);
        for($x = 0; $x < $sleep; $x++) {
            echo $sleep;
        }
    }

    public function add_task($command) {
        if (!$command) {
            throw new Exception('command is missing!');
        }
        $task = new \Entity\TaskQueue();
        $task->setCommand($command);
        self::$em->persist($task);
        self::$em->flush($task);
    }
}


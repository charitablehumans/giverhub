<?php
require_once(__DIR__.'/Base_Controller.php');
require_once(__DIR__.'/../models/CharityNavigatorAPI.php');

use \Entity\Charity;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class CharityNavigator
 *
 * intended to be run from the cli
 * http://ellislab.com/codeigniter/user-guide/general/cli.html
 */
class CharityNavigator extends \Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_cli_request()) {
            sleep(rand(0,4));
            $msg = md5(rand(0,1000));
            echo json_encode(array('msg' => $msg, 'crc' => crc32($msg)));
            die;
        }
        set_time_limit(0);
        ini_set('memory_limit', '2G');
    }

    public function index() {
        $q = $this->db->query('select max(id) as max from Charity');
        $rows = $q->result_array();
        $max = $rows[0]['max'];

        $limit = 1000;
        $start = 1;
        $end = $start + $limit;
        do {
            echo $start .' to '.$end . PHP_EOL;
            $q = $this->db->query('select id,ein from Charity where id >= '.$start. ' and id < '.$end . ' and revenueAmount is not null');
            $charities = $q->result_array();
            echo count($charities) . PHP_EOL;
            $charity_chunks = array_chunk($charities, 4);
            $eins = [];
            foreach($charity_chunks as $charities) {
                foreach($charities as $charity) {
                    $eins[$charity['id']] = $charity['ein'];
                }
                $results = CharityNavigatorAPI::search_eins($eins);
                foreach($results as $ein => $result) {
                    if ($result['meta']['total_count']) {
                        echo $ein . '+' .PHP_EOL;
                        //print_r($result);
                    } else {
                        echo $ein . '-' .PHP_EOL;
                        print_r($result);
                    }
                }
            }

            $start += $limit;
            $end += $limit;
        } while($start < $max);
    }

    public function crawl() {
        CharityNavigatorAPI::crawl();
    }

    public function crawl_and_update_db() {
        CharityNavigatorAPI::crawl_and_update_db();
    }

    public function get_all_names_from_guidestar() {
        $q = $this->db->query("select id, ein from Charity where orgId is not null");
        $rows = $q->result_array();
        echo count($rows) . ' charities with orgId is not null...' . PHP_EOL;

        $trim = function($str) { $str = preg_replace('#\xc2\xa0#', ' ', $str); return trim($str); };

        foreach($rows as $row) {
            $charity_id = $row['id'];
            $ein = $row['ein'];

            $guidestar_url = 'http://www.guidestar.org/ReportOrganization.aspx?ein=' . substr($ein, 0, 2) . '-' . substr($ein, 2);

            $q = $this->db->query('select data from UrlCache where url = \''.$guidestar_url.'\'');
            $datas = $q->result_array();
            if (!$datas) {
                echo 'missing data for ' . $guidestar_url .PHP_EOL;
                continue;
            }
            $data = $datas[0]['data'];

            $dom = new DOMDocument;
            if (!@$dom->loadHTML($data)) {
                throw new Exception('Failed to loadHTML from '. $guidestar_url);
            }

            $x = new DOMXpath($dom);

            $els = $x->query("descendant-or-self::a[@name = 'contact']");
            if ($els->length != 1) {
                echo 'could not find contact element! length: ' . $els->length . 'ein: ' . $ein . PHP_EOL;
                continue;
            } else {
                $section = $els->item(0)->parentNode;
                $charityName = null;
                foreach($section->childNodes as $node) {
                    /** @var DOMElement $node */
                    if ($node->tagName == 'p' && $node->getAttribute('class') == 'half-space') {
                        $charityName = $trim($node->nodeValue);
                        break; // get the first one only!
                    }
                }
                if (!$charityName) {
                    throw new Exception('could not find charity name! ein: ' . $ein);
                } else {
                    $charity = Charity::find($charity_id);

                    if (!$charity) {
                        throw new Exception('wtf ' . $charity_id);
                    }

                    $charity->setName($charityName);

                    $prevName = $charity->getName();
                    if (preg_match('#^Also Known As:(.*)#', $prevName, $matches)) {
                        $charityName = trim($matches[1]);
                        $charity->setName($charityName);
                    }

                    \Base_Controller::$em->persist($charity);
                    \Base_Controller::$em->flush();
                    \Base_Controller::$em->clear();

                    echo PHP_EOL . $ein . ':' . $prevName . ' -> ' . $charityName . PHP_EOL;

                }
            }
        }
    }
}

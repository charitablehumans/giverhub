<?php
require_once(__DIR__ . '/Base_Controller.php');

use \Entity\ChangeOrgPetition;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Change_ORG
 * http://www.change.org/developers
 * https://github.com/change/api_docs/blob/master/v1/documentation/resources/petitions.md
 *
 * intended to be run from the cli
 * http://ellislab.com/codeigniter/user-guide/general/cli.html
 */
class Change_Org extends \Base_Controller
{
    /** @var EntityManager */
    static public $em;

    public $live = false;
    public $signatures = false;
    public $reasons = false;
    public $news = false;

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

    public function index($live = false, $scrape = true, $signatures = false, $reasons = false, $news = false, $petition_id = null) {
        $this->live = $live;
        $this->signatures = $signatures;
        $this->reasons = $reasons;
        $this->news = $news;

        self::$em->getConnection()->getConfiguration()->setSQLLogger(null);

        if ($scrape) {
            $petition_ids = $this->scrape_petition_ids();
            $this->update_petitions($petition_ids);
        }

        if ($petition_id) {
            $this->update_petitions([$petition_id]);
        }

        $petition_ids = $this->get_petition_ids_from_db_that_needs_updating();

        if ($petition_ids) {
            echo count($petition_ids) . ' needs updating.' . PHP_EOL;
            $this->update_petitions($petition_ids);
        }
        echo 'FIN!' . PHP_EOL;
    }

    public function add_reasons_and_signatures($live = false, $nr_to_add = 1000, $real_run = true) {
        $this->live = $live;

        $petition_ids = $this->get_all_petition_ids_in_db();
        $count = count($petition_ids);

        foreach($petition_ids as $nr => $petition_id) {
            echo $nr . '/' . $count;
            /** @var \Entity\ChangeOrgPetition $petition */
            $petition = ChangeOrgPetition::findOneBy(['petition_id' => $petition_id]);
            $petition->addMoreReasonsAndSignatures($nr_to_add, $real_run);
            echo PHP_EOL;
        }
    }

    public function scrape_trending($live = false, $signatures = false, $reasons = false, $news = false, $nr_to_get = 20) {
        $this->live = $live;
        $this->signatures = $signatures;
        $this->reasons = $reasons;
        $this->news = $news;

        $offset = 0;
        $items = [];


        while(count($items) < $nr_to_get) {
            $response = @file_get_contents( 'https://www.change.org/api-proxy/api/-/petitions/trending/en-US?offset=' . $offset );

            if ( ! $response) {
                throw new Exception( 'no response' );
            }

            $json = json_decode( $response, true );

            if ( ! isset( $json['items'] ) || ! is_array( $json['items'] ) || ! $json['items']) {
                throw new Exception( 'no items in offset: ' . $offset . ' response: ' . $response );
            }

            foreach ($json['items'] as $item) {
                $items[$item['petition_id']] = $item;
            }
            $offset+=10;
        }

        $this->update_petitions(array_keys($items));

        /** @var \Entity\ChangeOrgPetition[] $trending */
        $trending = \Entity\ChangeOrgPetition::findBy(['trending' => 1]);
        foreach($trending as $trend) {
            $trend->setTrending(0);
            self::$em->persist($trend);
            self::$em->flush($trend);
        }

        foreach($items as $petition_id => $item) {
            /** @var \Entity\ChangeOrgPetition $petition */
            $petition = \Entity\ChangeOrgPetition::findOneBy(['petition_id' => $petition_id]);
            $petition->setTrending(1);
            self::$em->persist($petition);
            self::$em->flush($petition);
        }
    }

    public function scrape_petition_ids() {
        // https://www.change.org/en-GB/petitions?hash=recommended&hash_prefix=&list_type=default&view=recommended&page=3

        $baseUrls = array('https://www.change.org/en-GB/petitions?', 'https://www.change.org/petitions?');

        $parameterSets = array(
            array(
                'view' => 'all-time',
                'page' => 1,
                'hash' => 'all-time',
                'hash_prefix' => 'all-time',
                'first_request' => 'true',
                'list_type' => 'default'
            ),
            array(
                'view' => 'this-week',
                'page' => 1,
                'hash' => 'this-week',
                'hash_prefix' => 'this-week',
                'first_request' => 'true',
                'list_type' => 'default'
            ),
            array(
                'view' => 'today',
                'page' => 1,
                'hash' => 'today',
                'hash_prefix' => 'today',
                'first_request' => 'true',
                'list_type' => 'default'
            ),
            array(
                'view' => 'recommended',
                'page' => 1,
                'hash' => 'recommended',
                'hash_prefix' => '',
                'list_type' => 'default'
            ),
            array(
                'view' => 'most-recent',
                'page' => 1,
                'hash' => 'most-recent',
                'hash_prefix' => 'most-recent',
                'first_request' => 'true',
                'list_type' => 'default'
            ),
        );

        $ch = curl_init();


        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/curl-change-org-cookie-jar');
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/curl-change-org-cookie-jar');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_URL, $baseUrls[0]);
        $response = curl_exec($ch);
        preg_match('#content="(.*)" name="csrf-token"#', $response, $matches);
        $csrf = $matches[1];
        if (!$csrf) {
            throw new Exception('FUCK!!! no csrf. ' . print_r($matches, true));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                                  "Host: www.change.org",
                                                  "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",
                                                  "Accept: application/json, text/javascript, */*; q=0.01",
                                                  "Accept-Language: en-us,en;q=0.5",
                                                  "Accept-Encoding: gzip, deflate",
                                                  "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
                                                  "Keep-Alive: 115",
                                                  "Connection: keep-alive",
                                                  "X-Requested-With: XMLHttpRequest",
                                                  "Referer: https://www.change.org/en-GB/petitions",
                                                  'X-CSRF-Token: '.$csrf,
                                             ));

        $petition_ids = array();
        foreach($baseUrls as $baseUrl) {
            foreach($parameterSets as $parameters) {
                $petition_ids_for_this_part = [];
                do {
                    $final_request_url = $baseUrl.http_build_query($parameters);
                    // https://www.change.org/en-GB/petitions?view=all-time&page=1&hash=all-time&hash_prefix=all-time&first_request=1&list_type=default
                    curl_setopt($ch, CURLOPT_URL, $final_request_url);
                    echo 'fetching ' . $final_request_url . '...';
                    $response = curl_exec($ch);
                    echo 'DONE!';
                    $response = json_decode($response, true);
                    preg_match_all('#data-id="(.*?)"#', $response['html'], $matches);
                    if (!$matches[1]) {
                        break;
                    }
                    $petition_ids_before = count($petition_ids_for_this_part);
                    $petition_ids_for_this_part = array_merge($petition_ids_for_this_part, $matches[1]);
                    $petition_ids_for_this_part = array_unique($petition_ids_for_this_part);
                    $petition_ids_after = count($petition_ids_for_this_part);
                    $parameters['page']++;
                    echo ' unique: ' . $petition_ids_after . PHP_EOL;
                } while($parameters['page'] < 30 && $petition_ids_after > $petition_ids_before && !preg_match('#No petitions found|There are no more petitions to view#', $response['html']));
                $petition_ids = array_merge($petition_ids, $petition_ids_for_this_part);
                $petition_ids = array_unique($petition_ids);
                echo 'total unique: ' . count($petition_ids) . PHP_EOL;
            }
        }
        $petition_ids = array_unique($petition_ids);
        echo 'scraped ' . count($petition_ids) .' petition ids.' . PHP_EOL;;
        return $petition_ids;
    }

    public function update_petitions(array $petition_ids) {

        $oneDayAgo = new \DateTime('-1 day');

        $included_petition_ids = array();
        foreach($petition_ids as $petition_id) {
            /** @var ChangeOrgPetition $petition */
            $petition = ChangeOrgPetition::findOneBy(array('petition_id' => $petition_id));

            if ($petition) {
                $updatedAt = new \DateTime($petition->getUpdatedAt());
                if ($petition->getForceUpdate() || $updatedAt < $oneDayAgo) {
                    $included_petition_ids[] = $petition_id;
                }
            } else {
                $included_petition_ids[] = $petition_id;
            }
        }

        echo count($included_petition_ids) . ' petition ids needs updating.' . PHP_EOL;
        if ($included_petition_ids) {
            $petition_datas = $this->get_petitions($included_petition_ids);
            $count = count($petition_datas);
            foreach($petition_datas as $nr => $petition_data) {
                $this->update_petition($petition_data);
                echo '['.$nr.'/'.$count.']' . PHP_EOL;
            }
        }
    }

    public function update_petition($petition_data) {

        $d = $petition_data;

        $petition = ChangeOrgPetition::findOneBy(array('petition_id' => $d['petition_id']));
        echo 'updating petition with id: ' . $d['petition_id']. PHP_EOL;

        $debug = 0;
        if (!$petition) {
            $petition = new ChangeOrgPetition();
            $petition->setPetitionId($d['petition_id']);
            $debug = 1;
        } else {
            $debug = 2;
        }

        $petition->setTitle($d['title']);
        $petition->setStatus($d['status']);
        $petition->setUrl($d['url']);

        preg_match('#/p/(.*)#', $d['url'], $matches);

        if (!$matches[1]) {
            echo 'Problem with petition id : ' . $petition->getId() . ' could not get slug. ' . $d['url'] . '.' . PHP_EOL;
        } else {
            if (!$petition->getId()) { // only set url slug once
                $petition->setGiverhubUrlSlug($matches[1]);
            } else {
                $petition->changeGiverhubUrlSlug($matches[1]); // update slug and create history
            }
        }

        $petition->setOverview($d['overview']);
        $petition->setLetterBody($d['letter_body']);
        $petition->setSignatureCount($d['signature_count']);

        $petition->setImageUrl($d['image_url']);
        if (preg_match('/#processing$/', $d['image_url'])) {
            $petition->setImageUrl(null);
        }
        $petition->setCategory($d['category']);
        $petition->setGoal($d['goal']);

        $createdAt = new \DateTime($d['created_at'], new DateTimeZone('UTC'));
        $createdAt->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $petition->setCreatedAt($createdAt->format('Y-m-d H:i:s'));

        if ($d['end_at']) {
            $endAt = new \DateTime($d['end_at'], new DateTimeZone('UTC'));
            $endAt->setTimezone(new DateTimeZone(date_default_timezone_get()));
            $petition->setEndAt($endAt->format('Y-m-d H:i:s'));
        } else {
            $petition->setEndAt(null);
        }

        $petition->setCreatorName($d['creator_name']);
        $petition->setCreatorUrl($d['creator_url']);
        $petition->setOrganizationName($d['organization_name']);
        $petition->setOrganizationUrl($d['organization_url']);

        $this->update_auth_key($petition);

        $petition->setUpdatedAt(null);

        try {
            \Base_Controller::$em->persist($petition);
            \Base_Controller::$em->flush($petition);
        } catch(Exception $e) {
            $_SERVER['DUPE_DEBUG'] = $debug;
            throw $e;
        }

        if (!count($d['targets'])) {
            echo 'WARNING: NO TARGETS.'.PHP_EOL;
        }

        echo count($d['targets']) . ' targets..' . PHP_EOL;

        $tmp_targets = $petition->getTargets();
        $delete_targets = [];
        foreach($tmp_targets as $tmp_target) {
            $delete_targets[$tmp_target->getName()] = $tmp_target;
        }

        foreach($d['targets'] as $nr => $target) {
            $first = $nr == 0 ? 1 : 0;
            echo $first;
            $petition->createOrUpdateTarget($target['name'], $target['type'], $first);
            unset($delete_targets[$target['name']]);
        }
        echo PHP_EOL;

        foreach($delete_targets as $target) {
            \Base_Controller::$em->remove($target);
        }

        if ($this->signatures) {
            $petition->updateSignatures($this->signatures);
        }

        if ($this->reasons) {
            $petition->updateReasons($this->reasons);
        }

        if ($this->news) {
            $petition->updateNews($this->news);
        }

        $petition->setUpdatedAt(date('Y-m-d H:i:s'));
        $petition->setForceUpdate(0);
        \Base_Controller::$em->persist($petition);
        \Base_Controller::$em->flush($petition);
        \Base_Controller::$em->clear();
    }

    public function update_auth_key(ChangeOrgPetition $petition) {
        $api_key = 'removed';
        $secret = 'removed';

        $petition_id = $petition->getPetitionId();

        // Set up the endpoint and URL.
        $base_url = "https://api.change.org";
        $endpoint = "/v1/petitions/".$petition_id."/auth_keys";
        $url = $base_url . $endpoint;

        // Set up the signature parameters.
        $parameters = array();
        $parameters['api_key'] = $api_key;
        $parameters['source_description'] = 'Giverhub.com. If you would like GiverHub to gather signatures for your petition, no action is required on your part. It will be authorized automatically. GiverHub aims to make it super easy to donate to, and learn about, any non-profit in the US. In addition to that, we would love to provide our users with access to great petitions like yours from change.org. We don\'t charge any fees to our users, we only wish to match them up with the petitions that they are likely to be interested in signing. In short, we want to get more eyeballs on your petition. If you\'re interested in learning more about GiverHub, go to GiverHub.com and leave your email address. We\'ll be sure to put you at the top of our beta-tester waitlist';
        $parameters['source'] = $petition->getGiverHubUrl($this->live ? 'https://giverhub.com/' : 'https://dev.giverhub.com/') . '/?from=change_org';
        $parameters['requester_email'] = 'admin@giverhub.com';
        $parameters['callback_endpoint'] = ($this->live ? 'https://giverhub.com/' : 'https://dev.giverhub.com/') . 'change_org/callback';
        $parameters['timestamp'] = gmdate("Y-m-d\TH:i:s\Z");
        $parameters['endpoint'] = $endpoint;

        // Build request signature.
        $query_string_with_secret_and_auth_key = http_build_query($parameters) . $secret;

        // Add the request signature to the parameters array.
        $parameters['rsig'] = hash('sha256', $query_string_with_secret_and_auth_key);

        // Create the request body.
        $data = http_build_query($parameters);

        // POST the parameters to the petition's signatures endpoint.
        $curl_session = curl_init();
        curl_setopt_array($curl_session, array(
                                              CURLOPT_POST => 1,
                                              CURLOPT_URL => $url,
                                              CURLOPT_POSTFIELDS => $data,
                                              CURLOPT_RETURNTRANSFER => true,
                                              CURLOPT_SSL_VERIFYPEER => false,
                                         ));
        $result = curl_exec($curl_session);
        if (!$result) {
            $petition->setAuthKeyError('curl_error: ' . curl_error($curl_session));
        } else {
            $json = json_decode($result, true);

            $err = null;
            $auth_key = null;
            if (isset($json['status']) && $json['status'] == 'revoked') {
                $err = 'revoked';
            } elseif ($json['result'] != 'success') {
                $err = $json['result'] . ':' . join(',',$json['messages']);
            } elseif (!isset($json['auth_key']) || !$json['auth_key']) {
                $err = 'no/empty auth key';
            } else {
                $auth_key = $json['auth_key'];
            }
            $petition->setAuthKey($auth_key);
            $petition->setAuthKeyError($err);
        }

    }

    public function get_petitions(array $petition_ids) {


        $API_KEY = 'removed';
        $REQUEST_URL = 'https://api.change.org/v1/petitions';

        $return = [];
        $total = count($petition_ids);
        $current = 0;
        $chunks = array_chunk($petition_ids, 100);
        foreach($chunks as $petition_ids) {
            $parameters = array(
                'api_key' => $API_KEY,
                'petition_ids' => join(',', $petition_ids),
            );

            $query_string = http_build_query($parameters);
            $final_request_url = "$REQUEST_URL?$query_string";
            $current += count($petition_ids);
            echo 'Fetching '.count($petition_ids).' '.$current.'/'.$total.' petitions...';

            $attempts = 0;
            $response = '';
            while(true) {
                $response = @file_get_contents($final_request_url);
                if ($response) {
                    break;
                }
                echo 'Attempt failed...';
                $attempts++;
                if ($attempts >= 3) {
                    echo 'giving up.'.PHP_EOL;
                    break;
                }
                echo 'Attempting again...';
            }


            echo 'OK' . PHP_EOL;

            $json_response = json_decode($response, true);
            $return = array_merge($return, $json_response['petitions']);
        }
        return $return;
    }

    public function get_petition_ids_from_db_that_needs_updating() {
        $CI =& get_instance();
        $oneDayAgo = new \DateTime('-1 day');
        echo 'Finding petitions WHERE force_update = 1 OR updated_at < \'' . $oneDayAgo->format('Y-m-d H:i:s') . '\'' . PHP_EOL;
        $q = $CI->db->query($sql = 'SELECT petition_id AS petition_id FROM change_org_petition WHERE force_update = 1 OR updated_at < \'' . $oneDayAgo->format('Y-m-d H:i:s') . '\' ORDER BY updated_at ASC');
        $results = $q->result_array();
        $petition_ids = [];
        foreach($results as $result) {
            $petition_ids[] = $result['petition_id'];
        }
        return $petition_ids;
    }

    public function get_all_petition_ids_in_db() {
        $CI =& get_instance();

        echo 'Getting all petition ids in db... ';
        $q = $CI->db->query($sql = 'SELECT petition_id AS petition_id FROM change_org_petition');
        $results = $q->result_array();
        $petition_ids = [];
        foreach($results as $result) {
            $petition_ids[] = $result['petition_id'];
        }
        echo 'found ' . count($petition_ids) . PHP_EOL;
        return $petition_ids;
    }

    public function get_petition() {
        $API_KEY = 'removed';
        $REQUEST_URL = 'https://api.change.org/v1/petitions/1401279';

        $parameters = array(
            'api_key' => $API_KEY,
        );

        $query_string = http_build_query($parameters);
        $final_request_url = "$REQUEST_URL?$query_string";

        $attempts = 0;
        $response = '';
        while(true) {
            $response = @file_get_contents($final_request_url);
            if ($response) {
                break;
            }
            echo 'Attempt failed...';
            $attempts++;
            if ($attempts >= 3) {
                echo 'giving up.'.PHP_EOL;
                break;
            }
            echo 'Attempting again...';
        }

        $json_response = json_decode($response, true);
        $petition_id = $json_response['petition_id'];
        echo $petition_id;
    }

    public function get_id() {
        $API_KEY = 'removed';
        $REQUEST_URL = 'https://api.change.org/v1/petitions/get_id';
        $PETITION_URL = 'http://www.change.org/en-GB/petitions/madhead-please-keep-the-original-transmigration-open-in-tower-of-saviors';

        $parameters = array(
            'api_key' => $API_KEY,
            'petition_url' => $PETITION_URL
        );

        $query_string = http_build_query($parameters);
        $final_request_url = "$REQUEST_URL?$query_string";

        $attempts = 0;
        $response = '';
        while(true) {
            $response = @file_get_contents($final_request_url);
            if ($response) {
                break;
            }
            echo 'Attempt failed...';
            $attempts++;
            if ($attempts >= 3) {
                echo 'giving up.'.PHP_EOL;
                break;
            }
            echo 'Attempting again...';
        }

        $json_response = json_decode($response, true);
        $petition_id = $json_response['petition_id'];
        echo $petition_id;
    }

    public function callback() {
        $fp = fopen('/tmp/change_org_callbacks', 'a+');
        $data = print_r($_REQUEST, true) . print_r($_GET, true) . print_r($_POST, true);
        fputs($fp, $data);
        fflush($fp);
        fclose($fp);
    }

    public function import_profanities_from_text_file() {
        $file = file_get_contents(__DIR__.'/../../../profanity.txt');
        $rows = explode("\n", $file);
        foreach($rows as $row) {
            $row = trim($row);
            echo $row;
            $profanity = \Entity\Profanity::findOneBy(array('profanity' => $row));
            if (!$profanity) {
                $profanity = new \Entity\Profanity();
                $profanity->setProfanity($row);
                \Base_Controller::$em->persist($profanity);
                \Base_Controller::$em->flush($profanity);
                echo ' ..created!' . PHP_EOL;
            } else {
                echo ' ..exists!' . PHP_EOL;
            }
        }
    }
}


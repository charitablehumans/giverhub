<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'WebDriver.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'By.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'WebDriverWait.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'DesiredCapabilities.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'Exceptions.php');
require_once(__DIR__.'/../libraries/sphinxapi.php');
require_once(__DIR__ . '/Base_Controller.php');

use Entity\User;
use Entity\UserFollower;
use \Entity\Charity;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Special_operations
 *
 * intended to be run from the cli
 * http://ellislab.com/codeigniter/user-guide/general/cli.html
 */
class Special_operations extends \Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_cli_request()) {
			echo "You have no permission to access this page";exit;
        }
    }

    public function index() {
        echo 'updateUserAutofollow' . PHP_EOL;
    }

	public function updateUserAutofollow()
	{

		foreach(User::findAll() as $user) {

            if (!$user) {
                throw new Exception('could not find user. user_id: ' . $_POST['user_id']);
            }

            $userId = $user->getId();

            foreach(User::findBy(['auto_follow'=>'1']) as $auser){

				$auserId = $auser->getId();

				if ($auserId != $userId) {

		            $checkExistingFollower = UserFollower::findOneBy(array('follower_user_id' => $userId,'followed_user_id'=>$auserId));
		            $checkExistingFollowed = UserFollower::findOneBy(array('follower_user_id' => $auserId,'followed_user_id'=>$userId));
	
					if (!$checkExistingFollower || !$checkExistingFollowed) {
					
						if (!$checkExistingFollower) {

							$newUserFollower = new UserFollower();
						    $newUserFollower->setFollowerUserId($userId);
						    $newUserFollower->setFollowedUserId($auserId);
						    $newUserFollower->setDate(date('Y-m-d H:i:s'));
							self::$em->persist($newUserFollower);
							self::$em->flush($newUserFollower);

						}

						if (!$checkExistingFollowed) {

							$newUserFollower = new UserFollower();
						    $newUserFollower->setFollowerUserId($auserId);
						    $newUserFollower->setFollowedUserId($userId);
						    $newUserFollower->setDate(date('Y-m-d H:i:s'));
							self::$em->persist($newUserFollower);
							self::$em->flush($newUserFollower);

						}
					
					}
				}

            }
        }

	}

    public function reset_bets($user_id_1, $user_id_2) {
        $rand = function() {
            $random_strings = [
                'apples',
                'oranges',
                'obamacare',
                'puppies',
                'kittens',
                'inzomnia',
                'mr bush',
                'facebook',
                'crush',
                'twitter',
                'soccer',
                'thaiboxing',
                'renato',
                'laranja',
            ];

            $ret = [];
            for($x = 0; $x < rand(2,4); $x++) {
                $r = rand(0,count($random_strings)-1);
                $ret[] = $random_strings[$r];
            }
            return join(' ', $ret);
        };

        $u1 = User::find($user_id_1);
        $u2 = User::find($user_id_2);

        $this->db->query('delete from bet');

        $dt = new DateTime('+30 day');

        $q = $this->db->query('select id from Charity limit 1000');
        $charities = $q->result_array();
        shuffle($charities);

        foreach(array_merge(['claim_conflict'], \Entity\Bet::$statuses) as $status) {
            $bet1 = new \Entity\Bet();
            $bet2 = new \Entity\Bet();

            $bet1->setFirstUser($u1);
            $bet1->setOtherUser($u2);

            $bet2->setFirstUser($u2);
            $bet2->setOtherUser($u1);

            $bet1->setCreatedDateDt(new DateTime());
            $bet2->setCreatedDateDt(new DateTime());

            if ($status == 'confirmed' || $status == 'claim_conflict') {
                $bet1->setDeterminationDate(date('Y-m-d'));
                $bet2->setDeterminationDate(date('Y-m-d'));
            } else {
                $bet1->setDeterminationDateDt($dt);
                $bet2->setDeterminationDateDt($dt);
            }

            $bet1->setName($rand() . ' ' . $status . ' bet from ' . $u1->getName() . ' to ' . $u2->getName());
            $bet2->setName($rand() . ' ' . $status . ' bet to ' . $u1->getName() . ' from ' . $u2->getName());

            $bet1->setTerms($bet1->getName());
            $bet2->setTerms($bet2->getName());

            $bet1->setAmount(rand(10,1000));
            $bet2->setAmount(rand(10,1000));

            if ($status == 'claim_conflict') {
                $bet1->setStatus('confirmed');
                $bet2->setStatus('confirmed');
                $bet1->setFirstUserClaim('win');
                $bet2->setFirstUserClaim('win');
                $bet1->setOtherUserClaim('win');
                $bet2->setOtherUserClaim('win');
            } else {
                $bet1->setStatus($status);
                $bet2->setStatus($status);
            }

            if ($status == 'over') {
                $bet1->setFirstUserClaim('win');
                $bet1->setOtherUserClaim('loss');

                $bet2->setFirstUserClaim('win');
                $bet2->setOtherUserClaim('loss');
            }

            $charity_id = array_pop($charities);
            $charity_id = $charity_id['id'];
            $bet1->setFirstCharityId($charity_id);

            $charity_id = array_pop($charities);
            $charity_id = $charity_id['id'];
            $bet2->setFirstCharityId($charity_id);

            if (in_array($status, ['confirmed', 'over', 'claim_conflict'])) {
                $charity_id = array_pop($charities);
                $charity_id = $charity_id['id'];
                $bet1->setOtherCharityId($charity_id);

                $charity_id = array_pop($charities);
                $charity_id = $charity_id['id'];
                $bet2->setOtherCharityId($charity_id);
            }

            self::$em->persist($bet1);
            self::$em->persist($bet2);
            self::$em->flush();
        }

    }

    public function fix_charity_url_slugs($start_id = 0) {
        $q = $this->db->query('select max(id) as max_id from Charity');
        $rows = $q->result_array();
        $max_id = $rows[0]['max_id'];

        $limit = 1000;
        $end_id = $start_id + $limit;

        $n = 1;

        $tried_slugs = [];


        while($start_id <= $max_id) {
            echo $start_id.'-'.$end_id.'...';
            $q = $this->db->query('select id,name from Charity where id >= '.$start_id.' and id < '.$end_id);
            $rows = $q->result_array();
            echo PHP_EOL;

            foreach($rows as $i => $row) {
                $n++;
                $slug = \Common::slug($row['name'], '-');
                $x = 2;
                $slugx = $slug;
                do {
                    if (isset($tried_slugs[$slugx]) && $tried_slugs[$slugx] != $row['id']) {
                        $slugx = $slug . '-' . $x;
                        $x++;
                        continue;
                    }
                    $q = $this->db->query('select id as using_id from Charity where id <> '.$row['id']. ' and url_slug = \''.$slugx.'\'');
                    $rows2 = $q->result_array();
                    if ($rows2) {
                        $tried_slugs[$slugx] = $rows2[0]['using_id'];
                        $slugx = $slug . '-' . $x;
                        $x++;
                    } else {
                        break;
                    }
                } while(true);

                $tried_slugs[$slugx] = $row['id'];
                $q = 'update Charity set url_slug = \''.$slugx.'\' where id = '.$row['id'];
                if (!$this->db->query($q)) {
                    throw new Exception($q . ' failed.');
                }
                echo $i . ':' . round(($n / ($max_id-$start_id)) * 100, 1) . '%:' . $row['id'] . ' ' . $row['name'] . ' -> ' . $slugx . PHP_EOL;
            }

            $start_id += $limit;
            $end_id += $limit;
        }
    }

    public function generate_sitemap() {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $base = 'https://giverhub.com';
        $urls = [];

        $urls[] = 'https://giverhub.com';
        $urls[] = 'https://giverhub.com/home/contact';
        $urls[] = 'https://giverhub.com/home/privacy';
        $urls[] = 'https://giverhub.com/bet-a-friend';



        $q = $this->db->query('select max(id) as max_id from Charity');
        $rows = $q->result_array();
        $max_id = $rows[0]['max_id'];

        $limit = 1000;

        $start_id = 0;
        $end_id = $start_id + $limit;

        while($start_id <= $max_id) {
            echo $start_id.'-'.$end_id.'...';
            $q = $this->db->query('select url_slug from Charity where id >= '.$start_id.' and id < '.$end_id);
            $rows = $q->result_array();
            echo PHP_EOL;

            foreach($rows as $i => $row) {
                $urls[] = $base . '/nonprofits/'.$row['url_slug'];
                $urls[] = $base . '/nonprofits/'.$row['url_slug'].'/reviews';
                $urls[] = $base . '/nonprofits/'.$row['url_slug'].'/followers';
            }

            $start_id += $limit;
            $end_id += $limit;
        }

        $q = $this->db->query('select max(id) as max_id from change_org_petition');
        $rows = $q->result_array();
        $max_id = $rows[0]['max_id'];

        $limit = 1000;

        $start_id = 0;
        $end_id = $start_id + $limit;

        while($start_id <= $max_id) {
            echo $start_id.'-'.$end_id.'...';
            $q = $this->db->query('select giverhub_url_slug from change_org_petition where id >= '.$start_id.' and id < '.$end_id);
            $rows = $q->result_array();
            echo PHP_EOL;

            foreach($rows as $i => $row) {
                $urls[] = $base . '/petitions/'.$row['giverhub_url_slug'];
                $urls[] = $base . '/petitions/'.$row['giverhub_url_slug'].'/reasons';
                $urls[] = $base . '/petitions/'.$row['giverhub_url_slug'].'/news';
            }

            $start_id += $limit;
            $end_id += $limit;
        }

        $start = 0;

        $n = 0;

        $names = [];

        while(isset($urls[$start])) {
            $name = 'sitemap-'.$n.'.xml';
            echo 'generating ' . $name . '...';
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
            for($x = $start; $x < $start+50000; $x++) {
                if (!isset($urls[$x])) {
                    break;
                }
                $url = $urls[$x];
                $sitemap .= '<url>'.PHP_EOL;
                $sitemap .= '<loc>' . $url . '</loc>'.PHP_EOL;
                $sitemap .= '</url>'.PHP_EOL;
            }
            $sitemap .= '</urlset>'.PHP_EOL;

            echo 'saving...';
            file_put_contents($name, $sitemap);
            echo 'gzipping...';
            sleep(1);
            shell_exec('gzip '.$name);
            $names[] = $name .'.gz';

            $n++;
            $start += 50000;
            echo PHP_EOL;
        }

        $sitemap_index = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $sitemap_index .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        foreach($names as $name) {
            $sitemap_index .= '<sitemap><loc>https://giverhub.com/sms/'.$name.'</loc></sitemap>'.PHP_EOL;
        }
        $sitemap_index .= '</sitemapindex>'.PHP_EOL;

        file_put_contents('sitemap-index.xml', $sitemap_index);
    }

    public function generate_sitemap_sitemap() {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $base = 'https://giverhub.com';
        $urls = [];

        $urls[] = 'https://giverhub.com/a-z';
        $urls[] = 'https://giverhub.com/a-z/nonprofits';
        $urls[] = 'https://giverhub.com/a-z/petitions';

        $letters = array_merge(range(0,9), range('a', 'z'));

        $data['letters'] = [];
        foreach($letters as $letter) {
            foreach(['nonprofits', 'petitions'] as $type) {
                if ($type == 'nonprofits') {
                    $res = \Entity\Charity::findSphinxQuery('@fieldName ^'.$letter.'*', 0, 1, 1000000);
                } else {
                    $res = \Entity\ChangeOrgPetition::findSphinxQuery('@titleField ^'.$letter.'*', 0, 1, 1000000);
                }

                if ($res['count']) {
                    $pages = ceil($res['count'] / 80);
                    for($page = 1; $page <= $pages; $page++) {
                        $urls[] = $base . '/a-z/'.$type.'/'.$letter.'/'.$page;
                    }
                }

            }
        }
        print_r($urls);

        $name = 'sitemap-sitemap.xml';
        echo 'generating ' . $name . '...';
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        foreach($urls as $url) {
            $sitemap .= '<url>'.PHP_EOL;
            $sitemap .= '<loc>' . $url . '</loc>'.PHP_EOL;
            $sitemap .= '</url>'.PHP_EOL;
        }
        $sitemap .= '</urlset>'.PHP_EOL;

        echo 'saving...';
        file_put_contents($name, $sitemap);

    }

    public function generate_petition_reasons_signatures_sitemap() {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $base = 'https://giverhub.com';
        $urls = [];

        $q = $this->db->query('select max(id) as max_id from change_org_petition');
        $rows = $q->result_array();
        $max_id = $rows[0]['max_id'];

        $limit = 1000;

        $start_id = 0;
        $end_id = $start_id + $limit;

        while($start_id <= $max_id) {
            echo $start_id.'-'.$end_id.'...';
            $q = $this->db->query('select id,giverhub_url_slug from change_org_petition where id >= '.$start_id.' and id < '.$end_id);
            $rows = $q->result_array();
            echo PHP_EOL;

            foreach($rows as $i => $row) {
                /** @var \Entity\ChangeOrgPetition $petition */
                $petition = \Entity\ChangeOrgPetition::find($row['id']);
                $itemsPerPage = 20;

                $urls[] = $base . '/petitions/'.$row['giverhub_url_slug'].'/reasons';
                $count = $petition->getReasonCount();
                $pages = ceil($count / $itemsPerPage);
                for($page = 2; $page <= $pages; $page++) {
                    $urls[] = $base . '/petitions/'.$row['giverhub_url_slug'].'/reasons/'.$page;
                }

                $urls[] = $base . '/petitions/'.$row['giverhub_url_slug'].'/signatures';
                $count = $petition->getSignaturesCount();
                $pages = ceil($count / $itemsPerPage);
                for($page = 2; $page <= $pages; $page++) {
                    $urls[] = $base . '/petitions/'.$row['giverhub_url_slug'].'/signatures/'.$page;
                }
            }

            $start_id += $limit;
            $end_id += $limit;
        }

        $start = 0;
        $n = 0;
        $names = [];

        while(isset($urls[$start])) {
            $name = 'sitemap-prs-'.$n.'.xml';
            echo 'generating ' . $name . '...';
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
            for($x = $start; $x < $start+50000; $x++) {
                if (!isset($urls[$x])) {
                    break;
                }
                $url = $urls[$x];
                $sitemap .= '<url>'.PHP_EOL;
                $sitemap .= '<loc>' . $url . '</loc>'.PHP_EOL;
                $sitemap .= '</url>'.PHP_EOL;
            }
            $sitemap .= '</urlset>'.PHP_EOL;

            echo 'saving...';
            file_put_contents($name, $sitemap);
            echo 'gzipping...';
            sleep(1);
            shell_exec('gzip '.$name);
            $names[] = $name .'.gz';

            $n++;
            $start += 50000;
            echo PHP_EOL;
        }

        $sitemap_index = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $sitemap_index .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        foreach($names as $name) {
            $sitemap_index .= '<sitemap><loc>https://giverhub.com/prs/'.$name.'</loc></sitemap>'.PHP_EOL;
        }
        $sitemap_index .= '</sitemapindex>'.PHP_EOL;

        file_put_contents('sitemap-prs-index.xml', $sitemap_index);
    }

    public function move_nonprofit_mission() {
        $q = $this->db->query('select id,mission,mission_user_id from Charity where mission is not null');

        $rows = $q->result_array();

        foreach($rows as $row) {
            $user_id = $row['mission_user_id'] ? $row['mission_user_id'] : 137;
            $mission = \Entity\Mission::findOneBy(['charity_id' => $row['id'], 'user_id' => $row['mission_user_id']]);
            if (!$mission) {
                $mission = new \Entity\Mission();
                $mission->setSource('Unknown');
                $mission->setUserId($user_id);
                $mission->setCharityId($row['id']);
            }
            $mission->setMission($row['mission']);

            self::$em->persist($mission);
            self::$em->flush($mission);
        }
    }

    public function set_scrambled_post_ids() {
        /** @var \Entity\ActivityFeedPost[] $posts */
        $posts = \Entity\ActivityFeedPost::findAll();

        foreach($posts as $post) {
            do {
                $rand = bin2hex(openssl_random_pseudo_bytes(10));
                $existing = \Entity\ActivityFeedPost::findOneBy(['scrambled_id' => $rand]);
            } while($existing);
            $post->setScrambledId($rand);
            self::$em->persist($post);
            self::$em->flush($post);
        }
    }

    /**
     * @see http://bugtracker.giverhub.com/view.php?id=1188
     */
    public function calculate_percentiles($sample_size = 100) {
        $q = $this->db->query('select max(id) as max_id from Charity');
        $rows = $q->result_array();
        $max_id = $rows[0]['max_id'];

        $stuff_left = $sample_size;
        $ids_used = [];

        $data = [
            'program_services' => [],
            'fundraising_expenses' => [],
            'Professional Fundraising Fees' => [],
            'Executive Compensation' => [],
            'Advertising and Promotion' => [],
            'Office Expenses' => [],
            'Information Technology' => [],
            'Travel' => [],
            'Conferences, conventions, meetings' => [],
        ];
        do {
            $id = rand(1,$max_id);
            if (isset($ids_used[$id])) {
                continue;
            }

            /** @var \Entity\Charity $charity */
            $charity = \Entity\Charity::find($id);
            if (!$charity) {
                continue;
            }

            if ($charity->getProgramServicesAmount() === null) {
                continue;
            }

            if (!$charity->getIrsTotalRevenue()) {
                continue;
            }

            $prog = $charity->getPercentageOfRevenue($charity->getProgramServicesAmount(), false, false);
            $fund = $charity->getIrsFundraisingExpensesPercentageOfRevenue(false,false);
            $pro = $charity->getIrsProFundraisingFeesPercentageOfRevenue(false,false);
            $exe = $charity->getIrsCompensationOfCurrentOfficersPercentageOfRevenue(false,false);
            $adv = $charity->getIrsAdvertisingAndPromotionsPercentageOfRevenue(false,false);
            $off = $charity->getIrsOfficeExpensesPercentageOfRevenue(false,false);
            $inf = $charity->getIrsInformationTechnologyPercentageOfRevenue(false,false);
            $trav = $charity->getIrsTravelPercentageOfRevenue(false,false);
            $conf = $charity->getIrsConferencesConventionsMeetingsPercentageOfRevenue(false,false);

            if (
                $prog === '-' ||
                $fund === '-' ||
                $pro === '-' ||
                $exe === '-' ||
                $adv === '-' ||
                $off === '-' ||
                $inf === '-' ||
                $trav === '-' ||
                $conf === '-') {
                continue;
            }

            $data['program_services'][] = $prog;
            $data['fundraising_expenses'][] = $fund;
            $data['Professional Fundraising Fees'][] = $pro;
            $data['Executive Compensation'][] = $exe;
            $data['Advertising and Promotion'][] = $adv;
            $data['Office Expenses'][] = $off;
            $data['Information Technology'][] = $inf;
            $data['Travel'][] = $trav;
            $data['Conferences, conventions, meetings'][] = $conf;

            $ids_used[$id] = true;
            $stuff_left--;
        } while($stuff_left);

        $markers = [];
        foreach($data as $name => &$values) {
            sort($values);
            $count = count($values);
            $step = (int)round($count/5);
            $markers[$name] = [
                'very low' => $values[$step],
                'low' => $values[$step*2],
                'normal' => $values[$step*3],
                'high' => $values[$step*4],
            ];
        }

        print_r($markers);
    }

    public function new_york_list($limit = 500, $silence = 1) {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $em = self::$em;
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $offset = 0;


        $l = [
            'main' => [],
            'unk-ir' => [],
            'unk-r' => [],
            'unk-ps' => [],
            'unk-it' => [],
            'no-ruling' => [],
            'old-ruling' => [],
        ];


        do {
            echo $offset.'...';

            $cl = new \SphinxClient();

            $cl->SetServer('sphinx');
            $cl->SetConnectTimeout(5);
            $cl->SetArrayResult(true);

            $cl->SetLimits((int)$offset, (int)$limit, 200000);

            $cl->SetFilter('cityId', array(1));

            $res = $cl->Query('', 'charity:charity_delta');

            if ($res && isset($res['matches'])) {
                echo count($res['matches']) . PHP_EOL;

                foreach($res['matches'] as $i => $row) {
                    $em->clear();
                    /** @var \Entity\Charity $charity */
                    $charity = \Entity\Charity::find($row['id']);

                    $v        = [];
                    $v['url'] = 'https://giverhub.com/' . $charity->getUrl();
                    $v['ein'] = $charity->getEin();
                    $v['ir']  = $charity->getIrsTotalRevenue(false);
                    $v['r']   = $charity->getRevenueAmount();
                    $v['ps']  = $charity->getIrsProgramServicesAsPercentageOfTotalFunctionalExpenses(true);
                    $v['it']  = $charity->getIrsInformationTechnologyPercentageOfRevenue(false, false);
                    $v['rd']  = $charity->getIrsRulingDate(false);

                    if (!$silence) {
                        echo 'checking ' . $row['id'] . '...' . PHP_EOL;
                        print_r($v);
                    }

                    if (!$v['ir'] || $v['ir'] == 'N/A') {
                        if ($v['r'] && $v['r'] < 1000000) {
                            //$l['unk-ir'][] = $v;
                            if (!$silence) {
                                echo 'adding to no-irs-revenue-list...' . PHP_EOL;
                            }
                        } else {
                            //$l['unk-r'][] = $v;
                            if (!$silence) {
                                echo 'skipping.. no irs-revenue.' . PHP_EOL;
                            }
                        }
                        continue;
                    }

                    if ($v['ir'] > 1000000) {
                        if (!$silence) {
                            echo 'skipping.. revenue too high.' . PHP_EOL;
                        }
                        continue;
                    }

                    if ($v['ps'] === null) {
                        if (!$silence) {
                            echo 'skipping.. unknown program services.' . PHP_EOL;
                        }
                        //$l['unk-ps'][] = $v;
                        continue;
                    }

                    if ($v['it'] != '0' && (!$v['it'] || $v['it'] === '-')) {
                        if (!$silence) {
                            echo 'skipping.. unknown IT.' . PHP_EOL;
                        }
                        //$l['unk-it'][] = $v;
                        continue;
                    }

                    if (!$v['rd']) {
                        if (!$silence) {
                            echo 'no ruling date.' . PHP_EOL;
                        }
                        //$l['no-ruling'][] = $v;
                        continue;
                    }

                    $ruling_year = substr($v['rd'], 0, 4);

                    if ($ruling_year < 2010) {
                        if (!$silence) {
                            echo 'old ruling date.' . PHP_EOL;
                        }
                        //$l['old-ruling'][] = $v;
                        continue;
                    }

                    $v['ps'] = round($v['ps'], 1) . '%';
                    $v['it'] = round($v['it'], 1) . '%';
                    $v['rd'] = $charity->getIrsRulingDate(false);

                    $l['main'][] = $v;
                }

                if ($silence) {
                    $count = [];
                    foreach ($l as $name => $list) {
                        $count[] = $name . ':' . count($list);
                    }
                    echo join(' ', $count) . PHP_EOL;
                }
            }

            $offset += $limit;
        } while($res && isset($res['matches']));

        foreach($l as $name => $list) {
            $fp = fopen($name.'.csv', 'w');

            fputcsv($fp,['url','ein', 'revenue (ir) (ca)','revenue (r) (gs)','program services (ps)','it (% of rev)', 'ruling date']);
            foreach($list as $fields) {
                fputcsv($fp, $fields);
            }

            fflush($fp);
            fclose($fp);
        }

    }

    public function test_betfriend() {
        $f = \Entity\BetFriend::find(12);

        $d = \Entity\Donation::find(60);

        $f->setStatus('pending');
        \Base_Controller::$em->persist($f);
        \Base_Controller::$em->flush($f);
    }

    public function set_overallscore($start = 1, $end = 10000) {
        if ($end === null) {
            $q      = $this->db->query( 'SELECT max(id) AS max_id FROM Charity' );
            $rows   = $q->result_array();
            $end = $rows[0]['max_id'];
        } else {
            $end = $start + $end;
        }

        for($x = $start; $x <= $end; $x++) {
            /** @var \Entity\Charity $charity */
            $charity = \Entity\Charity::find($x);

            if (!$charity) {
                continue;
            }
            $old = $charity->getOverallScore();
            $new = $charity->getIrsProgramServicesAsPercentageOfTotalFunctionalExpenses(true);
            echo $x.':'.round($old,2).':'.round($new,2).PHP_EOL;
            $charity->setOverallScore($new);
            self::$em->persist($charity);
            self::$em->flush($charity);
            self::$em->clear($charity);
        }
    }

    public function set_revenue($start = 1, $end = 10000) {
        if ($end === null) {
            $q      = $this->db->query( 'SELECT max(id) AS max_id FROM Charity' );
            $rows   = $q->result_array();
            $end = $rows[0]['max_id'];
        } else {
            $end = $start + $end;
        }

        for($x = $start; $x <= $end; $x++) {
            /** @var \Entity\Charity $charity */
            $charity = \Entity\Charity::find($x);

            if (!$charity) {
                continue;
            }
            $old = $charity->getRevenueAmount();
            $new = $charity->getIrsTotalRevenue(false);
            if ($new === 'N/A') {
                $new = null;
            }
            echo $x.':'.round($old,2).':'.round($new,2).PHP_EOL;
            $charity->setRevenueAmount($new);
            self::$em->persist($charity);
            self::$em->flush($charity);
            self::$em->clear($charity);
        }
    }

    public function fix_external_url_images() {
        /** @var \Entity\ExternalUrl[] $external_urls */
        $external_urls = \Entity\ExternalUrl::findAll();

        foreach($external_urls as $external_url) {
            echo $external_url->getUrl() . ' ...';
            \Entity\ExternalUrl::fetch($external_url->getUrl());
            echo PHP_EOL;
        }
    }

    public function crazy_city() {
        /** @var \Entity\CharityCity[] $cities */
        $cities = \Entity\CharityCity::findAll();

        $has = 0;
        $has_not = 0;

        foreach($cities as $city) {
            $q = $this->db->query('select count(*) as cnt from ZipCode where cityId = ' . $city->getId());
            $rows = $q->result_array();
            $cnt = $rows[0]['cnt'];
            if (!$cnt) {
                echo $city->getId() . " '".$city->getName()."' state: " . $city->getStateEntity()->getName().  PHP_EOL;
                echo "select * from CharityCity where name like \"%" . $city->getName() . "%\"" . PHP_EOL;
                /** @var \Entity\Charity[] $charities */
                $charities = \Entity\Charity::findBy(['cityId' => $city->getId()]);
                foreach($charities as $charity) {
                    echo $charity->getId() . ' ' . $charity->getName() . PHP_EOL;
                }
            }
        }

        echo PHP_EOL . 'HAS: ' . $has . PHP_EOL;
        echo 'Has Not: ' . $has_not;
    }
}


<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'WebDriver.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'By.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'WebDriverWait.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'DesiredCapabilities.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'Exceptions.php');
require_once(__DIR__ . '/Base_Controller.php');

use \Entity\RecurringDonation;
use \Entity\EmailHistory;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Task
 *
 * intended to be run from the cli
 * http://ellislab.com/codeigniter/user-guide/general/cli.html
 */
class Task extends \Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_cli_request()) {
			echo "You have no permission to access this page";exit;
        }
        set_time_limit(0);
        ini_set('memory_limit', '2G');
    }

    public function index() {
    }

    public function map_keywords_to_causes() {
        $offset = 0;
        $limit = 1000;

        $q = $this->db->query('select charity_cause_id,strength,keyword from charity_cause_keyword');
        $keywords = $q->result_array();
        $mappings = [];
        foreach($keywords as $keyword) {
            $mappings[strtoupper($keyword['keyword'])][] = [
                'cause_id' => $keyword['charity_cause_id'],
                'strength' => $keyword['strength']
            ];
        }

        $pattern = '#\b('.join('|',array_keys($mappings)).')\b#i';


        do {
            echo 'Getting ' . $limit . ' petitions from ' . $offset .'...';
            $q = $this->db->query('select id,title,overview from change_org_petition LIMIT '.$offset.','.$limit);
            $petitions = $q->result_array();
            echo 'OK'.PHP_EOL;

            foreach($petitions as $petition) {
                $q = $this->db->query('select cause_id from change_org_petition_cause where petition_id = '.$petition['id']);
                $tmpCauses = $q->result_array();
                $currentCauses = [];
                foreach($tmpCauses as $cause) {
                    $currentCauses[$cause['cause_id']] = $cause['cause_id'];
                }

                $matchingCauses = [];

                if ($mappings && preg_match_all($pattern, $petition['title']."\n".$petition['overview'], $matches)) {
                    foreach($matches[0] as $match) {
                        if (!isset($mappings[strtoupper($match)])) {
                            throw new Exception($match . ' is not set.');
                        }

                        foreach($mappings[strtoupper($match)] as $mapping) {
                            if (!isset($matchingCauses[$mapping['cause_id']])) {
                                $matchingCauses[$mapping['cause_id']] = ['strong' => 0, 'weak' => 0];
                            }

                            if ($mapping['strength']) {
                                $matchingCauses[$mapping['cause_id']]['strong']++;
                            } else {
                                $matchingCauses[$mapping['cause_id']]['weak']++;
                            }
                        }
                    }
                }

                $determinedCauses = [];
                foreach($matchingCauses as $causeId => $matchingCause) {
                    if ($matchingCause['strong'] || $matchingCause['weak'] >= 2) {
                        $determinedCauses[$causeId] = $causeId;
                    }
                }

                foreach($determinedCauses as $determinedCause) {
                    if (!isset($currentCauses[$determinedCause])) {
                        $ret = $this->db->insert('change_org_petition_cause', array(
                                'cause_id' => $determinedCause,
                                'petition_id' => $petition['id'],
                            ));
                        if (!$ret) {
                            throw new Exception('Failed inserting to change_org_petition_cause where cause_id = '.$determinedCause.' AND petition_id = '.$petition['id'] .' '. $ret);
                        }
                        echo 'ADDED new cause with id: ' . $determinedCause . ' to petition with id: ' . $petition['id'] . PHP_EOL;
                    }
                }

                foreach($currentCauses as $currentCause) {
                    if (!isset($determinedCauses[$currentCause])) {
                        $ret = $this->db->query('delete from change_org_petition_cause where cause_id = '.$currentCause.' AND petition_id = '.$petition['id']);
                        if (!$ret) {
                            throw new Exception('Failed deleting from change_org_petition_cause where causeId = '.$currentCause.' AND petition_id = '.$petition['id'] .' '. $ret);
                        }
                        echo 'DELETED cause with id: ' . $currentCause . ' from petition with id: ' . $petition['id'] . PHP_EOL;
                    }
                }
            }
            $offset += $limit;
        } while($petitions);



        $q = $this->db->query('select max(id) as max from Charity');
        $rows = $q->result_array();
        $max = $rows[0]['max'];

        $offset = 1;
        $limit = 1000;

        do {
            echo 'Getting between id ' . $offset . ' and '.($offset+$limit).' charities ...';
            $q = $this->db->query('select id,name,description from Charity where id >= '.$offset.' AND id < '.($offset+$limit).' AND revenueAmount IS NOT NULL');
            $charities = $q->result_array();
            echo 'OK'.PHP_EOL;

            foreach($charities as $charity) {
                $q = $this->db->query('select causeId, from_charity_navigator from CharityCharityCause where charityId = '.$charity['id']);
                $tmpCauses = $q->result_array();
                $currentCauses = [];
                $currentCausesFromCharityNavigator = [];
                foreach($tmpCauses as $cause) {
                    $currentCauses[$cause['causeId']] = $cause['causeId'];
                    $currentCausesFromCharityNavigator[$cause['causeId']] = $cause['from_charity_navigator'];
                }

                $matchingCauses = [];

                if ($mappings && preg_match_all($pattern, $charity['name']."\n".$charity['description'], $matches)) {
                    foreach($matches[0] as $match) {
                        if (!isset($mappings[strtoupper($match)])) {
                            throw new Exception($match . ' is not set.');
                        }

                        foreach($mappings[strtoupper($match)] as $mapping) {
                            if (!isset($matchingCauses[$mapping['cause_id']])) {
                                $matchingCauses[$mapping['cause_id']] = ['strong' => 0, 'weak' => 0];
                            }

                            if ($mapping['strength']) {
                                $matchingCauses[$mapping['cause_id']]['strong']++;
                            } else {
                                $matchingCauses[$mapping['cause_id']]['weak']++;
                            }
                        }
                    }
                }

                $determinedCauses = [];
                foreach($matchingCauses as $causeId => $matchingCause) {
                    if ($matchingCause['strong'] || $matchingCause['weak'] >= 2) {
                        $determinedCauses[$causeId] = $causeId;
                    }
                }

                foreach($determinedCauses as $determinedCause) {
                    if (!isset($currentCauses[$determinedCause])) {
                        $ret = $this->db->insert('CharityCharityCause', array(
                                'causeId' => $determinedCause,
                                'charityId' => $charity['id'],
                        ));
                        if (!$ret) {
                            throw new Exception('Failed inserting to CharityCharityCause where causeId = '.$determinedCause.' AND charityId = '.$charity['id'] .' '. $ret);
                        }
                        echo 'ADDED new cause with id: ' . $determinedCause . ' to charity with id: ' . $charity['id'] . PHP_EOL;
                    }
                }

                foreach($currentCauses as $currentCause) {
                    if (!isset($determinedCauses[$currentCause]) && !$currentCausesFromCharityNavigator[$currentCause]) {
                        $ret = $this->db->query('delete from CharityCharityCause where causeId = '.$currentCause.' AND charityId = '.$charity['id']);
                        if (!$ret) {
                            throw new Exception('Failed deleting from CharityCharityCause where causeId = '.$currentCause.' AND charityId = '.$charity['id'] .' '. $ret);
                        }
                        echo 'DELETED cause with id: ' . $currentCause . ' from charity with id: ' . $charity['id'] . PHP_EOL;
                    }
                }
            }
            $offset += $limit;
        } while($offset < $max);
    }

    public function notify_recurring_donations() {
        $today = new DateTime(date('Y-m-d 00:00:00'));
        $tomorrow = clone $today;
        $tomorrow->modify('+1 day');
        $day_after_tomorrow = clone $tomorrow;
        $day_after_tomorrow->modify('+1 day');

        /** @var RecurringDonation[] $recurring_donations */
        $recurring_donations = RecurringDonation::findBy(['notify' => 1]);

        foreach($recurring_donations as $recurring_donation) {
            if ($recurring_donation->getStatus() != 'Live') {
                continue;
            }
            if ($recurring_donation->getNextDate() === null) {
                continue;
            }
            $next_date = new DateTime($recurring_donation->getNextDate());
            if ($next_date < $tomorrow) {
                continue;
            }
            if ($next_date >= $day_after_tomorrow) {
                continue;
            }

            $email = $recurring_donation->getUser()->getEmail();
            $amount = $recurring_donation->getAmount();
            $npo_name = $recurring_donation->getNpoName();
            $recur_type = $recurring_donation->getRecurType();
            $next_date = $next_date->format('d-m-y');
            $name = $recurring_donation->getUser()->getName();
            $msg = <<<EMAIL
Hello {$name},

Your {$recur_type} recurring donation of \${$amount} to {$npo_name} is about to be sent again tomorrow ({$next_date})!

To manage your recurring donations, please visit https://www.giverhub.com/members

Best Regards
GiverHub, Inc.
EMAIL;

            mail($email, 'NOTIFICATION ABOUT RECURRING DONATION', $msg);
            echo 'notified ' . $email . ' about recurring_donation_id: ' . $recurring_donation->getId() . ' next_date: ' . $next_date . PHP_EOL;
        }
    }

    public function petition_trend_start($threshold = 3, $live = 0) {

        /** @var \Entity\UserPetitionSignature[] $signatures */
        $signatures = \Entity\UserPetitionSignature::findAll();

        $signatures_by_petition = [];
        foreach($signatures as $signature) {
            $signatures_by_petition[$signature->getPetitionId()][$signature->getSignedAtDt()->format('Y-m-d H:i:s')] = $signature;
        }

        foreach($signatures_by_petition as $petition_id => $signatures) {
            $count = count($signatures);
            if ($count > $threshold) {
                ksort($signatures);
                /** @var \Entity\UserPetitionSignature $signature */
                $signature = array_shift($signatures);
                $user = $signature->getUser();

                $existing_email_for_signature = EmailHistory::findOneBy(['user' => $user, 'type' => EmailHistory::TYPE_PETITION_TREND_START, 'extra' => $signature->getId()]);
                if (!$existing_email_for_signature) {
                    $body = "Congrats, you started a trend! After you signed this petition on GiverHub, ".($count-1)." more people signed it!<br/><br/>" .
                            $signature->getPetition()->getLink(true);

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From: GiverHub <admin@giverhub.com>" . "\r\n";

                    $recipients = $live ? [$user->getEmail()] : ['levineam@gmail.com', 'robban2005@gmail.com'];
                    foreach($recipients as $recipient) {
                        mail($recipient,
                         'You started a trend on GiverHub',
                         $body,
                         $headers );
                    }

                    $email_history = new \Entity\EmailHistory;
                    $email_history->setUser($user);
                    $email_history->setType(EmailHistory::TYPE_PETITION_TREND_START);
                    $email_history->setExtra($signature->getId());
                    $email_history->setDate(new \DateTime);
                    self::$em->persist($email_history);
                    self::$em->flush($email_history);
                }
            }
        }
    }
}
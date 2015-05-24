<?php
require_once(__DIR__ . '/Base_Controller.php');
use Entity\UrlCache;
use Entity\ZipCode;
use Entity\CharityCity;
use Entity\CharityState;
use Doctrine\ORM\EntityManager;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Charity_API
 *
 * intended to be run from the cli
 * http://ellislab.com/codeigniter/user-guide/general/cli.html
 */
class Charity_API extends \Base_Controller
{
    /** @var EntityManager */
    static public $em;

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

    public function guidestarbasic() {
        set_time_limit(0);

        $vvversionvvv = 1;
        $trim = function($str) { $str = preg_replace('#\xc2\xa0#', ' ', $str); return trim($str); };
        $em = self::$em;
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        $charityRepo = $em->getRepository('Entity\Charity');
        $stateRepo = $em->getRepository('Entity\CharityState');

        $logfile = fopen('guidestarbasic.txt', 'a');
        $log = function($msg, $ein = null, Entity\Charity $charity = null) use ($logfile) {
            $msgParts = array(date('[Y-m-d H:i:s]') . ' ' . $msg);
            if ($charity) {
                $msgParts[] = 'charity: id: '. $charity->getId() . ' ein: ' . $charity->getEin();
            }
            if ($ein) {
                $msgParts[] = 'ein: ' . $ein;
            }
            $str = join(' || ', $msgParts) . PHP_EOL;
            fputs($logfile, $str);
            echo $str;
        };

        $limit = 500;
        do {
            $this->db->like('url', 'guidestar');
            $this->db->where('version < ' . $vvversionvvv);
            $query = $this->db->get('UrlCache', $limit, 0);

            $results = $query->result();
            foreach($results as $row) {
                $id = $row->id;
                $url = $row->url;
                $data = $row->data;

                preg_match('#(\d\d)-(\d\d\d\d\d\d\d)$#', $url, $matches);

                if (!count($matches) == 3) {
                    $log('wrong match count when checking ein from url! id: ' . $id . ' c: ' . count($matches) . ' url: ' . $url);
                    $this->db->where('id', $row->id);
                    $this->db->update('UrlCache', array('version' => 200));
                    continue;
                }
                $ein = $matches[1] . $matches[2];

                if (!$data) {
                    $log('data is empty! id: ' . $id . ' url: ' . $url, $ein);
                    $this->db->where('id', $row->id);
                    $this->db->update('UrlCache', array('version' => 201));
                    continue;
                }

                $dom = new DOMDocument;
                if (!@$dom->loadHTML($data)) {
                    $log('failed to loadHTML id: ' . $id . ' url: ' . $url . ' data: ' . $data, $ein);
                    $this->db->where('id', $row->id);
                    $this->db->update('UrlCache', array('version' => 202));
                    continue;
                }

                $canlink = $dom->getElementById('ctl00_idCanonicalLink');
                if ($canlink && $canlink->getAttribute('href') == 'http://www.guidestar.org/Page404.aspx') {
                    $log('404, skipping', $ein);
                    $this->db->where('id', $row->id);
                    $this->db->update('UrlCache', array('version' => 203));
                    continue;
                }
                $x = new DOMXpath($dom);

                $els = $x->query("descendant-or-self::a[@name = 'contact']");
                if ($els->length != 1) {
                    $log('could not find contact element! length: ' . $els->length, $ein);
                    $this->db->where('id', $row->id);
                    $this->db->update('UrlCache', array('version' => 204));
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
                        $log('could not find charity name!', $ein);
                        $this->db->where('id', $row->id);
                        $this->db->update('UrlCache', array('version' => 205));
                        continue;
                    } else {
                        $charity = $charityRepo->findOneBy(array('ein' => $ein));
                        if (!$charity) {
                            $charity = new \Entity\Charity();
                            $charity->setCreatedAt(date('Y-m-d H:i:s'));
                        }
                        $charity->setEin($ein);
                        $charity->setName($charityName);

                        $prevName = $charity->getName();
                        if (preg_match('#^Also Known As:(.*)#', $prevName, $matches)) {
                            $charityName = trim($matches[1]);
                            $charity->setName($charityName);
                            $em->persist($charity);
                            $em->flush();
                            $em->clear();
                            echo PHP_EOL . $ein . ':' . $prevName . ' -> ' . $charityName . PHP_EOL;
                        }

                        $els = $x->query("descendant-or-self::a[@name = 'mission']");
                        if ($els->length != 1) {
                            $log('could not find mission element! length: ' . $els->length, $ein, $charity);
                        } else {
                            $parent = $els->item(0)->parentNode;
                            $set = false;
                            foreach($parent->childNodes as $node) {
                                if ($node->hasAttribute('class') && $node->getAttribute('class') == 'html-snippet') {
                                    $set = true;
                                    $charity->setDescription($trim($node->textContent));
                                    echo PHP_EOL . $ein . ':id:' . $charity->getId() . ':len:'. strlen($node->textContent) . PHP_EOL;
                                }
                            }
                            if (!$set) {
                                $log('No description found..', $ein, $charity);
                            }
                        }

                        $els = $x->query('descendant-or-self::dt | descendant-or-self::dd', $section);

                        $addr1 = null;
                        $addr2 = null;
                        $cityName = null;
                        $stateName = null;
                        $zipCode = null;
                        $email = null;
                        $phoneNum = null;
                        $webSite = null;
                        for($i = 0; $i < $els->length; $i++) {
                            /** @var DOMElement $el */
                            $el = $els->item($i);
                            $v = $trim($el->nodeValue);
                            if ($v == 'Physical Address:') {
                                $addressParts = $els->item($i+1)->childNodes;
                                $ps = array();
                                foreach($addressParts as $p) {
                                    if (!$p instanceof DOMText) { continue; /* found a br.. */ }
                                    $ps[] = $trim($p->nodeValue);
                                }
                                if (count($ps) != 3 && count($ps) != 2) {
                                    $log('unexpected length of address parts. ' . count($ps) . ' parts: ' . join('||', $ps), $ein);
                                } else {
                                    if (count($ps) == 3) {
                                        $addr1 = $ps[0];
                                        $addr2 = $ps[1];
                                        $p3 = $ps[2];
                                    } elseif (count($ps) == 2) {
                                        $addr1 = $ps[0];
                                        $p3 = $ps[1];
                                    }
                                    if (!preg_match('#(.*),(.*)(\d{5})$#s', $p3, $matches)) {
                                        $log('could not match city, state and zip. parts: ' . join('||', $ps). 'p3: ' . $p3, $ein);
                                    } else {
                                        $cityName = $trim($matches[1]);
                                        $stateName = $trim($matches[2]);
                                        $zipCode = $trim($matches[3]);
                                        if (!$stateName) {
                                            $log('empty state name in p3: ' . $p3, $ein);
                                        } else {
                                            $state = $stateRepo->findOneBy(array('name' => $stateName));
                                            if (!$state) {
                                                $log('could not find state: ' . $stateName, $ein);
                                            } else {
                                                if (!$cityName) {
                                                    $log('empty city name: p3: ' . $p3, $ein);
                                                } else {
                                                    $city = CharityCity::getOrCreateByNameAndState($em, $cityName, $state);
                                                    $charity->setStateId($state->getId());
                                                    $charity->setCityId($city->getId());
                                                    if ($addr1) {
                                                        $charity->setAddress($addr1);
                                                    }
                                                    if ($addr2) {
                                                        $charity->setAddress2($addr2);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if (preg_match('#.*@.*\..*#', $v, $matches)) {
                                $email = $trim($matches[0]);
                            }

                            if ($v == 'Telephone:') {
                                $phoneNum = $trim($els->item($i+1)->nodeValue);
                            }

                            if ($v == 'Web Address:') {
                                $webSite = $trim($els->item($i+1)->nodeValue);
                            }
                        }
                        if ($charity->getStateId() && $charity->getCityId()) {
                            $em->persist($charity);
                            $em->flush();
                            echo PHP_EOL . $charity->getId() . PHP_EOL;

                            if (!$email) {
                                $log('no email found',$ein,$charity);
                            } else {
                                $charity->setEmail($email);
                            }

                            if (!$phoneNum) {
                                $log('no phone', $ein, $charity);
                            } else {
                                $charity->setPhoneNumber($phoneNum);
                            }

                            if (!$webSite) {
                                $log('no website', $ein, $charity);
                            } else {
                                $charity->setWebSite($webSite);
                            }

                            $els = $x->query("descendant-or-self::a[@name = 'at-a-glance']");
                            if ($els->length != 1) {
                                $log('could not find at-a-glance element! length: ' . $els->length, $ein, $charity);
                            } else {
                                $section = $els->item(0)->parentNode;
                                $els = $x->query('descendant-or-self::dt | descendant-or-self::dd', $section);
                                $income = null;
                                $expenses = null;
                                $assets = null;
                                $liabilities = null;
                                for($i = 0; $i < $els->length; $i++) {
                                    /** @var DOMElement $el */
                                    $el = $els->item($i);
                                    $v = $trim($el->nodeValue);

                                    switch($v) {
                                        case 'Income:':
                                            $income = $trim($els->item($i+1)->nodeValue);
                                            break;
                                        case 'Expenses:':
                                            $expenses = $trim($els->item($i+1)->nodeValue);
                                            break;
                                        case 'Assets:':
                                            $assets = $trim($els->item($i+1)->nodeValue);
                                            break;
                                        case 'Liabilities:':
                                            $liabilities = $trim($els->item($i+1)->nodeValue);
                                            break;
                                    }
                                }

                                foreach(array('income','expenses','assets','liabilities') as $vvv) {
                                    if (!$$vvv) {
                                        $log('no '.$vvv, $ein, $charity);
                                    } else {
                                        $charity->setMeta($vvv, $$vvv);
                                    }
                                }
                            }

                            $divLinkYears = $dom->getElementById('divLinkYears');
                            if (!$divLinkYears) {
                                $log('could not find divLinkYears', $ein, $charity);
                            } else {
                                $yearLinks = $divLinkYears->getElementsByTagName('a');
                                $duplicateYears = array();
                                foreach($yearLinks as $yearLink) {
                                    /** @var DOMElement $yearLink */
                                    $year = $trim($yearLink->nodeValue);
                                    if (isset($duplicateYears[$year])) {
                                        $log('found duplicate year....?? ' . $year, $ein, $charity);
                                        continue;
                                    }
                                    $duplicateYears[$year] = true;
                                    if (!preg_match('#yearly_div_(\d+)#', $yearLink->getAttribute('onclick'), $matches)) {
                                        $log('year link year:'.$year.' didnt contain yearly_div.. onclick: ' . $yearLink->getAttribute('onclick'), $ein, $charity);
                                        continue;
                                    }


                                    $yearlyDiv = $dom->getElementById($matches[0]);
                                    if (!$yearlyDiv) {
                                        $log('could not get yearly div id: ' . $matches[0] . ' year: ' . $year);
                                        continue;
                                    }

                                    $revExpDiv = $dom->getElementById('rev_exp_div_'.$matches[1]);
                                    if (!$revExpDiv) {
                                        $log('could not get rev_exp_div id: ' . $matches[1] . ' year: ' . $year);
                                        continue;
                                    }

                                    $tables = $revExpDiv->getElementsByTagName('table');
                                    if ($tables->length != 4 && $tables->length != 3) {
                                        $log('wrong tables..' . $tables->length . ' ein: ' . $ein . ' url: ' . $url, $ein, $charity);
                                        continue;
                                    }
                                    foreach($tables as $nr => $table) {

                                        $caption = $table->getElementsByTagName('caption');
                                        if ($caption->length) {
                                            $tableName = $trim($caption->item(0)->nodeValue);
                                        } else {
                                            $prevSibling = $table->previousSibling;
                                            if ($prevSibling->tagName == 'p') {
                                                $tableName = $trim($prevSibling->nodeValue);
                                            }
                                        }
                                        switch($tableName) {
                                            case 'Revenue':
                                            case 'Expenses':
                                            case 'Functional Expenses':
                                                break;
                                            default:
                                                $log('could not find financial table name for year: '.$year . ' nr: '.$nr.' of '. $tables->length. ', found: ' . $tableName, $ein, $charity);
                                                continue;
                                        }


                                        /** @var DOMElement $table */
                                        $trs = $table->getElementsByTagName('tr');

                                        foreach($trs as $tr) {
                                            /** @var DOMElement $tr */
                                            $th = $tr->getElementsByTagName('th')->item(0);
                                            $td = $tr->getElementsByTagName('td')->item(0);

                                            if (!$th || !$th->childNodes->item(0)) {
                                                $log('table: ' . $nr . 'tablename: '.$tableName.' th was empty. year: ' . $year, $ein, $charity);
                                                continue;
                                            }
                                            $childNodes = $th->childNodes;
                                            $item0 = $childNodes->item(0);
                                            $childNodes = $item0->childNodes;

                                            if ($childNodes && $childNodes->length > 1) {
                                                $k = $tableName.'_'.$trim($th->childNodes->item(0)->childNodes->item(0)->nodeValue) . '_' . $year;
                                            } else {
                                                $k = $tableName.'_'.$trim($th->nodeValue) . '_' . $year;
                                            }
                                            $v = $trim($td->nodeValue);
                                            if ($k && $v) {
                                                $charity->setMeta($k,$v);
                                            } else {
                                                $log('table: ' . $nr . 'tablename: '.$tableName.' key: ' . $k . ' val: ' . $v . ' was empty. year: ' . $year, $ein, $charity);
                                            }
                                        }
                                    }
                                }
                            }

                            $divPeopleLinkYears = $dom->getElementById('divPeopleLinkYears');
                            if (!$divPeopleLinkYears) {
                                $log('could not find divPeopleLinkYears', $ein, $charity);
                            } else {
                                $duplicateYears = array();
                                $yearLinks = $divPeopleLinkYears->getElementsByTagName('a');

                                foreach($yearLinks as $yearLink) {
                                    /** @var DOMElement $yearLink */
                                    $year = $trim($yearLink->nodeValue);

                                    if (isset($duplicateYears[$year])) {
                                        $log('found duplicate year in people....?? ' . $year, $ein, $charity);
                                        continue;
                                    }
                                    $duplicateYears[$year] = true;

                                    if (!preg_match('#people_div_(\d+)#', $yearLink->getAttribute('onclick'), $matches)) {
                                        $log('year link year:'.$year.' didnt contain people_div.. onclick: ' . $yearLink->getAttribute('onclick'), $ein, $charity);
                                        continue;
                                    }

                                    $peopleDiv = $dom->getElementById($matches[0]);
                                    if (!$peopleDiv) {
                                        $log('could not get people div id: ' . $matches[0] . ' year: ' . $year);
                                        continue;
                                    }
                                    /** @var DOMElement $table */
                                    $table = $peopleDiv->getElementsByTagName('table')->item(0);
                                    if (!$table) {
                                        $log('Could not find table for people year: ' . $year, $ein, $charity);
                                        continue;
                                    }
                                    $trs = $table->getElementsByTagName('tr');
                                    foreach($trs as $n => $tr) {
                                        if ($n == 0) { continue; }

                                        $nameKey = 'people_'.$n.'_year_'.$year.'_name';
                                        $nameVal = $trim($tr->childNodes->item(0)->nodeValue);
                                        if ($nameKey && $nameVal) {
                                            $charity->setMeta($nameKey,$nameVal);
                                        } else {
                                            //$log('people name-key: ' . $nameKey . ' name-val: ' . $nameVal . ' was empty. year: ' . $year, $ein, $charity);
                                        }

                                        $titleKey = 'people_'.$n.'_year_'.$year.'_title';
                                        $titleVal = $trim($tr->childNodes->item(1)->nodeValue);

                                        if ($titleKey && $titleVal) {
                                            $charity->setMeta($titleKey,$titleVal);
                                        } else {
                                            //$log('people title-key: ' . $titleKey . ' title-val: ' . $titleVal . ' was empty. year: ' . $year, $ein, $charity);
                                        }

                                        $compKey = 'people_'.$n.'_year_'.$year.'_compensation';
                                        $compVal = $trim($tr->childNodes->item(2)->nodeValue);

                                        if ($compKey && $compVal) {
                                            $charity->setMeta($compKey,$compVal);
                                        } else {
                                            //$log('people comp-key: ' . $compKey . ' comp-val: ' . $compVal . ' was empty. year: ' . $year, $ein, $charity);
                                        }
                                    }
                                }
                            }

                            $missions = $els = $x->query("descendant-or-self::a[@name = 'mission']");
                            if (!$missions->length) {
                                $log('could not get mission statement start', $ein, $charity);
                            } else {
                                $statement = null;
                                foreach($missions as $mission) {
                                    /** @var DOMElement $mission */
                                    if ($mission->nextSibling && $mission->nextSibling->nextSibling) {
                                        $div = $mission->nextSibling->nextSibling;
                                        if ($div->tagName == 'div' && $div->getAttribute('class') == 'html-snippet') {
                                            $statement = $trim($div->nodeValue);
                                        }
                                    }
                                }
                                if (!$statement) {
                                    $log('could not find mission statement.', $ein, $charity);
                                } else {
                                    $charity->setMeta('mission_statement', $statement);
                                }
                            }

                            $this->db->where('id', $row->id);
                            $this->db->update('UrlCache', array('version' => $vvversionvvv));
                            $em->persist($charity);
                            $em->flush();
                            unset($charity);
                            $em->clear();
                        } else {
                            $log('did not set city and state so cannot save...', $ein);
                            $this->db->where('id', $row->id);
                            $this->db->update('UrlCache', array('version' => $vvversionvvv));
                        }
                    }
                }
            }

        } while($results);
    }

    public function guidestarchiefexec() {
        set_time_limit(0);
        $vvversionvvv = 13;
        $trim = function($str) { $str = preg_replace('#\xc2\xa0#', ' ', $str); return trim($str); };
        $em = self::$em;
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $charityRepo = $em->getRepository('Entity\Charity');


        $logfile = fopen('guidestarchiefexec.txt', 'a');
        $log = function($msg, $ein = null, Entity\Charity $charity = null) use ($logfile) {
            $msgParts = array(date('[Y-m-d H:i:s]') . ' ' . $msg);
            if ($charity) {
                $msgParts[] = 'charity: id: '. $charity->getId() . ' ein: ' . $charity->getEin();
            }
            if ($ein) {
                $msgParts[] = 'ein: ' . $ein;
            }
            $str = join(' || ', $msgParts) . PHP_EOL;
            fputs($logfile, $str);
            echo $str;
        };

        $limit = 10;
        do {
            $this->db->like('url', 'guidestar');
            $this->db->where('version < ' . $vvversionvvv);
            $query = $this->db->get('UrlCache', $limit, 0);

            $results = $query->result();
            foreach($results as $row) {
                $id = $row->id;
                $url = $row->url;
                $data = $row->data;

                preg_match('#(\d\d)-(\d\d\d\d\d\d\d)$#', $url, $matches);

                if (!count($matches) == 3) {
                    $log('wrong match count when checking ein from url! id: ' . $id . ' c: ' . count($matches) . ' url: ' . $url);
                    $this->db->where('id', $row->id);
                    $this->db->update('UrlCache', array('version' => 210));
                    continue;
                }
                $ein = $matches[1] . $matches[2];

                if (!$data) {
                    $log('data is empty! id: ' . $id . ' url: ' . $url, $ein);
                    $this->db->where('id', $row->id);
                    $this->db->update('UrlCache', array('version' => 211));
                    continue;
                }

                $dom = new DOMDocument;
                if (!@$dom->loadHTML($data)) {
                    $log('failed to loadHTML id: ' . $id . ' url: ' . $url . ' data: ' . $data, $ein);
                    $this->db->where('id', $row->id);
                    $this->db->update('UrlCache', array('version' => 212));
                    continue;
                }

                $canlink = $dom->getElementById('ctl00_idCanonicalLink');
                if ($canlink && $canlink->getAttribute('href') == 'http://www.guidestar.org/Page404.aspx') {
                    $log('404, skipping', $ein);
                    $this->db->where('id', $row->id);
                    $this->db->update('UrlCache', array('version' => 213));
                    continue;
                }
                $x = new DOMXpath($dom);

                $els = $x->query("descendant-or-self::a[@name = 'contact']");
                if ($els->length != 1) {
                    $log('could not find contact element! length: ' . $els->length, $ein);
                    $this->db->where('id', $row->id);
                    $this->db->update('UrlCache', array('version' => 214));
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
                        $log('could not find charity name!', $ein);
                        $this->db->where('id', $row->id);
                        $this->db->update('UrlCache', array('version' => 215));
                        continue;
                    } else {
                        /** @var Entity\Charity $charity */
                        $charity = $charityRepo->findOneBy(array('ein' => $ein));
                        if (!$charity) {
                            $log('could not open charity!', $ein);
                            $this->db->where('id', $row->id);
                            $this->db->update('UrlCache', array('version' => 216));
                            continue;
                        }


                        $els = $x->query("descendant-or-self::a[@name = 'chief-exec']");
                        if ($els->length != 1) {
                            $log('could not find chief-exec element! length: ' . $els->length, $ein, $charity);
                            $this->db->where('id', $row->id);
                            $this->db->update('UrlCache', array('version' => 217));
                            unset($charity);
                            continue;
                        }
                        $parent = $els->item(0)->parentNode;
                        $execName = false;
                        foreach($parent->childNodes as $childNode) {
                            /** @var DOMElement $childNode */
                            if ($childNode->tagName == 'p') {
                                $execName = $trim($childNode->textContent);
                                $execName = str_replace(array("\n", "\r"), '', $execName);
                                $execName = preg_replace('#\s+#', ' ', $execName);
                                break;
                            }
                        }
                        if (!$execName) {
                            $log('could not find exec-name', $ein, $charity);
                            $this->db->where('id', $row->id);
                            $this->db->update('UrlCache', array('version' => 218));
                            unset($charity);
                            continue;
                        } else {
                            $log('found exec name: ' . $execName, $ein, $charity);
                            $execSalary = $charity->prepareChiefExecSalary($execName);
                            if ($execSalary !== null) {
                                $log('found exec salary: ' . $execSalary);
                            } else {
                                $log('found no salary');
                            }
                            $em->persist($charity);
                            $em->flush($charity);
                            $em->clear();
                        }

                        $this->db->where('id', $row->id);
                        $this->db->update('UrlCache', array('version' => $vvversionvvv));

                        unset($charity);
                    }
                }
            }
        } while($results);
    }

    public function percentile_prepare() {
        set_time_limit(0);
        $trim = function($str) { $str = preg_replace('#\xc2\xa0#', ' ', $str); return trim($str); };

        $CI =& get_instance();
        $CI->db->select_max('id');
        $CI->db->where('highestPaidOfficerCompensation IS NOT NULL OR
         programServicesAmount IS NOT NULL OR
         fundRaisingCostsAmount IS NOT NULL OR
         adminExpensesAmount IS NOT NULL OR
         revenueAmount IS NOT NULL');

        $query = $CI->db->get('Charity');
        $results = $query->result_array();
        $offset = $results ? $results[0]['id'] : 0;
        echo 'Starting with offset ' . $offset . PHP_EOL;
        $limit = 5000;

        $em = self::$em;
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $charityRepo = $em->getRepository('Entity\Charity');

        do {
            echo $offset;
            if (isset($charities)) {
                unset($charities);
            }
            /** @var \Entity\Charity[] $charities */
            $charities = $charityRepo->findBy(array(),null,$limit,$offset);
            $offset += $limit;
            echo PHP_EOL;
            foreach($charities as $charity) {
                $charity->percentilePrepare();
                $em->persist($charity);
                echo '.';
            }
            $em->flush();
            $em->clear();
            echo PHP_EOL;
        } while($charities);
    }


    public function insert_cause_keywords() {
        set_time_limit(0);

        $categories = array(
            /*
             * ANIMALS--->Let's call these "Types"
            Wildlife Conservation-->Let's call these "Sub-types"
            -*endangered species, wildlife-->these are keywords, if the keyword has an asterisk next to it then whatever charity has that keyword should automatically be classified as the type and sub-type that the keyword is in. So in this case, if a charity's name or mission statement has the phrase "endangered species" in it, then that charity would fall under the sub-type "Wildlife Conservation" and the type "Animals"
            Animal Rights, Welfare, and Services
            -*animal rights, *animal welfare, *animal services, *animal
            Zoos and Aquariums
            -*zoo, *aquarium
             */
            array(
                'id' => 1,
                'name' => 'Animals',
                'causes' => array(
                    array(
                        'id' => 1,
                        'name' => 'Wildlife Conservation',
                        'keywords' => array(
                            'endangered species',
                        )
                    ),
                    array(
                        'id' => 2,
                        'name' => 'Animal Rights, Welfare, and Services',
                        'keywords' => array(
                            'animal rights', 'animal welfare', 'animal services', 'animal',
                        )
                    ),
                    array(
                        'id' => 32,
                        'name' => 'Zoos and Aquariums',
                        'keywords' => array(
                            'zoo', 'aquarium',
                        )
                    )
                ),
            ),
            /*
             *
             * ARTS, CULTURE, HUMANITIES-->Change to HUMANITIES
                Museums
                -museum
                Performing Arts
                -*opera, theater, theatre, *performing arts
                Public Broadcasting and Media
                -public broadcasting
                Libraries, Historical Societies and Landmark Preservation
                -library, historical society, landmark preservation
             */
            array(
                'id' => 2,
                'name' => 'Humanities',
                'causes' => array(
                    array(
                        'id' => 3,
                        'name' => 'Museums',
                        'keywords' => array(
                        )
                    ),
                    array(
                        'id' => 4,
                        'name' => 'Performing Arts',
                        'keywords' => array(
                            'opera', 'performing arts',
                        )
                    ),
                    array(
                        'id' => 5,
                        'name' => 'Public Broadcasting and Media',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 6,
                        'name' => 'Libraries, Historical Societies and Landmark Preservation',
                        'keywords' => array(

                        )
                    )
                ),
            ),
            /*
             *
            EDUCATION
            Other Education Programs and Services
            -education program, education services
            Private Elementary and Secondary Schools
            -school
            Universities, Graduate Schools, and Technological Institutes
            -*university, graduate school, technological institute, *college
            Private Liberal Arts Colleges
             */
            array(
                'id' => 3,
                'name' => 'Education',
                'causes' => array(
                    array(
                        'id' => 33,
                        'name' => 'Private Liberal Arts Colleges',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 9,
                        'name' => 'Universities, Graduate Schools, and Technological Institutes',
                        'keywords' => array(
                            'university', 'college',
                        )
                    ),
                    array(
                        'id' => 8,
                        'name' => 'Private Elementary and Secondary Schools',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 7,
                        'name' => 'Other Education Programs and Services',
                        'keywords' => array(

                        )
                    )
                ),
            ),
            /*
             *
             * ENVIRONMENT
            Botanical Gardens, Parks, and Nature Centers
            -*botanical garden, park, nature center
            Environmental Protection and Conservation
            -*environmental protection, conservation
             */
            array(
                'id' => 4,
                'name' => 'Environment',
                'causes' => array(
                    array(
                        'id' => 10,
                        'name' => 'Botanical Gardens, Parks, and Nature Centers',
                        'keywords' => array(
                            'botanical garden',
                        )
                    ),
                    array(
                        'id' => 11,
                        'name' => 'Environmental Protection and Conservation',
                        'keywords' => array(
                            'environmental protection',
                        )
                    )
                ),
            ),
            /*
             *
             * HEALTH
            Treatment and Prevention Services
            -treatment, drugs, drug, prevention services, alcohol, *cure
            Diseases, Disorders, and Disciplines
            -*disease, *disorder, *cancer, *aids, hiv, *eating disorders, *breast cancer, *prostate cancer, *lung cancer
            Medical Research
            -*medical research, *cancer research, *hiv research, *aids research
            Patient and Family Support
             */
            array(
                'id' => 5,
                'name' => 'Health',
                'causes' => array(
                    array(
                        'id' => 34,
                        'name' => 'Patient and Family Support',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 12,
                        'name' => 'Treatment and Prevention Services',
                        'keywords' => array(
                            'cure',
                        )
                    ),
                    array(
                        'id' => 14,
                        'name' => 'Medical Research',
                        'keywords' => array(
                            'medical research', 'cancer research', 'hiv research', 'aids research',
                        )
                    ),
                    array(
                        'id' => 13,
                        'name' => 'Diseases, Disorders, and Disciplines',
                        'keywords' => array(
                            'disease', 'disorder', 'cancer', 'aids', 'eating disorders', 'breast cancer', 'prostate cancer', 'lung cancer',
                        )
                    ),
                ),
            ),
            /*
             * HUMAN SERVICES-->Change to PEOPLE
            Multipurpose Human Service Organizations --> change to Human Services
            -volunteers, homelessness, support
            Youth Development, Shelter, and Crisis Services
            -youth, children, young adults
            Children's and Family Services
            -children, family, families, services
            Food Banks, Food Pantries, and Food Distribution
            -food bank, food pantry, food distribution
            Homeless Services
            -*homelessness, *homeless
            Social Services
            -services, serving, service, hope
             */
            array(
                'id' => 6,
                'name' => 'People',
                'causes' => array(
                    array(
                        'id' => 15,
                        'name' => 'Human Services',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 29,
                        'name' => 'Social Services',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 28,
                        'name' => 'Homeless Services',
                        'keywords' => array(
                            'homelessness', 'homeless',
                        )
                    ),
                    array(
                        'id' => 17,
                        'name' => 'Children\'s and Family Services',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 16,
                        'name' => 'Youth Development, Shelter, and Crisis Services',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 18,
                        'name' => 'Food Banks, Food Pantries, and Food Distribution',
                        'keywords' => array(

                        )
                    ),
                ),
            ),
            /*
             * INTERNATIONAL
            International Peace, Security, and Affairs
            -peace, international
            Development and Relief Services
            -developing world, hunger, poverty, poor
            Humanitarian Relief Supplies
            -*humanitarian, relief
            Foreign Charity Support Organizations
             */
            array(
                'id' => 7,
                'name' => 'International',
                'causes' => array(
                    array(
                        'id' => 19,
                        'name' => 'International Peace, Security, and Affairs',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 31,
                        'name' => 'Foreign Charity Support Organizations',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 20,
                        'name' => 'Development and Relief Services',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 30,
                        'name' => 'Humanitarian Relief Supplies',
                        'keywords' => array(
                            'humanitarian',
                        )
                    )
                ),
            ),
            /*
             * PUBLIC BENEFIT-->Change to PUBLIC
            Advocacy and Civil Rights
            -advocacy, civil rights
            Community Foundations
            -*community foundation
            Fundraising Organizations
            -fundraising
            Research and Public Policy Institutions
            -research, public policy
            Community and Housing Development
             */
            array(
                'id' => 8,
                'name' => 'Public',
                'causes' => array(
                    array(
                        'id' => 22,
                        'name' => 'Community Foundations',
                        'keywords' => array(
                            'community foundation',
                        )
                    ),
                    array(
                        'id' => 27,
                        'name' => 'Community and Housing Development',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 21,
                        'name' => 'Advocacy and Civil Rights',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 23,
                        'name' => 'Fundraising Organizations',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 24,
                        'name' => 'Research and Public Policy Institutions',
                        'keywords' => array(

                        )
                    ),
                ),
            ),
            /*
             * RELIGION-->change to "FAITH"
            Religious Media and Broadcasting (remove)
            Religious Activities (remove)
            ADD TO RELIGION
            Christianity
            -church, christian, evangelical, presbyterian, protestant, catholic
            Islam
            -*mosque, allah, *koran, *quran
            Judaism
            -temple, *jewish, *torah
            Hinduism
            -*hindu
            Buddhism
            -*buddhist
            Non-religious
            -*secular, *atheist, *agnostic
            Other
             */
            array(
                'id' => 9,
                'name' => 'Faith',
                'causes' => array(
                    array(
                        'id' => 35,
                        'name' => 'Christianity',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 36,
                        'name' => 'Islam',
                        'keywords' => array(
                            'mosque', 'koran', 'quran',
                        )
                    ),
                    array(
                        'id' => 37,
                        'name' => 'Judaism',
                        'keywords' => array(
                            'jewish', 'torah',
                        )
                    ),
                    array(
                        'id' => 38,
                        'name' => 'Hinduism',
                        'keywords' => array(
                            'hindu',
                        )
                    ),
                    array(
                        'id' => 39,
                        'name' => 'Buddhism',
                        'keywords' => array(
                            'buddhist',
                        )
                    ),
                    array(
                        'id' => 40,
                        'name' => 'Non-religious',
                        'keywords' => array(
                            'secular', 'atheist', 'agnostic',
                        )
                    ),
                    array(
                        'id' => 41,
                        'name' => 'Other',
                        'keywords' => array(

                        )
                    ),
                ),
            ),
            array(
                'id' => 10,
                'name' => 'Politics',
                'causes' => array(
                    array(
                        'id' => 42,
                        'name' => 'Democrat',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 43,
                        'name' => 'Republican',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 44,
                        'name' => 'Libertarian',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 45,
                        'name' => 'Green Party',
                        'keywords' => array(

                        )
                    ),
                    array(
                        'id' => 46,
                        'name' => 'Tea Party',
                        'keywords' => array(

                        )
                    ),
                ),
            ),
        );

        $q = $this->db->query('select id,name from CharityCause');
        $tmp_causes = $q->result_array();
        $causes = [];
        foreach($tmp_causes as $cause) {
            $causes[strtoupper($cause['name'])] = $cause['id'];
        }

        foreach($categories as $category) {
            echo 'Category: ' . $category['name'] . ' id: ' . $category['id'] . PHP_EOL;
            foreach($category['causes'] as $cause) {
                echo 'Cause: ' . $cause['name'] . ' id: ' . $cause['id'] . PHP_EOL;
                if (!isset($causes[strtoupper($cause['name'])])) {
                    throw new Exception(print_r($cause, true));
                }
                $cause['id'] = $causes[strtoupper($cause['name'])];

                foreach($cause['keywords'] as $keyword) {
                    $key = \Entity\CharityCauseKeyword::findOneBy(['charity_cause_id' => $cause['id'], 'keyword' => $keyword]);
                    if ($key) {
                        continue;
                    }
                    $key = new \Entity\CharityCauseKeyword();
                    $key->setCharityCauseId($cause['id']);
                    $key->setKeyword($keyword);
                    $key->setStrength(0);
                    self::$em->persist($key);
                    self::$em->flush($key);
                }
            }
        }
    }

    public function scrape_cause_names_from_charity_names_and_descs() {
        set_time_limit(0);

        $this->db->select('id,name');
        $q = $this->db->get('CharityCause');
        $tmpCauses = $q->result_array();

        $causes = array();
        foreach($tmpCauses as $cause) {
            $causes[strtoupper($cause['name'])] = $cause['id'];
        }

        $offset = 0;
        $limit = 1000;

        $pattern = '#\b('.join('|',array_keys($causes)).')\b#i';

        do {
            echo 'Getting ' . $limit . ' from ' . $offset .'...';
            $this->db->select('id,name,description');
            $q = $this->db->get('Charity', $limit, $offset);
            $charities = $q->result_array();
            echo 'OK'.PHP_EOL;

            foreach($charities as $charity) {
                if (preg_match_all($pattern, $charity['name']."\n".$charity['description'], $matches)) {
                    echo 'y';
                    foreach($matches[0] as $match) {
                        if (!isset($causes[strtoupper($match)])) {
                            throw new Exception($match . ' is not set.');
                        }
                        $causeId = $causes[strtoupper($match)];
                        $this->db->select('count(*) AS cnt');
                        $this->db->where('causeId', $causeId);
                        $this->db->where('charityId', $charity['id']);
                        $q2 = $this->db->get('CharityCharityCause');
                        $res = $q2->result_array();
                        $count = $res[0]['cnt'];
                        if ($count) {
                            echo 'x';
                        } else {
                            $ret = $this->db->insert('CharityCharityCause', array(
                                                                             'causeId' => $causeId,
                                                                             'charityId' => $charity['id'],
                                                                        ));
                            if (!$ret) {
                                throw new Exception('Failed inserting because something..' . $ret);
                            }
                            echo 'i';
                        }
                    }
                } else {
                    echo 'n';
                }
            }
            echo PHP_EOL;
            $offset += $limit;
        } while($charities);
    }

    public function keyword_scraper() {
        set_time_limit(0);
        ini_set('memory_limit', '1G');
        $keywords = array(
            'endangered species',            'wildlife',            'animal rights',            'animal welfare',
            'animal services',            'zoo',            'aquarium',            'museum',            'opera',
            'theater',            'theatre',            'performing arts',            'public broadcasting',
            'library',            'historical society',            'landmark preservation',            'education program',
            'education services',            'school',            'university',            'graduate school',
            'technological institute',            'botanical garden',            'park',            'nature center',
            'environmental protection',            'conservation',            'treatment',            'drugs',
            'drug',            'prevention services',            'alcohol',            'disease',            'disorder',
            'cancer',            'aids',            'hiv',            'eating disorders',            'medical research',
            'cancer research',            'hiv research',            'aids research',            'volunteers',
            'homelessness',            'support',            'youth',            'children',            'young adults',
            'children',            'family',            'families',            'services',            'food bank',
            'food pantry',            'food distribution',            'homelessness',            'homeless',
            'services',            'serving',            'service',            'hope',            'peace',
            'international',            'developing world',            'hunger',            'poverty',
            'poor',            'humanitarian',            'relief',            'advocacy',            'civil rights',
            'community foundation',            'fundraising',            'research',            'public policy',
            'church',            'christian',            'evangelical',            'presbyterian',
            'protestant',            'catholic',            'mosque',            'allah',            'koran',
            'quran',            'temple',            'jewish',            'torah',            'hindu',
            'buddhist',            'secular',            'atheist',            'agnostic',            'Democrat',
            'Republican',            'Libertarian',            'Green Party',            'Tea Party',
            'church',            'christian',            'evangelical',            'presbyterian',
            'protestant',            'catholic',            'mosque',            'allah',            'koran',
            'judaism',            'jewish',            'temple',            'torah',
            'animal',
        );

        $exists = [];
        $doesNotExist = [];
        foreach($keywords as $keyword) {
            $q = $this->db->query('select id from charity_cause_keyword where keyword = \''.$keyword.'\'');
            $rows = $q->result_array();
            if (count($rows)) {
                $exists[] = $keyword;
            } else {
                $doesNotExist[] = $keyword;
            }
        }
        echo 'mapped: ' . count($exists) . PHP_EOL;
        print_r($exists);
        echo 'not mapped: ' . count($doesNotExist) . PHP_EOL;
        print_r($doesNotExist);
        die;

        $this->db->select('id,name,description');

        $offset = 0;
        $limit = 1000;

        $pattern = '#\b('.join('|',$keywords).')\b#i';

        do {
            echo 'Getting ' . $limit . ' from ' . $offset .'...';
            $q = $this->db->get('Charity', $limit, $offset);
            $charities = $q->result_array();
            echo 'OK'.PHP_EOL;

            foreach($charities as $charity) {
                if (preg_match_all($pattern, $charity['name']."\n".$charity['description'], $matches)) {
                    echo 'y';
                    foreach(array_unique($matches[0]) as $match) {
                        $match = ucfirst($match);
                        $this->db->select('count(*) AS cnt');
                        $this->db->where('keyword_name', $match);
                        $this->db->where('charity_id', $charity['id']);
                        $q2 = $this->db->get('charity_keyword');
                        $res = $q2->result_array();
                        $count = $res[0]['cnt'];
                        if ($count) {
                            echo 'x';
                        } else {
                            $ret = $this->db->insert('charity_keyword', array(
                                                                      'keyword_name' => $match,
                                                                      'charity_id' => $charity['id'],
                                                                      'user_id' => 99999,
                                                                      'date' => date('Y-m-d'),
                                                                 ));
                            if (!$ret) {
                                throw new Exception('Failed inserting because something..' . $ret);
                            }
                            echo 'i';
                        }
                    }
                } else {
                    echo 'n';
                }
            }
            echo PHP_EOL;
            $offset += $limit;
        } while($charities);
    }

    public function calculate_overall_score2() {
        set_time_limit(0);

        /*
         * programServicesPercentile
         * overallEfficiencyPercentile
         * executiveProductivityPercentile
         */
        $sql = "
            UPDATE Charity
            SET overallScore = IF(
                programServicesPercentile IS NULL OR
                overallEfficiencyPercentile IS NULL,
                NULL,
                IF(
                    executiveProductivityPercentile IS NOT NULL,
                    (programServicesPercentile+IF(overallEfficiency>2,50,overallEfficiencyPercentile)+executiveProductivityPercentile)/3,
                    IF(
                        executivePay = 0,
                        (programServicesPercentile+IF(overallEfficiency>2,50,overallEfficiencyPercentile)+50)/3,
                        NULL
                    )
                )
            )
        ";
    }

    public function calculate_new_stuff() {
        set_time_limit(0);
        $CI =& get_instance();
        $CI->db->select_max('id');
        $CI->db->where('overallEfficiency IS NOT NULL OR fundraisingEffectiveness IS NOT NULL OR executiveProductivity IS NOT NULL');

        $query = $CI->db->get('Charity');
        $results = $query->result_array();
        $offset = $results ? $results[0]['id'] : 0;
        $offset = 0; //$results ? $results[0]['id'] : 0;
        echo 'Starting with offset ' . $offset . PHP_EOL;
        $limit = 6000;

        $em = self::$em;
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $charityRepo = $em->getRepository('Entity\Charity');

        do {
            echo $offset;
            if (isset($charities)) {
                unset($charities);
            }
            /** @var \Entity\Charity[] $charities */
            $charities = $charityRepo->findBy(array(),null,$limit,$offset);
            $offset += $limit;
            echo PHP_EOL;
            foreach($charities as $charity) {
                $charity->calculateOverallEfficiencyAndFundraisingEffectivenessAndExecutiveProductivity();
                $em->persist($charity);
                echo '.';
            }
            $em->flush();
            $em->clear();
            echo PHP_EOL;
        } while($charities);
    }

    public function get_graphdata() {
        $db = $this->db;
        $offset = 0;

        $out = fopen('revenue.dat', 'w');
        do {
            $db->select('revenueAmount');
            $db->order_by('revenueAmount');
            $db->where('revenueAmount is not null and revenueAmount <> 0');
            $query = $db->get('Charity', 1, $offset);
            $results = $query->result_array();

            if ($results) {
                fprintf($out, $offset . ' ' . $results[0]['revenueAmount'] . PHP_EOL);
                echo $offset . PHP_EOL;
            }
            $offset += 1000;
        } while($results);
    }

    public function set_executive_pay() {
        set_time_limit(0);

        $CI =& get_instance();
        $CI->db->select_max('id');
        $CI->db->where('executivePay IS NOT NULL');

        $query = $CI->db->get('Charity');
        $results = $query->result_array();
        $offset = $results ? $results[0]['id'] : 0;
        //$offset = 0; //$results ? $results[0]['id'] : 0;
        echo 'Starting with offset ' . $offset . PHP_EOL;
        $limit = 1000;

        $em = self::$em;
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $charityRepo = $em->getRepository('Entity\Charity');

        do {
            echo $offset;
            if (isset($charities)) {
                unset($charities);
            }
            /** @var \Entity\Charity[] $charities */
            $charities = $charityRepo->findBy(array(),null,$limit,$offset);
            $offset += $limit;
            echo PHP_EOL;
            foreach($charities as $charity) {
                $charity->calculateExecutivePay();
                $em->persist($charity);
                echo '.';
            }
            $em->flush();
            $em->clear();
            echo PHP_EOL;
        } while($charities);

    }

    public function set_exec_prod_and_fundraising_eff_percentile2() {
        set_time_limit(0);

        $mappings = array(
            'executiveProductivity' => array('col' => 'executiveProductivityPercentile', 'order' => 'ASC'),
            'fundraisingEffectiveness' => array('col' => 'fundraisingEffectivenessPercentile', 'order' => 'ASC'),
        );
        foreach($mappings as $amountCol => $info) {
            echo 'Setting 0 for ' . $amountCol . '...';
            $this->db->where($amountCol . ' = 0');
            $this->db->update('Charity', array($info['col'] => $info['order'] == 'DESC' ? 100 : 1));
            echo 'ok' . PHP_EOL;
        }

        foreach($mappings as $amountCol => $info) {
            $this->db->where($where = $amountCol . ' IS NOT NULL AND ' . $amountCol . ' <> 0');
            $this->db->from('Charity');
            echo 'Counting total for ' . $amountCol . '...';
            $total = $this->db->count_all_results();
            echo $total . PHP_EOL;

            $this->db->select('group_concat(id) as ids');
            $this->db->where($where);
            $this->db->from('Charity');
            $this->db->group_by($amountCol);
            $this->db->order_by($amountCol, $info['order']);
            echo 'Selecting ... ';
            $query = $this->db->get();
            $results = $query->result_array();
            echo count($results) . ' rows.' .PHP_EOL;
            $processed = 0;
            echo 'Processing ...';
            foreach($results as $row) {
                $ids = explode(',', $row['ids']);
                $thisPositionCount = count($ids);
                $processed += $thisPositionCount;

                $betterRanked = $total - $processed;

                $totalMinusSelf = $total - $thisPositionCount;
                if ($totalMinusSelf == 0) {
                    $percentile = 100;
                } else {
                    $percentile = 100 - ceil(($betterRanked / $totalMinusSelf) * 100);
                }
                if ($percentile == 0) {
                    $percentile = 1;
                }
                echo $percentile . ' ';
                $this->db->where_in('id', $ids);
                $this->db->update('Charity', array($info['col'] => $percentile));
            }
            echo PHP_EOL;
        }
    }

    public function set_revenue_percentile2() {
        set_time_limit(0);

        $mappings = array(
            'revenueAmount' => array('col' => 'revenuePercentile', 'order' => 'ASC'),
        );
        foreach($mappings as $amountCol => $info) {
            echo 'Setting 0 for ' . $amountCol . '...';
            $this->db->where($amountCol . ' = 0');
            $this->db->update('Charity', array($info['col'] => $info['order'] == 'DESC' ? 100 : 1));
            echo 'ok' . PHP_EOL;
        }

        foreach($mappings as $amountCol => $info) {
            $this->db->where($where = $amountCol . ' IS NOT NULL AND ' . $amountCol . ' <> 0');
            $this->db->from('Charity');
            echo 'Counting total for ' . $amountCol . '...';
            $total = $this->db->count_all_results();
            echo $total . PHP_EOL;

            $this->db->select('group_concat(id) as ids');
            $this->db->where($where);
            $this->db->from('Charity');
            $this->db->group_by($amountCol);
            $this->db->order_by($amountCol, $info['order']);
            echo 'Selecting ... ';
            $query = $this->db->get();
            $results = $query->result_array();
            echo count($results) . ' rows.' .PHP_EOL;
            $processed = 0;
            echo 'Processing ...';
            foreach($results as $row) {
                $ids = explode(',', $row['ids']);
                $thisPositionCount = count($ids);
                $processed += $thisPositionCount;

                $betterRanked = $total - $processed;

                $totalMinusSelf = $total - $thisPositionCount;
                if ($totalMinusSelf == 0) {
                    $percentile = 100;
                } else {
                    $percentile = 100 - ceil(($betterRanked / $totalMinusSelf) * 100);
                }
                if ($percentile == 0) {
                    $percentile = 1;
                }
                echo $percentile . ' ';
                $this->db->where_in('id', $ids);
                $this->db->update('Charity', array($info['col'] => $percentile));
            }
            echo PHP_EOL;
        }
    }

    public function set_overallscore_percentile2() {
        set_time_limit(0);

        $mappings = array(
            'overallScore' => array('col' => 'overallScorePercentile', 'order' => 'ASC'),
        );
        foreach($mappings as $amountCol => $info) {
            echo 'Setting 0 for ' . $amountCol . '...';
            $this->db->where($amountCol . ' = 0');
            $this->db->update('Charity', array($info['col'] => $info['order'] == 'DESC' ? 100 : 1));
            echo 'ok' . PHP_EOL;
        }

        foreach($mappings as $amountCol => $info) {
            $this->db->where($where = $amountCol . ' IS NOT NULL AND ' . $amountCol . ' <> 0');
            $this->db->from('Charity');
            echo 'Counting total for ' . $amountCol . '...';
            $total = $this->db->count_all_results();
            echo $total . PHP_EOL;

            $this->db->select('group_concat(id) as ids');
            $this->db->where($where);
            $this->db->from('Charity');
            $this->db->group_by($amountCol);
            $this->db->order_by($amountCol, $info['order']);
            echo 'Selecting ... ';
            $query = $this->db->get();
            $results = $query->result_array();
            echo count($results) . ' rows.' .PHP_EOL;
            $processed = 0;
            echo 'Processing ...';
            foreach($results as $row) {
                $ids = explode(',', $row['ids']);
                $thisPositionCount = count($ids);
                $processed += $thisPositionCount;

                $betterRanked = $total - $processed;

                $totalMinusSelf = $total - $thisPositionCount;
                if ($totalMinusSelf == 0) {
                    $percentile = 100;
                } else {
                    $percentile = 100 - ceil(($betterRanked / $totalMinusSelf) * 100);
                }
                if ($percentile == 0) {
                    $percentile = 1;
                }
                echo $percentile . ' ';
                $this->db->where_in('id', $ids);
                $this->db->update('Charity', array($info['col'] => $percentile));
            }
            echo PHP_EOL;
        }
    }

    public function set_percentile2() {
        set_time_limit(0);

        $mappings = array(
            'highestPaidOfficerCompensation' => array('col' => 'highestPaidOfficerCompensationPercentile', 'order' => 'ASC'),
            'programServicesAmount' => array('col' => 'programServicesPercentile', 'order' => 'ASC'),
            'fundRaisingCostsAmount' => array('col' => 'fundRaisingCostsPercentile', 'order' => 'ASC'),
            'overallEfficiency' => array('col' => 'overallEfficiencyPercentile', 'order' => 'ASC'),
            'fundraisingEffectiveness' => array('col' => 'fundraisingEffectivenessPercentile', 'order' => 'ASC'),
            'executiveProductivity' => array('col' => 'executiveProductivityPercentile', 'order' => 'ASC'),
            'chiefExecSalary' => array('col' => 'chiefExecSalaryPercentile', 'order' => 'ASC'),
            'revenueAmount' => array('col' => 'revenuePercentile', 'order' => 'ASC'),
        );
        foreach($mappings as $amountCol => $info) {
            echo 'Setting 0 for ' . $amountCol . '...';
            $this->db->where($amountCol . ' = 0');
            $this->db->update('Charity', array($info['col'] => $info['order'] == 'DESC' ? 100 : 1));
            echo 'ok' . PHP_EOL;
        }

        foreach($mappings as $amountCol => $info) {
            $this->db->where($where = $amountCol . ' IS NOT NULL AND ' . $amountCol . ' <> 0');
            $this->db->from('Charity');
            echo 'Counting total for ' . $amountCol . '...';
            $total = $this->db->count_all_results();
            echo $total . PHP_EOL;

            $this->db->select('group_concat(id) as ids');
            $this->db->where($where);
            $this->db->from('Charity');
            $this->db->group_by($amountCol);
            $this->db->order_by($amountCol, $info['order']);
            echo 'Selecting ... ';
            $query = $this->db->get();
            $results = $query->result_array();
            echo count($results) . ' rows.' .PHP_EOL;
            $processed = 0;
            echo 'Processing ...';
            foreach($results as $row) {
                $ids = explode(',', $row['ids']);
                $thisPositionCount = count($ids);
                $processed += $thisPositionCount;

                $betterRanked = $total - $processed;

                $totalMinusSelf = $total - $thisPositionCount;
                if ($totalMinusSelf == 0) {
                    $percentile = 100;
                } else {
                    $percentile = 100 - ceil(($betterRanked / $totalMinusSelf) * 100);
                }
                if ($percentile == 0) {
                    $percentile = 1;
                }
                echo $percentile . ' ';
                $this->db->where_in('id', $ids);
                $this->db->update('Charity', array($info['col'] => $percentile));
            }
            echo PHP_EOL;
        }
    }

    public function set_revenue_percentile() {
        die('deprecated, run set_revenue_percentile2');
        set_time_limit(0);
        $trim = function($str) { $str = preg_replace('#\xc2\xa0#', ' ', $str); return trim($str); };

        $CI =& get_instance();
        $CI->db->select_max('id');
        $CI->db->where('revenuePercentile IS NOT NULL');

        $query = $CI->db->get('Charity');
        $results = $query->result_array();
        $offset = 0; //$results ? $results[0]['id'] : 0;
        echo 'Starting with offset ' . $offset . PHP_EOL;
        $limit = 10;

        $em = self::$em;
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $charityRepo = $em->getRepository('Entity\Charity');

        do {
            echo $offset;
            if (isset($charities)) {
                unset($charities);
            }
            /** @var \Entity\Charity[] $charities */
            $charities = $charityRepo->findBy(array(),null,$limit,$offset);
            $offset += $limit;
            echo PHP_EOL;
            foreach($charities as $charity) {
                $charity->setPercentiles(true);
                $em->persist($charity);
                echo '.';
            }
            $em->flush();
            $em->clear();
            echo PHP_EOL;
        } while($charities);
    }

    public function trimCharityName() {
        set_time_limit(0);
        $trim = function($str) { $str = preg_replace('#\xc2\xa0#', ' ', $str); return trim($str); };
        $offset = 0;
        $limit = 500;

        do {
            echo $offset;
            $this->db->select('id, name');
            $query = $this->db->get('Charity', $limit, $offset);

            $results = $query->result();
            echo PHP_EOL;
            foreach($results as $row) {
                $row->name = $trim($row->name);
                $this->db->where('id', $row->id);
                $this->db->update('Charity', array('name' => $row->name));
                echo '.';
            }
            echo PHP_EOL;
            $offset += $limit;
        } while ($results);
    }

    /**
     * Perform parallel cURL request.
     *
     * @param array $urls Array of URLs to make request.
     * @param array $options (Optional) Array of additional cURL options.
     * @return mixed Results from the request (if any).
     */
    protected function curlMultiRequest($urls, $options = array()) {
        $ch = array();
        $results = array();
        $mh = curl_multi_init();
        foreach($urls as $key => $val) {
            $ch[$key] = curl_init();
            if ($options) {
                curl_setopt_array($ch[$key], $options);
            }
            curl_setopt($ch[$key], CURLOPT_URL, $val);
            curl_multi_add_handle($mh, $ch[$key]);
        }
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        }
        while ($running > 0);
        // Get content and remove handles.
        foreach ($ch as $key => $val) {
            $results[$key] = curl_multi_getcontent($val);
            curl_multi_remove_handle($mh, $val);
        }
        curl_multi_close($mh);
        return $results;
    }


    public function panic3($simultaneous = 1) {
        echo $simultaneous;

        set_time_limit(0);
        $chh = curl_init();

        $this->signin($chh);

        $cookiefile = '/tmp/curl-session';

        $options = array(
            CURLOPT_HEADER => 0,
            CURLOPT_COOKIEFILE => $cookiefile,
            CURLOPT_COOKIEJAR => $cookiefile,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POST => false,
            CURLOPT_POSTFIELDS, array(),
        );


        echo 'Getting eins..';
        $einfile = file_get_contents(__DIR__.'/../../../data-download-pub78.txt','r');
        $eins = explode("\n", $einfile);
        $eincount = count($eins);
        echo 'ok. got ' . $eincount . ' eins.' . PHP_EOL;


        self::$em->getConnection()->getConfiguration()->setSQLLogger(null);

        $fp = fopen('download-log.txt', 'w');

        $urls = array();
        $results = array();
        $ch = array();
        $mh = curl_multi_init();
        $running = null;
        foreach($eins as $nr => $ein) {
            $urlWasAdded = false;
            preg_match('#(\d{2})\-?(\d{7})#', $ein, $matches);
            if (!@$matches[1] || !@$matches[2]) {
                echo 'Weird ein: ' . $ein . PHP_EOL;
                fprintf($fp, "weird ein" . $ein . PHP_EOL);
                continue;
            }
            $ein = $matches[1] . '-' . $matches[2];
            echo 'Opening ein ' . ($nr+1) . ' of ' . $eincount . '...';

            $url = 'http://www.guidestar.org/ReportOrganization.aspx?ein='.$ein;
            echo 'url: ' . $url . PHP_EOL;

            $this->db->where('url', $url);
            $this->db->from('UrlCache');

            if (!$this->db->count_all_results()) {

                echo 'not cached..';

                if (count($ch) < $simultaneous) {
                    echo 'adding to queue.';
                    $ch[$url] = curl_init();
                    curl_setopt_array($ch[$url], $options);
                    curl_setopt($ch[$url], CURLOPT_URL, $url);
                    curl_multi_add_handle($mh, $ch[$url]);
                    curl_multi_exec($mh, $running);
                    $urlWasAdded = true;
                } else {
                    do {
                        do {
                            curl_multi_exec($mh, $running);
                        } while ($running == $simultaneous);
                        do {
                            $msg = curl_multi_info_read($mh);
                            if ($msg['msg'] == CURLMSG_DONE) {
                                foreach($ch as $url2 => $curl) {
                                    if ($curl === $msg['handle']) {
                                        $res = curl_multi_getcontent($curl);
                                        curl_multi_remove_handle($mh, $curl);
                                        unset($ch[$url2]);

                                        if (preg_match('#Andrew Levine#', $res)) {
                                            echo 'saving result ' . $url2 . PHP_EOL;
                                            echo 'got it.. length: '. strlen($res).PHP_EOL;
                                            $urlCache = new UrlCache($url2,$res);
                                            self::$em->persist($urlCache);
                                            self::$em->flush($urlCache);
                                            unset($urlCache);
                                            self::$em->clear();
                                        } else {
                                            echo PHP_EOL . 'No Andrew Levine...' . strlen($res) . PHP_EOL;
                                            fprintf($fp, 'No Andrew Levine...' . strlen($res) . PHP_EOL);
                                            if (count($urls)) {
                                                echo 'signing in again..';
                                                $this->signin($chh);
                                            }
                                        }

                                        echo 'got url' . $url2;

                                        $ch[$url] = curl_init();
                                        curl_setopt_array($ch[$url], $options);
                                        curl_setopt($ch[$url], CURLOPT_URL, $url);
                                        curl_multi_add_handle($mh, $ch[$url]);
                                        curl_multi_exec($mh, $running);
                                        echo 'adding ' . $url . ' to quu...';
                                        $urlWasAdded = true;
                                    }
                                }
                            }
                        } while($msg !== false);
                    } while(!$urlWasAdded);
                }

            } else {
                echo 'already got it in cache. going to next' . PHP_EOL;
            }
        }
        do {
            curl_multi_exec($mh, $running);
        } while ($running);
        do {
            $msg = curl_multi_info_read($mh);
            if ($msg['msg'] == CURLMSG_DONE) {
                foreach($ch as $url2 => $curl) {
                    if ($curl === $msg['handle']) {
                        $res = curl_multi_getcontent($curl);
                        curl_multi_remove_handle($mh, $curl);
                        unset($ch[$url2]);

                        if (preg_match('#Andrew Levine#', $res)) {
                            echo 'saving result ' . $url2 . PHP_EOL;
                            echo 'got it.. length: '. strlen($res).PHP_EOL;
                            $urlCache = new UrlCache($url2,$res);
                            self::$em->persist($urlCache);
                            self::$em->flush($urlCache);
                            unset($urlCache);
                            self::$em->clear();
                        } else {
                            echo PHP_EOL . 'No Andrew Levine...' . strlen($res) . PHP_EOL;
                            fprintf($fp, 'No Andrew Levine...' . strlen($res) . PHP_EOL);
                            if (count($urls)) {
                                echo 'signing in again..';
                                $this->signin($chh);
                            }
                        }

                        echo 'got url' . $url2;

                    }
                }
            }
        } while($msg !== false);

        curl_multi_close($mh);
        fclose($fp);
    }

    public function signin($ch) {
        echo 'signing in'.PHP_EOL;
        $cookiefile = '/tmp/curl-session';
        $loginUrl = 'https://www.guidestar.org/Login.aspx?ReturnUrl=http://www.guidestar.org/Home.aspx';
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $loginUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, 180);

        // grab URL and pass it to the browser
        $output = curl_exec($ch);

        $dom = new DOMDocument;
        $dom->loadHTML($output);
        $inputs = array();
        foreach($dom->getElementsByTagName('input') as $inputNode) {
            /** @var DOMNode $inputNode */
            $inputs[$inputNode->getAttribute('name')] = $inputNode->getAttribute('value');
        }
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, "https://www.guidestar.org/Login.aspx?ReturnUrl=http%3a%2f%2fwww.guidestar.org%2fHome.aspx");
        curl_setopt($ch, CURLOPT_POST, true);
        $viewstate = $inputs['__VIEWSTATE'];
        $eventvalidation = $inputs['__EVENTVALIDATION'];

        $form = array(
            '__LASTFOCUS' => '',
            '__EVENTTARGET' => '',
            '__EVENTARGUMENT' => '',
            '__VIEWSTATE' => $viewstate,
            '__EVENTVALIDATION' => $eventvalidation,
            'ctl00$SearchBar2$txtKeywords' => 'Organization name, EIN, City, State',
            'ctl00$SearchBar2$productConfigDropDown1$ddlProductConfigs' => '2',
            'ctl00$phMainBody$LoginMainsite$UserName' => 'andrewlevine@giverhub.com',
            'ctl00$phMainBody$LoginMainsite$Password' => 'hardcase',
            'ctl00$phMainBody$LoginMainsite$LoginButton' => 'Sign In',
            'ctl00$phMainBody$LoginMainsite$tbFirstName' => '',
            'ctl00$phMainBody$LoginMainsite$tbLastName' => '',
            'ctl00$phMainBody$LoginMainsite$tbEmailNew' => '',
            'ctl00$phMainBody$LoginMainsite$tbPasswordNew' => '',
            'ctl00$phMainBody$LoginMainsite$tbPasswordConfirm' => '',
            'ctl00$phMainBody$LoginMainsite$cbEmailPreferences' => 'on',
            'ctl00$phMainBody$LoginMainsite$cbTerms' => 'on');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
        print_r($form);
        $output = curl_exec($ch);
        if (!preg_match('#Andrew Levine#', $output)) {
            echo 'FAILED TO SIGN IN!'.PHP_EOL;
        }
    }

    public function fixzip()
    {

        $offset = 0;
        $limit  = 100;
        do {
            $qb = self::$em->createQueryBuilder();
            $qb->select('zc')
            ->from('Entity\ZipCode', 'zc')
            ->orderBy('zc.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
            /** @var ZipCode[] $zipCodes */
            $zipCodes = $qb->getQuery()->getResult();

            foreach ($zipCodes as $zipCode) {

                $state = CharityState::getOrCreateByName(self::$em, $zipCode->getState());
                $state->setFullName($zipCode->getFullState());
                self::$em->persist($state);
                self::$em->flush($state);

                $zipCode->setStateId($state->getId());

                if (!$zipCode->getCity()) {
                    $zipCode->setCityId(null);
                    self::$em->persist($zipCode);
                    self::$em->flush($zipCode);
                    continue;
                }

                $city = CharityCity::getOrCreateByNameAndState(self::$em, $zipCode->getCity(), $state);
                $zipCode->setCityId($city->getId());

                self::$em->persist($zipCode);
                self::$em->flush($zipCode);
                echo '.';
            }
            echo '+'.$offset.'+';
            $offset += $limit;
        } while ($zipCodes);
    }

    public function fixcitystate()
    {
        /** @var Doctrine\ORM\EntityRepository $ccRepo */
        $ccRepo = self::$em->getRepository('Entity\CharityCity');

        /** @var Doctrine\ORM\EntityRepository $csRepo */
        $csRepo = self::$em->getRepository('Entity\CharityState');


        $offset = 0;
        $limit  = 100;
        do {
            $qb = self::$em->createQueryBuilder();
            $qb->select('zc')
            ->from('Entity\ZipCode', 'zc')
            ->orderBy('zc.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
            /** @var ZipCode[] $zipCodes */
            $zipCodes = $qb->getQuery()->getResult();

            foreach ($zipCodes as $zipCode) {
                $city  = $ccRepo->findOneBy(array('id' => $zipCode->getCityId()));
                $state = $csRepo->findOneBy(array('id' => $zipCode->getStateId()));
                $city->setStateId($state->getId());
                self::$em->persist($city);
                self::$em->flush($city);
                echo '.';
            }
            echo '+';
            $offset += $limit;
        } while ($zipCodes);
    }

    public function guidestarsitemap() {
        $xml = new SimpleXMLElement(file_get_contents(__DIR__.'/../../../guidestar.sitemap-index.xml','r'));
        $eins = array();
        echo PHP_EOL;
        $sitemapNr = 1;
        foreach($xml->sitemap as $sitemap) {
            echo 'Downloading ' . $sitemap->loc . ' ' . ($sitemapNr++) . '/'.count($xml->sitemap).'...';
            /** @var \Entity\UrlCache $urlCache */
            $urlCache = UrlCache::findOneBy(array('url' => $sitemap->loc));
            if ($urlCache) {
                echo 'cached.';
                $gzipped = base64_decode($urlCache->getData());
            } else {
                do {
                    $gzipped = file_get_contents($sitemap->loc);
                    echo '.';
                } while(!$gzipped);
                $urlCache = new UrlCache($sitemap->loc,base64_encode($gzipped));
                self::$em->persist($urlCache);
                self::$em->flush($urlCache);
                self::$em->clear();
            }
            echo 'done. uncompressing...';
            $gunzipped = gzdecode($gzipped);
            echo 'done. parsing...';
            $newEins = array();
            try {
                $x = new SimpleXMLElement($gunzipped);
                echo PHP_EOL . 'found '.count($x->url). ' urls. Checking if they contain eins...';

                foreach($x->url as $url) {
                    if (preg_match('#\d{2}\-\d{7}#', $url->loc, $matches)) {
                        $newEins[] = $matches[0];
                    }
                }
            } catch(Exception $e) {
                echo 'failed...trying preg_match_all...';
                if (preg_match_all('#\d{2}\-\d{7}#', $gunzipped, $matches)) {
                    foreach($matches[0] as $ein) {
                        $newEins[] = $ein;
                    }
                }
            }
            $newEins = array_unique($newEins);
            echo PHP_EOL . 'Added ' . count($newEins);
            $eins = array_merge($eins, $newEins);

            echo ' total ' . count($eins) . PHP_EOL;
        }
        $eins = array_unique($eins);
        echo 'total unique: ' . count($eins);
        echo 'Saving eins to file...';
        file_put_contents('guidestarsitemapeins.txt', implode("\n", $eins));
        echo 'DONE.' . PHP_EOL;
    }

    public function multi_download($limit = 100, $simultaneous = 3, $run1 = 1, $run2 = 1, $run3 = 1) {
        $urls = array();
        for($x = 0; $x < $limit; $x++) {
            $urls[$x] = 'http://giverhub.127.0.0.1.xip.io/charity_api/bluuh'.$x;
        }

        $bench = array();

        if ($run1) {
            $start = microtime(true);
            foreach($urls as $url) {
                $content = file_get_contents($url);
                echo '.';
            }
            $bench[] = $time = microtime(true) - $start;
            echo $time . PHP_EOL;
        }

        $cookiefile = '/tmp/curl-session';

        $options = array(
            CURLOPT_HEADER => 0,
            CURLOPT_COOKIEFILE => $cookiefile,
            CURLOPT_COOKIEJAR => $cookiefile,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POST => false,
            CURLOPT_POSTFIELDS, array(),
        );


        if ($run2) {
            $queue = array();
            $start = microtime(true);
            foreach($urls as $url) {
                $queue[] = $url;
                if (count($queue) == $simultaneous) {
                    $results = $this->curlMultiRequest($queue, $options);
                    $queue = array();
                    echo '.';
                }
            }
            $bench[] = $time = microtime(true) - $start;
            echo $time . PHP_EOL;
        }

        if ($run3) {
            $start = microtime(true);
            $results = array();
            $ch = array();
            $mh = curl_multi_init();

            $running = null;
            foreach($urls as $url) {
                $urlWasAdded = false;
                if (count($ch) < $simultaneous) {
                    echo '+';
                    $ch[$url] = curl_init();
                    curl_setopt_array($ch[$url], $options);
                    curl_setopt($ch[$url], CURLOPT_URL, $url);
                    curl_multi_add_handle($mh, $ch[$url]);
                    curl_multi_exec($mh, $running);
                    $urlWasAdded = true;
                } else {
                    do {
                        do {
                            curl_multi_exec($mh, $running);
                        } while ($running == $simultaneous);
                        do {
                            $msg = curl_multi_info_read($mh);
                            if ($msg['msg'] == CURLMSG_DONE) {
                                foreach($ch as $url2 => $curl) {
                                    if ($curl === $msg['handle']) {
                                        $results[$url2] = curl_multi_getcontent($curl);
                                        curl_multi_remove_handle($mh, $curl);
                                        unset($ch[$url2]);
                                        echo '.';

                                        $ch[$url] = curl_init();
                                        curl_setopt_array($ch[$url], $options);
                                        curl_setopt($ch[$url], CURLOPT_URL, $url);
                                        curl_multi_add_handle($mh, $ch[$url]);
                                        curl_multi_exec($mh, $running);
                                        echo 'p';
                                        $urlWasAdded = true;
                                    }
                                }
                            }
                        } while($msg !== false);
                    } while(!$urlWasAdded);
                }
            }
            do {
                curl_multi_exec($mh, $running);
            } while ($running);
            do {
                $msg = curl_multi_info_read($mh);
                if ($msg['msg'] == CURLMSG_DONE) {
                    foreach($ch as $url2 => $curl) {
                        if ($curl === $msg['handle']) {
                            $results[$url2] = curl_multi_getcontent($curl);
                            curl_multi_remove_handle($mh, $curl);
                            unset($ch[$url2]);
                            echo '.';
                        }
                    }
                }
            } while($msg !== false);

            curl_multi_close($mh);
            $bench[] = $time = microtime(true) - $start;
            echo $time . PHP_EOL;
            echo count($results);
            foreach($results as $result) {
                $json = json_decode($result, true);
                if ($json['crc'] != crc32($json['msg'])) {
                    echo 'failed';
                } else {
                    echo 'K';
                }
            }
            print_r(array_keys($results));
        }

        print_r($bench);
    }


}


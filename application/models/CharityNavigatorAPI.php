<?php
require_once(__DIR__.'/../controllers/Base_Controller.php');
use \Entity\Charity;
use \Entity\CharityCause;

class VerifyError extends Exception {}
class VerifyWarning extends Exception {}
class VerifyInfo extends Exception {}

class CharityNavigatorAPI extends \Base_Controller {
    static public $APP_ID = '649cb5bd';
    static public $APP_KEY = '8b908eae6c5d53eed4a569a618877a61';

    static public function search_ein($ein) {
        $response = file_get_contents('http://api.charitynavigator.org/api/v1/search/?format=json&app_key='.self::$APP_KEY.'&app_id='.self::$APP_ID.'&rated=2&ein='.$ein);
        $response = json_decode($response, true);
        return $response;
    }

    static public function search_eins(array $eins) {
        $urls = [];
        foreach($eins as $ein) {
            $urls[$ein] = 'http://api.charitynavigator.org/api/v1/search/?format=json&app_key='.self::$APP_KEY.'&app_id='.self::$APP_ID.'&rated=2&ein='.$ein;
        }
        $results = self::curlMultiRequest($urls);

        $ret = [];
        foreach($results as $ein => $result) {
            $ret[$ein] = json_decode($result, true);
        }

        return $ret;
    }


    static public function getCategoryIds() {
        $results = self::curlMultiRequest(['http://api.charitynavigator.org/api/v1/categories/?app_key='.self::$APP_KEY.'&app_id='.self::$APP_ID.'&format=json']);
        $categories = json_decode($results[0], true);

        if (!isset($categories['objects'])) {
            throw new Exception('No category objects!');
        }
        if (!is_array($categories['objects'])) {
            throw new Exception('No category objects!!');
        }
        if (!count($categories['objects'])) {
            throw new Exception('No category objects!!!');
        }

        $categoryIds = [];
        foreach($categories['objects'] as $category) {
            $categoryIds[] = $category['categoryid'];
        }
        return $categoryIds;
    }

    static public function getCauseIds() {
        $results = self::curlMultiRequest(['http://api.charitynavigator.org/api/v1/causes/?app_key='.self::$APP_KEY.'&app_id='.self::$APP_ID.'&format=json&limit=100']);
        $res = json_decode($results[0], true);


        if (!isset($res['objects'])) {
            throw new Exception('No causes objects!');
        }
        if (!is_array($res['objects'])) {
            throw new Exception('No causes objects!!');
        }
        if (!count($res['objects'])) {
            throw new Exception('No causes objects!!!');
        }

        $objects = $res['objects'];

        while($res['meta']['next']) {
            $url = $res['meta']['next'];
            echo $url . PHP_EOL;
            $results = self::curlMultiRequest([$url]);
            $res = json_decode($results[0], true);

            $objects = array_merge($objects, $res['objects']);
        }

        $causeIds = [];

        foreach($objects as $object) {
            $causeIds[] = $object['causeid'];
        }

        return $causeIds;
    }

    static public function searchIds($key, array $ids) {
        $objects = [];

        foreach($ids as $id) {
            $url = 'http://api.charitynavigator.org/api/v1/search/?app_key='.self::$APP_KEY.'&app_id='.self::$APP_ID.'&'.$key.'='.$id.'&format=json&limit=100&rated=2';
            echo $url . PHP_EOL;
            $results = self::curlMultiRequest([$url]);
            $res = json_decode($results[0], true);
            if (!isset($res['meta'])) {
                throw new Exception('no meta');
            }

            $objects = array_merge($objects, $res['objects']);

            while($res['meta']['next']) {
                $url = 'http://api.charitynavigator.org'.$res['meta']['next'];
                echo $url . PHP_EOL;
                $results = self::curlMultiRequest([$url]);
                $res = json_decode($results[0], true);

                $objects = array_merge($objects, $res['objects']);
            }
        }

        return $objects;
    }

    /**
     * @return array
     */
    static public function crawl() {

        $categoryIds = self::getCategoryIds();
        $causeIds = self::getCauseIds();

        $objects = self::searchIds('category', $categoryIds);

        $objects = array_merge($objects, self::searchIds('cause', $causeIds));

        $orgIds = [];

        foreach($objects as $object) {
            $orgIds[$object['OrgId']] = $object;
        }
        echo count($orgIds) . PHP_EOL;
        return $orgIds;
    }

    static public function crawl_and_update_db() {
        $orgIds = self::crawl();

        /** @var CharityCause[] $causes */
        $causes = CharityCause::findAll();
        foreach($causes as $cause) {
            self::$causes[$cause->getName()] = $cause;
        }

        foreach($orgIds as $orgId => $object) {
            try {
                self::update($object);
            } catch(VerifyError $e) {
                echo 'ERROR: ' . $orgId . ': ' . $e->getMessage() . PHP_EOL;
            } catch(VerifyWarning $e) {
                echo 'WARNING: ' . $orgId . ': ' . $e->getMessage() . PHP_EOL;
            } catch(VerifyInfo $e) {
                echo 'INFO: ' . $orgId . ': ' . $e->getMessage() . PHP_EOL;
            }
        }
    }

    static public $causeMappings = [
        'Multipurpose Human Service Organizations' => 'Human Services',
        'Religious Activities' => 'Other',
        'Religious Media and Broadcasting' => 'Other',
    ];

    /** @var CharityCause[] */
    static public $causes = [];


    /**
     * @param array $object
     *
     * @throws VerifyError
     * @throws Exception
     */
    static public function update(array $object) {
        echo '1';
        if (!is_array($object)) {
            throw new VerifyError('object is not even an array!!');
        }
        if (!isset($object['ein'])) {
            throw new VerifyError('ein is not set!');
        }
        if (!$object['ein']) {
            throw new VerifyError('ein is empty or something: ' . $object['ein']);
        }

        echo '2';
        /** @var \Entity\CharityState $state */
        $state = \Entity\CharityState::findOneBy(['name' => $object['state']]);
        if (!$state) {
            throw new VerifyError('State ' . $object['state'] . ' is missing.');
        }
        echo '3';
        /** @var \Entity\CharityCity $city */
        $city = \Entity\CharityCity::findOneBy(['name' => $object['city'], 'stateId' => $state->getId()]);
        if (!$city) {
            $city = new \Entity\CharityCity($object['city']);
            $city->setStateId($state->getId());
            self::$em->persist($city);
            self::$em->flush($city);
        }
        echo '4';
        /** @var Charity $charityOrgId */
        $charityOrgId = Charity::findOneBy(['orgId' => $object['OrgId']]);
        echo '5';
        /** @var Charity $charityEin */
        $charityEin = Charity::findOneBy(['ein' => $object['ein']]);
        echo '6';

        $charity = null;

        if ($charityOrgId && $charityEin && $charityEin->getId() != $charityOrgId->getId()) {
            echo '6a';
            self::$em->remove($charityEin);
            self::$em->flush();
            $charity = $charityOrgId;
        } elseif ($charityOrgId) {
            $charity = $charityOrgId;
        } elseif ($charityEin) {
            $charity = $charityEin;
        } else {
            echo 'INFO: creating charity from ein: '.$object['ein'] . PHP_EOL;
            $charity = new Charity;
            $charity->setCreatedAt(date('Y-m-d H:i:s'));
        }

        echo '7';
        $charity->setEin($object['ein']);
        $charity->setOrgId($object['OrgId']);
        $charity->setName($object['Charity_Name']);

        $charity->setCityId($city->getId());
        $charity->setStateId($state->getId());
        echo '8';
        $cause = null;
        if (!isset(self::$causes[$object['Cause']])) {
            if (!isset(self::$causeMappings[$object['Cause']])) {
                echo 'DEBUG: causeMapping is missing: ' . $object['Cause'] . PHP_EOL;
                throw new Exception('DEBUG: causeMapping is missing: ' . $object['Cause']);
            } elseif (!isset(self::$causes[self::$causeMappings[$object['Cause']]])) {
                echo 'DEBUG: causeMapping is pointing wrong: ' . $object['Cause'] . PHP_EOL;
                throw new Exception('DEBUG: causeMapping is pointing wrong: ' . $object['Cause']);
            } else {
                $cause = self::$causes[self::$causeMappings[$object['Cause']]];
            }
        } else {
            $cause = self::$causes[$object['Cause']];
        }

        if (!$cause instanceof CharityCause) {
            throw new Exception('wtf cause not found? cause: ' . $object['Cause']);
        }

        $causeFound = false;
        foreach($charity->getCauses() as $c) {
            if ($c->getId() == $cause->getId()) {
                $causeFound = true;
                /** @var \Entity\CharityCharityCause $charity_charity_cause */
                $charity_charity_cause = \Entity\CharityCharityCause::findOneBy(['charityId' => $charity->getId(), 'causeId' => $c->getId()]);
                $charity_charity_cause->setFromCharityNavigator(1);
                \Base_Controller::$em->persist($charity_charity_cause);
                \Base_Controller::$em->flush($charity_charity_cause);
            }
        }
        echo '9';
        if (!$causeFound) {
            self::$em->persist($charity);
            self::$em->flush($charity);

            echo 'INFO: setting cause.' . PHP_EOL;
            $newCause = new \Entity\CharityCharityCause();
            $newCause->setCause($cause);
            $newCause->setCharity($charity);
            $newCause->setFromCharityNavigator(1);
            self::$em->persist($newCause);
            self::$em->flush($newCause);
        }
        echo '10';
        $charity->setOverallRtg($object['OverallRtg']);
        $charity->setOverallScore($object['OverallScore']);
        $charity->setRank($object['Rank']);
        $charity->setTagLine($object['Tag_Line']);
        $charity->setZipCode($object['ZIP']);
        echo '11';
        self::$em->persist($charity);
        self::$em->flush($charity);
        echo '12' . PHP_EOL;

        /*  [Category] => Education
            [Cause] => Private Elementary and Secondary Schools
            [Charity_Name] => Berkshire School
            [OrgId] => 12813
            [OverallRtg] => 3
            [OverallScore] => 53.530000000000000
            [Rank] => 0
            [Tag_Line] => Learning not just for school but for life
            [URL] => http://www.charitynavigator.org/index.cfm?bay=search.summary&orgid=042121313
            [ZIP] => 01257
            [city] => Sheffield
            [ein] => 042121313
            [resource_uri] =>
            [state] => MA */


    }


    /**
     * @param array $urls
     *
     * @return array
     * @throws Exception
     */
    static public function curlMultiRequest(array $urls) {

        $options = array(
            CURLOPT_HEADER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POST => false,
            CURLOPT_POSTFIELDS, array(),
        );

        $ch = array();
        $results = array();
        $mh = curl_multi_init();
        $CI =& get_instance();
        foreach($urls as $key => $val) {

            $q = $CI->db->query('select `data` as data from UrlCache where url = \''.$val.'\'');
            $rows = $q->result_array();
            if ($rows) {
                $results[$key] = $rows[0]['data'];
                echo $val . ' was found in cache.' . PHP_EOL;
            } else {
                $ch[$key] = curl_init();
                if ($options) {
                    curl_setopt_array($ch[$key], $options);
                }
                curl_setopt($ch[$key], CURLOPT_URL, $val);
                curl_multi_add_handle($mh, $ch[$key]);
            }
        }
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        }
        while ($running > 0);
        // Get content and remove handles.
        foreach ($ch as $key => $val) {

            $err = curl_error($val);
            if ($err) {
                echo $err . PHP_EOL;
            }
            $httpCode = curl_getinfo($val, CURLINFO_HTTP_CODE);
            if ($httpCode != 200) {
                echo $key . ': http-code: ' . $httpCode . PHP_EOL;
            }
            $results[$key] = curl_multi_getcontent($val);
            if ($results[$key]) {
                $res = $CI->db->query('insert into UrlCache (`url`, `data`, `version`) values(\''.mysql_real_escape_string($urls[$key]).'\', \''.mysql_real_escape_string($results[$key]).'\',0)');
                if (!$res) {
                    throw new Exception('Failed inserting to cache');
                }
            }
            curl_multi_remove_handle($mh, $val);
        }
        curl_multi_close($mh);
        return $results;
    }

    static public function test_curl_error(array $urls) {

        $results = self::curlMultiRequest($urls);

    }
}
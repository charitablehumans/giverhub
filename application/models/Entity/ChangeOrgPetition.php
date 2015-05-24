<?php

namespace Entity;
require_once(__DIR__.'/../../libraries/sphinxapi.php');

/**
 * ChangeOrgPetition
 *
 * @Table(name="change_org_petition")
 * @Entity @HasLifecycleCallbacks
 */
class ChangeOrgPetition extends BaseEntity {

    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @Column(name="petition_id", type="integer", nullable=false)
     */
    private $petition_id;

    /**
     * @var string
     *
     * @Column(name="auth_key", type="string", nullable=true)
     */
    private $auth_key;

    /**
     * @var string
     *
     * @Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Column(name="status", type="string", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @Column(name="url", type="string", nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @Column(name="overview", type="string", nullable=false)
     */
    private $overview;

    /**
     * @var string
     *
     * @Column(name="letter_body", type="string", nullable=false)
     */
    private $letter_body;

    /**
     * @var integer
     *
     * @Column(name="signature_count", type="integer", nullable=false)
     */
    private $signature_count;

    /**
     * @var string
     *
     * @Column(name="image_url", type="string", nullable=true)
     */
    private $image_url;

    /**
     * @var string
     *
     * @Column(name="category", type="string", nullable=true)
     */
    private $category;

    /**
     * @var integer
     *
     * @Column(name="goal", type="integer", nullable=true)
     */
    private $goal;

    /**
     * @var string
     *
     * @Column(name="created_at", type="string", nullable=false)
     */
    private $created_at;

    /**
     * @var string
     *
     * @Column(name="end_at", type="string", nullable=true)
     */
    private $end_at;

    /**
     * @var string
     *
     * @Column(name="creator_name", type="string", nullable=true)
     */
    private $creator_name;

    /**
     * @var string
     *
     * @Column(name="creator_url", type="string", nullable=true)
     */
    private $creator_url;

    /**
     * @var string
     *
     * @Column(name="organization_name", type="string", nullable=true)
     */
    private $organization_name;

    /**
     * @var string
     *
     * @Column(name="organization_url", type="string", nullable=true)
     */
    private $organization_url;

    /**
     * @var string
     *
     * @Column(name="updated_at", type="string", nullable=true)
     */
    private $updated_at;

    /**
     * @var string
     *
     * @Column(name="giverhub_url_slug", type="string", nullable=false)
     */
    private $giverhub_url_slug;

    /**
     * @var string
     *
     * @Column(name="auth_key_error", type="string", nullable=false)
     */
    private $auth_key_error;

    /**
     * @var string
     *
     * @Column(name="profanity_filter", type="string", nullable=true)
     */
    private $profanity_filter;

    /**
     * @var integer
     *
     * @Column(name="is_featured", type="integer", nullable=false)
     */
    private $is_featured = 0;

    /**
     * @var string
     *
     * @Column(name="featured_text", type="string", nullable=true)
     */
    private $featured_text;

    /**
     * @var integer
     *
     * @Column(name="force_update", type="integer", nullable=false)
     */
    private $force_update = 0;

    /**
     * @var integer
     *
     * @Column(name="removed", type="integer", nullable=false)
     */
    private $removed = 0;

    /**
     * @var integer
     *
     * @Column(name="trending", type="integer", nullable=false)
     */
    private $trending = 0;

    /**
     * @return int
     */
    public function getTrending()
    {
        return $this->trending;
    }

    /**
     * @param int $trending
     */
    public function setTrending( $trending )
    {
        $this->trending = $trending;
    }


    /**
     * @param string $auth_key
     */
    public function setAuthKey($auth_key)
    {
        $this->auth_key = $auth_key;
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAtDt() {
        return new \DateTime($this->getCreatedAt());
    }

    /**
     * @param string $creator_name
     */
    public function setCreatorName($creator_name)
    {
        $this->creator_name = $creator_name;
    }

    /**
     * @return string
     */
    public function getCreatorName()
    {
        return $this->creator_name;
    }

    /**
     * @param string $creator_url
     */
    public function setCreatorUrl($creator_url)
    {
        $this->creator_url = $creator_url;
    }

    /**
     * @return string
     */
    public function getCreatorUrl()
    {
        return $this->creator_url;
    }

    /**
     * @param string $end_at
     */
    public function setEndAt($end_at)
    {
        $this->end_at = $end_at;
    }

    /**
     * @return string
     */
    public function getEndAt()
    {
        return $this->end_at;
    }

    /**
     * @return \DateTime
     */
    public function getEndAtDt()
    {
        return new \DateTime($this->end_at);
    }

    /**
     * @return bool
     */
    public function hasEnd() {
        return $this->end_at !== null;
    }

    /**
     * @param int $goal
     */
    public function setGoal($goal)
    {
        $this->goal = $goal;
    }

    /**
     * @return int
     */
    public function getGoal()
    {
        return $this->goal;
    }

    /**
     * @return bool
     */
    public function hasGoal() {
        return $this->goal !== null;
    }

    /**
     * @param string $image_url
     */
    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function getImageUrlPrependHttp() {
        if ($this->image_url && $this->image_url[0] == '/') {
            return 'http:'.$this->image_url;
        }
        return $this->image_url;
    }

    /**
     * @param string $letter_body
     */
    public function setLetterBody($letter_body)
    {
        $this->letter_body = $letter_body;
    }

    /**
     * @return string
     */
    public function getLetterBody()
    {
        return $this->letter_body;
    }

    /**
     * @param string $organization_name
     */
    public function setOrganizationName($organization_name)
    {
        $this->organization_name = $organization_name;
    }

    /**
     * @return string
     */
    public function getOrganizationName()
    {
        return $this->organization_name;
    }

    /**
     * @param string $organization_url
     */
    public function setOrganizationUrl($organization_url)
    {
        $this->organization_url = $organization_url;
    }

    /**
     * @return string
     */
    public function getOrganizationUrl()
    {
        return $this->organization_url;
    }

    /**
     * @param string $overview
     */
    public function setOverview($overview)
    {
        $this->overview = $overview;
    }

    /**
     * @return string
     */
    public function getOverview()
    {
        return $this->overview;
    }

    /**
     * @param int $petition_id
     */
    public function setPetitionId($petition_id)
    {
        $this->petition_id = $petition_id;
    }

    /**
     * @return int
     */
    public function getPetitionId()
    {
        return $this->petition_id;
    }

    /**
     * @param int $signature_count
     */
    public function setSignatureCount($signature_count)
    {
        $this->signature_count = $signature_count;
    }

    /**
     * @return int
     */
    public function getSignatureCount()
    {
        return $this->signature_count;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $giverhub_url_slug
     */
    public function setGiverhubUrlSlug($giverhub_url_slug)
    {
        $this->giverhub_url_slug = $giverhub_url_slug;
    }

    /**
     * @return string
     */
    public function getGiverhubUrlSlug()
    {
        return $this->giverhub_url_slug;
    }

    public function getGiverhubUrl($baseUrl = 'https://giverhub.com/') {
        return $baseUrl . 'petitions/' . $this->getGiverhubUrlSlug();
    }

    /**
     * @param string $auth_key_error
     */
    public function setAuthKeyError($auth_key_error)
    {
        $this->auth_key_error = $auth_key_error;
    }

    /**
     * @return string
     */
    public function getAuthKeyError()
    {
        return $this->auth_key_error;
    }

    /**
     * @param $name
     * @param $type
     *
     * @return bool
     */
    public function createOrUpdateTarget($name, $type, $first) {
        return ChangeOrgPetitionTarget::createOrUpdate($this, $name, $type, $first);
    }

    /**
     * @return ChangeOrgPetitionTarget[]
     */
    public function getTargets() {
        /** @var ChangeOrgPetitionTarget[] $targets */
        $targets = ChangeOrgPetitionTarget::findBy(['petition_id' => $this->getId()], ['first' => 'desc']);
        return $targets;
    }

    public function getFirstTargetString() {
        $targets = $this->getTargets();
        if ($targets) {
            return $targets[0]->getName();
        }
        return '';
    }

    /**
     * @return string
     */
    public function getTargetsString() {
        $targets = array();
        foreach($this->getTargets() as $target) {
            $targets[] = $target->getName();
        }
        return join(', ', $targets);
    }

    public function hasEnded() {
        if ($this->end_at === null) {
            return false;
        }

        $end = new \DateTime($this->end_at);
        $now = new \DateTime();
        if ($end < $now) {
            return true;
        }

        return false;
    }

    public function sign(User $user, $hidden = false, $reason = null) {

        /** @var UserPetitionSignature $existingSignature */
        $existingSignature = UserPetitionSignature::findOneBy(['user_id' => $user->getId(), 'petition_id' => $this->getId()]);
        if ($existingSignature) {
            return 'You already signed this petition ' . $existingSignature->getSignedAt();
        }
        $CI =& get_instance();
        $api_key = $CI->config->item("change_org_api_key");
        $secret = $CI->config->item("change_org_secret");

        $petition_id = $this->getPetitionId();

        // Set up the endpoint and URL.
        $base_url = "https://api.change.org";
        $endpoint = "/v1/petitions/".$petition_id."/signatures";
        $url = $base_url . $endpoint;

        // Set up the signature parameters.
        $parameters = array();
        $parameters['api_key'] = $api_key;

        if (!$this->getAuthKey()) {
            throw new \Exception('Auth key is missing. Petition-id: ' . $this->getId() . ' auth key error: ' . $this->getAuthKeyError());
        }

        $parameters['auth_key'] = $this->getAuthKey();


        $parameters['source_description'] = 'GiverHub petition page.';
        $parameters['source'] = $this->getGiverHubUrl($_SERVER['SERVER_NAME'] == 'giverhub.com' ? 'https://giverhub.com/' : 'https://dev.giverhub.com/') . '/?from=change_org';
        $parameters['email'] = $user->getEmail();
        $parameters['first_name'] = $user->getFname();
        $parameters['last_name'] = $user->getLname();

        $ua = $user->getDefaultAddress();

        if ($ua->getAddress1()) {
            $parameters['address'] = $ua->getAddress1();
        }
        if ($ua->getAddress2()) {
            $parameters['address'] .= ("\n" . $ua->getAddress2());
        }

        $parameters['city'] = $ua->getCity()->getName();

        $parameters['state_province'] = $ua->getState()->getName();

        $parameters['postal_code'] = $ua->getZipCode();

        $parameters['country_code'] = 'US';

        if ($ua->getPhone()) {
            $parameters['phone'] = $ua->getPhone();
        }

        if ($reason) {
            $parameters['reason'] = $reason;
        }

        if (in_array($hidden, array('true', 'false'))) {
            $parameters['hidden'] = $hidden;
        }

        $parameters['timestamp'] = gmdate("Y-m-d\TH:i:s\Z");
        $parameters['endpoint'] = $endpoint;

        // Build request signature.
        $query_string_with_secret_and_auth_key = http_build_query($parameters) . $secret . $parameters['auth_key'];

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

        $json = json_decode($result, true);
        if (($result && $json && $json['result'] === 'success') ||
            (isset($json['messages']) && is_array($json['messages']) && isset($json['messages'][0]) && $json['messages'][0] == 'There was a server error. Please try again later.')) {
            $signature = new UserPetitionSignature();
            $signature->setUser($user);
            $signature->setPetition($this);
            $signature->setHidden($hidden === "true" || $hidden === true ? 1 : 0);

            \Base_Controller::$em->persist($signature);
            \Base_Controller::$em->flush($signature);


            $this->setForceUpdate(1);
            \Base_Controller::$em->persist($this);
            \Base_Controller::$em->flush($this);

            return ['success' => true, 'signature_id' => $signature->getId()];
        } else {
            if (!$result) {
                return 'Request failed. ' . curl_error($curl_session);
            }
            if (!$json || !$json['messages']) {
                return 'Bad response from change.org.';
            }
            $ret = join(', ', $json['messages']);
            if ($ret) {
                return $ret .',';
            }
            return print_r($json, true);
        }
    }

    public function updateSignatures($pages) {
        $CI =& get_instance();
        $api_key = $CI->config->item("change_org_api_key");

        $REQUEST_URL = 'https://api.change.org/v1/petitions/'.$this->petition_id.'/signatures';

        $parameters = array(
            'api_key' => $api_key,
            'page_size' => 500,
            'page' => 1,
            'sort' => 'time_desc',
        );


        /** @var ChangeOrgPetitionSignature $latestSignature */
        $latestSignature = ChangeOrgPetitionSignature::findOneBy(array('petition_id' => $this->getId()), array('signed_on' => 'desc'));
        $latestSignatureHasBeenFetched = false;


        do {
            $query_string = http_build_query($parameters);
            $final_request_url = "$REQUEST_URL?$query_string";

            $attempts = 0;
            $response = '';
            while(true) {
                $response = @file_get_contents($final_request_url);
                if ($response) {
                    break;
                }
                echo 'Fetching signatures page ' . $parameters['page'] . ' Attempt failed...';
                $attempts++;
                if ($attempts >= 3) {
                    echo 'giving up.'.PHP_EOL;
                    break;
                }
                echo 'Attempting again...';
            }

            $json_response = json_decode($response, true);
            foreach($json_response['signatures'] as $signature_data) {
                $signed_on = new \DateTime($signature_data['signed_at'], new \DateTimeZone('UTC'));
                $signed_on->setTimezone(new \DateTimeZone(date_default_timezone_get()));

                if ($latestSignature && $latestSignature->getSignedOnDt() >= $signed_on) {
                    $latestSignatureHasBeenFetched = true;
                    break;
                }

                $signature = new ChangeOrgPetitionSignature();
                $signature->setPetition($this);
                $signature->setName($signature_data['name']);
                $signature->setCity($signature_data['city']);
                $signature->setState($signature_data['state_province']);
                $signature->setCountryName($signature_data['country_name']);
                $signature->setCountryCode($signature_data['country_code']);
                $signature->setSignedOnDt($signed_on);
                \Base_Controller::$em->persist($signature);
                \Base_Controller::$em->flush($signature);
            }
            $parameters['page']++;

        } while($parameters['page'] <= (int)$pages && !$latestSignatureHasBeenFetched && $json_response['next_page_endpoint'] !== null);
    }

    public function updateReasons($pages) {
        $CI =& get_instance();
        $api_key = $CI->config->item("change_org_api_key");

        $REQUEST_URL = 'https://api.change.org/v1/petitions/'.$this->petition_id.'/reasons';

        $parameters = array(
            'api_key' => $api_key,
            'page_size' => 500,
            'page' => 1,
            'sort' => 'time_desc',
        );


        /** @var ChangeOrgPetitionReason $latestReason */
        $latestReason = ChangeOrgPetitionReason::findOneBy(array('petition_id' => $this->getId()), array('created_on' => 'desc'));
        $latestReasonHasBeenFetched = false;


        do {
            $query_string = http_build_query($parameters);
            $final_request_url = "$REQUEST_URL?$query_string";

            $attempts = 0;
            $response = '';
            while(true) {
                $response = @file_get_contents($final_request_url);
                if ($response) {
                    break;
                }
                echo 'Fetching reasons page ' . $parameters['page'] . ' Attempt failed...';
                $attempts++;
                if ($attempts >= 3) {
                    echo 'giving up.'.PHP_EOL;
                    break;
                }
                echo 'Attempting again...';
            }

            $json_response = json_decode($response, true);

            foreach($json_response['reasons'] as $reason_data) {
                $created_on = new \DateTime($reason_data['created_at'], new \DateTimeZone('UTC'));
                $created_on->setTimezone(new \DateTimeZone(date_default_timezone_get()));

                if ($latestReason && $latestReason->getCreatedOnDt() >= $created_on) {
                    $latestReasonHasBeenFetched = true;
                    break;
                }

                $reason = new ChangeOrgPetitionReason();
                $reason->setPetition($this);
                $reason->setContent($reason_data['content']);
                $reason->setCreatedOnDt($created_on);
                $reason->setLikeCount($reason_data['like_count']);
                $reason->setAuthorName($reason_data['author_name']);
                $reason->setAuthorUrl($reason_data['author_url']);
                \Base_Controller::$em->persist($reason);
                \Base_Controller::$em->flush($reason);
            }
            $parameters['page']++;

        } while($parameters['page'] <= (int)$pages && !$latestReasonHasBeenFetched && $json_response['next_page_endpoint'] !== null);

    }

    public function updateNews($pages) {
        $CI =& get_instance();
        $api_key = $CI->config->item("change_org_api_key");

        $REQUEST_URL = 'https://api.change.org/v1/petitions/'.$this->petition_id.'/updates';

        $parameters = array(
            'api_key' => $api_key,
            'page_size' => 500,
            'page' => 1,
            'sort' => 'time_desc',
        );

        /** @var ChangeOrgPetitionNewsUpdate $latestUpdate */
        $latestUpdate = ChangeOrgPetitionNewsUpdate::findOneBy(array('petition_id' => $this->getId()), array('created_on' => 'desc'));
        $latestUpdateHasBeenFetched = false;

        do {

            $query_string = http_build_query($parameters);
            $final_request_url = "$REQUEST_URL?$query_string";

            $attempts = 0;
            $response = '';
            while(true) {
                $response = @file_get_contents($final_request_url);
                if ($response) {
                    break;
                }
                echo 'Fetching news updates page ' . $parameters['page'] . '...';
                echo 'Attempt failed...';
                $attempts++;
                if ($attempts >= 3) {
                    echo 'giving up.'.PHP_EOL;
                    break;
                }
                echo 'Attempting again...';
            }

            $json_response = json_decode($response, true);

            foreach($json_response['updates'] as $data) {
                $created_on = new \DateTime($data['created_at'], new \DateTimeZone('UTC'));
                $created_on->setTimezone(new \DateTimeZone(date_default_timezone_get()));

                if ($latestUpdate && $latestUpdate->getCreatedOnDt() >= $created_on) {
                    $latestUpdateHasBeenFetched = true;
                    break;
                }

                if (!$data['content'] && !$data['title']) {
                    break; // skip empty news..
                }
                $reason = new ChangeOrgPetitionNewsUpdate();
                $reason->setPetition($this);
                $reason->setContent($data['content']);
                $reason->setTitle($data['title']);
                $reason->setCreatedOnDt($created_on);
                $reason->setAuthorName($data['author_name']);
                $reason->setAuthorUrl($data['author_url']);
                \Base_Controller::$em->persist($reason);
                \Base_Controller::$em->flush($reason);
            }
            $parameters['page']++;

        } while($parameters['page'] <= (int)$pages && !$latestUpdateHasBeenFetched && $json_response['next_page_endpoint'] !== null);

    }

    public function addMoreReasonsAndSignatures($nr_to_add = 1000, $real_run = true) {
        $this->addMoreReasons($nr_to_add, $real_run);
        $this->addMoreSignatures($nr_to_add, $real_run);
    }

    public function addMoreReasons($nr_to_add = 1000, $real_run = true) {
        $CI =& get_instance();
        $api_key = $CI->config->item("change_org_api_key");

        $REQUEST_URL = 'https://api.change.org/v1/petitions/'.$this->petition_id.'/reasons';

        $parameters = array(
            'api_key' => $api_key,
            'page_size' => 500,
            'page' => 1,
            'sort' => 'time_desc',
        );


        echo PHP_EOL . 'getting latest...';
        /** @var ChangeOrgPetitionReason $latest */
        $latest = ChangeOrgPetitionReason::findOneBy(array('petition_id' => $this->getId()), array('created_on' => 'desc'));
        echo PHP_EOL . 'getting oldest...';
        /** @var ChangeOrgPetitionReason $oldest */
        $oldest = ChangeOrgPetitionReason::findOneBy(array('petition_id' => $this->getId()), array('created_on' => 'asc'));

        echo PHP_EOL;
        if ($latest) {
            echo 'Latest: ' . $latest->getCreatedOnDt()->format('Y-m-d H:i:s') . PHP_EOL;
        }
        if ($oldest) {
            echo 'Oldest: ' . $oldest->getCreatedOnDt()->format('Y-m-d H:i:s') . PHP_EOL;
        }

        $added = 0;

        do {
            $query_string = http_build_query($parameters);
            $final_request_url = "$REQUEST_URL?$query_string";

            $attempts = 0;
            $response = '';
            while(true) {
                $response = @file_get_contents($final_request_url);
                if ($response) {
                    break;
                }
                echo 'Fetching reasons page ' . $parameters['page'] . ' Attempt failed...';
                $attempts++;
                if ($attempts >= 3) {
                    echo 'giving up.'.PHP_EOL;
                    break;
                }
                echo 'Attempting again...';
            }

            $json_response = json_decode($response, true);

            foreach($json_response['reasons'] as $reason_data) {
                $created_on = new \DateTime($reason_data['created_at'], new \DateTimeZone('UTC'));
                $created_on->setTimezone(new \DateTimeZone(date_default_timezone_get()));


                if ($latest && $latest->getCreatedOnDt() >= $created_on && $oldest && $oldest->getCreatedOnDt() <= $created_on) {
                    echo 's'; // SSSkipping because we already should have it we guess :)
                    continue;
                }
                if ($latest && $latest->getCreatedOnDt() <= $created_on) {
                    echo 'n'; // adding because NNNewer than our newest
                } elseif ($oldest && $oldest->getCreatedOnDt() >= $created_on) {
                    echo 'o'; // adding because OOOlder than our oldest
                }

                $reason = new ChangeOrgPetitionReason();
                $reason->setPetition($this);
                $reason->setContent($reason_data['content']);
                $reason->setCreatedOnDt($created_on);
                $reason->setLikeCount($reason_data['like_count']);
                $reason->setAuthorName($reason_data['author_name']);
                $reason->setAuthorUrl($reason_data['author_url']);
                if ($real_run) {
                    \Base_Controller::$em->persist( $reason );
                    \Base_Controller::$em->flush( $reason );
                }
                $added++;
            }
            $parameters['page']++;

        } while($added < $nr_to_add && $json_response['next_page_endpoint'] !== null);
        echo PHP_EOL.'added ' . $added . ' reasons...';
    }

    public function addMoreSignatures($nr_to_add = 1000, $real_run = true) {
        $CI =& get_instance();
        $api_key = $CI->config->item("change_org_api_key");

        $REQUEST_URL = 'https://api.change.org/v1/petitions/'.$this->petition_id.'/signatures';

        $parameters = array(
            'api_key' => $api_key,
            'page_size' => 500,
            'page' => 1,
            'sort' => 'time_desc',
        );


        echo PHP_EOL . 'getting latest..';
        /** @var ChangeOrgPetitionSignature $latest */
        $latest = ChangeOrgPetitionSignature::findOneBy(array('petition_id' => $this->getId()), array('signed_on' => 'desc'));
        echo PHP_EOL . 'getting oldest..';
        /** @var ChangeOrgPetitionSignature $oldest */
        $oldest = ChangeOrgPetitionSignature::findOneBy(array('petition_id' => $this->getId()), array('signed_on' => 'asc'));

        echo PHP_EOL;
        if ($latest) {
            echo 'Latest: ' . $latest->getSignedOnDt()->format('Y-m-d H:i:s') . PHP_EOL;
        }
        if ($oldest) {
            echo 'Oldest: ' . $oldest->getSignedOnDt()->format('Y-m-d H:i:s') . PHP_EOL;
        }

        $added = 0;


        do {
            $query_string = http_build_query($parameters);
            $final_request_url = "$REQUEST_URL?$query_string";

            $attempts = 0;
            $response = '';
            while(true) {
                $response = @file_get_contents($final_request_url);
                if ($response) {
                    break;
                }
                echo 'Fetching signatures page ' . $parameters['page'] . ' Attempt failed...';
                $attempts++;
                if ($attempts >= 3) {
                    echo 'giving up.'.PHP_EOL;
                    break;
                }
                echo 'Attempting again...';
            }

            $json_response = json_decode($response, true);
            foreach($json_response['signatures'] as $signature_data) {
                $signed_on = new \DateTime($signature_data['signed_at'], new \DateTimeZone('UTC'));
                $signed_on->setTimezone(new \DateTimeZone(date_default_timezone_get()));


                if ($latest && $latest->getSignedOnDt() >= $signed_on && $oldest && $oldest->getSignedOnDt() <= $signed_on) {
                    echo 's'; // SSSkipping because we already should have it we guess :)
                    continue;
                }
                if ($latest && $latest->getSignedOnDt() <= $signed_on) {
                    echo 'n'; // adding because NNNewer than our newest
                } elseif ($oldest && $oldest->getSignedOnDt() >= $signed_on) {
                    echo 'o'; // adding because OOOlder than our oldest
                }

                $signature = new ChangeOrgPetitionSignature();
                $signature->setPetition($this);
                $signature->setName($signature_data['name']);
                $signature->setCity($signature_data['city']);
                $signature->setState($signature_data['state_province']);
                $signature->setCountryName($signature_data['country_name']);
                $signature->setCountryCode($signature_data['country_code']);
                $signature->setSignedOnDt($signed_on);
                if ($real_run) {
                    \Base_Controller::$em->persist( $signature );
                    \Base_Controller::$em->flush( $signature );
                }
                $added++;
            }
            $parameters['page']++;

        } while($added < $nr_to_add && $json_response['next_page_endpoint'] !== null);

        echo PHP_EOL.'added ' . $added . ' signatures...';
    }

    /**
     * @return integer
     */
    public function getNewsCount() {
        $CI =& get_instance();
        $CI->db->select('COUNT(*) AS cnt');
        $CI->db->from('change_org_petition_news_update');
        $CI->db->where('petition_id', $this->id);
        $res = $CI->db->get();
        $res = $res->row_array();
        $cnt = $res['cnt'];
        return (int)$cnt;
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return ChangeOrgPetitionNewsUpdate[]
     */
    public function getNewsUpdates($page = 1, $limit = 20) {
        $offset = ($page * $limit) - $limit;
        /** @var ChangeOrgPetitionNewsUpdate[] $updates */
        $updates = ChangeOrgPetitionNewsUpdate::findBy(array('petition_id' => $this->id), array('created_on' => 'desc'), $limit, $offset);
        return $updates;
    }

    /**
     * @return integer
     */
    public function getReasonCount() {
        $CI =& get_instance();
        $CI->db->select('COUNT(*) AS cnt');
        $CI->db->from('change_org_petition_reason');
        $CI->db->where('petition_id', $this->id);
        $res = $CI->db->get();
        $res = $res->row_array();
        $cnt = $res['cnt'];
        return (int)$cnt;
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return ChangeOrgPetitionReason[]
     */
    public function getReasons($page = 1, $limit = 20) {
        $offset = ($page * $limit) - $limit;

        /** @var ChangeOrgPetitionReason[] $reasons */
        $reasons = ChangeOrgPetitionReason::findBy(array('petition_id' => $this->id), array('created_on' => 'desc'), $limit, $offset);

        return $reasons;
    }

    /**
     * @return integer
     */
    public function getSignaturesCount() {
        $CI =& get_instance();
        $CI->db->select('COUNT(*) AS cnt');
        $CI->db->from('change_org_petition_signature');
        $CI->db->where('petition_id', $this->id);
        $res = $CI->db->get();
        $res = $res->row_array();
        $cnt = $res['cnt'];
        return (int)$cnt;
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return ChangeOrgPetitionSignature[]
     */
    public function getSignatures($page = 1, $limit = 20) {
        $offset = ($page * $limit) - $limit;

        /** @var ChangeOrgPetitionSignature[] $signatures */
        $signatures = ChangeOrgPetitionSignature::findBy(array('petition_id' => $this->id), array('signed_on' => 'desc'), $limit, $offset);

        return $signatures;
    }

    public static function findSphinx($search_text, $tab = false, $offset = 0, $limit = 20) {
        $em = \Base_Controller::$em;

        $data = array();
        $data['current_text'] = $search_text ? $search_text : '';
        $data['current_zip'] = '';

        $cl = new \SphinxClient();

        $cl->SetServer($_SERVER['SERVER_NAME'] == 'giverhub.com' ? 'sphinx' : '127.0.0.1');
        $cl->SetConnectTimeout(5);
        $cl->SetArrayResult(true);

        $cl->SetLimits((int)$offset, (int)$limit, 1000);


        $search = function($tab, $tabName) use($search_text, &$cl, &$em) {
            $sort = $tab['sort'];
            $cl->SetSortMode($sort['mode'], (string)$sort['by']);

            $query_string = \Common::getQueryString($search_text);
            $res = $cl->Query($query_string, 'petition');
            if ($res === false) {
                return array('count' => 0, 'petitions' => array());
            }

            /** @var \Entity\ChangeOrgPetition[] $petitions */
            $petitions = array();

            if (@$res['matches']) {
                foreach ($res['matches'] as $match){
                    $petitions[$match['id']] = $match['id'];
                }

                $qb = $em->createQueryBuilder();
                $qb->select('cop');
                $qb->from('Entity\ChangeOrgPetition', 'cop');
                $qb->where('cop.id IN ('.join(',',$petitions).')');


                /** @var \Entity\ChangeOrgPetition[] $results */
                $results = $qb->getQuery()->getResult();
                foreach($results as $r) {
                    $petitions[$r->getId()] = $r;
                }
            }

            return array(
                'petitions' => $petitions,
                'count' => $res['total']
            );
        };


        $tabs = array(
            'relevance' => array(
                'sort' => array(
                    'by' => "@weight + ((can_be_signed * 100000) + (signature_count / 100) / 100)",
                    'mode' => SPH_SORT_EXPR,
                ),
            ),
            'petition_title' => array(
                'sort' => array(
                    'by' => 'title',
                    'mode' => SPH_SORT_ATTR_ASC,
                ),
            ),
            'signature_count' => array(
                'sort' => array(
                    'by' => 'signature_count',
                    'mode' => SPH_SORT_ATTR_DESC,
                ),
            ),
        );

        if (!$tab) {
            foreach($tabs as $tabName => $tab) {
                $res = $search($tab, $tabName);
                $data['content_'.$tabName] = $res['petitions'];
                $data['result_count'] = $res['count'];
            }
        } else {
            $res = $search($tabs[$tab], $tab);
            $data['content_'.$tab] = $res['petitions'];
            $data['result_count'] = $res['count'];
        }

        return $data;
    }

    public static function findSphinxQuery($query, $offset = 0, $limit = 10, $max_matches = 1000) {
        $em = \Base_Controller::$em;
        $cl = new \SphinxClient();
        $cl->SetServer(GIVERHUB_LIVE ? 'sphinx' : '127.0.0.1');
        $cl->SetConnectTimeout(5);
        $cl->SetArrayResult(true);
        $cl->SetLimits($offset, $limit, $max_matches);

        $cl->SetSortMode(SPH_SORT_ATTR_ASC, 'title');

        $res = $cl->Query($query, 'petition');
        if ($res === false) {
            return ['count' => 0, 'petitions' => []];
        }

        /** @var ChangeOrgPetition[] $petitions */
        $petitions = array();

        if (@$res['matches']) {
            foreach ($res['matches'] as $match){
                $petitions[$match['id']] = $match['id'];
            }

            $qb = $em->createQueryBuilder();
            $qb->select('c');
            $qb->from('Entity\ChangeOrgPetition', 'c');
            $qb->where('c.id IN ('.join(',',$petitions).')');


            /** @var \Entity\ChangeOrgPetition[] $results */
            $results = $qb->getQuery()->getResult();
            foreach($results as $r) {
                $petitions[$r->getId()] = $r;
            }
        }

        return array(
            'petitions' => $petitions,
            'count' => $res['total_found']
        );
    }


    public function getGoalProgress() {
        return min(round(($this->signature_count / $this->goal) * 100), 100);
    }

    public function getFormattedSignaturesVsGoal() {
        return \Common::formatNumber($this->signature_count) . ' / ' . \Common::formatNumber($this->goal);
    }
    /**
     * @param string $profanity_filter
     */
    public function setProfanityFilter($profanity_filter)
    {
        $this->profanity_filter = $profanity_filter;
    }

    /**
     * @return string
     */
    public function getProfanityFilter()
    {
        return $this->profanity_filter;
    }

    /**
     * @param string $featured_text
     */
    public function setFeaturedText($featured_text)
    {
        $this->featured_text = $featured_text;
    }

    /**
     * @return string
     */
    public function getFeaturedText()
    {
        return $this->featured_text;
    }

    /**
     * @param int $is_featured
     */
    public function setIsFeatured($is_featured)
    {
        $this->is_featured = $is_featured;
    }

    /**
     * @return int
     */
    public function getIsFeatured()
    {
        return $this->is_featured;
    }

    /** @var ChangeOrgPetition[] */
    static private $featuredPetitions;

    /**
     * @return ChangeOrgPetition|null
     */
    static public function getFeaturedPetition() {
        if (self::$featuredPetitions === null) {
            self::$featuredPetitions = self::findBy(array('is_featured' => 1));
        }

        if (self::$featuredPetitions) {
            return self::$featuredPetitions[rand(0,count(self::$featuredPetitions)-1)];
        } else {
            return null;
        }
    }

    /**
     * @param int $limit
     *
     * @return ChangeOrgPetition[]
     */
    static public function getFeaturedPetitions($limit = 4) {
        if (self::$featuredPetitions === null) {
            self::$featuredPetitions = self::findBy(array('is_featured' => 1));
        }

        if (self::$featuredPetitions) {
            shuffle(self::$featuredPetitions);
            return array_slice(self::$featuredPetitions, 0, $limit);
        } else {
            return array();
        }
    }

    /** @PrePersist */
    public function onPrePersist()
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    /**
     * @param int $force_update
     */
    public function setForceUpdate($force_update)
    {
        $this->force_update = $force_update;
    }

    /**
     * @return int
     */
    public function getForceUpdate()
    {
        return $this->force_update;
    }

    public function changeGiverhubUrlSlug($giverhub_url_slug) {
        if (!$this->id) {
            throw new \Exception('Need to save petition before saving giverhub_url_slug.');
        }

        if ($this->giverhub_url_slug == $giverhub_url_slug) {
            return;
        }

        /** @var ChangeOrgPetitionUrlHistory $history */
        $history = ChangeOrgPetitionUrlHistory::findOneBy(['giverhub_url_slug' => $this->giverhub_url_slug]);

        if ($history) {
            if ($history->getPetitionId() != $this->id) {
                throw new \Exception('petition url conflict. new slug: ' . $giverhub_url_slug . ' old: ' . $this->giverhub_url_slug . ' petition-id: ' . $this->id . ' history-id: ' . $history->getId());
            }
        } else {
            $history = new ChangeOrgPetitionUrlHistory();
            $history->setPetition($this);
            $history->setGiverhubUrlSlug($this->giverhub_url_slug);
            \Base_Controller::$em->persist($history);
            \Base_Controller::$em->flush($history);
        }

        $this->giverhub_url_slug = $giverhub_url_slug;
    }

    public function getLink($full = false) {
        return '<a class="petition-link" title="'.htmlspecialchars($this->getTitle()).'" href="'.($full ? $this->getGiverhubUrl() : $this->getGiverhubUrl('/')).'">'.htmlspecialchars($this->getTitle()).'</a>';
    }

    public function isRemoved() {
        return (bool)$this->removed;
    }

    public function setRemoved($removed) {
        $this->removed = $removed;
    }

    public function getRemoved() {
        return $this->removed;
    }

    public function hasMedia() {
        return (bool)$this->getImageUrl();
    }

    public function getMediaHtml() {
        return '<img src="'.$this->getImageUrl().'"
                     alt="'.htmlspecialchars($this->getTitle()).'">';
    }

    public function getFullUrl() {
        return $this->getGiverhubUrl();
    }

    static private $_trending;

    /**
     * @return ChangeOrgPetition[]
     */
    static public function _getTrending() {

        if (self::$_trending !== null) {
            return self::$_trending;
        }

        $trendish = self::findBy(['trending' => 1]);
        if ($trendish) {
            self::$_trending = $trendish;
            return self::$_trending;
        }

        $trending_petition_ids = [];

        if (function_exists('apc_fetch') ) { // if the search is empty.. default front page
            $trending_petition_ids = apc_fetch('trending_petitions_ids', $success);
            if (!$success) {
                $trending_petition_ids = [];
            }
        }

        if (!$trending_petition_ids) {
            $CI    =& get_instance();
            $query = $CI->db->query('
              SELECT
                petition_id,
                signed_at
              FROM
                user_petition_signature
              WHERE
                signed_at > date_sub(now(), INTERVAL 7 DAY)
              ORDER BY
                signed_at DESC
            ');

            $rows = $query->result_array();

            $trending = [];
            foreach ($rows as $row) {
                if (!isset( $trending[$row['petition_id']] )) {
                    $trending[$row['petition_id']] = 1000; // count each giverhub signature as 1000 change.org signatures
                } else {
                    $trending[$row['petition_id']] += 1000; // count each giverhub signature as 1000 change.org signatures
                }
            }

            if (count($trending) < 20) {
                $query = $CI->db->query('
                  SELECT
                    petition_id
                  FROM
                    change_org_petition_signature
                  WHERE
                    signed_on > date_sub(now(), INTERVAL 7 DAY)
                  ORDER BY
                    signed_on DESC
                  LIMIT
                    500
                ');
                $rows = $query->result_array();

                foreach ($rows as $row) {
                    if (!isset( $trending[$row['petition_id']] )) {
                        $trending[$row['petition_id']] = 1;
                    } else {
                        $trending[$row['petition_id']] ++;
                    }
                }
            }

            if (count($trending) < 20) {
                $query = $CI->db->query('
                  SELECT
                    petition_id
                  FROM
                    change_org_petition_signature
                  ORDER BY
                    signed_on DESC
                  LIMIT
                    500
                ');
                $rows = $query->result_array();

                foreach ($rows as $row) {
                    if (!isset( $trending[$row['petition_id']] )) {
                        $trending[$row['petition_id']] = 1;
                    } else {
                        $trending[$row['petition_id']] ++;
                    }
                }
            }

            arsort($trending);

            $trending_petition_ids = array_keys(array_slice($trending, 0, 20, true));

            if (function_exists('apc_store')) {
                apc_store('trending_petitions_ids', $trending_petition_ids, 60*60*24*7); // 7 days
            }
        }

        shuffle($trending_petition_ids);

        /** @var ChangeOrgPetition[] $trending */
        $trending = [];
        foreach($trending_petition_ids as $petition_id) {
            $trending[] = self::find($petition_id);
        }

        self::$_trending = $trending;

        return self::$_trending;
    }
}